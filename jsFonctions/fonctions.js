var xhr1 = new XMLHttpRequest(); var xhr2 = new XMLHttpRequest();
var xhr3 = new XMLHttpRequest(); var xhr4 = new XMLHttpRequest();
var xhr5 = new XMLHttpRequest(); var xhr6 = new XMLHttpRequest();
var xhr7 = new XMLHttpRequest(); var xhr8 = new XMLHttpRequest();

function afficherSousCategorie(ingredient){
    xhr1.onreadystatechange = stateChanged(xhr1, 'hierarchie');
    xhr1.open("GET","phpFonctions/hierarchie.php?ingredient="+ingredient, true);
    xhr1.send(null);
}

function afficherFilAriane(ingredient){
    xhr2.onreadystatechange = stateChanged(xhr2, 'filAriane');
    xhr2.open("GET","phpFonctions/filAriane.php?ingredient="+ingredient, true);
    xhr2.send(null);
}

function refreshHierarchie(ingredient){
    afficherFilAriane(ingredient);
    afficherSousCategorie(ingredient);
    afficherRecettes();
}

function deconnecter(){
    xhr3.onreadystatechange = stateChanged(xhr3, '');
    xhr3.open("GET","phpFonctions/deconnecter.php", true);
    xhr3.send(null);
    document.cookie = "login= ; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    window.location = window.location.href;
}

function remplirListeContient(id){
    xhr4.onreadystatechange = stateChanged(xhr4, 'recherche');
    if(id != null) {
        let ingredient = document.getElementById(id.toString()).value;
        if(id === "rechercheContient"){
            xhr4.open("GET","phpFonctions/inclureIngredient.php?inclure="+ingredient+"&exclure=", true);
        }
        else if(id === "rechercheNeContientPas"){
            xhr4.open("GET","phpFonctions/inclureIngredient.php?inclure=&exclure="+ingredient, true);
        }
    }
    else {
        xhr4.open("GET", "phpFonctions/inclureIngredient.php?inclure=&exclure=", true);
    }
    xhr4.send(null);

    // Fonctionne alors que la condition devrait être dans l'autre sens
    // ??????????????????????
    if ((id === '')) {
        document.getElementById(id).value = "";
    }
}

function resetListe(nomListe){
    xhr5.onreadystatechange = stateChanged(xhr5, 'champsRecherche');
    xhr5.open("GET","phpFonctions/reset.php?nomListe="+nomListe, true);
    xhr5.send(null);
}

function afficherRecettes(){
    xhr7.onreadystatechange = stateChanged(xhr7, 'recettes');
    xhr7.open("GET","phpFonctions/afficherRecettes.php", true);
    xhr7.send(null);
}

function ajouterBoissonPanier(login, boisson){
    xhr8.onreadystatechange = stateChanged(xhr8, '');
    xhr8.open("GET","phpFonctions/ajouterBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr8.send(null);
    afficherPanier('login');
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
    remplirListeContient();
}
