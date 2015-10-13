#o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import time
import datetime
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

timeout = 0

ser = serial.Serial( port = '/dev/ttyACM0', baudrate =9600 )

while True:	
	try:
	 	time.sleep(0.2)
		# REMISE A ZERO DES ALERTES ACTIFS DEPUIS PLUS DE DEUX MINUTES
		#if time.time() > timeout or time.strftime('%S',time.localtime()) == '00':
		try:
			db = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
			time.sleep(0.2)
			cursor = db.cursor()
		except db.Error, e:
			if msql.idnotify!="":
				urllib.urlopen("http://notify8702.freeheberg.org/?id="+msql.idnotify+"&notif=Erreur connection bdd planning&id_notif:-3")
			print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error planning %d: %s" % (e.args[0],e.args[1])
			sys.exit(1)

		#sql_alert = " select ID from Etat_IO where type = 'Alerte' and date < Date_SUB(now(), INTERVAL 2 MINUTE) and Etat = 1"			
		sql_alert = " select ID from ( select ID,Etat,Date,Date_SUB(now(), INTERVAL RAZ MINUTE) as raz  from cmd_device  ) as t where t.Date< t.raz and t.Etat = 1 "		
		cursor.execute(sql_alert)
		for row in cursor.fetchall():
			DeviceID = row[0]
			cursor.execute(" update cmd_device set cmd_device.Etat ='0' where ID ="+str(DeviceID))				
		
		#sql = "SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Carte_ID,  Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux, thermo.DeviceID,Etat_IO.ID FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID AND planning.STATUS != Etat_IO.Value left join Etat_IO as thermo on thermo.ID = Etat_IO.sensor_attachID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00' and ACTIVATE = 1"

		sql = " SELECT STATUS, cmd_device.DeviceID, Device.CarteID,  Device.Nom, Type_Device.Type, Lieux.Nom, thermo.DeviceID,Device.ID FROM planning inner join cmd_device on cmd_device.ID = ETAT_IO_ID AND planning.STATUS != cmd_device.Value inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.Id = Device.Type_ID inner join Lieux on Lieux.Id = Device.Lieux_ID left join cmd_device as thermo on thermo.ID = cmd_device.sensor_attachID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00' and ACTIVATE = 1"

		# print sql
		cursor.execute(sql)
		timeout = time.time() + 60
		for row in cursor.fetchall():
			New_Status = row[0]
			DeviceID = row[1]
			CarteID = row[2]
			Nom = row[3]
			Type = row[4]
			Lieux = row[5]
			ID = row[7]
#				print New_Status
#				print DeviceID
#				print Nom
#				print Type
#				print Lieux
			if Type == "Chauffage":
				#val = "Chauffage_"+str(DeviceID)+"#"+str(New_Status)+"\n"
				val = str(DeviceID)+"@"+str(New_Status)+":0/"+str(CarteID)+"\n"
				# print val
				written = ser.write(val)
			else:
				val = str(DeviceID)+":"+str(New_Status)+"/"+str(CarteID)+"\n"
				# print val
				writen = ser.write(val)
										
			now = datetime.datetime.now()
			now = now.strftime('%Y-%m-%d %H:%M:%S')
			cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s,%s,%s, %s)", (ID,now,val, "Planning: "+Type+" "+Lieux+" "+Nom+" " +str(CarteID) +" " +str(DeviceID) +" " +str(New_Status)))
		db.close()
		time.sleep(1)
		# db.autocommit(True)
	except KeyboardInterrupt:
		print "Bye"
	        sys.exit()
			
			
	# try:
		# if time.time() > timeout:
			# timeout = time.time() + 60*5
			# time.sleep(0.2)
			# cursor.execute("SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00'")

			# for row in cursor.fetchall():
				# New_Status = row[0]
				# DeviceID = row[1]
				# Nom = row[2]
				# Type = row[3]
				# Lieux = row[4]
				# if Type == "Chauffage":
					# val = str(DeviceID)+"#"+str(New_Status)+"/n"
					# written = ser.write(val)
				# else:
					# val = str(DeviceID)+":"+str(New_Status)+"/n"
					# writen = ser.write(val)	

	# except KeyboardInterrupt:
		# print "Bye"
	        # sys.exit()
