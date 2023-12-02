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
    <script defer src = "../JS/accueil_script.js"> </script>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>  
</head>
<body class="bg-lightblue" style="font-family: 'Poppins', sans-serif;">

<header class="top-0 w-full shadow-md bg-lightblue">
        <nav class="flex justify-center items-center w-auto  mx-auto">
            <div class="container mx-auto flex items-center justify-center">
              <div >
                <a href="../index.html"><img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" alt="Logo" class="h-20"></a>
              </div>
              </div>
              <div class="float-right">
                    <?php
                //seul le user inscrit peut voir son profil
                if ($user->getRole() == Enum_role_user::USER){
                    echo "<img src='../PICTURES/IconeProfil.png' alt='profile picture' class='h-10 mr-10 cursor-pointer' onclick='showProfil()' id='showProfil'>   
                    ";
                }
                ?>
        </nav>
    </header>
    <?php
        if ($user->getRole() == Enum_role_user::USER){
            echo "<div class='hidden top-0 float-right w-60 px-5 py-3 mr-2 dark:bg-gray-800 bg-deepblue rounded-lg shadow border dark:border-transparent animate-fade-down animate-duration-[400ms] animate-ease-in-out' id='popUpProfil'>
            <ul class='space-y-3 text-white'>
              <li class='font-medium'>
                <a href='#' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700'>
                  <div class='mr-3'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'></path></svg>
                  </div>"; 
                echo $user->getLogin();
                echo "</a>
              </li>
              <li class='font-medium'>
                <a href='#' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700' onclick='showFormProfile()' id='linkShowProfil'>
                  <div class='mr-3'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'></path><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 12a3 3 0 11-6 0 3 3 0 016 0z'></path></svg>
                  </div>
                  Setting
                </a>
              </li>
              <hr class='dark:border-gray-700'>
              <li class='font-medium'>
                <a href='page_deconnexion.php' class='flex items-center transform transition-colors duration-200 border-r-4 border-transparent hover:border-red-600'>
                  <div class='mr-3 text-red-600'>
                    <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'></path></svg>
                  </div>
                  Logout
                </a>
              </li>
            </ul>
          </div>    
                    ";
                }
                ?>
<div class="h-screen items-center justify-center hidden" id="popUpFormProfil">
    <div class="container mx-auto">
    <div class="my-12 flex items-center justify-center px-6">
      <!-- Row -->
     
        <div class="w-full md:w-1/3 bg-deepblue rounded-3xl items-center relative">
          <h2 class="text-3xl text-center text-white my-8">Mon profil </h2>
          <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showFormProfile()"></ion-icon>
          <form class="rounded  px-8 pb-8 pt-6" method="post" action="traitement_profil.php" id="formProfil">
            <div class="mb-4">
                <label class="mb-2 block text-sm font-bold text-white" for="login"> Identifiant </label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="login" name="login" placeholder="Identifiant" value="<?php echo $user->getLogin(); ?>" required/>
            </div>
            <div class="mb-4 md:flex md:justify-between">
            
              <div class="mb-4 md:mb-0 md:mr-2">
                <label class="mb-2 block text-sm font-bold text-white" for="first_name"> Prénom </label>
                <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="first_name" id="first_name" type="text" placeholder="Prénom" value="<?php echo $user->getFirstName(); ?>" required/>
              </div>
              <div class="md:ml-2">
                <label class="mb-2 block text-sm font-bold text-white" for="last_name"> Nom </label>
                <input class="focus:shadow-outline w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="last_name" name="last_name" type="text" placeholder="Nom" value="<?php echo $user->getLastName(); ?>" required/>
              </div>
            </div>
            <div class="mb-4">
              <label class="mb-2 block text-sm font-bold text-white" for="email"> Email </label>
              <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="email" type="email" name="email" placeholder="Email" value="<?php echo $user->getUserMail(); ?>" required/>
            </div>
            <div class="mb-4 md:flex md:justify-between">
              <div class="mb-4 md:mb-0 md:mr-2">
                <label class="mb-2 block text-sm font-bold text-white" for="password"> Mot de passe </label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border border-red-500 px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password" name="password" type="password" placeholder="******************" required/>
                <p class="text-xs italic text-red-500">Choisissez un mot de passe</p>
                <p class="text-xs italic text-red-500">différent du précédent.</p>
              </div>
              <div class="md:ml-2">
                <label class="mb-2 block text-sm font-bold text-white" for="password_confirm"> Confirmez le mot de passe</label>
                <input class="focus:shadow-outline mb-3 w-full appearance-none rounded border px-3 py-2 text-sm leading-tight text-gray-700 shadow focus:outline-none" id="password_confirm" name="password_confirm" type="password" placeholder="******************" required/>
              </div>
            </div>
            <div class="text-center">
              <input type="submit" name="submit_inscription" value="Valider" class="w-3/4 mt-6 py-2 rounded-xl bg-lgrey text-white focus:outline-none hover:bg-lyellow hover:text-deepblue focus:ring-4 focus:ring-gray-300 cursor-pointer">
            </div>
          </form>
            <div class="mb-8 text-center">
              <button name="submit_suppression" value="Supprimer" class="w-2/4 mt-6 py-2 rounded-xl bg-red-500 text-white focus:outline-none hover:bg-white hover:text-red-500 focus:ring-4 focus:ring-gray-300 cursor-pointer" onclick="confirmDelete()"><ion-icon name="trash" class="text-center animate-rotate-y animate-infinite animate-duration-[2000ms] animate-ease-in-out"></ion-icon>Supprimer</button>
            </div>
          </div>
        
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.js"></script>
</body>
</html>