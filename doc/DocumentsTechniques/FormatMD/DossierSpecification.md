<img src="Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Dossier de spécification de la SAE S5 </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>


<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <li> <a href="#maquettes"> Maquettes  </a> </li>
    <li> <a href="#pres_requis"> Cas d'utilisations  </a>  </li>
    <ol>
        <li> <a href="#connaissances_competences"> Récit d'utilisation  </a> </li>
        <li> <a href="#ressources_materielles"> Cas d'utilisation détaillé  </a> </li>
    </ol>
</ol>



<h2 style="color:#5dbee7; page-break-before: always" id="introduction"> Introduction </h2>

Ce document est réalisé à partir du cahier des charges réécrit et permet d’afficher sous forme de pages les différentes fonctionnalités du site. Le site est également disponible en accédant au serveur apache installé sur le RaspBerryPi en écrivant l’adresse suivante dans un navigateur web : 85.170.243.176:80. Il faut au préalable être connecté au réseau de l’iut.

Dans la partie “Maquettes”, il y a les liens vers les 2 maquettes sur Figma.
Dans la partie “Cas d’utilisations”, les différents cas d’utilisations du site web.

Pour rédiger ce dossier on utilisera la méthode d’Alistair Cockburn comme vu dans le cours. En effet cette méthode facilite la visualisation et la compréhension des différents cas d’utilisation et de leurs descriptions, non seulement pour les personnes qui écrivent les cas d’utilisation, mais aussi pour les personnes extérieures et n’ayant pas de notions à ce propos.

<h2 style="color:#5dbee7; page-break-before: always" id="maquettes"> Maquettes </h2>

Voici ci-dessous le lien vers la première maquette réalisée :
https://www.figma.com/proto/qMbHJtZuRbIjwsbW98pGr0/Untitled?node-id=3%3A20&scaling=scale-down&page-id=0%3A1&starting-point-node-id=3%3A20

Voici ci-dessous le lien vers la deuxième maquette réalisée :
https://www.figma.com/proto/JJHEWjiEuGdnE0jZbyxsgo/page_accueil?node-id=21%3A53&scaling=min-zoom&page-id=0%3A1&starting-point-node-id=18%3A29

Avec l’outil Figma les boutons sont cliquables et permettent une première idée de la navigation sur le site. Les boutons utilisables sont les suivants : Inscription, Connexion, Retour, Valider, Mot de passe oublié, l’icône de profil, et le bouton déconnexion.  

<h2 style="color:#5dbee7; page-break-before: always" id="cas_utilisation"> Cas d'utilisations </h2>

<h2 style="color:#5dbee7; page-break-before: always" id="recit_utilisation"> Récit d'utilisation </h2>

Le premier livrable est un système de connexion et d'inscription relié à une base de données.

Texte 1 Description textuelle des cas d’utilisations : Le site permet directement à un visiteur de s'inscrire ou bien de se connecter via une page d’inscription ou de connexion. Ces deux choix correspondent à deux cas d’utilisation différents, le visiteur est déjà inscrit ou non.

Si le visiteur est déjà inscrit, il sera en mesure de se connecter (sous-fonctions) à chacune de ses visites sur le site en remplissant et en envoyant le formulaire qu’il aura rempli avec son identifiant et son mot de passe (sous-fonctions). Si l’inscrit commet une erreur en remplissant le formulaire ou qu’il n’est simplement pas inscrit alors il sera renvoyé sur le formulaire avec un message lui disant que les informations renseignées sont erronées. Après une connexion réussie, l’utilisateur inscrit se retrouvera sur une page dite principale à partir de laquelle il pourra accéder, via des boutons, à une page simulant les futurs modules de calcul disponible sur le site. Il pourra également accéder à son profil, où il sera en mesure de modifier son mot de passe, nom, prénom, identifiant et adresse mail ou bien de se déconnecter et de revenir à la page d'inscription.

Si le visiteur n’est pas inscrit, alors il devra remplir un formulaire et renseigner son identifiant et son mot de passe qu’il devra confirmer. Après son inscription validée, les données sont communiquées à la base de données et sont enregistrées. Le visiteur maintenant inscrit arrivera directement sur la page principale et pourra profiter de toutes les fonctionnalités du site. 

Si l'utilisateur se connecte avec les identifiants de l’administrateur, il n’aura pas accès aux mêmes fonctionnalités que les inscrits « classique » mais à une page exclusive.

<h2 style="color:#5dbee7; page-break-before: always" id="cas_detaille"> Cas d'utilisation détaillé </h2>

### Cas d’utilisation 1 ==> S’inscrire :

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


 
**Extension** : / \
**Liste des variantes** : \
Scénario alternatif 1 :
1. Le visiteur choisit une adresse mail déjà enregistrée
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription
	
Scénario alternatif 2 :
1. Le visiteur se trompe en remplissant une deuxième fois son mot de passe
2. Le serveur refuse l’insertion des données
3. Le visiteur est redirigé sur la page d’inscription

Scénario alternatif 3 :
1. Le visiteur est une personne mal intentionnée et essaye d’injecter du code à travers le formulaire
2. Le serveur détecte l’injection et repousse la tentative
3. Le visiteur est redirigé sur la page d’inscription


