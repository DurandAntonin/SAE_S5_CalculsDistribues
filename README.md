<img src="doc/DocumentsTechniques/FormatMD/Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> SAE S5 Calculs parallèles </h1>

L'objectif de ce projet est de permettre à des utilisateurs de réaliser du calcul distribué ou parallèle, grâce à un kit cluster. <br> 
Ces calculs seront accessibles depuis un site web dans lequel seront exécutés différents programmes. <br>
L'application est hébergé dans un docker swarm constitué deux services, et déployé à l'aide d'un stack: 
- Le premier est un service exécutant un serveur **Apache** contenant le back-end et le front-end de l'application
- Le deuxième est un service exécutant un serveur **MySQL** contenant la base de données de l'application

Il existe 3 types d'utilisateurs sur le site :

- L'inscrit
- Le visiteut
- L'administrateur

Sur le site, l'utilisateur a le choix de s'inscrire ou de se connecter s'il possède déjà un compte sur le site. Une fois connecté, il peut cliquer sur son profil pour voir ses informations personnelles et les changer s'il le souhaite. <br>
L'utilisateur peut aussi accéder au site sans inscription et connexion, et voir les modules comme l'utilisateur inscrit. Cependant, contrairement à ce dernier, il ne peut pas modifier son profil et effectuer des calculs dans les différents modules se fera sans le calcul distribué. <br>
Enfin, l'administrateur de l'application à un compte spécial dans la mesure où il n'a pas accès aux pages d'un inscrit ou d'un visiteur. En effet, lorsqu'il se connecte, il arrive sur une autre page où il peut à la fois voir les statistiques de l'application (nombre d'inscriptions, de connexions et d'utilisation des modules) par jour/semaine/mois/all, et les statistiques du Cluster Hat (Fréquence du CPU, utilisation CPU et RAM et la durée de fonctionnement de chaque rpi). L'administrateur peut enfin supprimer des utilisateurs, rechercher des utilisateurs et des journeaux (logs) selon un critère de recherche (ex: nom, prénom, description, ...) parmis plusieurs.


Le site possède deux modules de calcul : 

- Le premier permet de calculer les nombres premiers compris entre les bornes _n_ et _m_ saisies par l'utilisateur
- Le deuxième permet d'effectuer une approximation de $\pi$ selon l'algorithme de Monte Carlo selon un nombre _n_ de lancers saisi par l'utilisateur

Le script _database_script_ dans le répertoire _src/SQL/_ permet de créer la base de données de l'application, avec 2 utilisateurs par défaut (un administrateur et un utilisateur inscrit) qui ont comme mot de passe _azerty_. 

### Install Tailwind

Tutoriel du [site de Tailwind](https://tailwindcss.com/docs/installation). Il faut avant avoir installé Node.js.

Pour installer les dépendances du projet. Il faut executer cette commande dans le même répertoire que package.json.
```bash
npm install
```

Pour lancer le process de build de TailwindCLI (bloque le terminal) utile pour modifier le style en temps réel.
```bash
npx tailwindcss -i ./src/CSS/style.css -o ./src/dist/output.css --watch
```


