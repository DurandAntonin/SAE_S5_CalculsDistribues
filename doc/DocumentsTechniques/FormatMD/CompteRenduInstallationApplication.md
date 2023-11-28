<img src="Images/logoUvsq.jpg" width="500">

_Durand Antonin_ <br>
_Jougla Maxime_ <br>
_Parciany Benjamin_ <br>
_Zehren William_

<h1 style="color:#5d79e7; text-align: center"> Compte rendu installation application </h1>

<h1 style="color:#5d79e7; text-align: center; margin-top: 100px"> Table des matières</h1>

<ul>
<li><a href="#introduction">I- Introduction </a></li>
<li><a href="#installationKitCluster">II- Installation du kit Cluster hat </a></li>
<li><a href="#installationApplication">III- Installation de l'application </a></li>
<ul>
    <li><a href="#III_introduction">A) Introduction </a></li>
    <li><a href="#III_introduction">D) Création du service de la base de données </a></li>
    <li><a href="#III_creationImageWeb">C) Création de l'image pour le serveur web </a></li>
    <li><a href="#III_creationImageBaseDonnees">D) Création de l'image pour la base de données </a></li>
    <li><a href="#III_creationStackAppli">E) Création du stack de l'application </a></li>
</ul>
</ul>

<h2 style="color:#5d79e7; page-break-before: always" id="sprint1"> I- Introduction </h2>

Dans ce rapport sera présenté les différentes étapes, et installations effectuées pour mettre en place le kit Cluster hat ainsi que l'application qui sera hébergée sur ce dernier.
En premier lieu, sera montré les différentes étapes pour mettre en route le kit Cluster, de la création de l'iso pour le raspberry principal, en passant par l'allumage des 4 raspberry pi zero, à l'installation de différents utilisateurs et logiciels pour manager ce dernier.
Puis, dans un second temps, sera présenté comment l'application est installée, hébergée et gérée sur le kit Cluster à l'aide de docker swarm.

<h2 style="color:#5d79e7; page-break-before: always" id="sprint1"> II- Installation du kit Cluster hat  </h2>

https://clusterctrl.com/setup-software
https://medium.com/@dhuck/the-missing-clusterhat-tutorial-45ad2241d738

La première étape est le choix des images pour le rpi 4 host, et les rpi zeros. Nous avons choisit de prendre ces dernières sur le site <a href="https://clusterctrl.com/setup-software"> Cluster CTRL </a>, car il fournit des images permettant d'utiliser et contrôler facilement le cluster.
Ainsi, **CNAT-Desktop Controller** est l'image du rpi host, et **Lite Bullseye image PN** est l'image pour le n-ième rpi zero. Le premier rpi zero aura l'image _P1_, le deuxième _P2_, le troisième _P3_ et le quatrième _P4_.
L'avantage de ces  et plus précisément l'image du rpi host, est que cette dernière utilise la méthode NAT (Network Address Translation) pour créer un sous-réseau **172.19.181.0/24**, où chaque rpi zero se verra assignée une addresse ip fixe. Par exemple, le premier rpi zero aura l'adresse _172.19.181.1_, et ainsi de suite. Le rpi host aura l'adresse _172.19.181.254_.

La deuxième étape consiste à l'installation des images sur les cartes micro sd du rpi host et des 4 rpi zeros. Pour ce faire, nous avons utilisé le logiciel **Raspberry Pi Imager**, qui comme **balenaEtcher**, permet de flasher des images sur différents supports physiques comme une carte micro sd, un disque dur.
L'avantage de **Raspberry Pi Imager** est qu'il permet de personnaliser l'image qu'on veut flasher, en activant le _ssh_, et en créant un utilisateur _pi_ dans notre cas. Cela nous évite par exemple à avoir à activer manuellement le ssh sur chaque rpi zero, en montant de chaque carte micro sd dans une distrubution Linux, pour ensuite créer un fichier _ssh_ dans le répertoire _boot_.

Une fois les images créées, installées sur les cartes micro sd, la troisième étape est la configuration des différents des différents rpi. 
Dans un premier temps, on démarre le rpi host. Ensuite, on le met à jour manuellement, puis on crée un script bash pour faire en sorte qu'il soit à jour à chaque fois qu'il démarre.
Dans un second temps, on démarre le cluster de rpi zeros grâce à la commande ci-dessous qui nous est fournie par l'image **CNAT** : 

```bash
dev@cnat:~$ clusterhat on
```

Si on veut éteindre le cluster, il suffit d'effectuer la commande suivante, qui est similaire à celle pour allumer le cluste.

```bash
dev@cnat:~$ clusterhat ff
```

Pour vérifier que cluster est allumé, on exécute la commande suivante qui permet d'obtenir toutes les adresses IP qui sont dans le _cache ARP_, et notamment les adresses IP des rpi zero.

```bash
dev@cnat:~ $ arp -a
? (172.19.181.2) at 00:22:82:ff:ff:02 [ether] on br0
? (172.19.181.4) at 00:22:82:ff:ff:04 [ether] on br0
? (172.19.181.1) at 00:22:82:ff:ff:01 [ether] on br0
? (172.19.181.3) at 00:22:82:ff:ff:03 [ether] on br0
```

Ainsi, le sous-réseau en **172.19.181.0** a été crée par le rpi host, pour contenir les 4 rpi zero.

