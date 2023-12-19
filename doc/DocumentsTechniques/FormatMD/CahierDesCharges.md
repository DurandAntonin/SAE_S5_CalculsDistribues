<img src="Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Cahier des charges de la SAE S5 </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>

<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <li> <a href="#enonce"> Enoncé  </a> </li>
    <ol>
        <li> <a href="#livrable_1"> Livrable 1 - Squelette du site </a> </li>
        <li> <a href="#livrable_2"> Livrable 2 - 1er module et fonctionnalités utilisateur admin </li>
    </ol>
    <li> <a href="#prerequis"> Prérequis  </a>  </li>
    <ol>
        <li> <a href="#connaissances_competences"> Connaissances et compétences requises  </a> </li>
        <li> <a href="#ressources_materielles"> Ressources matérielles  </a> </li>
        <li> <a href="#ressources_logicielles"> Ressources logicielles  </a> </li>
    </ol>
    <li> <a href="#priorites"> Priorités  </a> </li>
</ol>



<h2 style="color:#5d79e7; page-break-before: always" id="introduction"> Introduction </h2>

Ce document est un cahier des charges technique rédigé en collaboration avec le client.

Il constitue la première étape de l’activité de l’analyse des besoins dans un projet informatique et permet de définir clairement les besoins de l’utilisateur exprimés dans sa version du cahier des charges.

Le cahier des charges comporte une description des objectifs à résoudre, des fonctionnalités du logiciel à développer et des modules qui s'y rattachent, i-e des exigences fonctionnelles de l’application dans la partie <a href="#enonce"> Enoncé</a>. Puis dans la partie <a href="#prerequis"> Prérequis</a>, une définition des connaissances, compétences et ressources matérielles et logicielles pour mener à bien ce projet.

Enfin, dans la partie <a href="#priorites"> Priorités</a> nous verrons les différentes fonctionnalités à développer en priorité, fixées au préalable avec le client.
Ce document est sujet-à modifications à la suite des entrevues avec les clients du projet.

<h2 style="color:#5d79e7; page-break-before: always" id="enonce"> Enoncé </h2>  

<h3 id="livrable_1"> Livrable 1 - Squelette du site </h3>

Le premier livrable consiste en premier lieu à l'initialisation du cluster rpi4 qui va héberger notre application.
Puis, en second lieu, seront définis le logo et la charte graphique. Ces derniers resteront inchangés durant le reste du projet.
Enfin, une première version du site sera réalisée, implémentant la fonctionnalité d'inscription et de connexion d'un utilisateur.
Les maquettes des différentes pages du site seront également réalisées dans ce livrable. Elles seront réalisées en fonction de la charte graphique préalablement définie.

3 types d'utilisateurs existeront : 

- Utilisateur inscrit

- Utilisateur non inscrit 

- Administrateur 

Une page d'accueil du site permet de présenter ce dernier. L'utilisateur a le choix de s'inscrire, de se connecter ou d'accéder directement aux modules de calculs sans se connecter.

Si l'utilisateur fait le choix d'accèder directement aux modules du site sans se connecter, il pourra toujours s'inscrire et se connecter. 

La page d'inscription permettra à de nouveaux utilisateurs de se créer un compte en renseignant une adresse mail, un login, un nom, un prénom et un mot de passe. 
La page de connexion permettra aux utilisateurs inscrits et aux administrateurs de se connecter, en entrant un login et un mot de passe. 

Les utilisateurs inscrits pourront accéder aux différents modules de calculs sur le site, et bénéficieront de la vitesse de calcul du kit cluster hat. 
Ils pourront cliquer sur une icone profil leur permettant de se deconnecter et de voir et modifier leurs informations personnelles. 
Cliquer sur ce bouton ouvrira une pop up dans laquelle ils pourront donc voir et modifier leurs informations personnelles, comme leur adresse mail, login, nom, prénom ou mot de passe.
Ils peuvent aussi supprimer leur compte.

Les utilisateurs non inscrits auront aussi accès au site de manière limitée. 
Ils pourront utiliser les modules de calculs comme les utilisateurs inscrits, mais n'auront pas accès aux performances améliorées du calcul distribué.
Comme ils n'ont pas de compte utilisateur, ils ne pourront pas accéder à leur profil.

L'administrateur quant à lui, n'aura accès qu'à une page qui lui sera dédiée, sur laquelle il pourra gérer les différents utilisateurs inscrits. Cette fonctionnalité sera développée dans le livrable 2. 

<h3 id="livrable_2"> Livrable 2 - 1er module et fonctionnalités utilisateur admin </h3>

