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
    <li><a href="#I_A"> A) Choix des images pour le kit Cluster Hat </a></li>
    <li><a href="#I_B"> B) Installation des images sur chaque Raspberry Pi et premier démarrage </a></li>
    <li><a href="#I_C"> C) Configuration du ssh des Raspberry pi du kut Cluster Hat </a></li>
    <li><a href="#I_D"> D) Installation du service Fail2Ban sur le Raspeberry Pi host </a></li>
</ul>
<li><a href="#II_installationApplication">III- Installation de l'application </a></li>
<ul>
    <li><a href="#III_introduction"> A) Introduction </a></li>
    <li><a href="#III_creationImageWeb"> B) Création de l'image pour le serveur web </a></li>
    <li><a href="#III_creationImageBaseDonnees"> C) Création de l'image pour la base de données </a></li>
    <li><a href="#III_creationDockerSwarm"> D) Création du docker swarm </a></li>
    <li><a href="#III_creationStackAppli"> E) Création du stack de l'application </a></li>
    <li><a href="#IV_repertoire_partage_named_pipe"> F) Lancement de commandes sur le rpi host depuis un conteneur </a></li>
    <ul>
      <li><a href="#IV_1"> 1. Création d'un tube nommé </a></li>
      <li><a href="#IV_2"> 2. Envoi de commandes dans le tube nommé </a></li>
      <li><a href="#IV_3"> 3. Exécution de commandes dans le rpi depuis un conteneur  </a></li>
      <li><a href="#IV_4"> 4. Automatisation de la mise en écoute des tubes nommés  </a></li>
    </ul>
</ul>
</ul>

<h2 style="color:#5d79e7; page-break-before: always" id="introduction"> I- Introduction </h2>

Dans ce rapport seront présentées les différentes étapes et installations effectuées pour mettre en place le kit Cluster hat, ainsi que l'application qui sera hébergée sur ce dernier.
En premier lieu, seront montrées les différentes étapes pour mettre en route le kit Cluster, de la création de l'iso pour le Raspberry principal, en passant par l'allumage des 4 Raspberry Pi Zero, à l'installation de différents utilisateurs et logiciels pour manager ce dernier.
Puis, dans un second temps, sera présenté comment l'application est installée, hébergée et gérée sur le kit Cluster à l'aide de docker swarm.

<h2 style="color:#5d79e7; page-break-before: always" id="installationKitCluster"> II- Installation du kit Cluster hat  </h2>

<h3 style="color:#5d79e7" id="I_A"> A) Choix des images pour le kit Cluster Hat </h3>

La première étape est le choix des images pour le RPI 4 host, et les RPI Zero. Nous avons choisit de prendre ces dernières sur le site <a href="https://clusterctrl.com/setup-software"> Cluster CTRL </a>, car il fournit des images permettant d'utiliser et contrôler facilement le cluster.
Ainsi, **CNAT-Desktop Controller** est l'image du RPI host, et **Lite Bullseye image PN** est l'image pour le N-ième RPI zero. Le premier RPI zero aura l'image _P1_, le deuxième _P2_, le troisième _P3_ et le quatrième _P4_. Comme les Raspberry du kit Cluster Hat ont des processeurs 32 bits, par conséquent il est nécessaire de prendre la version 32 bits de ces images, qui est aussi disponible sur le site.
L'avantage de ces images et plus précisément l'image du RPI host, est que cette dernière utilise la méthode NAT (Network Address Translation) pour créer un sous-réseau **172.19.181.0/24**, où chaque RPI zero se verra assigner une adresse ip fixe. Par exemple, le premier RPI zero aura l'adresse _172.19.181.1_, et ainsi de suite. Le RPI host aura l'adresse _172.19.181.254_.

<h3 style="color:#5d79e7" id="I_B"> B) Installation des images sur chaque Raspberry Pi et premier démarrage </h3>

La deuxième étape consiste à l'installation des images sur les cartes micro sd du RPI host et des 4 RPI Zero. Pour ce faire, nous avons utilisé le logiciel **Raspberry Pi Imager**, qui comme **balenaEtcher**, permet de flasher des images sur différents supports physiques comme une carte micro sd ou un disque dur.
L'avantage de **Raspberry Pi Imager** est qu'il permet de personnaliser l'image qu'on veut flasher, en activant le _ssh_, et en créant un utilisateur _pi_ dans notre cas. Cela nous évite par exemple à avoir à activer manuellement le ssh sur chaque RPI zero, où il aurait fallu monter chaque carte micro sd dans une distribution Linux, pour ensuite créer un fichier _ssh_ dans le répertoire _boot_.


