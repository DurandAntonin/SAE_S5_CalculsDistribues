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
</main>

</body>
</html>
