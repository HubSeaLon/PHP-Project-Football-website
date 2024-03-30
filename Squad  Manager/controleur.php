<?php

    session_start();

    require("class.php");
    require("tbs_class.php");
    require("connect.inc.php");

    $tbs = new clsTinyButStrong;
    $cible = $_SERVER["PHP_SELF"];

    try {
        $c = new PDO("mysql:host=$host; dbname=$dbname", $login, $password);
    } catch(PDOException $erreur) {
        $etatConnexion = $erreur->getMessage();
    }


    if (isset($_GET['page'])){
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
                $tbs->LoadTemplate('login.html');
                break;
            case 'inscription':
                $tbs->LoadTemplate('inscription.html');
                break;
            default:
                $tbs->LoadTemplate('404.html');   
        } 
    } else {
        $tbs->LoadTemplate('index.html');
    }

    $tbs->Show();

?>