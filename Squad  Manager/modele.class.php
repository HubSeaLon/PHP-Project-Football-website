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
    private $numJoueur;
    protected $id_joueur;

    // Fonction pour ajouter des joueurs
    public function ajouterJoueur($p_num, $p_nom_joueur, $p_prenom_joueur, $p_nationalite, $p_poste){
        $this->idEntraineur();
        $this->idEquipe();
        $this->numJoueur = intval($p_num);

        $req = $this->pdo->prepare("SELECT num_joueur, id_equipe FROM joueur WHERE num_joueur = :num_joueur AND id_equipe = :id_equipe");
        $req->execute(['num_joueur' => $this->numJoueur, 'id_equipe' => $this->idEquipe]);
        $result = $req->rowCount(); 

        if ($result == 0){
           
            $req2 = $this->pdo->prepare("INSERT INTO joueur (num_joueur, nom_joueur, prenom_joueur,
                             nationalité, poste, id_equipe) VALUES (:num_joueur, :nom_joueur,
                             :prenom_joueur, :nationalite, :poste, :id_equipe)");

            $req2->execute([
                'num_joueur' => $this->numJoueur,
                'nom_joueur' => $p_nom_joueur,
                'prenom_joueur' => $p_prenom_joueur,
                'nationalite' => $p_nationalite,
                'poste' => $p_poste,
                'id_equipe' => $this->idEquipe
                ]);

           
         
            header("Location: controleur.php?page=gererJoueur");
            exit;
        } else {  // Cas pour moodifier le joueur
            $req4 = $this->pdo->prepare("UPDATE joueur SET nom_joueur = :nom_joueur,
                                        prenom_joueur = :prenom_joueur, nationalité = :nationalite, poste = :poste
                                        WHERE id_equipe = :id_equipe AND num_joueur = :num_joueur");
            $req4->execute([
                            'nom_joueur' => $p_nom_joueur,
                            'prenom_joueur' => $p_prenom_joueur,
                            'nationalite' => $p_nationalite,
                            'poste' => $p_poste,
                            'id_equipe' => $this->idEquipe,
                            'num_joueur' => $this->numJoueur
                            ]);

            header("Location: controleur.php?page=gererJoueur");
            exit;
        }
    }

    public function getMessageJoueur(){
        return $this->message_joueur;
    }

    public function afficherJoueur(){
        $this->idEquipe();

        $req = $this->pdo->prepare("SELECT id_joueur, num_joueur, nom_joueur, prenom_joueur, nationalité, poste FROM joueur WHERE id_equipe = :id_equipe");
        $req->execute(['id_equipe' => $this->idEquipe]);
        $this->data_joueurs = $req->fetchAll();

      
        $i = 0;
        $id = array();
        $num = array();
        $nom = array();
        $prenom = array();
        $nationalite = array();
        $poste = array();

        if (empty($this->data_joueurs)){

        } else {
            foreach($this->data_joueurs as $ligne){   
                $id[$i++] = $ligne['id_joueur'];   
                $num[$i++] = $ligne['num_joueur'];
                $nom[$i++] = $ligne['nom_joueur'];
                $prenom[$i++] = $ligne['prenom_joueur'];
                $nationalite[$i++] = $ligne['nationalité'];
                $poste[$i++] = $ligne['poste'];
            }
        }
        $this->tbs->LoadTemplate("gererJoueur.html");
        $this->tbs->MergeBlock("id_joueur", $id);
        $this->tbs->MergeBlock("num_joueur", $num);
        $this->tbs->MergeBlock("nom_joueur", $nom);
        $this->tbs->MergeBlock("prenom_joueur", $prenom);
        $this->tbs->MergeBlock("nationalité", $nationalite);
        $this->tbs->MergeBlock("poste", $poste);
        $this->tbs->Show();
    }

    public function supprimerJoueur(){
        $this->idEquipe();
        $this->numJoueur = intval($_POST['numJoueur']);

        $req3 = $this->pdo->prepare("SELECT id_joueur, num_joueur FROM joueur WHERE id_equipe = :id_equipe AND num_joueur = :num_joueur");
        $req3->execute(['id_equipe' => $this->idEquipe, 'num_joueur' => $this->numJoueur]); 
        $id_joueur = $req3->fetchColumn();

        $req2 = $this->pdo->prepare("DELETE FROM participation WHERE id_joueur = :id_joueur");
        $req2->execute(['id_joueur' => $id_joueur]);

        $req4 = $this->pdo->prepare("DELETE FROM blessure WHERE id_joueur = :id_joueur");
        $req4->execute(['id_joueur' => $id_joueur]);


        $req = $this->pdo->prepare("DELETE FROM joueur WHERE num_joueur = :num_joueur AND id_equipe = :id_equipe");
        $req->execute(['num_joueur' => $this->numJoueur, 'id_equipe' => $this->idEquipe]);

        header("Location: controleur.php?page=gererJoueur");
        exit;
    }

    public function idJoueur(){
        $this->idEquipe();
        $req_id_joueur= $this->pdo->prepare("SELECT id_joueur FROM joueur WHERE id_equipe = :id_equipe");
        $req_id_joueur->execute(['id_equipe' => $this->idEquipe]);
        $this->id_joueur = $req_id_joueur->fetchColumn();
    }

    
    public function getIdJoueur(){
        return $this->id_joueur;
    }
}
      

