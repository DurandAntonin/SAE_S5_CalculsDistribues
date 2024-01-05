#!/bin/bash

nbArgumentsScript=1
hostName=
repoOutputFile=/home/pi/pipeDockerSwarm/outputsStats/
outputFileName=
outputFile=
enteteFile="piName;cpuUsage;cpuFrequency;memTotal;memUsed;uptime"

declare -a rpiNameList
rpiNameList[0]="cnat"
rpiNameList[1]="pi2"
rpiNameList[2]="pi2"
rpiNameList[3]="pi3"
rpiNameList[4]="pi4"

#for i in "${rpiNameList[*]}"; do echo "$i"; done

#on vérifie qu'on a passé le nom du fichier en paramètre
if (( $#==$nbArgumentsScript )); then
  outputFileName="$1"

  #on récupère le hostname courant dans le réseau
  hostName=$(hostname)
  hostName="pi$(expr substr $hostName 2 1)"

  #on vérifie que le répertoire output existe bien
  if [[ -d "$repoOutputFile" ]]; then
    outputFile="${repoOutputFile}${outputFileName}"
    #echo $outputFile;
    #on supprime le fichier output s'il existe déjà
    if [[ -f "$outputFile" ]]; then
      rm $outputFile;
    fi
    #on met l'entete dans le fichier
    echo $enteteFile > $outputFile

    #pour chaque rpi du cluster, on va récupérer lusage cpu, lusage memoire et le uptime
    for i in "${rpiNameList[@]}"; do
      #echo "$i"
      hostNameStat=$i
      cpuUsageStat=
      cpuFrequencyStat=
      memTotal=
      memUsed=
      uptimeStat=

      #on se connecte pas en ssh si on recupere les stats du rpi hostname dans leque le script s'execute
      if [[ $i = $hostName ]]; then
        cpuUsageStat=$(top -b -n 1 | grep "Cpu" | awk '{print $2}')
        cpuFrequencyStat=$(sudo cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_cur_freq)
        memTotal=$(free -m | grep "Mem" | awk '{print $2}')
        memUsed=$(free -m | grep "Mem" | awk '{print $3}')
        uptimeStat=$(uptime -p)
      else
        cpuUsageStat=$(ssh $i top -b -n 1 | grep "Cpu" | awk '{print $2}')
        cpuFrequencyStat=$(ssh $i sudo cat /sys/devices/system/cpu/cpu0/cpufreq/cpuinfo_cur_freq)
        memTotal=$(ssh $i free -m | grep "Mem" | awk '{print $2}')
        memUsed=$(ssh $i free -m | grep "Mem" | awk '{print $3}')
        uptimeStat=$(ssh $i uptime -p)
      fi

      cpuFrequencyStat=$(echo $cpuFrequencyStat 1000000 | awk '{print $1/$2}')

      #on stocke ces stats dans une chaine de caracteres, espaces par des ';'
      piStats="${hostNameStat};${cpuUsageStat};${cpuFrequencyStat};${memTotal};${memUsed};${uptimeStat}"
      #echo $cpuUsageStat
      #on écrit ajoute cette ligne a la fin du fichier
      echo $piStats >> $outputFile
    done
  fi
fi