#! /bin/bash
echo "Update in progress <br>"
cd /tmp
sudo rm -fr ThiDom
git clone https://github.com/tguillaume02/ThiDom
cd ThiDom
sudo rm Script_domotique/msql.py
sudo rm www/ThiDom/Core/class/db.class.php
sudo mv Script\ crontab/update_thidom.sh Script\ crontab/update_thidom_new.sh
sudo cp -r Script_domotique/ /home/ThiDom/
sudo cp -r Script\ crontab/ /home/ThiDom/
sudo cp -r www/ /var/
sudo mv /home/ThiDom/Script\ crontab/update_thidom_new.sh /home/ThiDom/Script\ crontab/update_thidom.sh
 
sudo chmod +x /home/ThiDom/Script\ crontab/*
sudo chmod 755 /home/ThiDom/Script\ crontab/*
sudo chmod 655 /home/ThiDom/Script\ crontab/debug/*
sudo chmod +x /home/ThiDom/Script_domotique/*
sudo chmod 775 -R /var/www
sudo chown -R www-data:www-data /var/www
sudo wget -q https://localhost/ThiDom/Desktop/UpdateDB.php --no-check-certificate >/dev/null
sudo rm /var/www/ThiDom/Desktop/UpdateDB.php
sudo rm -r /tmp/ThiDom
echo "End update"