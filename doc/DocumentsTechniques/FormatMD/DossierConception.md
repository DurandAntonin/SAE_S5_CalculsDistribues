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
| page_profil.php                          |                                                             |                                        |
| Pagination.php                           |                                                             |                                        |
| traitement_connexion.php                 |                                                             |                                        |
| traitement_inscription.php               |                                                             |                                        |
| traitement_profil.php                    |                                                             |                                        |
| User.php                                 |                                                             |                                        |
| Utility.php                              |                                                             |                                        |
| verif_identite_page_admin.php            |                                                             |                                        |
| verif_identite_page_user_inscription.php |                                                             |                                        |
| verif_identite_page_user.php             |                                                             |                                        |
| index.html                               |                                                             |                                        |
| Serveur Web                              | A tous les objets .php, .html, .css, .json, a une interface | Ecoute les requêtes du navigateur      |
| Navigateur Web                           |                                                             | Lit les fichiers, exécute des requêtes |
| Base de données                          | a les tables Friends, User_activity, Users, Weak_passwords  |                                        |



A partir de ce tableau, on peut définir les composants suivants : "page_accueil.php", "page_connexion.php", "page_inscription.php", "page_accueil_user.php", "page_accueil_admin.html", "style.css", "Enum_fic_logs.php", "Enum_niveau_logger.php","Enum_role_user.php","Enum_role_user.php","Logger.php","LoggerInstance.php","Logging.php","MYSQLDataManagement.php","page_deconnexion.php","page_profil.php","Pagination.php","traitement_connexion.php","traitement_inscription.php","traitement_profil.php","User.php","Utility.php","verif_identite_page_admin.php","verif_identite_page_user_inscription.php","verif_identite_page_user.php","index.html". 

Ces derniers sont des abstractions respectives des objets : "page_accueil.php", "page_connexion.php", "page_inscription.php", "page_accueil_user.php", "page_accueil_admin.html", "style.css", "Enum_fic_logs.php", "Enum_niveau_logger.php","Enum_role_user.php","Enum_role_user.php","Logger.php","LoggerInstance.php","Logging.php","MYSQLDataManagement.php","page_deconnexion.php","page_profil.php","Pagination.php","traitement_connexion.php","traitement_inscription.php","traitement_profil.php","User.php","Utility.php","verif_identite_page_admin.php","verif_identite_page_user_inscription.php","verif_identite_page_user.php","index.html".

On définit ensuite le composant "Navigateur", abstraction de l'objet "Navigateur web", qui correspond au client voulant accèder au site web. On définit également le composant "Serveur", abstraction de l'objet "Serveur web". Enfin, on définit le composant "Base de données", abstraction de l'objet "Base de données". 

On peut constater que certains composants d’objets sont similaires, on externalise donc le style de chaque page pour le mettre dans un objet, fichier appelé “style.charte.css”. Cela va être montré dans la partie conception 
détaillée. 

On crée aussi 3 packages, le premier appelé CSS contenant l'objet « style.css », le 
deuxième appelé HTML contenant l'objet « index.html, le troisième intitulé PHP contenant tous les objets en .php.

On représente dans un premier diagramme de classes ci-dessous les dépendances et relations 
entre les objets du 1er, 2ème et 3ème packages. 

Dans un deuxième diagramme uml, on représente les relations entre “BD” et "Friends", 
"User_activity","Users","Weak_passwords". 










<h3 style="color:#5d79e7; id=concepDeta"> Conception détaillée </h3> 