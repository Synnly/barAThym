<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="fonctions.js"></script>
</head>
<body>
    <header>
        <?php
            include "configBD.php";
            if(isset($_COOKIE['login'])){                       // Utilisateur connecté
                echo "<p>Bienvenue ".$_COOKIE['login']."</p>";
            }
            else{
                if(isset($_POST['submitConnect']) && $_POST['submitConnect'] == "Connexion"){  // Demande de connexion envoyée

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
                        echo "<p>Bienvenue ".$_POST['login']."</p>";
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
                        </form>";
                }
            }

        ?>
    </header>
    <div class="main">
        <nav id="nav">
            <div id="filAriane"></div>
            <div id="hierarchie"></div>
        </nav>
        <main>
            <?php
                $dernierAliment = isset($_SESSION['filArianne']) ? end($_SESSION['filArianne']) : 'Aliment';
                if(isset($_SESSION['filArianne'])) reset($_SESSION['filArianne']);
                $Avisiter = [$dernierAliment];
                $recettes=[];
                $alimentActuel;
                $sousCatAct=[];

                //Remplissage du tableau des recettes à afficher
                while( ($alimentActuel = array_pop($Avisiter))){
                    //Echappement des guillemets
                    $titreAliment = addslashes($alimentActuel);
                    $temp=[];
                    $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);

                    
                    //Requete
                    $query = "SELECT sousCategorie FROM Aliments WHERE titreAliment = '$titreAliment'";
                    $resultat = $mysqli->query($query);
                    
                    $nuplet = $resultat->fetch_row();
                    //Si sous possède des sous catégories
                    if($nuplet[0] != null){
                        //Séparation des sous catégories
                        $sousCatAct = explode(",",$nuplet[0]);
        
                        //Ajout dans la liste des aliments à visiter
                        foreach($sousCatAct as $sousCat){
                            array_push($Avisiter,$sousCat);
                        } 
                        
                    }else{
                        $recettesTemp=[];
                        //Requete
              
                        $query = "SELECT B.* FROM Boisson B,Contient C WHERE titreAliment = '$titreAliment' AND C.titreBoisson = B.titreBoisson";
                        $resultat = $mysqli->query($query);
                        //Parcours du resultat
                        while($nuplet = $resultat->fetch_assoc()){
                            array_push($recettesTemp,$nuplet);
                        
                        }
                        //On vérifie si les recettes ajoutées sont dejà dans les recettes affichées
                        foreach($recettesTemp as $recette){
                            if(!in_array($recettes,$recette)){
                                array_push($recettes,$recette);
                            }
                        }
                    }
                }
                //Affichage des recettes
                echo 'TitreBoisson :<br>'.$recettes[0]['titreBoisson'].'<br>';
                echo 'Ingredients :<br>'.$recettes[0]['ingredients'].'<br>';
                echo 'Préparation :<br>'.$recettes[0]['preparation'].'<br>';

                mysqli_close($mysqli);
            ?>
        </main>
    </div>
</body>
</html>