<img src="Images/interfaceFlashImage1.jpg" alt="Interface 1 pour flash d'une image à l'aide de Pi Imager" width="500">
<p style="font-style: italic"> 
Comme le montre l'image ci-dessus, pour flasher l'image à l'aide de Pi Imager, il faut en premier lieu sélectionner le système d'exploitation, dans notre cas l'image CNAT ou les images lite Bullseye. <br>
Puis dans un deuxième lieu, il faut choisir le support sur lequel installer le système d'exploitation, à savoir une carte micro sd dans notre situation.
Enfin, on peut modifier certains paramètres du système d'exploitation à installer à l'aide du rouage en bas à droite, et ensuite appuyer sur <b>Ecrire</b> pour installer ce dernier sur la carte micro sd.
</p>

<img src="Images/interfaceFlashImage2.jpg" alt="Interface 2 pour flash d'une image à l'aide de Pi Imager" width="500">
<p style="font-style: italic">
L'image ci-dessus permet de montrer les paramètres les plus importants que nous avons utilisé pour chacune des images du kit Cluster Hat, comme l'activation du SSH et la définition d'un utilisateur et de son mot de passe. 
</p>

Une fois les images créées, installées sur les cartes micro sd, on allume le RPI host. 
Ensuite, on le met à jour manuellement, puis on crée un script bash pour faire en sorte qu'il soit à jour à chaque fois qu'il démarre.
Dans un second temps, on démarre le cluster de RPI zero grâce à la commande ci-dessous qui nous est fournie par l'image **CNAT** : 

```bash
dev@cnat:~$ clusterhat on
```

Si on veut éteindre le cluster, il suffit d'effectuer la commande suivante, qui est similaire à celle pour allumer le cluster.

```bash
dev@cnat:~$ clusterhat off
```

Pour vérifier que le cluster est allumé, on exécute la commande suivante qui permet d'obtenir toutes les adresses IP qui sont dans le _cache ARP_, et notamment les adresses IP des RPI zero.

```bash
dev@cnat:~ $ arp -a
? (172.19.181.2) at 00:22:82:ff:ff:02 [ether] on br0
? (172.19.181.4) at 00:22:82:ff:ff:04 [ether] on br0
? (172.19.181.1) at 00:22:82:ff:ff:01 [ether] on br0
? (172.19.181.3) at 00:22:82:ff:ff:03 [ether] on br0
```
Ainsi, le sous-réseau en **172.19.181.0** a été créé par le RPI host, pour contenir les 4 RPI zero.

<h3 style="color:#5d79e7; page-break-before: always" id="I_C"> C) Configuration du ssh des Raspberry Pi du kit Cluster Hat </h3>

Ensuite, la troisième étape est la configuration du système de connexion en ssh entre le RPI host et les 4 RPI zero, dans les 2 sens, pour faciliter la communication entre le RPI host et ces derniers. 

Dans un premier temps, on change le hostname de chaque RPI zero. Le **hostname**, ou nom d'hôte, est une étiquette que l'on peut donner à un appareil dans un réseau. Dans notre cas, nous allons attribuer un hostname pour chaque RPI zero, stockés dans le fichier _/etc/hosts/_ du RPI host. On peut ainsi utiliser ce hostname au lieu de l'adresse IP du RPI zero lors de la connexion en ssh entre le RPI host et ce dernier.
De la même manière, pour pouvoir utiliser ce hostname pour se connecter au RPI host ou autre RPI zero depuis un autre RPI zero, il faut ajouter les adresses IP de ces derniers et les hostnames associés dans _/etc/hosts/_ du RPI zero en question.

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
pi@pi2 password:
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

Maintenant que l'on peut communiquer rapidement avec ssh on va créer une clé ssh et la partager aux autres nodes avec la commande ```ssh-copy-id```.
Pour créer une clé ssh on utilise la commande ```ssh-keygen -t rsa```, sans lui donner de nom ou de passphrase. On obtient deux clés shh : ```id_rsa``` et ```id_rsa.pub``` c'est la clé publique que l'on va partager (ne jamais partager sa clé privé) avec la commande ```ssh-copy-id IPNode```. <br>
On va faire cette manipulation une première fois sur le node principal et partager la clé ssh à chaque nodes. Une fois que c'est fait on va se connecter sur chaque node pour faire la même procédure à l'exception que l'on ne partagera la clé publique de chaque node seulement au node principal.

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

<h3 style="color:#5d79e7; page-break-before: always" id="I_D"> D) Installation du service Fail2Ban sur le Raspeberry Pi host </h3>

