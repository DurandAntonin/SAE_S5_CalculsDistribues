<?php

namespace PHP;


include_once "Logger.php";
include_once "LoggerInstance.php";
include_once "MySQLDataManagement.php";
include_once "Enum_niveau_logger.php";
include_once "Enum_role_user.php";

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
    <script defer src = "../JS/module1_script.js"> </script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="shortcut icon" type="image/png" href="../PICTURES/blitzcalc-favicon-color.png"/>
</head>

<body class="bg-lightblue" style="font-family: 'Poppins', sans-serif;">

<header class="top-0 w-full shadow-md bg-lightblue">
    <nav class="flex justify-between items-center w-auto  mx-auto">
        <div class="container mx-auto flex items-center justify-between">
            <div >
                <a href="page_accueil_user.php"><img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" alt="Logo" class="h-20"></a>
            </div>
            <?php
            //seul le user inscrit peut voir son profil
            if ($user->getRole() == Enum_role_user::VISITEUR){
                echo "
                <div class='nav-links duration-500 bg-lightblue md:static absolute md:min-h-fit min-h-[15vh] left-0 top-[-100%] md:w-auto  w-full flex items-center px-5'>
                <ul class='flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-8'>
                    <li>
                        <a href='page_inscription.php' class='text-deepblue mr-4 text-3xl relative w-fit block after:block after:content-[''] after:absolute after:h-[3px] after:bg-black after:w-full after:scale-x-0 after:hover:scale-x-100 after:transition after:duration-300 after:origin-center'>S'inscrire</a>
                    </li>
                </ul>
              </div>
            </div>
            <div class='flex items-center gap-6'>
                <ion-icon onclick='onToggleMenu(this)' name='menu' class='text-3xl cursor-pointer md:hidden'></ion-icon>
            </div>
                    ";
            }
            ?>
            <div class="float-right">
                <img src='../PICTURES/IconeProfil.png' alt='profile picture' class='h-10 cursor-pointer' onclick='showProfil()' id='showProfil'>
            </div>
    </nav>
</header>

<?php
    echo "<div class='hidden top-0 float-right w-60 px-5 py-3 mr-2 dark:bg-gray-800 bg-deepblue rounded-lg shadow border dark:border-transparent animate-fade-down animate-duration-[400ms] animate-ease-in-out' id='popUpProfil'>
            <ul class='space-y-3 text-white'>
              <li class='font-medium'>
                <a href='#' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700'>
                  <div class='mr-3'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'></path></svg>
                  </div>";
    echo $user->getLogin();
    echo "</a>
             </li>";
    //on affiche les settings si le user est inscrit
    if ($user->getRole() == Enum_role_user::USER){
        echo "<li class='font-medium'>
                <a href='#' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700' onclick='showFormProfile()' id='linkShowProfil'>
                  <div class='mr-3'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'></path><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'></path></svg>
                  </div>
                  Setting
                </a>
              </li>";
    }
    echo"<hr class='dark:border-gray-700'>
              <li class='font-medium'>
                <a href='page_deconnexion.php' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-red-600'>
                  <div class='mr-3 text-red-600'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'></path></svg>
                  </div>
                  Logout
                </a>
              </li>
            </ul>
          </div>";
?>

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

