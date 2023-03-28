<?php
    // fonction permettant la connexion à la base de données
    function bdLink(){
        try {
            // information sur la base de données
            $server = "127.0.0.1";
            $db = "projetapi";
            $login = "root";  

            //création de la connexion        
            $linkpdo = new PDO("mysql:host=$server;dbname=$db", $login);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
        return $linkpdo;
    }

    // fonction verifiant le bon login et mot de passe d'un utilisateur
    // renvoie vrai si le login et mot de passe bon
    // sinon non
    function checkUser($login, $password){
        //connexion a la base de données
        $linkpdo = bdLink();

        //requete recuperant le mot de passe correspondant au login
        $req = $linkpdo -> prepare("SELECT password from Utilisateur where login=:login");
        // execution de la requete
        $res  = $req -> execute(array('login' => $login));

        // verification de la bonne execution de la requete
        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
        } else {
            if($pwd = $req->fetch()) {
                // verification du bon mot de passe crypté en sha1
                if ($pwd["password"]==sha1($password)){
                    return true;
                }
            }
        }
        return false;
    }

    // fonction permettant la recuperation du role de l'utilisateur
    function getRole($login){
        //connexion à la base de données
        $linkpdo = bdLink();

        // requete permettant la recuperation du role appartenant au login de l'utilisateur
        $req = $linkpdo -> prepare("SELECT role from Utilisateur where login=:login");
        // executio de la requete
        $res  = $req -> execute(array('login' => $login));

        // verification bonne execution de la requete
        if($res == false){
            $req -> debugDumpParams();
                die('Erreur execute');
        } else {
            //recuperation du role
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
            // ecriture de la requete pour la consultation des donnees d'un article
            $req = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text FROM utilisateur u, article a
                                        WHERE u.id_utilisateur = a.id_utilisateur");
            // execution de la requete                          
            $res = $req -> execute();

            // verification de la bonne execution de la requete
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } else {
                // recuperation des informartions dans un tableau et est retourné
                return $req -> fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            // ecriture de la requete pour la consultation de donnees
            $req = $linkpdo -> prepare("SELECT u.login, a.date_pub, a.text FROM utilisateur u, article a
                                        WHERE u.id_utilisateur = a.id_utilisateur AND a.id_utilisateur=$id");
            // execution de la requete                          
            $res = $req -> execute();

            // verification de la bonne execution de la requete
            if($res == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } else {
                //si oui toutes les informations sont mises dans un tableau et est retourné
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

    // fonction GET c'est propre message
    function getPersonalMessage($login) {
        // appelle a la methode pour la connexion a la base de donnees
        $linkpdo = bdLink();
        $tab = array();
        //requete pour la recuperation des données liés à un article
        $req = $linkpdo -> query("SELECT a.id_article,a.date_pub,a.text,u.login 
                                  FROM article a,utilisateur u WHERE u.id_utilisateur = a.id_utilisateur AND u.login = '$login'");

            // entre dans la boucle si erreur dans la requete                       
            if($req == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } 

            while ($res = $req -> fetch()) {
                //requete comptant le nombre de like d'un article
                $reqnblike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 1 AND l.Id_Article = :id");
                //execution de la requete
                $res1 = $reqnblike -> execute(array("id" => $res['id_article']));

                 // entre dans la boucle si erreur dans la requete                       
                if($res1 == false){
                    $reqnblike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nblike = $resnblike -> fetch();
                }

                // requete comptant le nombre de dislike d'un article
                $reqnbdislike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 0 AND l.Id_Article = :id");
                //execution de la requete
                $res2 = $resnbdislike -> execute(array("id" => $res['id_article']));
                
                 // entre dans la boucle si erreur dans la requete                       
                if($res2 == false){
                    $reqnbdislike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nbdislike = $resnbdislike -> fetch();
                }

                // recuperation de toutes les donnees et insertion dans un tableau
                $tab[] = array(
                    "date de publication" => $res['date_pub'],
                    "contenu" => $res['text'],
                    "nombre de like" => $nblike,
                    "nombre de dislike" => $nbdislike
                );
            }
            return $tab;
    }

    //fonction GET
    function getMessagePub($id) {
        // appelle a la methode pour la connexion a la base de donnees
        $linkpdo = bdLink();
        // cas où l'id est égal à 0
        if ($id == 0) {
            $tab = array();
            //requete pour la recuperation des données liés à un article
            $req= $linkpdo -> query("SELECT a.id_article,a.date_pub,a.text,u.login 
                                    FROM article a,utilisateur u WHERE u.id_utilisateur = a.id_utilisateur");

            // entre dans la boucle si erreur dans la requete                       
            if($req == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } 

            while ($res = $req -> fetch()) {
                //requete comptant le nombre de like d'un article
                $reqnblike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 1 AND l.Id_Article = :id");
                //execution de la requete
                $res1 = $reqnblike -> execute(array("id" => $res['id_article']));

                 // entre dans la boucle si erreur dans la requete                       
                if($res1 == false){
                    $reqnblike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nblike = $reqnblike -> fetchAll(PDO::FETCH_ASSOC);
                }

                // requete comptant le nombre de dislike d'un article
                $reqnbdislike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 0 AND l.Id_Article = :id");
                //execution de la requete
                $res2 = $reqnbdislike -> execute(array("id" => $res['id_article']));
                
                 // entre dans la boucle si erreur dans la requete                       
                if($res2 == false){
                    $reqnbdislike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nbdislike = $reqnbdislike -> fetchAll(PDO::FETCH_ASSOC);
                }

                // recuperation de toutes les donnees et insertion dans un tableau
                $tab[] = array(
                    "auteur" => $res['login'],
                    "date de publication" => $res['date_pub'],
                    "contenu" => $res['text'],
                    "nombre de like" => $nblike,
                    "nombre de dislike" => $nbdislike
                );
            }
            return $tab;
        } else {

            $tab = array();
            //requete pour la recuperation des données liés à un article
            $req = $linkpdo -> query("SELECT a.id_article,a.date_pub,a.text,u.login 
                                    FROM article a,utilisateur u WHERE u.id_utilisateur = a.id_utilisateur AND a.id_article = $id");

            // entre dans la boucle si erreur dans la requete                       
            if($req == false){
                $req -> debugDumpParams();
                die('Erreur execute');
            } 

            while ($res = $req -> fetch()) {
                //requete comptant le nombre de like d'un article
                $reqnblike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 1 AND l.Id_Article = :id");
                //execution de la requete
                $res1 = $reqnblike -> execute(array("id" => $id));

                 // entre dans la boucle si erreur dans la requete                       
                if($res1 == false){
                    $reqnblike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nblike = $resnblike -> fetch();
                }

                // requete comptant le nombre de dislike d'un article
                $reqnbdislike = $linkpdo -> prepare("SELECT COUNT(l.id_article) as nombre FROM article a, like_dislike l 
                                                  WHERE a.id_article = l.Id_Article AND l.statute = 0 AND l.Id_Article = :id");
                //execution de la requete
                $res2 = $resnbdislike -> execute(array("id" => $id));
                
                 // entre dans la boucle si erreur dans la requete                       
                if($res2 == false){
                    $reqnbdislike -> debugDumpParams();
                    die('Erreur execute');
                } else {
                    $nbdislike = $resnbdislike -> fetch();
                }

                // recuperation de toutes les donnees et insertion dans un tableau
                $tab[] = array(
                    "auteur" => $res['login'],
                    "date de publication" => $res['date_pub'],
                    "contenu" => $res['text'],
                    "nombre de like" => $nblike,
                    "nombre de dislike" => $nbdislike
                );
            }
            return $tab;
        }     
    }

    //fonction PATCH
    function likeDislike($tab,$login) {
        //appelle a la methode pour la connexion a la base de donnees
        $linkpdo = bdLink();
        $retour = 0;
        // requete verifiant qu'il y a au moins une ligne inséré avec le login de l'utilisateur et de l'article
        $req = $linkpdo -> prepare("SELECT COUNT(like_dislike.id_utilisateur) as nbligne , like_dislike.statute, utilisateur.id_utilisateur as uti FROM like_dislike , article , utilisateur  
                                    WHERE like_dislike.id_article = article.id_article AND like_dislike.id_utilisateur = utilisateur.id_utilisateur 
                                    AND utilisateur.login = :login AND article.id_article = :id");
        $req3 = $req -> execute(array("login" => $login,
                                     "id" => $tab['id']));
        if ($req3 == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
        } else {
            $res = $req -> fetch();
        }
        // cas où l'utilisateur souhaite mettre un like
        if ($tab['type']=='like') {
            // si la requete donne une ligne
            if ($res['nbligne'] == 1) {
                // cas où la valeur de la ligne déjà insérer est un like
                if ($res['statute'] == 1) {
                    // requete permettant la suppression de l'enregistrement
                    $req1 = $linkpdo -> query("DELETE FROM like_dislike WHERE id_article = ".$tab['id']." AND id_utilisateur = ".$res['uti']."");

                    // rentre dans la boucle si la requete presente une erreur
                    if($req1 == false){
                        $req1 -> debugDumpParams();
                        die('Erreur execute');
                        $retour = 1;
                    } 
                //cas de la ligne deja inserer est un dislike
                } else {

                    // modification de la ligne representant le like de l'utilisateur sur l'article
                    $req2 = $linkpdo -> prepare("UPDATE like_dislike SET statute = 1 WHERE id_article = :id AND id_utilisateur = :uti");
                    //execution de la requete
                    $res1 = $req2 -> execute(array("id"=>$tab['id'],
                                                    "uti"=>$res['uti']));
                    
                    // rentre dans la boucle si la requete presente une erreur                             
                    if($res1 == false){
                        $req2 -> debugDumpParams();
                        die('Erreur execute');
                        $retour = 1;
                    } 
                }
            // cas pas de ligne insérer
            } else {
                // recuperation de l'id correspondant au login
                $req1 = $linkpdo -> query("SELECT id_utilisateur FROM utilisateur WHERE login = '$login'");

                // rentre dans la boucle si la requete presente une erreur
                if($req1 == false){
                    $req1 -> debugDumpParams();
                    die('Erreur execute');
                    $retour = 1;
                } else {
                    $res1 = $req1 -> fetch();
                }
                // requete permettant l'insertion du like de l'utilisateur sur l'article
                $req2 = $linkpdo -> query("INSERT INTO like_dislike VALUES (".$tab['id'].", ".$res1['id_utilisateur'].", 1)");
                
                // rentre dans la boucle si la requete presente une erreur
                if($req2 == false){
                    $req2 -> debugDumpParams();
                    die('Erreur execute');
                    $retour = 1;
                } 
            }
        } else {
            if ($res['nbligne'] == 1) {
                // cas où la valeur de la ligne déjà insérer est un dislike
                if ($res['statute'] == 0) {
                    // suppression de ligne correspondant au dislike
                    $req1 = $linkpdo -> query("DELETE FROM like_dislike WHERE id_article = ".$tab['id']." AND id_utilisateur = ".$res['uti']."");

                    // rentre dans la boucle si la requete presente une erreur
                    if($req1 == false){
                        $req1 -> debugDumpParams();
                        die('Erreur execute');
                        $retour = 1;
                    }
                    
                } else {
                    // cas où la ligne déjà insérer est un like
                    $req1 = $linkpdo -> prepare ("UPDATE like_dislike SET statute = 0 WHERE id_article = :id AND id_utilisateur = :uti");
                    // execution de la requete
                    $res1 = $req1 -> execute(array("id"=>$tab['id'],
                                                   "uti"=>$res['uti']));

                    // rentre dans la boucle si la requete presente une erreur                              
                    if($res1 == false){
                        $req1 -> debugDumpParams();
                        die('Erreur execute');
                        $retour = 1;
                    } 
                }
            } else {
                // cas où aucune ligne presente correspondant à ce login et cet article
                // requete permettant la recuperation de l'id correspondant au login
                $req1 = $linkpdo -> query("SELECT id_utilisateur FROM utilisateur WHERE login = '$login'");

                if($req1 == false){
                    $req1 -> debugDumpParams();
                    die('Erreur execute');
                    $retour = 1;
                } else {
                    $res1 = $req1 -> fetch();
                }
                // requete permettant l'insertion dans la table like_dislike
                $req2 = $linkpdo -> query("INSERT INTO like_dislike VALUES (".$tab['id'].", ".$res1['id_utilisateur'].", 0)");
                
                //rentre dans la boucle si la requete presente une erreur
                if($req2 == false){
                    $req2 -> debugDumpParams();
                    die('Erreur execute');
                    $retour = 1;
                } 
            }
        }
    }
    // fonction PUT
    function modifArticle($tab){
        //appelle a la methode pour la connexion a la base de donnees
        $linkpdo = bdLink();
        $retour = 0;

        //preparation de la requete pour la modification d'un article
        $req = $linkpdo -> prepare("UPDATE article SET text= :text, date_pub = CURRENT_TIMESTAMP WHERE id_article = :id");
        //execution de la requete
        $res = $req -> execute(array('text' => $tab['text'], 'id' => $tab['id']));

        // rentre dans la boucle si la requete presente une erreur
        if($res == false){
            $req -> debugDumpParams();
            die('Erreur execute');
            $retour = 1;
        }
    }
?>