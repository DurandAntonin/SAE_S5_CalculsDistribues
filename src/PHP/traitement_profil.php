<?php
namespace PHP;

include_once "Utility.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";
include_once "Logger.php";
include_once "LoggerInstance.php";

require_once "verif_identite_page_user.php";

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

if (isset($_POST)){
    //on regarde quel formulaire a été saisi
    if (!empty($_POST["submit_profil"])){
        //on vérifie que tous les champs du formulaires de connexion ont été saisis
        if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["last_name"]) && !empty($_POST["first_name"])){

            //on récupère les différents input du formulaire
            $login_form = trim($_POST["login"]);
            $mail_form =  trim($_POST["email"]);
            $lastName_form = trim($_POST["last_name"]);
            $firstName_form = trim($_POST["first_name"]);
            $password_form = null;

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

                //on récupère les 2 loggers instances pour enregistrer des événements
                $logger = unserialize($_SESSION["logger"]);
                $loggerBd = $logger->getLoggerInstance("loggerDb");
                $loggerFile = $logger->getLoggerInstance("loggerFile");

                //on se reconnecte à la bd
                $loggerBd->getMySqlConnector()->reconnect_to_bd();

                //on vérifie que le logger s'est connecté au serveur
                if ($loggerBd->getMySqlConnector()->getConnectionErreur() == 1){
                    //on enregistre l'erreur dans le loggerFile
                    $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur:{$loggerBd->getMySqlConnector()->getConnectionErreurMessage()}");
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                    header("Location:page_accueil_user.php");
                }
                else{
                    $user = unserialize($_SESSION["user"]);
                    $userId = $user->getId();

                    //on vérifie que les champs ont une taille valide
                    if (strlen($mail) >= $VARIABLES_GLOBALES["taille_champ_mail"][0] && strlen($mail) <= $VARIABLES_GLOBALES["taille_champ_mail"][1]
                        && strlen($login) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($login) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                        && strlen($lastName) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($lastName) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                        && strlen($firstName) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($firstName) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                    ){
                        //on se connecte à la bd
                        $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

                        //on vérifie qu'il n'y a aucune erreur
                        if ($sqlData->getConnectionErreur() == 0) {
                            //on récupère les informations avant changement de l'utilisateur
                            $userLoginBeforeChange = $user->getLogin();
                            $userMailBeforeChange = $user->getMail();
                            $userLastNameBeforeChange = $user->getLastName();
                            $userFirstNameBeforeChange = $user->getFirstName();

                            //on regarde quels sont les champs qui ont une info personnelles différente que l'info actuelle
                            $listeInfoUsersAChanger = [
                                "Login" => strcmp($userLoginBeforeChange, $login) != 0,
                                "Mail" => strcmp($userMailBeforeChange, $mail) != 0,
                                "LastName" => strcmp($userLastNameBeforeChange, $lastName) != 0,
                                "FirstName" => strcmp($userFirstNameBeforeChange, $firstName) != 0,
                                "Password" => false
                            ];

                            $errorDuringChange = false;
                            $messageForUser = "";

                            //on regarde si les 2 champs mdp ont été saisi
                            if (!empty($_POST["password"]) && !empty($_POST["password_confirm"])){
                                $password_form = trim($_POST["password"]);
                                $password_confirm_form = trim($_POST["password_confirm"]);

                                //on vérifie que les champs mdp ont la bonne taille
                                if (strlen($password_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($password_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
                                    && strlen($password_confirm_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($password_confirm_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]){
                                    //on vérifie que les deux mots de passes saisis sont identiques
                                    if ($password_form == $password_confirm_form){
                                        $listeInfoUsersAChanger["Password"] = true;
                                    }
                                    else{
                                        //on redirige l'utilisateur vers la page de profil
                                        $messageForUser = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_differents"];
                                    }
                                }
                                else{
                                    $messageForUser = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_tailles_incorrectes"];
                                }
                            }

                            //on regarde si un message pour le user est à transmettre, i.e s'il y a une erreur
                            if ($messageForUser == ""){
                                foreach ($listeInfoUsersAChanger as $fieldName => $changed){
                                    //s'il y a eu une erreur durant le changement d'une information personnelle de l'utilisateur, on arrête
                                    if ($errorDuringChange){
                                        break;
                                    }

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
                                                        $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user login|{$userLoginBeforeChange}->{$login}");
                                                    }
                                                    else{
                                                        $errorDuringChange = true;
                                                        //le login est deja pris, on affiche une erreur à l'utilisateur
                                                        $messageForUser = $VARIABLES_GLOBALES["notif_erreur_login_existant"];
                                                    }
                                                }
                                                else{
                                                    $errorDuringChange = true;

                                                    //on enregistre l'erreur à l'aide du logger
                                                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user login|Erreur:{$resultChangeUserLogin["errorMessage"]}");
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
                                                        $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user mail|{$userMailBeforeChange}->{$mail}");
                                                    }
                                                    else{
                                                        $errorDuringChange = true;

                                                        //le mail est deja pris, on affiche une erreur à l'utilisateur
                                                        $messageForUser = $VARIABLES_GLOBALES["notif_erreur_mail_existant"];
                                                    }
                                                }
                                                else{
                                                    $errorDuringChange = true;

                                                    //on enregistre l'erreur à l'aide du logger
                                                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user mail|Erreur:{$resultChangeUserMail["errorMessage"]}");
                                                }
                                                break;
                                            case "LastName":
                                                $resultChangeUserLastName = $sqlData->change_user_lastname("Users", $userId, $lastName);

                                                //on regarde que la requête s'est exécutée sans erreur
                                                if ($resultChangeUserLastName["error"] == 0){

                                                    //on change l'information dans l'objet user
                                                    $user->setLastName($lastName);

                                                    //on enregistre cet événement à l'aide du logger
                                                    $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user last name|{$userLastNameBeforeChange}->{$lastName}");
                                                }
                                                else{
                                                    $errorDuringChange = true;

                                                    //on enregistre l'erreur à l'aide du logger
                                                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user last name|Erreur:{$resultChangeUserLastName["errorMessage"]}");
                                                }
                                                break;
                                            case "FirstName":
                                                $resultChangeUserFirstName = $sqlData->change_user_firstname("Users", $userId, $firstName);

                                                //on regarde que la requête s'est exécutée sans erreur
                                                if ($resultChangeUserFirstName["error"] == 0){

                                                    //on change l'information dans l'objet user
                                                    $user->setFirstName($firstName);

                                                    //on enregistre cet événement à l'aide du logger
                                                    $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user first name|{$userFirstNameBeforeChange}->{$firstName}");
                                                }
                                                else{
                                                    $errorDuringChange = true;

                                                    //on enregistre l'erreur à l'aide du logger
                                                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user first name|Erreur:{$resultChangeUserFirstName["errorMessage"]}");
                                                }
                                                break;
                                            case "Password":
                                                $resultChangeUserPassword = $sqlData->change_user_password("Users", "Weak_passwords", $userId, $user->getLogin(), $password_form);

                                                //on regarde que la requête s'est exécutée sans erreur
                                                if ($resultChangeUserPassword["error"] == 0){

                                                    //on regarde si le changement n'a pas eu lieu car le mdp est identique à l'ancien
                                                    if ($resultChangeUserPassword["result"] == -1){
                                                        $errorDuringChange = true;
                                                        $messageForUser = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_identiques"];
                                                    }

                                                    //on regarde si le changement n'a pas eu lieu car le mdp est trop fragile
                                                    elseif ($resultChangeUserPassword["result"] == -2){
                                                        $errorDuringChange = true;
                                                        $messageForUser = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_fragile"];
                                                    }

                                                    else{
                                                        //on enregistre cet événement à l'aide du logger
                                                        $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changement user password");
                                                    }
                                                }
                                                else{
                                                    $errorDuringChange = true;

                                                    //on enregistre l'erreur à l'aide du logger
                                                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Changement user password|Erreur:{$resultChangeUserPassword["errorMessage"]}");
                                                }
                                                break;
                                        }
                                    }
                                }

                                //on ferme la connexion au serveur MySQL
                                $sqlData->close_connexion_to_db();

                                //on remet l'objet user avec ou non les nouvelles info
                                $_SESSION["user"] = serialize($user);

                                //on renvoit un message pour l'utilisateur en fonction de s'il y a eu une erreur ou non
                                if ($errorDuringChange){
                                    if ($messageForUser == "")
                                        $messageForUser = $_SESSION["notif_erreur_interne"];

                                    //on enregistre l'erreur
                                    $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changements profil user annulés|Message:{$messageForUser}");
                                }
                                else{
                                    $messageForUser = $VARIABLES_GLOBALES["notif_changements_reussis"];
                                    $_SESSION["message_positif"] = true;
                                }

                                $_SESSION["notif_page_user"] = $messageForUser;

                                header("Location:page_accueil_user.php");
                            }
                            else{
                                $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changements profil user annulés|Message:{$messageForUser}");

                                $_SESSION["notif_page_user"] = $messageForUser;
                                header("Location:page_accueil_user.php");
                            }
                        }
                        else{
                            //on affiche une erreur à l'utilisateur
                            $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$sqlData->getConnectionErreurMessage()}");
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_accueil_user.php");
                        }
                    }
                    else{
                        //on enregistre cette info
                        $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Changements profil user annulés, mauvaise longueur de certains champs|Login:{$login}|Mail:{$mail}|Lastname:{$lastName}|Firstname{$firstName}");

                        $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                        header("Location:page_inscription.php");
                    }
                }
            }
            else{
                //on affiche une erreur à l'utilisateur
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_accueil_user.php");
            }
        }
        else{
            //echo "Champs incorrects";
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
            header("Location:page_accueil_user.php");
        }
    }

    elseif (!empty($_POST["submit_supprimer_compte"])){
        //l'utilisateur veut supprimer son compte

        //on récupère l'ID du user
        $user = unserialize($_SESSION["user"]);
        $userId = $user->getId();

        //on récupère les 2 loggers instances pour enregistrer des événements
        $logger = unserialize($_SESSION["logger"]);
        $loggerBd = $logger->getLoggerInstance("loggerDb");
        $loggerFile = $logger->getLoggerInstance("loggerFile");

        //on se reconnecte à la bd
        $loggerBd->getMySqlConnector()->reconnect_to_bd();

        //on vérifie que le logger s'est connecté au serveur
        if ($loggerBd->getMySqlConnector()->getConnectionErreur() == 1){
            //on enregistre l'erreur dans le loggerFile
            $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur:{$loggerBd->getMySqlConnector()->getConnectionErreurMessage()}");
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
            header("Location:page_accueil_user.php");
        }
        else{
            //on se connecte à la bd
            $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

            //on vérifie qu'il n'y a aucune erreur
            if ($sqlData->getConnectionErreur() == 0) {
                //on exécute une requête pour supprimer le compte
                $resultDeleteUserAccount = $sqlData->supprimer_user("Users", $userId);

                if ($resultDeleteUserAccount["error"] == 0){
                    //la suppression du compte a été effectuée, on enregistre cette action
                    $loggerBd->info($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Suppression du compte");

                    //on le redirige le user vers la page de déconnexion
                    header("Location:page_deconnexion.php");
                }
                else{
                    //on affiche une erreur à l'utilisateur
                    $loggerBd->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne lors de la suppression de son compte|Erreur:{$resultDeleteUserAccount["errorMessage"]}");
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                    header("Location:page_accueil_user.php");
                }
            }
            else{
                //on affiche une erreur à l'utilisateur
                $loggerFile->error($userId, getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Erreur:{$sqlData->getConnectionErreurMessage()}");
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                header("Location:page_accueil_user.php");
            }
        }
    }

    else{
        header("Location:page_accueil_user.php");
    }
}
else{
    //acces au script non autorise, on redirige vers la page d'accueil
    header("Location:../index.html");
}
?>