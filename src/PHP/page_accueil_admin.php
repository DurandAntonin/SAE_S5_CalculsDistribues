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
                        <div class="mt-4">
                            <div class="flex flex-wrap -mx-6">
                                <div class="w-full px-6 sm:w-1/2 xl:w-1/3">
                                    <div class="flex items-center px-5 py-6 bg-white rounded-md cursor-pointer" onclick='showUsers()' id="showUsers">
                                        <div class="p-4 h-16 w-16 bg-lyellow  rounded-full">
                                        <ion-icon name="people" class="text-3xl "></ion-icon>
                                        </div>
        
                                        <div class="mx-5">
                                            <h4 id="nb-users" class="text-2xl font-semibold text-gray-700">8,282</h4>
                                            <div class="text-deepblue ">Nombres d'utilisateurs</div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 sm:mt-0">
                                    <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm cursor-pointer" onclick='showLogs()' id="showLogs">
                                        <div class="p-4  h-16 w-16 bg-lyellow rounded-full">
                                            <ion-icon name="key" class="text-3xl "></ion-icon>
                                        </div>
        
                                        <div class="mx-5">
                                            <h4 id="nb-visits" class="text-2xl font-semibold text-gray-700">200,521</h4>
                                            <div class="text-deepblue">Nombres de visites</div>
                                        </div>
                                    </div>
                                </div>
        
                                <div class="w-full px-6 mt-6 sm:w-1/2 xl:w-1/3 xl:mt-0">
                                    <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-sm">
                                        <div class="p-4  h-16 w-16 bg-lyellow rounded-full">
                                            <ion-icon name="calculator" class="text-3xl"></ion-icon>
                                        </div>
        
                                        <div class="mx-5">
                                            <h4 id="nb-module-uses" class="text-2xl font-semibold text-gray-700">215,542</h4>
                                            <div class="text-deepblue">Utilisation des modules</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <div class="mt-8">
        
                        </div>

                        <div class="flex justify-between items-center w-auto  mx-auto my-20">
        
                            <div class="flex flex-col mt-8 w-3/4">
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
                                                        <div class="text-sm leading-5 text-gray-900">14.7 %</div>
                                                        <div clasistUserss="text-sm leading-5 text-gray-500">0.8 GHz</div>
                                                    </td>
            
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                    <div class="text-sm leading-5 text-gray-900">14.4 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.766 Go</div>
                                                    </td>
            
                                                    <td
                                                        class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                                        up 5 days, 7 hours, 50 minutes</td>
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
                                                        <div class="text-sm leading-5 text-gray-900">5.7 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.5 GHz</div>
                                                    </td>
            
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                    <div class="text-sm leading-5 text-gray-900">45.9 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.233 Go</div>
                                                    </td>
            
                                                    <td
                                                        class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                                        up 4 days, 21 hours, 25 minutes</td>
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
                                                        <div class="text-sm leading-5 text-gray-900">5.7 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.5 GHz</div>
                                                    </td>
            
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                    <div class="text-sm leading-5 text-gray-900">45.9 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.233 Go</div>
                                                    </td>
            
                                                    <td
                                                        class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                                        up 4 days, 21 hours, 25 minutes</td>
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
                                                        <div class="text-sm leading-5 text-gray-900">5.7 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.5 GHz</div>
                                                    </td>
            
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                    <div class="text-sm leading-5 text-gray-900">45.9 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.233 Go</div>
                                                    </td>
            
                                                    <td
                                                        class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                                        up 4 days, 21 hours, 25 minutes</td>
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
                                                        <div class="text-sm leading-5 text-gray-900">5.7 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.5 GHz</div>
                                                    </td>
            
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                    <div class="text-sm leading-5 text-gray-900">45.9 %</div>
                                                        <div class="text-sm leading-5 text-gray-500">0.233 Go</div>
                                                    </td>
            
                                                    <td
                                                        class="px-6 py-4 text-sm leading-5 text-gray-500 whitespace-no-wrap border-b border-gray-200">
                                                        up 4 days, 21 hours, 25 minutes</td>
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
  <div class="relative z-50 mx-auto mt-36 w-3/6 p-10 bg-deepblue rounded-xl h-3/4" id="contentUsers">
  <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showUsers()"></ion-icon>
    <p class="text-white text-2xl text-center">Liste des utilisateur inscrits</p>
    <form class="flex items-center my-5">
      <label for="voice-search" class="sr-only">Search</label>
      <div class="relative w-full">
        <div id="button-submit-research-users" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
        </div>
        <input type="text" id="research-bar-user" class="block w-full rounded-lg border border-white bg-deepblue p-2.5 pl-10 text-sm text-white focus:border-lightblue focus:ring-lightblue" placeholder="Rechercher un utilisateur" required />
      </div>
      <select id="select-user-attribute" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <option value="">--Trier par--</option>
        <option value="login">Login</option>
        <option value="id">ID</option>
        <option value="mail">Mail</option>
        <option value="nom">Nom</option>
        <option value="prenom">Prénom</option>
      </select>
    </form>
    <div class="w-full overflow-y-auto h-4/5 rounded-xl bg-deepblue p-1 shadow-xl border border-white">
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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

          <ion-icon name="trash" class="text-3xl absolute right-2 text-red-700 cursor-pointer"></ion-icon>      </div>
      </div>
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
    </div>
  </div>
</div>
<div class="fixed left-0 top-0 z-40 h-full w-full select-none bg-gray-200 bg-opacity-50 hidden" id="popUpLogs">
  <div class="relative z-50 mx-auto mt-36 w-3/6 p-10 bg-deepblue rounded-xl h-3/4" id="contentLogs">
  <ion-icon name="close" class="text-white right-0 my-9 mr-6 top-0 absolute text-3xl cursor-pointer" onclick="showLogs()"></ion-icon>
    <p class="text-white text-2xl text-center">Liste des Logs</p>
    <form class="flex items-center my-5">
      <label for="voice-search" class="sr-only">Search</label>
      <div class="relative w-full">
        <div id="button-submit-research-logging" class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
        </div>
        <input type="text" id="research-bar-logging" class="block w-full rounded-lg border border-white bg-deepblue p-2.5 pl-10 text-sm text-white focus:border-lightblue focus:ring-lightblue" placeholder="Rechercher un logs" required />
      </div>
      <select id="select-logging-attribute" class="ml-2 inline-flex items-center rounded-lg border border-white bg-deepblue px-3 py-2.5 text-sm font-medium text-white hover:bg-deepblue focus:outline-none focus:ring-4 focus:ring-lightblue">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <option value="">--Trier par--</option>
        <option value="level">Level</option>
        <option value="logid">ID</option>
        <option value="userid">UserID</option>
        <option value="ip">IP</option>
        <option value="date">Date</option>
      </select>
    </form>
    <div class="w-full overflow-y-auto h-4/5 rounded-xl bg-deepblue p-1 shadow-xl border border-white">
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <!-- items -->
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
      <div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div><div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div><div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div><div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div><div class="flex w-full items-center rounded-lg p-3 pl-4 hover:bg-lightblue relative">
        <div class="grid grid-flow-col grid-rows-2 ">
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
        </div>
      </div>
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
