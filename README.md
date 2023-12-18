<img src="doc/DocumentsTechniques/FormatMD/Images/logoUvsq.jpg" width="500px" alt="Logo uvsq">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> SAE S5 Calculs parallèles </h1>

L'objectif de ce projet est de permettre à des utilisateurs de réaliser du calcul distribué ou parallèle, grâce à un kit cluster. <br> 
Ces calculs seront accessibles depuis un site web dans lequel seront exécutés différents programmes. <br>

Sur le site, l'utilisateur a le choix de s'inscrire ou de se connecter s'il possède déjà un compte sur le site. Une fois connecté, il peut cliquer sur son profil pour voir ses informations personnelles et les changer s'il le souhaite.
Les modules ne sont pas encore disponibles sur le site, mais l'utilisateur connecté pourra y accéder les utiliser. <br>
L'utilisateur peut aussi accéder au site sans inscription et connexion, et voir les prochains modules. Cependant, il ne peut pas modifier son profil et utiliser les calculs réalisés dans les modules n'utiliserons pas le calcul distribué. <br>
Un premier module de calcul de nombres premiers est accessible et utilisable par n'importe quel utilisateur, qu'il soit connecté ou non. Cependant, seul un utilisateur connecté pourra utiliser le calcul distribué pour accélérer la vitesse de calcul. 



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