<section class="w-full h-screen ">
<div class="h-screen items-center justify-center hidden" id="popUpFormProfil">
    <div class="container mx-auto">
        <div class="my-12 flex items-center justify-center px-6">
            <!-- Row -->

            <div class="w-full md:w-1/3 bg-deepblue rounded-3xl items-center relative">
                <h2 class="text-3xl text-center text-white my-8">Mon profil </h2>
                <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showFormProfile()"></ion-icon>
                <form class="rounded  px-8 pb-8 pt-6" method="post" action="traitement_profil.php" id="formProfil">
                <input type="hidden" id="submit_supprimer_compte" name="submit_supprimer_compte" value="">
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-bold text-white" for="login"> Identifiant </label>
                        <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="login" name="login" placeholder="Identifiant" value="<?php echo $user->getLogin(); ?>" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
                    </div>
                    <div class="mb-4 md:flex md:justify-between">

                        <div class="mb-4 md:mb-0 md:mr-2">
                            <label class="mb-2 block text-sm font-bold text-white" for="first_name"> Prénom </label>
                            <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="first_name" name="first_name" type="text" placeholder="Prénom" value="<?php echo $user->getFirstName(); ?>" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
                        </div>
                        <div class="md:ml-2">
                            <label class="mb-2 block text-sm font-bold text-white" for="last_name"> Nom </label>
                            <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="last_name" name="last_name" type="text" placeholder="Nom" value="<?php echo $user->getLastName(); ?>" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-bold text-white" for="email"> Email </label>
                        <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="email" type="email" name="email" placeholder="Email" value="<?php echo $user->getMail(); ?>" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mail"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mail"][1];?>" required/>
                    </div>
                    <div class="mb-4 md:flex md:justify-between">
                        <div class="mb-4 md:mb-0 md:mr-2">
                            <label class="mb-2 block text-sm font-bold text-white" for="password"> Mot de passe </label>
                            <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border border-red-500 px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password" name="password" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>"/>
                            <p class="text-xs italic text-red-500">Choisissez un mot de passe</p>
                            <p class="text-xs italic text-red-500">différent du précédent.</p>
                        </div>
                        <div class="md:ml-2">
                            <label class="mb-2 block text-sm font-bold text-white" for="password_confirm"> Confirmez le mot de passe</label>
                            <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password_confirm" name="password_confirm" type="password" placeholder="******************" minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_mdp"][1];?>"/>
                        </div>
                    </div>
                    <div class="text-center">
                        <input type="submit" name="submit_profil" value="Valider" class="w-3/4 mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
                    </div>
                </form>
                <div class="mb-8 text-center">
                    <button name="submit_suppression" value="Supprimer" class="w-2/4 mt-6 py-2 rounded-xl bg-red-500 text-white focus:outline-none hover:bg-white hover:text-red-500 focus:ring-4 focus:ring-gray-300 cursor-pointer" onclick="confirmDelete()"><ion-icon name="trash" class="text-center animate-rotate-y animate-infinite animate-duration-[2000ms] animate-ease-in-out"></ion-icon>Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>   


<div class="h-screen flex items-center justify-center">
    <div class="container mx-auto">
    <div class="my-12 flex items-center justify-center px-6">
      <!-- Row -->
     
        <div class="w-full md:w-2/3 bg-deepblue rounded-3xl items-center">
          <h2 class="text-3xl text-center text-white my-8">Calcul des nombres premiers</h2>
          <form class="mb-4 rounded  px-8 pb-8 pt-6" method="post" action="traitement_module1.php">
            <div class="mb-4 md:flex md:justify-between">
            
              <div class="mb-4 md:mb-0 md:mr-2 ml-36">
                <label class="mb-2 block text-sm font-bold text-white" for="debut"> Borne début </label>
                <input class="focus:shadow-outline w-1/2 appearance-none rounded-xl border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="debut" name="debut" type="number" placeholder="1, 2, 3 , ..." minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
              </div>
              <div class="md:ml-2 mr-15">
                <label class="mb-2 block text-sm font-bold text-white" for="fin"> Borne fin </label>
                <input class="focus:shadow-outline w-1/2 appearance-none rounded-xl border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="fin" name="fin" type="number" placeholder="100, 200, 1000, ..." minlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][0];?>" maxlength="<?php echo $VARIABLES_GLOBALES["taille_champ_texte"][1];?>" required/>
              </div>
            </div>

            <div class="mb-4 md:mb-0 md:mr-2 flex justify-center items-center">
                <p class="border-2 border-white h-80 w-3/4 rounded-xl"></p>
            </div>
            <div class="md:mb-0 md:mr-2 flex justify-center items-center">
                <p class="inline-block align-baseline text-sm text-opacity-200 text-white" href="page_connexion.php"> Temps d'exécution : 243.12s </p>
            </div>
            <div class="mb-6 text-center">
              <input type="submit" name="submit_inscription" value="Calculer" class="w-1/4 mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
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
            <div class="flex items-center justify-center w-full">
  
  <label for="toggleB" class="flex flex-col items-center cursor-pointer">
    <div class="relative">
      <input type="checkbox" id="toggleB" class="sr-only">
      <div class="block bg-deepblue w-24 h-8 rounded-full border-2 border-white"></div>
      <div class="dot absolute left-1 top-1 bg-lightblue w-6 h-6 rounded-full transition"></div>
    </div>
    <div class="ml-3 text-white font-medium">
      CALCUL DISTRIBUE <b class="text-red-700"> INACTIF</b>
    </div>
  </label>

</div>
              
                </div>
            </form>
            </div>
        </div>
     </div>
    </div>
</div>
</section>