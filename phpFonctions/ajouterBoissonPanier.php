<?php session_start() ?>
<?php
    include "../configBD.php"; 
    //Si l'utilisateur est connecté alors on ajoute un tuple dans la table panier
    if(isset($_COOKIE['login'])){
        try{
            $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
        }
        catch(Exception $e){
            exit($e->getMessage());
        }
        // Ajout de la boisson au panier de l'utilisateur
        $sql="INSERT INTO Panier VALUES(?,?);";
        $res = $pdo->prepare($sql);
        $res->execute([$_GET['login'], $_GET['boisson']]);
    }else{//Sinon on ajoute la boisson dans le tableau de session panier
        $titreBoisson = $_GET['boisson'];
        $mysqli = mysqli_connect($_IPBD,$_USERNAME,$_PASSWORD,$_NAMEBD);
        $query = "SELECT * FROM Boisson WHERE titreBoisson = '$titreBoisson'";
        $resultat = $mysqli->query($query);

        $nuplet = $resultat->fetch_assoc();
        $_SESSION['panier'][] = $nuplet;
    }
    
    

?>