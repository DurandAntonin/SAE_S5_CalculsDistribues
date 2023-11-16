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
        <li> <a href="#livrable_1"> Livrable 1 - Fondations du site </a> </li>
    </ol>
    <li> <a href="#pres_requis"> Prés-requis  </a>  </li>
    <ol>
        <li> <a href="#connaissances_competences"> Connaissances et compétences requises  </a> </li>
        <li> <a href="#ressources_materielles"> Ressources matérielles  </a> </li>
        <li> <a href="#ressources_logicielles"> Ressources logicielles  </a> </li>
    </ol>
    <li> <a href="#priorites"> Priorités  </a> </li>
</ol>



<h2 style="color:#5dbee7; page-break-before: always" id="introduction"> Introduction </h2>

Ce document est un cahier des charges technique rédigé en collaboration avec le client.

Il constitue la première étape de l’activité de l’analyse des besoins dans un projet informatique et permet de définir clairement les besoins de l’utilisateur exprimés dans sa version du cahier des charges.

Le cahier des charges comporte une description précise des objectifs à résoudre, des fonctionnalités du logiciel à développer, i-e des exigences fonctionnelles de l’application dans la partie « Énoncé ». Puis dans la partie « Pré-requis », une définition des connaissances, compétences et ressources matérielles et logicielles pour mener à bien ce projet.

Enfin, dans la partie « Priorités » nous verrons les différentes fonctionnalités à développer en priorité, fixées au préalable avec le client.
Ce document est sujet-à modifications à la suite des entrevues avec les clients du projet.

<h2 style="color:#5dbee7; page-break-before: always" id="enonce"> Enoncé </h2>  

<h3 id="livrable_1"> Livrable 1 - Fondations du site </h3>

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

Les utilisateurs non inscrits auront aussi accès au site, mais y seront limités. 
Ils pourront utiliser les modules de calculs comme les utilisateurs inscrits, mais n'auront pas accès aux performances améliorées du calcul distribué, les calculs seront ainsi plus longs à exécuter.
Comme ils n'ont pas de compte utilisateur, il ne pourront pas accéder à la page profil.

L'administrateur quant à lui, n'aura accès qu'à une page qui lui sera dédiée, sur laquelle il pourra gérer les différents utilisateurs inscrits. Cette fonctionnalité sera développée dans le livrable 2. 


<h2 style="color:#5dbee7; page-break-before: always" id="pres_requis"> Prés-requis </h2>

<h3 id="connaissances_competences"> Connaissances et compétences requises </h3>

Dans ce projet, plusieurs connaissances et compétences sont requises.

En premier lieu, il est nécessaire de maîtriser __Git__ pour versionner les différents fichiers du projet et créer des livrables.

Une connaissance en __PHP__ est nécessaire pour développer le côté back-end de l'application, et effectuer des requêtes à la base de données.

Une connaissance en __HTML__, __JS__, __CSS__ est ensuite nécessaire pour développer le côté front-end de l'application.

Il est aussi important de connaître les fondamentaux des base de données relationnelles, ainsi que les requêtes basiques en SQL, pour enregistrer et manipuler les données de l'application stockée dans un __SGBD MySQL__.

Une connaissance de __Linux__ est nécessaire pour mettre à jour l'application hébergée sur le RaspberryPi 4 du kit Cluster.

Enfin, il est requis d'avoir des bases en programmation distribuée, pour pouvoir réaliser les programmes de calculs de l'application en utilisant le calcul distribué.

<h3 id="ressources_materielles"> Ressources matérielles </h3>

Les ressouces matérielles que nous avons à disposition pour ce projet sont :

- Un kit Cluster Hat + 4 Pi Zero
- Une salle informatique à l'IUT avec des ordinateurs, des tableaux et projecteurs
- Des ordinateurs personnels

<h3 id="ressources_logicielles"> Ressources logicielles </h3>

Les ressources logicielles que nous à disposition pour ce projet sont :

- La suite JetBrains avec l'IDE PhpStorm 

<h2 style="color:#5dbee7; page-break-before: always" id="priorites"> Priorités </h2>

Aucune tâche de développement n'a été définie comme prioritaire pour l'instant.