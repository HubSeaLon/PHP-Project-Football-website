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
    
    form += "<form action='controleur.php?page=modifierEquipe' method='post'>";
    form += "<label for='nomEquipe' id='nom' name='nom'><b>Modifier les informations de mon équipe</b>";
    form += "<input type='text' id='nom' name='nomEquipe' placeholder='Nouveau nom de l&#39;équipe' required>";
    form += "<input type='submit' id='id_val' value='Changer le nom'>";
    form += "<input type='button' id='id_val' value='Annuler' onclick='annulerModif()'>";
    form += "</form>"

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

    sHTML += "<button id='ajouterJoueur' name='ajouter' onclick='ajouterJoueur()'>Ajouter ou Modifier un joueur</button>";
    sHTML += "<button id='supprimerJoueur' name='supprimer' onclick='supprimerJoueur()'>Supprimer joueur</button>";

    document.getElementById("joueurForm").innerHTML = sHTML;
}

function ajouterJoueur() {
    let formAjouter = "";

    formAjouter += "<div class='ajoutClass' id='id_ajout'>";
    formAjouter += "<h4>Pour modifier un joueur, mettez un numéro valide sinon ajouter</h4>";
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
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerAjoutJoueur()'>";
    formAjouter += "</fieldset> </form>";
    formAjouter += "</div>";

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


function supprimerJoueur(){
    formSupp = "";

    formSupp += "<div class='suppClass' id='id_supp'>";
    formSupp += "<h4> Supprimer un joueur en mettant son numéro</h4>";
    formSupp += "<form action='controleur.php?page=supprimerJoueur' method='post'> <fieldset>";
    formSupp += "<label for='numJoueur'> <b>Numéro du joueur à supprimer</b>";
    formSupp += "<input type='text' id='numJoueur' name='numJoueur' placeholder='Numéro de votre joueur' required>";
    formSupp += "<input type='submit' id ='id_val' value='Valider'>";
    formSupp += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerSuppJoueur()'>";
    formSupp += "</fieldset> </form>";

    document.getElementById("gererJoueurForm").innerHTML = formSupp;
    document.getElementById("id_num").addEventListener("input", function(event) {
        var valeur = parseInt(event.target.value);
        if (isNaN(valeur) || valeur < 1 || valeur > 100) {
            event.target.setCustomValidity("Veuillez saisir un nombre entre 1 et 100.");
        } else {
            event.target.setCustomValidity("");
        }
    });
}

function annulerAjoutJoueur(){
    sHTML = '';
    document.getElementById("id_ajout").innerHTML = sHTML;
}
function annulerSuppJoueur(){
    sHTML = '';
    document.getElementById("id_supp").innerHTML = sHTML;
}

// Fin partie gererJoueur 



// Partie gererStaff

document.addEventListener("DOMContentLoaded", function() {
    gererStaff();
});

// Fonction qui va générer boutons pour afficher les formulaires pour modifier, supprimer et ajouter des joueurs
function gererStaff(){ 
    let sHTML = document.getElementById('staffForm').innerHTML;

    sHTML += "<button id='ajouterStaff' name='ajouter' onclick='ajouterStaff()'>Ajouter ou Modifier un staff</button>";
    sHTML += "<button id='supprimerStaff' name='supprimer' onclick='supprimerStaff()'>Supprimer staff</button>";

    document.getElementById("staffForm").innerHTML = sHTML;
}

function ajouterStaff() {
    let formAjouter = "";

    formAjouter += "<div class='ajoutClass' id='id_ajout'>";
    formAjouter += "<h4>Pour modifier un staff, mettez un numéro valide sinon ajouter</h4>";
    formAjouter += "<form action='controleur.php?page=ajouterStaff' method='post'> <fieldset>";
    formAjouter += "<label for='numStaff'> <b>Numéro</b>";
    formAjouter += "<input type='text' id='num' name='numStaff' placeholder='Numéro du staff à modifier'>";
    formAjouter += "<label for='nomStaff'> <b>Nom</b>";
    formAjouter += "<input type='text' id='nom' name='nomStaff' placeholder='Nom du staff' required>";
    formAjouter += "<label for='prenomStaff'> <b>Prénom</b>";
    formAjouter += "<input type='text' id='prenom' name='prenomStaff' placeholder='Prenom du staff' required>";
    formAjouter += "<label for='roleStaff'> <b>Rôle</b>";
    formAjouter += "<input type='text' id='role' name='roleStaff' placeholder='Rôle de votre staff' required>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerAjoutStaff()'>";
    formAjouter += "</fieldset> </form>";
    formAjouter += "</div>";

    document.getElementById("gererStaffForm").innerHTML = formAjouter;
}


function supprimerStaff(){
    formSupp = "";

    formSupp += "<div class='suppClass' id='id_supp'>";
    formSupp += "<h4> Supprimer un staff en mettant son num</h4>";
    formSupp += "<form action='controleur.php?page=supprimerStaff' method='post'> <fieldset>";
    formSupp += "<label for='numStaff'> <b>Num du staff à supprimer</b>";
    formSupp += "<input type='text' id='num' name='numStaff' placeholder='Num de votre staff' required>";
    formSupp += "<input type='submit' id ='id_val' value='Valider'>";
    formSupp += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerSuppStaff()'>";
    formSupp += "</fieldset> </form>";

    document.getElementById("gererStaffForm").innerHTML = formSupp;
    document.getElementById("num").addEventListener("input", function(event) {
        var valeur = parseInt(event.target.value);
        if (isNaN(valeur) || valeur < 1 || valeur > 10000) {
            event.target.setCustomValidity("Veuillez saisir un nombre correct");
        } else {
            event.target.setCustomValidity("");
        }
    });

}

function annulerAjoutStaff(){
    sHTML = '';
    document.getElementById("id_ajout").innerHTML = sHTML;
}
function annulerSuppStaff(){
    sHTML = '';
    document.getElementById("id_supp").innerHTML = sHTML;
}

// Fin partie gererStaff

// Partie gererBlessure 

document.addEventListener("DOMContentLoaded", function() {
    gererBlessure();
});


// Fonction qui va générer boutons pour afficher les formulaires pour modifier, supprimer et ajouter des joueurs
function gererBlessure(){ 
    let sHTML = document.getElementById('blessureForm').innerHTML;

    sHTML += "<button id='ajouterBlessure' name='ajouter' onclick='ajouterBlessure()'>Ajouter ou Modifier une blessure</button>";
    sHTML += "<button id='supprimerBlessure' name='supprimer' onclick='supprimerBlessure()'>Supprimer blessure</button>";

    document.getElementById("blessureForm").innerHTML = sHTML;
}

function ajouterBlessure() {
    let formAjouter = "";

    formAjouter += "<div class='ajoutClass' id='id_ajout'>";
    formAjouter += "<h4>Pour modifier une blessure, mettez un numéro valide sinon ajouter</h4>";
    formAjouter += "<form action='controleur.php?page=ajouterBlessure' method='post'> <fieldset>";
    formAjouter += "<label for='numBlessure'> <b>Numéro</b>";
    formAjouter += "<input type='text' id='num' name='numBlessure' placeholder='Numéro blessure à modifier'>";
    formAjouter += "<label for='dateBlessure'> <b>Date</b>";
    formAjouter += "<input type='date' id='date' name='dateBlessure' placeholder='Date blessure' required>";
    formAjouter += "<label for='typeBlessure'> <b>Type</b>";
    formAjouter += "<input type='text' id='type' name='typeBlessure' placeholder='Type de blessure' required>";
    formAjouter += "<label for='dureeBlessure'> <b>Durée (jours)</b>";
    formAjouter += "<input type='text' id='duree' name='dureeBlessure' placeholder='Durée de la blessure' required>";
    formAjouter += "<label for='numJoueur'> <b>Numéro joueur (pas l'ID)</b>";
    formAjouter += "<input type='text' id='numJ' name='numJoueur' placeholder='Numéro du joueur' required>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerAjoutBlessure()'>";
    formAjouter += "</fieldset> </form>";
    formAjouter += "</div>";

    document.getElementById("gererBlessureForm").innerHTML = formAjouter;
    document.getElementById("dureeBlessure").addEventListener("input", function(event) {
        var valeur = parseInt(event.target.value);
        if (isNaN(valeur) || valeur < 1 || valeur > 10000) {
            event.target.setCustomValidity("Veuillez saisir un nombre correct");
        } else {
            event.target.setCustomValidity("");
        }
    });
}




function supprimerBlessure(){
    formSupp = "";

    formSupp += "<div class='suppClass' id='id_supp'>";
    formSupp += "<h4> Supprimer une blessure en mettant son num</h4>";
    formSupp += "<form action='controleur.php?page=supprimerBlessure' method='post'> <fieldset>";
    formSupp += "<label for='numBlessure'> <b>Num de la blessure à supprimer</b>";
    formSupp += "<input type='text' id='num' name='numBlessure' placeholder='Num de la blessure' required>";
    formSupp += "<input type='submit' id ='id_val' value='Valider'>";
    formSupp += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerSuppBlessure()'>";
    formSupp += "</fieldset> </form>";

    document.getElementById("gererBlessureForm").innerHTML = formSupp;
    document.getElementById("num").addEventListener("input", function(event) {
        var valeur = parseInt(event.target.value);
        if (isNaN(valeur) || valeur < 1 || valeur > 10000) {
            event.target.setCustomValidity("Veuillez saisir un nombre correct");
        } else {
            event.target.setCustomValidity("");
        }
    });
}

function annulerAjoutBlessure(){
    sHTML = '';
    document.getElementById("id_ajout").innerHTML = sHTML;
}
function annulerSuppBlessure(){
    sHTML = '';
    document.getElementById("id_supp").innerHTML = sHTML;
}



// Gestion Match 

document.addEventListener("DOMContentLoaded", function() {
    gererMatch();
});


function gererMatch(){
    let sHTML = document.getElementById('matchForm').innerHTML;

    sHTML += "<button id='ajouterMatch' name='ajouter' onclick='ajouterMatch()'>Ajouter ou modifier un match</button>";
    sHTML += "<button id='ajouterScoreMatch' name='score' onclick='ajouterScore()'>Ajouter ou modifier score</button>";
    document.getElementById("matchForm").innerHTML = sHTML;
}


function ajouterMatch() {
    let formAjouter = "";

    formAjouter += "<div class='ajoutClass' id='id_ajout'>";
    formAjouter += "<h4>Pour modifier un match, mettez un numéro valide sinon ajouter</h4>";
    formAjouter += "<form action='controleur.php?page=ajouterMatchs' method='post'> <fieldset>";
    formAjouter += "<label for='numMatch'> <b>Numéro match</b>";
    formAjouter += "<input type='text' id='num' name='numMatch' placeholder='Numéro match à modifier'>";
    formAjouter += "<label for='equipeAdverse'> <b>Equipe adverse</b>";
    formAjouter += "<input type='text' id='adverse' name='equipeAdverse' placeholder='Nom equipe adverse' required> ";
    formAjouter += "<label for='dateMatch'> <b>Date</b>";
    formAjouter += "<input type='date' id='date' name='dateMatch' placeholder='Date du match' required>";
    formAjouter += "<label for='typeMatch'> <b>Type match</b>";
    formAjouter += "<select name='typeMatch'>";
    formAjouter += "<option selected>Championnat</option>";
    formAjouter += "<option>Coupe nationale</option>";
    formAjouter += "<option>Coupe européenne</option>";
    formAjouter += "<option>Autre</option>";
    formAjouter += "</select>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerAjoutMatch()'>";
    formAjouter += "</fieldset> </form>";
    formAjouter += "</div>";

    document.getElementById("gererMatchForm").innerHTML = formAjouter;
}

function validerNombrePositif(event) {
    var valeur = event.target.value.trim(); 
    if (valeur === "") {
        event.target.setCustomValidity(""); 
    } else {
        // Vérifier si la valeur est un nombre positif ou égal à zéro
        var nombre = parseFloat(valeur);
        if (isNaN(nombre) || nombre < 0) {
            event.target.setCustomValidity("Veuillez saisir un nombre positif ou égal à zéro");
        } else {
            event.target.setCustomValidity(""); 
        }
    }
}

function annulerAjoutMatch(){
    sHTML = '';
    document.getElementById("id_ajout").innerHTML = sHTML;
}


function ajouterScore(){
    formAjouter = "";

    formAjouter += "<div class='ajoutClass' id='id_score'>";        
    formAjouter += "<form action='controleur.php?page=ajouterScore' method='post'>";
    formAjouter += "<label for='numMatch'> <b>Numéro match</b>";
    formAjouter += "<input type='text' id='num' name='numMatch' placeholder='Numéro match à modifier ou ajouter' required>";
    formAjouter += "<label for='scoreEquipe'> <b>Score équipe</b>";
    formAjouter += "<input type='text' id='scoreE' name='scoreEquipe' placeholder='Score équipe' required>";
    formAjouter += "<label for='scoreEquipeAdverse'> <b>Score équipe adverse</b>";
    formAjouter += "<input type='text' id='scoreEA' name='scoreEquipeAdverse' placeholder='Score équipe adverse' required>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerScoreMatch()'>";
    formAjouter += "</form>";
    formAjouter += "</div>";

    document.getElementById("gererMatchForm").innerHTML = formAjouter;
    document.getElementById("scoreE").addEventListener("input", validerNombrePositif);
    document.getElementById("scoreEA").addEventListener("input", validerNombrePositif);
}

function annulerScoreMatch(){
    sHTML = '';
    document.getElementById("id_score").innerHTML = sHTML;
}


// Gestion Participation


document.addEventListener("DOMContentLoaded", function() {
    gererParticipation();
});


function gererParticipation(){

    let sHTML = document.getElementById('convocForm').innerHTML;

    sHTML += "<button id='convoquer name='convoquerJoueur' onclick='convoquerJoueur()'>Choisir un match pour convoquer</button>";
    document.getElementById("convocForm").innerHTML = sHTML;
}


function convoquerJoueur(){
    let formAjouter = "";

    formAjouter += "<div class ='convoquerClass' id='id_convoq'>";
    formAjouter += "<h4>Choisir match pour convoquer joueur (minimum 15 joueurs et 11 titulaires)</h4>";
    formAjouter += "<form action='controleur.php?page=convoquerJoueur' method='post'> <fieldset>";
    formAjouter += "<label for='numMatch'> <b>Numéro match</b>";
    formAjouter += "<input type='text' id='numM' name='numMatch' placeholder='Numéro match' required>";
    formAjouter += "<label for='numJoueur'> <b>l'ID du joueur</b>";
    formAjouter += "<input type='text' id='numJ' name ='numJoueur' placeholder='ID de votre joueur' required>";
    formAjouter += "<label for='statut'> <b>Poste</b>";
    formAjouter += "<select name='poste'>";
    formAjouter += "<option selected>Titulaire</option>";
    formAjouter += "<option>Remplaçant</options>";
    formAjouter += "</select>";
    formAjouter += "<input type='submit' id ='id_val' value='Valider'>";
    formAjouter += "<input type='button' id ='id_val' value='Annuler'  onclick='annulerConvoquerJoueur()'>";
    formAjouter += "</fieldset> </form>";
    formAjouter += "</div>";

    document.getElementById("gererConvocForm").innerHTML = formAjouter;

    document.getElementById("numM").addEventListener("input", validerNombrePositif);
    document.getElementById("numJ").addEventListener("input", validerNombrePositif);
    
}



function annulerConvoquerJoueur(){
    sHTML = '';
    document.getElementById("id_convoq").innerHTML = sHTML;
}




