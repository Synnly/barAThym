<?php
    include "../configBD.php"; 
    
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

?>