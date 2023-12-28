<?php

    /**
     * Affiche les recettes des boissons triés par ordre décroissante de taux de correspondance à la recherche
     * et séparé par les paliers 100%, 80%, 50%, 30% et 10%
     * @param $listeBoissons array La liste des boissons
     * @param $login string Le login de l'utilisateur
     * @param $listePrct array Optionnelle. La liste des taux de correspondance avec à l'indice i le taux de correspondance de la i-ème boisson de la liste $listeBoissons
     * @return void
     */
    function afficherRecettes($listeBoissons, $login, $listePrct) {
        $boissonsAffichees = array();
        $compteur = 0;
        $palier = 1;
        echo "<table id=\"boissons\"><tr>";

        foreach ($listeBoissons as $boisson) {

            // On verifie si la recette a déja été affichée
            if (!in_array($boisson['titreBoisson'], $boissonsAffichees)) {

                // Affichage du palier le palier a été dépassé
                if(isset($listePrct) && $listePrct[array_search($boisson, $listeBoissons)] <= $palier) {
                    $prct = $listePrct[array_search($boisson, $listeBoissons)];


                    if($prct == $palier && $palier == 1) {  // Palier 100%
                        echo "</tr><tr>";
                        echo "<td colspan='3' class='ligne'>
                            <div class='ligne'>
                                <div><p>100 %</p></div>
                                <div class='divLigne'><hr></div>
                            </div>
                        </td>";
                        echo "</tr><tr>";

                        $palier = 0.8;
                        $compteur = 0;
                    }
                    elseif($prct < $palier){                // Palier <100%
                        $temp = $palier;
                        if($prct < 1.) {    // 80%
                            $palier = 0.8;
                            $temp = 1.;
                        }
                        if($prct < 0.8) {   // 50%
                            $palier = 0.5;
                            $temp = 0.8;
                        }
                        if($prct < 0.5) {   // 30%
                            $palier = 0.3;
                            $temp = 0.5;
                        }
                        if($prct < 0.3) {   // 10%
                            $palier = 0.1;
                            $temp = 0.3;
                        }

                        echo "</tr><tr>";
                        echo "<td colspan='3' class='ligne'>
                        <div class='ligne'>
                            <div><p>".(int)($temp*100)." %</p></div>
                            <div class='divLigne'><hr></div>
                        </div>
                        </td>";
                        echo "</tr><tr>";
                        $compteur = 0;
                    }
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