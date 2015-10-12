#o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import sys
import time
import urllib2
import urllib
import msql

try:
	db = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
	time.sleep(0.2)
	cursor = db.cursor()
	strSQL = db.cursor()

except db.Error, e:
	print "Error %d: %s" % (e.args[0],e.args[1])
	sys.exit(1)


#while True:
try:
	time.sleep(0.2)
	cursor.execute("INSERT INTO Temperature (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') as date , ROUND(AVG(temp),2) as avg, lieux, Etat_IO_ID from Temperature_Temp where date between (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL 15 MINUTE - INTERVAL 1 SECOND) and (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00')) group by lieux)")			
	cursor.execute("DELETE FROM Temperature_Temp WHERE date between (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL  20 MINUTE - INTERVAL 1 SECOND) and (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00'))")
	mon_fichier = open("/home/pi/text.txt","w")
	mon_fichier.write ("test")
	mon_fichier.close()
	db.close()
	time.sleep(1)
	sys.exit()			
except KeyboardInterrupt:
	print "Bye"
	sys.exit()
