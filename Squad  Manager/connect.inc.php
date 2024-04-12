<?php
    $host = "mysql02.univ-lyon2.fr";
    $login = "php_hgeoffray";
    $password = "deQVKrdHNU-iJ6DU8Dv2P472W";
    $dbname = "php_hgeoffray";

    try {
        $c = new PDO("mysql:host=$host; dbname=$dbname", $login, $password);
        $etatConnexion = "Base de données Ok";
    } catch(PDOException $erreur) {
        $etatConnexion = $erreur->getMessage();
    }

?>