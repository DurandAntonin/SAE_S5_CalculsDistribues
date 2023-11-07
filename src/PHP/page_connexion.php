<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="../CSS/style.css" rel="stylesheet">
</head>
<body>

<h1> BlitzCalc </h1>

<main>
    <h2> Connexion </h2>

    <form method="post" action="traitement_connexion.php">
        <div>
            <label for="login">Login</label>
            <input type="text" id="login" name="login" min="2" max="25">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"  min="6" max="25">
        </div>

        <input type="submit" name="submit_connexion_user" value="Submit">
    </form>

    <form method="post" action="traitement_connexion.php">
        <input type="submit" name="submit_connexion_visiteur" value="Accéder au site gratuitement">
    </form>

    <p id="erreur_message">
        <?php
        //on affiche ou non le message renvoye par la page traitement
        $message = null;
        $style = "'color: #EB3939'";
        if (!empty($_SESSION["erreur_traitement_connexion"])){
            $message = $_SESSION["erreur_traitement_connexion"];

            //on supprime la variable dans la session
            unset($_SESSION["erreur_traitement_connexion"]);
        }
        if ($message != null)
            echo "<b style=$style>$message </b>";
        ?>
    </p>

    <nav>
        <ul>
            <li> Pas encore inscrit ? <a href="page_inscription.php"> Créer un compte </a> </li>
            <li> Retourner à la page d'accueil ? <a href="../index.html"> Cliquez ici </a> </li>
        </ul>
    </nav>
</main>

</body>
</html>