On installe **Fail2ban** grâce à la commande ci-dessous, qui est une application analysant les différents logs de nombreux services, comme SSH, Apache, FTP, et bannissant temporairement les adresses ip à l'origine de motifs de connexions, de requêtes au préalablement indiqués comme suspectes et non désirables.

```bash
dev@cnat:~ $ sudo apt install fail2ban
dev@cnat:~ $ sudo systemctl start fail2ban
dev@cnat:~ $ sudo systemctl enable fail2ban
```

Dans notre cas, l'objectif est de bannir temporairement les adresses ip à l'origine des tentatives de connexions en ssh au rpi host échouées. Pour ce faire, nous avons modifier le fichier __/etc/fail2ban/jail.d/defaults-debian.conf_, pour créer une 'prison' pour le service sshd.

```bash
dev@cnat:~ $ sudo cat /etc/fail2ban/jail.d/defaults-debian.conf
[sshd]
enabled = true
port = 22
logpath = /var/log/auth.log
banaction = iptables
maxretry = 5
bantime = 120
```

Comme indiqué dans la commande ci-dessus, nous avons spécifié de nombreux paramètres pour la prison, comme le port du service sshd, le fichier du log de ce dernier à écouter, la méthode bannissement utilisée qui est iptables et qui permet de bannir seulement un port ou tous les ports de l'adresse ip suspecte.
On précise ensuite le nombre d'essais maximum après quoi fail2ban bannit l'adresse ip avec le paramètre _maxretry_.
Et enfin, on indique la durée de bannissement de cettre adresse ip, qui est mis à 120s soit 2min.

On active aussi le filtre récidive, qui permet de poursuivre le banissement si l'adresse ip essaie encore de se connecter en ssh sans succès. Pour ce faire, on ajoute les lignes suivante dans le fichier __/etc/fail2ban/jail.d/defaults-debian.conf_.
```bash
[recidive]
enabled = true
logpath = /var/log/fail2ban.log
bantime = 1h
```
Ainsi, fail2ban va bannir l'adresse ip pendant 1h si cette dernière récidive.

Pour que fail2ban prenne en compte ces changements, on redémarre le service à l'aide de la commande suivante :
```bash
dev@cnat:~ $ sudo systemctl reload fail2ban
```

<h2 style="color:#5d79e7; page-break-before: always" id="II_installationApplication"> III- Installation de l'application  </h2>

<h3 style="color:#5d79e7" id="III_introduction"> A) Introduction </h3>

Une fois le kit Cluster configuré, et les 4 pi zero accessibles, nous allons installer l'application en utilisant docker swarm.
Docker swarm est une fonctionnalité avancée de docker permettant de gérer un cluster de containers et un ensemble de services. 
Il est composé d'un manager, le RaspberryPi principal dans notre cas, qui s'occupe de gérer les différents workers, et services du docker swarm. Il peut ajouter, supprimer un worker, ajouter, modifier et supprimer un service.
Un worker ne possède pas de droits et son seul rôle est d'exécuter les tâches données par le manager, soit un ou plusieurs services.
L'avantage de docker swarm est qu'il permet d'assurer la haute disponiblité des services en gérant automatiquement le fail-over et le load-balancing, dans notre cas, l'application doit être disponible en permanance.
Dans notre situation, chaque worker est un RaspberryPi zero.

Nous aurons un service web qui sera le front-end et le back-end du site. Il aura 4 réplicas hébergés sur chaque noeud worker RPI zero, ce qui permet au site d'être accesssible si l'un de ces derniers rencontre une erreur.
Ensuite, un autre service sera la base de données MySql de l'application, avec 1 réplica qui sera hébergé sur le noeud manager RPI Host.

Avant d'initialiser la swarm, il nous faut tester les différentes images sur le RPI Host et les RPI zero, pour s'assurer de leur bon fonctionnement. On créé donc un nouveau dossier dockerConfig au même niveau que le src dans le RPI Host pour y mettre tous les dockerfiles et les différents fichiers de configuration. 

<h3 style="color:#5d79e7" id="III_creationImageWeb"> B) Création de l'image pour le serveur web </h3>

Pour créer le service web de notre application, nous avons besoin d'une image docker personnalisée.  

Nous avons décidé de choisir l'image de base **php:8.2-apache** car cette dernière est compatible avec l'architecture armv6 des quatre RPI zero et qu'elle contient à la fois un serveur web capable de lire et d'exécuter des fichiers html et du js ainsi que des fichiers php grâce au module présent dans Apache. 
Elle doit contenir l'ensemble du front et back-end de l'application, soit les fichiers du site web. 

