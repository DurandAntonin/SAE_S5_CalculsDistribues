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
    <script defer src = "../JS/profil_script.js"> </script>
    <script defer src = "../JS/accueil_admin_script.js"> </script>
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
                <a href="../index.html"><img src="../PICTURES/blitzcalc-high-resolution-logo-transparent.png" alt="Logo" class="xl:h-20 h-40"></a>
            </div>

            <div class="float-right">
                <img src='../PICTURES/IconeProfil.png' alt='profile picture' class='xl:h-10 h-20 cursor-pointer' onclick='showProfil()' id='showProfil'>
            </div>
        </div>
    </nav>
</header>
<?php

echo "<div class='hidden absolute xl:top-20 z-50 top-40 right-0 xl:w-60 w-fit px-5 py-3 mr-2 dark:bg-gray-800 bg-deepblue rounded-lg shadow border dark:border-transparent animate-fade-down animate-duration-[400ms] animate-ease-in-out' id='popUpProfil'>
            <ul class='space-y-3 text-white'>
              <li class='font-medium'>
                <a href='#' class='flex items-center transform text-5xl xl:text-base transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700'>
                  <div class='mr-3'>
                    <svg class='w-12 xl:w-6 h-12 xl:h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'></path></svg>
                  </div>";
echo $user->getLogin();
echo "</a>
             </li>";
echo"<hr class='dark:border-gray-700'>
             <li class='font-medium'>
               <a href='page_deconnexion.php' class='flex items-center transform text-5xl xl:text-base transition-colors duration-200 border-r-4 border-transparent hover:border-red-600'>
                 <div class='mr-3 text-red-600'>
                   <svg class='w-12 xl:w-6 h-12 xl:h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'></path></svg>
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


