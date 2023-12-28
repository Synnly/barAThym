<?php session_start(); ?>
<?php
    include "../configBD.php";


    if(isset($_COOKIE['login'])){   //Si l'utilisateur est connecté, on supprime le tuple de la table Panier
        try{
            $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
        }
        catch(Exception $e){
            exit($e->getMessage());
        }
    
        // Suppression de l'aliment
        $sql="DELETE FROM Panier WHERE login = ? AND titreBoisson = ?;";
        $res = $pdo->prepare($sql);
        $res->execute([$_GET['login'], $_GET['boisson']]);
    }
    //On supprime la boisson du tableau de session panier
    foreach($_SESSION['panier'] as $boisson){
        if($boisson['titreBoisson'] == $_GET['boisson']){
            array_splice($_SESSION['panier'], array_search($boisson['titreBoisson'], $_SESSION['panier']), 1);
        }
    }
    
    

?>