Ensuite, on crée notre image personnalisée à l'aide d'un fichier **dockerfile**. Ce dockerfile va contenir des instructions pour nous permettre de créer une image standardisée et contenant les différents fichiers dont nous avons besoin sans devoir les intégrer manuellement à nos conteneurs. Chaque conteneur / service créé à partir de cette image personnalisée sera par conséquent identique et contiendra tout le nécéssaire pour l'éxécution de notre site web. 

En premier lieu, on se retrouve donc avec un dockerfile nommé dockerfilePHP tel que : 

```dockerfile

FROM php:8.2-apache

VOLUME /hostpipe

RUN apt update -y \
    && apt upgrade -y \
    && apt clean -y

COPY ./src/ /var/www/html/
COPY ./dockerConfig/php.ini-development /usr/local/etc/php/
COPY ./dockerConfig/php.ini-production /usr/local/etc/php/

RUN chown -R www-data:www-data /var/www/html/ \
    && docker-php-ext-install mysqli && docker-php-ext-enable mysqli
```

Ce dockerfile va donc créer une image personnalisée à partir de l'image existante **php:8.2-apache**, créé un volume, ou répertoire partagé _/hostpipe/_ à la racine du conteneur et permet notamment le transfert de fichiers, répertoires entre le rpi et le conteneur. <br>
Il va ensuite mettre à jour l'image, puis va copier l'intégralité des pages du site dans le répertoire _/var/www/html/_ du conteneur utilisant cette image, car c'est le répertoire par défaut dans lequel apache va chercher les fichiers web. <br>
Ensuite, on copie les différents fichiers de configuration pour les pages en PHP, qui vont par exemple nous permettre d'afficher les erreurs et de charger les extensions PHP, dans notre cas, l'extension MySQLI pour se connecter à un serveur MySQL depuis un script PHP. <br>
Puis, on attribue le répertoire de l'application, on donne les droits à l'utilisateur _www-data_ qui execute le serveur apache au répertoire de l'application. <br>
Enfin, on installe puis on active l'extension de PHP MySQLi pour pouvoir ouvrir des connexions avec un serveur de base de données MySQL depuis un script PHP.

Après avoir écrit ce dockerfile, on va donc tester son bon fonctionnement sur le RPI Host. 

Pour tester cette image personnalisée, on se met sur un des RPI zero et on créé l'image depuis ce dernier pour s'assurer que l'image à bien été créée et est bien compatible avec les RPI zero. 

On commence donc par créer l'image qui découle de ce dockerfile avec la commande suivante (cette commande doit être exécutée au même niveau que le répertoire _src_) : 

```bash
docker build -t phpimage -f dockerConfig/dockerfilePHP .
```

Lorsqu'on a créé l'image, on démarre un conteneur avec la commande suivante : 

```bash
docker run --name phpcont -dit phpimage
```

<img src="Images/ImagesRapports/PHPContRapportInstall.PNG">

Après le lancement du container, on s'y connecte en bash pour vérifier que les changements ont bien été pris en compte avec la commande :

```bash
 docker exec -it phpcont bash
```

On peut donc déjà vérifier que les fichiers ont bien été insérés dans le repertoire /var/www/html/ avec la commande ```pwd``` : 

<img src="Images/ImagesRapports/varhtmlPHPCont.PNG">

On vérifie ensuite les droits ont bien été modifiés pour www-data sur les différents répertoires des fichiers du site web :

<img src="Images/ImagesRapports/droitsPHPWeb.png">

Enfin, on vérifie que MySQLi à bien été installé : 

<img src="Images/ImagesRapports/MySQLiPHP.png">

<h3 style="color:#5d79e7; page-break-before: always" id="III_creationImageBaseDonnees"> C) Création de l'image pour la base de données </h3>

Après avoir créé le service web de notre application et confirmé son bon fonctionnement, nous avons pu passer à la réalisation du service bd de notre application. 

Pour créer le service bd de notre application, nous avons aussi besoin d'une image docker personnalisée.

Cette dernière se base sur l'image existante **mysql**, car elle contient déjà un système de gestion de base de données (SGBD).
Elle doit contenir la base de données de notre application.

Ensuite, on crée notre image personnalisée à l'aide d'un fichier **dockerfile**.

On créé donc un dockerfile dockerfileMYSQL tel que : 

