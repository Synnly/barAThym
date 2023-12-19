var xhr = new XMLHttpRequest();
var xhr2 = new XMLHttpRequest();

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

function stateChanged(xhr, name){ 
    return function(){
        if(xhr.readyState == 4 && xhr.status == 200){
            document.getElementById(name).innerHTML = xhr.responseText;
        }
    }
}