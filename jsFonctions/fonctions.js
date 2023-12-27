

function afficherSousCategorie(ingredient){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'hierarchie');
    xhr.open("GET","phpFonctions/hierarchie.php?ingredient="+ingredient, true);
    xhr.send(null);
}

function afficherFilAriane(ingredient){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'filAriane');
    xhr.open("GET","phpFonctions/filAriane.php?ingredient="+ingredient, true);
    xhr.send(null);
}

function refreshHierarchie(ingredient){
    afficherFilAriane(ingredient);
    afficherSousCategorie(ingredient);
    afficherRecettes();
}

function deconnecter(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/deconnecter.php", true);
    xhr.send(null);
    document.cookie = "login= ; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    window.location = window.location.href;
}

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

function resetListe(id){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/reset.php?id="+id, true);
    xhr.send(null);

    remplirListes(id);
}

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

function afficherRecettes(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'recettes');
    xhr.open("GET","phpFonctions/afficherRecettesFilAriane.php", true);
    xhr.send(null);
}

function ajouterBoissonPanier(login, boisson){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/ajouterBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr.send(null);
}

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
