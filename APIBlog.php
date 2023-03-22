<?php
/// Librairies nécéssaire
include('bd_utils.php');
include('jwt_utils.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode http
$http_method = $_SERVER['REQUEST_METHOD'];

/// Verification du tocken
if (is_jwt_valid(get_bearer_token())){
    ///Verification du role
    switch(get_role(get_bearer_token())){
        ///Traitement publisher
        case "publisher":
            switch ($http_method){
                /// Cas de la méthode GET
                case "GET" :
                    /// Récupération des critères de recherche envoyés par le Client
                    $id = 0;
                    if (!empty($_GET['id'])){
                        $id = $_GET['id'];
                    }
        
                    $matchingData = getMessagePub($id);
        
                    if (count($matchingData)==0) {
                        /// Envoi de l'erreur au client
                        deliver_response(400, "No matching data found", NULL);
                    } else {
                        /// Envoi de la réponse au client
                        deliver_response(200, "Operation successfuly complete", $matchingData);
                    }
                    break;
                case "POST":
                    /// Recuperation des donné envoyer
                    $posteddata = file_get_contents('php://input');
                    $data = json_decode($posteddata,true);

                    if(addArticle($data)==0) {
                        /// Envoi de la réponse au Client
                        deliver_response(200, "Data successfuly added", NULL);
                    } else {
                        /// Envoi de l'erreur serveur
                        deliver_response(500, "Internal server error", NULL);
                    }

                    break;
                case "DELETE" :
                    /// Récupération des critères de supression envoyés par le Client (id)
                    if (!empty($_GET['id'])){
                        $id = $_GET['id'];

                        if(belongToPublisher($id,get_login(get_bearer_token()))) {
                            if(delArticle($id)==0) {
                                /// Envoi de la réponse au Client
                                deliver_response(200, "Data successfuly deleted", NULL);
                            } else {
                                /// Envoi de l'erreur serveur
                                deliver_response(500, "Internal server error", NULL);
                            }
                        } else {
                            /// ENvoie l'erreur au client
                            deliver_response(405, "Insufficent permission - Imposibility to remove an other user's article",NULL);
                        }
                    } else {
                        /// Envoi l'erreur au Client indiqunat le manque d'id
                        deliver_response(400, "Missing Data - ID", NULL);
                    }
                    break;
                case "PUT" :
                    if(belongToPublisher($id,get_login(get_bearer_token()))) {
                        $posteddata = file_get_contents('php://input');
                        $data = json_decode($posteddata,true);
                        if(modifArticle($data)==0) {
                            /// Envoi de la réponse au Client
                            deliver_response(200, "Data successfuly modify", NULL);
                        } else {
                            /// Envoi de l'erreur serveur
                            deliver_response(500, "Internal server error", NULL);
                        }
                    } else {
                        /// ENvoie l'erreur au client
                        deliver_response(405, "Insufficent permission - Imposibility to alter an other user's article",NULL);
                    }
                    break;
                case "PATCH" :

                    break;
                default:
                    deliver_response(405, "Insufficent permission or no matching method", NULL);
                    break;           
            }
            break;
        ///Traitement moderator
        case "moderator":
            switch ($http_method){
                /// Cas de la méthode GET
                case "GET" :
                    /// Récupération des critères de recherche envoyés par le Client
                    $id = 0;
                    if (!empty($_GET['id'])){
                        $id = $_GET['id'];
                    }
        
                    $matchingData = getMessageMod($id);
        
                    if (count($matchingData)==0) {
                        /// Envoi de l'erreur au client
                        deliver_response(400, "No matching data found", NULL);
                    } else {
                        /// Envoi de la réponse au client
                        deliver_response(200, "Operation successfuly complete", $matchingData);
                    }
                    break;
                case "DELETE" :
                    /// Récupération des critères de supression envoyés par le Client (id)
                    if (!empty($_GET['id'])){
                        $id = $_GET['id'];

                        if(delArticle($id)==0) {
                            /// Envoi de la réponse au Client
                            deliver_response(200, "Data successfuly deleted", NULL);
                        } else {
                            /// Envoi de l'erreur serveur
                            deliver_response(500, "Internal server error", NULL);
                        }
                    } else {
                        /// Envoi l'erreur au Client indiqunat le manque d'id
                        deliver_response(400, "Missing Data - ID", NULL);
                    }
                    break;
                default:
                    deliver_response(405, "Insufficent permission or no matching method", NULL);
                    break;
            }
            break;
        ///Message d'erreur si role inconu
        default:
            deliver_response(401, "Invalid role",get_role(get_bearer_token()) );
            break;
    }
 
} else {
    ///Traitement non user non connecté
    $http_method = $_SERVER['REQUEST_METHOD'];

    switch ($http_method){
        /// Cas de la méthode GET
        case "GET" :
            /// Récupération des critères de recherche envoyés par le Client
            $id = 0;
            if (!empty($_GET['id'])){
                $id = $_GET['id'];
            }

            $matchingData = getMessage($id);

            if (count($matchingData)==0) {
                /// Envoi de l'erreur au client
                deliver_response(400, "No matching data found", NULL);
            } else {
                /// Envoi de la réponse au client
                deliver_response(200, "Operation successfuly complete", $matchingData);
            }
            break;
        default:
            deliver_response(405, "Insufficent permission or no matching method", NULL);
            break;
    }
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