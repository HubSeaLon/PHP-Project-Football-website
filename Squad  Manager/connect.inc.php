<?php
    $host = "localhost";
    $login = "root";
    $password = "";
    $dbname = "squad manager";

    try {
        $c = new PDO("mysql:host=$host; dbname=$dbname", $login, $password);
        $etatConnexion = "Base de données Ok";
    } catch(PDOException $erreur) {
        $etatConnexion = $erreur->getMessage();
    }

?>