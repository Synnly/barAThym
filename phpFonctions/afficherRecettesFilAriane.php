<?php session_start() ?>
<?php
    
    include "../configBD.php";
    include "afficherRecettes.php";

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
    while( ($alimentActuel = array_pop($Avisiter))){
        //Echappement des guillemets
        $titreAliment = mysqli_escape_string($mysqli,$alimentActuel);
        $temp=[];

        //Requete
        $query = "SELECT sousCategorie FROM Aliments WHERE titreAliment = '$titreAliment'";
        $resultat = $mysqli->query($query);
        $nuplet = $resultat->fetch_row();

        //Si la sous catégorie possède des sous catégories
        if($nuplet[0] != null){
            //Séparation des sous catégories
            $sousCatAct = explode(",",$nuplet[0]);

            //Ajout dans la liste des aliments à visiter
            foreach($sousCatAct as $sousCat) $Avisiter[] = $sousCat;

        }else{
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

    afficherRecettes($recettes, $login, null);
?>