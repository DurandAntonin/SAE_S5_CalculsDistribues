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
    <title>Inscription</title>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="../PICTURES/blitzcalc-favicon-color.png"/>
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
          <h2 class="text-3xl text-center text-white my-8">S'inscrire</h2>
          <form class="mb-4 rounded  px-8 pb-8 pt-6" method="post" action="traitement_inscription.php">
            <div class="mb-4">
                <label class="mb-2 block text-sm font-bold text-white" for="login"> Identifiant </label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="login" name="login" placeholder="Identifiant" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
            </div>
            <div class="mb-4 md:flex md:justify-between">
            
              <div class="mb-4 md:mb-0 md:mr-2">
                <label class="mb-2 block text-sm font-bold text-white" for="first_name"> Prénom </label>
                <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="first_name" name="first_name" type="text" placeholder="Prénom" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
              </div>
              <div class="md:ml-2">
                <label class="mb-2 block text-sm font-bold text-white" for="last_name"> Nom </label>
                <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="last_name" name="last_name" type="text" placeholder="Nom" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
              </div>
            </div>
            <div class="mb-4">
              <label class="mb-2 block text-sm font-bold text-white" for="email"> Email </label>
              <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="email" type="email" name="email" placeholder="Email" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mail"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mail"][1];?>" required/>
            </div>
            <div class="mb-4 md:flex md:justify-between">
              <div class="mb-4 md:mb-0 md:mr-2">
                <label class="mb-2 block text-sm font-bold text-white" for="password"> Mot de passe </label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border border-red-500 px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password" name="password" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>" required/>
                <p class="text-xs italic text-red-500">Choisissez un mot de passe.</p>
              </div>
              <div class="md:ml-2">
                <label class="mb-2 block text-sm font-bold text-white" for="password_confirm"> Confirmez le mot de passe</label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password_confirm" name="password_confirm" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>" required/>
              </div>
            </div>
            <div class="mb-6 text-center">
              <input type="submit" name="submit_inscription" value="Valider" class="w-3/4 mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
            </div>
            <div class="flex justify-center mb-6 ">
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
            </div>

            <hr class="mb-6 border-t" />
            <div class="text-center">
              <a class="inline-block align-baseline text-sm text-opacity-200 text-white hover:underline" href="page_connexion.php"> Déja un compte? Connectez vous! </a>
                </div>
            </form>
            </div>
        </div>
     </div>
    </div>
</div>
</body>
</html>