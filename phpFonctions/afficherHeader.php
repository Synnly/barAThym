<?php

    function afficherHeader($login, $connecte){
        echo "<table id=\"header\"><tr>
                    <td><div id=\"headerGauche\">";

        if($connecte){  // Si connecté, affichage du login
            echo "<p>Bienvenue $login</p>";
        }
        else{   // Sinon, affichage du formulaire de connection
            echo "<form method=\"post\" id=\"loginForm\">
                    <legend>Login</legend>
                    <input type=\"text\" name=\"login\">
                    <legend>Mot de passe</legend>
                    <input type=\"password\" name=\"password\">
                    <input type=\"submit\" value=\"Connexion\" name=\"submitConnect\">
                </form>
                <form action=\"creerCompte.php\" id=\"signinForm\">
                    <button type=\"submit\">Créer un compte</button>
                </form>";
        }
        echo "</div></td>
            <td><div id=\"headerDroite\">
                <form action=\"panier.php\" id=\"panierForm\">
                    <button type=\"submit\">Panier</button> 
                </form>
            </div></td>
            </tr></table>";
    }

    function afficherHeaderPanier($login, $connecte){
        echo "<table id=\"header\"><tr>
                    <td><div id=\"headerGauche\">";

        if($connecte){  // Si connecté, affichage du login
            echo "<p>Bienvenue $login</p>";
        }
        else{   // Sinon, affichage du formulaire de connection
            echo "<form method=\"post\" id=\"loginForm\">
                    <legend>Login</legend>
                    <input type=\"text\" name=\"login\">
                    <legend>Mot de passe</legend>
                    <input type=\"password\" name=\"password\">
                    <input type=\"submit\" value=\"Connexion\" name=\"submitConnect\">
                </form>
                <form action=\"creerCompte.php\" id=\"signinForm\">
                    <button type=\"submit\">Créer un compte</button>
                </form>";
        }
        echo "</div></td>
            <td><div id=\"headerDroite\">
                <form action=\"index.php\" id=\"panierForm\">
                    <button type=\"submit\">Accueil</button> 
                </form>
            </div></td>
            </tr></table>";
    }

?>