class Staff extends Equipe {
    private $data_staff;

    public function ajouterStaff($p_nom_staff, $p_prenom_staff, $p_role){
        $this->idEquipe();
        $id_equipe = $this->getIdEquipe();

        $req = $this->pdo->prepare("INSERT INTO staff (nom, prenom,
                             role_staff, id_equipe) VALUES (:nom, :prenom,
                             :role_staff, :id_equipe)");
    
        $req->execute([
            'nom' => $p_nom_staff,
            'prenom' => $p_prenom_staff,
            'role_staff' => $p_role,
            'id_equipe' => $id_equipe
        ]);

        header("Location: controleur.php?page=gererStaff");
        exit;  
    }
   

    public function modifierStaff($p_num_staff, $p_nom_staff, $p_prenom_staff, $p_role){
        $this->idEquipe();
        $id_staff = intval($p_num_staff);


        $req = $this->pdo->prepare("SELECT id_staff, id_equipe FROM staff WHERE id_staff = :id_staff AND id_equipe = :id_equipe");
        $req->execute(['id_staff' => $id_staff, 'id_equipe' => $this->idEquipe]);
        $result = $req->rowCount(); 

        if ($result == 0){
           header("Location: controleur.php?page=gererStaff");
           exit;
        } else {  
            $req2 = $this->pdo->prepare("UPDATE staff SET nom = :nom,
                                        prenom = :prenom, role_staff = :role_staff WHERE id_staff = :id_staff AND id_equipe = :id_equipe");
            $req2->execute([
                            'nom' => $p_nom_staff,
                            'prenom' => $p_prenom_staff,
                            'role_staff' => $p_role,
                            'id_equipe' => $this->idEquipe,
                            'id_staff' => $id_staff
                            ]);

            header("Location: controleur.php?page=gererStaff");
            exit;
        }
    }

    public function afficherStaff(){
        $this->idEquipe();

        $req = $this->pdo->prepare("SELECT id_staff, nom, prenom, role_staff FROM staff WHERE id_equipe = :id_equipe");
        $req->execute(['id_equipe' => $this->idEquipe]);
        $this->data_staff = $req->fetchAll();

      
        $i = 0;
        $num = array();
        $nom = array();
        $prenom = array();
        $role = array();

        if (empty($this->data_staff)){

        } else {
            foreach($this->data_staff as $ligne){      
                $num[$i++] = $ligne['id_staff'];
                $nom[$i++] = $ligne['nom'];
                $prenom[$i++] = $ligne['prenom'];
                $role[$i++] = $ligne['role_staff'];
            }
        }
        $this->tbs->LoadTemplate("gererStaff.html");
        $this->tbs->MergeBlock("id_staff", $num);
        $this->tbs->MergeBlock("nom_staff", $nom);
        $this->tbs->MergeBlock("prenom_staff", $prenom);
        $this->tbs->MergeBlock("role_staff", $role);
        $this->tbs->Show();
    }


    public function supprimerStaff($p_num_staff){
        $this->idEquipe();
        $id_staff = intval($p_num_staff);

        $req = $this->pdo->prepare("DELETE FROM staff WHERE id_staff = :id_staff AND id_equipe = :id_equipe");
        $req->execute(['id_staff' => $id_staff, 'id_equipe' => $this->idEquipe]);

        header("Location: controleur.php?page=gererStaff");
        exit;
    }
}

class Blessure Extends Joueurs{
    private $data_blessures;

