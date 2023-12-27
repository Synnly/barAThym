
<?php session_start(); ?>
<?php 
    include "../configBD.php"; 

    // Connexion
    $mysqli=mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);

    // Requete pour afficher les sous categories de l'ingredient
    $sql1="SELECT sousCategorie FROM Aliments WHERE titreAliment='".mysqli_escape_string($mysqli,$_GET['ingredient'])."';";
    $resultat1=mysqli_query($mysqli,$sql1) or die("$sql1 : ".mysqli_error($mysqli));
    
    while($row1 = $resultat1->fetch_row()){     // Parcours des resultats de la requete
        
        $categories = explode(",", $row1[0]);
        $html = "";

        foreach($categories as $categorie){     // Parcours des sous categories

            // Requete pour savoir si la sous categorie a une sous categorie 
            $sql2 = "SELECT sousCategorie FROM Aliments WHERE titreAliment='".mysqli_escape_string($mysqli,$categorie)."';";
            $resultat2=mysqli_query($mysqli,$sql2) or die("$sql2 : ".mysqli_error($mysqli));
            $html .= "<p";

            $html .= " onclick=\"refreshHierarchie('".mysqli_escape_string($mysqli,$categorie)."')\"";

            $html .= ">".$categorie."</p>";
        }
    }

    echo $html;

    $mysqli->close();
?>