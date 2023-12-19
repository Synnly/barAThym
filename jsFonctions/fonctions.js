var xhr1 = new XMLHttpRequest(); var xhr2 = new XMLHttpRequest();
var xhr3 = new XMLHttpRequest();

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
}

function deconnecter(){
    xhr3.onreadystatechange = stateChanged(xhr3, '');
    xhr3.open("GET","phpFonctions/deconnecter.php", true);
    xhr3.send(null);
    document.cookie = "login= ; expires=Thu, 01 Jan 1970 00:00:01 GMT";
    window.location = window.location.href;
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
}
