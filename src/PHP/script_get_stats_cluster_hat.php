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


if (isset($header["Content-Type"]) && $header["Content-Type"] == "application/json-charset=utf-8") {
    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();

    //on récupère le logger file pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    //on exécute une commande bash qui s'occupe d'écrire dans un fichier les statistiques du cluster hat
    $output = null;
    $resultCode = null;

    //on donne au fichier un nom précis
    $outputFileName = guidv4() . "csv";

    $command = "echo \"{$VARIABLES_GLOBALES["chemin_script_get_stats_cluster_hat"]} {$outputFileName}\" > {$VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"]}";
    //echo $command;

    //on exécute la commande
    exec($command,$output,$resultCode);
    //$resultat_calcul = $resultCode[0];

    //on attend n secondes le temps que la commande s'exécute
    sleep(40);

    //on va stocker dans une liste les paramètres de renvoi
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];
    $listStatsClusterHat = array();

    //on regarde si le fichier a été créé
    $outputFile = $VARIABLES_GLOBALES["repertoire_resultat_script_get_stats_cluster_hat"] . $outputFileName;
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

        //on ajoute la liste des stats du cluster hat dans la liste de renvoi
        $listeResultParams["result"] = $listStatsClusterHat;
    }
    else{
        //on enregistre a l'aide du logger l'erreur
        $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Fichier resultat_script_get_stats_cluster_hat non créé dans les 10 secondes de l'exécution du script");
        $listeResultParams["connBd"]["error"] = 1;
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams);
}
else{
    $header("Location:page_accueil_admin.php");
}