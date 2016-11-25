# -*- coding: utf-8 -*-

import serial
import MySQLdb
import datetime
import time
import urllib
import urllib2
import sys
import os
import msql
import json
import ssl
import re

processus = 'mysqld'

s = os.popen('ps ax').read()

urlnotify = "http://notify8702.freeheberg.org/"

while processus not in s:
	n = 1
	time.sleep(0.2)
	s = os.popen('ps ax').read()

try:
	DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
	time.sleep(0.2)
	cursor = DbConnect.cursor()

except MySQLdb.Error, e:
	if msql.idnotify!="":
		urllib.urlopen(urlnotify+"?id="+msql.idnotify+"&notif=Erreur connection bdd Scenario&id_notif:-8")
	print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error Scenario %d: %s" % (e.args[0],e.args[1])
	sys.exit(1)

context = ssl._create_unverified_context()

ser = serial.Serial( port = '/dev/ttyACM0', baudrate =9600 )	

while True:	
	try:
		time.sleep(0.2)
		val = ""
		CarteID = ""
		DeviceID = ""
		Actions_Device = ""
		BLog = True

		sql_Liste_Scenario = "SELECT Scenario.ID as ScenarioID, XmlID,Conditions,Actions, SequenceNo,Scenario_Xml.Name, Scenario_Xml.status,Scenario.NextTimeEvents,Scenario.NextActionEvents FROM Scenario  inner join Scenario_Xml on Scenario_Xml.ID = Scenario.XmlID where status = 1  and (NextActionEvents is NULL or NextActionEvents >= Now()) ORDER BY XmlID, SequenceNo"
		cursor.execute(sql_Liste_Scenario)
		Old_XmlID = -5555

		#############   LISTE LES SCENARIO   ##############

		for row in cursor.fetchall():
			ScenarioID =  str(row[0])
			XmlID = int(row[1])
			Conditions = row[2]
			Actions = row[3]
			SequenceNo = row[4]
			ScenarioName = row[5]
			Status = row[6]
			NextTimeEvents = row[7]
			NextActionEvents = row[8]
			if Status == 1:
				Conditions_SQL =""
				conditions_Where =""
				Conditions = Conditions.replace("(","").replace(")","")
				max_row_id =0
				if len(Conditions.split(" and ")) > 1:
					NbAnd = Conditions.count(" and ")
					Len_And = range(len(Conditions.split(" and ")))
					Conditions_And = Conditions.split("and")				
					for row_Len_And in Len_And:
						row_id = str(row_Len_And)
						max_row_id = int(row_id)
						if 'LastExecute' in  Conditions_And[row_Len_And] :
							Conditions_SQL += " inner join Scenario as S"+row_id+" on "
							Conditions_SQL += Conditions_And[row_Len_And].replace("LastExecute","S"+row_id+".LastTimeEvents ").replace("==","=").replace(re.split('== | >= | <= | < | >',Conditions_And[row_Len_And])[0],"(( select now() - INTERVAL " + re.split('== | >= | <= | < | >',Conditions_And[row_Len_And])[0].strip()  +" minute )")+"or S"+row_id+".LastTimeEvents  is null ) and  S"+row_id+".ID = "+ScenarioID
						else:
							Conditions_SQL += " inner join cmd_device as e"+row_id+" on "
							Conditions_SQL += Conditions_And[row_Len_And].replace("timeofday  ==","(HOUR(now())*60+MINUTE(now())) =").replace("timeofday ","(HOUR(now())*60+MINUTE(now())) ").replace("timeofsun  ==","(select DATE_FORMAT(now(), '%H:%i')) = ").replace("weekday  ==","WEEKDAY(now()) =").replace("==","and e"+row_id+".Etat=",).replace(">=","and e"+row_id+".Etat >=",).replace("<=","and e"+row_id+".Etat <=",).replace(">","and e"+row_id+".Etat >",).replace("<","and e"+row_id+".Etat <",).replace("temperaturedevice[","e"+row_id+".id=").replace("device[","e"+row_id+".id=").replace("]","").replace("On",'1').replace("Off",'0').replace("@Sunrise","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunrise')").replace("@Sunset","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunset')")
					
					#Conditions_SQL = "where "
					#Conditions_SQL += Conditions.replace("timeofday  ==","(HOUR(now())*60+MINUTE(now())) =").replace("timeofday ","(HOUR(now())*60+MINUTE(now())) ").replace("timeofsun  ==","(select DATE_FORMAT(now(), '%H:%i')) = ").replace("weekday  ==","WEEKDAY(now()) =").replace("==","and Etat_IO.Etat=",).replace("device[","Etat_IO.id=").replace("]","").replace("On",'1').replace("Off",'0').replace("@Sunrise","(select DATE_FORMAT(sunrise, '%H:%i') from Sunrise_set)").replace("@Sunset","(select DATE_FORMAT(sunset, '%H:%i') from Sunrise_set)")
					#print Conditions_SQL
				

				# if len(Conditions.split(" or ")) > 1:
				# 	Len_OR =  range(len(Conditions.split(" or ")))
				# 	Conditions_OR = Conditions.split(" or ")
				# 	max_row_id = max_row_id+1
				# 	for row_Len_OR in Len_OR:
				# 		row_id = str(int(row_Len_OR)+int(max_row_id))
				# 		Conditions_SQL += " left join cmd_device as e"+row_id+" on "
				# 		Conditions_SQL += Conditions_OR[row_Len_OR].replace("timeofday  ==","(HOUR(now())*60+MINUTE(now())) =").replace("timeofday ","(HOUR(now())*60+MINUTE(now()))").replace("timeofsun  ==","(select DATE_FORMAT(now(), '%H:%i')) = ").replace("weekday  ==","WEEKDAY(now()) =").replace("==","and e"+row_id+".Etat=",).replace(">=","and Etat >=",).replace("<=","and Etat <=",).replace(">","and Etat >",).replace("<","and Etat <",).replace("temperaturedevice[","e"+row_id+".id=").replace("device[","e"+row_id+".id=").replace("]","").replace("On",'1').replace("Off",'0').replace("@Sunrise","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunrise')").replace("@Sunset","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunset')")
				# 		if conditions_Where !="":
				# 			conditions_Where += " or "
				# 		conditions_Where +=  " e"+row_id+".ID is not null"
				if Conditions_SQL == "":
					Conditions_SQL+= " " 
					conditions_Where += Conditions.replace("timeofday  ==","(HOUR(now())*60+MINUTE(now())) =").replace("timeofsun  ==","(select DATE_FORMAT(now(), '%H:%i')) = ").replace("weekday  ==","WEEKDAY(now()) =").replace("==","and Etat=",).replace(">=","and Etat >=",).replace("<=","and Etat <=",).replace(">","and Etat >",).replace("<","and Etat <",).replace("temperaturedevice[","id=").replace("device[","id=").replace("]","").replace("On",'1').replace("Off",'0').replace("@Sunrise","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunrise')").replace("@Sunset","( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunset')")
				if conditions_Where !="":
					conditions_Where= "where "+conditions_Where
				Actions = Actions.split(",")

				############ SI NOUVEAU SCENARIO   #################	
							
				if XmlID != Old_XmlID:	
					if Conditions_SQL != "" or conditions_Where != "":
						sql_check_etat =  "SELECT COUNT(*) as result from cmd_device "+Conditions_SQL+" "+conditions_Where+" ;"
						cursor.execute(sql_check_etat)
						############### EXECUTION DU SCENARIO SI RESULTAT DE LA REQUETE
						bTimer = False;
						if int(cursor.fetchone()[0]) >0:
							Len_Actions = range(len(Actions))
							for row_Action in Len_Actions:
								BChange_Status = False;
								Split_Actions_length = len(Actions[row_Action].split("="))
								Device = Actions[row_Action].split("=")[0]
								if Split_Actions_length >1:
									Actions_Device =  Actions[row_Action].replace(Device+"=","")						
									Actions_Device = Actions_Device.replace("On",'1').replace("Off",'0').replace('\"','')
								ID = Device.replace("commandArray[","").replace("]","")

								if "FOR" in Actions_Device:
									tbtimer = Actions_Device.split("FOR");
									Actions_Device = tbtimer[0];
									bTimer = True;
								if ID =='"SendNotification"':
									BLog = False
									if msql.idnotify!="":
										notif_value = Actions_Device.replace('$','')
										notif_data = notif_value.split(";getdata[")
										for i in range (0,len(notif_data)):
											if ']' in notif_data[i] : 
												dataID = notif_data[i].split(']')[0]
												sql_getdata = "SELECT Value from cmd_device where ID = "+dataID
												cursor.execute(sql_getdata)
												result_sql_getdata = cursor.fetchone()
												DataValue = result_sql_getdata[0]
												notif_value = notif_value.replace(";getdata["+dataID+"]"," "+DataValue)
										url = urlnotify
										data = {}
										data['id'] = msql.idnotify
										data['notif'] = notif_value
										data['id_notif'] = XmlID
										url_values = urllib.urlencode(data)
										full_url = url + '?' + url_values										
										try:
											urllib2.urlopen(full_url)															
										except urllib2.HTTPError as e:
											print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error Scenario Exec url " + e.code + "//" + e.read()
										BChange_Status = True					
								else:
									sql_Type_device = "SELECT Type_Device.Type, Device.CarteID, cmd_device.DeviceID,cmd_device.Etat,cmd_device.Value,Sensor_attached.value, cmd_device.DATE,cmd_device.ID, cmd_device.Request FROM cmd_device INNER JOIN Device on Device.ID = cmd_device.Device_ID INNER JOIN Type_Device ON Device.Type_ID  = Type_Device.ID INNER JOIN cmd_device as Sensor_attached on Sensor_attached.ID = cmd_device.sensor_attachID WHERE cmd_device.ID ="+str(ID)+";"
									cursor.execute(sql_Type_device)
									result_sql_Type_device = cursor.fetchone()
									Type_Device = result_sql_Type_device[0]
									CarteID = result_sql_Type_device[1]
									DeviceID = result_sql_Type_device[2]
									Etat_Actuel = result_sql_Type_device[3]
									Etat_Value = result_sql_Type_device[4]
									value_sensor_attached = result_sql_Type_device[5]
									Last_Action_Date = result_sql_Type_device[6]
									ID = result_sql_Type_device[7]
									Request = result_sql_Type_device[8]
									if Type_Device == "Plugins":
										ConditionsId =  Conditions.replace("device[","").replace("]","").split("==")[0]
										sql_Date_Conditions = "Select Date from cmd_device where ID = "+ConditionsId
										cursor.execute(sql_Date_Conditions)
										result_sql_Date_Conditions = cursor.fetchone()
										Last_Date_Execute_Conditions = result_sql_Date_Conditions[0]
										if Last_Date_Execute_Conditions > Last_Action_Date :
											Request = json.loads(Request)
											RequestUrl =""
											RequestData=""
											val = ""
											try:
												RequestUrl = Request["url_ajax"]
											except:
												pass
											try:
												RequestData = Request["data"]
											except: 
												pass
											if RequestUrl != "":
												url = "https://localhost/ThiDom/"+RequestUrl
												#url_values = urllib.urlencode(RequestData)
												url_values = RequestData
												full_url = urllib2.Request(url, url_values)								
												try:
													exec_cmd = urllib2.urlopen(full_url,context=context)													
												except urllib2.HTTPError as e:
													print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error Scenario Exec url " + e.code + "//" + e.read()
												BChange_Status = True
									elif Etat_Value != Actions_Device :
										if Type_Device == "Chauffage":
											if float(value_sensor_attached) < float(Actions_Device):
												act_chauff = 1
											else:
												act_chauff =0
											if (float(Etat_Actuel) != float(act_chauff) or float(Etat_Value) != float(Actions_Device)):
												BChange_Status = True
												val = str(CarteID)+"/"+str(DeviceID)+"@"+str(Actions_Device)+":"+str(act_chauff)+"\n"
												#print val
												written = ser.write(val.replace(' ',''))
										else:
											if (float(Etat_Actuel) != float(Actions_Device) or float(Etat_Value) != float(Actions_Device)):
												BChange_Status = True
												val = str(CarteID)+"/"+str(DeviceID)+"@"+str(Actions_Device)+":"+str(Actions_Device)+"\n"
												#print val
												written = ser.write(val.replace(' ',''))
									elif Etat_Value == Actions_Device:
										#print Etat_Value +"//" +Actions_Device
										#print "bTimer = " + str(bTimer)
										if bTimer == True:
											Last_Action_Date = datetime.datetime.strptime(Last_Action_Date,"%Y-%m-%d %H:%M:%S")
											Next_Action_Date = Last_Action_Date  + datetime.timedelta(minutes = tbtimer[1])
											if datetime.datetime.strptime(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"),"%Y-%m-%d %H:%M:%S") >= Next_Action_Date:
												if Type_Device == "Chauffage":
													c = str(int(not Etat_Value))
												else:
													BChange_Status = True
													val = str(CarteID)+"/"+str(DeviceID)+"@"+str(int(not Etat_Value))+":"+str(int(not Etat_Value))+"\n"
													written = ser.write(val.replace(' ',''))
								if BChange_Status == True:
									now = datetime.datetime.now()
									sNow = now.strftime('%Y-%m-%d %H:%M:%S')
									if NextActionEvents!=None:
										NextEvent = now + datetime.timedelta(minutes = NextActionEvents)
										NextEvent= NextEvent.strftime('%Y-%m-%d %H:%M:%S')
										cursor.execute("UPDATE Scenario set LastTimeEvents=%s,NextTimeEvents=%s where XmlID=%s", (sNow,NextEvent,XmlID))
									else:
										cursor.execute("UPDATE Scenario set LastTimeEvents=%s where XmlID=%s",(sNow,XmlID))
									if BLog == True:
										cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s, %s, %s, %s)", (ID, sNow, val, "Scenario: "+ScenarioName+" " +str(CarteID) +" " +str(DeviceID) +" " +str(Actions_Device)))
									if ID !='"SendNotification"':
										try:
											if msql.idnotify!="":
												url = urlnotify
												data = {}
												data['id'] = msql.idnotify
												data['notif'] = "Scenario : "+ScenarioName
												data['id_notif'] = XmlID
												url_values = urllib.urlencode(data)
												full_url = url + '?' + url_values
												try:
													urllib2.urlopen(full_url)													
												except urllib2.HTTPError as e:
													print time.strftime('%A %d. %B %Y  %H:%M',time.localtime()) + " Error Scenario Exec url " + e.code + "//" + e.read()
										except IOError,e:
											pass
								time.sleep(1)
								#print val
				############  SI MEME SCENARIO    ###############
				elif XmlID == Old_XmlID:
					print "Old_XmlID"
	except KeyboardInterrupt:
		print "Bye"
		sys.exit()