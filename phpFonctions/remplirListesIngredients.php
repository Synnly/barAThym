<?php session_start(); ?>
<?php

    include_once "../configBD.php";

    // Creation des tableaux de session
    if(!isset($_SESSION['inclureIngredients'])) $_SESSION['inclureIngredients'] = array();
    if(!isset($_SESSION['exclureIngredients'])) $_SESSION['exclureIngredients'] = array();

    // Ajout de l'ingrédient dans la liste correspondante
    if(!$_GET['inclure']=="") $_SESSION['inclureIngredients'][] = $_GET['inclure'];
    if(!$_GET['exclure']=="") $_SESSION['exclureIngredients'][] = $_GET['exclure'];

    // Initialisations
    $categorie = $_SESSION['filAriane'][count($_SESSION['filAriane'])-1];
    $listeIngredientsInclure = array();
    $listeIngredientsExclure = array();
    $listeCategoriesExclues = $_SESSION['exclureIngredients'];
    $aVisiter = [$categorie];

    // Requete
    try{
        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
    }
    catch(Exception $e){
        exit($e->getMessage());
    }

    $sql = "SELECT sousCategorie, titreAliment FROM Aliments WHERE titreAliment = ?";
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

                    // Sous categories exclues
                    if (in_array($row[1], $listeCategoriesExclues)) $listeCategoriesExclues[] = $element;

                    if(!in_array($element, $_SESSION['inclureIngredients']) && !in_array($element, $_SESSION['exclureIngredients']) && !in_array($element, $listeCategoriesExclues)){
                        if(!in_array($element, $listeIngredientsInclure)) $listeIngredientsInclure[] = $element;
                        if(!in_array($element, $listeIngredientsExclure)) $listeIngredientsExclure[] = $element;
                    }

                    // Si l'ingrédient a déjà été inclus, on le rajoute quand meme aux possibilités d'exclusion ses sous-catégories
                    if(!in_array($element, $aVisiter)) $aVisiter[] = $element;
                }
            }
        }
        $aVisiter = array_slice($aVisiter, 1, count($aVisiter)-1);
    }

    // Remplissage de la datalist des ingrédients pouvant etre inclus
    echo "<div id=\"datalist\"><datalist id=\"listeContient\">";
    foreach ($listeIngredientsInclure as $categorie){
        echo "<option>$categorie</option>";
    }
    echo "</datalist>";

    // Remplissage de la datalist des ingrédients pouvant etre exclus
    echo "<datalist id=\"listeNeContientPas\">";
    foreach ($listeIngredientsExclure as $categorie){
        echo "<option>$categorie</option>";
    }
    echo '</datalist></div>';

?>