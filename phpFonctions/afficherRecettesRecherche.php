<?php session_start() ?>
<?php

    include "../configBD.php";

    /**
     * Renvoie le taux de correspondance entre les ingrédients dans $ingredients et les ingrédients de la boisson
     * @param string $titreBoisson La boisson
     * @param string $ingredients La liste des ingrédients
     * @return float Le taux de correspondance (entre 0 et 1)
     */
    function getPrctCorrespondance($titreBoisson, $ingredients){
        include "../configBD.php";
        $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);
        $query = "SELECT titreAliment FROM Contient WHERE titreBoisson ='$titreBoisson'";
        $resultat = $mysqli->query($query);

        $nbIngredients = 0;
        $totalIngredients = $mysqli->affected_rows;
        while($row = $resultat->fetch_row()){
            if(in_array($row[0], $ingredients)) $nbIngredients+=1;
        }
        mysqli_close($mysqli);
        return fdiv($nbIngredients, $totalIngredients);
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

    $aVisiter = $_SESSION['inclureIngredients'];
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
            else{
                $categoriesInclues[] = $row[1];
            }
        }
        array_shift($aVisiter);
    }

    $aVisiter = $_SESSION['exclureIngredients'];

    // Ingrédients à exclure. Executé après les ingrédients à inclure pour que l'exclusion ait la priorité
    while(count($aVisiter) > 0) {
        echo $aVisiter[0]."<br>";
        $resultat->execute([$aVisiter[0]]);

        foreach($resultat as $row){
            $categories = explode(',', $row[0]);

            foreach ($categories as $element) {
                if (!in_array($element, $aVisiter)) $aVisiter[] = $element;
            }

            // Retrait des sous categories exclues
            if(in_array($aVisiter[0], $categoriesInclues)){
                $index = array_search($aVisiter[0], $categoriesInclues);
                array_splice($categoriesInclues, $index, 1);
            }
        }
        array_shift($aVisiter);
    }

    //
    if(count($categoriesInclues) > 0) {
        $correspondanceRecette = array();

        // Creation de la requete
        $mysqli = mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);
        $sql = "SELECT DISTINCT B.* FROM Boisson B, Contient C WHERE C.titreBoisson = B.titreBoisson AND (";

        foreach ($categoriesInclues as $categorie) {
            $sql .= "titreAliment = '". mysqli_escape_string($mysqli, $categorie)."' OR ";
        }
        $sql = substr($sql, 0, -4).")";

        $resultat = $mysqli->query($sql);

        while($row = $resultat->fetch_row()){
            $correspondanceRecette[] = array(0 => $row, 1 => getPrctCorrespondance($row[0], $categoriesInclues));
        }
    }















    echo '<table>';
    echo '<tr>';
    $compteur = 0;

    //Affichage des recettes
    foreach($recettes as $boisson){
        if($compteur % 3 == 0 && $compteur != 0) echo '</tr><tr>';
        echo '<td><div class = "boisson">';
        $titrePhoto = $boisson['titreBoisson'];
        //ON RETIRE LES ACCENTS ET LES ESPACES PARCE QUE SINON CA MARCHE PAS + ON AJOUTE .JPG
        $titrePhoto = iconv('UTF-8', 'ASCII//TRANSLIT', $titrePhoto);
        $titrePhoto = preg_replace('/\s/', '_', $titrePhoto);
        $titrePhoto = $titrePhoto.'.jpg';

        if(!($photo = fopen("Photos/".$titrePhoto,"r"))){
            $titrePhoto = "glass.png";
        }
        //Affichage de l'image
        echo '<img src="Photos/'.$titrePhoto.'"/>
            <button onClick="ajouterBoissonPanier(\''.$login.'\',\''.$boisson['titreBoisson'].'\')">Ajouter Favoris</button>';
        echo '<br>';

        //Affichage du titre de la boisson
        echo $boisson['titreBoisson'];
        echo '<br><br>';

        //Affichage des ingredients
        echo 'Ingrédients :<ul>';
        foreach(explode('|',$boisson['ingredients']) as $ingredient){
            echo '<li>'.$ingredient.'</li>';
        }
        echo '</ul><br>';

        //Affichage de la préparation
        echo $boisson['preparation'];
        echo '<br>';

        $compteur++;
        echo '</div></td>';

    }
    echo'</tr>';
    echo '</table>';

?>