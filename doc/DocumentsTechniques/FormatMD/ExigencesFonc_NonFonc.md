<img src="Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Exigences fonctionnelles et non fonctionnelles </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>

<ol>
    <li> <a href="#introduction"> Introduction  </a> </li>
    <li> <a href="#exigences_fonc"> Exigences fonctionnelles  </a>  </li>
    <li> <a href="#exigences_nonfonc"> Exigences non fonctionnelles  </a> </li>
</ol>




<h2 style="color:#5d79e7; page-break-before: always" id="introduction"> Introduction </h2>

Ce document rassemble l'ensemble des exigences fonctionnelles et non fonctionnelles de notre site. 

Les exigences fonctionnelles correspondent à l'ensemble des fonctionnalités que doit possèder. Elles modélisent les intéractions entre le site et l'utiisateur et les réactions du site en fonction des actions de l'utilisateur. Elles doivent donc exprimer ce que le système doit faire de manière simple et compréhensible. 

Les exigences non fonctionnelles permettent de spécifier la manière dont le site doit exécuter des fonctions. Elles servent à définir des exigences en termes de qualité, de performance ou de sécurité. En résumé, ce sont les qualités, caractéristiques et contraintes du site. 

<h2 style="color:#5d79e7; page-break-before: always" id="exigences_fonc"> Exigences fonctionnelles </h2>

L'utilisateur accède à la page web en tapant l'url du site sur internet. 

Il y a trois type d'utilisateurs différents sur le site : Le non-inscrit, l'inscrit et l'administrateur. Tous ont des droits et des accès différents au site. 

Le non-inscrit n'a accès qu'a la page d'accueil du site, à la page d'accueil utilisateur, à la page d'inscription et à la page de connexion. Il aura cependant des accès aux differents modules mais sous certaines conditions qui seront définies dans les prochaines versions de ces exigences fonctionnelles, en fonction du développement des modules. 

L'utilisateur inscrit à accès aux pages d'accueil du site, page d'accueil utilisateur, de connexion et d'inscription, à la page de profil ainsi qu'a tous les autres services du site. 

L'administrateur aura accès à une page qui lui sera dédiée. 

L'utilisateur non-inscrit peut s'inscrire via la page d'inscription, accessible depuis la page d'accueil du site et la page d'accueil utilisateur. 

L'utilisateur inscrit et l'administrateur peuvent se connecter à leur compte via la page de connexion, également accessible depuis la page d'accueil du site et la page d'accueil utilisateur. Ils peuvent accéder à leurs informations personnelles depuis la page profil. 

La page d'accueil du site contient des liens vers les pages d'inscription et de connexion. 

La page de connexion comporte un formulaire qui permet à l'utilisateur de se connecter. Elle comporte également un lien vers une page mot de passe oublié ainsi que vers la page d'accueil utilisateur et la page d'inscription. 

La page d'inscription comporte un formulaire qui permet à l'utilisateur de s'inscrire et contient un lien vers la page d'accueil utilisateur et vers la page de connexion. 

La page d'accueil utilisateur comporte un lien vers la page profil et permet à l'utilisateur de se deconnecter. 

La page profil comporte un lien vers la page d'accueil utilisateur et permet à l'utilisateur de modifier et de visualiser ses informations personnelles. 

L'utilisateur connecté qui se déconnecte perd l'accès à son compte et doit se reconnecter. 

<h2 style="color:#5d79e7; page-break-before: always" id="exigences_nonfonc"> Exigences non fonctionnelles </h2>

Le site doit être conforme aux normes de sécurité liées aux sites web en vigueur, comme le protocole HTTPS.

Le site doit respecter les normes imposées par le RGPD. 

Le site doit respecter les normes concernant l'accessibilité. 

Le site doit être installé sur un cluster de quatre RPI4.  

Le SGBD du site doit également etre installé sur ce même cluster.

Les fichiers de log doivent être hébergés sur le SGBD et en local. 

Le serveur web du site doit aussi être hebergé sur le cluster de RPI4.

Les composants du site web devront être programmés en HTML, CSS, JS et PHP. 

Ce cluster doit être exploité pour permettre au site ainsi qu'a ses modules, de gagner en performance et en vitesse. 

Le code du site devra être entièrement documenté pour faciliter sa maintenance et l'ajout de modules. 

(Les différents modules du site seront testés et devront fournir des résultats cohérents.)

Le site doit être opérationnel et prêt à la mise en service pour Janvier 2024. 


















