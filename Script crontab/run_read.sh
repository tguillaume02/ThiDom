#!/bin/bash
 > /dev/ttyUSB1
sudo service ntp stop
sudo ntpd -q -g
sudo service ntp start

sudo /home/pi/Script\ crontab/kill_read.sh
python /home/pi/Script_domotique/read_usb.py >> /home/pi/Script\ crontab/debug/console.log 2>&1 &
