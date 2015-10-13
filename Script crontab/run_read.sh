#!/bin/bash

sudo /home/pi/Script\ crontab/kill_read.sh
python /home/pi/Script_domotique/read_usb.py >> /home/pi/Script\ crontab/debug/console.log 2>&1 &
