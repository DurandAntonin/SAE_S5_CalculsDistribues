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
<ul>
    <li><a href="#I_A">A) Choix des images pour le kit Cluster Hat </a></li>
    <li><a href="#I_B">D) Installation des images sur chaque Raspberry pi et premier démarrage </a></li>
    <li><a href="#I_C"> C) Configuration du ssh des Raspberry pi du kut Cluster Hat </a></li>
</ul>
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

Dans ce rapport seront présentées les différentes étapes et installations effectuées pour mettre en place le kit Cluster hat, ainsi que l'application qui sera hébergée sur ce dernier.
En premier lieu, seront montrées les différentes étapes pour mettre en route le kit Cluster, de la création de l'iso pour le Raspberry principal, en passant par l'allumage des 4 Raspberry Pi Zero, à l'installation de différents utilisateurs et logiciels pour manager ce dernier.
Puis, dans un second temps, sera présenté comment l'application est installée, hébergée et gérée sur le kit Cluster à l'aide de docker swarm.

<h2 style="color:#5d79e7; page-break-before: always" id="sprint1"> II- Installation du kit Cluster hat  </h2>

<h3 id="I_A"> A) Choix des images pour le kit Cluster Hat </h3>

La première étape est le choix des images pour le RPI 4 host, et les RPI Zero. Nous avons choisit de prendre ces dernières sur le site <a href="https://clusterctrl.com/setup-software"> Cluster CTRL </a>, car il fournit des images permettant d'utiliser et contrôler facilement le cluster.
Ainsi, **CNAT-Desktop Controller** est l'image du RPI host, et **Lite Bullseye image PN** est l'image pour le n-ième RPI zero. Le premier RPI zero aura l'image _P1_, le deuxième _P2_, le troisième _P3_ et le quatrième _P4_. Comme les Raspberry du kit Cluster Hat des processeurs 32 bits, par conséquent il est nécessaire de prendre la version 32 bits de ces images, qui est aussi disponible sur le site.
L'avantage de ces images et plus précisément l'image du RPI host, est que cette dernière utilise la méthode NAT (Network Address Translation) pour créer un sous-réseau **172.19.181.0/24**, où chaque RPI zero se verra assignée une addresse ip fixe. Par exemple, le premier RPI zero aura l'adresse _172.19.181.1_, et ainsi de suite. Le RPI host aura l'adresse _172.19.181.254_.

<h3 id="I_B"> B) Installation des images sur chaque Raspberry Pi et premier démarrage </h3>

La deuxième étape consiste à l'installation des images sur les cartes micro sd du RPI host et des 4 RPI zeros. Pour ce faire, nous avons utilisé le logiciel **Raspberry Pi Imager**, qui comme **balenaEtcher**, permet de flasher des images sur différents supports physiques comme une carte micro sd ou un un disque dur.
L'avantage de **Raspberry Pi Imager** est qu'il permet de personnaliser l'image qu'on veut flasher, en activant le _ssh_, et en créant un utilisateur _pi_ dans notre cas. Cela nous évite par exemple à avoir à activer manuellement le ssh sur chaque RPI zero, où il aurait fallu monter chaque carte micro sd dans une distrubution Linux, pour ensuite créer un fichier _ssh_ dans le répertoire _boot_.


<img src="Images/interfaceFlashImage1.jpg" alt="Interface 1 pour flash d'une image à l'aide de Pi Imager" width="500">
<p style="font-style: italic"> 
Comme le montre l'image ci-dessous, pour flasher l'image à l'aide de Pi Imager, il faut en premier lieu sélectionner le système d'exploitation, dans notre cas l'image CNAT ou les images lite Bullseye. <br>
Puis en deuxième lieu, il faut choisir le support sur lequel installer le système d'exploitation, à savoir une carte micro sd dans notre situation.
Enfin, on peut modifier certains paramètres du système d'exploitation à installer à l'aide du rouage en bas à droite, et ensuite appuyer sur <b>Ecrire</b> pour installer ce dernier sur la carte micro sd.
</p>

