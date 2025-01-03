#!/bin/bash

nbArgumentsScript=2
hostName=
repoOutputFile=
outputFileName=
outputFile=

#on vérifie qu'on a passé le nom du fichier en paramètre
if (( $#==$nbArgumentsScript )); then
  repoOutputFile="$1"
  outputFileName="$2"

  #on vérifie que le répertoire output existe bien
    if [[ -d "$repoOutputFile" ]]; then
      outputFile="${repoOutputFile}${outputFileName}"
      #echo $outputFile;
      #on supprime le fichier output s'il existe déjà
      if [[ -f "$outputFile" ]]; then
        rm $outputFile;
      fi

      #on exécute une commande pour récupérer le hostname courant et on le met dans le fichier
      hostName=$(hostname)
      hostName="pi$(expr substr $hostName 2 1)"

      #echo outputFile
      echo $hostName > $outputFile
    fi
fi