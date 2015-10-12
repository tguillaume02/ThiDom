#o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import datetime
import time
import urllib
import sys
import os
import msql

processus = 'mysqld'
s = os.popen('ps ax').read()
while processus not in s:
	n = 1
	time.sleep(0.2)
	s = os.popen('ps ax').read()

try:
	db = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
	time.sleep(0.2)
	cursor = db.cursor()

except db.Error, e:
	urllib.urlopen("http://notify8702.freeheberg.org/?id=thibault&notif=Erreur connection bdd Sunrise&id_notif:-9")
	print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error Scenario %d: %s" % (e.args[0],e.args[1])
	sys.exit(1)


ser = serial.Serial( port = '/dev/ttyACM0', baudrate =9600 )
sunrise = sys.argv[1]
sunset = sys.argv[2]
conditions = sys.argv[3]
#cursor.execute("UPDATE Sunrise_set set sunrise=%s,sunset=%s,conditions=%s", (sunrise, sunset,conditions))
cursor.execute("UPDATE cmd_device set Value=%s, Date=now() where Nom = 'Sunrise' ",  (sunrise))
cursor.execute("UPDATE cmd_device set Value=%s, Date=now() where Nom = 'Sunset' ",  (sunset))
cursor.execute("UPDATE cmd_device set Value=%s, Date=now() where Nom = 'Conditions'", (conditions))
cursor.execute("delete from Log where Log.date < SUBDATE(CURRENT_DATE, INTERVAL 15 DAY)")
cursor.close()
del cursor
db.close()
