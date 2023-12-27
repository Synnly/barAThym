<?php session_start(); ?>
<?php

    echo "<div id=\"champsRecherche\">

        <div id=\"inputRechercher\">
            <button onclick=\"rechercher()\">Rechercher</button>
        </div>
        <label for=\"rechercheContient\">Ingrédients à inclure</label>
        <div class=\"inputRecherche\">
            <input type=\"text\" list=\"listeContient\" id=\"rechercheContient\">
            <button onclick=\"remplirListes('rechercheContient')\">Inclure</button>
            <button onclick=\"resetListe('rechercheContient')\">Reset</button>
            </div><ul>";

    foreach ($_SESSION['inclureIngredients'] as $categorie){
        echo "<li>$categorie</li>";
    }

    echo "</ul>
        <label for=\"rechercheNeContientPas\">Ingrédients à exclure</label>
        <div class=\"inputRecherche\">
            <input type=\"text\" list=\"listeNeContientPas\" id=\"rechercheNeContientPas\">
            <button onclick=\"remplirListes('rechercheNeContientPas')\">Exclure</button>
            <button onclick=\"resetListe('rechercheNeContientPas')\">Reset</button>
        </div><ul>";

    foreach ($_SESSION['exclureIngredients'] as $categorie){
        echo "<li>$categorie</li>";
    }

    echo "</ul></div>";

// TODO : Reremplir les listes apres appel
// TODO : Requete correspondance 100%
// TODO : Requete correspondance <100%

    ?>