<?php
    function bdLink(){
        try {
            $server = "127.0.0.1";
            $db = "projetapi";
            $login = "root";        
                    
            $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        return $linkpdo;
    }

    function checkUser($login, $password){
        $linkpdo = bdLink();

        $req = $linkpdo -> prepare("SELECT password from Utilisateur where login=:login");
        $res  = $req -> execute(array('login' => $login));

        if($res == false){
            $req -> debugDumpParams();
                die('Erreur execute');
        } else {
            if($pwd = $req->fetch()) {
                if ($pwd["password"]==sha1($password)){
                    return true;
                }
            }
        }
        return false;
    }

    function getRole($login){
        $linkpdo = bdLink();

        $req = $linkpdo -> prepare("SELECT role from Utilisateur where login=:login");
        $res  = $req -> execute(array('login' => $login));

        if($res == false){
            $req -> debugDumpParams();
                die('Erreur execute');
        } else {
            $pwd = $req->fetch();
            return $pwd["role"];
        }
    }

    // SANS AUTHENTIFICATION
    // fonction GET
    function getMessage($id) {
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // cas de l'id est null
        if ($id == null) {
            // ecriture de la requete pour la consultation de donnees
            $req = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text FROM utilisateur u, article a
                                        WHERE u.id_utilisateur = a.id_utilisateur");
            // execution de la requete                          
            $res = $req -> execute();
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            }
        } else {
            // ecriture de la requete pour la consultation de donnees
            $req = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text FROM utilisateur u, article a
                                        WHERE u.id_utilisateur = a.id_utilisateur AND a.id_utilisateur=$id");
            // execution de la requete                          
            $res = $req -> execute();
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            }
        }
        

    }

    // ROLE PUBLISHER

    //fonction ADD
    function addArticle($tab){
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // ecriture de la requete d'ajout
        $req = $linkpdo -> prepare("INSERT INTO article (date_pub,text,id_utilisateur) VALUES (CURRENT_TIMESTAMP,:text,:id_utilisateur)");
        // execution de la requete
        $res  = $req -> execute(array('date_pub' => $tab['date_pub'],
                                    'text' => $tab['text'],
                                    'id_utilisateur' => $tab['id_utilisateur']));
        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
        }
    }

    //fonction GET
    function getMessagePub($id) {
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // ecriture de la requete pour la consultation de donnees
        $req = $linkpdo -> prepare("SELECT  a.date_pub, a.text FROM article a
                                    WHERE a.id_utilisateur = $id");
        // execution de la requete                          
        $res = $req -> execute();
        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
        }
    }

    function delData($id){
        $linkpdo = bdLink();
        $req = $linkpdo -> prepare('DELETE FROM chuckn_facts WHERE :id = id');
        $res = $req -> execute(array("id" => $id));

        if($res == false){
            $req -> debugDumpParams();
                die('Erreur execute');
        }
    }

    function modifData($tab){
        $linkpdo = bdLink();
        $req = $linkpdo -> prepare("UPDATE chuckn_facts SET phrase= :phrase WHERE id = :id");
        $res = $req -> execute(array('phrase' => $tab['phrase'], 'id' => $tab['id']));

        if($res == false){
            $req -> debugDumpParams();
                die('Erreur execute');
        }
    }
?>