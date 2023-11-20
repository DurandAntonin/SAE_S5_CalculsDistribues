<img src="Images/logoUvsq.jpg" width="500">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Dossier de conception </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>

<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <li> <a href="#livr1"> Conception Livrable 1 </a> </li>
    <ol>
        <li> <a href="#concepArchi"> Conception architecturale </a> </li>
        <li> <a href="#concepDeta"> Conception détaillée </a> </li>
    </ol>
</ol>



<h2 style="color:#5d79e7; id=introduction"> Introduction </h2>

Ce document est un dossier de conception divisé en plusieurs parties. Il sera constitué de la conception architecturale et détaillée de chaque livrable. Il aura pour but de donner de renseigner toutes les informations sur la conception du site ainsi que des diagrammes permettant de la représenter graphiquement. 

Le premier livrable consiste en la réalisation des bases du site web, à savoir les pages principales. Il consiste également en la configuration du kit cluster hat, et en l'installation du site web sur ce dernier. 

<h2 style="color:#5d79e7; id=livr1"> Conception Livrable 1 </h2>

<h3 style="color:#5d79e7; id=concepArchi"> Conception architecturale </h3>

Le domaine du problème est le site web BlitzCalc, on décompose donc ce système en sous-systèmes. 

On définit ci-dessous une conception architecturale du site web avec : le serveur web côté serveur, le navigateur web côté client et la base de données. 

On utilise donc l'approche orientée objet pour repérer les différents objets du problème. On utilise également la vue composant-connecteur pour représenter le système ainsi que l'ensemble des composants du système d'un point de vue statique.

On introduit également le serveur, le navigateur web et la base de données. 

Le composant serveur est dans le kit cluster hat et le navigateur est un composant présent dans les machines des utilisateurs. Le composant base de données quant à lui est installé sur le kit cluster hat. 

| Objet                                    | Etat                                                        | Comportement                           |
|------------------------------------------|-------------------------------------------------------------|----------------------------------------|
| page_inscription.php                     |                                                             |                                        |
| page_connexion.php                       |                                                             |                                        |
| page_accueil.php                         |                                                             |                                        |
| page_accueil_user.php                    |                                                             |                                        |
| page_accueil_admin.php                   |                                                             |                                        |
| style.css                                |                                                             |                                        |
| Enum_fic_logs.php                        |                                                             |                                        |
| Enum_niveau_logger.php                   |                                                             |                                        |
| Enum_role_user.php                       |                                                             |                                        |
| Logger.php                               |                                                             |                                        |
| LoggerInstance.php                       |                                                             |                                        |
| Logging.php                              |                                                             |                                        |
| MYSQLDataManagement.php                  |                                                             |                                        |
| page_deconnexion.php                     |                                                             |                                        |
| traitement_connexion.php                 |                                                             |                                        |
| traitement_inscription.php               |                                                             |                                        |
| traitement_profil.php                    |                                                             |                                        |
| User.php                                 |                                                             |                                        |
| Utility.php                              |                                                             |                                        |
| verif_identite_page_admin.php            |                                                             |                                        |
| verif_identite_page_user_inscrit_only.php|                                                             |                                        |
| verif_identite_page_user.php             |                                                             |                                        |
| index.html                               |                                                             |                                        |
| Serveur Web                              | A tous les objets .php, .html, .css, .json, a une interface | Ecoute les requêtes du navigateur      |
| Navigateur Web                           |                                                             | Lit les fichiers, exécute des requêtes |
| Base de données                          | a les tables Logging, Users, Weak_passwords  |                                        |



A partir de ce tableau, on peut définir les composants suivants : "page_accueil.php", "page_connexion.php", "page_inscription.php", "page_accueil_user.php", "page_accueil_admin.html", "style.css", "Enum_fic_logs.php", "Enum_niveau_logger.php","Enum_role_user.php","Enum_role_user.php","Logger.php","LoggerInstance.php","Logging.php","MYSQLDataManagement.php","page_deconnexion.php","Pagination.php","traitement_connexion.php","traitement_inscription.php","traitement_profil.php","User.php","Utility.php","verif_identite_page_admin.php","verif_identite_page_user_inscrit_only.php","verif_identite_page_user.php","index.html". 

Ces derniers sont des abstractions respectives des objets : "page_accueil.php", "page_connexion.php", "page_inscription.php", "page_accueil_user.php", "page_accueil_admin.html", "style.css", "Enum_fic_logs.php", "Enum_niveau_logger.php","Enum_role_user.php","Enum_role_user.php","Logger.php","LoggerInstance.php","Logging.php","MYSQLDataManagement.php","page_deconnexion.php","Pagination.php","traitement_connexion.php","traitement_inscription.php","traitement_profil.php","User.php","Utility.php","verif_identite_page_admin.php","verif_identite_page_user_inscrit_only.php","verif_identite_page_user.php","index.html".

