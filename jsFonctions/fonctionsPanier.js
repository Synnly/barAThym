function afficherPanier(login){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'panier');
    xhr.open("GET","phpFonctions/afficherPanier.php?login="+login, true);
    xhr.send(null);
}

function retirerBoissonPanier(login, boisson){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/retirerBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr.send(null);
    afficherPanier('login');
}

function retirerBoissonPanierVisiteur(boisson){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/retirerBoissonPanier.php?boisson="+boisson, true);
    xhr.send(null);
    afficherPanier(null);
}

function stateChanged(xhr, name){ 
    return function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById(name).innerHTML = xhr.responseText;
        }
    }
}