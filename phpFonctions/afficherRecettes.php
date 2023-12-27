<?php
    function afficherRecettes($listeBoissons, $login) {
        $boissonsAffichees = array();

        echo "<table id=\"boissons\"><tr>";
        $compteur = 0;

        foreach ($listeBoissons as $boisson) {
            if (!in_array($boisson['titreBoisson'], $boissonsAffichees)) {
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
                            <button onClick=\"ajouterBoissonPanier('$login','" . $boisson['titreBoisson'] . "')\">Ajouter Favoris</button>
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