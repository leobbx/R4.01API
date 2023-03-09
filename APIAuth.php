<?php
/// Librairies nécéssaire
include('jwt_utils.php');
include('bd_utils.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method){
    /// Cas de la méthode POST, seul methode accepté
    case "POST" :
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData,true);

        if (isset($data['login']) && isset($data['password'])){
            if (checkUser($data['login'],$data['password'])){
                $headers = array('alg'=>'HS256','typ'=>'JWT');
                $payload = array('login'=>$data['login'],'role'=>getRole($data['login']),'exp'=>(time()+3600));

                $jwt = generate_jwt($headers,$payload);

                deliver_response(201, "Login success", $jwt);
            } else {
                deliver_response(401, "Login or password invalide", NULL);
            }
        } else {
            deliver_response(400, "Missing login or password", NULL);
        }

        break;
    /// Cas par defaut (methode autre que POST)
    default :
        deliver_response(405, "No corresponding method", NULL);
    break;
}

/// Envoi de la réponse au Client
function deliver_response($status, $status_message, $data){
    /// Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $status_message");

    /// Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response);
    echo $json_response;
}
?>