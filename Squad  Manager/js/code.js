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

function modifierEquipe(){
    let form = '';
    
    form += "<form action='controleur.php?page=modifierEquipe' method='post'> <fieldset>";
    form += "<legend> <b>Modifier les informations de mon équipe</b> </legend>";
    form += "<label for='nomEquipe' id='nom' name='nom'><b>Nom</b></label>";
    form += "<input type='text' id='nom' name='nomEquipe' placeholder='Nouveau nom de l&#39;équipe' required>";
    form += "<input type='submit' id='id_val' value='Changer le nom'>";
    form += "<input type='button' id='id_val' value='Annuler' onclick='annuler()'></button>";
    form += "</fieldset> </form>"

    document.getElementById("champsForm").innerHTML = form;
}

function supprimerEquipe(){
    let form = '';

    form += "<form action='controleur.php?page=supprimerEquipe' method='post'>";
    form += "<label for='etre-sur' id='sur' name='sur'><b>Etes-vous sur de supprimer votre équipe ?</b>";
    form += "<input type='submit' id='id_val' value='Oui'>";
    form += "<input type='button' id='id_val' Value='Non' onclick='annuler()'></button>";
    form += "</form>";

    document.getElementById('suppForm').innerHTML = form;
}

function annuler(){
    location.reload();
}