    public function ajouterBlessure($p_date, $p_type, $p_duree, $p_numJoueur){
        $this->idEquipe();
        $this->idJoueur();
        $num_joueur = intval($p_numJoueur);
    

        $req_joueur = $this->pdo->prepare("SELECT num_joueur, id_joueur FROM joueur WHERE id_equipe = :id_equipe AND id_joueur = :id_joueur");
        $req_joueur->execute(['id_equipe' => $this->idEquipe, 'id_joueur' => $this->id_joueur]);
        $result = $req_joueur->fetchAll();

        $numJoueurValide = false;
        $i = 0;
        foreach ($result as $row){
            if ($row['num_joueur'] == $num_joueur){
                $numJoueurValide = true;
                break;
            }
        }

        if ($numJoueurValide == true){
            $req = $this->pdo->prepare("INSERT INTO blessure (date_blessure, type_blessure,
                             duree_blessure, id_joueur) VALUES (:date_blessure, :type_blessure,
                             :duree_blessure, :id_joueur)");
    
            $req->execute([
                'date_blessure' => $p_date,
                'type_blessure' => $p_type,
                'duree_blessure' => $p_duree,
                'id_joueur' => $this->id_joueur
            ]);

            header("Location: controleur.php?page=gererBlessure");
            exit;
        } else {
            echo "Numéro de joueur invalide";
            header("Location: controleur.php?page=gererBlessure");
            exit;
        }
    }

    public function modifierBlessure($p_num, $p_date, $p_type, $p_duree){
        $this->idJoueur();
        $id_blessure = intval($p_num);

        $req = $this->pdo->prepare("SELECT id_blessure, id_joueur FROM blessure WHERE id_blessure = :id_blessure AND id_joueur = :id_joueur");
        $req->execute(['id_blessure' => $id_blessure, 'id_joueur' => $this->id_joueur]);
        $result = $req->rowCount(); 

        if ($result == 0){
           header("Location: controleur.php?page=gererBlessure");
           exit;
        } else {  
            $req2 = $this->pdo->prepare("UPDATE blessure SET date_blessure = :date_blessure,
                                        type_blessure = :type_blessure, duree_blessure = :duree_blessure WHERE id_blessure = :id_blessure AND id_joueur = :id_joueur");
            $req2->execute([
                            'date_blessure' => $p_date,
                            'type_blessure' => $p_type,
                            'duree_blessure' => $p_duree,
                            'id_blessure' => $id_blessure,
                            'id_joueur' => $this->id_joueur
                            ]);

            header("Location: controleur.php?page=gererBlessure");
            exit;
        }
    }

    public function afficherBlessure(){
        $this->idJoueur();

        $req = $this->pdo->prepare("SELECT * FROM blessure WHERE id_joueur = :id_joueur");
        $req->execute(['id_joueur' => $this->id_joueur]);
        $this->data_blessures = $req->fetchAll();

      
        $i = 0;
        $num= array();
        $date = array();
        $type = array();
        $duree = array();
        $numJ= array();

        if (empty($this->data_blessures)){

        } else {
            foreach($this->data_blessures as $ligne){      
                $num[$i++] = $ligne['id_blessure'];
                $date[$i++] = $ligne['date_blessure'];
                $type[$i++] = $ligne['type_blessure'];
                $duree[$i++] = $ligne['duree_blessure'];
                $numJ[$i++] = $ligne['id_joueur'];
            }
        }
        $this->tbs->LoadTemplate("gererBlessure.html");
        $this->tbs->MergeBlock("id_blessure", $num);
        $this->tbs->MergeBlock("date_blessure", $date);
        $this->tbs->MergeBlock("type_blessure", $type);
        $this->tbs->MergeBlock("duree_blessure", $duree);
        $this->tbs->MergeBlock("id_joueur", $numJ);
        $this->tbs->Show();
    }

    public function supprimerBlessure($p_num){
        $this->idJoueur();
        $id_blessure = intval($p_num);

        $req = $this->pdo->prepare("DELETE FROM blessure WHERE id_blessure = :id_blessure AND id_joueur = :id_joueur");
        $req->execute(['id_blessure' => $id_blessure, 'id_joueur' => $this->id_joueur]);

        header("Location: controleur.php?page=gererBlessure");
        exit;
    }
} 


class Matchs extends Joueurs {
    private $data_matchs;
    protected $id_match;

