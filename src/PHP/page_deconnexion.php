<?php

namespace PHP;

include_once "Utility.php";

include_once "Enum_fic_logs.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";


session_start();

//on récupère l'objet user
if (!empty($_SESSION["user"])){
    $user = unserialize($_SESSION["user"]);
    //on charge les variables d'environnement
    $VARIABLES_GLOBALES = import_config();

    //echo "ici";

    //on enregistre dans un fichier de log la déconnexion d'un user
    //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Déconnexion d'un user", "User id : " . $user->getId(), "User role : " . $user->getRole() ,getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);
    //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Connexion d'un user inscrit", $user->getId(), getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

}


session_destroy();

header("Location:../index.html");