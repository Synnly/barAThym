/**
 * Fonction AJAX pour affiche les recettes dans le panier
 */
function afficherPanier(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, 'panier');
    xhr.open("GET","phpFonctions/afficherPanier.php", true);
    xhr.send(null);
}

/**
 * Fonction AJAX pour retirer la boisson du panier de l'utilisateur
 * @param login Le login de l'utilisateur
 * @param boisson La boisson à retirer
 */
function retirerBoissonPanier(login, boisson){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = stateChanged(xhr, '');
    xhr.open("GET","phpFonctions/retirerBoissonPanier.php?login="+login+"&boisson="+boisson, true);
    xhr.send(null);
    afficherPanier('login');
}

/**
 * Fonction AJAX pour retirer la boisson du panier du visiteur
 * @param boisson La boisson à retirer
 */
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