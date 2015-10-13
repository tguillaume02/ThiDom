#!/bin/bash

[ `find /var/www/nodejs/public_html/temperature.json -mmin +16` ] &&
strDate=$(date) &&
strAct=" Relance read usb" &&
echo $strDate $strAct >> /home/pi/Script\ crontab/debug/cronNode.txt  &&
/home/pi/Script\ crontab/run_read.sh
