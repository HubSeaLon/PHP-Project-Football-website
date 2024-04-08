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

        $eq = new Equipe($_SESSION['login'], $c, $tbs); //Création Objet $eq (équipe) pour la classe Equipe

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

                if (!empty($nomEquipe) && !empty($paysEquipe) && !empty($ligue)){
                    $gererEquipe = 'controleur.php?page=gererEquipe';
                    $gererJoueur = 'controleur.php?page=gererJoueur';
                    $gererStaff = 'controleur.php?page=gererStaff';
                    $gererBlessure = 'controleur.php?page=gererBlessure';
                    $stats = 'controleur.php?page=stats';                   
                    $gererMatchs = 'controleur.php?page=gererMatchs';
                    $tbs->LoadTemplate('afficherEquipe.html');
                } else {
                    $message = 'Veuilez créer une équipe pour utiliser cette fonctionnalité.';
                    $tbs->LoadTemplate('message.html');
                }
                
                break;
            case 'supprimerEquipe': 
                $eq->idEntraineur();
                $id_entraineur = $eq->getIdEntraineur();
                    
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
                $tbs->LoadTemplate('gererJoueur.html');
                break;
            case 'gererStaff':
                $tbs->LoadTemplate('gererStaff.html');
                break;
            case 'gererBlessure':
                $tbs->LoadTemplate('gererBlessure.html');
                break;
            case 'stats':
                $tbs->LoadTemplate('stats.html');
                break;
            case 'gererMatchs':
                $tbs->LoadTemplate('gererMatchs.html');
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