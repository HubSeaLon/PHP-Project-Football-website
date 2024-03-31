<?php

    // Ne fonctionne pas l'entrée des utilisateurs par la classe

    // class newID {
    //     private $login;
    //     private $password;
    //     private $verif;

    //     function __construct($p_login, $p_password, $p_verif){
    //         $this->login = $p_login;
    //         $this->password = $p_password;
    //         $this->verif = $p_verif;
    //     }

    //     public function nouveauId(){
    //         require("connect.inc.php");
    //         if(isset($_POST['formsend'])){
    //             if(!empty($this->login) && !empty($this->password) && !empty($this->verif)) {
    //                 if($this->password == $this->verif){
    //                     $options = [
    //                         'cost' => 12, 
    //                     ];

    //                     $hashpass = password_hash($this->password, PASSWORD_BCRYPT, $options);
                        
    //                     try {
    //                         $q = $c->prepare("INSERT INTO entraineur (mail, mdp) VALUES (:mail, :mdp)");
    //                         $q->execute([
    //                             'mail' => $this->login,
    //                             'mdp' => $hashpass
    //                         ]);
        
    //                         echo 'Nouvel utilisateur enregistré';
    //                     } catch (PDOException $e) {
    //                         echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
    //                     }
                        
    //                 } else echo "Les mots de passe ne correspondent pas";
    //             }
    //         }
    //     }
    // }

    function nouveauID ($p_login, $p_password, $p_verif){

        require("connect.inc.php");
                if(!empty($p_login) && !empty($p_password) && !empty($p_verif)) {
                    if($p_password == $p_verif){
                        $options = [
                            'cost' => 12, 
                        ];

                        $hashpass = password_hash($p_password, PASSWORD_BCRYPT, $options);
                        
                        try {
                            $s = $c->prepare("SELECT mail FROM entraineur WHERE mail = :mail");
                            $s->execute(['mail' => $p_login]);
                            
                            $resultat = $s->rowCount();

                            if($resultat == 0){
                                $q = $c->prepare("INSERT INTO entraineur (mail, mdp) VALUES (:mail, :mdp)");
                                $q->execute([
                                    'mail' => $p_login,
                                    'mdp' => $hashpass
                                ]);
                                echo 'Nouvel utilisateur enregistré';
                                header("Location: controleur.php?page=login");
                                exit;
                            } else echo 'Un email existe déjà !';
                            
                        } catch (PDOException $e) {
                            echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
                        }
                        
                    } else echo "Les mots de passe ne correspondent pas";
                }
            }

?>