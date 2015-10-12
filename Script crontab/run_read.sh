#!/bin/bash

sudo /home/pi/Script\ crontab/kill_read.sh
python /home/pi/Script_domotique/read_usb.py >> /var/www/nodejs/log/console.log 2>&1 &
