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
        // cas de l'id est égal à 0
        if ($id == 0) {
            // ecriture de la requete pour la consultation de donnees
            $req = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text FROM utilisateur u, article a
                                        WHERE u.id_utilisateur = a.id_utilisateur");
            // execution de la requete                          
            $res = $req -> execute();
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } else {
                return $req -> fetchAll(PDO::FETCH_ASSOC);
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
            } else {
                return $req -> fetchAll(PDO::FETCH_ASSOC);
            }
        }
        

    }

    // ROLE MODERATOR
    //fonction GET
    function getMessageMod($id) {
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // cas de l'id est égal à 0
        if ($id == 0) {
            // ecriture des requetes pour la consultation de donnees

            /// requete retournant le nombre de like par article
            $reqnblike = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text, COUNT(l.id_utilisateur) as nb_like FROM utilisateur u, article a, like_dislike l
            WHERE u.id_utilisateur = a.id_utilisateur AND a.id_article = l.id_article AND l.statute = 0 GROUP BY a.id_article");
            // execution de la requete                          
            $resnblike = $reqnblike -> execute();
            if($resnblike == false){
                $reqnblike -> debugDumpParams();
                die('Erreur execute');
            } else {
                $nblike = $reqnblike -> fetchAll(PDO::FETCH_ASSOC);
            }

            /// requete retournant le nombre de dislike par article
            $reqnbdislike = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text,COUNT(l.id_utilisateur) as nb_dislike FROM utilisateur u, article a, like_dislike l
            WHERE u.id_utilisateur = a.id_utilisateur AND a.id_article = l.id_article AND l.statute = 1 GROUP BY a.id_article");
            // execution de la requete                          
            $resnbdislike = $reqnbdislike -> execute();
            if($resnbdislike == false){
                $reqnbdislike -> debugDumpParams();
                die('Erreur execute');
            } else {
                $nbdislike = $reqnbdislike -> fetchAll(PDO::FETCH_ASSOC);
            }  

            /// requete retournant la liste des utilisateurs ayant liker par article
            $reqllike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
            WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 0");
            // execution de la requete                          
            $resllike = $reqllike -> execute();
            if($resllike == false){
                $reqllike -> debugDumpParams();
                die('Erreur execute');
            } else {
                $llike = $reqllike -> fetchAll(PDO::FETCH_ASSOC);
            }  

            /// requete retournant la liste des utilisateurs ayant disliker par article
            $reqldislike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
            WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 1");
            // execution de la requete                          
            $resldislike = $reqldislike -> execute();
            if($resldislike == false){
                $reqldislike -> debugDumpParams();
                die('Erreur execute');
            } else {
                $ldislike = $reqldislike -> fetchAll(PDO::FETCH_ASSOC);
            }  

        }
        
    }

    //fonction DELETE
    function delarticle($id) {
        $retour = 0;
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // ecriture des requetes de suppression
        /// supression dans la table like_dislike
        $req = $linkpdo -> prepare("DELETE FROM like_dislike WHERE id_article = $id");
        ///execution de la requete
        $res = $req -> execute();

        ///supression dans la table article
        $req1 = $linkpdo -> prepare("DELETE FROM article WHERE id_article = $id");
        ///execution de la requete
        $res1 = $req1 -> execute();
        
        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
        }

        if($res1 == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
        }
        return retour;
    }

    /// Role PUBLISHER
    //fonction Ajout d'article
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
        if ($id == null) {
            // ecriture de la requete pour la consultation de donnees
            $req = $linkpdo -> prepare("SELECT a.date_pub, a.text FROM article a");
            // execution de la requete                          
            $res = $req -> execute();
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            }
        } else {
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
    }

    // fonction DELETE
    function delarticlepub($id){
        $linkpdo = bdLink();
        $req = $linkpdo -> prepare('DELETE FROM article WHERE :id = id');
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