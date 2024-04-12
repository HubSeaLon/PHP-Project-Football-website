<?php


// -- Fonction inscription utilisateur -- //


function nouveauID ($p_login, $p_password, $p_verif, $c){
    
    if(!empty($p_login) && !empty($p_password) && !empty($p_verif)) {
        if($p_password == $p_verif){
            $options = [ // Hashage du mot de passe pour la sécurité 
                'cost' => 12, 
            ];

            $hashpass = password_hash($p_password, PASSWORD_BCRYPT, $options);
                        
            try {
                $s = $c->prepare("SELECT mail FROM entraineur WHERE mail = :mail"); // Requete pour vérifier si le mail n'existe pas
                $s->execute(['mail' => $p_login]);
                            
                $resultat = $s->rowCount(); 

                if($resultat == 0){ // Si le mail n'existe pas 
                    $q = $c->prepare("INSERT INTO entraineur (mail, mdp) VALUES (:mail, :mdp)"); // Insérer le nouvel utilisateur
                    $q->execute([
                        'mail' => $p_login,
                        'mdp' => $hashpass
                    ]);
                    
                    header("Location: controleur.php?page=login");
                    echo 'Nouvel utilisateur enregistré';
                    exit;
                } else echo 'Un email existe déjà !';
                        
            } catch (PDOException $e) {
                echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
            }                    
        } else echo "Les mots de passe ne correspondent pas";
    } else echo "Veuillez compléter les champs";
}

// -- Fonction connexion utilisateur -- //

function connexionID($p_login, $p_password, $c){

    if (!empty($p_login) && !empty($p_password)){
        $q = $c->prepare("SELECT * FROM entraineur WHERE mail = :mail"); // requete pour vérifier si le mail existe
        $q->execute(['mail' => $p_login]);
        $result = $q->fetch();

        if ($result == true){ 
            $hashpassword = $result['mdp'];

            if(password_verify($p_password, $hashpassword)){ // Fonction password_verify pour vérifier le mot de passe hashé dans la BD
                echo "Mot de passe correct, connexion à votre compte";    
                $_SESSION['login'] = $result['mail']; // On affecte le login de l'user à la variable session 
                header("Location: controleur.php?page=index");
                exit;
            } else echo "Mot de passe incorrect";

        } else echo "L'email n'existe pas";

    } else echo "Veuillez compléter l'ensemble des champs";
}

?>