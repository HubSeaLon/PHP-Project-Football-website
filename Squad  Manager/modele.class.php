<?php


class Equipe {

    private $login;
    private $pdo;
    private $tbs;
    private $data;

    private $message, $id_entraineur;
    private $nom, $pays, $ligue;

    function __construct($p_login, $p_pdo, $p_tbs){
        $this->login = $p_login;
        $this->pdo = $p_pdo;
        $this->tbs = $p_tbs;
    }

    public function getMessage(){
        return $this->message;
    }

    public function getIdEntraineur(){
        return $this->id_entraineur;
    }

    public function idEntraineur(){
        $req_id_entraineur = $this->pdo->prepare("SELECT id_entraineur FROM entraineur WHERE mail = :mail");
        $req_id_entraineur->execute(['mail' => $this->login]);
        $this->id_entraineur = $req_id_entraineur->fetchColumn();
    }


    // Fonction pour vérifier si l'utilisateur a au moins 1 équipe
    public function verif(){

        $this->idEntraineur();

        $req = $this->pdo->prepare("SELECT * FROM equipe WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $result = $req->rowCount();

        if ($result == 1){
            $this->preparer();
            header("Location: controleur.php?page=afficherEquipe");
            exit;
        } else {
            $this->message = "Commencez à créer une équipe !";
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
            $this->data = $req2->fetchAll();

            $this->nom = array();
            $this->pays = array();
            $this->ligue = array();

            foreach($this->data as $ligne){      
                $this->nom[] = $ligne['nom_equipe'];
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
        return $this->nom[0];
    }

    public function getPaysEquipe(){
        return $this->pays[0];
    }

    public function getLigue(){
        return $this->ligue[0];
    }
}

?>