On définit ensuite le composant "Navigateur", abstraction de l'objet "Navigateur web", qui correspond au client voulant accèder au site web. On définit également le composant "Serveur", abstraction de l'objet "Serveur web". Enfin, on définit le composant "Base de données", abstraction de l'objet "Base de données". 

On peut constater que certains composants d’objets sont similaires, on externalise donc le style de chaque page pour le mettre dans un objet, fichier appelé “style.charte.css”. Cela va être montré dans la partie conception 
détaillée. 

On crée aussi 3 packages, le premier appelé CSS contenant l'objet « style.css », le 
deuxième appelé HTML contenant l'objet « index.html, le troisième intitulé PHP contenant tous les objets en .php.

On représente les relations entre “BD” et 
"Logging","Users","Weak_passwords". 

<div style="text-align:center">
<img src="../DiagrammesUML/PNG_DiagrammesUML/L1_diagBD.PNG" width="700px">
<p> Diagramme BD du livrable 1 </p>
</div>

Ensuite, représente les relations entre les packages dans le diagramme de packages ci-dessous.

<div style="text-align:center">
<img src="../DiagrammesUML/PNG_DiagrammesUML/L1_diagPackages.PNG" width="700px">
<p> Diagramme BD du livrable 1 </p>
</div>

On va utiliser la vue composants-connecteurs pour modéliser le système.   

Les composants “Serveur” et “Navigateur” sont de stéréotype “executable”, le composant “DB” est de stéréotype “database” avant les composants “utilisateur”, “utilisation_module” qui sont de stéréotype “table” et enfin tous les autres composants sont de stéréotype “file”.  

On a la même interface nommée « IRequeteWeb » entre « Navigateur » et « Serveur ». On a des relations de dépendances entre « Serveur » et les composants de stéréotype « file ». De même entre le “Navigateur” et les composants de stéréotype “html”.  

Le composant « Serveur » interprète les composants en .php et envoi les fichiers html correspondants au « Navigateur ».  

On a une interface entre les composants “Navigateur” et “Serveur” que l’on va nommer 
“IRequeteWeb”. On a des relations de dépendances entre “Serveur” et les composants de stéréotype “file”, de même entre le “Navigateur” et les composants de stéréotype html.  

On modélise cette vue à l’aide d’un diagramme de composants ci-dessous :

On a une interface que l’on va nommer "MySqli" reliant le composant “MySQLDataManagement.php” avec le composant “Base de données”. 

<div style="text-align:center">
<img src="../DiagrammesUML/PNG_DiagrammesUML/L1_diagComposants.PNG" width="700px">
<p> Diagramme de composants du livrable 1 </p>
</div>

L’étude du système ainsi que sa modélisation à l’aide de la vue composant-connecteur nous a 
permis de représenter le système qui est le site web. Nous allons représenter maintenant le système du point de vue de son déploiement à l’aide de la vue d’attribution.  

Le serveur, la base de données et les pages sont stockées dans une machine Kit Cluster Hat qu’on va appeler "Serveur Kit Cluster Hat" contenant ces composants. De plus, comme le site web doit être accessible pour tous les ordinateurs étant sur le même réseau que la machine "Serveur Kit Cluster Hat", on a le composant Navigateur ainsi que les pages web envoyées par le serveur qui sont dans la machine du client, qu’on va appeler Client Web. Il peut y avoir 0 ou n Client Web car plusieurs utilisateurs peuvent se connecteur au serveur. Il peut également y avoir 0 ou n "Serveur Kit Cluster Hat", car des réplicas de ce serveur peuvent être créées.  

Enfin, les 2 machines sont reliées par une connexion internet, représentée par une relation d’association dans le diagramme.

<div style="text-align:center">
<img src="../DiagrammesUML/PNG_DiagrammesUML/L1_diagDeploiement.PNG" width="700px">
<p> Diagramme de deploiement du livrable 1 </p>
</div>

<h3 style="color:#5d79e7; id=concepDeta"> Conception détaillée </h3> 

On analyse maintenant comment sont constitués les objets en .php, à savoir "page_accueil_admin.php", "page_accueil_user.php", "page_connexion.php", "page_deconnexion.php", "page_inscription.php".

La page "page_deconnexion.php" ne contient pas d'html et n'apparaitra jamais sur l'écran de l'utilisateur, elle permet uniquemement de déconnecter la session de l'utilisateur. 

La page "page_accueil_admin.php" ne contient qu'un lien de déconnexion contenu dans un ```<nav>```. Elle contient aussi un ```<h2>``` dans le ```<main>``` qui affiche le nom de l'admin. 

La page "page_accueil_user.php" ne contient qu'un lien de déconnexion contenu dans un ```<nav>```. Elle contient aussi un ```<h2>``` dans le ```<main>``` qui affiche le nom de l'utilisateur. 

La page "page_connexion.php" contient deux formulaires ```<form>```. Le premier contient deux ```<div>``` et 

La page "page_inscription.php"