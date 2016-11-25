#o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import time
import datetime
import urllib
import urllib2
import sys
import os
import msql
import json
import ssl

processus = 'mysqld'
s = os.popen('ps ax').read()
while processus not in s:
	n = 1
	time.sleep(0.2)
	s = os.popen('ps ax').read()    

timeout = 0

ser = serial.Serial( port = '/dev/ttyACM0', baudrate =9600 )

def Action(New_status,DeviceID,CarteID,Nom,Type,Lieux,ID,sensor_attach_value):
	if Type == "Chauffage":
		#val = "Chauffage_"+str(DeviceID)+"#"+str(New_Status)+"\n"
		if float(sensor_attach_value) < float(New_Status):
			str_action = "1"
		else:
			str_action = "0"
		val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str_action+"\n"
		# print val
		written = ser.write(val)
	else:
		val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(New_Status)+"\n"
		# print val
		writen = ser.write(val)										
	now = datetime.datetime.now()
	now = now.strftime('%Y-%m-%d %H:%M:%S')
	cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s,%s,%s, %s)", (ID,now,val, "Planning: "+Type+" "+Lieux+" "+Nom+" " +str(CarteID) +" " +str(DeviceID) +" " +str(New_Status)))



while True:	
	try:
	 	time.sleep(0.2)
		# REMISE A ZERO DES ALERTES ACTIFS DEPUIS PLUS DE DEUX MINUTES
		#if time.time() > timeout or time.strftime('%S',time.localtime()) == '00':
		try:
			DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
			time.sleep(0.2)
			cursor = DbConnect.cursor()
		except MySQLdb.Error, e:
			if msql.idnotify!="":
				urllib.urlopen("http://notify8702.freeheberg.org/?id="+msql.idnotify+"&notif=Erreur connection bdd planning&id_notif:-3")
			print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error planning %s" % (e.args[1])
			sys.exit(1)

		#sql_alert = " select ID from Etat_IO where type = 'Alerte' and date < Date_SUB(now(), INTERVAL 2 MINUTE) and Etat = 1"			
		sql_alert = "select ID,Nom,Type_ID,Type,CarteID,Request,RAZ from ( select cmd_device.ID,Device.Nom,Etat,Device.Type_ID,Device.CarteID,Request,Date,Date_SUB(now(), INTERVAL RAZ MINUTE) as DateRaz, RAZ, Type_Device.Type  from cmd_device inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.Id = Device.Type_ID ) as t where t.Date< t.DateRaz and (t.Etat = 1 or t.Type_ID = 8) group by Request"

		cursor.execute(sql_alert)
		for row in cursor.fetchall():
			DeviceID = row[0]
			Nom = row[1]
			Type_ID = row[2]
			Type = row[3]
			CarteID = row[4]
			Request = row[5]
			RAZ = row[6]
			if Type_ID != 8:
				#cursor.execute("update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(DeviceID))
				#Action(0,DeviceID,CarteID,Nom,Type,Lieux,ID,sensor_attach_value)
				e="ee"
			elif Type_ID == 8:
				context = ssl._create_unverified_context()
				Request = json.loads(Request)
				url = "https://localhost/ThiDom/"+Request["url_ajax"]
				data = Request["data"]
				#url_values = urllib.urlencode(data)
				url_values = data
				full_url = urllib2.Request(url, url_values)
				try:
					exec_cmd = urllib2.urlopen(full_url,context=context)
				except urllib2.HTTPError as e:
					print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error planning Exec url " + e.code + "//" + e.read()
				#Minute = date('i');
				Minute = datetime.datetime.now().minute
				#if (Minute % RAZ) != 0:
				cursor.execute("update cmd_device set date =(select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute%RAZ)+" MINUTE)  where ID ="+str(DeviceID))
		
		#sql = "SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Carte_ID,  Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux, thermo.DeviceID,Etat_IO.ID FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID AND planning.STATUS != Etat_IO.Value left join Etat_IO as thermo on thermo.ID = Etat_IO.sensor_attachID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00' and ACTIVATE = 1"

		sql = " SELECT STATUS, cmd_device.DeviceID, Device.CarteID,  Device.Nom, Type_Device.Type, Lieux.Nom, sensor_attach.DeviceID,Device.ID, sensor_attach.value as sensor_attachValue FROM planning inner join cmd_device on cmd_device.ID = ETAT_IO_ID AND planning.STATUS != cmd_device.Value inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.Id = Device.Type_ID inner join Lieux on Lieux.Id = Device.Lieux_ID left join cmd_device as sensor_attach on sensor_attach.ID = cmd_device.sensor_attachID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00' and ACTIVATE = 1"

		#print sql
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
			sensor_attach_value = row[8]
#				print New_Status
#				print DeviceID
#				print Nom
#				print Type
#				print Lieux
			Action(New_status,DeviceID,CarteID,Nom,Type,Lieux,ID,sensor_attach_value)
		DbConnect.close()
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
