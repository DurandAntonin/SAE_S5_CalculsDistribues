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
    $timeFilter = $paramExecRequete["timeFilter"];

    //on récupère les 2 loggers instances pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on va stocker dans une liste l'erreur et/ou le résultat de chaque requete
    $listeResultParams = array();
    $listeResultParams["connBd"] = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on se connecte à la bd
    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

    //on vérifie qu'il n'y a aucune erreur
    if ($sqlData->getConnectionErreur() == 0) {
        //on définit les dates de début et de fin pour la recherche
        $startDate = $timeFilter[0];
        $endDate = $timeFilter[1];

        //on exécute une première requete pour sélectionner le nombre d'utilisateurs
        $resultRequestGetNbUsers = $sqlData->get_nb_users_with_registration_dates("Users", $startDate, $endDate);
        $listeResultParams["resultRequestGetNbUsers"] = $resultRequestGetNbUsers;

        //on exécute une deuxieme requete pour sélectionner le nombre de visites
        $resultRequestGetNbVisits = $sqlData->get_nb_visits_with_dates("Logging", $startDate, $endDate);
        $listeResultParams["resultRequestGetNbVisits"] = $resultRequestGetNbVisits;

        //on exécute une troisième requete pour sélectionner le nombre d'utilisations des modules
        $resultRequestGetNbModuleUses = $sqlData->get_nb_module_uses_with_dates("Logging", $startDate, $endDate);
        $listeResultParams["resultRequestGetNbModuleUses"] = $resultRequestGetNbModuleUses;

        //on parcourt chaque résultat de requete sql, et on enregistre le(s) erreur(s) s'il y en a
        foreach ($listeResultParams as $resultParamKey => $resultParamValue ){
            if ($resultParamValue["error"] == 1){
                //on enregistre l'erreur dans un fichier de log
                $loggerFile->error($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur internet|Erreur:{$resultParamValue["errorMessage"]}");
            }
        }
    }
    else{
        //on renvoie une erreur
        $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$sqlData->getConnectionErreurMessage()}");
        $listeResultParams["connBd"]["error"] = 1;
        $listeResultParams["connBd"]["errorMessage"] = $sqlData->getConnectionErreurMessage();
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams, depth: 5);
}
else{
    //acces au script non autorise, on redirige vers la page d'accueil
    header("Location:../index.html");
}
