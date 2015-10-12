# -*- coding: utf-8 -*-

import thread
import serial
import MySQLdb 
import sys 
import datetime
import time
import urllib2
import urllib
import os
import msql

processus = 'mysqld'
s = os.popen('ps ax').read()

while processus not in s:
	n = 1
	time.sleep(0.2)
	s = os.popen('ps ax').read()

try:
	db =MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
	cursor = db.cursor()

	try:
		url = "http://notify8702.freeheberg.org/"
		data = {}
		data['id'] = 'thibault'
		data['notif'] = "Demarrage read usb"
		data['id_notif'] = "-1"
		url_values = urllib.urlencode(data)
		full_url = url + '?' + url_values
		urllib2.urlopen(full_url)
	except IOError,e:
		pass

except db.Error, e:  
	print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error %d: %s" % (e.args[0],e.args[1])
	sys.exit(1)

ser = serial.Serial(port = '/dev/ttyACM0', baudrate = 9600)

#print ser

def ReadArduino():
	global Alert_LastTime
	Alert_LastTime ='1900-01-01 00:00:00'
	while True: 
		try:
			x = ""
			x = ser.readline() # read one byte
			str_usb_read = x
			date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
			mon_fichier = open("/var/www/nodejs/public_html/toto.txt","a")
			mon_fichier.write(date+" : ")
			mon_fichier.write(x)
			mon_fichier.write("\r\n")
			mon_fichier.close()
			x = x.replace("\r\n", "")


			# planning()
			# send or receive by arduino device_lieux:act
			#x ="Chauffage_Salon:Chauffage salon_17"
			#x ="Temperature_Exterieur:11"
			#print x

			if ":" in x:
				#mon_fichier = open("/var/www/nodejs/public_html/toto.txt","a")
				#mon_fichier.write(x)
				#mon_fichier.write("\r\n")
				type = "" 
				pinID = ""
				value = ""
				status = ""
				SlaveCarteId = ""

				if "/" in x:
					#SlaveCarteId,x = x.split("/")
					try:
						x,SlaveCarteId = x.split("/")
					except:
						print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + "value: " + x

				try:
					pinID, value = x.split(":")
				except:
					 print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + "value: " + x

				status = value
				#type_lieux, value = x.split(":")
				if "_" in pinID:
					try:
						type,pinID = pinID.split("_")
					except:
						print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + "value: " + pinID

				#type,lieux = type_lieux.split("_")
				# if "@" in status:
					# value,status = status.split("@")

				if "@" in pinID:
					status = value
					try:
						pinID,value = pinID.split("@")
					except:
						print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + "value: " + x
				else:
					status = value

				############ TEMPORAIRE A RETIRER QUAND CORRECTIF FAIT SUR ARDUINO

				if status == "":
					status = value

				pinID = pinID.strip()
				type = type.strip()
				status = status.strip()
				value = value.strip()
				x = ""

				if type == "Temp":
					Temp(pinID,value)
				elif type == "Alert":
					Alert(str_usb_read)
				else:
					#Action(value,status,pinID,SlaveCarteId,str_usb_read)
						date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
						#cursor.execute("UPDATE Etat_IO SET ETAT=%s, Date=%s where
						#Type=%s and Lieux=%s and Nom=%s ",
						#(value,date,type,lieux,device))
						#cursor.execute("UPDATE Etat_IO SET Value=%s, ETAT=%s, Date=%s where
						#Type=%s and Lieux=%s ", (value,status,date,type,lieux))
						#print (SlaveCarteId)
						if SlaveCarteId != "":
							#print ("UPDATE Etat_IO SET Value=%s, Etat=%s, Date=%s where DeviceID=%s and Carte_ID=%s", (value,status,date,pinID,int(SlaveCarteId)))
							#cursor.execute("UPDATE Etat_IO SET Value=%s, Etat=%s, Date=%s where DeviceID=%s and Carte_ID=%s", (value,status,date,pinID,int(SlaveCarteId)))
							cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteID=%s and cmd_device.Device_ID = Device.ID", (value,status,date,pinID,int(SlaveCarteId)))
							now = datetime.datetime.now()
							now = now.strftime('%Y-%m-%d %H:%M:%S')
							#cursor.execute("INSERT INTO Log (DATE,ACTION) VALUES (%s, %s)",
							#(now,"UPDATE" + str_usb_read))
						else:
							SlaveCarteId = "0"
							#cursor.execute("UPDATE Etat_IO SET Value=%s, Etat=%s, Date=%s where DeviceID=%s and Carte_Id=0", (value,status,date,pinID))
							cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteID=0 and cmd_device.Device_ID = Device.ID", (value,status,date,pinID))
							now = datetime.datetime.now()
							now = now.strftime('%Y-%m-%d %H:%M:%S')
							#cursor.execute("INSERT INTO Log (DATE,ACTION) VALUES (%s, %s)",
							#(now,"UPDATE" + str_usb_read))

						#value = value.strip()
						#if value == 0 or value == "0":
						#	value="off"
						#elif value == 1 or value == "1":
						#	value="on"
						#else:
						#	value = value

						if status == 0 or status == "0":
							status = "off"
						elif status == 1 or status == "1":
							status = "on"
						else: 
							status = status

						try:
							#cursor.execute("SELECT ID FROM Etat_IO WHERE
							#concat(Nom,'_',Lieux)=%s",(type_lieux))
							#cursor.execute("SELECT ID,Nom,Lieux,widget FROM Etat_IO where DeviceID=%s and Carte_ID=%s",(int(pinID),int(SlaveCarteId)))
							cursor.execute("SELECT cmd_device.ID,cmd_device.Nom,Lieux.Nom,Type_Device.widget FROM cmd_device LEFT JOIN Device on Device.ID= cmd_device.Device_ID LEFT JOIN Type_Device on Device.Type_ID = Type_Device.ID LEFT JOIN Lieux on Lieux.ID = Device.Lieux_ID WHERE DeviceID=%s and Device.CarteID=%s",(int(pinID),int(SlaveCarteId)))
							for row in cursor.fetchall():
								idNotif = row[0]
								nom = row[1]
								lieux = row[2]
								widget = row[3]
								
							if widget == "slider":
								value = ""

							now = datetime.datetime.now()
							now = now.strftime('%Y-%m-%d %H:%M:%S')
							#cursor.execute("INSERT INTO Log (DATE,ACTION) VALUES (%s, %s)", (now, str_usb_read + "    " + type + " " + lieux + " " + str(value) + " : " + status))
							cursor.execute("INSERT INTO Log (DeviceId,DATE,ACTION,Message) VALUES (%s, %s, %s, %s)", (idNotif,now, str_usb_read, nom + " " + lieux + " " + str(value) + " : " + status))

							try:
								url = "http://notify8702.freeheberg.org/"
								data = {}
								data['id'] = 'thibault'
								#data['notif'] = type + " " + lieux + " " + value + " : " + status
								data['notif'] = nom + " " + lieux + " " + value + " : " + status
								data['id_notif'] = str(idNotif)
								url_values = urllib.urlencode(data)
								full_url = url + '?' + url_values
								urllib2.urlopen(full_url)
							except IOError, e:
								pass
						except:
							pass
							try:
								url = "http://notify8702.freeheberg.org/"
								data = {}
								data['id'] = 'thibault'
								data['notif'] = "Erreur dans la requete read_usb"
								data['id_notif'] = "-2"
								url_values = urllib.urlencode(data)
								full_url = url + '?' + url_values
								urllib2.urlopen(full_url)
							except IOError, e:
								pass
		except KeyboardInterrupt:
			print "Bye"
			sys.exit()
				
