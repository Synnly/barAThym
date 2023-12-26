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
                <div id="champsRecherche">
                    <label for="rechercheContient">Ingrédients à inclure</label>
                    <div class="inputRecherche">
                        <input type="text" list="listeContient" id="rechercheContient">
                        <button onclick="remplirListeContient('rechercheContient')">Inclure</button>
                        <button onclick="resetListe('inclureIngredients')">Reset</button>
                    </div>
                    <ul></ul>

                    <label for="rechercheNeContientPas">Ingrédients à exclure</label>
                    <div class="inputRecherche">
                        <input type="text" list="listeNeContientPas" id="rechercheNeContientPas">
                        <button onclick="remplirListeContient('rechercheNeContientPas')">Exclure</button>
                        <button onclick="resetListe('exclureIngredients')">Reset</button>
                    </div>
                    <ul></ul>
                </div>
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