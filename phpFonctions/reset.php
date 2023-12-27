<?php session_start(); ?>
<?php

    include "../configBD.php";

    if($_GET['id'] == "rechercheContient"){
        unset($_SESSION['inclureIngredients']);
    }

    elseif($_GET['id'] == "rechercheNeContientPas"){
        unset($_SESSION['exclureIngredients']);
    }
?>
