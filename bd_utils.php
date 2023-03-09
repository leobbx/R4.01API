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
?>