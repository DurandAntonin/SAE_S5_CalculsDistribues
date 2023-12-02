#!/usr/bin/bash

hostHostName=cnat
nodesHostnamesPrefix=pi
numberOfNodes=4
defaultUserNode=pi

#on update le rpi host
sudo apt-cache update
sudo apt-cache upgrade

#on update chaque rpi nodes
for (( i=1;i<=$numberOfNodes;i++ )); do
	#on construit le nom complet du node
        nodeHostname=${nodesHostnamesPrefix}${i}
	echo Traitement du node pi${i}

	ssh ${nodeHostname} /bin/bash << EOF
	sudo apt-get update
	sudo apt-get upgrade

        echo Mise à jour terminée
        echo -e "\n"
EOF
done

#on éteint les nodes, puis on les rallume
echo On atteind les nodes
clusterctrl off
echo On attend 5s ...
sleep 5
echo On rallume les nodes ...
clusterctrl on

#on attend 120s pour commencer à installer docker
echo On attend 120s avant d'installer docker ...
sleep 120

#on installe docker sur chaque node
for (( i=1;i<=1;i++ )); do
        #on construit le nom complet du node
        nodeHostname=${nodesHostnamesPrefix}${i}
        echo Traitement du node pi${i}

	#on désinstalle l'ancienne version de docker
	apt-get purge docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin docker-ce-rootless-extras

	#on installe ensuite docker sur chaque node
        #on l'active au démarage
        #on ajoute le user du node dans le groupe docker
        echo -e "\nInstallation de docker ..."
        ssh ${nodeHostname} /bin/bash << EOF
        curl -sSL https://get.docker.com | sh
        sudo usermod -a -G docker ${defaultUserNode}

        echo Installation de docker réussie
        echo -e "\n\n"
EOF
done
