<img src="Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Dossier de spécification de la SAE S5 </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>


<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <li> <a href="#maquette"> Maquette  </a> </li>
    <li> <a href="#cas_utilisations"> Cas d'utilisations  </a>  </li>
    <ol>
        <li> <a href="#recit_utilisation"> Récit d'utilisation  </a> </li>
        <li> <a href="#cas_utilisations_detailles"> Cas d'utilisations détaillés  </a> </li>
        <ul>
        <li><a href="#cu1"> CU#1 </a></li>
        <li><a href="#cu2"> CU#2 </a></li>
        <li><a href="#cu3"> CU#3 </a></li>
        <li><a href="#cu4"> CU#4 </a></li>
        <li><a href="#cu5"> CU#5 </a></li>
        <li><a href="#cu6"> CU#6 </a></li>
        <li><a href="#cu7"> CU#7 </a></li>
        <li><a href="#cu8"> CU#8 </a></li>
        <li><a href="#cu9"> CU#9 </a></li>
        <li><a href="#cu10"> CU10 </a></li>
        <li><a href="#cu11"> CU#11 </a></li>
        <li><a href="#cu12"> CU#12 </a></li>
        <li><a href="#cu13"> CU#13 </a></li>
        </ul>
    </ol>
</ol>



<h2 style="color:#5d79e7; page-break-before: always" id="introduction"> Introduction </h2>

Ce document est réalisé à partir du cahier des charges réécrit et permet d’afficher sous forme de pages les différentes fonctionnalités du site. Le site est également disponible en accédant au serveur apache installé sur le RaspBerryPi en écrivant l’adresse suivante dans un navigateur web : 85.170.243.176:80. Il faut au préalable être connecté au réseau de l’iut.

Dans la partie “Maquettes”, il y a les liens vers les 2 maquettes sur Figma.
Dans la partie “Cas d’utilisations”, les différents cas d’utilisations du site web.

Pour rédiger ce dossier on utilisera la méthode d’Alistair Cockburn comme vu dans le cours. En effet cette méthode facilite la visualisation et la compréhension des différents cas d’utilisation et de leurs descriptions, non seulement pour les personnes qui écrivent les cas d’utilisation, mais aussi pour les personnes extérieures et n’ayant pas de notions à ce propos.

<h2 style="color:#5d79e7; page-break-before: always" id="maquette"> Maquette </h2>

Ci-dessous le lien vers la maquette de l'application.

https://www.figma.com/file/S1pEHXusOJ29ZLPUjzZCoc/BlitzCalc?type=design&t=ohczqNCd79y9zTP1-6

Avec l’outil Figma les boutons sont cliquables et permettent une première idée de la navigation sur le site. Les boutons utilisables sont les suivants : Inscription, Connexion, Retour, Valider, Mot de passe oublié, l’icône de profil, et le bouton déconnexion.  

<h2 style="color:#5d79e7; page-break-before: always" id="cas_utilisations"> Cas d'utilisations </h2>

<h3 id="recit_utilisation"> Récit d'utilisation </h3>

L'application est hébergée sur le kit Cluster Hat. Chaque noeud doit pouvoir communique entre eux et surtout communiquer avec le rpi principal via ssh. Pour se connecter en ssh à un noeud du cluster depuis le rpi principal, on précise le hostname du noeud en question qui est enregistré dans le fichier config du serveur ssh.

Le premier livrable est un système de connexion et d'inscription relié à une base de données.

Texte 1 Description textuelle des cas d’utilisations : Le site permet directement à un visiteur de s'inscrire, de se connecter via une page d’inscription ou de connexion ou d'accéder au site directement. Il permet aussi à l'utilisateur connecté de voir ses informations personnelles et de les modifier. Ces fonctionnalités correspondent aux cas d'utilisations.

