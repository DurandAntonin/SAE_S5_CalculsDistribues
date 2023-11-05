<?php
//on controle l'acces a cette page, seul le user inscrit a le droit d'y accéder
require_once "verif_identite_page_user_inscrit_only.php";

$user = unserialize($_SESSION["user"]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlitzCalc</title>
    <link href="../CSS/style.css" rel="stylesheet">
</head>
<body>

<main id="main_page_profil">
    <h1 class="title_lvl1"> Profile </h1>

    <div id="div_info_user">
        <h2 class="title_lvl2">Vos informations</h2>

        <p> <?php echo "Login : <b>" . $user->getLogin() . "</b>" ?> </p>
        <p> <?php echo "Adresse mail : <b>" . $user->getUserMail() . "</b>" ?> </p>
        <p> <?php echo "Nom : <b>" . $user->getLastName() . "</b>" ?> </p>
        <p> <?php echo "Prénom : <b>" . $user->getFirstName() . "</b>" ?> </p>

    </div>

    <div id="div_forms">

        <div id="div_change_mail">
            <h2 class="title_lvl2"> Changer votre adresse mail </h2>

            <form method="post" action="traitement_profil.php" class="form">

                <div class="div_form_label_input">
                    <label for="new_mail">Nouvelle adresse mail</label>
                    <input type="email" id="new_mail" name="new_mail" min="6" max="35">
                </div>

                <input type="submit" name="submit_change_mail" value="Confirm" class="form_input_submit">

            </form>
        </div>

        <div id="div_change_last_name">
            <h2 class="title_lvl2"> Changer votre login </h2>

            <form method="post" action="traitement_profil.php" class="form">

                <div class="div_form_label_input">
                    <label for="new_login">Nouveau login</label>
                    <input type="text" id="new_login" name="new_login" min="2" max="25">
                </div>

                <input type="submit" name="submit_change_login" value="Confirm" class="form_input_submit">

            </form>
        </div>

        <div id="div_change_password">
            <h2 class="title_lvl2"> Changer son mot de passe</h2>

            <form method="post" action="traitement_profil.php" class="form">

                <div class="div_form_label_input">
                    <label for="new_password">Nouveau mot de passe</label>
                    <input type="password" id="new_password" name="new_password" min="6" max="25">
                </div>

                <div class="div_form_label_input">
                    <label for="new_password_confirm">Confirmez votre nouveau mot de passe</label>
                    <input type="password" id="new_password_confirm" name="new_password_confirm" min="6" max="25">
                </div>

                <input type="submit" name="submit_change_password" value="Confirm" class="form_input_submit">

            </form>
        </div>


        <div id="div_change_last_name">
            <h2 class="title_lvl2"> Changer votre nom </h2>

            <form method="post" action="traitement_profil.php" class="form">

                <div class="div_form_label_input">
                    <label for="new_lastname">Nouveau nom</label>
                    <input type="text" id="new_lastname" name="new_lastname" min="2" max="25">
                </div>

                <input type="submit" name="submit_change_lastname" value="Confirm" class="form_input_submit">

            </form>
        </div>

        <div id="div_change_first_name">
            <h2 class="title_lvl2"> Changer votre prénom</h2>

            <form method="post" action="traitement_profil.php" class="form">

                <div class="div_form_label_input">
                        <label for="new_firstname">Nouveau prénom</label>
                    <input type="text" id="new_firstname" name="new_firstname" min="2" max="25">
                </div>

                <input type="submit" name="submit_change_firstname" value="Confirm" class="form_input_submit">

            </form>
        </div>
    </div>

    <p id="erreur_message">
        <?php
        //on affiche ou non le message renvoye par la page traitement
        $message = null;
        $style = "'color: #EB3939'";

        if (!empty($_SESSION["notif_page_user"])){
            $message = $_SESSION["notif_page_user"];

            //on regarde si le message est un message positif ou negatif
            if (isset($_SESSION["message_positif"]) && $_SESSION["message_positif"]){
                $style = "'color:#4FBB6B'";
                unset($_SESSION["message_positif"]);
            }

            //on supprime la variable dans la session
            unset($_SESSION["notif_page_user"]);
        }
        if ($message != null)
            echo "<b style=$style>$message </b>";
        ?>
    </p>

    <a href="page_accueil_user.php" class="link"> Retourner à la page principale </a>
</main>


</body>
</html>