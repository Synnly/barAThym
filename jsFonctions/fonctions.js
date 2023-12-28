/**
 * Fonction AJAX pour afficher les sous catégories de l'ingrédient sélectionné
 * @param ingredient L'ingrédient dont on veut afficher les sous catégories
 */
function afficherSousCategorie(ingredient){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'hierarchie');
    xhr.open("GET","phpFonctions/hierarchie.php?ingredient="+ingredient, true);
    xhr.send(null);
}

/**
 * Fonction AJAX pour afficher le fil d'ariane
 * @param ingredient Le debut du fil d'ariane si le fil d'ariane est vide
 */
function afficherFilAriane(ingredient){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'filAriane');
    xhr.open("GET","phpFonctions/filAriane.php?ingredient="+ingredient, true);
    xhr.send(null);
}

/**
 * Fontion AJAX pour afficher les recettes dont au moins un ingrédient
 * est une sous catégorie du dernier élément du fil d'ariane
 */
function afficherRecettes(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'recettes');
    xhr.open("GET","phpFonctions/afficherRecettesFilAriane.php", true);
    xhr.send(null);
}

/**
 * Fonction AJAX pour rafraichir la hierarchie
 * @param ingredient L'ingrédient séléctionné
 */
function refreshHierarchie(ingredient){
    afficherFilAriane(ingredient);
    afficherSousCategorie(ingredient);
    afficherRecettes();
}

/**
 * Fonction AJAX pour déconnecter l'utilisateur
 */
function deconnecter(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/deconnecter.php", true);
    xhr.send(null);
    document.cookie = "login= ; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    window.location = window.location.href;
}

/**
 * Fonction AJAX pour remplir la liste d'ingrédients désigné par id
 * @param id L'identifiant de la liste à remplir
 */
function remplirListes(id){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'datalist');
    if(id != null) {
        let ingredient = document.getElementById(id.toString()).value;
        if(id === "rechercheContient"){
            xhr.open("GET","phpFonctions/remplirListesIngredients.php?inclure="+ingredient+"&exclure=", true);
        }
        else if(id === "rechercheNeContientPas"){
            xhr.open("GET","phpFonctions/remplirListesIngredients.php?inclure=&exclure="+ingredient, true);
        }
    }
    else {
        xhr.open("GET", "phpFonctions/remplirListesIngredients.php?inclure=&exclure=", true);
    }
    xhr.send(null);
    afficherChampsRecherche(id);
}

/**
 * Fonction AJAX pour vider la liste des ingrédients sélectionnés
 * @param id L'identifiant de la liste
 */
function resetListe(id){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/reset.php?id="+id, true);
    xhr.send(null);

    remplirListes(id);
}

/**
 * Fonction AJAX pour afficher les champs de recherche ainsi que les listes d'éléments sélectionnés
 * @param id L'identifiant du champ à afficher ainsi que sa liste correspondante
 */
function afficherChampsRecherche(id){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'champsRecherche');
    xhr.open("GET","phpFonctions/afficherChampsRecherche.php", true);
    xhr.send(null);

    // Fonctionne alors que la condition devrait être dans l'autre sens
    // ??????????????????????
    if ((id === '')) {
        document.getElementById(id).value = "";
    }
}

/**
 * Fonction AJAX pour ajouter une boisson au panier de l'utilisateur
 * @param login Le login de l'utilisateur
 * @param boisson La boisson à ajouter
 */
function ajouterBoissonPanier(login, boisson){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/ajouterBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr.send(null);
}

/**
 * Fonction AJAX pour effectuer la recherche de recettes
 */
function rechercher(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'recettes');
    xhr.open("GET","phpFonctions/afficherRecettesRecherche.php", true);
    xhr.send(null);
}

function stateChanged(xhr, name){
    return function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById(name).innerHTML = xhr.responseText;
        }
    }
}

window.onload = function(){
    afficherSousCategorie("Aliment");
    afficherFilAriane("Aliment");
    remplirListes();
}
