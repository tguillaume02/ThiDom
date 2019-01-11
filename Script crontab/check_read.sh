#!/bin/bash

#[ `find /home/ThiDom/Script\ crontab/debug/temperature.json -mmin +16` ] &&
#strDate=$(date) &&
#strAct=" Relance read usb" &&
#echo $strDate $strAct >> /home/ThiDom/Script\ crontab/debug/cronNode.txt  &&
#/home/ThiDom/Script\ crontab/run_read.sh



if  ! ps ax | grep -v grep |grep read_usb.py > /dev/null	
then
	strDate=$(date) &&
	strAct=" Relance read usb" &&
	echo $strDate $strAct >> /home/ThiDom/Script\ crontab/debug/cronNode.txt  &&
	/home/ThiDom/Script\ crontab/run_read.sh
fi
