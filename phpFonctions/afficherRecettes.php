<?php session_start() ?>
<?php
    
    include "../configBD.php";
    $dernierAliment = isset($_SESSION['filAriane']) ? end($_SESSION['filAriane']) : 'Aliment';
    if(isset($_SESSION['filAriane'])) reset($_SESSION['filAriane']);
    $Avisiter = [$dernierAliment];
    $recettes=[];
    $alimentActuel;
    $sousCatAct=[];

    // Recuperation du login
    if(isset($_COOKIE['login'])){
        $login = $_COOKIE['login'];
    }
    elseif(isset($_POST['login'])){
        $login = $_POST['login'];
    }

    //Remplissage du tableau des recettes à afficher
    while( ($alimentActuel = array_pop($Avisiter))){
        //Echappement des guillemets
        $titreAliment = addslashes($alimentActuel);
        $temp=[];
        $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);

        
        //Requete
        $query = "SELECT sousCategorie FROM Aliments WHERE titreAliment = '$titreAliment'";
        $resultat = $mysqli->query($query);
        
        $nuplet = $resultat->fetch_row();
        //Si sous possède des sous catégories
        if($nuplet[0] != null){
            //Séparation des sous catégories
            $sousCatAct = explode(",",$nuplet[0]);

            //Ajout dans la liste des aliments à visiter
            foreach($sousCatAct as $sousCat){
                array_push($Avisiter,$sousCat);
            } 
            
        }else{
            $recettesTemp=[];
            //Requete
    
            $query = "SELECT B.* FROM Boisson B,Contient C WHERE titreAliment = '$titreAliment' AND C.titreBoisson = B.titreBoisson";
            $resultat = $mysqli->query($query);
            //Parcours du resultat
            while($nuplet = $resultat->fetch_assoc()){
                array_push($recettesTemp,$nuplet);
            
            }
            //On vérifie si les recettes ajoutées sont dejà dans les recettes affichées
            foreach($recettesTemp as $recette){
                $dejaPresent = false;
                if(sizeof($recettes)>0){
                    foreach($recettes as $boisson){
                        if($recette['titreBoisson'] == $boisson['titreBoisson']) $dejaPresent=true;
                    }
                }
                if(!$dejaPresent) array_push($recettes,$recette);
            }
        }
    }
    $coupable;
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
    mysqli_close($mysqli);
?>