def planning():
	#try:
		 # while True:
			# ser.flush()
			cursor.execute("SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID WHERE DAYS like '%" + str(time.localtime().tm_wday) + "%' and HOURS ='" + time.strftime('%H:%M',time.localtime()) + ":00'")
			print("SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID WHERE DAYS like '%" + str(time.localtime().tm_wday) + "%' and HOURS ='" + time.strftime('%H:%M',time.localtime()) + ":00'")
			for row in cursor.fetchall():
				New_Status = row[0]
				DeviceID = row[1]
				Nom = row[2]
				Type = row[3]
				Lieux = row[4]
				if Type == "Chauffage":
					val = str(DeviceID) + "#" + str(New_Status) + "/n"
					written = ser.write(val)
					time.sleep(30)
				else:
					val = str(DeviceID) + ":" + str(New_Status) + "/n"
					writen = ser.write(val)	
					time.sleep(30)
					# print val
					# print Nom

	 # data = "Etat_IO"
	 # query = "Select * from %s" % (data)
	 # cursor.execute(query)

def Temp(pinID,value):
	global Alert_LastTime
	cmd_device_ID = -1
	lieux=''
	#cursor.execute("SELECT Lieux,ID,COALESCE(Alert_Time,'0000-00-00 00:00:00') as Alert_Time from Etat_IO where DeviceId = %s",(pinID))
	cursor.execute(" SELECT Lieux.Nom,cmd_device.ID,COALESCE(Alert_Time,'0000-00-00 00:00:00') as Alert_Time  from cmd_device INNER JOIN Device on cmd_device.Device_ID = Device.ID INNER JOIN Lieux on Lieux.Id = Device.Lieux_ID where cmd_device.DeviceId = %s",(pinID))
	if cursor.rowcount > 0:
		for row in cursor.fetchall():
			lieux = row[0]
			cmd_device_ID = row[1]
			Alert_Time = row[2]
			#lieux = cursor.fetchone()[0]
			#cmd_device_ID = cursor.fetchone()[1]
	else:
		#lieux = lieux
		#lieux = "Exterieur"
		lieux = pinID

	#if float(value) > 0 and pinID == 'Exterieur':
	#	value = float(value) * float(-1)
	#	value = str(value)
		
	if pinID == 'Exterieur':
		cmd_device_ID = 0
		Alert_Time_Diff=datetime.datetime.now() - datetime.datetime.strptime(Alert_LastTime ,"%Y-%m-%d %H:%M:%S")
		min_diff = divmod(Alert_Time_Diff.days * 86400 + Alert_Time_Diff.seconds, 60)[0]
		if min_diff >=30:
			Bnotif = False
			Alert_LastTime = datetime. datetime.now().strftime('%Y-%m-%d %H:%M:%S')
			if float(value) >= 0 and float(value) < 3:
				str_usb_read = "Alert : risque de gelée "+ value
				Bnotif = True
			elif float(value) < 0:
				str_usb_read = "Alert : gelée "+ value
				Bnotif = True

			if Bnotif == True:
				try:
					date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
					cursor.execute("INSERT INTO Log (DeviceID, DATE,ACTION, Message) VALUES (%s, %s, %s, %s)", (cmd_device_ID, date,'', str_usb_read))
					#cursor.execute("UPDATE Etat_IO SET Alert_Time=%s where ID=%s", (date,Etat_IO_ID))
					cursor.execute("UPDATE cmd_device SET Alert_Time=%s where ID=%s", (date,cmd_device_ID))
					try:
						url = "http://notify8702.freeheberg.org/"
						data = {}
						data['id'] = 'thibault'
						data['notif'] = str_usb_read
						now = datetime.datetime.now()
						now = now.strftime("%Y%m%d%H%M%S")
						data['id_notif'] = str(date)
						url_values = urllib.urlencode(data)
						full_url = url + '?' + url_values
						urllib2.urlopen(full_url)

					except IOError,e:
						pass
				except IOError, e:
					pass

		if float(value) < 0:
			value = float(value) * -1
			value = str(value)
			
	date = time.strftime('%y-%m-%d %H:%M:%S',time.localtime())
	#cursor.execute("UPDATE Etat_IO SET Value=%s, Date=%s where ID=%s", (value,date,Etat_IO_ID))
	cursor.execute("UPDATE cmd_device SET Value=%s, Date=%s where ID=%s", (value,date,cmd_device_ID))
	if cmd_device_ID != -1:
		cursor.execute("INSERT INTO Temperature_Temp VALUES (%s, %s, %s, %s)", (date,value,lieux, cmd_device_ID))
	mon_fichier = open("/var/www/nodejs/public_html/temperature.json","a")
	mon_fichier.write(lieux + " :" + value + " , " + "date " + date)
	mon_fichier.write("\r\n")
	mon_fichier.close() 

def Alert(str_usb_read):
	try:
		now = datetime.datetime.now()
		now = now.strftime('%Y-%m-%d %H:%M:%S')
		cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s, %s, %s, %s)", ("",now,"","Alert : " + str_usb_read))
		try:
			url = "http://notify8702.freeheberg.org/"
			data = {}
			data['id'] = 'thibault'
			data['notif'] = value
			now = datetime.datetime.now()
			now = now.strftime("%Y%m%d%H%M%S")
			data['id_notif'] = str(now)
			url_values = urllib.urlencode(data)
			full_url = url + '?' + url_values
			urllib2.urlopen(full_url)
		except IOError,e:
			pass
	except IOError, e:
		pass

#def Action(value,status,pinID,SlaveCarteId,str_usb_read):

# try:
   # thread.start_new_thread( ReadArduino,() )
   # thread.start_new_thread( planning,() )
# except:
   # print "Error: unable to start thread"

# while 1:
	# pass
	
try: 
	ReadArduino()
except KeyboardInterrupt:
	print "Bye"
	sys.exit()
