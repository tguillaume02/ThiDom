#!/bin/bash

########## SUPPRIME LES BACKUPS SUPERIEUR à 3 JOURS  ###############

find /home/pi/backup_bdd_* -mtime +2 -exec rm -rf {} \;


NOW=$(date +"%d_%m_%Y")
FILE="/home/pi/backup_bdd_$NOW.sql"

mysqldump thidom > $FILE
