#! /bin/bash

cd /tmp
git clone https://github.com/tguillaume02/ThiDom
cd ThiDom
sudo rm Script_domotique/msql.py/
sudo rm www/ThiDom/Core/class/db.class.php
sudo cp -r Script_domotique/ /home/ThiDom/
sudo cp -r Script\ scrontab/ /home/ThiDom/
sudo cp -r www /var/

sudo chmod +x /home/ThiDom/Script\ crontab/*
sudo chmod 755 /home/ThiDom/Script\ crontab/*
sudo chmod 655 /home/ThiDom/Script\ crontab/debug/*
sudo chmod +x /home/ThiDom/Script_domotique/*
chmod 775 -R /var/www

sudo rm -r /tmp/ThiDom