```dockerfile
FROM clover/mysql:5.7

COPY ./database_script.sql /docker-entrypoint-initdb.d/

RUN chown -R mysql:mysql /docker-entrypoint-initdb.d/ \
    && chmod 755 -R /docker-entrypoint-initdb.d/

WORKDIR /docker-entrypoint-initdb.d/ 
```

Dans le dockerfile dockerfileMYSQL, il nous a fallu choisir une image mysql compatible avec l'architecture 32 bits armv7 de notre RPI Host. Cependant cette dernière n'existant pas dans la version officielle de l'image mysql, il a fallu choisir une image non officielle.
Nous avons donc décidé de choisir l'image clover/mysql. Cette image ayant plus de 10000 pulls, nous en avons déduit qu'elle était fiable et fonctionnelle.

Il nous faut également placer notre script .sql dans un répertoire bien particulier du conteneur qui sera créé à partir de cette image, le répertoire **/docker-entrypoint-initdb.d/**. Ce répertoire permet d'exécuter facilement le script .sql pour créer la base de données après la création du container. 
Le script quant à lui créé un utilisateur pour manipuler la base de données, deux utilisateurs de base dans l'application et instancie les trois tables SQL.  

On peut alors, comme pour notre dockerfilePHP, commencer par build l'image à partir du dockerfileMYSQL : 

```bash
docker build -t sqlimage -f ../../dockerConfig/dockerfileMYSQL .
```
On créé donc une image personnalisée nommée sqlimage à partir du dockerfileMYSQL. Cette commande doit être exécutée a l'endroit ou est situé le script .sql. 

Lorsqu'on a build l'image sql on peut démarrer un container avec la commande suivante : 

```bash
docker run --name mysqlcont -dit sqlimage
```

On créé donc un conteneur nommé mysqlcont à partir de l'image sqlimage. 
Puis on se connecte en sh (bash n'existant pas sur cette image) à ce conteneur : 

```bash
docker exec -it mysqlcont sh
```

Après cela, il faut executer la commande suivant pour se accèder à mysql : 

```bash
mysql -h 127.0.0.1 -u root -proot
```

Pour exécuter le script .sql et donc l'insérer dans le conteneur, il faut effectuer la commande suivante dans le dossier ```/docker-entrypoint-initdb.d/``` : 

```bash 
\. database_script.sql
```

Le script .sql à bien été executé au lancement du conteneur comme le montre l'image suivante : 

<img src="Images/ImagesRapports/SQLContRapportInstall.PNG">


<h3 style="color:#5d79e7" id="III_creationDockerSwarm"> D) Création du docker swarm</h3>

On installe docker s'il ne l'est pas encore.
Ensuite, on initialise un docker swarm, et on rajoute 4 workers, où le manager est le RasperryPi principal, et chaque worker est un RaspberryPi zero.
L'objectif ensuite est d'avoir 1 stack contenant l'application déployée et 1 autre stack contenant l'application en production. 
Ce docker swarm contiendra deux services, respectivement un service pour le serveur web et un service pour le serveur sql. 

Après avoir vérifié le bon fonctionnement de nos conteneurs et des deux services php et mysql, on crée le swarm : 

On commence donc par initialiser le swarm dans le RPI Host avec la commande : 

```bash
docker swarm init --advertise-addr 172.19.181.254
```

On fait ensuite rejoindre les workers (donc les 4 RPI0) avec la commande : 

```bash
docker swarm join --token SWMTKN-1-2lcnd86mpg7d8x64b1nyspdm6ezhujg6tbdqzxk4ugr3vhio2r-ctzz5dlvv7q0b73bl7kgc5n6n 192.168.0.25:2377
```

Etant donné que l'on veut héberger notre service web uniquement dans les quatre RPI Zero, on doit leur donner à chacun un label spécifique. Lorsqu'un service sera créé, on pourra lui indiquer une contrainte de placement en fonction d'un label. Par exemple, on associe aux quatre RPI zero un label service=web, si on créé un service avec pour contrainte de placement service=web, ce dernier ira placer ses réplicas sur les quatre RPI Zero et pas sur le RPI Host. 

On utilise donc la commande suivante pour donner un label aux différents nodes qui vont héberger le service web : 

```bash
docker node update --label-add service=web p2 
```

On effectue la même opération pour le service sql avec la commande suivante : 

```bash 
docker node update --label-add service=sql cnat
```

<h3 style="color:#5d79e7" id="III_creationStackAppli"> E) Création du stack de l'application </h3>

Après avoir créé les deux images et initialisé le swarm, nous allons créer un stack dans le swarm nous permettant de déployer deux services liés aux deux images. 

Un stack est un outil permettant de gérer un ensemble de services, sur plusieurs machines, sur plusieurs noeuds, et selon certaines contraintes au sein d'un swarm. Un service correspond à une définiton de taches à exécuter par un ou plusieurs nodes du swarm. 

Ainsi, dans notre stack, nous allons créer un service BD qui lancera un conteneur se basant sur l'image sqlimage précédemment créée, ainsi qu'un service web qui lancera un conteneur se basant sur l'image phpimage précédemment créée. 

Cependant, comme énoncé précédemment, on souhaite déployer le service web sur quatre noeuds, l'image PHP doit donc également être présente sur ces quatre noeuds. 
Pour ce faire, nous avons choisi de mettre en ligne nos deux images PHP et MySQL sur la banque d'images docker DockerHub.

Pour mettre nos images en ligne, il a fallu créer un compte docker ainsi qu'un repository docker par images a stocker :  

<img src="Images/ImagesRapports/DockerHubREPO.PNG">

En local, pour mettre nos images en ligne, il a fallu se connecter a notre compte docker hub avec la commande suivante :
```bash
docker login --username=wzehren --email=williamzehrentravail@gmail.com
```
Et entrer le mot de passe du compte.

Puis il a fallu créer un alias aux images à push de la même manière que le repository avec la commande suivante : 

Pour l'image phpimage
```bash
docker tag phpimage wzehren/phpsae
```
Pour l'image sqlimage
```bash
docker tag sqlimage wzehren/mysqlsae
```

On peut ensuite les push sur docker hub avec la commande suivante : 

Pour l'image phpimage
```bash
docker push wzehren/phpsae
```

Pour l'image sqlimage
```bash
docker push wzehren/mysqlsae
```

Après avoir réalisé ces étapes, on peut se placer sur chacun des RPI (zero et host) pour pull, et donc installer leurs images respectives (wzehren/phpsae pour les RPI zero et wzehren/mysqlsae pour le RPI Host) avec la commande suivante : 

Pour l'image phpimage
```bash
docker pull wzehren/phpsae
```

Pour l'image sqlimage
```bash
docker pull wzehren/mysqlsae
```

Ensuite, on créé un fichier de configuration .yml pour le stack qui va permettre de créer automatiquement les deux services, en spécifiant le nombre de réplicas, leurs redirections de port, leurs volumes ainsi que les contraintes de placement de ces derniers sur les noeuds : 

```yml
version: "3.0"

services:
  serviceweb:
    image: wzehren/phpsae
    ports:
      - "80:80"
    deploy:
      placement:
        constraints: [node.labels.service == web ]
      replicas: 4
    volumes:
      - "/home/pi/webVolume:/hostpipe"

  servicebd:
    image: wzehren/mysqlsae
    ports:
      - "3306:3306"
    deploy:
      placement:
        constraints: [node.labels.service == sql ]
      replicas: 1
    volumes:
      - "/var/lib/mysql_volume_app:/var/lib/mysql"
```

Le volume du service _servicebd_ permet d'assurer la persistance des données du service, à savoir les données contenues dans le serveur de base de données MySQL, comme les bases de données et utilisateurs par exemple. <br>
En d'autre terme, cela évite la suppression des données de l'application contenues dans le service lorsque le stack est recréé, car ces dernières sont stockées sur le rpi host et non dans le réplica du service.
Le volume du service _serviceweb_ permet de créer comme le volume du service ci-dessus, un répertoire partagé qui va nous être utile pour l'exécution de certaines commandes dans l'application.

Ensuite, pour déployer le stack sur le swarm, on utilise la commande : 

```bash 
docker stack deploy --compose-file docker-compose-stack.yml appli
```

Pour voir que les services sont bien déployés, on peut effectuer la commande : 

```bash
docker service ls
```

Ce qui donne le résultat suivant : 

<img src="Images/ImagesRapports/dockerServices.png">

On voit bien que les deux services ont été créés et ont bien été répliqués.
Pour visualiser les différents services de manière plus claire, on créé un conteneur visualizer qui permet d'observer les différents services présents dans le swarm et leur répartition dans les différents nodes. 

<img src="Images/ImagesRapports/dockerVisualizer.png">  

<br>
<br>

**Une fois l'application déployée, on peut accèder au site web à partir de l'adresse ip suivante :** http://85.170.243.176/ 

**Le site web est donc bien accessible et on peut se connecter à son compte normalement** 

On peut également accèder au visualizer depuis l'adresse ip suivante : http://85.170.243.176:5000/ 

(Cette adresse n'est accessible qu'a des fins de test et ne sera plus accessible lors du deploiement du site web)

<h3 style="color:#5d79e7" id="IV_repertoire_partage_named_pipe"> F) Lancement de commandes sur le rpi host depuis un conteneur </h3>

Une fois l'application lancée et disponible sur naviguateur depuis n'importe quel réseau internet, en entrant l'adresse ip http://85.170.243.176/, il reste une dernière étape pour que le suite soit totalement fonctionnel. <br>
En effet, à partir du livrable 2, nous avons besoin d'exécuter plusieurs commandes sur le rpi, comme une commande pour exécuter un programme mpi de calcul des nombres premiers dans le module 1, ou encore une autre qui récupère les statistiques cpu, mémoire, ... de chaque rpi du Cluster Hat. <br>
Cependant, il est à ce stade impossible d'exécuter ces commandes sur un rpi car l'application est dans un conteneur docker, ainsi ces commandes seront exécutées sur le conteneur et non sur le rpi. <br>

_Tous les tests ont été effectués sur le rpi zero pi3_.

<h4 style="color:#859bed" id="IV_1"> 1. Création d'un tube nommé </h4>

Pour ce faire, nous allons en premier lieu créer un _tube nommé_ ou _named pipe_ avec la commande suivante, qui est un tube unidirectionnel (FIFO) permettant d'envoyer des données. Ce tube est un fichier particulier.

```bash 
pi@p3:~ $ mkfifo pipe_test
```

On vérifie ensuite si le tube a bien été créé
```bash 
pi@p3:~ $ ls -lh pipe_test
prw-r--r-- 1 pi pi 0 Jan 17 12:50 pipe_test
```

On vérifie son bon fonctionnement sur le rpi. <br>
Pour ce faire, on ouvre une première fenêtre dans lequel on exécute la commande suivante pour écouter le tube.

```bash
pi@p3:~ $ tail -f pipe_test #fenetre 1
```

Puis on ouvre une deuxième fenêtre dans lequel on envoie un message dans le tube grâce à la commande suivante, message qu'on doit avoir affiché dans la première fenêtre.

```bash
pi@p3:~ $ echo "test pipe" > pipe_test #fenetre 2
```

On revient dans la première fenêtre et on constate bien que le message a été envoyé.

```bash
pi@p3:~ $ tail -f pipe_test #fenetre 1
test pipe
```

<h4 style="color:#859bed" id="IV_2"> 2. Envoi de commandes dans le tube nommé </h4>

La prochaine étape consiste à pouvoir envoyer des commandes dans le pipe, pour qu'elles soient ensuite exécutées. En effet, pour l'instant, si on exécute la commande suivante, cela nous renvoie cette chaine de caractères dans la première fenêtre.

```bash
pi@p3:~ $ echo uptime > pipe_test #fenetre 2
```

```bash
pi@p3:~ $ tail -f pipe_test  #fenetre 1
test pipe
uptime
```

Pour résoudre ce problème, nous allons mette fin à l'écoute du tube nommé dans la fenêtre 1 avec la combinaison de touches *CTRL+C*, puis nous allons lancer cette commande : 

```bash
pi@p3:~ $ while true; do eval "$(cat pipe_test)"; done #fentre 1
```

Et cette fois, si on renvoi une commande dans le pipe, elle est bien exécutée

```bash
pi@p3:~ $ echo uptime > pipe_test #fenetre 2
```
```bash
pi@p3:~ $ eval "$(cat pipe_test)"
 13:10:28 up 13 days, 20:31,  2 users,  load average: 0.00, 0.10, 0.22
```
On obtient bien le résultat de la commande

<h4 style="color:#859bed" id="IV_3"> 3. Exécution de commandes dans le rpi depuis un conteneur </h4>

Si on revient à notre problématique de départ, l'objectif est d'exécuter des commandes dans le rpi depuis un conteneur docker, cela est maintenant possible grâce au tube nommé. <br>
Pour ce faire, nous allons reprendre les répertoires parcréer depuis le rpi un tube nommé dans le répertoire _/home/pi/webVolume/_ partagé entre le rpi et le conteneur avec la commande suivante : 

```bash 
pi@p3:~ $ mkfifo /home/pi/webVolume/pipe_conteneur #fentre 1
```

On modifie les droits d'écriture du tube pour que tout le monde puisse y envoyer des données, et le met à l'écoute avec la commande : 
```bash 
pi@p3:~ $ chmod a+w /home/pi/webVolume/pipe_conteneur #fentre 1
pi@p3:~ $ while true; do eval "$(cat /home/pi/webVolume/pipe_conteneur)"; done #fentre 1
```

Après, on vérifie qu'il est présent dans le répertoire _hostpipe_, et qu'on peut y envoyer des commandes depuis le conteneur de l'application.
On le met à l'écoute avec la commande : 
```bash 
pi@p3:~ $ docker exec -it e3f5c734a64a sh #fentre 2
$ cd /hostpipe
$ ls -lh pipe_conteneur
prw-rw-rw- 1 1000 1000 0 Jan 17 12:20 pipe_conteneur
```

On lance la commande suivante depuis la fenêtre 2, qui est utilisée dans le module 1 de notre application, et qui permet de calculer les nombres premiers compris entre _1_ et _100_, et enregistre le résultat dans le fichier _result_calcul_nb_premiers.json_ situé dans le répertoire partagé _/home/pi/webVolume/repoOutputResults/_.
```bash 
$ echo "ssh cnat mpiexec -n 3 --host pi3,pi2,pi4 python /home/pi/webVolume/repoPythonScripts/prime.py 1 100 /home/pi/webVolume/repoOutputResults/result_calcul_nb_premiers.json --mca btl_tcp_if_include 172.19.181.0/24" > /hostpipe/pipe_conteneur
```

On attend quelques secondes le temps que la commande soit exécutée, puis on visualise le contenu du fichier _result_calcul_nb_premiers.json_ depuis le rpi, et on obtient le résultat suivante :
```bash 
pi@p3:~ $ cat /home/pi/webVolume/repoOutputResults/result_calcul_nb_premiers.json
{
   "executionTime": 0.02,
   "primeNumbersList": [
      1,
      3,
      5,
      7,
      11,
      13,
      17,
      19,
      23,
      29,
      31,
      37,
      41,
      43,
      47,
      53,
      59,
      61,
      67,
      71,
      73,
      79,
      83,
      89,
      97
   ]
}
```
Il est maintenant possible d'exécuter des commandes sur rpi depuis l'application sur internet, contenue dans un conteneur docker grâce à un tube nommé. <br>
Nous allons répéter ces mêmes étapes pour chaque module de l'application et pour la page administrateur du site où les statistiques de chaque rpi du Cluster Hat doivent être récupérées et affichées. <br>

<h4 style="color:#859bed" id="IV_4"> 4. Automatisation de la mise en écoute des tubes nommés </h4>

Enfin, pour éviter d'avoir à lancer une commande pour écouter le tube nommé, nous allons faire en sorte qu'il soit à l'écoute automatiquement au démarrage du rpi.
Pour ce faire, nous allons pour chaque tube nommé, créer un script bash contenant l'exécution de ce dernier. 
```bash 
pi@p3:~ $ touch /home/pi/webVolume/repoBashScripts/start_pipe_module_calcul_pi.sh
pi@p3:~ $ touch /home/pi/webVolume/repoBashScripts/start_pipe_module_nb_premiers.sh
pi@p3:~ $ touch /home/pi/webVolume/repoBashScripts/start_pipe_stats_cluster_hat.sh
```

Puis nous allons planifier l'exécution de ces scripts pour qu'ils soit exécutés à chaque redémarrage du rpi. Cela est rendu possible grâce à l'outil **Crontab** qui permet de lancer des programmes, commandes à une heure spécifique ou selon une période de temps.

```bash 
pi@p3:~ $ crontab -e
  GNU nano 5.4                                                                                                                                     /tmp/crontab.UIXpgN/crontab
# Edit this file to introduce tasks to be run by cron.
#
# Each task to run has to be defined through a single line
# indicating with different fields when the task will be run
# and what command to run for the task
#
# To define the time you can provide concrete values for
# minute (m), hour (h), day of month (dom), month (mon),
# and day of week (dow) or use '*' in these fields (for 'any').
#
# Notice that tasks will be started based on the cron's system
# daemon's notion of time and timezones.
#
# Output of the crontab jobs (including errors) is sent through
# email to the user the crontab file belongs to (unless redirected).
#
# For example, you can run a backup of all your user accounts
# at 5 a.m every week with:
# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/
#
# For more information see the manual pages of crontab(5) and cron(8)
#
# m h  dom mon dow   command

@reboot /home/pi/webVolume/repoBashScripts/start_pipe_module_calcul_pi.sh
@reboot /home/pi/webVolume/repoBashScripts/start_pipe_module_nb_premiers.sh
@reboot /home/pi/webVolume/repoBashScripts/start_pipe_stats_cluster_hat.sh
```