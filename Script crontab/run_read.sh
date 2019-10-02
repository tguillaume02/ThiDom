#!/bin/bash
# > /dev/ttyUSB1

#sudo service ntp stop
#sudo ntpd -q -g
#sudo service ntp start
#sudo sntp -s 24.56.178.140
sudo sntp -s 0.fr.pool.ntp.org

sudo /home/ThiDom/Script\ crontab/kill_read.sh
python3 -u /home/ThiDom/Script_domotique/read_usb.py >> /home/ThiDom/Script\ crontab/debug/console.log 2>&1 &
