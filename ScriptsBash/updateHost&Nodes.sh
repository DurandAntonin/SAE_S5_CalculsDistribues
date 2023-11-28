#!/bin/bash

hostHostName=cnat
nodesHostnamesPrefix=pi
nodesUser=pi
numberOfNodes=4

#on update le rpi host
apt -y update
apt -y upgrade

#on update chaque rpi nodes
for (( i=0;i<$numberOfNodes;i++ )); do
  #on construit le nom complet du node
  nodeHostname=${nodesHostnamesPrefix}${i}

  #on se connecte en ssh au rpi node, et on l'update
  ssh ${nodesUser}@{nodeHostname} /bin/bash << EOF
  apt -y update
  apt -y upgrade
  EOF

done