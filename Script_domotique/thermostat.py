#o!/usr/bin/python# -*- coding: utf-8 -*-import serialimport MySQLdbimport timeimport urllibimport sysimport osimport msqlprocessus = 'mysqld's = os.popen('ps ax').read()while processus not in s:	n = 1	time.sleep(0.2)	s = os.popen('ps ax').read()ser = serial.Serial( port = '/dev/ttyACM0', baudrate =9600 )timeout = 0while True:		try:		time.sleep(0.2)		if time.time() > timeout or time.strftime('%S',time.localtime()) == '00':			try:				DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)				time.sleep(0.2)				cursor = DbConnect.cursor()			except MySQLdb.Error, e:				if msql.idnotify!="":					urllib.urlopen("http://notify8702.freeheberg.org/?id="+msql.idnotify+"&notif=Erreur connection bdd planning&id_notif:-3")				print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error planning %d: %s" % (e.args[0],e.args[1])				sys.exit(1)			#sql = "select Etat_IO.DeviceID, Etat_IO.Value as Thermo, temp.Value, Etat_IO.Etat as status from Etat_IO inner join Etat_IO as temp on temp.ID = Etat_IO.sensor_attachID where Etat_IO.sensor_attachID <> 0 and Etat_IO.Type = 'Chauffage'"			sql = "select cmd_device.DeviceID, cmd_device.Value as Thermo, temp.Value, cmd_device.Etat as status from cmd_device inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.ID = Device.Type_ID inner join cmd_device as temp on temp.ID = cmd_device.sensor_attachID  where cmd_device.sensor_attachID <> 0 and Type_Device.Type = 'Chauffage'"#			print sql			cursor.execute(sql)			timeout = time.time() + 60				for row in cursor.fetchall():				DeviceID = row[0]				Thermo = row[1]				TempValue = row[2]				status = row[3]#				print New_Status#				print row[4]#				print Nom#				print Type#				print Lieux#				print TempValue#				print Thermo#				print status				if TempValue < Thermo and int(status) == 0:					val = DeviceID+"@"+str(Thermo)+":1\n"					# print val					written = ser.write(val)				elif TempValue >= Thermo and int(status) == 1:					val = DeviceID+"@"+str(Thermo)+":0\n"					# print val					written = ser.write(val)				DbConnect.close()			time.sleep(1)			# db.autocommit(True)	except KeyboardInterrupt:		print "Bye"	        sys.exit()	# try:		# if time.time() > timeout:			# timeout = time.time() + 60*5			# time.sleep(0.2)			# cursor.execute("SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00'")			# for row in cursor.fetchall():				# New_Status = row[0]				# DeviceID = row[1]				# Nom = row[2]				# Type = row[3]				# Lieux = row[4]				# if Type == "Chauffage":					# val = str(DeviceID)+"#"+str(New_Status)+"/n"					# written = ser.write(val)				# else:					# val = str(DeviceID)+":"+str(New_Status)+"/n"					# writen = ser.write(val)		# except KeyboardInterrupt:		# print "Bye"	        # sys.exit()