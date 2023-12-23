<?php session_start(); ?>
<?php

    if($_GET['nomListe'] == "inclureIngredients"){
        unset($_SESSION['inclureIngredients']);
    }

    elseif($_GET['nomListe'] == "exclureIngredients"){
        unset($_SESSION['exclureIngredients']);
    }

    echo '<div id="champsRecherche><label for="rechercheContient">Ingrédients à inclure</label>
        <div class="inputRecherche">
            <input type="text" list="listeContient" id="rechercheContient">
            <button onclick="remplirListeContient(\'rechercheContient\')">Inclure</button>
            <button onclick="resetListe(\'inclureIngredients\')">Reset</button>
            </div>
            <ul></ul>
    
        <label for="rechercheNeContientPas">Ingrédients à exclure</label>
        <div class="inputRecherche">
            <input type="text" list="listeNeContientPas" id="rechercheNeContientPas">
            <button onclick="remplirListeContient(\'rechercheNeContientPas\')">Exclure</button>
            <button onclick="resetListe(\'exclureIngredients\')">Reset</button>
        </div>
        <ul></ul>
        </div>';

?>
