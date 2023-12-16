<?php
namespace PHP\Scriptstests;

use PHP\Enum_niveau_logger;
use PHP\Enum_role_user;
use PHP\Logger;
use PHP\MySQLDataManagement;
use PHP\User;
use function PHP\getTodayDate;
use function PHP\import_config;

include_once "MySQLDataManagement.php";
include_once "Utility.php";

session_start();

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

//$logger = new Logger("../../LOGS/logs_programme/", Enum_niveau_logger::ERROR);

//on se connecte à la bd
$sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"], $logger);

//on vérifie qu'il n'y a aucune erreur
if ($sqlData->getConnectionErreur() == 0){
    //on insère dans la table users, n utilisateurs tests
    $n_users_tests = 100;
    for ($i=0;$i<$n_users_tests;$i++){
        $user = new User("fauxuser$i@mail.com", "fauxlogin$i" ,"fauxnom$i", "fauxprenom$i", Enum_role_user::USER);

        $reponseRequeteCreationUser = $sqlData->insert_user("Users", $user,"fauxusermotdepasse$i");

        if ($reponseRequeteCreationUser == -1){
            //$logger->error("Connexion impossible à la bd dans script_insertion_faux_users.php", array($this->hostname, $this->username, $this->password, $this->database, $user->serialize()), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));
        }
    }

    //on ferme la connexion à la base de données
    $sqlData->close_connexion_to_db();
}
else{
    //$logger->error("Connexion impossible à la bd dans script_insertion_faux_users.php", array($this->hostname, $this->username, $this->password, $this->database), getTodayDate()->format("Ym"), getTodayDate()->format("Y-m-d H:i:s"));

    //on affiche une erreur à l'utilisateur
    $_SESSION["traitement_envoi_message_conv"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
    header("Location:page_conversations_privees.php");
}


