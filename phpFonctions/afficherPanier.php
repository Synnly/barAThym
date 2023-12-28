<?php session_start() ?>
<?php
    include "../configBD.php";
    
    // Connexion
    try{
        $pdo = new PDO('mysql:host='.$_IPBD.';dbname='.$_NAMEBD, $_USERNAME, $_PASSWORD);
    }
    catch(Exception $e){
        exit($e->getMessage());
    }

    $boissonsAffichees= [];

    // Requete des boissons dans le panier
    $sql="SELECT b.* FROM Panier p, Boisson b WHERE p.login = ? and b.titreBoisson = p.titreBoisson;";
    $resultat = $pdo->prepare($sql);

    // Recuperation du login
    if(isset($_COOKIE['login'])){
        $login = $_COOKIE['login'];
    }
    elseif(isset($_POST['login'])){
        $login = $_POST['login'];
    }else{
        $login = null;
    }

    echo "<table id=\"boissons\"><tr>";
    $compteur = 0;

    //Si l'utilisateur est connecté on parcours la table Panier
    if($login != null){
        $resultat->execute([$login]);

        // Affichage des boissons
        foreach ($resultat as $row){
            if($compteur % 3 == 0 && $compteur > 0) echo "</tr><tr>";
            $boissonsAffichees[] = $row['titreBoisson'];
            echo "<td><div class=\"boissonEnTete\">";

            // Transformation du titre de la boisson
            $titreBoisson = preg_replace("/\s/", "_", $row['titreBoisson']);
            $titreBoisson = "../Photos/".$titreBoisson.".jpg";

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
    }

    //Parcours du tableau de session panier
    if(isset($_SESSION['panier'])){
        // Affichage des boissons
        foreach ($_SESSION['panier'] as $boisson){
            if(!in_array($boisson['titreBoisson'],$boissonsAffichees)){
                if($compteur % 3 == 0 && $compteur > 0) echo "</tr><tr>";
                echo "<td><div class=\"boissonEnTete\">";

                // Transformation du titre de la boisson
                $titreBoisson = preg_replace("/\s/", "_", $boisson['titreBoisson']);
                $titreBoisson = "../Photos/".$titreBoisson.".jpg";

                //Affichage de l'image
                if(fopen($titreBoisson, 'r')){
                    echo "<img src=\"$titreBoisson\">";
                }
                else{
                    echo "<img src=\"Photos/glass.png\">";
                }

                echo "<div class=\"boissonEnTeteTexte\">
                        <p>".$boisson['titreBoisson']."</p>
                        <button onClick=\"retirerBoissonPanierVisiteur('".$boisson['titreBoisson']."')\">Retirer</button>
                    </div>
                    </div>";

                // Affichage des ingrédients
                echo "<br/>Ingrédients : <ul>";
                foreach(explode("|", $boisson['ingredients']) as $ingredient){
                    echo "<li>$ingredient</li>";
                }
                echo "</ul>";

                // Affichage de la recette
                echo "Préparation : <br>".$boisson['preparation'];
                echo "</td>";
                $compteur++;
            }
        }
    }
    echo "</tr></table>";
 ?>