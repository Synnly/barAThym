<!DOCTYPE html>
<html lang="fr">

<?php session_start()?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="import" href="jsFonctions/fonctions.js">
</head>
<body>
    <?php 
        include "configBD.php";

        //Récupération du login de l'utilisateur
        if(isset($_COOKIE['login'])){
            $login = $_COOKIE['login'];
        }
        elseif(isset($_POST['login'])){
            $login = $_POST['login'];
        }

        function afficherInfoCompte($login){
            include "configBD.php";
            
            //Récupération des données de l'utilisateur dans la BD
            $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);
            $query = "SELECT * FROM Utilisateurs WHERE login ='".$mysqli->escape_string($login)."'";
            $resultat = $mysqli->query($query);
            $nuplet = $resultat->fetch_assoc();

            //Formulaire prérempli avec les données de l'utilisateur
            echo "<div id=\"divFormulaire\"><div id=\"infocompte\"><form method=\"post\">
                
                <legend>Nouveau mot de passe (Facultatif)</legend>
                <input type=\"password\" name=\"password\">

                <legend>Confirmer nouveau Mot de passe</legend>
                <input type=\"password\" name=\"password2\">
        
                <legend>Nom</legend>
                <input type=\"text\" name=\"nom\" value=\"".$nuplet['nom']."\">
        
                <legend>Prénom</legend>
                <input type=\"text\" name=\"prenom\" value=\"".$nuplet['prenom']."\">

                
                <legend>Sexe</legend>
                <div id=\"sexe\">
                <input type=\"radio\" name=\"sexe\" value=\"homme\" " . ($nuplet['sexe'] === 'homme' ? 'checked' : '') . ">Homme
                <input type=\"radio\" name=\"sexe\" value=\"femme\" " . ($nuplet['sexe'] === 'femme' ? 'checked' : '') . ">Femme
                </div>

                <legend>E-mail</legend>
                <input type=\"email\" name=\"email\"value=\"".$nuplet['email']."\">
        
                <legend>Date de naissance</legend>
                <input type=\"date\" name=\"dateNaissance\"value=\"".$nuplet['dateNaissance']."\">
        
                <legend>Adresse</legend>
                <input type=\"text\" name=\"adresse\"value=\"".$nuplet['adresse']."\">
        
                <legend>Code postal</legend>
                <input type=\"number\" name=\"codePostal\"value=\"".$nuplet['codePostal']."\">
        
                <legend>Ville</legend>
                <input type=\"text\" name=\"ville\"value=\"".$nuplet['ville']."\">
        
                <legend>Téléphone</legend>
                <input type=\"number\" name=\"telephone\" maxlengh=\"10\"value=\"".$nuplet['telephone']."\">
                <br>
                <input type=\"submit\" value=\"Modifier\" name=\"submitModif\">
            </form></div></div>
            ";
        }

        
        if(!isset($_POST['submitModif'])){  // Demande de création de compte non envoyée
            afficherInfoCompte($login);
        }
        else{
            if($_POST['submitModif'] == "Modifier"){

                if(preg_match("/[\*\.\'\/\"\(\)\{\}\[\]\$\;\:\&\|]+/", $_POST['password'])){     // Mot de passe contient des caractères interdits
                    echo "Le mot de passe ne peut pas contenir ces caractères : *.'/\"(){}[]$;:&|";
                    afficherInfoCompte($login);
                }
                elseif($_POST['password'] != $_POST['password2']){ //Les deux mots de passe ne correspondent pas
                    echo "Les deux mots de passes ne correspondent pas !";
                    afficherInfoCompte($login);
                }

                else{   // Login et mot de passe valides
                    
                    try{
                        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
                    }
                    catch(Exception $e){
                        exit($e->getMessage());
                    }

                    // Mise à jour des données de l'utilisateur dans la base de donnée
                    $nom = $_POST['nom'] == '' ? NULL : $_POST['nom'];
                    $prenom = $_POST['prenom'] == '' ? NULL : $_POST['prenom'];
                    $sexe = $_POST['sexe'] == '' ? NULL : $_POST['sexe'];
                    $email = $_POST['email'] == '' ? NULL : $_POST['email'];
                    $dateNaissance = $_POST['dateNaissance'] == '' ? NULL : $_POST['dateNaissance'];
                    $adresse = $_POST['adresse'] == '' ? NULL : $_POST['adresse'];
                    $codePostal = $_POST['codePostal'] == '' ? NULL : $_POST['codePostal'];
                    $ville = $_POST['ville'] == '' ? NULL : $_POST['ville'];
                    $telephone = $_POST['telephone'] == '' ? NULL : $_POST['telephone'];
                    if($_POST['password'] == ''){
                        $sql = "UPDATE Utilisateurs SET nom = ? , prenom = ? , sexe = ? , email = ? , dateNaissance = ? , adresse = ? , codePostal = ? , ville = ? , telephone = ? WHERE login = ?;";
                        $res = $pdo->prepare($sql);
                        $res->execute([$nom,$prenom,$sexe,$email,$dateNaissance,$adresse,$codePostal,$ville,$telephone,$login]);
                    }else{
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $sql = "UPDATE Utilisateurs SET password = ? , nom = ? , prenom = ? , sexe = ? , email = ? , dateNaissance = ? , adresse = ? , codePostal = ? , ville = ? , telephone = ? WHERE login = ?;";
                        $res = $pdo->prepare($sql);
                        $res->execute([$password,$nom,$prenom,$sexe,$email,$dateNaissance,$adresse,$codePostal,$ville,$telephone,$login]);
                    }
                    
                    header("Location: index.php");  // Redirection vers la page principale
                    
                    unset($_POST['submitModif']);
                }
            }
            else{
                echo "Erreur lors de la modification du compte";
            }
        }
    ?>
</body>
</html>