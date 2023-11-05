<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="../CSS/style.css" rel="stylesheet">
</head>
<body>

<h1> BlitzCalc </h1>

<main>
    <h2> Inscription </h2>

    <form method="post" action="traitement_inscription.php">
        <div>
            <label for="email">Adresse mail</label>
            <input type="email" id="email" name="email" min="6" max="35">
        </div>

        <div>
            <label for="login">Login</label>
            <input type="text" id="login" name="login" min="6" max="25">
        </div>

        <div>
            <label for="last_name">Nom</label>
            <input type="text" id="last_name" name="last_name" min="2" max="25">
        </div>

        <div>
            <label for="first_name">Prénom</label>
            <input type="text" id="first_name" name="first_name" min="2" max="25">
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password"  min="6" max="25">
        </div>

        <div>
            <label for="password_confirm">Confirmez votre mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm"  min="6" max="25">
        </div>

        <input type="submit" name="submit_inscription" value="Submit">
    </form>

    <p id="erreur_message">
        <?php
        //on affiche ou non le message renvoye par la page traitement
        $message = null;
        $style = "'color: #EB3939'";

        if (!empty($_SESSION["erreur_traitement_inscription"])){
            $message = $_SESSION["erreur_traitement_inscription"];

            //on supprime la variable dans la session
            unset($_SESSION["erreur_traitement_inscription"]);
        }
        if ($message != null)
            echo "<b style=$style>$message </b>";
        ?>
    </p>

    <nav>
        <ul>
            <li> Déjà inscrit ? <a href="page_connexion.php"> Se connecter </a> </li>
            <li> Retourner à la page d'accueil ? <a href="../index.html"> Cliquez ici </a> </li>
        </ul>
    </nav>
</main>

</body>
</html>