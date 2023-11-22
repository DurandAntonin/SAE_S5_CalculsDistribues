<?php

namespace PHP;


include_once "Logger.php";
include_once "LoggerInstance.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";

//on controle l'acces a cette page
require_once "verif_identite_page_user.php";

$user = unserialize($_SESSION["user"]);


//echo $user->str();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlitzCalc</title>
    <link href="../CSS/style.css" rel="stylesheet">
</head>
<body>

<h1> Page d'accueil </h1>

<nav>
    <ul>
        <?php
        //seul le user inscrit peut voir son profil
        if ($user->getRole() == Enum_role_user::USER){
            echo "<li> <a href='page_profil.php'> Voir son profil </a> </li>";
        }
        ?>
        <li> <a href="page_deconnexion.php"> Se déconnecter </a> </li>
    </ul>
</nav>

<main>
    <h2> Bienvenue <?php echo $user->getLogin() ?> ! </h2>

</main>

</body>
</html>