var xhr = new XMLHttpRequest();
var xhr2 = new XMLHttpRequest();
var xhr3 = new XMLHttpRequest();

function afficherPanier(login){
    xhr.onreadystatechange = stateChanged(xhr, 'panier');
    xhr.open("GET","phpFonctions/afficherPanier.php?login="+login, true);
    xhr.send(null);
}

function retirerBoissonPanier(login, boisson){
    xhr2.onreadystatechange = stateChanged(xhr2, '');
    xhr2.open("GET","phpFonctions/retirerBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr2.send(null);
    afficherPanier('login');
}

function retirerBoissonPanierVisiteur(boisson){
    xhr3.onreadystatechange = stateChanged(xhr3, '');
    xhr3.open("GET","phpFonctions/retirerBoissonPanier.php?boisson="+boisson, true);
    xhr3.send(null);
    afficherPanier(null);
}

function stateChanged(xhr, name){ 
    return function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById(name).innerHTML = xhr.responseText;
        }
    }
}