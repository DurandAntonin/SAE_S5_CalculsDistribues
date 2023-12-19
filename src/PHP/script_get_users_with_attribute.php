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
    $fieldSearch = $paramExecRequete["fieldSearch"];
    $stringToSearch = $paramExecRequete["stringToSearch"];

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on vérifie que l'attribut de recherche est valide
    $listFieldNamesUser = $user->getListFieldNames();

    //on va stocker les paramètres de renvoi du script
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on récupère les 2 loggers instances pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    if (in_array($fieldSearch, $listFieldNamesUser)){
        //on se connecte à la bd
        $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

        //on vérifie qu'il n'y a aucune erreur
        if ($sqlData->getConnectionErreur() == 0) {
            //on va exécuter une requete sql pour sélectionner les users en fonction du filtre de recherche
            $resultRequestGetUsers = $sqlData->get_users_with_attribute("Users", $fieldSearch, $stringToSearch);

            $sqlData->close_connexion_to_db();

            //on regarde si une erreur est survenue au cours du script
            if ($resultRequestGetUsers["error"] == 0){
                $listeResultParams["result"] = $resultRequestGetUsers["result"];
            }
            else{
                $errorMessage = $resultRequestGetUsers["errorMessage"];
                $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur script_get_users_with_attribute|Erreur:{$errorMessage}}");
                $listeResultParams["error"] = 1;
                $listeResultParams["errorMessage"] = $errorMessage;
            }
        }
        else{
            //on renvoie une erreur
            $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur script_get_users_with_attribute|Erreur:{$sqlData->getConnectionErreurMessage()}");
            $listeResultParams["error"] = 1;
            $listeResultParams["errorMessage"] = $sqlData->getConnectionErreurMessage();
        }
    }
    else{
        //on renvoie une erreur
        $errorMessage = $VARIABLES_GLOBALES["notif_erreur_attribut_incorrect"];
        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur script_get_users_with_attribute|Erreur:{$errorMessage}");
        $listeResultParams["error"] = 1;
        $listeResultParams["errorMessage"] =  $errorMessage;
    }
    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams, depth: 5);
}
else{
    $header("Location:page_accueil_admin.php");
}