<img src="Images/interfaceFlashImage2.jpg" alt="Interface 2 pour flash d'une image à l'aide de Pi Imager" width="500">
<p style="font-style: italic">
L'image ci-dessous permet de montrer les paramètres les plus importants que nous avons utilisés pour chacune des images du kit Cluster Hat, comme l'activation du SSH et la définition d'un utilisateur et de son mot de passe. 
</p>

Une fois les images créées, installées sur les cartes micro sd, on allume le RPI host. 
Ensuite, on le met à jour manuellement, puis on crée un script bash pour faire en sorte qu'il soit à jour à chaque fois qu'il démarre.
Dans un second temps, on démarre le cluster de RPI zeros grâce à la commande ci-dessous qui nous est fournie par l'image **CNAT** : 

```bash
dev@cnat:~$ clusterhat on
```

Si on veut éteindre le cluster, il suffit d'effectuer la commande suivante, qui est similaire à celle pour allumer le cluster.

```bash
dev@cnat:~$ clusterhat off
```

Pour vérifier que cluster est allumé, on exécute la commande suivante qui permet d'obtenir toutes les adresses IP qui sont dans le _cache ARP_, et notamment les adresses IP des RPI zero.

```bash
dev@cnat:~ $ arp -a
? (172.19.181.2) at 00:22:82:ff:ff:02 [ether] on br0
? (172.19.181.4) at 00:22:82:ff:ff:04 [ether] on br0
? (172.19.181.1) at 00:22:82:ff:ff:01 [ether] on br0
? (172.19.181.3) at 00:22:82:ff:ff:03 [ether] on br0
```
Ainsi, le sous-réseau en **172.19.181.0** a été créé par le RPI host, pour contenir les 4 RPI zero.

<h3 id="I_C"> C) Configuration du ssh des Raspberry pi du kut Cluster Hat </h3>

Ensuite, la troisième étape est la configuration du système de connexion en ssh entre le RPI host et les 4 RPI zero, dans les 2 sens, pour faciliter la communication entre le RPI host et ces derniers. 

Dans un premier temps, on change le hostname de chaque RPI zero. Le **hostname**, ou nom d'hôte, est une étiquette que l'on peut donner à un appareil dans un réseau. Dans notre cas, nous allons attribuer un hostname pour chaque RPI zero, stockés dans le fichier _/etc/hosts/_ du RPI host. On peut ainsi utiliser ce hostname au lieu de l'adresse IP du RPI zero lors de la connexion en ssh entre le RPI host et ce dernier.
De la même manière, pour pouvoir utiliser ce hostname pour se connecter au RPI host ou autre RPI zeros depuis un autre RPI zero, il faut ajouter les adresses IP de ces derniers et les hostnames associés dans _/etc/hosts/_ du RPI zero en question.

Pour vérifier que cela fonctionne, on regarde les hostnames accessibles depuis le RPI host.
```bash
dev@cnat:~$ cat /etc/hosts
127.0.0.1       localhost
::1             localhost ip6-localhost ip6-loopback
ff02::1         ip6-allnodes
ff02::2         ip6-allrouters

127.0.1.1       cnat
172.19.181.1 pi1
172.19.181.2 pi2
172.19.181.3 pi3
172.19.181.4 pi4
```

Ensuite on vérifie qu'on peut se connecter à un RPI zero depuis le RPI host en utilisant son hostname.
```bash
dev@cnat:~ $ ssh pi@pi2
pi@pi2's password:
Linux p2 6.1.21+ #1642 Mon Apr  3 17:19:14 BST 2023 armv6l

The programs included with the Debian GNU/Linux system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.

Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
permitted by applicable law.
Last login: Fri Dec  1 14:51:07 2023 from 172.19.181.254
pi@p2:~ $
```

