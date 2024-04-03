<?php

    session_start();
    

    require("modele.class.php");
    require("modele.fonction.php");
    require("tbs_class.php");
    require("connect.inc.php");

    $tbs = new clsTinyButStrong;
    $connexion = '';
    $menu_profil_ou_login = '';
    $nom_menu_profil_ou_login = '';
 



    if (isset($_SESSION['login'])){
        $menu_profil_ou_login = "controleur.php?page=profil";
        $nom_menu_profil_ou_login = "Mon profil";

        $eq = new Equipe($_SESSION['login'], $c, $tbs); //Objet pour la classe Equipe

        echo  "Connecté en tant que ". $_SESSION['login'];
    } else {
        $menu_profil_ou_login = "controleur.php?page=login";
        $nom_menu_profil_ou_login = "Se connecter / S'inscrire";
        echo "Non connecté";
    }

    if(isset($_GET['page'])){
        $page = $_GET['page'];
        switch ($page){
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
            case 'gererEquipe':
                
                if(isset($_SESSION['login'])){          // Vérifier si session                     
                    $eq->verif();  // Appel fonction verif pour savoir existance d'une équipe 
                    $message = $eq->getMessage();  
                    $id_entraineur = $eq->getIdEntraineur();
                    
                    $tbs->LoadTemplate('gererEquipe.html');

                } else $tbs->LoadTemplate('login.html');

                break;
            case 'creerEquipe':
            
                if (isset($_POST["nom"]) && isset($_POST["pays"]) && isset($_POST["ligue"])){
                    $eq->creer($_POST["nom"], $_POST['pays'], $_POST['ligue']);  
                }
            
                // Appel fonction pour créer équipe
                $tbs->LoadTemplate("creerEquipe.html");
                break;
            
            case 'afficherEquipe': // Page qui affiche l'équipe de l'user
                $eq->preparer();  
                $nomEquipe = $eq->getNomEquipe();   // Accès aux infos de l'équipe 
                $paysEquipe = $eq->getPaysEquipe();
                $ligue = $eq->getLigue();

                $tbs->LoadTemplate('afficherEquipe.html');
                break;
                    
    
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
        
                $tbs->LoadTemplate('index.html');
                $page = $_SERVER['PHP_SELF'];
                $sec = "0.001";
                header("Refresh: $sec; url=$page");
            
            case 'supprimerEquipe': // A corriger
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
               
            default:
                $tbs->LoadTemplate('index.html');   
        } 
    } else {
        $tbs->LoadTemplate('index.html');
    }
    $tbs->Show();
?>