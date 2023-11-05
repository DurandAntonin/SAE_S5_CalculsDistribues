<?php
//on controle l'acces a cette page
require_once "verif_identite_page_admin.php";

$user = unserialize($_SESSION["user"]);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlitzCalc</title>

    <link href="../CSS/style_page_admin.css" rel="stylesheet">
    <script src="../JS/action_page_admin.js"></script>
</head>
<body>

<h1> Page d'accueil de l'admin </h1>

<nav>
    <ul>
        <li> <a href="page_deconnexion.php"> Se déconnecter </a> </li>
    </ul>
</nav>

<main>
    <h2> Welcome <?php echo $user->getLastName() . " " . $user->getFirstName()  ?> ! </h2>

    <div id="contenu_page_admin">

        <div class="div_recherche">

            <div class="div_form_recherche">
                <label for="input_recherche_user">Chercher un utilisateur par leur adresse mail</label>
                <div>
                    <input type="text" id="input_recherche_user" placeholder="Chercher les utilisateurs">
                    <input type="submit" id="submitRechercheUser" name="submitRechercheUser" value="Rechercher">
                </div>
            </div>

            <div id="div_resultat_recherche_user">

                <!--<div id="div_select_filtre_affichage_recherche_user">
                    <label for="select_filtre_affichage_recherche_user">Display settings</label>
                    <select id="select_filtre_affichage_recherche_user">
                        <option></option>
                    </select>
                </div>-->

                <div id="div_result">
                    <ul id="ul_users_recherches"></ul>

                    <div id="div_navigation_pagination_recherche_user">
                        <button id="button_first_page" class="button_nav" onclick="buttonSwitchPageUsersSearch(0)">
                            <img src="../PICTURES/angle_first_left.svg" width="25px" height="25px" alt="Left fist angle navigation">
                        </button>

                        <button id="button_previous_page" class="button_nav" onclick="buttonSwitchPageUsersSearch(1)">
                            <img src="../PICTURES/angle-left.svg" width="25px" height="25px" alt="Left angle navigation">
                        </button>

                        <button class="button_nav" id="p_indice_pagination_recherche_users"></button>

                        <button id="button_next_page" class="button_nav" onclick="buttonSwitchPageUsersSearch(2)">
                            <img src="../PICTURES/angle-right.svg" width="25px" height="25px" alt="Left angle navigation">
                        </button>

                        <button id="button_last_page" class="button_nav" onclick="buttonSwitchPageUsersSearch(3)">
                            <img src="../PICTURES/angle_last_right.svg" width="25px" height="25px" alt="Left last angle navigation">
                        </button>
                    </div>

                    <div>

                        <label for="input_indice_page_recherche_user">Aller à la page </label>

                        <input type="text" id="input_indice_page_recherche_user">

                        <input type="submit" value="Go" onclick="goToPage()">

                    </div>
                </div>
            </div>

            <div id="message_pour_user"></div>
        </div>

    </div>

    <div>
        <!-- Rechercher les actions d'un user selon son login, adresse_mail, période d'activité, module choisi -->
    </div>
</main>

</body>
</html>
