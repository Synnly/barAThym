<?php session_start(); ?>
<?php
    include "../configBD.php"; 
    $html = "";

    // Connexion
    $mysqli=mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);

    // Remplissage du fil d'arianne
    if(isset($_SESSION['filAriane'])){
        $filAriane = $_SESSION['filAriane'];
        if(array_search($_GET['ingredient'], $filAriane) === false){
            array_push($filAriane, $_GET['ingredient']);
        }
        else{
            $index = array_search($_GET['ingredient'], $filAriane);
            $filAriane = array_slice($filAriane, 0, $index+1);
        }
    }
    else{
        $filAriane = [$_GET['ingredient']];
    }
    $_SESSION['filAriane'] = $filAriane;

    // Affichage du fil d'arianne
    $html = "<p>";
    foreach($_SESSION['filAriane'] as $noeud){    // Parcours du fil d'arianne

        foreach(explode(",", $noeud) as $categorie){   // Parcours des categories
            if($categorie != "") $html .= "<a onClick=\"refreshHierarchie('".addslashes($categorie)."')\">".$categorie."</a>, ";
        }
        $html = substr($html, 0, -2); // Suppression de la derniere virgule
        $html .= " > ";
    }

    $html = substr($html, 0, -3);
    echo $html."</p>";

    $mysqli->close();
?>