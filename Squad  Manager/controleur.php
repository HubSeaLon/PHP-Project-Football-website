<?php

    session_start();
    

    require("modele.class.php");
    require("modele.fonction.php");
    require("tbs_class.php");
    require("connect.inc.php");

    $tbs = new clsTinyButStrong;

   
  

    // Gestion Session
    if (isset($_SESSION['login'])){
        $menu_profil_ou_login = "controleur.php?page=profil";
        $nom_menu_profil_ou_login = "Mon profil";

        $eq = new Equipe($_SESSION['login'], $c, $tbs); //Création des objets
        $eq2 = new Joueurs($_SESSION['login'], $c, $tbs);
        $eq3 = new Staff($_SESSION['login'], $c, $tbs);
        $eq4 = new Blessure($_SESSION['login'], $c, $tbs);
        $eq5 = new Matchs($_SESSION['login'], $c, $tbs);
        $eq6 = new Participation($_SESSION['login'], $c, $tbs);

        echo  "Connecté en tant que ". $_SESSION['login'];
    } else {
        $menu_profil_ou_login = "controleur.php?page=login";
        $nom_menu_profil_ou_login = "Se connecter / S'inscrire";
        echo "Non connecté";
    }
    // Fin gestion session


    // Navigation des pages selon l'url  
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        switch ($page){

            // Cas des pages sans traitements serveur
            case 'index':
                $tbs->LoadTemplate('index.html');
                break;
            case 'services':
                $tbs->LoadTemplate('Nos services.html');
                break;
            case 'qui-sommes-nous':
                $tbs->LoadTemplate('Qui sommes-nous.html');
                break;
            case 'equipe':
                $tbs->LoadTemplate('Notre equipe.html');
                break;
            // Fin cas des pages sans traitements serveur 

            // Cas Gestion Equipe :
            case 'gererEquipe': 
                if(isset($_SESSION['login'])){          // Vérifier si session                     
                    $eq->verif();  // Appel fonction verif pour savoir existance d'une équipe 

                    // Cas si pas d'équipe 
                    $messageEquipe = $eq->getMessage();  
                    $id_entraineur = $eq->getIdEntraineur();
                    
                    $tbs->LoadTemplate('gererEquipe.html');

                } else $tbs->LoadTemplate('login.html');
                break;

            case 'creerEquipe':         
                if (isset($_POST["nom"]) && isset($_POST["pays"]) && isset($_POST["ligue"])){
                    $eq->creer($_POST["nom"], $_POST['pays'], $_POST['ligue']);  // Appel fonction creer() de la classe Equipe pour créer une équipe
                }
            
                $tbs->LoadTemplate("creerEquipe.html");
                break;
            
            case 'afficherEquipe': // Page qui affiche l'équipe de l'user
                $eq->preparer();
                $nomEquipe = $eq->getNomEquipe();   // Accès aux infos de l'équipe 
                $paysEquipe = $eq->getPaysEquipe();
                $ligue = $eq->getLigue();

                $tbs->LoadTemplate('afficherEquipe.html');

                break;

            case 'supprimerEquipe': 
                $eq->idEntraineur();
                $eq2->idEquipe();
                $id_equipe = $eq2->getIdEquipe();
                $id_entraineur = $eq->getIdEntraineur();

                $req = $c->prepare("DELETE FROM participation WHERE id_entraineur = :id_entraineur");
                $req->execute(['id_entraineur' => $id_entraineur]);

                $req2 = $c->prepare("DELETE FROM staff WHERE id_equipe = :id_equipe");
                $req2->execute(['id_equipe' => $id_equipe]);

                $req3 = $c->prepare("DELETE FROM `match` WHERE id_entraineur = :id_entraineur");
                $req3->execute(['id_entraineur' => $id_entraineur]);

                $req4 = $c->prepare("DELETE FROM joueur WHERE id_equipe = :id_equipe");
                $req4->execute(['id_equipe' => $id_equipe]);
                    
                $reqSupp = $c->prepare("DELETE FROM equipe WHERE id_entraineur = :id_entraineur");
                $reqSupp->execute(['id_entraineur' => $id_entraineur]);
                    
                header("Location: controleur.php?page=gererEquipe");
                exit;
    
            case 'modifierEquipe':
                $eq->idEntraineur();
                  
                $id_entraineur = $eq->getIdEntraineur();
                $eq = $c->prepare("UPDATE equipe SET nom_equipe = :nom_equipe WHERE id_entraineur = :id_entraineur");
                $nomEquipe = $_POST['nomEquipe']; 
                $eq->execute([
                    'nom_equipe' => $nomEquipe,
                    'id_entraineur' => $id_entraineur
                ]);
    
                header("Location: controleur.php?page=afficherEquipe");
                exit;
            
            case 'gererJoueur':
                $eq->idEquipe();
                $idEquipe = $eq->getIdEquipe();

                // Voir si il y a des éléments
                $eq = $c->prepare("SELECT num_joueur, nom_joueur, prenom_joueur, nationalité, poste FROM joueur WHERE id_equipe = :id_equipe");
                $eq->execute(['id_equipe' => $idEquipe]);
                $data_joueurs = $eq->fetchAll();
            
                if (empty($data_joueurs)){
                    $message_joueur = "Vous n'avez pas de joueur";
                } else $message_joueur = "";
                $eq2->afficherJoueur();
                break;

            case 'ajouterJoueur':
                if (isset($_POST['numJoueur']) && isset($_POST['nomJoueur']) && isset($_POST['prenomJoueur']) && isset($_POST['nationaliteJoueur']) && isset($_POST['posteJoueur'])){
                    $eq2->ajouterJoueur($_POST['numJoueur'],$_POST['nomJoueur'], $_POST['prenomJoueur'],  $_POST['nationaliteJoueur'],$_POST['posteJoueur'] );              
                }
           
                break;
            
            case 'supprimerJoueur':          
                $eq2->supprimerJoueur();
                break;


            // Gestion staff 
            case 'gererStaff':
                $eq->idEquipe();
                $idEquipe = $eq->getIdEquipe();

                $eq = $c->prepare("SELECT * FROM staff WHERE id_equipe = :id_equipe");
                $eq->execute(['id_equipe' => $idEquipe]);
                $data_staff = $eq->fetchAll();

                if (empty($data_staff)){
                    $message_staff = "Vous n'avez pas de staff";
                } else $message_staff = "";
            
                $eq3->afficherStaff();
                break;
            
            case 'ajouterStaff':
                if (empty($_POST['numStaff'])){
                    $eq3->ajouterStaff($_POST['nomStaff'], $_POST['prenomStaff'], $_POST['roleStaff']);
                } else $eq3->modifierStaff($_POST['numStaff'], $_POST['nomStaff'], $_POST['prenomStaff'], $_POST['roleStaff']);
                break;
            
            case 'supprimerStaff':
                $eq3->supprimerStaff($_POST['numStaff']);
                break;

            // Fin gestion staff

            // Gestion blessure

            case 'gererBlessure':
                $eq2->idJoueur();
                $id_joueur = $eq2->getIdJoueur();

                $eq = $c->prepare("SELECT * FROM blessure WHERE id_joueur = :id_joueur");
                $eq->execute(['id_joueur' => $id_joueur]);
                $data_blessures = $eq->fetchAll();

                if (empty($data_blessures)){
                    $message_blessures = "Vous n'avez pas de blessure";
                } else $message_blessures = "";
            
                $eq4->afficherBlessure();
                break;
            
            case 'ajouterBlessure':
                if (empty($_POST['numBlessure'])){
                    $eq4->ajouterBlessure($_POST['dateBlessure'], $_POST['typeBlessure'], $_POST['dureeBlessure'], $_POST['numJoueur']);
                } else $eq4->modifierBlessure($_POST['numBlessure'], $_POST['dateBlessure'], $_POST['typeBlessure'], $_POST['dureeBlessure']);
                break;

            case 'supprimerBlessure':
                $eq4->supprimerBlessure($_POST['numBlessure']);
                break;

            // Fin gestion Blessure

            case 'stats':
                $tbs->LoadTemplate('stats.html');
                break;



            // Gestion Matchs
            case 'gererMatchs':
                $eq2->idEntraineur();
                $id_entraineur = $eq2->getIdEntraineur();

                $eq = $c->prepare("SELECT id_match, equipe_adverse, date_match, type_match, score_equipe, score_equipe_adverse FROM `match` WHERE id_entraineur = :id_entraineur");
                $eq->execute(['id_entraineur' => $id_entraineur]);
                $data_matchs = $eq->fetchAll();

                if (empty($data_matchs)){
                    $message_match = "Vous n'avez pas de match";
                } else $message_match = "";
            
                $eq5->afficherMatch();
                break;

            case 'ajouterMatchs':
                if (empty($_POST['numMatch'])){
                    $eq5->ajouterMatch($_POST['equipeAdverse'], $_POST['dateMatch'], $_POST['typeMatch']);
                } else $eq5->modifierMatch($_POST['numMatch'], $_POST['equipeAdverse'], $_POST['dateMatch'], $_POST['typeMatch']);
                
                break;

            case 'ajouterScore':
                $eq5->ajouterScore($_POST['numMatch'], $_POST['scoreEquipe'], $_POST['scoreEquipeAdverse']);
                break;
            // Fin gestion Matchs

            // Gestion participation :
            case 'participation':
                // Match 
                $eq2->idEntraineur();
                $id_entraineur = $eq2->getIdEntraineur();

                $eq = $c->prepare("SELECT id_match, equipe_adverse, date_match, type_match, score_equipe, score_equipe_adverse FROM `match` WHERE id_entraineur = :id_entraineur");
                $eq->execute(['id_entraineur' => $id_entraineur]);
                $data_matchs = $eq->fetchAll();

                if (empty($data_matchs)){
                    $message_match = "Vous n'avez pas de match";
                } else $message_match = "";

                // Joueurs 
                $eq3->idEquipe();
                $idEquipe = $eq3->getIdEquipe();

                $eq = $c->prepare("SELECT num_joueur, nom_joueur, prenom_joueur, nationalité, poste FROM joueur WHERE id_equipe = :id_equipe");
                $eq->execute(['id_equipe' => $idEquipe]);
                $data_joueurs = $eq->fetchAll();
            
                if (empty($data_joueurs)){
                    $message_joueur = "Vous n'avez pas de joueur";
                } else $message_joueur = "";


                // Participation
                $eq = $c->prepare("SELECT * FROM participation WHERE id_entraineur = :id_entraineur");
                $eq->execute(['id_entraineur' => $id_entraineur]);
                $data_participation = $eq->fetchAll();

                if (empty($data_participation)){
                    $message_participation = "Vous n'avez pas de joueur convoqué";
                } else $message_participation = "";

                $eq6->afficherParticipation();
                break;

            case 'convoquerJoueur':
                if (isset($_POST['numMatch'])){
                    $eq6->ajouterParticipation($_POST['numMatch'], $_POST['numJoueur'], $_POST['poste']);
                } 
                
                break;














            // Fin Gestion Equipe 
                    
            // Gestion Login / Inscription 
            case 'login':
                if (isset($_POST["llogin"])){
                    connexionID($_POST["llogin"], $_POST['lpasswd'], $c);
                }
    
                $tbs->LoadTemplate('login.html');
                break;
    
            case 'inscription':
                if (isset($_POST["ilogin"])) {                 
                    nouveauID($_POST["ilogin"], $_POST["ipasswd"], $_POST["verif"], $c);
                }   
    
                $tbs->LoadTemplate('inscription.html');
                break;
            
            case 'profil':
                $tbs->LoadTemplate('profil.html');
                break;

            case 'deconnexion':
                session_destroy();
                header("Location: controleur.php?page=index");
                exit;
            
            default:
                $message = 'Page introuvable';
                $tbs->LoadTemplate('message.html');   
                break;
        } 
    } else {
        $tbs->LoadTemplate('index.html');
    }
    $tbs->Show();
?>