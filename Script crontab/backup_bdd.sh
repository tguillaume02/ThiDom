#!/bin/bash

########## SUPPRIME LES BACKUPS SUPERIEUR à 3 JOURS  ###############

#NOW=$(date +"%d_%m_%Y")
#FILE="/home/ThiDom/backup_bdd_$NOW.sql"

#mysqldump thidom > $FILE

#find /home/ThiDom/backup_bdd_* -mtime +2 -exec rm -rf {} \;

python3 /home/ThiDom/Script_domotique/backup_bdd.py  >> /home/ThiDom/Script\ crontab/debug/console.log 2>&1 &
