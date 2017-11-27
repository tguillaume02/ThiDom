#!/bin/bash

#IP=`ifconfig wlan0 | grep 'inet '  | tr -d [:alpha:] | tr -s [:space:] | cut -d: -f2`
#IP_fixe='192.168.1.25'

#if [ $IP -eq ' ' ]
#then
#	echo "not exist"
#else
#	if [ $(cat /sys/class/net/wlan0/carrier) -eq 0 ] || [ $IP -ne $IP_fixe ]
#	then
#		sudo /etc/init.d/networking restart &&  touch /home/pi/Script\ crontab/debug/cronNode.txt && strDate=$(date) && strAct=" Relance wlan0" && echo $strDate $strAct $IP  " //  "  $IP_fixe >> /home/pi/Script\ crontab/debug/cronNode.txt
# 	fi
#fi

ping -c2 192.168.1.1 > /dev/null
# If the return code from ping ($?) is not 0 (meaning there was an error)
if [ $? != 0 ]; then
    sudo /etc/init.d/networking restart &&  touch /home/pi/Script\ crontab/debug/cronNode.txt && strDate=$(date) && strAct=" Relance wlan0" && echo $strDate $strAct $IP  " //  "  $IP_fixe >> /home/pi/Script\ crontab/debug/cronNode.txt
fi

