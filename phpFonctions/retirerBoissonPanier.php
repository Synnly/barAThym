<?php session_start() ?>
<?php
    include "../configBD.php";
    //Si l'utilisateur est connecté alors on supprime un tuple de la table Panier 
    if(isset($_COOKIE['login'])){
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
    }else{//Sinon on supprime la boisson du tableau de session panier
        foreach($_SESSION['panier'] as $boisson){
            if($boisson['titreBoisson'] == $_GET['boisson']){
                unset($_SESSION['panier'][$boisson['titreBoisson']]);
            }
        }
    }
    

?>