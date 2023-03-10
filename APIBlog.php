<?php
/// Librairies nécéssaire
 include('fonctionServeur.php');
 include('jwt_utils.php');

 /// Paramétrage de l'entête HTTP (pour la réponse au Client)
 header("Content-Type:application/json");

 /// Lien avec la bd
 $linkpdo = bdLink();

 /// Verification du tocken
 if (is_jwt_valid(get_bearer_token())){

 /// Identification du type de méthode HTTP envoyée par le client
 $http_method = $_SERVER['REQUEST_METHOD'];
 switch ($http_method){
 /// Cas de la méthode GET
 case "GET" :
 /// Récupération des critères de recherche envoyés par le Client
 if (!empty($_GET['id'])){
    $req = $linkpdo->prepare('SELECT * FROM chuckn_facts where id=:id');
    $res = $req->execute(array('id'=>$_GET['id']));

    if($res == false){
        $req -> debugDumpParams();
            die('Erreur execute');
    } else {
        $matchingData = $req->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    $req = $linkpdo->query('SELECT * FROM chuckn_facts');

    if($req == false){
        $req -> debugDumpParams();
            die('Erreur execute');
    } else {
        $matchingData = $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
/// Envoi de la réponse au Client
deliver_response(200, "Operation effectier avec succés", $matchingData);
break;

/// Cas de la méthode POST
case "POST" :
 /// Récupération des données envoyées par le Client
 $postedData = file_get_contents('php://input');
 $data = json_decode($postedData,true);

 if (isset($data['phrase'])){
    addData($data);
    /// Envoi de la réponse au Client
    deliver_response(201, "Les données ont bien etait enregisté", NULL);
 } else {
    deliver_response(400, "Donnée manquante pour l'enregistrement", NULL);
 }

 break;
 /// Cas de la méthode PUT
 case "PUT" :
 /// Récupération des données envoyées par le Client
 $postedData = file_get_contents('php://input');
 $data = json_decode($postedData,true);

 if (isset($data['phrase'])&&isset($data['id'])){
    modifData($data);
    /// Envoi de la réponse au Client
    deliver_response(201, "Les données ont bien etait modifier", NULL);
 } else {
    deliver_response(400, "Donnée manquante pour la modification", NULL);
 }
 break;
 /// Cas de la méthode DELETE
 case "DELETE" :
 /// Récupération de l'identifiant de la ressource envoyé par le Client
 if (!empty($_GET['id'])){
    delData($_GET['id']);
    deliver_response(200, "Donnée corectement suprimé", NULL);
 }
 /// Envoi de la réponse au Client
 deliver_response(400, "Donnée manquante pour la supression", NULL);
 break;
 default :
 deliver_response(405, "Aucune methode corespondante existante ou implementé", NULL);
 break;
 }
 } else {
    deliver_response(401, "Token invalide", NULL);
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