**Informations connexes** : /





### Cas d’utilisation 2 ==> Se connecter :

**Nom** : L’inscrit se connecte \
**Contexte d’utilisation** : Un utilisateur inscrit souhaite se servir du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : sous-fonction\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et enregistré dans la base de données\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L’utilisateur est connecté\
**Déclencheur** : L’inscrit clique sur le bouton connexion\
**Scénario nominal** :
1. Le site affiche la page d’accueil.

2. L’inscrit clique sur le bouton connexion.

3. Le site affiche la page de connexion.

4. L’inscrit remplit les formulaires.

5. L’inscrit appui sur le bouton valider pour se connecter.

6. Les données sont transmises à la base de données

7. La base de données confirme l’authenticité des identifiants de connexion     

8. Le site affiche la page principale.
        

**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. L’utilisateur se trompe en remplissant son mot de passe
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion
    
Scénario alternatif 2 :
1. L’utilisateur se trompe en remplissant son identifiant
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion

Scénario alternatif 3 :
1. L’utilisateur est une personne mal intentionnée et essaye d’injecter du code à travers le formulaire
2. Le serveur détecte l’injection et repousse la tentative
3. Le visiteur est redirigé sur la page de connexion

Scénario alternatif 4 :
1. L’utilisateur se trompe en remplissant son nom
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion
    
Scénario alternatif 5 :
1. L’utilisateur se trompe en remplissant son prénom
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion

Scénario alternatif 6 :
1. L’utilisateur se trompe en remplissant son adresse mail
2. La base de données ne reconnaît pas les identifiant de connexion
3. Le visiteur est redirigé sur la page de connexion

**Informations connexes** : /


### Cas d’utilisation 3 ==> Changer son mot de passe :

**Nom** : Changement de son mot de passe par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et s’être connecter\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le mot de passe est modifié\
**Déclencheur** : l’inscrit clique sur le bouton de profil\
**Scénario nominal** :

1. L’inscrit remplit les deux formulaires avec le même nouveau mot de passe
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. Le mot de passe est similaire à l’ancien mot de passe
2. Le serveur refuse le changement de mot de passe
3. L’inscrit est redirigé sur la page de changement de mot de passe
4. Un message lui disant de ne pas choir le même mot de passe est affiché

Scénario alternatif 2 :
1. Les deux mots de passe renseignés sont différents
2. Le serveur refuse le changement de mot de passe
3. L’inscrit est redirigé sur la page de changement de mot de passe
4. Un message lui disant que les mots de passe étaient différents est affiché

**Informations connexes** : /


### Cas d’utilisation 4 ==> Changer son adresse mail :

**Nom** : Changement de son adresse mail par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et s’être connecter\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'adresse mail est modifiée\
**Déclencheur** : l’inscrit clique sur le bouton de profil\
**Scénario nominal** :


1. L’inscrit remplit le formulaire avec la nouvelle adresse mail
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. L'adresse mail est similaire à l’ancienne adresse mail
2. Le serveur refuse le changement d'adresse mail
3. L’inscrit est redirigé sur la page de profil

**Informations connexes** : /


### Cas d’utilisation 5 ==> Changer son identifiant:

**Nom** : Changement de son identifiant par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et s’être connecter\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : L'identifiant est modifié\
**Déclencheur** : l’inscrit clique sur le bouton de profil\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouvel identifiant
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. L'identifiant est similaire à l'ancien identifiant
2. Le serveur refuse le changement de l'identifiant
3. L’inscrit est redirigé sur la page de profil

**Informations connexes** : /


### Cas d’utilisation 6 ==> Changer son nom:

**Nom** : Changement de son nom par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et s’être connecter\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le nom est modifié\
**Déclencheur** : l’inscrit clique sur le bouton de profil\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouveau nom
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. Le nom est similaire à l'ancien nom
2. Le serveur refuse le changement du nom
3. L’inscrit est redirigé sur la page de profil

**Informations connexes** : /

### Cas d’utilisation 7 ==> Changer son prénom:

**Nom** : Changement de son prénom par un utilisateur inscrit\
**Contexte d’utilisation** : Utilisation normale du site\
**Portée** : page web, serveur apache, base de données\
**Niveau** : utilisateur\
**Acteur principal** : utilisateur inscrit\
**Intervenants et intérêts** : /\
**Précondition** : Être inscrit sur le site et s’être connecter\
**Garantie minimale** : pas de garantie\
**Garantie de succès** : Le prénom est modifié\
**Déclencheur** : l’inscrit clique sur le bouton de profil\
**Scénario nominal** :

1. L’inscrit remplit le formulaire avec le nouveau nom
        
2. L’inscrit clique sur le bouton valider

3. Les données sont transmises à la base de données

4. La base de données modifie les données


**Extension** : /\
**Liste des variantes** : \
Scénario alternatif 1 :
1. Le prénom est similaire à l'ancien prénom
2. Le serveur refuse le changement du prénom
3. L’inscrit est redirigé sur la page de profil

**Informations connexes** : /

