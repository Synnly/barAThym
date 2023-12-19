<?php
    include "../configBD.php";
    
    // Connexion
    try{
        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
    }
    catch(Exception $e){
        exit($e->getMessage());
    }

    // Requete des boissons dans le panier
    $sql="SELECT b.* FROM Panier p, Boisson b WHERE p.login = ? and b.titreBoisson = p.titreBoisson;";
    $resultat = $pdo->prepare($sql);

    // Recuperation du login
    if(isset($_COOKIE['login'])){
        $login = $_COOKIE['login'];
    }
    elseif(isset($_POST['login'])){
        $login = $_POST['login'];
    }

    $resultat->execute([$login]);

    echo "<table><tr>";
    $compteur = 0;

    // Affichage des boissons
    foreach ($resultat as $row){
        if($compteur % 3 == 0 && $compteur > 0) echo "</tr><tr>";
        echo "<td>";

        // Transformation du titre de la boisson
        $titreBoisson = preg_replace("/\s/", "_", $row['titreBoisson']);
        $titreBoisson = "../Photos/".$titreBoisson.".jpg";

        // Affichage de l'entete
        echo "<div class=\"boissonEnTete\">";

            //Affichage de l'image
        if(fopen($titreBoisson, 'r')){
            echo "<img src=\"$titreBoisson\">";
        }
        else{
            echo "<img src=\"Photos/glass.png\">";
        }

        echo "<div class=\"boissonEnTeteTexte\">
                <p>".$row['titreBoisson']."</p>
                <button onClick=\"retirerBoissonPanier('$login', '".$row['titreBoisson']."')\">Retirer</button>
            </div>
            </div>";

        // Affichage des ingrédients
        echo "<br/>Ingrédients : <ul>";
        foreach(explode("|", $row['ingredients']) as $ingredient){
            echo "<li>$ingredient</li>";
        }
        echo "</ul>";  
        
        // Affichage de la recette
        echo "Préparation : <br>".$row['preparation'];
        echo "</td>";   
        $compteur++;
    }

    echo "</tr></table>";
 ?>