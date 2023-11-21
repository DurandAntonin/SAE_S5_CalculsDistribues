<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">  
</head>
<body class="bg-lightblue" style="font-family: 'Poppins', sans-serif;">

<header class="top-0 w-full shadow-md bg-lightblue">
        <nav class="flex justify-center items-center w-auto  mx-auto">
            <div class="container mx-auto flex items-center justify-center">
              <div >
                <a href="../index.html"><img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" alt="Logo" class="h-20"></a>
              </div>
        </nav>
    </header>


<div class="h-screen flex items-center justify-center">

    <form method="post" action="traitement_connexion.php" class="w-full md:w-1/3 bg-deepblue rounded-3xl items-center">
        <h2 class="text-3xl text-center text-white my-8">
            Se connecter
        </h2>
        <div class="px-12 pb-10">
            <div class="w-full mb-10">
                <div class="flex justify-center">
                    <input type="text" id="login" name="login" min="2" max="25" placeholder="Username"
        class="px-8 w-full border rounded-xl py-2 text-black focus:outline-none items-center"
            /> 
                </div>
            </div>            
        <div class="w-full mb-10">
            <div class="flex justify-center">
                    <input type="password" id="password" name="password"  min="6" max="25" placeholder="Password"
                class="px-8 w-full border rounded-xl py-2 text-black focus:outline-none">
                </div>
            </div>
            <div class="flex justify-center">
                <input type="submit" name="submit_connexion_user" value="Valider" class="w-3/4 mt-6 py-2 rounded-xl animate-pulse bg-lgrey text-white focus:outline-none hover:bg-gray-800 focus:ring-4 focus:ring-gray-300">
            </div>
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

                <a href="page_inscription.php" class="text-sm text-opacity-200 float-right mt-6 mb-4 text-white hover:underline"> Pas encore inscrit ? </a> 
                <a href="../index.html" class="text-sm text-opacity-200 float-left mt-6 mb-8 text-white hover:underline"> Mot de passe oublié ??</a> 
    </form>  
    </div>

</body>
</html>