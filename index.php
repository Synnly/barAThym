<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="style.css">
    <link rel="import" href="fonctions.js">
</head>
<body>
    <header>
        <?php

            include "configBD.php";
            if($_GET['submitConnect'] == "Connexion"){  // Demande de connexion envoyée

                $mysqli=mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);
                $sql="SELECT * FROM Utilisateurs WHERE login='".$_GET['login']."' AND password='".$_GET['password']."';";
                $resultat=mysqli_query($mysqli,$sql) or die("$sql : ".mysqli_error($mysqli));

                setcookie("login", $_GET['login'], time()+3600);

                if($resultat->num_rows == 1){    // Connexion réussie
                    echo "<p>Bienvenue</p>";
                }
                else{
                    echo "<p>Erreur de connexion</p>";
                }
            }
            else{
                echo "<form method=\"get\">
                        <legend>Login</legend>
                        <input type=\"text\" name=\"login\">
                        <legend>Mot de passe</legend>
                        <input type=\"password\" name=\"password\">
                        <input type=\"submit\" value=\"Connexion\" name=\"submitConnect\">
                    </form>
                    <form action=\"creerCompte.php\">
                        <button type=\"submit\">Créer un compte</button>
                    </form>";
            }

        ?>
    </header>
</body>
</html>