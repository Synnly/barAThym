<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/stylePanier.css">
    <script type="text/javascript" src="fonctions.js"></script>
</head>
<body>
    <header>
        <?php
            include "configBD.php";
            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                echo "<p>Bienvenue ".$_POST['login']."</p>
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

            try{
                $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
            }
            catch(Exception $e){
                exit($e->getMessage());
            }

            // Requete des boissons dans le panier
            $sql="SELECT b.* FROM Panier p, Boisson b WHERE p.login = ? and b.titreBoisson = p.titreBoisson;";
            $resultat = $pdo->prepare($sql);

            // Affichage du login s'il est connecté
            if(isset($_COOKIE['login'])){
                $resultat->execute([$_COOKIE['login']]);
            }
            elseif(isset($_POST['login'])){
                $resultat->execute([$_POST['login']]);
            }

            echo "<table><tr>";
            $compteur = 0;

            foreach ($resultat as $row){
                if($compteur % 3 == 0 && $compteur > 0) echo "</tr><tr>";
                echo "<td>";

                // Transformation du titre de la boisson
                $titreBoisson = preg_replace("/\s/", "_", $row['titreBoisson']);
                $titreBoisson = "Photos/".$titreBoisson.".jpg";

                // Affichage de l'entete
                echo "<div class=\"boissonEnTete\">";

                    //Affichage de l'image
                if(fopen($titreBoisson, 'r')){
                    echo "<img src=\"$titreBoisson\">";
                }
                else{
                    echo "<img src=\"Photos/glass.png\">";
                }

                echo "<div class=\"boissonEnTeteTexte\">
                        <p>".$row['titreBoisson']."</p>
                        <button>Retirer</button>
                    </div>
                    </div>";

                // Affichage des ingrédients
                echo "<br/>Ingrédients : <ul>";
                foreach(explode("|", $row['ingredients']) as $ingredient){
                    echo "<li>$ingredient</li>";
                }
                echo "</ul>";  
                
                // Affichage de la recette
                echo "Préparation : <br>".$row['preparation'];
                echo "</td>";   
                $compteur++;
            }

            echo "</tr></table>";
            $pdo = NULL;
        ?>
    </main>
</body>
</html>