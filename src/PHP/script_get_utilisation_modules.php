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
    //on récupère les 2 loggers instances pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on va stocker dans une liste le résultat du script
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on se connecte à la bd
    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

    //on vérifie qu'il n'y a aucune erreur
    if ($sqlData->getConnectionErreur() == 0) {
        $listLoggingFieldNames = Logging::defaultLogging()->getListFieldNames();

        //on exécute une requete sql pour récupérer le nombre d'utilisations de chaque module
        $resultRequestGetNbModuleUses = $sqlData->get_logs_with_attribute("Logging", $listLoggingFieldNames[count($listLoggingFieldNames)-1], "Utilisation module");

        $sqlData->close_connexion_to_db();

        //on vérifie qu'il n'y a pas eu d'erreur
        if ($resultRequestGetNbModuleUses["error"] == 0){
            //on compte le nombre d'utilisations de chaque module
            $listeResultParams["result"]["module1"] = 0;
            $listeResultParams["result"]["module2"] = 0;
            $listeResultParams["result"]["module3"] = 0;

            foreach ($resultRequestGetNbModuleUses["result"] as $logModuleUse){
                $moduleDescription = $logModuleUse->getDescription();
                if (str_contains($moduleDescription, "1")){
                    $listeResultParams["result"]["module1"] ++;
                }
                elseif (str_contains($moduleDescription, "2")){
                    $listeResultParams["result"]["module2"] ++;
                }
                elseif (str_contains($moduleDescription, "3")){
                    $listeResultParams["result"]["module3"] ++;
                }
            }
        }
        else{
            $listeResultParams["errorMessage"] = $resultRequestGetNbModuleUses["errorMessage"];
        }

    }
    else{
        //on renvoie une erreur
        $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$sqlData->getConnectionErreurMessage()}");
        $listeResultParams["error"] = 1;
        $listeResultParams["errorMessage"] = $sqlData->getConnectionErreurMessage();
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams);
}
else{
    //acces au script non autorise, on redirige vers la page d'accueil
    header("Location:../index.html");
}
