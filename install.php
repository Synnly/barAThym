<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    include "Donnees.inc.php";
    include "configBD.php";

    function query($link,$requete){ 
        $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));
        return($resultat);
    }

    $mysqli=mysqli_connect($_IPBD, $_USERNAME, $_PASSWORD);

    // Creation de la base de donnees
    $Sql="DROP DATABASE IF EXISTS $_NAMEBD; CREATE DATABASE $_NAMEBD";
    foreach(explode(';',$Sql) as $Requete) query($mysqli,$Requete);

    $mysqli->select_db($_NAMEBD);

    // Creation et remplissage de la table Boisson
    $sql="DROP TABLE IF EXISTS Boisson;
        CREATE TABLE Boisson (
            titreBoisson VARCHAR(150) PRIMARY KEY,
            ingredients VARCHAR(200),
            preparation VARCHAR(800)
        );
    ";

    foreach($Recettes as $Boisson){

        $titreBoisson = addslashes($Boisson['titre']);
        $ingredients = preg_replace('/&quot;/', '"', addslashes($Boisson['ingredients']));
        $preparation = preg_replace('/&quot;/', '"', addslashes($Boisson['preparation']));
        $preparation = preg_replace('/;/', ':', addslashes($Boisson['preparation']));
        $sql=$sql."INSERT INTO  Boisson VALUES ('$titreBoisson','$ingredients','$preparation');";
    }

    foreach(explode(';',$sql) as $Requete) {
        echo $Requete."<br/><br/>";
        if($Requete != "") query($mysqli,$Requete);
    }


    // Creation et remplissage de la table Aliments
    $sql="DROP TABLE IF EXISTS Aliments;
        CREATE TABLE Aliments (
            titreAliment VARCHAR(150) PRIMARY KEY,
            superCategorie VARCHAR(100),
            sousCategorie VARCHAR(500) 
    );";

    foreach($Hierarchie as $nomAliment => $infosAliment){
        $titreAliment = addslashes($nomAliment);
        $sousCategorie = "";
        $superCategorie = "";
        if(isset($infosAliment['sous-categorie'])){
            foreach ($infosAliment['sous-categorie'] as $index => $ingredient) {
                $sousCategorie = $sousCategorie.addslashes($ingredient).",";
            }
        }

        if(isset($infosAliment['super-categorie'])){
            foreach ($infosAliment['super-categorie'] as $index => $ingredient) {
                $superCategorie = $superCategorie.addslashes($ingredient).",";
            }
        }   
        $superCategorie == "" ? $superCategorie = "NULL" : $superCategorie = "'".substr($superCategorie, 0, -1)."'";
        $sousCategorie == "" ? $sousCategorie = "NULL" : $sousCategorie = "'".substr($sousCategorie, 0, -1)."'";
        
        $sql=$sql."INSERT INTO  Aliments VALUES ('$titreAliment',$superCategorie,$sousCategorie);";
    }

    foreach(explode(';',$sql) as $Requete) {
        if($Requete != "") query($mysqli,$Requete);
    }


    // Creation et remplissage de la table Contient
    $sql="DROP TABLE IF EXISTS Contient;
        CREATE TABLE Contient (
            titreBoisson VARCHAR(150) REFERENCES Boisson(titreBoisson),
            titreAliment VARCHAR(150) REFERENCES Aliments(titreAliment),
            PRIMARY KEY (titreBoisson, titreAliment)
    );";

    foreach($Recettes as $Boisson){
        $titreBoisson = addslashes($Boisson['titre']);
        foreach($Boisson['index'] as $ingredient){
            $titreAliment = addslashes($ingredient);
            $sql=$sql."INSERT INTO  Contient VALUES ('$titreBoisson','$titreAliment');";
        }
    }
    foreach(explode(';',$sql) as $Requete) {
        if($Requete != "") query($mysqli,$Requete);
    }


    //Creation de la table Utilisateurs
    $sql="DROP TABLE IF EXISTS Utilisateurs;
        CREATE TABLE Utilisateurs (
            login VARCHAR(255) PRIMARY KEY,
            password VARCHAR(255) NOT NULL,
            nom VARCHAR(50),
            prenom VARCHAR(20),
            sexe VARCHAR(20),
            email VARCHAR(50),
            dateNaissance DATE,
            adresse VARCHAR(50),
            codePostal INTEGER,
            ville VARCHAR(40),
            telephone INTEGER
        );";
    
    foreach(explode(';',$sql) as $Requete) {
        if($Requete != "") query($mysqli,$Requete);
    }


    //Create de la table Panier
    $sql="DROP TABLE IF EXISTS Panier;
        CREATE TABLE Panier (
            login VARCHAR(50) REFERENCES Utilisateurs(login),
            titreBoisson VARCHAR(150) REFERENCES Boisson(titreBoisson),
            PRIMARY KEY (login, titreBoisson)
        );";

    foreach(explode(';',$sql) as $Requete) {
        if($Requete != "") query($mysqli,$Requete);
    }

    $mysqli->close();

    ?>
</body>
</html>