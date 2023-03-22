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
            // création d'un tableau
            $tab=array();
            // ecriture des requetes pour la consultation de donnee d'un moderator
            // requete permettant de recuperer toutes les informations lié à un article
            $req= $linkpdo -> query("SELECT a.id_article,a.date_pub,a.text,u.login 
                                    FROM article a,utilisateur u WHERE u.id_utilisateur = a.id_utilisateur");

            // entre dans la boucle si erreur dans la requete                       
            if($req == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } 

            while($res = $req -> fetch()) {

                // preparation de la requete permettant de recuperer la liste des utilisateurs ayant like un article donnee
                $reqlike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
                WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 0 AND l.id_article = :id");
                // execution de la requete
                $res1 = $reqlike -> execute(array ("id" => $res['id_article']));

                if($res1 == false){
                    $reqlike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    // recupere toutes les donnees retourner par la requete dans un tableau
                    $listel = $reqlike -> fetchAll(PDO::FETCH_ASSOC);
                }

                // preparation de la requete permettant de recuperer la liste des utilisateurs ayant disliker un article donnee
                $reqdislike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
                WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 1 AND l.id_article =:id");
                //execution de la requete
                $res2 = $reqdislike -> execute(array("id" => $res['id_article']));
                if($res2 == false){
                    $reqdislike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    // recupere toutes les donnees retourner par la requete dans un tableau
                    $listed = $reqdislike -> fetchAll(PDO::FETCH_ASSOC);
                }
                // recuperation de toutes les donnees et insertion dans un tableau
                $tab[] = array(
                    "auteur" => $res['login'],
                    "date de publication" => $res['date_pub'],
                    "contenu" => $res['text'],
                    "utilisateur ayant like cette article" => $listel,
                    "nombre de like" => count($listel),
                    "utilisateur ayant dislike cette article" => $listed,
                    "nombre de dislike" => count($listed)
                );
            } 
            // retourne le tableau de donnees
            return $tab; 
        
        //cas de l'id est renseigner
        } else {

            // ecriture des requetes
            // preparation de la requete permettant la recuperation de toutes les donnees lie à l'article renseigner par l'utilisateur
            $req= $linkpdo -> query("SELECT a.id_article,a.date_pub,a.text,u.login 
            FROM article a,utilisateur u WHERE u.id_utilisateur = a.id_utilisateur AND a.id_article = $id");

            if($req == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } 

            while ($res = $req -> fetch()){
                //preparation de la requete de recuperation de tout les utilisateurs ayant liké l'article renseigner
                $reqlike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
                                                WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 0 AND l.id_article = $id");
                //execution de la requete
                $res1 = $reqlike -> execute();

                if($res1 == false){
                    $reqlike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    // recupere toutes les donnees retourner par la requete dans un tableau
                    $listel = $reqlike -> fetchAll(PDO::FETCH_ASSOC);
                }

                // preparation de la requete permettant de recuperer la liste des utilisateurs ayant disliker un article donnee
                $reqdislike = $linkpdo -> prepare("SELECT u.login FROM utilisateur u, like_dislike l 
                                                    WHERE u.id_utilisateur = l.Id_Utilisateur AND l.statute = 1 AND l.id_article =$id");
                //execution de la requete
                $res2 = $reqdislike -> execute();

                if($res2 == false){
                    $reqdislike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    // recupere toutes les donnees retourner par la requete dans un tableau
                    $listed = $reqdislike -> fetchAll(PDO::FETCH_ASSOC);
                }
                // recuperation de toutes les donnees et insertion dans un tableau
                $tab[] = array(
                    "auteur" => $res['login'],
                    "date de publication" => $res['date_pub'],
                    "contenu" => $res['text'],
                    "utilisateur ayant like cette article" => $listel,
                    "nombre de like" => count($listel),
                    "utilisateur ayant dislike cette article" => $listed,
                    "nombre de dislike" => count($listed)
                );
            }
            // retourne le tableau de donnees
            return $tab;
        }
        
    }

    // Publisher et moderator
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
    // test appartenance publisher
    function BelongToPublisher($id,$login) {
        /// SELECT * FROM article, utilisateur where article.Id_Utilisateur=utilisateur.id_utilisateur and utilisateur.login = "jean";
        $linkpdo = bdLink();

        /// ecriture de la requete e selection
        $req = $linkpdo -> prepare("SELECT * FROM article, utilisateur where article.Id_Utilisateur=utilisateur.id_utilisateur 
                                    and utilisateur.login =:login and article.id_article=:id");

        /// execution de la requete
        $res  = $req -> execute(array('login' => $login, 'id' => $id));

        /// traitement du resultat
        if ($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
        } else {
            $tab = $req -> fetchAll(PDO::FETCH_ASSOC);
            if (count($tab)==1) {
                return true;
            }
        }
        return false;
    }

    //fonction Ajout d'article
    function addArticle($tab){
        $retour = 0;
        // appelle de la methode pour se connecter a la base de donnees
        $linkpdo = bdLink();
        // ecriture de la requete d'ajout
        $req = $linkpdo -> prepare("INSERT INTO article (date_pub,text,id_utilisateur) VALUES (CURRENT_TIMESTAMP,:text,:id_utilisateur)");
        // execution de la requete
        $res  = $req -> execute(array('text' => $tab['text'],
                                     'id_utilisateur' => $tab['id_utilisateur']));
        if ($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
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

    // fonction PUT
    function modifData($tab){
        //appelle a la methode pour la connexion a la base de donnees
        $linkpdo = bdLink();
        $retour = 0;

        //preparation de la requete pour la modification d'un article
        $req = $linkpdo -> prepare("UPDATE article SET text= :text, date_pub = CURRENT_TIMESTAMP WHERE id_article = :id");
        //execution de la requete
        $res = $req -> execute(array('text' => $tab['text'], 'id' => $tab['id']));

        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
        }
    }
?>