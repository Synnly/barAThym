<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/stylePanier.css">
    <script type="text/javascript" src="fonctionsPanier.js"></script>
</head>
<body>
    <header>
        <?php
            include "configBD.php";
            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                echo "<p>Bienvenue ".$_COOKIE['login']."</p>
                        <form action=\"index.php\" id=\"panierForm\">
                            <button type=\"submit\">Accueil</button> 
                        </form>";
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
                        echo "<p>Bienvenue ".$_POST['login']."</p>
                                <form action=\"index.php\" id=\"panierForm\">
                                    <button type=\"submit\">Accueil</button> 
                                </form>";
                        setcookie("login", $_POST['login'], time()+3600);
                    }
                    else{
                        echo "<p>Erreur de connexion</p>";
                    }
                    unset($_POST['submitConnect']);
                }
                else{                                               // Affichage du formulaire de connection
                    echo "<form method=\"post\" id=\"loginForm\">
                            <legend>Login</legend>
                            <input type=\"text\" name=\"login\">
                            <legend>Mot de passe</legend>
                            <input type=\"password\" name=\"password\">
                            <input type=\"submit\" value=\"Connexion\" name=\"submitConnect\">
                        </form>
                        <form action=\"creerCompte.php\" id=\"signinForm\">
                            <button type=\"submit\">Créer un compte</button>
                        </form>
                        <form action=\"index.php\" id=\"panierForm\">
                            <button type=\"submit\">Accueil</button> 
                        </form>";
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