Dans un second temps, on va faire en sorte de pouvoir se connecter en ssh à un RPI zero depuis le host et inversement sans avoir à préciser un mot de passe, ce qui va être important pour certains programmes de calculs distribués à venir, et pour gagner en rapidité. 
Pour ce faire, depuis le RPI host, nous allons configurer le ssh à l'aide du fichier **.ssh/config** dont le format est le suivant :
```bash
Host hostname1
    SSH_OPTION value
    SSH_OPTION value
```
Ces 3 lignes permettent d'ajouter des options de connexion en ssh à un autre système. Dans notre cas, pour la connexion au premier RPI zero, on remplace **hostname1** par le nom que nous voulons utiliser pour se connecter à ce dernier.
Ensuite, la première option que nous allons mettre est le hostname du RPI zero sur lequel se connecter en ssh, dans notre cas _pi1_.
Enfin, on indique l'utilisateur du RPI zero à utiliser pour établir la connexion, qui est dans notre cas _pi_.
Après avoir écrit et adapté ces lignes pour les 3 autres RPI zero, on enregistre les modifications et le **.ssh/config** ressemble désormais à cela :
```bash
dev@cnat:~ $ cat .ssh/config
Host pi1
    Hostname pi1
    User pi
Host pi2
    Hostname pi2
    User pi
Host pi3
    Hostname pi3
    User pi
Host pi4
    Hostname pi4
    User pi
```

On utilise la commande **ssh-keygen -t rsa** sur chaque RPI zero. Cela nous génère un couple de clés privée/publique, et nous enregistrons la clé publique générée dans le RPI host grâce à la commande **ssh-copy-id @cnat**. 
Une fois cela fait, on peut se connecter en ssh au RPI host depuis n'importe quel RPI zero sans avoir à fournir un mot de passe comme le montre la commande ci-dessous :
```bash
dev@cnat:~ $ ssh pi1
Linux p1 6.1.21+ #1642 Mon Apr  3 17:19:14 BST 2023 armv6l

The programs included with the Debian GNU/Linux system are free software;
the exact distribution terms for each program are described in the
individual files in /usr/share/doc/*/copyright.

Debian GNU/Linux comes with ABSOLUTELY NO WARRANTY, to the extent
permitted by applicable law.
Last login: Fri Dec  1 20:13:44 2023 from 172.19.181.254
pi@p1:~ $
```

Il ne reste plus qu'à effectuer les mêmes commandes sur le RPI host, pour pouvoir se connecter en ssh à n'importe quel RPI zero depuis ce dernier sans avoir à entrer de mot de passe.

<h2 style="color:#5d79e7; page-break-before: always" id="sprint1"> III- Installation de l'application  </h2>

<h3 id="III_introduction"> A) Introduction </h3>

Une fois le kit Cluster configuré, et les 4 pi zero accessibles, nous allons installer l'application en utilisant docker swarm.
Docker swarm est une fonctionnalité avancée de docker permettant de gérer un cluster de containers et un ensemble de services. 
Il est composé d'un manager, le RaspberryPi principal dans notre cas, qui s'occupe de gérer les différents workers, et services du docker swarm. Il peut ajouter, supprimer un worker, ajouter, modifier et supprimer un service.
Un worker ne possède pas de droits et son seul rôle est d'exécuter les tâches données par le manager, soit un ou plusieurs services.
L'avantage de docker swarm est qu'il permet d'assurer la haute disponiblité des services en gérant automatiquement le fail-over et le load-balancing, dans notre cas, l'application doit être disponible en permanance.
Dans notre situation, chaque worker est un RaspberryPi zero.

Nous aurons un service web qui sera le front-end et le back-end du site. Il aura 3 réplicas, ce qui permet au site d'être accesssible si l'un des RaspberryPi rencontre une erreur.
Ensuite, un autre service sera la base de données MySql de l'application, avec 1 réplica.
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