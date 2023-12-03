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

//on regarde si le bouton submit du formulaire a été appuyé
if (isset($_POST) && !empty($_POST["submit_inscription"])){
    
    //on vérifie que tous les champs du formulaires de connexion ont été saisis
    if (!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["last_name"]) && !empty($_POST["first_name"]) && !empty($_POST["password"]) && !empty($_POST["password_confirm"])){
        //on supprime les espaces au début et a la fin de la chaine
        $login_form = trim($_POST["login"]);
        $mail_form =  trim($_POST["email"]);
        $lastName_form = trim($_POST["last_name"]);
        $firstName_form = trim($_POST["first_name"]);
        $password_form = trim($_POST["password"]);
        $password_confirm_form = trim($_POST["password_confirm"]);

        //echo "Info saisies : $mail || $lastName || $firstName || $password || $password_confirm<br><br>";

        //on supprime les caractères spéciaux des champs login, nom, prenom, mail
        $login = deleteSpecialCharacters($login_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);
        $mail = deleteSpecialCharacters($mail_form, $VARIABLES_GLOBALES["listSpecialCharactersMail"]);
        $lastName = deleteSpecialCharacters($lastName_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);
        $firstName = deleteSpecialCharacters($firstName_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

        if (strlen($login_form) == strlen($login)
            && strlen($mail) == strlen($mail_form)
            && strlen($lastName_form) == strlen($lastName)
            && strlen($firstName_form) == strlen($firstName)){

            //on vérifie que les champs sont valides
            if (strlen($mail) >= $VARIABLES_GLOBALES["taille_champ_mail"][0] && strlen($mail) <= $VARIABLES_GLOBALES["taille_champ_mail"][1]
                && strlen($login) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($login) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                && strlen($lastName) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($lastName) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                && strlen($firstName) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($firstName) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]
                && strlen($password_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($password_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
                && strlen($password_confirm_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($password_confirm_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
            ){
                //on vérifie que les 2 mdp entrés sont identiques
                if ($password_form == $password_confirm_form){
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

                    //on crée un objet logger en fonction de la configuration enregistrée
                    $logger = new Logger($VARIABLES_GLOBALES["loggerConf"]);
                    $loggerBd = $logger->getLoggerInstance("loggerDb");
                    $loggerFile = $logger->getLoggerInstance("loggerFile");
                    
                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0){
                        //on regarde si le mail et le login saisis sont déjà pris
                        $resultCheckMailLoginTaken = $sqlData->check_mail_login_taken("Users", $mail, $login);
                        //var_dump($resultCheckMailLoginTaken);

                        if ($resultCheckMailLoginTaken["error"] == 0){

                            //on regarde si le login est déjà associé à un compte
                            if ($resultCheckMailLoginTaken["result"] == -1) {
                                $loggerFile->info("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Inscription annulée:login déjà pris|Login:{$login}");
                                $sqlData->close_connexion_to_db();
                                //echo "Compte existant";
                                $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_login_existant"];
                                header("Location:page_inscription.php");
                            }

                            //on regarde si le mail est déjà associé à un compte
                            elseif ($resultCheckMailLoginTaken["result"] == -2){
                                $loggerFile->info("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Inscription annulée:mail déjà pris|Mail:{$mail}");
                                $sqlData->close_connexion_to_db();
                                //echo "Compte existant";
                                $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_mail_existant"];
                                header("Location:page_inscription.php");
                            }

                            else{
                                //le login et le mail ne sont pas pris
                                //on vérifie si le mdp n'est pas présent dans la table des mot de passe fragiles
                                $resultVerifSoliditePasword = $sqlData->verif_solidite_password("Weak_passwords", $password_form);

                                if ($resultVerifSoliditePasword["error"] == 0) {
                                    if ($resultVerifSoliditePasword["result"]){
                                        //on créer le user, on enregistre l'action dans un fichier de log, on l'enregistre dans la bd et on le redirige vers sa page
                                        $uuid = guidv4();
                                        $user = new User($uuid, $mail, $login, $lastName, $firstName, Enum_role_user::USER);

                                        $resultInsertUser = $sqlData->insert_user("Users", $user, hash_password($password_form));
                                        $sqlData->close_connexion_to_db();

                                        if ($resultInsertUser["error"] == 0){
                                            //on démarre une session pour stocker le user
                                            session_start();

                                            $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Inscription utilisateur");
                                            $loggerBd->info($user->getId(), getTodayDate(), $_SERVER['REMOTE_ADDR'], "Connexion user {$user->getRole()->name}");

                                            //on stocke le logger dans la session
                                            $_SESSION["logger"] = serialize($logger);

                                            //on serialize l'objet pour pouvoir le passer dans la session
                                            $_SESSION["user"] = serialize($user);
                                            //print_r($_SESSION);
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
                                        }
                                        else{
                                            $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|User:{$user}|Erreur:{$resultInsertUser["errorMessage"]}");
                                            $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                                            header("Location:page_inscription.php");
                                        }
                                    }
                                    else{
                                        $loggerFile->info("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Inscription annulée:Mot de passe entré trop fragile|Mot de passe:{$password_form}");
                                        $sqlData->close_connexion_to_db();
                                        //echo "Le mot de passe entré est trop facile à deviner";
                                        $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_fragile"];
                                        header("Location:page_inscription.php");
                                    }
                                } else {
                                    $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$resultVerifSoliditePasword["errorMessage"]}");
                                    $sqlData->close_connexion_to_db();
                                    $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                                    header("Location:page_inscription.php");
                                }
                            }
                        }
                        else{
                            $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$resultCheckMailLoginTaken["errorMessage"]}");
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur à l'utilisateur
                            $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_inscription.php");
                        }
                    }
                    else{
                        $loggerFile->error("", getTodayDate(), $_SERVER['REMOTE_ADDR'], "Erreur interne|Login:{$login}|Erreur:{$sqlData->getConnectionErreurMessage()}");
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_inscription.php");
                    }
                }
                else{
                    //echo "Mots de passes non identiques";
                    $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_differents"];
                    header("Location:page_inscription.php");
                }
            }
            else{
                //echo "Champs incorrects";
                $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_inscription.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
            header("Location:page_inscription.php");
        }
    }
    else{
        //echo "Champs vides";
        $_SESSION["erreur_traitement_inscription"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_inscription.php");
    }
}
else{
    header("Location:page_inscription.php");
}


?>