Si le visiteur est déjà inscrit, il sera en mesure de se connecter (sous-fonctions) à chacune de ses visites sur le site en remplissant et en envoyant le formulaire qu’il aura rempli avec son identifiant et son mot de passe (sous-fonctions). Si l’inscrit commet une erreur en remplissant le formulaire ou qu’il n’est simplement pas inscrit alors il sera renvoyé sur le formulaire avec un message lui disant que les informations renseignées sont erronées. Après une connexion réussie, l’utilisateur inscrit se retrouvera sur une page dite principale à partir de laquelle il pourra accéder, via des boutons, à une page simulant les futurs modules de calcul disponible sur le site. Il pourra également accéder à son profil, où il sera en mesure de modifier son mot de passe, nom, prénom, identifiant et adresse mail ou bien de se déconnecter et de revenir à la page d'inscription.

Si le visiteur n’est pas inscrit, alors il devra remplir un formulaire et renseigner son identifiant et son mot de passe qu’il devra confirmer. Après son inscription validée, les données sont communiquées à la base de données et sont enregistrées. Le visiteur maintenant inscrit arrivera directement sur la page principale et pourra profiter de toutes les fonctionnalités du site. 
Si l'utilisateur se connecte avec les identifiants de l’administrateur, il n’aura pas accès aux mêmes fonctionnalités que les inscrits « classique » mais à une page exclusive.

L'utilisateur connecté peut accéder à son profil en cliquant sur l'image profil en haut à droite de la page d'accueil une fois ce dernier connecté. Un formulaire sous forme de pop-up se dévoile et affiche le login, adresse mail, nom et prénom de l'utilisateur dans plusieurs champs. L'utilisateur saisit dans le(s) champ(s) adéquat(s) le ou les nouvelles informations personnelles qu'il veut modifier, puis clique ensuite sur le bouton valider pour enregistrer ces nouvelles dernières.
Si l'utilisateur modifier son mot de passe, il doit aussi saisir le nouveau mot de passe dans deux champs, puis cliquer sur valider. 

Le deuxième livrable contient un module de calcul des nombres premiers ainsi que les fonctionnalités de l'utilisateur administrateur. <br>
Un utilisateur accède au module de calcul des nombres premiers, soit en étant déjà inscrit, ou en y accédant en étant comme non connecté. Il clique ensuite sur le module en question depuis la page principale pour accéder à la page de ce dernier.<br>
Puis, il saisit la borne minmimum _n_ et la borne maximum _m_ et clique sur valider pour calculer les nombres premiers compris entre _n_ et _m_. Par défaut, le calcul ne s'effectue que sur un seul rpi. <br>
Une fois les nombres premiers calculés, le temps d'exécution du calcul ainsi que la liste de ces derniers sont affichés dans la page du module.
Un bouton en dessous du résultat permet de passer du mode calcul non distribué au mode de calcul distribué. <br>
Si l'utilisateur qui clique sur le bouton est un visiteur, une pop-up s'affiche invitant l'utilisateur à se connecter ou à s'inscrire pour profiter de la puissance du calcul distribué, sinon il n'y a pas de pop-up, et le calcul passe en mode distribué.

Ci-dessous les différents niveaux des cas d'utilisation:
**Niveau stratégique**:
- L'utilisateur utilise des modules
- L'administrateur administre le site web

**Niveau utilisateur**:
- S'inscrire
- Supprimer son compte
- Utilisation du module de calcul des nombres premiers
- Rechercher des utilisateurs
- Rechercher des logs

**Niveau sous-fonctions**:
- Se connecter
- Se déconnecter
- Changer son login
- Changer son adresse mail
- Changer son nom
- Changer son prénom
- Visualiser les statistiques du site

<h3 style="page-break-before: always" id="cas_utilisations_detailles"> Cas d'utilisations détaillés </h3>

<h4 id="cu1"> CU#1 : S’inscrire</h4> 

**Nom** : S’inscrire\
**Contexte d’utilisation** : inscrire le visiteur lors de sa première visite sur le site\
**Portée** : page web, serveur apache, base de données\
**Niveau**: utilisateur\
**Acteur principal**: visiteur du site\
**Intervenants et intérêts** : /\
**Précondition** : base de données opérationnelles\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : le visiteur devient un inscrit\
**Déclencheur** : le visiteur clique sur le bouton inscription\
**Scénario nominal** :
1. Le site affiche la page d’accueil.

