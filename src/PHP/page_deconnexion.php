<?php

namespace PHP;

include_once "MySQLDataManagement.php";
include_once "User.php";
include_once "Logger.php";
include_once "LoggerInstance.php";
include_once "Logging.php";

session_start();

//on récupère l'objet user
if (!empty($_SESSION["user"]) && !empty($_SESSION["logger"])){
    $user = unserialize($_SESSION["user"]);

    //on récupère l'objet logger pour enregistrer l'information
    $logger = unserialize($_SESSION["logger"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");

    //on se reconnecte à la bd
    $loggerBd->getMySqlConnector()->reconnect_to_bd();

    $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Déconnexion user {$user->getRole()->name}");
    $loggerBd->disconnectLoggerInstanceBd();
}


session_destroy();

header("Location:../index.html");