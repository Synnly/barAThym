<?php

    /**
     * Affiche le header de la page d'accueil
     * @param $login string Le login de l'utilisateur
     * @param $connecte boolean True si l'utilisateur s'est connecté, false sinon
     * @return void
     */
    function afficherHeader($login, $connecte){
        echo "<table id=\"header\"><tr>
                    <td><div id=\"headerGauche\">";

        if($connecte){  // Si connecté, affichage du login
            echo "<p>Bienvenue <b>$login</b></p>";
        }
        else{           // Sinon, affichage du formulaire de connection
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
                <td><div id=\"headerDroite\">";

        if($connecte) echo "<button onClick=\"deconnecter()\">Deconnection</button>";

        echo "<form action=\"panier.php\">
                        <button type=\"submit\">Panier</button> 
                    </form>
                </div></td>
            </tr></table>";
    }

    /**
     * Affiche le header de la page du panier
     * @param $login string Le login de l'utilisateur
     * @param $connecte boolean True si l'utilisateur s'est connecté, false sinon
     * @return void
     */
    function afficherHeaderPanier($login, $connecte){
        echo "<table id=\"header\"><tr>
                    <td><div id=\"headerGauche\">";

        if($connecte){  // Si connecté, affichage du login
            echo "<p>Bienvenue <b>$login</b></p>";
        }
        else{           // Sinon, affichage du formulaire de connection
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
                <td><div id=\"headerDroite\">";
                
        if($connecte) echo "<button onClick=\"deconnecter()\">Deconnection</button>";

        echo "<form action=\"index.php\">
                        <button type=\"submit\">Accueil</button> 
                    </form>
                </div></td>
            </tr></table>";
    }

?>