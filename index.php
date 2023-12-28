<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/style.css">
    <script type="text/javascript" src="jsFonctions/fonctions.js"></script>
</head>
<body>
    <header>
        <?php
            include "phpFonctions/afficherHeader.php";
            include "configBD.php";

            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                afficherHeader($_COOKIE['login'], true);
                unset($_POST['submitConnect']);
            }
            else{
                if($_POST['submitConnect'] == "Connexion"){  // Demande de connexion envoyée

                    // Requete du mot de passe
                    try{
                        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
                    }
                    catch(Exception $e){
                        exit($e->getMessage());
                    }

                    $sql="SELECT password FROM Utilisateurs WHERE login = ? ;";
                    $resultat = $pdo->prepare($sql);
                    $resultat->execute([$_POST['login']]);
                    foreach ($resultat as $row){
                        $pswd = $row[0];
                    }

                    // Verification du mot de passe
                    if (password_verify($_POST['password'], $pswd)){
                        afficherHeader($_POST['login'], true);
                        setcookie("login", $_POST['login'], time()+3600);
                    }
                    else{
                        echo "<p>Erreur de connexion</p>
                                <form action=\"index.php\">
                                    <button type=\"submit\">Accueil</button> 
                                </form>";
                    }
                    unset($_POST['submitConnect']);
                }
                else {                                               // Affichage du formulaire de connection
                    afficherHeader("", false);
                }
            }
        ?>
    </header>
    <div class="main">
        <nav>
            <div id="nav">
                <div id="filAriane"></div>
                <div id="hierarchie"></div>
            </div>
            <div id="recherche">
                <div id="datalist"></div>
                <div id="champsRecherche"></div>
            </div>
        </nav>
        <main>
            <?php
                echo "<div id=\"recettes\"></div>";
                echo "<script>afficherRecettes();</script>"
            ?>
    </div>
</body>
</html>