Ensuite, la quatrième étape est la configuration du système de connexion en ssh entre le rpi host et les 4 rpi zero e, dans les 2 sens, pour faciliter la communication entre le rpi host et ces derniers. 
Dans un premier temps, on change le hostname de chaque rpi zero. Le **hostname**, ou nom d'hôte, est une étiquette que l'on peut donner à un appareil dans un réseau. Dans notre cas, nous allons attribuer un hostname pour chaque rpi zero, stockés dans le fichier _/etc/hosts/_ du rpi host. On peut ainsi utiliser ce hostname au lieu de l'adresse IP du rpi zero lors de la connexion en ssh entre le rpi host et ce dernier.
De la même manière, pour pouvoir utiliser ce hostname pour se connecter au rpi host ou autre rpi zeros depuis un rpi zero, il faut ajouter les adresses IP de ce derniers et les hostnames associés dans _/etc/hosts/_ du rpi zero en question.

Le fichier _/etc/hosts/_ du rpi host contient les valeurs suivantes : 
```bash
dev@cnat:~$ cat /etc/hosts
```

Dans un second temps, on va faire en sorte de pouvoir se connecter en ssh à un rpi zero depuis le host et inversement sans avoir à préciser un mot de passe, ce qui va être important pour certains programmes de calculs distribués à venir. 
Pour ce faire, on utilise la commande **ssh-keygen -t rsa** sur chaque rpi zero. Cela nous génère un couple de clés privée/publique, et nous enregistrer la clé publique générée dans le rpi host grâce à la commande **ssh-copy-id @cnat**. 
Une fois cela fait, on peut se connecter en ssh au rpi host depuis n'importe quel rpi zero sans avoir à fournir un mot de passe.
Il ne reste plus qu'à effectuer les mêmes commandes sur le rpi host, pour pouvoir se connecteren ssh à n'importe quel rpi zero depuis ce dernier sans avoir à entrer de mot de passe.


Enfin, la cinquième étape consiste à créer un dossier partager sur le rpi host entre ce dernier et les 4 rpi zero pour faciliter le partages de fichiers par exemple. Pour ce faire, 

<h2 style="color:#5d79e7; page-break-before: always" id="sprint1"> III- Installation de l'application  </h2>

<h3 id="III_introduction"> A) Introduction </h3>

Une fois le kit Cluster configuré, et les 4 pi zero accessibles, nous allons installer l'application en utilisant docker swarm.
Docker swarm est une fonctionnalité avancée de docker permettant de gérer un cluster de containers et un ensemble de services. 
Il est composé d'un manager, le RaspberryPi principal dans notre cas, qui s'occupe de gérer les différents workers, et services du docker swarm. Il peut ajouter, supprimer un worker, ajouter, modifier et supprimer un service.
Un worker ne possède pas de droits et son seul rôle est d'exécuter les tâches données par le manager, soit un ou plusieurs services.
L'avantage de docker swarm est qu'il permet d'assurer la haute disponiblité des services en gérant automatiquement le fail-over et le load-balancing, dans notre cas, l'application doit être disponible en permanance.
Dans notre situation, chaque worker est un RaspberryPi zero.

Nous aurons un service web qui sera le front-end et le back-end du site. Il aura 3 réplicas, ce qui permet au site d'être accesssible si l'un des RaspberryPi rencontre une erreur.
Ensuite, un autre service sera la base de données MySql de l'application, avec 2 réplicas.
Enfin, il faut créer deux autres services identiques aux précédents, mais qui seront utilisés pour tester les changements effectués dans l'application avant son déploiement.

<h3 id="III_creationDockerSwarm"> B) Création du docker swarm</h3>

On installe docker s'il ne l'est pas encore.
Ensuite, on initialise un docker swarm, et on rajoute 4 workers, où le manager est le RasperryPi principal, et chaque worker est un RaspberryPi zero.
L'objectif ensuite est d'avoir 1 stack contenant l'application déployée et 1 autre stack contenant l'application en production. 

<h3 id="III_creationImageWeb"> C) Création de l'image pour le serveur web </h3>

Pour créer le service web de notre application, nous avons besoin d'une image docker personnalisée.

Cette dernière se base sur l'image existante **php:8.2-apache**, car elle contient un serveur apache capable de lire et d'exécuter des fichiers PHP.
Elle doit contenir l'ensemble du front et back-end de l'application, soit les fichiers du site web.

Ensuite, on crée notre image personnalisée à l'aide d'un fichier **dockerfile**.

<h3 id="III_creationImageBaseDonnees"> D) Création de l'image pour la base de données </h3>

Pour créer le service bd de notre application, nous avons besoin d'une image docker personnalisée.

Cette dernière se base sur l'image existante **mysql**, car elle contient déjà un système de gestion de base de données (SGBD).
Elle doit contenir la base de données de notre application.

Ensuite, on crée notre image personnalisée à l'aide d'un fichier **dockerfile**.

<h3 id="III_creationStackAppli"> E) Création du stack de l'application </h3>

Enfin, une fois les images pour la partie web et la partie base de données créées, on utilise docker stack pour créer un stack, collection contenant un service web et un service base de données.

Pour ce faire, nous allons utiliser un fichier de configuration du stack **docker-compose.yml**, qui crée un service en utilisant l'image du serveur web, et un autre en utilisant l'image de la base de données. 
Dans ce fichier de configuration, on précise aussi les ports des services ainsi que les replicas et le volume pour le service de base de données.