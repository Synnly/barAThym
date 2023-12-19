<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/stylePanier.css">
    <script type="text/javascript" src="jsFonctions/fonctionsPanier.js"></script>
    <script type="text/javascript" src="jsFonctions/fonctions.js"></script>
</head>
<body>
    <header>
        <?php
            include "phpFonctions/afficherHeader.php";
            include "configBD.php";

            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                afficherHeaderPanier($_COOKIE['login'], true);
            }
            else{
                if($_POST['submitConnect'] == "Connexion"){  // Demande de connexion envoyée

                    try{
                        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
                    }
                    catch(Exception $e){
                        exit($e->getMessage());
                    }
                   
                    // Requete du mot de passe
                    $sql="SELECT password FROM Utilisateurs WHERE login = ? ;";
                    $resultat = $pdo->prepare($sql);
                    $resultat->execute([$_POST['login']]);
                    foreach ($resultat as $row){
                        $pswd = $row[0];
                    } 

                    if (password_verify($_POST['password'], $pswd)){   // Le mot de passe correspond
                        afficherHeaderPanier($_POST['login'], true);
                        setcookie("login", $_POST['login'], time()+3600);
                    }
                    else{
                        echo "<p>Erreur de connexion</p>";
                    }
                    unset($_POST['submitConnect']);
                }
                else{                                               // Affichage du formulaire de connection
                    afficherHeaderPanier("", false);
                }
            }
        ?>
    </header>
    <main>
        <?php

            if(isset($_COOKIE['login'])){
                $login = $_COOKIE['login'];
            }
            elseif(isset($_POST['login'])){
                $login = $_POST['login'];
            }

            echo "<div id=\"panier\"></div>";
            echo "<script>afficherPanier('$login');</script>"
        ?>
    </main>
</body>
</html>