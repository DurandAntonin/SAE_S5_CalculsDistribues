<?php
namespace PHP;

session_start();

include_once "Utility.php";

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();
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
    <div class="container mx-auto">
    <div class="my-12 flex items-center justify-center px-6">
      <!-- Row -->

        <div class="w-full md:w-1/3 bg-deepblue rounded-3xl items-center">
          <h2 class="text-3xl text-center text-white my-8">Se connecter</h2>
          <form class="mb-4 rounded  px-8 pb-8 pt-6" method="post" action="traitement_connexion.php">
            <div class="mb-4">
                <label class="mb-2 block text-sm font-bold text-white" for="login"> Identifiant </label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="login" name="login" placeholder="Identifiant" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
            </div>
            
            <div class="mb-4">
              <label class="mb-2 block text-sm font-bold text-white" for="password"> Mot de passe </label>
              <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password" name="password" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>" required/>
            </div>
            <div class="mb-6 text-center">
              <input type="submit" name="submit_connexion_user" value="Valider" class="w-3/4 mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
            </div>
            <div class="flex justify-center mb-6 ">
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
            </div>
            <hr class="mb-6 border-t" />

                <a href="page_inscription.php" class="text-sm text-opacity-200 float-right mt-6 mb-4 text-white hover:underline"> Pas encore inscrit ? </a> 
                <a href="../index.html" class="text-sm text-opacity-200 float-left mt-6 mb-8 text-white hover:underline"> Mot de passe oublié ??</a> 
    </form>  
    </div>
    </div>
     </div>
    </div>
</div>

</body>
</html>