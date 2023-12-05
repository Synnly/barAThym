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
    <?php 
        include "configBD.php";

        function afficherPageLogin(){
            echo "<div id=\"signin\"><form method=\"get\">
                <legend>Login</legend>
                <input type=\"text\" name=\"login\" required=\"required\">
        
                <legend>Mot de passe</legend>
                <input type=\"password\" name=\"password\" required=\"required\">
        
                <legend>Nom</legend>
                <input type=\"text\" name=\"nom\">
        
                <legend>Prénom</legend>
                <input type=\"text\" name=\"prenom\">

                
                <legend>Sexe</legend>
                <div id=\"sexe\">
                <input type=\"radio\" name=\"sexe\" value=\"homme\">Homme
                <input type=\"radio\" name=\"sexe\" value=\"femme\">Femme
                </div>

                <legend>E-mail</legend>
                <input type=\"email\" name=\"email\">
        
                <legend>Date de naissance</legend>
                <input type=\"date\" name=\"dateNaissance\">
        
                <legend>Adresse</legend>
                <input type=\"text\" name=\"adresse\">
        
                <legend>Code postal</legend>
                <input type=\"number\" name=\"codePostal\">
        
                <legend>Ville</legend>
                <input type=\"text\" name=\"ville\">
        
                <legend>Téléphone</legend>
                <input type=\"number\" name=\"telephone\" maxlengh=\"10\">
                <br>
                <input type=\"submit\" value=\"Valider\" name=\"submitCreate\">
            </form></div>
            <p>ou <a href=\"index.php\">se connecter</a></p> 
            ";
        }

        
        if(!isset($_GET['submitCreate'])){  // Demande de création de compte non envoyée
            afficherPageLogin();
        }
        else{
            if($_GET['submitCreate'] == "Valider" && $_GET['login'] != "" && $_GET['password'] != ""){
                
                // Verification de l'unicité du login
                $mysqli= mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);
                $res = $mysqli->query("SELECT * FROM Utilisateurs WHERE login='".$_GET['login']."';");
                
                // On verifie si le login est déjà utilisé
                if($res->num_rows > 0){
                    echo "Ce login est déjà utilisé";
                    unset($_GET['submitCreate']);
                    $mysqli->close();
                    afficherPageLogin();
                }
                else{
                    // Ajout de l'utilisateur dans la base de données
                    $login = $_GET['login']; 
                    $password = $_GET['password'];
                    $nom = $_GET['nom'] == '' ? "NULL" : "'".$_GET['nom']."'";
                    $prenom = $_GET['prenom'] == '' ? "NULL" : "'".$_GET['prenom']."'";
                    $sexe = $_GET['sexe'] == '' ? "NULL" : "'".$_GET['sexe']."'";
                    $email = $_GET['email'] == '' ? "NULL" : "'".$_GET['email']."'";
                    $dateNaissance = $_GET['dateNaissance'] == '' ? "NULL" : "'".$_GET['dateNaissance']."'";
                    $adresse = $_GET['adresse'] == '' ? "NULL" : "'".$_GET['adresse']."'";
                    $codePostal = $_GET['codePostal'] == '' ? "NULL" : "'".$_GET['codePostal']."'";
                    $ville = $_GET['ville'] == '' ? "NULL" : "'".$_GET['ville']."'";
                    $telephone = $_GET['telephone'] == '' ? "NULL" : "'".$_GET['telephone']."'";
                    
                    $sql = "INSERT INTO Utilisateurs VALUES ('$login','$password',$nom,$prenom,$sexe,$email,$dateNaissance,$adresse,$codePostal,$ville,$telephone);";
                    $mysqli = new mysqli($_IPBD, $_USERNAME, $_PASSWORD, $_NAMEBD);
                    $mysqli->query($sql);
                    header("Location: index.php");  // Redirection vers la page principale
                    unset($_GET['submitCreate']);
                }
            }
            else{
                echo "Erreur lors de la création du compte";
            }
        }
    ?>
</body>
</html>