    public function idMatch(){
        $this->idEntraineur();
        $req_id_match= $this->pdo->prepare("SELECT id_match FROM `match` WHERE id_entraineur = :id_entraineur");
        $req_id_match->execute(['id_entraineur' => $this->id_entraineur]);
        $this->id_match = $req_id_match->fetchColumn();
    }

    public function ajouterMatch($p_nom_EA, $p_date, $p_type){
        $this->idEntraineur();
        $req = $this->pdo->prepare("INSERT INTO `match` (equipe_adverse,
                             date_match, type_match, id_entraineur) VALUES (:equipe_adverse, :date_match,
                             :type_match, :id_entraineur)");
    
        $req->execute([
            'equipe_adverse' => $p_nom_EA,
            'date_match' => $p_date,
            'type_match' => $p_type,
            'id_entraineur' => $this->id_entraineur
        ]);

        header("Location: controleur.php?page=gererMatchs");
        exit;  
    }

    public function modifierMatch($p_num, $p_nom_EA, $p_date, $p_type){
        $this->idEntraineur();
        $id_num = intval($p_num);

        $req = $this->pdo->prepare("SELECT id_match FROM `match` WHERE id_match = :id_match and id_entraineur = :id_entraineur");
        $req->execute(['id_match' => $id_num, 'id_entraineur' => $id_entraineur]);
        $result = $req->rowCount(); 

        if ($result == 0){
            echo "Mauvais numéro";
            header("Location: controleur.php?page=gererMatchs");
            exit;
        } else {  
            $req2 = $this->pdo->prepare("UPDATE `match` SET equipe_adverse = :equipe_adverse,
                                        date_match = :date_match, type_match = :type_match WHERE id_match = :id_match and id_entraineur = :id_entraineur");
            $req2->execute([
                            'equipe_adverse' => $p_nom_EA,
                            'date_match' => $p_date,
                            'type_match' => $p_type,
                            'id_match' => $id_num,
                            'id_entraineur' => $id_entraineur
                            ]);

            header("Location: controleur.php?page=gererMatchs");
            exit;
        }
    }


    public function ajouterScore($p_num, $p_score_E, $p_score_EA){
        $this->idEntraineur();

        $id_num = intval($p_num);
        $scoreE = intval($p_score_E);
        $scoreEA = intval($p_score_EA);

        $req = $this->pdo->prepare("SELECT score_equipe, score_equipe_adverse FROM `match` WHERE id_match = :id_match AND id_entraineur = :id_entraineur");
        $req->execute(['id_match' => $id_num, 
                        'id_entraineur' => $this->id_entraineur
                       ]);
        $result = $req->fetchAll(); 

        if (empty($result)) {
            $req2 = $this->pdo->prepare("INSERT INTO `match` (score_equipe,
                                    score_equipe_adverse, statut_match) VALUES (:score_equipe, :score_equipe_adverse
                                    ) WHERE id_entraineur = :id_entraineur AND id_match = :id_match");
            $req2->execute(['score_equipe' => $scoreE,
                            'score_equipe_adverse' => $scoreEA,
                            'id_entraineur' => $this->id_entraineur,
                            'id_match' => $id_num]);

            header("Location: controleur.php?page=gererMatchs");
            exit;
        
        } else {
            $req3 = $this->pdo->prepare("UPDATE `match` SET score_equipe = :score_equipe, score_equipe_adverse = :score_equipe_adverse, statut_match = :statut_match
                                        WHERE id_entraineur = :id_entraineur AND id_match = :id_match");
            $req3->execute(['score_equipe' => $scoreE,
                            'score_equipe_adverse' => $scoreEA,
                            'id_entraineur' => $this->id_entraineur,
                            'statut_match' => "Match terminé",
                            'id_match' => $id_num]);

            $req4 = $this->pdo->prepare("DELETE FROM participation WHERE id_match = :id_match");
            $req4->execute(['id_match' => $id_num]);
            
            header("Location: controleur.php?page=gererMatchs");
            exit;
        }
    }

    public function afficherMatch(){
        $this->idEntraineur();

        $req = $this->pdo->prepare("SELECT id_match, equipe_adverse, date_match, type_match, score_equipe, score_equipe_adverse, statut_match FROM `match` WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $this->data_matchs = $req->fetchAll();
 
        $i = 0;
        $num = array();
        $EA= array();
        $date = array();
        $type = array();
        $scoreE = array();
        $scoreEA = array();
        $statut = array();

        if (empty($this->data_matchs)){

        } else {
            foreach($this->data_matchs as $ligne){      
                $num[$i++] = $ligne['id_match'];
                $EA[$i++] = $ligne['equipe_adverse'];
                $date[$i++] = $ligne['date_match'];
                $type[$i++] = $ligne['type_match'];
                $scoreE[$i++] = $ligne['score_equipe'];
                $scoreEA[$i++] = $ligne['score_equipe_adverse'];
                $statut[$i++] = $ligne['statut_match'];
            }
        }
        $this->tbs->LoadTemplate("gererMatchs.html");
        $this->tbs->MergeBlock("id_match", $num);
        $this->tbs->MergeBlock("equipe_adverse", $EA);
        $this->tbs->MergeBlock("date_match", $date);
        $this->tbs->MergeBlock("type_match", $type);
        $this->tbs->MergeBlock("score_equipe", $scoreE);
        $this->tbs->MergeBlock("score_equipe_adverse", $scoreEA);
        $this->tbs->MergeBlock("statut_match", $statut);
        $this->tbs->Show();
    }
}

class Participation extends Matchs {
    private $data_participation;

    public function ajouterParticipation($p_match, $p_joueur, $p_statut){
        $this->idEntraineur();
        $id_match = intval($p_match);
        $id_joueur = intval($p_joueur);

    
        // Vérification si l'id_joueur existe dans la table joueur
        $reqVerifJoueur = $this->pdo->prepare("SELECT COUNT(*) FROM joueur WHERE id_joueur = :id_joueur");
        $reqVerifJoueur->execute(['id_joueur' => $id_joueur]);
        $joueurExists = $reqVerifJoueur->fetchColumn();

        // Vérification si l'id_match existe dans la table match
        $reqVerifMatch = $this->pdo->prepare("SELECT COUNT(*) FROM `match` WHERE id_match = :id_match");
        $reqVerifMatch->execute(['id_match' => $id_match]);
        $matchExists = $reqVerifMatch->fetchColumn();
        
        $reqVerifParticipation = $this->pdo->prepare("SELECT COUNT(*) FROM participation WHERE id_joueur = :id_joueur AND id_match = :id_match");
        $reqVerifParticipation->execute(['id_joueur' => $id_joueur, 'id_match' => $id_match]);
        $participationExists = $reqVerifParticipation->fetchColumn();

        if ($joueurExists && $matchExists && !$participationExists){ 
            $req = $this->pdo->prepare("INSERT INTO participation (id_match,
                             id_joueur, id_entraineur, statut) VALUES (:id_match, :id_joueur, :id_entraineur,
                             :statut)");

            $req->execute([
                'id_match' => $id_match,
                'id_joueur' => $id_joueur,
                'id_entraineur' => $this->id_entraineur,
                'statut' => $p_statut
                ]);

            $req3 = $this->pdo->prepare("SELECT COUNT(*) FROM participation WHERE id_entraineur = :id_entraineur");
            $req3->execute(['id_entraineur' => $this->id_entraineur]);
            $nombreJoueur = $req3->fetchColumn();
    
            if ($nombreJoueur >= 15){
                $req5 = $this->pdo->prepare("UPDATE `match` SET statut_match = :statut_match WHERE id_entraineur = :id_entraineur");
                $req5->execute(['statut_match' => "Match à venir", 'id_entraineur' => $this->id_entraineur]);
            } else {
                $req5 = $this->pdo->prepare("UPDATE `match` SET statut_match = :statut_match WHERE id_entraineur = :id_entraineur");
                $req5->execute(['statut_match' => "Convoquer joueur (pas assez)", 'id_entraineur' => $this->id_entraineur]);
            }
    
        } else {
            echo "Verifiez l'id du match ou du joueur ! ";
        }
        header("Location: controleur.php?page=participation");
        exit;  
    }

    public function afficherParticipation(){

        // Affichage participation 

        $this->idEntraineur();

        $req = $this->pdo->prepare("SELECT id_match, id_joueur, statut FROM participation WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $this->data_participation = $req->fetchAll();

      
        $i = 0;
        $id_match= array();
        $id_joueur= array();
        $statut = array();

        if (empty($this->data_participation)){

        } else {
            foreach($this->data_participation as $ligne){      
                $id_match[$i++] = $ligne['id_match'];
                $id_joueur[$i++] = $ligne['id_joueur'];
                $statut[$i++] = $ligne['statut'];
            }
        }

        // Affichage matchs 

        $req2 = $this->pdo->prepare("SELECT id_match, equipe_adverse, date_match, type_match, score_equipe, score_equipe_adverse, statut_match FROM `match` WHERE id_entraineur = :id_entraineur");
        $req2->execute(['id_entraineur' => $this->id_entraineur]);
        $data_matchs = $req2->fetchAll();
 
        $j = 0;
        $num = array();
        $EA= array();
        $date = array();
        $type = array();
        $scoreE = array();
        $scoreEA = array();
        $statutM = array();

        if (empty($data_matchs)){

        } else {
            foreach($data_matchs as $ligne){      
                $num[$j++] = $ligne['id_match'];
                $EA[$j++] = $ligne['equipe_adverse'];
                $date[$j++] = $ligne['date_match'];
                $type[$j++] = $ligne['type_match'];
                $scoreE[$j++] = $ligne['score_equipe'];
                $scoreEA[$j++] = $ligne['score_equipe_adverse'];
                $statutM[$j++] = $ligne['statut_match'];
            }
        }

        // Affichage joueurs 

        $this->idEquipe();

        $req3 = $this->pdo->prepare("SELECT id_joueur, num_joueur, nom_joueur, prenom_joueur, nationalité, poste FROM joueur WHERE id_equipe = :id_equipe");
        $req3->execute(['id_equipe' => $this->idEquipe]);
        $data_joueurs = $req3->fetchAll();

      
        $k = 0;
        $id = array();
        $numJ = array();
        $nom = array();
        $prenom = array();
        $nationalite = array();
        $poste = array();

        if (empty($data_joueurs)){

        } else {
            foreach($data_joueurs as $ligne){   
                $id[$k++] = $ligne['id_joueur'];   
                $numJ[$k++] = $ligne['num_joueur'];
                $nom[$k++] = $ligne['nom_joueur'];
                $prenom[$k++] = $ligne['prenom_joueur'];
                $nationalite[$k++] = $ligne['nationalité'];
                $poste[$k++] = $ligne['poste'];
            }
        }

        $this->tbs->LoadTemplate("participation.html");

        // Affichage matchs 
        $this->tbs->MergeBlock("id_match", $num);
        $this->tbs->MergeBlock("equipe_adverse", $EA);
        $this->tbs->MergeBlock("date_match", $date);
        $this->tbs->MergeBlock("type_match", $type);
        $this->tbs->MergeBlock("score_equipe", $scoreE);
        $this->tbs->MergeBlock("score_equipe_adverse", $scoreEA);
        $this->tbs->MergeBlock("statut_match", $statutM);
     
        // Affichage joueurs 

        $this->tbs->MergeBlock("id_joueur", $id);
        $this->tbs->MergeBlock("num_joueur", $numJ);
        $this->tbs->MergeBlock("nom_joueur", $nom);
        $this->tbs->MergeBlock("prenom_joueur", $prenom);
        $this->tbs->MergeBlock("nationalité", $nationalite);
        $this->tbs->MergeBlock("poste", $poste);

        // Affichage joueurs convoqués 
        $this->tbs->MergeBlock("id_match2", $id_match);
        $this->tbs->MergeBlock("id_joueur2", $id_joueur);
        $this->tbs->MergeBlock("statut", $statut);

        $this->tbs->Show();
    }
}


class Profil extends Equipe {

    private $id;
    private $nom, $prenom;
    private $dateN;
    private $rue, $ville;
    private $cp;


    public function preparerProfil(){
        $this->idEntraineur();

        $req = $this->pdo->prepare("SELECT id_entraineur, nom_e, prenom_e, datenaiss, rue, cp, ville FROM entraineur WHERE id_entraineur = :id_entraineur");
        $req->execute(['id_entraineur' => $this->id_entraineur]);
        $data_profil = $req->fetchAll();

        $this->id = array();
        $this->nom = array();
        $this->prenom = array();
        $this->dateN = array();
        $this->rue= array();
        $this->cp = array();
        $this->ville = array();
  
        foreach($data_profil as $ligne){      
            $this->id[] = $ligne['id_entraineur'];
            $this->nom[] = $ligne['nom_e'];
            $this->prenom[]= $ligne['prenom_e'];
            $this->dateN[] = $ligne['datenaiss'];
            $this->rue[] = $ligne['rue'];
            $this->cp[] = $ligne['cp'];
            $this->ville[] = $ligne['ville'];
        }
      
    }

    public function getId(){
        $this->idEntraineur();
        return $this->id_entraineur;
    }

    public function getNom(){
        return $this->nom[0];
    }

    public function getPrenom(){
        return $this->prenom[0];
    }

    public function getDateN(){
        return $this->dateN[0];
    }

    public function getRue(){
        return $this->rue[0];
    }

    public function getCP(){
        return $this->cp[0];
    }

    public function getVille(){
        return $this->ville[0];
    }

    public function modifierProfil($p_nom, $p_prenom, $p_date, $p_rue, $p_cp, $p_ville){
        $this->idEntraineur();
        $req = $this->pdo->prepare("UPDATE entraineur SET nom_e = :nom_e,
        prenom_e = :prenom_e, datenaiss = :datenaiss, rue = :rue, cp = :cp, ville = :ville
        WHERE id_entraineur = :id_entraineur");
        $req->execute([
            'nom_e' => $p_nom,
            'prenom_e' => $p_prenom,
            'datenaiss' => $p_date,
            'rue' => $p_rue,
            'cp' => $p_cp,
            'ville' => $p_ville,
            'id_entraineur' => $this->id_entraineur
        ]);

        header("Location: controleur.php?page=profil");
    }
}




class StatsEquipe extends Matchs {
    // Pas le temps
}

class StatsJoueurs extends Participation {
    // Pas le temps
}


?>