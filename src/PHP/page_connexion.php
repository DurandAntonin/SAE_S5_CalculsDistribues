<?php
namespace PHP;

session_start();

include_once "Utility.php";
include_once "User.php";
include_once "Enum_role_user.php";

//on charge les variables d'environnement
$VARIABLES_GLOBALES = import_config();

//on redirige le user vers la bonne page s'il est déjà connecté
if (!empty($_SESSION["user"])){
    $user = unserialize($_SESSION["user"]);
    $userRole = $user->getRole();

    switch ($userRole){
        case Enum_role_user::ADMIN:
            header("Location:page_accueil_admin.php");
            break;
        case Enum_role_user::USER:
            header("Location:page_accueil_user.php");
            break;
        case Enum_role_user::VISITEUR:
        default :
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="shortcut icon" type="image/png" href="../PICTURES/blitzcalc-favicon-color.png"/>
</head>
<body class="bg-lightblue" style="font-family: 'Poppins', sans-serif;">

<header class="w-full shadow-md bg-lightblue">
    <nav class="bg-lightblue border-gray-200 dark:bg-gray-900">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
          <a href="../index.html" class="flex items-center space-x-3 rtl:space-x-reverse">
              <img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" class="h-14 xl:h-20" alt="BlitzCalc Logo" />
          </a>
        </div>
      </nav>
</header>


<section class="h-fit xl:h-screen  w-full flex items-center justify-center mt-10 xl:mt-0 mb-20">
    <div class="w-full flex items-center justify-center px-6 mb-20">

        <div class=" xl:w-1/3 w-full bg-deepblue rounded-3xl items-center">
            <h2 class="text-4xl xl:text-3xl text-center text-white my-8">Se connecter</h2>
            <form class="mb-4 rounded  px-8 pb-8 pt-6 w-full xl:w-full" method="post" action="traitement_connexion.php">
                <div class="mb-4">
                    <label class="mb-2 block xl:text-sm text-3xl font-bold text-white" for="login"> Identifiant </label>
                    <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 xl:text-sm text-xl leading-tight text-gray-700 shadow focus:outline-none" id="login" name="login" placeholder="Identifiant" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block xl:text-sm text-3xl font-bold text-white" for="password"> Mot de passe </label>
                    <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 xl:text-sm text-xl leading-tight text-gray-700 shadow focus:outline-none" id="password" name="password" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>" required/>
                </div>
                <div class="mb-6 text-center">
                    <input type="submit" name="submit_connexion_user" value="Valider" class="xl:w-1/2 w-2/4 text-xl xl:text-base mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
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
                <div class="flex flex-col xl:flex-row items-center xl:justify-center">
                    <a href="page_inscription.php" class="xl:text-sm text-xl text-opacity-200 float-right mt-6 xl:mt-0 xl:mr-6 mb-4 xl:mb-0 text-white hover:underline"> Pas encore inscrit ? </a>
                    <a href="../index.html" class="xl:text-sm text-xl text-opacity-200 float-left mt-6 xl:mt-0 xl:ml-6 mb-8 xl:mb-0 text-white hover:underline"> Mot de passe oublié ??</a>
                </div>
                </form>
            </div>
        </div>
    </div>
 </section>
<?php
require_once "../HTML/footer.html";
?>

</body>
</html>