Le deuxième livrable consiste en premier lieu à développer un premier module de calculs utilisant le calcul distribué. <br>
Ce module permet de calculer les nombres premiers compris entre les bornes _n_ et _m_ saisis par l'utilisateur. Ce dernier est accessible depuis la page d'accueil à la fois pour les utilisateurs connectés et pour les utilisateurs non inscrits.
Le temps d'exécution du calcul des nombres premiers ainsi que la liste de ces derniers sont affichés à l'utilisateur. <br>
Le calcul s'effectue de manière distribué sur les 4 rpi zeros uniquement pour l'utilisateur connecté. <br>
Pour l'utilisateur non inscrit, le calcul s'effectue seulement sur un rpi zero, mais un bouton permet à l'utilisateur de passer en mode "calcul distribué", et donc d'utiliser les autres rpi zero. Lors que l'utilisateur non inscrit clique sur le bouton, une pop-up s'affiche et invite l'utilisateur à s'inscrire ou à se connecter s'il souhaite calculer les nombres premiers en utilisant la puissance du calcul distribué. <br> 
L'utilisateur connecté voit également ce bouton, et peut passer en mode "calcul distribué" ou non, pour tester la différence de vitesse d'exécution par exemple. 

Dans un deuxième temps seront développées les différentes fonctionnalités liées à l'utilisateur administrateur du site. <br>
La première fonctionnalité est le visionnage des différentes statistiques du site, à savoir le nombre de visites, d'utilisateurs inscrits et d'utilisations des modules du site ainsi que le pourcentage d'utilisateurs connectés et non inscrits qui accèdent au site. L'administrateur pourra filtrer ces statistiques en fonction du jour, mois, semaine ou depuis le déploiement du site. <br>
L'administrateur pourra aussi rechercher des utilisateurs inscrits en fonction d'un des critères id, login, adresse mail, nom, prénom et date d'inscription, ainsi que les journaux (logs) du site en fonction d'un des critères date, niveau du journal, l'identifiant de l'utilisateur à l'origine du log, description de ce dernier. Il pourra également supprimer des utilisateurs via un bouton de suppression. 

<br>
Enfin, il peut visualiser les statistiques du cluster hat, à savoir le pourcentage d'utilisation du processeur, de la mémoire ainsi que le uptime de chaque rpi du kit Cluster Hat, à savoir le rpi host et les 4 rpi zeros.

<h2 style="color:#5d79e7; page-break-before: always" id="prerequis"> Prérequis </h2>

<h3 id="connaissances_competences"> Connaissances et compétences requises </h3>

Dans ce projet, plusieurs connaissances et compétences sont requises.

En premier lieu, il est nécessaire de maîtriser __Git__ pour versionner les différents fichiers du projet et créer des livrables.

Une connaissance en __PHP__ est nécessaire pour développer le côté back-end de l'application, et effectuer des requêtes à la base de données.

Une connaissance en __HTML__, __JS__, __CSS__ est ensuite nécessaire pour développer le côté front-end de l'application.

Il est aussi important de connaître les fondamentaux des base de données relationnelles, ainsi que les requêtes basiques en SQL, pour enregistrer et manipuler les données de l'application stockée dans un __SGBD MySQL__.

Une connaissance en __Linux__ et en __bash__ est nécessaire pour gérer, mettre à jour le kit Cluster Hat et l'application hébergée sur ce dernier ainsi que pour créer différents scripts utilisés dans le site.

Une connaissance de __Python__ et de la librairie __MPI__ est nécessaire pour exécuter et maintenir le programme du 1er module de calcul des nombres premiers.

Enfin, il est requis d'avoir des bases en programmation distribuée, pour pouvoir réaliser les programmes de calculs de l'application en utilisant le calcul distribué.

<h3 id="ressources_materielles"> Ressources matérielles </h3>

Les ressouces matérielles que nous avons à disposition pour ce projet sont :

- Un <a href=https://www.minimachines.net/actu/clusterhat-raspberry-pi-80208> Kit Cluster Hat + 4 Pi Zero</a> 
- Une salle informatique à l'IUT avec des ordinateurs, des tableaux et projecteurs
- Des ordinateurs personnels

<h3 id="ressources_logicielles"> Ressources logicielles </h3>

Les ressources logicielles que nous à disposition pour ce projet sont :

- La suite JetBrains avec l'IDE PhpStorm 

<h2 style="color:#5d79e7; page-break-before: always" id="priorites"> Priorités </h2>

Aucune tâche de développement n'a été définie comme prioritaire pour l'instant.