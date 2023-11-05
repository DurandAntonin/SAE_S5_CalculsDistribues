<?php
namespace PHP;

include_once "Utility.php";

include_once "Enum_fic_logs.php";
include_once "MySQLDataManagement.php";
include_once "Enum_fic_logs.php";
include "Pagination.php";

session_start();

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();
$header = getallheaders();
$chaine_JSON = file_get_contents("php://input");
//print_r($chaine_JSON);
if (isset($header["Content-Type"]) && $header["Content-Type"] == "application/json-charset=utf-8") {
    $paramExecRequete = json_decode($chaine_JSON, true);
    $mailSaisi = $paramExecRequete["chaineSaisie"];
    $pagination = new Pagination($paramExecRequete["pagination"][0], $paramExecRequete["pagination"][1]);
    $modeExec = $paramExecRequete["modeExec"];

    //print_r($paramExecRequete);

    //on vérifie que la chaine a bien été convertie au format JSON
    if (json_last_error() == JSON_ERROR_NONE) {
        //$logger = unserialize($_SESSION["logger"]);

        //on se connecte à la base de données
        $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

        if ($sqlData->getConnectionErreur() == 0){
            //on récupère la liste des users ayant $mailSaisi dans leur adresse mail, avec une pagination
            $reponseRequeteSelectionUsers = $sqlData->get_users_by_mail_appro("Users", $mailSaisi, $pagination);
            $reponseRequeteSelectNombreUsersTotal = null;

            if ($modeExec == "1")
                $reponseRequeteSelectNombreUsersTotal = $sqlData->get_number_users_by_mail("Users", $mailSaisi, $pagination);

            //on vérifie qu'il n'y a pas d'erreurs
            if ($reponseRequeteSelectionUsers == -1 || $reponseRequeteSelectNombreUsersTotal == -1){
                echo json_encode("-1");
            }
            elseif ($modeExec == "1"){
                echo json_encode(["listeUsers" => $reponseRequeteSelectionUsers, "numberOfUsers" => $reponseRequeteSelectNombreUsersTotal]);
            }
            else{
                echo json_encode(["listeUsers" => $reponseRequeteSelectionUsers]);
            }

            //on ferme la connexion à la bd
            $sqlData->close_connexion_to_db();
        }
        else{
            echo json_encode("-1");
        }
    }
    else{
        echo json_encode("-1");
    }
}