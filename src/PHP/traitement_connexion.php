<?php

namespace PHP;


include_once "Utility.php";
include_once "Enum_fic_logs.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";
include_once "Logger.php";
include_once "LoggerInstance.php";

session_start();

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

//on regarde si une requete post a été envoyé
if (isset($_POST)) {
    //on crée un objet logger en fonction de la configuration enregistrée
    $logger = new Logger($VARIABLES_GLOBALES["loggerConf"]);
    $loggerBd = $logger->getLoggerInstance("loggerDb");
    $loggerFile = $logger->getLoggerInstance("loggerFile");

    //on regarde si le user a rempli le formulaire de connexion
    if (!empty($_POST["submit_connexion_user"])) {

        //on vérifie que tous les champs du formulaires de connexion ont été saisis
        if (!empty($_POST["login"]) && !empty($_POST["password"])) {
            //on supprime les espaces au début et a la fin de la chaine
            $login_form = trim($_POST["login"]);
            $password_form = trim($_POST["password"]);
            //echo "Info saisies : $login_form || $password_form <br><br>";

            //on supprime les caractères spéciaux du champ login
            $login = deleteSpecialCharacters($login_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

            //on continue s'il n'y a pas de caracteres speciaux
            if (strlen($login) == strlen($login)) {

                //on vérifie que les champs sont valides
                if (strlen($login) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($login) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                    && strlen($password_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($password_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
                ) {
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0) {
                        $resultGetLogin = $sqlData->get_user_by_login("Users", $login);

                        //on regarde si la requete s'est exécutée sans erreur
                        if ($resultGetLogin["error"] != 1) {
                            //on vérifie si un utilisateur avec ce login existe
                            if (count($resultGetLogin["result"]) == 1) {

                                //on regarde maintenant si le mot de passe est celui associé à ce login
                                $resultVerifPassword = $sqlData->verif_password("Users", $login, $password_form);
                                if ($resultVerifPassword["error"] == 0) {
                                    if ($resultVerifPassword["result"] == true) {
                                        //le user existe, on demarre une session et on le redirige en fonction de son role
                                        //et on écrit dans un fichier de log la connexion
                                        $user = $resultGetLogin["result"][0];

                                        $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Connexion user {$user->getRole()->name}");
                                        $sqlData->close_connexion_to_db();

                                        //on stocke le logger dans la session
                                        $_SESSION["logger"] = serialize($logger);

                                        //on serialize l'objet pour pouvoir le passer dans la session
                                        $_SESSION["user"] = serialize($user);
                                        switch ($user->getRole()) {
                                            case Enum_role_user::USER:
                                                //echo "Redirection page USER";
                                                header("Location:page_accueil_user.php");
                                                break;
                                            case Enum_role_user::ADMIN:
                                                //echo "Redirection page ADMIN";
                                                header("Location:page_accueil_admin.php");
                                                break;
                                            default :
                                                //role inconnu, on le redirige vers la page de connexion
                                                //echo "Role inconnu";
                                                $loggerFile->warning($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Role inconnu lors d'une tentative de connexion|User:{$user}");
                                                header("Location:page_connexion.php");
                                        }
                                    } else {
                                        //le couple login/mpd entré par le user n'existe pas
                                        //echo "Mot de passe incorrect";
                                        $loggerFile->info("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Couple (login,mdp) inconnu dans la base de données|Login:{$login};Mot de passe:{$password_form}");

                                        $sqlData->close_connexion_to_db();
                                        $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_compte_introuvable"];
                                        header("Location:page_connexion.php");
                                    }

                                } else {
                                    $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$resultVerifPassword["errorMessage"]}");
                                    $sqlData->close_connexion_to_db();
                                    //echo $resultVerifPassword;
                                    //on affiche une erreur à l'utilisateur
                                    $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                                    header("Location:page_connexion.php");
                                }
                            } else {
                                //le user n'existe pas, on le renvoie à la page de connexion
                                //echo "Login introuvable";
                                $loggerFile->info("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Login inconnu|Login:{$login};Mot de passe:{$password_form}");
                                $sqlData->close_connexion_to_db();

                                $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_compte_introuvable"];
                                header("Location:page_connexion.php");
                            }
                        } else {
                            $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$resultGetLogin["errorMessage"]}");
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur à l'utilisateur
                            $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_connexion.php");
                        }
                    } else {
                        //on affiche une erreur à l'utilisateur
                        $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$sqlData->getConnectionErreurMessage()}");
                        $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_connexion.php");
                    }
                }
                else{
                    //on affiche une erreur à l'utilisateur
                    $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                    header("Location:page_connexion.php");
                }
            } else {
                //on affiche une erreur à l'utilisateur
                $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
                header("Location:page_connexion.php");
            }
        } else {
            //echo "Champs incorrects";
            $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
            header("Location:page_connexion.php");
        }
    } else if (!empty($_POST["submit_connexion_visiteur"])) {
        //le user n'est pas inscrit sur le site, on créé un objet user avec le role visiteur, et on le redirige sur la page d'accueil du user
        $user = User::defaultUser();

        //on enregistre dans un fichier de log la connexion d'un visiteur
        $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Connexion user {$user->getRole()->name}");

        //on stocke le logger dans la session
        $_SESSION["logger"] = serialize($logger);

        //on serialize l'objet pour pouvoir le passer dans la session
        $_SESSION["user"] = serialize($user);
        //print_r($_SESSION);

        header("Location:page_accueil_user.php");
    }
    else{
        //echo "Champs incorrects";
        $_SESSION["erreur_traitement_connexion"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_connexion.php");
    }
}

else {
    //formulaire inconnu, on renvoit le user à la page de connexion
    header("Location:page_connexion.php");
}

?>