2. Le visiteur clique sur le bouton d’inscription.

3. Le site affiche la page d’inscription.

4. Le visiteur remplit le formulaire d’inscription.

5. Le visiteur appuie sur le bouton valider.

6. Les données sont transmises à la base de données puis enregistrées.

7. Le visiteur devient un utilisateur inscrit et est renvoyé vers la page principale


 
**Extension** : / \
**Liste des variantes** : \

Scénario alternatif 4.1 :
1. Le formulaire est vide
2. Un message d'erreur est affiché

Scénario alternatif 6.1 :
1. Le visiteur choisit une adresse mail déjà enregistrée
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.2 :
1. Le visiteur choisit un login déjà enregistré
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.3 :
1. Le visiteur saisit une adresse mail, login, nom ou prénom avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.4 :
1. Le visiteur saisit une adresse mail dont la taille n'est pas valide
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.5 :
1. Le visiteur saisit un login, nom ou prénom dont la taille n'est pas valide
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur
	
Scénario alternatif 6.6 :
1. Le visiteur se trompe en remplissant une deuxième fois son mot de passe
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.7 :
1. Le visiteur saisit deux mot passes différents
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.8 :
1. Le visiteur saisit un mot de passe trop facile à deviner
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur

Scénario alternatif 6.9 :
1. Le visiteur est une personne mal intentionnée et essaie d’injecter du code à travers le formulaire
2. Le serveur détecte l’injection et repousse la tentative
3. Le visiteur est redirigé sur la page d’inscription avec un message d'erreur


**Informations connexes** : /


<h4 id="cu2"> CU#2 : Se connecter</h4> 

