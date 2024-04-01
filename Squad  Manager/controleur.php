<?php

    session_start();
    

    require("class.php");
    require("tbs_class.php");
    require("connect.inc.php");

    $tbs = new clsTinyButStrong;
    $connexion = '';
    $menu_profil_ou_login = '';
    $nom_menu_profil_ou_login = '';


    if (isset($_SESSION['login'])){
        $menu_profil_ou_login = "controleur.php?page=profil";
        $nom_menu_profil_ou_login = "Mon profil";
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
                $tbs->LoadTemplate('gererEquipe.html');
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

            default:
                $tbs->LoadTemplate('index.html');   
        } 
    } else {
        $tbs->LoadTemplate('index.html');
    }


    // if(isset($_SESSION['login'])){

    //     $menu_profil_ou_login = "controleur.php?page=profil";
    //     $nom_menu_profil_ou_login = "Mon profil";
    //     echo  "Connecté en tant que ". $_SESSION['login'];

    //     if (isset($_GET['page'])){
    //         $page = $_GET['page'];
    //         switch ($page){
    //             case 'cindex':
    //                 $tbs->LoadTemplate('cindex.html');
    //                 break;
    //             case 'cservices':
    //                 $tbs->LoadTemplate('cNos services.html');
    //                 break;
    //             case 'cqui-sommes-nous':
    //                 $tbs->LoadTemplate('cQui sommes-nous.html');
    //                 break;
    //             case 'cequipe':
    //                 $tbs->LoadTemplate('cNotre equipe.html');
    //                 break;
    //             case 'gererEquipe':
    //                 $tbs->LoadTemplate('gererEquipe.html');
    //                 break;   
    //             case 'profil':
    //                 $tbs->LoadTemplate('profil.html');
    //                 break;
    //             default:
    //                 $tbs->LoadTemplate('cindex.html');   
    //         } 
    //     } else {
    //         $tbs->LoadTemplate('cindex.html');
    //     }

    // } else {

    //     $menu_profil_ou_login = "controleur.php?page=login";
    //     $nom_menu_profil_ou_login = "Se connecter / S'inscrire";
    //     echo "Non connecté";

    //     if (isset($_GET['page'])){
    //         $page = $_GET['page'];
    //         switch ($page){
    //             case 'index':
    //                 $tbs->LoadTemplate('index.html');
    //                 break;
    //             case 'services':
    //                 $tbs->LoadTemplate('Nos services.html');
    //                 break;
    //             case 'qui-sommes-nous':
    //                 $tbs->LoadTemplate('Qui sommes-nous.html');
    //                 break;
    //             case 'equipe':
    //                 $tbs->LoadTemplate('Notre equipe.html');
    //                 break;
    //             case 'gererEquipe':
    //                 $tbs->LoadTemplate('gererEquipe.html');
    //                 break;
    
    //             case 'login':
    //                 if (isset($_POST["llogin"])){
    //                     connexionID($_POST["llogin"], $_POST['lpasswd'], $c);
    //                 }
    
    //                 $tbs->LoadTemplate('login.html');
    //                 break;
    
    //             case 'inscription':
    //                 if (isset($_POST["ilogin"])) {                 
    //                    nouveauID($_POST["ilogin"], $_POST["ipasswd"], $_POST["verif"], $c);
    //                 }   
    
    //                 $tbs->LoadTemplate('inscription.html');
    //                 break;
    //             default:
    //                 $tbs->LoadTemplate('index.html');   
    //         } 
    //     } else {
    //         $tbs->LoadTemplate('index.html');
    //     }
    // }

    $tbs->Show();

?>