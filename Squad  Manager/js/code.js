// Partie creerEquipe.html

function afficherOptions(){
    let pays = document.getElementById('selectPays').value;
    let options = '';

    if (pays == "Angleterre"){
        options += "<option>Premier League</option>";
        options += "<option>Championship</option>";
        options += "<option>League One</option>";
    } else if (pays == "Espagne"){
        options += "<option>Liga </option>";
        options += "<option>Segunda Division</option>";
        options += "<option>Tercera Division</option>";
    } else if (pays == "Allemagne"){
        options += "<option>Bundesliga</option>";
        options += "<option>Bundesliga 2</option>";
        options += "<option>Regionlliga</option>";
    } else if (pays == "Italie"){
        options += "<option>Serie A</option>";
        options += "<option>Serie B</option>";
        options += "<option>Serie C</option>";
    } else if (pays == "France"){
        options += "<option>Ligue 1 </option>";
        options += "<option>Ligue 2 </option>";
        options += "<option>National</option>";
    }

    document.getElementById("ligueOptions").innerHTML = options;
}
// Fin partie creerEquipe.html

// Partie afficherEquipe.html
function modifierEquipe(){
    let form = '';
    
    annulerSupp();
    
    form += "<form action='controleur.php?page=modifierEquipe' method='post'> <fieldset>";
    form += "<label for='nomEquipe' id='nom' name='nom'><b>Modifier les informations de mon équipe</b></label>";
    form += "<input type='text' id='nom' name='nomEquipe' placeholder='Nouveau nom de l&#39;équipe' required>";
    form += "<input type='submit' id='id_val' value='Changer le nom'>";
    form += "<input type='button' id='id_val' value='Annuler' onclick='annulerModif()'></button>";
    form += "</fieldset> </form>"

    document.getElementById("champsForm").innerHTML = form;
}

function supprimerEquipe(){
    let form = '';

    annulerModif();
    
    form += "<form action='controleur.php?page=supprimerEquipe' method='post'>";
    form += "<label for='etre-sur' id='sur' name='sur'><b>Etes-vous sur de supprimer votre équipe ?</b>";
    form += "<input type='submit' id='id_val' value='Oui'>";
    form += "<input type='button' id='id_val' Value='Non' onclick='annulerSupp()'></button>";
    form += "</form>";

    document.getElementById('suppForm').innerHTML = form;
}

function annulerModif(){
    let form = '';
    document.getElementById('champsForm').innerHTML = form;
}

function annulerSupp(){
    let form = '';
    document.getElementById('suppForm').innerHTML = form;
}

// Fin partie afficherEquipe


// Partie gererJoueur.html
document.addEventListener("DOMContentLoaded", function() {
    gererJoueur();
});

// Fonction qui va générer boutons pour afficher les formulaires pour modifier, supprimer et ajouter des joueurs
function gererJoueur(){ 
    let sHTML = document.getElementById('joueurForm').innerHTML;

    sHTML += "<button id='ajouterJoueur' name='ajouter' onclick='ajouterJoueur()'>Ajouter joueur</button>";
    sHTML += "<button id='modifierJoueur' name='modifier' onclick='modifierJoueur()'>Modifier joueur</button>";
    sHTML += "<button id='supprimerJoueur' name='supprimer' onclick='supprimerJoueur()'>Supprimer joueur</button>";

    document.getElementById("joueurForm").innerHTML = sHTML;
}

function ajouterJoueur() {
    let formAjouter = "";

    formAjouter += "<h4>Ajouter un joueur</h4>";
    formAjouter += "<form action='controleur.php?page=ajouterJoueur' method='post'> <fieldset>";
    formAjouter += "<label for='numJoueur'> <b>Numéro</b>";
    formAjouter += "<input type='text' id='numJoueur' name='numJoueur' placeholder='Numéro de votre joueur' required>";
    formAjouter += "<label for='nomJoueur'> <b>Nom</b>";
    formAjouter += "<input type='text' id='nomJoueur' name='nomJoueur' placeholder='Nom de votre joueur' required>";
    formAjouter += "<label for='prenomJoueur'> <b>Prénom</b>";
    formAjouter += "<input type='text' id='prenomJoueur' name='prenomJoueur' placeholder='Prénom de votre joueur' required>";
    formAjouter += "<label for='nationaliteJoueur'> <b>Nationalité</b>";
    formAjouter += "<input type='text' id='nationaliteJoueur' name='nationaliteJoueur' placeholder='Nationalité de votre joueur' required>";
    formAjouter += "<label for='poste'> <b>Poste</b>";
    formAjouter += "<select name='posteJoueur' id='poste-select'>";
    formAjouter += "<option value='Attaquant'>Attaquant</option>";
    formAjouter += "<option value='Milieu'>Milieu</option>";
    formAjouter += "<option value='Défense'>Défense</option></select>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerJoueur()'>";
    formAjouter += "</fieldset> </form>";

    document.getElementById("gererJoueurForm").innerHTML = formAjouter;



    document.getElementById("id_num").addEventListener("input", function(event) {
        var valeur = parseInt(event.target.value);
        if (isNaN(valeur) || valeur < 1 || valeur > 100) {
            event.target.setCustomValidity("Veuillez saisir un nombre entre 1 et 100.");
        } else {
            event.target.setCustomValidity("");
        }
    });
}

function modifierJoueur(){
    formModifier = "";

    formModifier += "<h4>Modifier un jouer</h4>";
    formModifier += "<form action='controleur.php?page=gererJoueur' method='post'> <fieldset>";
    formModifier += "<label for='modif'> <b>Numéro</b>";
    
}

function supprimerJoueur(){
    // ou sinon faire tout dans une fonction la section
}

function annulerJoueur(){
    sHTML = '';
    document.getElementById("gererJoueurForm").innerHTML = sHTML;
}
// Fin partie afficherEquipe*