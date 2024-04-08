<?php



// Classe équipe qui va gérer tout ce qui est lié à la gestion de l'équipe de l'utilisateur
class Equipe {


    protected $login; // Son login ($_SESSION['login'])
    protected $pdo; // Objet pour accéder base de données
    protected $tbs; // Objet TBS
    protected $data_equipe; // Data pour regroupper données de l'équipe

    protected $messageEquipe, $id_entraineur, $id_equipe;
    protected $nom_equipe, $pays, $ligue;


    function __construct($p_login, $p_pdo, $p_tbs){
        $this->login = $p_login;
        $this->pdo = $p_pdo;
        $this->tbs = $p_tbs;
    }

    public function getMessage(){
        return $this->messageEquipe;
    }

    public function idEntraineur(){
        $req_id_entraineur = $this->pdo->prepare("SELECT id_entraineur FROM entraineur WHERE mail = :mail");
        $req_id_entraineur->execute(['mail' => $this->login]);
        $this->id_entraineur = $req_id_entraineur->fetchColumn();
    }

    public function getIdEntraineur(){
        return $this->id_entraineur;
    }

    public function idEquipe(){
        $this->idEntraineur();
        $req_id_equipe = $this->pdo->prepare("SELECT id_equipe FROM equipe WHERE id_entraineur = :id_entraineur");
        $req_id_equipe->execute(['id_entraineur' => $this->id_entraineur]);
        $this->idEquipe = $req_id_equipe->fetchColumn();
    }

    public function getIdEquipe(){
        return $this->idEquipe;
    }


    // Fonction pour vérifier si l'utilisateur a au moins 1 équipe
    public function verif(){
        $this->idEntraineur();

        $req = $this->pdo->prepare("SELECT * FROM equipe WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $result = $req->rowCount();

        if ($result == 1){
            $aEquipe = True;
            $this->preparer();
            header("Location: controleur.php?page=afficherEquipe");
            exit;
        } else {
            $this->messageEquipe = "Commencez à créer une équipe !";
        } 
    }

    public function creer($p_nom, $p_pays, $p_ligue){
    
        $this->idEntraineur();

        $q = $this->pdo->prepare("INSERT INTO equipe (nom_equipe, pays, ligue, id_entraineur) VALUES (:nom_equipe, :pays, :ligue, :id_entraineur)");
        $q->execute([
            'nom_equipe' => $p_nom,
            'pays' => $p_pays,
            'ligue' => $p_ligue, 
            'id_entraineur' => $this->getIdEntraineur()
        ]);

        header("Location: controleur.php?page=afficherEquipe");
        echo "Equipe créée";
        exit;

    }

    // Fonction pour preparer les données de l'équipe de foot
    public function preparer(){
        $this->idEntraineur(); // Appel fonction idEntraineur() pour récupérer la clé
      
        $req = $this->pdo->prepare("SELECT * FROM equipe WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $result = $req->rowCount();  // Vérifier si l'entraineur possède bien qu'une seule équipe (pas obligé)
        

        if ($result == 1){
            $this->idEntraineur();

            $req2 = $this->pdo->prepare("SELECT nom_equipe, pays, ligue FROM equipe WHERE id_entraineur = :id_entraineur");
            $req2->execute(['id_entraineur' => $this->id_entraineur]);
            $this->data_equipe = $req2->fetchAll();

            $this->nom_equipe = array();
            $this->pays = array();
            $this->ligue = array();

            foreach($this->data_equipe as $ligne){      
                $this->nom_equipe[] = $ligne['nom_equipe'];
                $this->pays[] = $ligne['pays'];
                $this->ligue[] = $ligne['ligue'];
            }
            
        } else {
            $this->message = "Erreur : Pas d'équipe";
            header("Location: controleur.php?page=index");
            exit;
        }
    }

    public function getNomEquipe(){
        return $this->nom_equipe[0];
    }

    public function getPaysEquipe(){
        return $this->pays[0];
    }

    public function getLigue(){
        return $this->ligue[0];
    }
}

class Joueurs extends Equipe {
    private $data_joueurs;
    private $message_joueur;

    // Fonction pour ajouter des joueurs
    public function ajouterJoueur($p_num, $p_nom_joueur, $p_prenom_joueur, $p_nationalite, $p_poste){
        $this->idEntraineur();
        $this->idEquipe();
        $numJoueur = intval($p_num);

        $req = $this->pdo->prepare("SELECT num_joueur, id_equipe FROM joueur WHERE num_joueur = :num_joueur AND id_equipe = :id_equipe");
        $req->execute(['num_joueur' => $numJoueur, 'id_equipe' => $this->idEquipe]);
        $result = $req->rowCount(); 

        if ($result == 0){
           
            $req2 = $this->pdo->prepare("INSERT INTO joueur (num_joueur, nom_joueur, prenom_joueur,
                             nationalité, poste, id_equipe) VALUES (:num_joueur, :nom_joueur,
                             :prenom_joueur, :nationalite, :poste, :id_equipe)");

            $req2->execute([
                'num_joueur' => $numJoueur,
                'nom_joueur' => $p_nom_joueur,
                'prenom_joueur' => $p_prenom_joueur,
                'nationalite' => $p_nationalite,
                'poste' => $p_poste,
                'id_equipe' => $this->idEquipe
            ]);
         
            header("Location: controleur.php?page=gererJoueur");
            exit;
        } else echo "Le numéro du joueur existe déjà !";
    }

    public function getMessageJoueur(){
        return $this->message_joueur;
    }

    public function afficherJoueur(){
        $this->idEquipe();

        $req = $this->pdo->prepare("SELECT num_joueur, nom_joueur, prenom_joueur, nationalité, poste FROM joueur WHERE id_equipe = :id_equipe");
        $req->execute(['id_equipe' => $this->idEquipe]);
        $this->data_joueurs = $req->fetchAll();

      
        $i = 0;
        $num = array();
        $nom = array();
        $prenom = array();
        $nationalite = array();
        $poste = array();

        if (empty($this->data_joueurs)){

        } else {
            $req3 = $this->pdo->prepare("DELETE FROM joueur WHERE num_joueur = :num_joueur");
            $req3->execute(['num_joueur' => -1]);

            foreach($this->data_joueurs as $ligne){      
                $num[$i++] = $ligne['num_joueur'];
                $nom[$i++] = $ligne['nom_joueur'];
                $prenom[$i++] = $ligne['prenom_joueur'];
                $nationalite[$i++] = $ligne['nationalité'];
                $poste[$i++] = $ligne['poste'];
            }
        }
        $this->tbs->LoadTemplate("gererJoueur.html");
        $this->tbs->MergeBlock("num_joueur", $num);
        $this->tbs->MergeBlock("nom_joueur", $nom);
        $this->tbs->MergeBlock("prenom_joueur", $prenom);
        $this->tbs->MergeBlock("nationalité", $nationalite);
        $this->tbs->MergeBlock("poste", $poste);
        $this->tbs->Show();
    }
}
    
        
    // Faire fonction supprimerJoueur(), modifierJoueur(), 

class Staff {
    private $nom_staff, $prenom_staff, $role;

    // Faire constructeur
    // Faire fonction : ajouterStaff(), afficherStaff(), supprimerStaff(), modifierStaff(),
}

class Blessure {
    private $date_blessure, $type_blessure, $duree_blessure;

    // Faire conconstructeur
    // Faire fonction : ajouterBlessure(), afficherBlessure(), modifierBlessure(), supprimerBlessure();
} 

// Class match et participation
?>