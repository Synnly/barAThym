<?php
    include "../configBD.php"; 
    
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

?>