<section class="w-full h-fit xl:h-screen ">
    <div class="container flex flex-col xl:flex-row justify-between items-center w-auto  mx-auto my-20">
        <div>
            <p class="text-3xl text-deepblue flex flex-row justify-between items-center xl:mx-2 mb-4 xl:mb-0"><ion-icon name="home" class="mx-2"></ion-icon>Dashboard</p>
        </div>
        <div class="grid w-full md:w-2/3 xl:w-1/3 grid-cols-4 gap-2 rounded-xl bg-deepblue p-2 text-white">
            <div>
                <input type="radio" name="option" id="jour" value="jour" class="peer hidden" checked />
                <label for="jour" class="time-filter block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Jour</label>
            </div>
            <div>
                <input type="radio" name="option" id="semaine" value="semaine" class="peer hidden" />
                <label for="semaine" class="time-filter block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Semaine</label>
            </div>

            <div>
                <input type="radio" name="option" id="mois" value="mois" class="peer hidden" />
                <label for="mois" class="time-filter block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Mois</label>
            </div>

            <div>
                <input type="radio" name="option" id="tout" value="tout" class="peer hidden" />
                <label for="tout" class="time-filter block cursor-pointer select-none rounded-xl p-2 text-center peer-checked:bg-white peer-checked:font-bold peer-checked:text-deepblue">Tout</label>
            </div>
        </div>
    </div>
    <main class="flex-1 overflow-x-hidden overflow-y-auto">

        <div class="container px-6 py-8 mx-auto">
            <div class="mt-2">
                <p id="p-message" style="color:#EB3939"></p>
            </div>

            <div class="mt-4">
                <div class="flex flex-col xl:flex-row -mx-6">
                    <div class="w-full px-6">
                        <div class="flex items-center px-5 py-6 bg-white rounded-md cursor-pointer" onclick='showUsers()' id="showUsers">
                            <div class="p-4 h-16 w-16 bg-lyellow  rounded-full">
                                <ion-icon name="people" class="text-3xl "></ion-icon>
                            </div>

                            <div class="mx-5">
                                <h4 id="nb-users" class="text-2xl font-semibold text-gray-700">0</h4>
                                <div class="text-deepblue ">Nouveaux utilisateurs</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 mt-6 xl:mt-0">
                        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm cursor-pointer" onclick='showLogs()' id="showLogs">
                            <div class="p-4  h-16 w-16 bg-lyellow rounded-full">
                                <ion-icon name="key" class="text-3xl "></ion-icon>
                            </div>

                            <div class="mx-5">
                                <h4 id="nb-visits" class="text-2xl font-semibold text-gray-700">0</h4>
                                <div class="text-deepblue">Nombres de visites</div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 mt-6  xl:mt-0">
                        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                            <div class="p-4  h-16 w-16 bg-lyellow rounded-full">
                                <ion-icon name="calculator" class="text-3xl"></ion-icon>
                            </div>

                            <div class="mx-5">
                                <h4 id="nb-module-uses" class="text-2xl font-semibold text-gray-700">0</h4>
                                <div class="text-deepblue">Utilisation des modules</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
            </div>

            <div class="flex justify-between items-center w-auto flex-col md:flex-row  mx-auto my-20">

                <div class="flex flex-col mt-8 md:w-3/4 w-full">
                    <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                        <div
                                class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                            <table class="min-w-full">
                                <thead>
                                <tr>
                                    <th
                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                        Machine</th>
                                    <th
                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                        % Processeur</th>
                                    <th
                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                        % Mémoire</th>
                                    <th
                                            class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                                        Uptime</th>
                                </tr>
                                </thead>

                                <tbody id="tbody-table-stats" class="bg-white">
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                     src="../PICTURES/pi4.png"
                                                     alt="">
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium leading-5 text-gray-900">cnat
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">172.19.181.254</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td
                                            class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                        null</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                     src="../PICTURES/pi0.png"
                                                     alt="">
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium leading-5 text-gray-900">pi1
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">172.19.181.1</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td
                                            class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                        null</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                     src="../PICTURES/pi0.png"
                                                     alt="">
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium leading-5 text-gray-900">pi2
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">172.19.181.2</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td
                                            class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                        null</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                     src="../PICTURES/pi0.png"
                                                     alt="">
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium leading-5 text-gray-900">pi3
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">172.19.181.3</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td
                                            class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                        null</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-10 h-10">
                                                <img class="w-10 h-10 rounded-full"
                                                     src="../PICTURES/pi0.png"
                                                     alt="">
                                            </div>

                                            <div class="ml-4">
                                                <div class="text-sm font-medium leading-5 text-gray-900">pi4
                                                </div>
                                                <div class="text-sm leading-5 text-gray-500">172.19.181.4</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">null</div>
                                        <div class="text-sm leading-5 text-gray-500">null</div>
                                    </td>

                                    <td
                                            class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                        null</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg w-1/3  mt-4">
                    <h1 class="text-2xl text-deepblue mb-8 text-center"> Connexions</h1>
                    <canvas class=" ml-20 mr-20" id="chartPie"></canvas>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            </div>
        </div>
    </main>


    <div class="fixed left-0 top-0 z-40 h-full w-full select-none bg-gray-200 bg-opacity-50 hidden" id="popUpUsers">
        <div class="relative z-50 mx-auto mt-36 xl:w-1/2 w-4/6 p-10 bg-deepblue rounded-xl h-3/4" id="contentUsers">
            <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showUsers()"></ion-icon>
            <p class="text-white text-2xl text-center">Rechercher des utilisateurs</p>
            <form class="flex items-center my-5" onsubmit="return false">
                <label for="voice-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="research-bar-user" class="block w-full rounded-lg border border-white bg-deepblue p-2.5 pl-10 text-sm text-white focus:border-lightblue focus:ring-lightblue" placeholder="Rechercher un utilisateur"/>
                </div>
                <select id="select-user-attribute" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <option value="">--Rechercher par--</option>
                    <option value="userId">ID</option>
                    <option value="login">Login</option>
                    <option value="userMail">Mail</option>
                    <option value="lastName">Nom</option>
                    <option value="firstName">Prénom</option>
                </select>
                <input type="button" name="User" value="Rechercher" id="button-submit-research-users" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
            </form>
            <div class="my-5">
                <p id="p-message-erreur-recherche-users" style="color:#EB3939"></p>
            </div>
            <div id="div-liste-users" class="w-full overflow-y-auto h-4/5 rounded-xl bg-deepblue p-1 shadow-xl border border-white">
                <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
                    <!--<div class="grid grid-flow-col grid-rows-2 ">
                        <div class="mr-20 text-lg font-bold text-white">Login : Tom</div>
                        <div class="text-xs text-white">
                            <span class="mr-2">ID : 007886</span>
                        </div>
                        <div class="text-lg font-bold text-white mr-20">Adresse mail : test@gmail.com</div>
                        <div class="text-xs text-white">
                            <span class="mr-2">Nom : Zehren</span>
                            <span class="mr-2">Prénom: William</span>
                        </div>
                        <div class="text-lg font-bold text-white">Inscription : 2023-12-19</div>

                        <ion-icon name="trash" class="text-3xl absolute right-2 text-red-700 cursor-pointer"></ion-icon>
                    </div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="fixed left-0 top-0 z-40 h-full w-full select-none bg-gray-200 bg-opacity-50 hidden" id="popUpLogs">
        <div class="relative z-50 mx-auto mt-36 xl:w-1/2 w-4/6 p-10 bg-deepblue rounded-xl md:h-3/4 h-3/4" id="contentLogs">
            <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showLogs()"></ion-icon>
            <p class="text-white text-2xl text-center">Rechercher des logs</p>
            <form class="flex items-center my-5" onsubmit="return false">
                <label for="voice-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input type="text" id="research-bar-logging" class="block w-full rounded-lg border border-white bg-deepblue p-2.5 pl-10 text-sm text-white focus:border-lightblue focus:ring-lightblue" placeholder="Rechercher des logs"/>
                </div>
                <select id="select-logging-attribute" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <option value="">--Rechercher par--</option>
                    <option value="logId">ID</option>
                    <option value="logLevel">Level</option>
                    <option value="userId">UserID</option>
                    <option value="description">Description</option>
                    <option value="date">Date</option>
                </select>
                <input type="button" name="Logging" value="Rechercher" id="button-submit-research-logging" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
            </form>
            <div class="my-5">
                <p id="p-message-erreur-recherche-logs" style="color:#EB3939"></p>
            </div>
            <div id="div-list-logging" class="w-full overflow-y-auto h-4/5 rounded-xl bg-deepblue p-1 shadow-xl border border-white">
                <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
                    <!--<div class="grid grid-flow-col grid-rows-2 ">
                        <div class="mr-20 text-lg font-bold text-white">LogLevel : INFO</div>
                        <div class="text-xs text-white">
                            <span class="mr-2">LogID : 007886-009975-90876-09865</span>
                        </div>
                        <div class="text-lg font-bold text-white mr-20">Description : Connexion user USER</div>
                        <div class="text-xs text-white">
                            <span class="mr-2">UserID : 90875-09889 </span>
                            <span class="mr-2">IP : 127.0.0.1</span>
                        </div>

                        <div class=" text-lg font-bold text-white absolute right-2">Date : 2023-12-19 13:55:12 </div>
                    </div>-->
                </div>
            </div>
        </div>
</section>

<?php
require_once "../HTML/footer.html";
?>

</body>
</html>
