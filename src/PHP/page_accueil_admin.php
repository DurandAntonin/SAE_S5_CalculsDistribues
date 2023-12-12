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
    <script defer src = "../JS/accueil_script.js"> </script>
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
                <a href="../index.html"><img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" alt="Logo" class="h-20"></a>
            </div>
        
            <div class="float-right">
                <img src='../PICTURES/IconeProfil.png' alt='profile picture' class='h-10 cursor-pointer' onclick='showProfil()' id='showProfil'>
            </div>
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
    <div class="container flex flex-row justify-between items-center w-auto  mx-auto my-20">
        <div>
            <p class="text-3xl text-deepblue flex flex-row justify-between items-center mx-2"><ion-icon name="home" class="mx-2  "></ion-icon>Dashboard</p>
        </div>
      <div class="grid w-[40rem] grid-cols-4 gap-2 rounded-xl bg-deepblue p-2 text-white">
        <div>
            <input type="radio" name="option" id="jour" value="jour" class="peer hidden" checked />
            <label for="jour" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Jour</label>
        </div>
        <div>
            <input type="radio" name="option" id="semaine" value="semaine" class="peer hidden" />
            <label for="semaine" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Semaine</label>
        </div>

        <div>
            <input type="radio" name="option" id="mois" value="mois" class="peer hidden" />
            <label for="mois" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Mois</label>
        </div>

        <div>
            <input type="radio" name="option" id="tout" value="tout" class="peer hidden" />
            <label for="tout" class="block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Tout</label>
        </div>
    </div>
    </div>
</section>
    <footer class="relative bg-deepblue pt-8 pb-6">
        <div class="container mx-auto px-4">
          <div class="flex flex-wrap text-left lg:text-left">
            <div class="w-full lg:w-6/12 px-4">
              <h4 class="text-3xl fonat-semibold text-white">En savoir plus sur le projet !</h4>
              <h5 class="text-lg mt-0 mb-2 text-white">
                Trouvez nous sur les plateformes ci-dessous.
              </h5>
              <div class="mt-6 lg:mb-0 mb-6">
                <a href="https://discord.com"><button class="bg-white text-blue-800 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2" type="button">
                    <ion-icon name="logo-discord" class="text-2xl my-2"></ion-icon></button></a>
                <a href="https://github.com/DurandAntonin/SAE_S5_CalculsDistribues/"><button class="bg-white text-black shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2" type="button">
                    <ion-icon name="logo-github" class="text-2xl my-2"></ion-icon></button></a>
                <a href="https://hub.docker.com/u/wzehren"><button class="bg-white text-blue-700 font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2" type="button">
                    <ion-icon name="logo-docker" class="text-2xl my-2"></ion-icon></button></a>
                <a href="https://youtu.be/1-kKTOr5mcU?si=IaO0BMT9EiPGCDlr"><button class="bg-white text-red-600 shadow-lg font-normal h-10 w-10 items-center justify-center align-center rounded-full outline-none focus:outline-none mr-2" type="button">
                    <ion-icon name="logo-youtube" class="text-2xl my-2"></ion-icon>
                </button></a>
              </div>
            </div>
            <div class="w-full lg:w-6/12 px-4">
              <div class="flex flex-wrap items-top mb-6">
                <div class="w-full h-full  lg:w-6/12 px-4 ml-auto my-10">
                  <a href="https://www.uvsq.fr"><img src="../PICTURES/IUT_logo.png" class="h-full"></a>
                </div>
                <div class="w-full lg:w-4/12 px-4">
                  <span class="block uppercase text-white text-sm font-semibold mb-2">Ressources</span>
                  <ul class="list-unstyled">
                    <li>
                      <a class="text-white hover:text-lyellow font-semibold block pb-2 text-sm" href="../../doc/Sujet/SujetSaeS5.pdf">Sujet de SAE</a>
                    </li>
                    <li>
                      <a class="text-white hover:text-lyellow font-semibold block pb-2 text-sm" href="https://loldle.net/">LoLdle</a>
                    </li>
                    <li>
                      <a class="text-white hover:text-lyellow font-semibold block pb-2 text-sm" href="">Confidentialités</a>
                    </li>
                    <li>
                      <a class="text-white hover:text-lyellow font-semibold block pb-2 text-sm" href="">Contactez nous</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <hr class="my-6 border-blueGray-300">
          <div class="flex flex-wrap items-center md:justify-between justify-center">
            <div class="w-full md:w-4/12 px-4 mx-auto text-center">
              <div class="text-sm text-white font-semibold py-1">
                Copyright © <span id="get-current-year">2023</span><a href="" class="text-white hover:text-lyellow" target="_blank"> BlitzCalc by
                <a href="https://www.creative-tim.com?ref=njs-profile" class="text-blueGray-500 hover:text-lyellow">Madianou Corp</a>.
              </div>
            </div>
          </div>
        </div>
      </footer>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.2/dist/sweetalert2.min.js"></script>

</body>
</html>