**Nom** : L’inscrit se connecte \
**Contexte d’utilisation** : Un utilisateur inscrit souhaite se servir du site\
**Portée** : site web, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et enregistré dans la base de données</u> **(CU#1)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L’utilisateur est connecté\
**Déclencheur** : L’inscrit clique sur le bouton connexion\
**Scénario nominal** :
1. Le site affiche la page d’accueil.

2. L’inscrit clique sur le bouton connexion.

3. Le site affiche la page de connexion.

4. L’inscrit remplit le formulaires.

5. L’inscrit appuie sur le bouton valider pour se connecter.

6. Les données sont transmises à la base de données

7. La base de données confirme l’authenticité des identifiants de connexion     

8. Le site affiche la page principale.
        

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 4.1 :
1. Le formulaire est vide
2. Un message d'erreur est affiché

Scénario alternatif 6.1 :
1. Le visiteur saisit son login avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page connexion avec un message d'erreur

Scénario alternatif 6.2 :
1. Le visiteur saisit son login ou mot de passe dont la taille n'est pas valide
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page de connexion avec un message d'erreur

Scénario alternatif 6.3 :
1. L’utilisateur se trompe en remplissant son mot de passe
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion
    
Scénario alternatif 6.4 :
1. L’utilisateur se trompe en remplissant son identifiant
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion

Scénario alternatif 6.5 :
1. L’utilisateur est une personne mal intentionnée et essaie d’injecter du code à travers le formulaire
2. Le serveur détecte l’injection et repousse la tentative
3. Le visiteur est redirigé sur la page de connexion

Scénario alternatif 6.6 :
1. L’utilisateur se trompe en remplissant son nom
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion

**Informations connexes** : /


<h4 id="cu3"> CU#3 : Changer son mot de passe</h4> 

**Nom** : Changement de son mot de passe par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le mot de passe est modifié\
**Déclencheur** : l’inscrit clique sur le bouton valider les changements\
**Scénario nominal** :

1. L’inscrit remplit les deux formulaires avec le nouveau mot de passe
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 1.1 :
1. Le formulaire est vide
2. L’inscrit est redirigé vers son profil
3. Un message d'erreur est affiché

Scénario alternatif 3.1 :
1. La taille du mot de passe saisi par l'inscrit n'est pas valide
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.2 :
1. Le mot de passe est similaire à l’ancien mot de passe
2. Le serveur refuse le changement de mot de passe
3. L’inscrit est redirigé sur la page profil
4. Un message lui disant de ne pas choir le même mot de passe est affiché

Scénario alternatif 3.3 :
1. Les deux mots de passe renseignés sont différents
2. Le serveur refuse le changement de mot de passe
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur lui disant que les deux mots de passe sont différents est affiché

Scénario alternatif 3.4 :
1. Le mot de passe est trop fragile
2. Le serveur refuse le changement de mot de passe
3. L’inscrit est redirigé sur la page profil
4. Un message lui disant que le mot de passe est fragile

Scénario alternatif 3.5 :
1. La requête pour le mot de passe dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /

<h4 id="cu4"> CU#4 : Changer son adresse mail</h4> 

**Nom** : Changement de son adresse mail par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'adresse mail est modifiée\
**Déclencheur** : l’inscrit clique sur le bouton valider les changements\
**Scénario nominal** :


1. L’inscrit remplit le formulaire avec la nouvelle adresse mail
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 1.1 :
1. Le formulaire est vide
2. L’inscrit est redirigé vers son profil
3. Un message d'erreur est affiché

Scénario alternatif 3.1 :
1. L'inscrit saisit une adresse mail avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page connexion avec un message d'erreur

Scénario alternatif 3.2 :
1. La taille de l'adresse mail saisie par l'inscrit n'est pas valide
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.3 :
1. L'adresse mail est similaire à l’ancienne adresse mail et ou déjà prise
2. Le serveur refuse le changement d'adresse mail
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.4 :
1. La requête pour changer l'adresse mail dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /


<h4 id="cu5"> CU#5 : Changer son login</h4> 

**Nom** : Changement de son identifiant par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'identifiant est modifié\
**Déclencheur** : l’inscrit clique sur le bouton valider les changements\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouveau login
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 1.1 :
1. Le formulaire est vide
2. L’inscrit est redirigé vers son profil
3. Un message d'erreur est affiché

Scénario alternatif 3.1 :
1. L'inscrit saisit un login avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.2 :
1. La taille du login saisi par l'inscrit n'est pas valide
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.3 :
1. Le login est similaire à l'ancien login et ou déjà pris
2. Le serveur refuse le changement du login
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.4 :
1. La requête pour changer le login dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /


<h4 id="cu6"> CU#6 : Changer son nom</h4> 

**Nom** : Changement de son nom par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le nom est modifié\
**Déclencheur** : l’inscrit clique sur le bouton valider les changements\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouveau nom
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 1.1 :
1. Le formulaire est vide
2. L’inscrit est redirigé vers son profil
3. Un message d'erreur est affiché

Scénario alternatif 3.1 :
1. L'inscrit saisit un nom avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.2 :
1. La taille du nom saisi par l'inscrit n'est pas valide
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.3 :
1. La requête pour changer le nom dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /

<h4 id="cu7"> CU#7 : Changer son prénom</h4> 

**Nom** : Changement de son prénom par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le prénom est modifié\
**Déclencheur** : l’inscrit clique sur le bouton valider les changements\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouveau prénom
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 1.1 :
1. Le formulaire est vide
2. Un message d'erreur est affiché

Scénario alternatif 3.1 :
1. L'inscrit saisit le prénom avec des caractères spéciaux
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.2 :
1. La taille du prénom saisi par l'inscrit n'est pas valide
2. Le serveur refuse l’insertion des données
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché

Scénario alternatif 3.3 :
1. La requête pour changer le prénom dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /

<h4 id="cu8"> CU#8 : Supprimer son compte</h4> 

**Nom** : Changement de son prénom par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le compte est supprimé\
**Déclencheur** : l’inscrit clique sur le bouton supprimer son compte\
**Scénario nominal** :

1. L'inscrit clique sur l'image de profil
        
2. L’inscrit clique sur le bouton supprimer son compte

3. Les données sont transmises à la base de données

4. La base de données modifie les données

5. Il devient visiteur et est redirigé vers la page d'accueil


**Extension** : /\
**Liste des variantes** : 

Scénario alternatif 3.1 :
1. La requête pour supprimer le compte dans la base de données à échouée
2. L'erreur est enregistrée à l'aide d'un logger
3. L’inscrit est redirigé vers son profil
4. Un message d'erreur est affiché à l'utilisateur

**Informations connexes** : /

<h4 id="cu9"> CU#9 : Utilisation du module de calcul des nombres premiers par un utilisateur visiteur</h4> 

**Nom** : L'utilisateur inscrit utilise le module de calcul des nombres premiers\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur\
**Précondition** : /\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'utilisateur visualise les nombres premiers calculés \
**Déclencheur** : L'utilisateur clique sur le module \
**Scénario nominal** :

1. L'utilisateur se connecte et clique sur le module depuis la page principale

2. Le site affiche la page du module

3. L'utilisateur remplit 2 champs pour les bornes n et m pour calculer les nombre premiers situés entre n et m

4. L'utilisateur choisit le mode de calcul non distribué

5. L'utilisateur clique sur le bouton calculer
        
6. Le site exécute un script qui effectue le calcul des nombres premiers de manière distribué sur le rpi zero

7. Le site affiche le temps d'exécution du calcul des nombres premiers ainsi que la liste de ces derniers

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 4.1:
- L'utilisateur choisit le mode de calcul distribué
- Le mode n'est pas activé
- Une pop-up s'affiche invitant l'utilisateur à s'inscrire, se connecter
- L'utilisateur se connecte, s'inscrit ou ferme la pop-up

Scénario alternatif 5.1:
- L'utilisateur a entré des valeurs incorrectes pour les bornes n et m : n < 0 ou m <= n ou m > valeur maximale
- Le script de calcul n'est pas exécuté
- Le site affiche un message d'erreur à l'utilisateur

Scénario alternatif 6.1:
- Les rpi zeros ne sont pas accessibles en mode connexion ssh
- Le script s'arrête prématurément et renvoie une erreur
- Le site affiche une erreur à l'utilisateur 

**Informations connexes** : /

<h4 id="cu10"> CU#10 : Utilisation du module de calcul des nombres premiers par un utilisateur inscrit</h4> 

**Nom** : L'utilisateur inscrit utilise le module de calcul des nombres premiers\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur\
**Précondition** : <u>Être inscrit sur le site et s’être connecté</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'utilisateur visualise les nombres premiers calculés \
**Déclencheur** : L'utilisateur clique sur le module \
**Scénario nominal** :

1. L'utilisateur appuie sur le bouton "essayer sans inscription" depuis la page d'accueil

2. Le site affiche la page d'accueil avec les modules

3. L'utilisateur clique sur le module de calcul des nombres premiers

4. Le site affiche la page du module

5. L'utilisateur remplit 2 champs pour les bornes n et m pour calculer les nombre premiers situés entre n et m

6. L'utilisateur choisit le mode de calcul distribué

7. L'utilisateur clique sur le bouton calculer
        
8. Le site exécute un script qui effectue le calcul des nombres premiers de manière distribué sur les 4 rpi zeros

9. Le site affiche le temps d'exécution du calcul des nombres premiers ainsi que la liste de ces derniers

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 5.1:
- L'utilisateur a entré des valeurs incorrectes pour les bornes n et m : n < 0 ou m <= n ou m > valeur maximale
- Le script de calcul n'est pas exécuté
- Le site affiche un message d'erreur à l'utilisateur

Scénario alternatif 8.1:
- Les rpi zeros ne sont pas accessibles en mode connexion ssh
- Le script s'arrête prématurément et renvoie une erreur
- Le site affiche une erreur à l'utilisateur 

Scénario alternatif 8.2:
- Le mode de calcul est sur non distribué, le calcul ne s'effectue que sur un seul rpi

**Informations connexes** : /

<h4 id="cu11"> CU#11 : Visualiser les statistiques du site</h4> 

**Nom** : L'administrateur visualise les statistiques du site\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : administrateur\
**Précondition** : <u>Être inscrit sur le site et s’être connecté en tant qu'administrateur</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'administrateur visualise les statistiques du site \
**Déclencheur** : L'administrateur se connecte\
**Scénario nominal** :

1. L'administrateur clique sur le bouton lié à la période de temps souhaitée (Jour, Semaine, Mois, Tout) pour visualier les statistiques

2. Le site exécute des scripts pour récupérer ces statistiques en fonction de la période choisie

3. Le site affiche les statistiques renvoyées par les scripts

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 2.1:
- Une erreur est survenue lors de l'exécution d'un des scripts
- Le script s'arrête prématurément et renvoie une erreur
- Le site affiche ne change pas les statistiques et affiche une erreur à l'administrateur

**Informations connexes** : /

<h4 id="cu12"> CU#12 : Rechercher des utilisateurs</h4> 

**Nom** : L'administrateur recherche des utilisateurs\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : utilisateur\
**Acteur principal** : administrateur\
**Précondition** : <u>Être inscrit sur le site et s’être connecté en tant qu'administrateur</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'administrateur visualise les utilisateurs  \
**Déclencheur** : L'administrateur clique sur le bouton rechercher des utilisateurs\
**Scénario nominal** :

1. Le site affiche une pop-up avec un formulaire

2. L'administrateur sélectionne sur quel attribut de l'utilisateur effectuer la recherche

3. L'administrateur entre dans un champ la chaîne de caractères à rechercher

4. L'administrateur clique sur le bouton rechercher

5. Le site exécute un script qui recherche les utilisateurs dans la base de données en fonction des valeurs saisies par l'administrateur

6. Le site affiche les différents utilisateurs de cette recherche

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 4.1:
- L'administrateur essaie de sélectionner un attribut qui ne définit par un utilisateur
- Un message d'erreur est affiché à l'administrateur

Scénario alternatif 4.1:
- La taille de la chaîne de caractères est incorrecte
- Un message d'erreur est affiché à l'administrateur

Scénario alternatif 4.2:
- La chaîne de caractères contient des caractères spéciaux
- Un message d'erreur est affiché à l'utilisateur

Scénario alternatif 5.1:
- Une erreur est survenue lors de l'exécution du script
- La recherche est annulée
- Un message d'erreur est affiché à l'administrateur

**Informations connexes** : /

<h4 id="cu13"> CU#13 : Rechercher des logs</h4> 

**Nom** : L'administrateur recherche des logs (journaux)\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : site web, base de données\
**Niveau** : utilisateur\
**Acteur principal** : administrateur\
**Précondition** : <u>Être inscrit sur le site et s’être connecté en tant qu'administrateur</u> **(CU#2)**\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'administrateur visualise les logs  \
**Déclencheur** : L'administrateur clique sur le bouton rechercher des logs\
**Scénario nominal** :

1. Le site affiche une pop-up avec un formulaire

2. L'administrateur sélectionne un attribut de recherche parmis une liste d'attributs définissant un log

3. L'administrateur entre dans un champ la chaîne de caractères à rechercher

4. L'administrateur clique sur le bouton rechercher

5. Le site exécute un script qui recherche les logs dans la base de données en fonction des mots-clés et valeurs saisies par l'administrateur

6. Le site affiche les différents logs de cette recherche

**Extension** : /\
**Liste des variantes** : \

Scénario alternatif 4.1:
- L'administrateur essaie de sélectionner un mot clé qui ne définit pas un log
- Un message d'erreur est affiché à l'administrateur

Scénario alternatif 4.2:
- La taille d'une valeur associé à un mot-clé est incorrecte
- Un message d'erreur est affiché à l'administrateur

Scénario alternatif 4.3:
- Des caractères spéciaux sont présents dans la chaîne à rechercher
- Un message d'erreur est affiché à l'utilisateur

Scénario alternatif 5.1:
- Une erreur est survenue lors de l'exécution du script
- La recherche est annulée
- Un message d'erreur est affiché à l'administrateur

**Informations connexes** : /