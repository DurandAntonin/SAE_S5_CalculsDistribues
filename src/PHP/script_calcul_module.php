<?php

namespace PHP;

include_once "Utility.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";
include_once "Logger.php";
include_once "LoggerInstance.php";
include_once "CommandBuilder.php";

session_start();

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();
$header = getallheaders();
$chaine_JSON = file_get_contents("php://input");


if (isset($header["Content-Type"]) && $header["Content-Type"] == "application/json-charset=utf-8") {
    $paramExecRequete = json_decode($chaine_JSON, true);
    $mode = $paramExecRequete["mode"];

    //on récupère le logger file pour enregistrer des événements
    $logger = unserialize($_SESSION["logger"]);
    $loggerFile = $logger->getLoggerInstance("loggerFile");
    $loggerBd = $logger->getLoggerInstance("loggerDb");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    $user = unserialize($_SESSION["user"]);
    $userId = $user->getId();
    $userRole = $user->getRole();

    //on va stocker dans une liste les paramètres de renvoi
    $listeResultParams = ["error"=>0, "errorMessage"=>"", "result"=>null];

    //on regarde quel est le mode utilisé
    //0 = exécute un script python
    //1 = on regarde dans le fichier d'indicateur si le programme s'est terminé
    //2 = récupère le résultat du script python (liste des nombres premiers et temps d'exécution)
    if ($mode == 0){
        $execMode = $paramExecRequete["execMode"]; // mode d'exécution du script distribué ou non
        $numModule = $paramExecRequete["numModule"]; //module du site, pour déterminer quel script python exécuter

        $outputFileName = guidv4() . ".json"; //fichier qui stocke le résultat du script python

        $output = null;
        $resultCode = null;

        //on exécute une commande pour récupérer le hostname courant
        $fileNameForHostname = guidv4() . ".txt";
        $commandGetHostName = "echo \"{$VARIABLES_GLOBALES["chemin_script_get_hostname"]} {$fileNameForHostname}\" > {$VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"]}";
        exec($commandGetHostName, $output, $resultCode);

        //on attend 1s
        sleep(1);

        $hostname = "";
        if (file_exists($VARIABLES_GLOBALES["repertoire_resultat"] . $fileNameForHostname)){
            $fp = fopen($VARIABLES_GLOBALES["repertoire_resultat"] . $fileNameForHostname, "r");
            $hostname = trim(fread($fp, 10));
            //echo $hostname;
            fclose($fp);
        }

        //fichier qui va contenir le résultat de la commande
        $outputFile = $VARIABLES_GLOBALES["chemin_result_dans_pi"] . $outputFileName;

        //on regarde quel script exécuter en fonction du module
        //on insère dans une liste les paramètres du script
        //on regarde dans quel pipe envoyer le script
        $pathToScript = "";
        $listScriptParameter = array();
        $pipeToExecuteScript = "";
        if ($numModule == 1){
            $pathToScript = $VARIABLES_GLOBALES["chemin_script_calcul_nombres_premiers"];

            $borneMin = $paramExecRequete["bornes"][0];
            $borneMax = $paramExecRequete["bornes"][1];
            $listScriptParameter = [$borneMin, $borneMax, $outputFile];

            $pipeToExecuteScript = $VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"];
        }

        else if ($numModule == 2){
            $pathToScript = $VARIABLES_GLOBALES["chemin_script_calcul_pi"];

            $nbLancers = $paramExecRequete["nbLancers"];
            $listScriptParameter = [$nbLancers, $outputFile];

            $pipeToExecuteScript = $VARIABLES_GLOBALES["chemin_pipe_module_calcul_pi_dans_conteneur"];
        }

        //on créé une liste pour stocker les hostnames de chaque rpi pour le calcul
        $listHostnames = array();
        $listHostnameAvailable = $VARIABLES_GLOBALES["list-hostnames-valides"];
        if ($userRole == Enum_role_user::USER && $execMode){
            if ($hostname == $listHostnameAvailable[0])
                $listHostnames = [$hostname, "pi2", "pi3", "pi4"];
            elseif ($hostname == $listHostnameAvailable[1])
                $listHostnames = [$hostname, "pi3", "pi4"];
            elseif ($hostname == $listHostnameAvailable[2])
                $listHostnames = [$hostname, "pi2", "pi4"];
            elseif ($hostname == $listHostnameAvailable[3])
                $listHostnames = [$hostname, "pi2", "pi3"];
        }
        elseif (!$execMode && ($userRole == Enum_role_user::USER || $userRole == Enum_role_user::VISITEUR)){
            //on vérifie que le hostname existe bien
            if (in_array($hostname, ["pi1", "pi2", "pi3", "pi4"])){
                $listHostnames[] = $hostname;
            }
        }

        //on créé une liste pour stocker les paramètres de la commande
        $listCommandParameter = ["--mca ", "btl_tcp_if_include 172.19.181.0/24"];

        //on on construit et build la commande
        $commandBuilder = new CommandBuilder($pathToScript, $listScriptParameter, $listCommandParameter, $listHostnames, $pipeToExecuteScript);
        $command = $commandBuilder->buildCommand();
        //echo $command;

        //on regarde s'il y a un problème
        if ($command == ""){
            //le module est inconnu, on enregistre l'erreur
            //on enregistre a l'aide du logger le warning
            $commandStr = $commandBuilder->__toString();
            $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur lors du build de la Commande : {$commandStr}");
            $listeResultParams["error"] = 1;
            $listeResultParams["errorMessage"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
        }
        else{
            //on enregistre à l'aide d'un logger l'utilisation du module par le user
            $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Utilisation module{$numModule}");

            //on exécute la commande
            exec($command,$output,$resultCode);

            //on renvoie au script le nom du fichier contenant le résultat du programme
            //ainsi que le nom du fichier indiquant l'exécution du programme terminée ou non
            $listeResultParams["result"] = $outputFileName;
        }
    }
    elseif ($mode == 1){
        $outputFileName = $paramExecRequete["outputFileName"];

        //on ouvre le fichier et on récupère le résultat
        $outputFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $outputFileName;

        //on vérifie qu'il existe
        if (file_exists($outputFile)){
            $listeResultParams["result"] = true;
        }
        else{
            $listeResultParams["result"] = false;
        }
    }

    elseif ($mode == 2){
        $outputFileName = $paramExecRequete["fileName"];

        //on regarde si le fichier a été créé
        $outputFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $outputFileName;

        if (file_exists($outputFile)){
            //on récupère le résultat de l'exécution du programme sous format json
            $outputFileContent = file_get_contents($outputFile);

            //on ajoute la liste des stats du cluster hat dans la liste de renvoi
            $listeResultParams["result"] = json_decode($outputFileContent, true);
        }
        else{
            //on enregistre a l'aide du logger le warning
            $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Fichier {$outputFile} non présent");
            $listeResultParams["error"] = 1;
            $listeResultParams["errorMessage"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
        }
    }

    else{
        //on enregistre a l'aide du logger le warning
        $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Mode {$mode} dans script_get_stats_cluster_hat inconnu");
        $listeResultParams["error"] = 1;
        $listeResultParams["errorMessage"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams);
}