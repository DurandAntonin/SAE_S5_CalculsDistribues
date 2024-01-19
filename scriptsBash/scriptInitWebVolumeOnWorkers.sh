#!/usr/bin/bash

nbArgumentsScript=2 #nombre d'arguments du script
volumePath= #chemin et nom du volume
repoBashScript= #repertoire contenant les scripts bash a mettre dans le volume
numberOfWorkerNodes=4 #nombre de workers

declare -a listWorkerHostname #liste contenant le hostname de chaque worker
listWorkerHostname[1]="pi2"
listWorkerHostname[2]="pi3"
listWorkerHostname[3]="pi4"
#listWorkerHostname[4]="pi1"

#tubes nommées
pipeModule1="pipe_module_nb_premiers"
pipeModule2="pipe_module_calcul_pi"
pipeStatsClusterHat="pipe_stats_cluster_hat"

#on verifie qu'on a le bon nombre d'arguments
if (( $#==$nbArgumentsScript )); then
  volumePath="$1"
  repoBashScript="$2"

  #chemin complet pour les scripts bash et les résultats
  completePathToRepoBashScripts="${volumePath}/repoBashScripts/"
  completePathToRepoOutputResults="${volumePath}/repoOutputResults/"

  for i in "${listWorkerHostname[@]}"; do
    echo "Hostname : ${i}"

    echo "Suppression de l'ancien répertoire volume ..."
    #on se connecte en ssh a chaque worker et on supprime le repertoire volume s'il existe pour le recréer ensuite
    ssh ${i} /bin/bash << EOF
        	rm -rf ${volumePath}/
EOF

    echo "Création de l'architecture du volume à l'hostname ..."
    #on créé le repertoire du volume, les tubes nommes, le repo pour les scripts et le repo pour les resultats
    ssh ${i} /bin/bash << EOF
    	mkdir ${volumePath} && mkfifo ${volumePath}/${pipeModule1} && mkfifo ${volumePath}/${pipeModule2} && mkfifo ${volumePath}/${pipeStatsClusterHat}&& mkdir ${completePathToRepoBashScripts} && mkdir ${completePathToRepoOutputResults}
EOF

    echo "Copie des scripts bash ..."
    #on copie ensuite les scripts bash dans le repertoire
    scp -r ${repoBashScript}/* ${i}:${completePathToRepoBashScripts}

    echo "Modifications des droits ..."
    printf "\n"
    #on ajoute des droits spcéficiques
    ssh ${i} /bin/bash << EOF
      chmod 777 ${volumePath} && chmod 777 ${completePathToRepoOutputResults} && chmod a+w ${volumePath}/${pipeModule1} ${volumePath}/${pipeModule2} ${volumePath}/${pipeStatsClusterHat} && chmod a+x ${completePathToRepoBashScripts}*.sh
EOF

  done
else
  echo "Le script a besoin de 2 arguments"
fi