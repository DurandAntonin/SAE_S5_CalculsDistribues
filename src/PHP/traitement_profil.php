<?php
namespace PHP;

include_once "Utility.php";

include_once "Enum_fic_logs.php";
include_once "MySQLDataManagement.php";

require_once "verif_identite_page_user.php";

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

//on regarde quel formulaire a été saisi
if (!empty($_POST["submit_profil"])){

    //on vérifie que tous les champs du formulaires de connexion ont été saisis
    if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["last_name"]) && !empty($_POST["first_name"]) && !empty($_POST["password"]) && !empty($_POST["password_confirm"])){
        //on récupère les différents input du formulaire
        $login_form = trim($_POST["login"]);
        $mail_form =  trim($_POST["email"]);
        $lastName_form = trim($_POST["last_name"]);
        $firstName_form = trim($_POST["first_name"]);
        $password_form = trim($_POST["password"]);
        $password_confirm_form = trim($_POST["password_confirm"]);

        //on enlève les espaces et/ou caractères spéciaux de certains champs
        $login = deleteSpecialCharacters($login_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);
        $mail = deleteSpecialCharacters($mail_form, $VARIABLES_GLOBALES["listSpecialCharactersMail"]);
        $lastName = deleteSpecialCharacters($lastName_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);
        $firstName = deleteSpecialCharacters($firstName_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

        //on regarde s'il y avait des caractères spéciaux dans ces champs, auquel cas on affiche une erreur à l'utilisateur
        if (strlen($login_form) == strlen($login)
            && strlen($mail) == strlen($mail_form)
            && strlen($lastName_form) == strlen($lastName)
            && strlen($firstName_form) == strlen($firstName)){

            //on vérifie que les deux mots de passes saisis sont identiques
            if (strlen($password_form) == strlen($password_confirm_form)){

                //on récupère les 2 loggers instances pour enregistrer des événements
                $logger = unserialize($_SESSION["logger"]);
                $loggerBd = $logger->getLoggerInstance("loggerDb");
                $loggerFile = $logger->getLoggerInstance("loggerFile");

                //on se connecte à la bd
                $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

                //on vérifie qu'il n'y a aucune erreur
                if ($sqlData->getConnectionErreur() == 0) {
                    //on récupère les informations avant changement de l'utilisateur
                    $user = unserialize($_SESSION["user"]);
                    $userId = $user->getId();
                    $userLoginBeforeChange = $user->getLogin();
                    $userMailBeforeChange = $user->getMail();
                    $userLastNameBeforeChange = $user->getLastName();
                    $userFirstNameBeforeChange = $user->getFirstName();

                    //on regarde quels sont les champs qui ont une info personnelles différente que l'info actuelle
                    //on met le champ mdp de base à true, on vérifie plus tard si le nouveau mdp est différent de l'ancien
                    $listeInfoUsersAChanger = [
                        "Login" => strcmp($userLoginBeforeChange, $login) == 0,
                        "Mail" => strcmp($userMailBeforeChange, $mail) == 0,
                        "LastName" => strcmp($userLastNameBeforeChange, $lastName) == 0,
                        "FirstName" => strcmp($userFirstNameBeforeChange, $firstName) == 0,
                        "Password" => true
                    ];

                    $errorDuringChange = false;
                    foreach ($listeInfoUsersAChanger as $fieldName => $changed){
                        //s'il y a eu une erreur durant le changement d'une information personnelle de l'utilisateur, on arrête
                        if ($errorDuringChange)
                            break;

                        //on exécute une requete sql pour modifier l'info personnelle de l'utilisateur
                        if ($changed){
                            switch ($fieldName){
                                case "Login":
                                    $resultChangeUserLogin = $sqlData->change_user_login("Users", $userId, $login);

                                    //on regarde que la requête s'est exécutée sans erreur
                                    if ($resultChangeUserLogin["error"] == 0){

                                        //on regarde si le changement du login a été effectuée
                                        if ($resultChangeUserLogin["result"]){
                                            //on change l'information dans l'objet user
                                            $user->setLogin($login);

                                            //on enregistre cet événement à l'aide du logger
                                            $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user login|{$userLoginBeforeChange}->{$login}");
                                        }
                                        else{
                                            //le login est deja pris, on affiche une erreur à l'utilisateur
                                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_login_existant"];
                                            header("Location:page_profil.php");
                                        }
                                    }
                                    else{
                                        $errorDuringChange = true;

                                        //on enregistre l'erreur à l'aide du logger
                                        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user login|Erreur:{$resultChangeUserLogin["errorMessage"]}");
                                    }
                                break;
                                case "Mail":
                                    $resultChangeUserMail = $sqlData->change_user_mail("Users", $userId, $mail);

                                    //on regarde que la requête s'est exécutée sans erreur
                                    if ($resultChangeUserMail["error"] == 0){

                                        //on regarde si le changement du mail a été effectuée
                                        if ($resultChangeUserMail["result"]){
                                            //on change l'information dans l'objet user
                                            $user->setMail($mail);

                                            //on enregistre cet événement à l'aide du logger
                                            $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user mail|{$userMailBeforeChange}->{$mail}");
                                        }
                                        else{
                                            //le login est deja pris, on affiche une erreur à l'utilisateur
                                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_login_existant"];
                                            header("Location:page_profil.php");
                                        }
                                    }
                                    else{
                                        $errorDuringChange = true;

                                        //on enregistre l'erreur à l'aide du logger
                                        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user mail|Erreur:{$resultChangeUserMail["errorMessage"]}");
                                    }
                                    break;
                                case "LastName":
                                    $resultChangeUserLastName = $sqlData->change_user_lastname("Users", $userId, $lastName);

                                    //on regarde que la requête s'est exécutée sans erreur
                                    if ($resultChangeUserLastName["error"] == 0){

                                        //on change l'information dans l'objet user
                                        $user->setLastName($lastName);

                                        //on enregistre cet événement à l'aide du logger
                                        $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user last name|{$userLastNameBeforeChange}->{$lastName}");

                                    }
                                    else{
                                        $errorDuringChange = true;

                                        //on enregistre l'erreur à l'aide du logger
                                        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user last name|Erreur:{$resultChangeUserLastName["errorMessage"]}");
                                    }
                                    break;
                                case "FirstName":
                                    $resultChangeUserFirstName = $sqlData->change_user_firstname("Users", $userId, $firstName);

                                    //on regarde que la requête s'est exécutée sans erreur
                                    if ($resultChangeUserFirstName["error"] == 0){

                                        //on change l'information dans l'objet user
                                        $user->setLastName($lastName);

                                        //on enregistre cet événement à l'aide du logger
                                        $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user first name|{$userFirstNameBeforeChange}->{$firstName}");

                                    }
                                    else{
                                        $errorDuringChange = true;

                                        //on enregistre l'erreur à l'aide du logger
                                        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user first name|Erreur:{$resultChangeUserFirstName["errorMessage"]}");
                                    }
                                    break;
                                case "Password":
                                    $resultChangeUserPassword = $sqlData->change_user_password("Users", "Weak_passwords", $userId, $password_form);

                                    //on regarde que la requête s'est exécutée sans erreur
                                    if ($resultChangeUserPassword["error"] == 0){

                                        //on regarde si le changement n'a pas eu lieu car le mdp est identique à l'ancien
                                        if ($resultChangeUserPassword["result"] == -1){
                                            break;
                                        }

                                        //on regarde si le changement n'a pas eu lieu car le mdp est trop fragile
                                        elseif ($resultChangeUserPassword["result"] == -2){
                                            break;
                                        }

                                        else{
                                            //on enregistre cet événement à l'aide du logger
                                            $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user password");
                                        }
                                    }
                                    else{
                                        $errorDuringChange = true;

                                        //on enregistre l'erreur à l'aide du logger
                                        $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user password|Erreur:{$resultChangeUserPassword["errorMessage"]}");
                                    }
                                    break;
                            }
                        }
                    }

                    //on ferme la connexion au serveur MySQL
                    $sqlData->close_connexion_to_db();

                    //on remet l'objet user avec ou non les nouvelles info
                    $_SESSION["user"] = serialize($user);

                    //on affiche une erreur à l'utilisateur si on n'a pas réussi à prendre en compte tous les changements
                    if ($errorDuringChange){
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                    }
                    else{
                        //pas d'erreur, on indique au user que les changements ont été effectués
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changements_reussis"];
                        $_SESSION["message_positif"] = true;
                    }

                    header("Location:page_profil.php");
                }
                else{
                    //on affiche une erreur à l'utilisateur
                    $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$sqlData->getConnectionErreurMessage()}");
                    $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                    header("Location:page_connexion.php");
                }
            }
            else{
                //echo "Mots de passes non identiques";
                //on redirige l'utilisateur vers la page de profil
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_differents"];
                header("Location:page_profil.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
            header("Location:page_connexion.php");
        }
    }
    else{
        //echo "Champs incorrects";
        $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_connexion.php");
    }
}
else{
    header("Location:page_profil.php");
}
?>