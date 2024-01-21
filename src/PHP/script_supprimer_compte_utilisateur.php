<?php

namespace PHP;

include_once "Utility.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";
include_once "Logger.php";
include_once "LoggerInstance.php";

session_start();

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();
$header = getallheaders();
$chaine_JSON = file_get_contents("php://input");
//print_r($chaine_JSON);

if (isset($header["Content-Type"]) && $header["Content-Type"] == "application/json-charset=utf-8") {
    $paramExecRequete = json_decode($chaine_JSON, true);
    //print_r($paramExecRequete);
    $userIdToDelete = $paramExecRequete["userIdToDelete"];

    //on récupère les 2 loggers instances pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on se connecte à la bd
    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on vérifie qu'il n'y a aucune erreur
    if ($sqlData->getConnectionErreur() == 0) {
        //on exécute une commande sql pour supprimer l'utilisateur de la base de données
        $listeResultParams = $sqlData->supprimer_user("Users", $userIdToDelete);

        //on enregistre l'erreur s'il y en a
        if ($listeResultParams["error"] == 1){
            $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$listeResultParams["errorMessage"]}");

        }
        else{
            //on enregistre la suppression du user
            $listeResultParams["result"] = $userIdToDelete;
            $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Suppression de l'utilisateur avec userId : {$userIdToDelete}");
        }
    }
    else{
        //on renvoie une erreur
        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$sqlData->getConnectionErreurMessage()}");
        $listeResultParams["error"] = 1;
        $listeResultParams["errorMessage"] = $sqlData->getConnectionErreurMessage();
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams, depth: 5);
}
else{
    //acces au script non autorise, on redirige vers la page d'accueil
    header("Location:../index.html");
}