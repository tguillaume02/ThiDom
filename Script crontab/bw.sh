#!/bin/bash 

# Indique la bande passante au moment de l'execution pour une interface donnée en paramètre.
# Utilisation : ./bw.sh eth0
# Sortie      : <BP IN>:<BP OUT> 

in_old=`cat /proc/net/dev | grep "$1:" | cut -d ":" -f 2 | awk '{print $1}'`
out_old=`cat /proc/net/dev | grep "$1:" | cut -d ":" -f 2 | awk '{print $9}'`
sleep 1
in=`cat /proc/net/dev | grep "$1:" | cut -d ":" -f 2 | awk '{print $1}'`
out=`cat /proc/net/dev | grep "$1:" | cut -d ":" -f 2 | awk '{print $9}'`

bwin=$((((in-in_old)/1024)))
bwout=$((((out-out_old)/1024)))

echo "$bwin:$bwout"

 
