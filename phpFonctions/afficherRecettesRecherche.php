<?php session_start() ?>
<?php

    include "../configBD.php";
    include "afficherRecettes.php";

    /**
     * Renvoie le taux d'ingrédients inclus dans $ingrédients par rapport au nombre d'ingrédients de la boisson
     * @param string $titreBoisson La boisson
     * @param array $ingredients La liste des ingrédients
     * @return float Le taux de correspondance (entre 0 et 1)
     */
    function getPrctCorrespondance($titreBoisson, $ingredients){
        include "../configBD.php";
        $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);
        $query = "SELECT titreAliment FROM Contient WHERE titreBoisson ='".$mysqli->escape_string($titreBoisson)."'";
        $resultat = $mysqli->query($query);

        $nbIngredients = 0;
        $totalIngredients = $mysqli->affected_rows;
        while($row = $resultat->fetch_row()){
            if(in_array($row[0], $ingredients)) $nbIngredients+=1;
        }
        mysqli_close($mysqli);
        return fdiv($nbIngredients, $totalIngredients);
    }

    function cmp($arg1, $arg2){
        if($arg1[1] == $arg2[1]) return 0;
        return ($arg1[1] > $arg2[1] ? -1 : 1);
    }

    // Initialisations
    $dernierAliment = isset($_SESSION['filAriane']) ? end($_SESSION['filAriane']) : 'Aliment';
    if(isset($_SESSION['filAriane'])) reset($_SESSION['filAriane']);
    $Avisiter = [$dernierAliment];
    $recettes=[];
    $sousCatAct=[];

    // Recuperation du login
    if(isset($_COOKIE['login'])){
        $login = $_COOKIE['login'];
    }
    elseif(isset($_POST['login'])){
        $login = $_POST['login'];
    }
    else{
        $login = '';
    }

    $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);

    //Remplissage du tableau des recettes à afficher
    while(($alimentActuel = array_pop($Avisiter))){

        //Echappement des guillemets
        $titreAliment = mysqli_escape_string($mysqli,$alimentActuel);
        $temp=[];

        //Requete
        $query = "SELECT sousCategorie FROM Aliments WHERE titreAliment = '$titreAliment'";
        $resultat = $mysqli->query($query);
        $nuplet = $resultat->fetch_row();

        //Si sous possède des sous catégories
        if($nuplet[0] != null){
            //Séparation des sous catégories
            $sousCatAct = explode(",",$nuplet[0]);

            //Ajout dans la liste des aliments à visiter
            foreach($sousCatAct as $sousCat) $Avisiter[] = $sousCat;
        }
        else{
            $recettesTemp=[];
            //Requete
            $query = "SELECT B.* FROM Boisson B,Contient C WHERE titreAliment = '$titreAliment' AND C.titreBoisson = B.titreBoisson";
            $resultat = $mysqli->query($query);

            //Parcours du resultat
            while($nuplet = $resultat->fetch_assoc()){
                $recettesTemp[] = $nuplet;
            }

            //On vérifie si les recettes ajoutées sont dejà dans les recettes affichées
            foreach($recettesTemp as $recette){
                $dejaPresent = false;
                if(sizeof($recettes)>0){
                    foreach($recettes as $boisson){
                        if($recette['titreBoisson'] == $boisson['titreBoisson']) $dejaPresent=true;
                    }
                }
                if(!$dejaPresent) $recettes[] = $recette;
            }
        }
    }
    mysqli_close($mysqli);


    // Filtrage des ingrédients exclus
    try{
        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
    }
    catch(Exception $e){
        exit($e->getMessage());
    }

    $sql = "SELECT sousCategorie, titreAliment FROM Aliments WHERE titreAliment = ?";
    $resultat = $pdo->prepare($sql);

    $aVisiter = count($_SESSION['inclureIngredients']) > 0 ? $_SESSION['inclureIngredients'] : ['Aliment'];
    $categoriesInclues = array();

    // Ingredients à inclure
    while(count($aVisiter) > 0) {
        $resultat->execute([$aVisiter[0]]);

        foreach($resultat as $row){

            // Ajout des éléments si l'aliment actuel a une sous-catégorie
            if($row[0] != NULL){
                $categories = explode(',', $row[0]);

                // Ajout des sous-catégories à la liste s'ils n'y sont pas déjà et s'ils ne sont pas déjà inclus ou exclus
                foreach ($categories as $element) {
                    if (!in_array($element, $aVisiter)) $aVisiter[] = $element;
                }
            }
        }
        $categoriesInclues[] = array_shift($aVisiter);
    }

    $aVisiter = $_SESSION['exclureIngredients'];
    $categoriesExclues = array();

    // Ingrédients à exclure. Executé après les ingrédients à inclure pour que l'exclusion ait la priorité
    while(count($aVisiter) > 0) {
        $resultat->execute([$aVisiter[0]]);

        foreach($resultat as $row){
            $categories = explode(',', $row[0]);

            // Ajout des sous categories à la liste de visite
            foreach ($categories as $element) {
                if (!in_array($element, $aVisiter)) $aVisiter[] = $element;
            }

            // Retrait des sous categories exclues
            if(in_array($aVisiter[0], $categoriesInclues)){
                $index = array_search($aVisiter[0], $categoriesInclues);
                array_splice($categoriesInclues, $index, 1);
            }

        }
        $categoriesExclues[] = array_shift($aVisiter);
    }

    // On recupere les recettes uniquement s'il y a des ingrédients inclus
    if(count($categoriesInclues) > 0) {
        $correspondanceRecette = array();

        // Creation de la requete
        $mysqli = mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);
        $sql = "SELECT DISTINCT B.* FROM Boisson B, Contient C WHERE C.titreBoisson = B.titreBoisson AND (";

        // Inclusion des ingredients
        foreach ($categoriesInclues as $categorie) {
            $sql .= "titreAliment = '". mysqli_escape_string($mysqli, $categorie)."' OR ";
        }
        $sql = substr($sql, 0, -4).")";

        // Exclusion des ingredients
        if(count($categoriesExclues) > 0){
            $sql .= " AND B.titreBoisson NOT IN (SELECT titreBoisson FROM Contient WHERE (";
            foreach ($categoriesExclues as $categorie) {
                if($categorie != '') $sql .= "titreAliment = '".mysqli_escape_string($mysqli, $categorie)."' OR ";
            }
            $sql = substr($sql, 0, -4)."))";
        }
        $resultat = $mysqli->query($sql);

        // Creation de la table
        while($row = $resultat->fetch_assoc()){
            $correspondanceRecette[] = array(0 => $row, 1 => getPrctCorrespondance($row['titreBoisson'], $categoriesInclues));
        }
        usort($correspondanceRecette, "cmp");
    }

    // Affichage des boissons
    $listeBoissons = array();
    $listePrct = array();
    foreach ($correspondanceRecette as $boisson) {
        $listeBoissons[] = $boisson[0];
        $listePrct[] = $boisson[1];
    }

    afficherRecettes($listeBoissons, $login, $listePrct);
?>