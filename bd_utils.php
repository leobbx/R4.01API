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

    function addData($tab){
        $linkpdo = bdLink();
        $req = $linkpdo -> prepare("INSERT INTO chuckn_facts (phrase,vote,date_ajout,date_modif,faute,signalement) VALUES (:phrase,0,CURRENT_TIMESTAMP,NULL,0,0)");
        $res  = $req -> execute(array('phrase' => $tab['phrase']));

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