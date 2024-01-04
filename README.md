<img src="doc/DocumentsTechniques/FormatMD/Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> SAE S5 Calculs parallèles </h1>

L'objectif de ce projet est de permettre à des utilisateurs de réaliser du calcul distribué ou parallèle, grâce à un kit cluster. <br> 
Ces calculs seront accessibles depuis un site web dans lequel seront exécutés différents programmes. <br>

Sur le site, l'utilisateur a le choix de s'inscrire ou de se connecter s'il possède déjà un compte sur le site. Une fois connecté, il peut cliquer sur son profil pour voir ses informations personnelles et les changer s'il le souhaite.
L'utilisateur peut aussi accéder au site sans inscription et connexion, et voir les modules. Cependant, il ne peut pas modifier son profil et effectuer des calculs dans les différents modules se fera sans le calcul distribué. <br>
Un premier module de calcul de nombres premiers est accessible et utilisable par n'importe quel utilisateur, qu'il soit connecté ou non. Pour effectuer ses calculs, l'utilisateur aura le choix entre ne pas activer le calcul distribué et l'activer. Seul un utilisateur connecté pourra activer le calcul distribué afin d'accélérer la vitesse de calcul. Cependant, si l'utilisatuer non connecté essaye de changer de mode, il lui sera proposé de se connecter ou de s'inscrire. Il pourra alors utiliser le calcul distribué. 



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


