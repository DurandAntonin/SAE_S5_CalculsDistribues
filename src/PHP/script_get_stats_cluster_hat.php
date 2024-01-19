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


if (isset($header["Content-Type"]) && $header["Content-Type"] == "application/json-charset=utf-8") {
    $paramExecRequete = json_decode($chaine_JSON, true);
    $execMode = $paramExecRequete["execMode"];

    //on récupère le logger file pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on va stocker dans une liste les paramètres de renvoi
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on regarde quel est le mode d'exécution du script
    //0 = exécute la commande pour écrire les stats du cluster dans un fichier
    //1 = regarde si le fichier existe
    //2 = récupère les stats du fichier
    if ($execMode == 0){
        //on donne au fichier un nom précis
        $outputFileName = guidv4() . ".csv";

        $output = null;
        $resultCode = null;

        //on exécute une commande bash qui s'occupe d'écrire dans un fichier les statistiques du cluster hat
        $command = "echo \"{$VARIABLES_GLOBALES["chemin_script_get_stats_cluster_hat"]} {$VARIABLES_GLOBALES["chemin_result_dans_pi"]} {$outputFileName}\" > {$VARIABLES_GLOBALES["chemin_pipe_stats_cluster_hat_dans_conteneur"]}";
        //echo $command;

        //on exécute la commande
        exec($command,$output,$resultCode);
        //$resultat_calcul = $resultCode[0];

        //on renvoi aus script le nom du fichier contenant les stats du cluster hat
        $listeResultParams["result"] = $outputFileName;
    }

    elseif ($execMode == 1){
        $fileName = $paramExecRequete["fileName"];
        $outputFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $fileName;

        //on regarde si le fichier a été créé
        if (file_exists($outputFile)){
            $listeResultParams["result"] = true;
        }
        else{
            $listeResultParams["result"] = false;
        }
    }

    elseif ($execMode == 2){
        $fileName = $paramExecRequete["fileName"];

        //on regarde si le fichier a été créé
        $outputFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $fileName;

        $listStatsClusterHat = array();

        if (file_exists($outputFile)){
            //on l'ouvre en mode csv, et on stocke dans le champ result les stats pour chaque rpi
            $fp = fopen($outputFile, "r");

            //on saute l'entete
            $header = fgetcsv($fp, "1024", ";");

            while ($ligne = fgetcsv($fp, "1024", ";")) {
                $listStatsRpi = array();
                for ($i = 0; $i < count($header); $i++) {
                    $listStatsRpi[$header[$i]] = $ligne[$i];
                }
                $listStatsClusterHat[] = $listStatsRpi;
            }
            fclose($fp);

            //on supprime le fichier
            $result = deleteFileOnHostFromContainer($VARIABLES_GLOBALES["repertoire_resultat"], $fileName);

            //on enregistre dans un log s'il y a eu une erreur
            if ($result[0] == 0)
                $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], $result[1]);

            //on ajoute la liste des stats du cluster hat dans la liste de renvoi
            $listeResultParams["result"] = $listStatsClusterHat;
        }
        else{
            //on enregistre a l'aide du logger le warning
            $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Fichier {$outputFile} non créé ");
            $listeResultParams["error"] = 1;
        }
    }

    else{
        //on enregistre a l'aide du logger le warning
        $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Mode {$execMode} d'exécution de script_get_stats_cluster_hat inconnu");
        $listeResultParams["error"] = 1;
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams);
}
else{
    $header("Location:page_accueil_admin.php");
}