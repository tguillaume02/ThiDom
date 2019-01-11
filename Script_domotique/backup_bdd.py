import MySQLdb
import sys
import time
import msql
import subprocess
import os

subprocess.Popen("mysqldump -h" + msql.host + " -u" + msql.usr + " -p" + msql.pwd + " " + msql.db + " > /home/ThiDom/backup_bdd_" +  time.strftime("%d_%m_%Y") + ".sql", shell=True)

subprocess.Popen("find /home/ThiDom/backup_bdd_* -mtime +2 -exec rm -rf {} \;", shell=True)

# Wait for completion
#p.communicate()
# Check for errors
#if(p.returncode != 0):
    #raise
#print("Backup done for"+ msql.host)
#except:
#print("Backup failed for"+ msql.host)
