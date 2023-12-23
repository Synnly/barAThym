<?php session_start(); ?>
<?php

    include_once "../configBD.php";

    // Creation des tableaux de session
    if(!isset($_SESSION['inclureIngredients'])) $_SESSION['inclureIngredients'] = array();
    if(!isset($_SESSION['exclureIngredients'])) $_SESSION['exclureIngredients'] = array();

    // Ajout de l'ingrédient dans la liste correspondante
    if(!$_GET['inclure']=="") array_push($_SESSION['inclureIngredients'], $_GET['inclure']);
    if(!$_GET['exclure']=="") array_push($_SESSION['exclureIngredients'], $_GET['exclure']);

    echo $_GET['exclure'];
    print_r($_SESSION['exclureIngredients']);

    // Initialisations
    $categorie = $_SESSION['filAriane'][count($_SESSION['filAriane'])-1];
    $listeSousCategoriesInclure = array();
    $listeSousCategoriesExclure = array();
    $aVisiter = [$categorie];

    try{
        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
    }
    catch(Exception $e){
        exit($e->getMessage());
    }

    $sql = "SELECT sousCategorie FROM Aliments WHERE titreAliment = ?";
    $resultat = $pdo->prepare($sql);

    // Parcours de toutes les sous-catégories
    while(count($aVisiter) > 0) {
        $resultat->execute([$aVisiter[0]]);

        foreach($resultat as $row){

            // Ajout des éléments si l'aliment actuel a une sous-catégorie
            if($row[0] != NULL){
                $categories = explode(',', $row[0]);

                // Ajout des sous-catégories à la liste s'ils n'y sont pas déjà et s'ils ne sont pas déjà inclus ou exclus
                foreach ($categories as $element){

                    // Si l'ingredient a déjà été inclus, on le rajoute quand meme aux possibilités d'exclusion ses sous-catégories
                    if(!in_array($element, $listeSousCategoriesInclure) && !in_array($element, $_SESSION['inclureIngredients']) && !in_array($element, $_SESSION['exclureIngredients'])) array_push($listeSousCategoriesInclure, $element);
                    if(!in_array($element, $listeSousCategoriesExclure) && !in_array($element, $_SESSION['inclureIngredients']) && !in_array($element, $_SESSION['exclureIngredients'])) array_push($listeSousCategoriesExclure, $element);
                    if(!in_array($element, $aVisiter)) array_push($aVisiter, $element);
                }
            }
        }

        $aVisiter = array_slice($aVisiter, 1, count($aVisiter)-1);
    }


    // TODO : Enlever de la datalist des inclus les sous categories des ingredients deja inclus

    // Remplissage de la datalist des ingrédients pouvant etre inclus
    echo "<div id=\"datalist\"><datalist id=\"listeContient\">";
    foreach ($listeSousCategoriesInclure as $categorie){
        echo "<option>$categorie</option>";
    }
    echo "</datalist>";

    // Remplissage de la datalist des ingrédients pouvant etre exclus
    echo "<datalist id=\"listeNeContientPas\">";
    foreach ($listeSousCategoriesExclure as $categorie){
        echo "<option>$categorie</option>";
    }
    echo '</datalist></div>';

    echo "<div id=\"champsRecherche\"><label for=\"rechercheContient\">Ingrédients à inclure</label>
    <div class=\"inputRecherche\">
        <input type=\"text\" list=\"listeContient\" id=\"rechercheContient\">
        <button onclick=\"remplirListeContient('rechercheContient')\">Inclure</button>
        <button onclick=\"resetListe('inclureIngredients')\">Reset</button>
        </div><ul>";

    foreach ($_SESSION['inclureIngredients'] as $categorie){
        echo "<li>$categorie</li>";
    }

    echo "</ul>
    <label for=\"rechercheNeContientPas\">Ingrédients à exclure</label>
    <div class=\"inputRecherche\">
        <input type=\"text\" list=\"listeNeContientPas\" id=\"rechercheNeContientPas\">
        <button onclick=\"remplirListeContient('rechercheNeContientPas')\">Exclure</button>
        <button onclick=\"resetListe('exclureIngredients')\">Reset</button>
    </div><ul>";

    foreach ($_SESSION['exclureIngredients'] as $categorie){
        echo "<li>$categorie</li>";
    }

echo "</ul></div>";

    // TODO : Reremplir les listes apres appel
    // TODO : Requete correspondance 100%
    // TODO : Requete correspondance <100%

?>