<?php
namespace PHP;

include_once "Utility.php";

include_once "Enum_fic_logs.php";
include_once "MySQLDataManagement.php";

require_once "verif_identite_page_user.php";

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

//on regarde quel formulaire a été saisi
if (!empty($_POST["submit_change_mail"])){
    if (!empty($_POST["new_mail"])) {
        //on enlève les caractères spéciaux du nom
        $new_mail_form = $_POST["new_mail"];
        $new_mail = deleteSpecialCharacters($new_mail_form, $VARIABLES_GLOBALES["listSpecialCharactersMail"]);

        //on vérifie que le champ ne contient pas de caracteres speciaux
        if (strlen($new_mail) == strlen($new_mail_form)){
            $user = unserialize($_SESSION["user"]);
            $userId = $user->getId();

            //$logger = unserialize($_SESSION["logger"]);

            //on vérifie la validité des champs
            if (strlen($new_mail) >= $VARIABLES_GLOBALES["taille_champ_mail"][0] && strlen($new_mail) <= $VARIABLES_GLOBALES["taille_champ_mail"][1]) {
                //on vérifie que le nouveau nom est différent de l'ancien
                if ($new_mail != $user->getUserMail()) {
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"]);

                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0){
                        //on change dans la bd et dans l'objet user le mail
                        $resultChangeUserLogin = $sqlData->change_user_mail("Users", $userId, $new_mail);

                        //on regarde si le changement a eu lieu
                        if ($resultChangeUserLogin == 1){
                            $ancien_mail = $user->getUserMail();
                            $user->setUserMail($new_mail);
                            $_SESSION["user"] = serialize($user);

                            $sqlData->close_connexion_to_db();

                            //on enregistre la modifications dans un fichier log
                            //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Changement adresse mail, Ancien mail : $ancien_mail || Nouveau login : $new_mail", $user->getId(), getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

                            //on redirige l'utilisateur vers la page de profil
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changement_lastname_reussi"];
                            $_SESSION["message_positif"] = true;
                            header("Location:page_profil.php");
                        }
                        elseif($resultChangeUserLogin == -2){
                            $sqlData->close_connexion_to_db();
                            //l'adresse mail existe déjà, on affiche une erreur au user
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_mail_existant"];
                            header("Location:page_profil.php");
                        }
                        else{
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur à l'utilisateur
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_profil.php");
                        }
                    }
                    else{
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_profil.php");
                    }

                } else {
                    //echo "Les deux noms sont identiques";
                    //on redirige l'utilisateur vers la page de profil
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_mail_identiques"];
                    header("Location:page_profil.php");
                }
            } else {
                //echo "Champs incorrects";
                //on redirige l'utilisateur vers la page de profil
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_profil.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
            header("Location:page_profil.php");
        }
    }
    else{
        //echo "Champs vides";
        //on redirige l'utilisateur vers la page de profil
        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_profil.php");
    }
}
elseif(!empty($_POST["submit_change_login"])){
    if (!empty($_POST["new_login"])) {
        //on enlève les espaces au début et à la fin ainsi que les caracteres speciaux
        $new_login_form = trim($_POST["new_login"]);
        $new_login = deleteSpecialCharacters($new_login_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

        //on vérifie que le champ ne contient pas de caracteres speciaux
        if (strlen($new_login) == strlen($new_login_form)){
            $user = unserialize($_SESSION["user"]);
            $userId = $user->getId();
            $logger = unserialize($_SESSION["logger"]);

            //on vérifie la validité des champs
            if (strlen($new_login) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($new_login) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]) {
                //on vérifie que le nouveau nom est différent de l'ancien
                if ($new_login != $user->getLogin()) {
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"], $logger);

                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0){
                        //on change dans la bd et dans l'objet user le nom
                        $resultChangeUserLastName = $sqlData->change_user_login("Users", $userId, $new_login);

                        //on affiche continue si la modification a bien eu lieu
                        if ($resultChangeUserLastName == 1){
                            $ancienLogin = $user->getLogin();
                            $user->setLogin($new_login);
                            $_SESSION["user"] = serialize($user);

                            $sqlData->close_connexion_to_db();

                            //on enregistre la modifications dans un fichier log
                            //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Changement du login, Ancien login : $ancienLogin || Nouveau login : $new_login", $userId, getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

                            //on redirige l'utilisateur vers la page de profil
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changement_lastname_reussi"];
                            $_SESSION["message_positif"] = true;
                            header("Location:page_profil.php");
                        }
                        elseif ($resultChangeUserLastName == -2){
                            $sqlData->close_connexion_to_db();
                            //le login est déjà pris, on affiche donc une erreur au user
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_login_existant"];
                            header("Location:page_profil.php");
                        }
                        else{
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_profil.php");
                        }
                    }
                    else{
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_profil.php");
                    }

                } else {
                    //echo "Les deux login sont identiques";
                    //on redirige l'utilisateur vers la page de profil
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_login_identiques"];
                    header("Location:page_profil.php");
                }
            } else {
                //echo "Champs incorrects";
                //on redirige l'utilisateur vers la page de profil
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_profil.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
            header("Location:page_profil.php");
        }
    }
    else{
        //echo "Champs vides";
        //on redirige l'utilisateur vers la page de profil
        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_profil.php");
    }
}
elseif (!empty($_POST["submit_change_password"])){
    //on vérifie que tous les champs ont été saisis
    if (!empty($_POST["new_password"]) && !empty($_POST["new_password_confirm"])){
        $new_password_form = trim($_POST["new_password"]) ;
        $new_password_confirm_form = trim($_POST["new_password_confirm"]);
        $user = unserialize($_SESSION["user"]);
        $userId = $user->getId();

        //print_r($_SESSION);
        $logger = unserialize($_SESSION["logger"]);

        //on vérifie la validité des champs
        if (strlen($new_password_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($new_password_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
            && strlen($new_password_confirm_form) >= $VARIABLES_GLOBALES["taille_champ_mdp"][0] && strlen($new_password_confirm_form) <= $VARIABLES_GLOBALES["taille_champ_mdp"][1]
        ){
            //on vérifie que les deux mots de passe sont identiques
            if ($new_password_form == $new_password_confirm_form){

                //on se connecte à la bd
                $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"],$VARIABLES_GLOBALES["bd_username"],$VARIABLES_GLOBALES["bd_password"],$VARIABLES_GLOBALES["bd_database"], $logger);
                //on vérifie qu'il n'y a aucune erreur
                if ($sqlData->getConnectionErreur() == 0) {

                    //on vérifie que le nouveau mot de passe est différent de l'ancien
                    $resultVerifPassword = $sqlData->verif_password("Users", $userId, $new_password_form);
                    if (gettype($resultVerifPassword) == "boolean"){
                        if ($resultVerifPassword == false){

                            //enfin, on regarde si le mot de passe est suffisamment robuste
                            $resultVerifSoliditePassword = $sqlData->verif_solidite_password("Weak_passwords", $new_password_form);
                            //echo gettype($resultVerifPassword);
                            if (gettype($resultVerifSoliditePassword) == "boolean"){
                                if ($resultVerifSoliditePassword){
                                    //on change le mot de passe dans la base de données
                                    //echo "Changement du mot de passe";
                                    $sqlData->change_user_password("Users", $userId, hash_password($new_password_form));
                                    $sqlData->close_connexion_to_db();

                                    //on enregistre l'action dans un fichier log
                                    //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Changement mot de passe", $userId, getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

                                    //on redirige l'utilisateur vers la page de profil
                                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changement_mdp_reussi"];
                                    $_SESSION["message_positif"] = true;
                                    header("Location:page_profil.php");
                                }
                                else{
                                    $sqlData->close_connexion_to_db();
                                    //echo "Le mot de passe entré est trop facile à deviner";
                                    //on redirige l'utilisateur vers la page de profil
                                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_fragile"];
                                    header("Location:page_profil.php");
                                }
                            }
                            else{
                                $sqlData->close_connexion_to_db();
                                //on affiche une erreur à l'utilisateur
                                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                                header("Location:page_profil.php");
                            }
                        }
                        else{
                            $sqlData->close_connexion_to_db();
                            //echo "Le nouveau mot de passe est identique à l'ancien";
                            //on redirige l'utilisateur vers la page de profil
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_mdp_identiques"];
                            header("Location:page_profil.php");
                        }
                    }
                    else{
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_profil.php");
                    }
                }
                else{
                    //on affiche une erreur à l'utilisateur
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                    header("Location:page_profil.php");
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
            //echo "Champs incorrects";
            //on redirige l'utilisateur vers la page de profil
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
            header("Location:page_profil.php");
        }
    }
    else{
        //echo "Champs vides";
        //on redirige l'utilisateur vers la page de profil
        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_profil.php");
    }
}
elseif (!empty($_POST["submit_change_lastname"])){
    if (!empty($_POST["new_lastname"])) {
        //on enlève les espaces au début et à la fin ainsi que les caracteres speciaux
        $new_lastname_form = trim($_POST["new_lastname"]);
        $new_lastname = deleteSpecialCharacters($new_lastname_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

        //on vérifie que le champ ne contient pas de caracteres speciaux
        if (strlen($new_lastname) == strlen($new_lastname_form)){
            $user = unserialize($_SESSION["user"]);
            $userId = $user->getId();
            $logger = unserialize($_SESSION["logger"]);

            //on vérifie la validité des champs
            if (strlen($new_lastname) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($new_lastname) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]) {
                //on vérifie que le nouveau nom est différent de l'ancien
                if ($new_lastname != $user->getLastName()) {
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"], $logger);

                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0){
                        //on change dans la bd et dans l'objet user le nom
                        $resultChangeUserLastName = $sqlData->change_user_lastname("Users", $userId, $new_lastname);
                        if ($resultChangeUserLastName == 1){
                            $ancien_lastName = $user->getFirstName();
                            $user->setLastName($new_lastname);
                            $_SESSION["user"] = serialize($user);

                            $sqlData->close_connexion_to_db();

                            //on enregistre la modifications dans un fichier log
                            //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Changement du nom, Ancien nom : $ancien_lastName || Nouveau nom : $new_lastname", $userId, getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

                            //on redirige l'utilisateur vers la page de profil
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changement_lastname_reussi"];
                            $_SESSION["message_positif"] = true;
                            header("Location:page_profil.php");
                        }
                        else{
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur à l'utilisateur
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_profil.php");
                        }
                    }
                    else{
                        $sqlData->close_connexion_to_db();
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_profil.php");
                    }

                } else {
                    //echo "Les deux noms sont identiques";
                    //on redirige l'utilisateur vers la page de profil
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_lastname_identiques"];
                    header("Location:page_profil.php");
                }
            } else {
                //echo "Champs incorrects";
                //on redirige l'utilisateur vers la page de profil
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_profil.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
            header("Location:page_profil.php");
        }
    }
    else{
        //echo "Champs vides";
        //on redirige l'utilisateur vers la page de profil
        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_profil.php");
    }
}
elseif (!empty($_POST["submit_change_firstname"])){
    if (!empty($_POST["new_firstname"])){
        //on enlève les espaces au début et à la fin ainsi que les caracteres speciaux
        $new_firstname_form = trim($_POST["new_firstname"]);
        $new_firstname = deleteSpecialCharacters($new_firstname_form, $VARIABLES_GLOBALES["listSpecialCharactersText"]);

        //on vérifie que le champ ne contient pas de caracteres speciaux
        if (strlen($new_firstname) == strlen($new_firstname_form)){
            $user = unserialize($_SESSION["user"]);
            $userId = $user->getId();

            $logger = unserialize($_SESSION["logger"]);

            //on vérifie la validité des champs
            if (strlen($new_firstname) >= $VARIABLES_GLOBALES["taille_champ_texte"][0] && strlen($new_firstname) <= $VARIABLES_GLOBALES["taille_champ_texte"][1]) {
                //on vérifie que le nouveau prenom est différent de l'ancien
                if ($new_firstname != $user->getFirstName()) {
                    //on se connecte à la bd
                    $sqlData = new MySQLDataManagement($VARIABLES_GLOBALES["bd_hostname"], $VARIABLES_GLOBALES["bd_username"], $VARIABLES_GLOBALES["bd_password"], $VARIABLES_GLOBALES["bd_database"], $logger);
                    //on vérifie qu'il n'y a aucune erreur
                    if ($sqlData->getConnectionErreur() == 0){
                        $resultChangeUserFirstName = $sqlData->change_user_firstname("Users", $userId, $new_firstname);
                        if ($resultChangeUserFirstName == 1){
                            $ancien_firstName = $user->getFirstName();
                            $user->setFirstName($new_firstname);
                            $_SESSION["user"] = serialize($user);

                            $sqlData->close_connexion_to_db();

                            //on enregistre la modifications dans un fichier log
                            //enregistrement_actions_dans_logs([$_SERVER['REMOTE_ADDR'], "Changement du prénom, Ancien prénom : $ancien_firstName || Nouveau prénom : $new_firstname", $userId, getTodayDate()->format("Y-m-d H:i:s")], Enum_fic_logs::REPO_LOGS_USERS_ACTIONS, $VARIABLES_GLOBALES);

                            //on redirige l'utilisateur vers la page de profil
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_changement_firstname_reussi"];
                            $_SESSION["message_positif"] = true;
                            header("Location:page_profil.php");
                        }
                        else{
                            $sqlData->close_connexion_to_db();
                            //on affiche une erreur à l'utilisateur
                            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                            header("Location:page_profil.php");
                        }
                    }
                    else{
                        $sqlData->close_connexion_to_db();
                        //on affiche une erreur à l'utilisateur
                        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_interne"];
                        header("Location:page_profil.php");
                    }
                } else {
                    //echo "Les deux prénoms sont identiques";
                    //on redirige l'utilisateur vers la page de profil
                    $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_firstname_identiques"];
                    header("Location:page_profil.php");
                }
            } else {
                //echo "Champs incorrects";
                //on redirige l'utilisateur vers la page de profil
                $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_incorrects"];
                header("Location:page_profil.php");
            }
        }
        else{
            //on affiche une erreur à l'utilisateur
            $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_champ_avec_caracteres_speciaux"];
            header("Location:page_profil.php");
        }
    }
    else{
        //echo "Champs vides";
        //on redirige l'utilisateur vers la page de profil
        $_SESSION["notif_page_user"] = $VARIABLES_GLOBALES["notif_erreur_champs_vides"];
        header("Location:page_profil.php");
    }
}
else{
    header("Location:page_profil.php");
}
?>