<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar à thym</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="import" href="fonctions.js">
</head>
<body>
    <?php 
        include "configBD.php";

        function afficherPageLogin(){
            echo "<div id=\"divFormulaire\"><div id=\"signin\"><form method=\"post\">
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
            <p>ou <a href=\"index.php\">se connecter</a></p></div>
            ";
        }

        
        if(!isset($_POST['submitCreate'])){  // Demande de création de compte non envoyée
            afficherPageLogin();
        }
        else{
            if($_POST['submitCreate'] == "Valider" && $_POST['login'] != "" && $_POST['password'] != ""){

                if(preg_match("/[\*\.\'\/\"\(\)\{\}\[\]\$\;\:\&\|]+/", $_POST['login'])){    // Login contient des caractères interdits
                    echo "<h3>Le login ne peut pas contenir ces caractères : *.'/\"(){}[]$;:&|</h3>";
                    afficherPageLogin();
                }
    
                elseif(preg_match("/[\*\.\'\/\"\(\)\{\}\[\]\$\;\:\&\|]+/", $_POST['password'])){     // Mot de passe contient des caractères interdits
                    echo "Le mot de passe ne peut pas contenir ces caractères : *.'/\"(){}[]$;:&|";
                    afficherPageLogin();
                }

                else{   // Login et mot de passe valides
                    
                    try{
                        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
                    }
                    catch(Exception $e){
                        exit($e->getMessage());
                    }
                   
                    // Requete du mot de passe
                    $sql="SELECT * FROM Utilisateurs WHERE login = ? ;";
                    $res = $pdo->prepare($sql);
                    $res->execute([$_POST['login']]);

                    // On verifie si le login est déjà utilisé
                    if($res->rowCount() > 0){
                        echo "Ce login est déjà utilisé";
                        afficherPageLogin();
                    }
                    else{
                        // Ajout de l'utilisateur dans la base de données
                        $login = $_POST['login'];
                        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                        $nom = $_POST['nom'] == '' ? NULL : $_POST['nom'];
                        $prenom = $_POST['prenom'] == '' ? NULL : $_POST['prenom'];
                        $sexe = $_POST['sexe'] == '' ? NULL : $_POST['sexe'];
                        $email = $_POST['email'] == '' ? NULL : $_POST['email'];
                        $dateNaissance = $_POST['dateNaissance'] == '' ? NULL : $_POST['dateNaissance'];
                        $adresse = $_POST['adresse'] == '' ? NULL : $_POST['adresse'];
                        $codePostal = $_POST['codePostal'] == '' ? NULL : $_POST['codePostal'];
                        $ville = $_POST['ville'] == '' ? NULL : $_POST['ville'];
                        $telephone = $_POST['telephone'] == '' ? NULL : $_POST['telephone'];

                        $sql = "INSERT INTO Utilisateurs VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                        $res = $pdo->prepare($sql);
                        $res->execute([$login,$password,$nom,$prenom,$sexe,$email,$dateNaissance,$adresse,$codePostal,$ville,$telephone]);
                        header("Location: index.php");  // Redirection vers la page principale
                    }
                    unset($_POST['submitCreate']);
                }
            }
            else{
                echo "Erreur lors de la création du compte";
            }
        }
    ?>
</body>
</html>