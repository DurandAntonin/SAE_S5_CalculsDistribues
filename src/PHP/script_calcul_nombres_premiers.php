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
    //0 = exécute le script python pour calculer les nombres premiers
    //1 = on regarde dans le fichier d'indicateur si le programme s'est terminé
    //2 = récupère le résultat du script python (liste des nombres premiers et temps d'exécution)
    if ($mode == 0){
        $borneMin = $paramExecRequete["bornes"][0];
        $borneMax = $paramExecRequete["bornes"][1];
        $execMode = $paramExecRequete["execMode"]; // mode d'exécution du script distribué ou non

        $outputFileName = guidv4() . ".json"; //fichier qui stocke le résultat du script python
        $indicatorFileName = guidv4() . ".txt"; //fichier indiquant si le script s'est terminé ou non

        //fichier qui va indiquer si le calcul est terminé
        $indicatorFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $indicatorFileName;
        //$fp = fopen( $indicatorFile, "w");
        //fputs($fp, false);
        //fclose($fp);

        $output = null;
        $resultCode = null;

        //on exécute une commande pour récupérer le hostname courant
        $fileNameForHostname = guidv4() . ".txt";
        $commandGetHostName = "echo \"{$VARIABLES_GLOBALES["chemin_script_get_hostname"]} {$fileNameForHostname}\" > {$VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"]}";
        exec($commandGetHostName, $output, $resultCode);

        //on attend 1s
        sleep(1);

        $fp = fopen($VARIABLES_GLOBALES["repertoire_resultat"] . $fileNameForHostname, "r");
        $hostname = fread($fp, 1024);
        //echo $hostname;
        fclose($fp);

        //la commande change en fonction du role du user et du mode d'exécution (distribué ou non du programme)
        $command = null;
        $outputFile = "/home/pi/pipeDockerSwarm/outputsStats/".$outputFileName;
        $indicatorFile = "/home/pi/pipeDockerSwarm/outputsStats/".$indicatorFileName;
        if ($userRole == Enum_role_user::USER && $execMode){
            //on stocke dans une chaine de caratères la liste des hostname de chaque pi
            $listHostnames = array();
            if ($hostname == "pi1")
                $listHostnames = [$hostname, "pi2", "pi3", "pi4"];
            elseif ($hostname == "pi2")
                $listHostnames = [$hostname, "pi3", "pi4"];
            elseif ($hostname == "pi3")
                $listHostnames = [$hostname, "pi2", "pi4"];
            else
                $listHostnames = [$hostname, "pi2", "pi3"];

            $hostnames = implode(",", $listHostnames);

            $command = "echo \"mpiexec -n 3 --host $hostnames python /home/pi/prime.py {$borneMin} {$borneMax} {$outputFile} {$indicatorFile}  --mca btl_tcp_if_include 172.19.181.0/24\" > {$VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"]}";
        }
        elseif (!$execMode && ($userRole == Enum_role_user::USER || $userRole == Enum_role_user::VISITEUR))
            $command = "echo \"mpiexec -n 1 --host $hostname python /home/pi/prime.py {$borneMin} {$borneMax} {$outputFile} {$indicatorFile} --mca btl_tcp_if_include 172.19.181.0/24\" > {$VARIABLES_GLOBALES["chemin_pipe_module_nb_premiers_dans_conteneur"]}";

        else
            $command = "";


        //on regarde s'il y a un problème
        if ($command == ""){
            //le user n'est pas connecté et essaie d'exécuter le programme de manière distribué, on enregistre l'erreur
            //on enregistre a l'aide du logger le warning
            $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Le user n'est pas connecté et essaie d'exécuter le programme de manière distribué");
            $listeResultParams["error"] = 1;
        }
        else{
            //on enregistre à l'aide d'un logger l'utilisation du module par le user
            $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Utilisation module1");

            //on exécute la commande
            exec($command,$output,$resultCode);
            //$resultat_calcul = $resultCode[0];

            //on renvoie au script le nom du fichier contenant le résultat du programme
            //ainsi que le nom du fichier indiquant l'exécution du programme terminée ou non
            $listeResultParams["result"] = [$outputFileName, $indicatorFileName];
        }
    }
    elseif ($mode == 1){
        $indicatorFileName = $paramExecRequete["indicatorFileName"];

        //on ouvre le fichier et on récupère le résultat
        $indicatorFile = $VARIABLES_GLOBALES["repertoire_resultat"] . $indicatorFileName;

        //on vérifie qu'il existe
        if (file_exists($indicatorFile)){
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
        }
    }

    else{
        //on enregistre a l'aide du logger le warning
        $loggerFile->warning($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Mode {$mode} dans script_get_stats_cluster_hat inconnu");
        $listeResultParams["error"] = 1;
    }

    //en renvoie le résultat des requetes au script js sous format json
    echo json_encode($listeResultParams);
}