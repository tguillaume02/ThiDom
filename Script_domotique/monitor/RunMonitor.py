#o!/usr/bin/python
# -*- coding: utf-8 -*-

import os
import serial
import MySQLdb
import sys
sys.path.append('/home/pi/Script_domotique')
import time
import urllib2
import urllib
import msql
from SendNotification import SendNotification

from datetime import datetime

date = time.strftime('%y-%m-%d %H:%M:%S', time.localtime())
new_up = open("/home/pi/Script_domotique/monitor/last_up.txt", "r")
old_date = new_up.read()
new_up.close()

old_date =  datetime.strptime(old_date, "%y-%m-%d %H:%M:%S")
date = datetime.strptime(date, "%y-%m-%d %H:%M:%S")

diff = str(abs(old_date - date))
sOld_date = str(old_date)

def main():
    while True:
        try:
            date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
            new_up = open("/home/pi/Script_domotique/monitor/last_up.txt", "w")
            new_up.write(date)
            new_up.close()
            time.sleep(60)

        except KeyboardInterrupt:
            date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
            new_up = open("/home/pi/Script_domotique/monitor/last_up.txt", "w")
            new_up.write(date)
            new_up.close()
            print "Bye"
            sys.exit()
try:
    SendNotification("Coupure electrique depuis " + sOld_date + " duree " + diff , "-30")
    main()
except KeyboardInterrupt:
    print "Bye"
    sys.exit()
    