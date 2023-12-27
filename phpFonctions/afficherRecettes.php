<?php
    function afficherRecettes($listeBoissons, $login, $listePrct) {
        $boissonsAffichees = array();
        $compteur = 0;
        $palier = 1;
        echo "<table id=\"boissons\"><tr>";

        foreach ($listeBoissons as $boisson) {
            if (!in_array($boisson['titreBoisson'], $boissonsAffichees)) {

                if(isset($listePrct) && $listePrct[array_search($boisson, $listeBoissons)] <= $palier) {
                    $compteur = 0;
                    $prct = $listePrct[array_search($boisson, $listeBoissons)];
                    echo "</tr><tr>";
                    echo "<td colspan='3' class='ligne'>
                            <div class='ligne'>
                                <div><p>".(int)($palier*100)." %</p></div>
                                <div class='divLigne'><hr></div>
                            </div>
                        </td>";
                    echo "</tr><tr>";
                    if($prct < 0.3) $palier = 0.1;
                    elseif($prct < 0.5) $palier = 0.3;
                    elseif($prct < 0.8) $palier = 0.5;
                    elseif($prct <= 1.) $palier = 0.8;
                }
                if ($compteur % 3 == 0 && $compteur > 0) echo "</tr><tr>";
                echo "<td>";

                // Transformation du titre de la boisson
                $titreBoisson = iconv('UTF-8', 'ASCII//TRANSLIT', $boisson['titreBoisson']);
                $titreBoisson = preg_replace("/\s/", "_", $titreBoisson);
                $titreBoisson = "../Photos/" . $titreBoisson . ".jpg";

                // Affichage de l'entete
                echo "<div class=\"boissonEnTete\">";

                //Affichage de l'image
                if (fopen($titreBoisson, 'r')) {
                    echo "<img src=\"$titreBoisson\">";
                } else {
                    echo "<img src=\"Photos/glass.png\">";
                }

                echo "<div class=\"boissonEnTeteTexte\">
                            <p>" . $boisson['titreBoisson'] . "</p>
                            <button onClick=\"ajouterBoissonPanier('$login','" . $boisson['titreBoisson'] . "')\">Ajouter au panier</button>
                        </div>
                        </div>";

                // Affichage des ingrédients
                echo "<br/>Ingrédients : <ul>";
                foreach (explode("|", $boisson['ingredients']) as $ingredient) {
                    echo "<li>$ingredient</li>";
                }
                echo "</ul>";

                // Affichage de la recette
                echo "Préparation : <br>" . $boisson['preparation'];
                echo "</td>";
                $compteur++;
            }
        }
    }

?>