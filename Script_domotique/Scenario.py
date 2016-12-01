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
from SendNotification import SendNotification

processus = 'mysqld'

s = os.popen('ps ax').read()

urlnotify = "http://notify8702.freeheberg.org/"
global BScenarioExecute
##############  TRY CONNECT SQL ##################
try:
    DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
    time.sleep(0.2)
    cursor = DbConnect.cursor()

except MySQLdb.Error, e:
    if msql.idnotify != "":
        urllib.urlopen(urlnotify + "?id=" + msql.idnotify + "&notif=Erreur connection bdd Scenario&id_notif:-8")
    print "####### SCENARIO - Connect BDD #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error Scenario %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)

context = ssl._create_unverified_context()

ser = serial.Serial(port='/dev/ttyUSB1', baudrate=9600)

############### REPLACE STRING TO SQL DATA #########################
def ReplaceStrToSQL(Str, row_id, ScenarioID):
    Str = Str.replace("timeofday", "(HOUR(now())*60+MINUTE(now())) ")\
    .replace("timeofsun", "(select DATE_FORMAT(now(), '%H:%i')) ")\
    .replace("INTERVAL", "select now() - INTERVAL")\
    .replace("&&", "and")\
    .replace("On", '1')\
    .replace("Off", '0')\
    .replace("@Sunrise", "( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunrise')")\
    .replace("@Sunset", "( select DATE_FORMAT(str_to_date(Value, '%H:%i'),'%H:%i') from cmd_device where cmd_device.Nom = 'Sunset')")\
    .replace("weekday", "WEEKDAY(now())")
    if row_id != "":
        Str = Str.replace("temperaturedevice[", row_id + ".id")\
        .replace("DeviceId", row_id + ".id")\
        .replace("DeviceValue", row_id + ".value")\
        .replace("DeviceEtat", row_id + ".Etat")\
        .replace("LastExecute", row_id + ".LastTimeEvents and  " + row_id + ".ID = " + ScenarioID)
    else:       
        Str = Str.replace("temperaturedevice[", "cmd_device.id")\
        .replace("DeviceId", "cmd_device.id")\
        .replace("DeviceValue", "cmd_device.value")\
        .replace("DeviceEtat", "cmd_device.Etat")

    NbParentheseOuvrant = Str.count('(')
    NbParentheseFermant = Str.count(')')

    if NbParentheseOuvrant > NbParentheseFermant:
        Str = Str + ')'
    elif NbParentheseOuvrant < NbParentheseFermant:
        Str = '(' + Str

    return Str

################ GENERATE SQL ######################
def GenerateSQL():
    global Conditions
    global Conditions_SQL
    global conditions_Where
    if len(Conditions.split(" and ")) > 1:
        NbAnd = Conditions.count(" and ")
        Len_And = range(len(Conditions.split(" and ")))
        Conditions_And = Conditions.split("and")
        for row_Len_And in Len_And:
            row_id = str(row_Len_And)
            max_row_id = int(row_id)
            if 'LastExecute' in Conditions_And[row_Len_And] :
                Conditions_SQL += " inner join Scenario as S" + row_id + " on "
                Conditions_SQL += ReplaceStrToSQL(Conditions_And[row_Len_And], "S" + row_id, ScenarioID)
            else:
                Conditions_SQL += " inner join cmd_device as E" + row_id + " on "
                Conditions_SQL += ReplaceStrToSQL(Conditions_And[row_Len_And], "E" + row_id, ScenarioID)

    if Conditions_SQL == "":
        Conditions_SQL += " "
        conditions_Where += ReplaceStrToSQL(Conditions, '', '')
    if conditions_Where != "":
        conditions_Where = "where " + conditions_Where

############### GET ACTION COMMAND OF SCENARIO ##########
def GetActionCommand(index):
    global ActionSplitted
    global ID
    global Etat
    global Value_with_space
    global Value
    tbAction = ActionSplitted.split("&&")[index]
    if ((tbAction.find("DeviceId") >= 0) or (tbAction.find("Execute")) >= 0):
        ID = tbAction.replace("DeviceId", "").replace("Execute", "").replace("=", "").replace("On", '1').replace("Off", '0').replace('\"', '').replace(" ", "")
    if tbAction.find("DeviceEtat") >= 0:
        Etat = tbAction.replace("DeviceEtat", "").replace("=", "").replace("On", '1').replace("Off", '0').replace('\"', '').replace(" ", "")
    if tbAction.find("DeviceValue") >= 0:
        Value_with_space = tbAction.replace("DeviceValue", "").replace("=", "").replace('\"', '')
        Value = tbAction.replace("DeviceValue", "").replace("=", "").replace('\"', '').replace(" ", "")

while True:
    try:
        time.sleep(0.2)
        val = ""
        CarteID = ""
        DeviceID = ""
        Actions_Device = ""
        BLog = True
        
        #############   LISTE LES SCENARIO ##############

        sql_Liste_Scenario = "SELECT Scenario.ID as ScenarioID, XmlID,Conditions,Actions, SequenceNo,Scenario_Xml.Name, Scenario_Xml.status,Scenario.NextTimeEvents,Scenario.NextActionEvents FROM Scenario  inner join Scenario_Xml on Scenario_Xml.ID = Scenario.XmlID where status = 1  and (NextActionEvents is NULL or NextActionEvents >= Now()) ORDER BY XmlID, SequenceNo"
        cursor.execute(sql_Liste_Scenario)
        Old_XmlID = -5555
    
        for row in cursor.fetchall():
            ScenarioID = str(row[0])
            XmlID = int(row[1])
            Conditions = row[2]
            Actions = row[3]
            SequenceNo = row[4]
            ScenarioName = row[5]
            Status = row[6]
            NextTimeEvents = row[7]
            NextActionEvents = row[8]
            Conditions_SQL = ""
            conditions_Where = ""
            #Conditions = Conditions.replace("(","").replace(")","")
            max_row_id = 0

            GenerateSQL()            
            Actions = Actions.split(",")

            ############ SI NOUVEAU SCENARIO #################
                            
            if XmlID != Old_XmlID:  
                ID = ""
                Etat = ""
                Value = ""
                if Conditions_SQL != "" or conditions_Where != "":
                    sql_check_etat = "SELECT COUNT(*) as result from cmd_device " + Conditions_SQL + " " + conditions_Where + " ;"
                    cursor.execute(sql_check_etat)
                    ############### EXECUTION DU SCENARIO SI RESULTAT DE LA REQUETE
                    bTimer = False
                    if int(cursor.fetchone()[0]) > 0:
                        for row_Action in range(len(Actions)):
                            BScenarioExecute = False
                            ActionSplitted = Actions[row_Action]
                            Split_Actions_length = len(ActionSplitted.split("&&"))
                            if Split_Actions_length >= 1:
                                if range(Split_Actions_length) == 0:
                                        GetActionCommand(0)
                                else:
                                    for index in range(Split_Actions_length):
                                        GetActionCommand(index)
                                #if "FOR" in Actions_Device:
                                #   tbtimer = Actions_Device.split("FOR")
                                #   Actions_Device = tbtimer[0]
                                #   bTimer = True
                                if ID == 'SendNotification':
                                    notif_value = Value_with_space.replace('$', '')  #Actions_Device.replace('$','')
                                    notif_data = notif_value.split(";getdata[")
                                    DeviceID = -99999
                                    for i in range(0, len(notif_data)):
                                        if ']' in notif_data[i]:
                                            dataID = notif_data[i].split(']')[0]
                                            sql_getdata = "SELECT Value from cmd_device where ID = " + dataID
                                            cursor.execute(sql_getdata)
                                            result_sql_getdata = cursor.fetchone()
                                            DataValue = result_sql_getdata[0]
                                            notif_value = notif_value.replace(";getdata[" + dataID + "]", " " + DataValue + " ")
                                    SendNotification(notif_value, XmlID)
                                    BScenarioExecute = True
                                else:
                                    sql_Type_device = """SELECT Type_Device.Type, Device.CarteID, cmd_device.DeviceID,cmd_device.Etat,cmd_device.Value,
                                                            Sensor_attached.value, cmd_device.DATE,cmd_device.ID, cmd_device.Request 
                                                            FROM cmd_device 
                                                            INNER JOIN Device on Device.ID = cmd_device.Device_ID 
                                                            INNER JOIN Type_Device ON Device.Type_ID  = Type_Device.ID 
                                                            LEFT JOIN cmd_device as Sensor_attached on Sensor_attached.ID = cmd_device.sensor_attachID 
                                                            WHERE cmd_device.ID ="""+ str(ID) + ";"
                                    cursor.execute(sql_Type_device)
                                    result_sql_Type_device = cursor.fetchone()
                                    Type_Device = result_sql_Type_device[0]
                                    CarteID = result_sql_Type_device[1]
                                    DeviceID = result_sql_Type_device[2]
                                    Device_Etat = result_sql_Type_device[3]
                                    Device_Value = result_sql_Type_device[4]
                                    value_sensor_attached = result_sql_Type_device[5]
                                    Last_Action_Date = result_sql_Type_device[6]
                                    ID = result_sql_Type_device[7]
                                    Request = result_sql_Type_device[8]

                                    if Type_Device == "Plugins":
                                        ConditionsId = re.sub(r'&&.*', '', re.sub(r'.*DeviceId', '',Conditions)).replace("=", "").replace(" ", "")
                                        #ConditionsId = Conditions.replace("device[","").replace("]","").split("==")[0]
                                        sql_Date_Conditions = "Select Date from cmd_device where ID = " + ConditionsId
                                        cursor.execute(sql_Date_Conditions)
                                        result_sql_Date_Conditions = cursor.fetchone()
                                        Last_Date_Execute_Conditions = result_sql_Date_Conditions[0]
                                        if Last_Date_Execute_Conditions > Last_Action_Date:
                                            Request = json.loads(Request)
                                            RequestUrl = ""
                                            RequestData = ""
                                            val = ""
                                            try:
                                                RequestUrl = Request["url_ajax"]
                                            except:
                                                print "####### SCENARIO - Plugins - url_ajax #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime())
                                                pass
                                            try:
                                                RequestData = Request["data"]
                                            except:
                                                print "####### SCENARIO - Plugins - data #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime())
                                                pass
                                            if RequestUrl != "":
                                                url = "https://localhost/ThiDom/" + RequestUrl
                                                #url_values = urllib.urlencode(RequestData)
                                                url_values = RequestData
                                                full_url = urllib2.Request(url, url_values)
                                                try:
                                                    exec_cmd = urllib2.urlopen(full_url, context=context)
                                                    sql_update_date = "UPDATE cmd_device set Date = Now() where ID =""" + str(ID) + ";"
                                                    cursor.execute(sql_update_date)
                                                except urllib2.HTTPError as e:
                                                    print "####### SCENARIO - Exec URL #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error Scenario Exec url " + e.code + "//" + e.read()
                                                BScenarioExecute = True
                                    else:
                                        val=""
                                        if (Etat != '' and Value == ''):
                                            if (Device_Etat != Etat):
                                                val = str(CarteID) + "/" + str(DeviceID) + "@" + str(Device_Value) + ":" + str(Etat) + "\n"
                                        elif (Etat == '' and Value != ''):
                                            if (Device_Value != Value):
                                                if Type_Device == "Chauffage":
                                                    if float(value_sensor_attached) < float(Value):
                                                        Device_Etat = 1
                                                    else:
                                                        Device_Etat = 0
                                                val = str(CarteID) + "/" + str(DeviceID) + "@" + str(Value) + ":" + str(Device_Etat) + "\n"
                                        elif (Etat != '' and Value != ''):
                                            if ((Device_Etat != Etat) or (Device_Value != Value)):
                                                val = str(CarteID) + "/" + str(DeviceID) + "@" + str(Value) + ":" + str(Etat) + "\n"

                                        if val != "":
                                            #print "############################"
                                            #print ScenarioName
                                            #print "----------------------------"
                                            #print sql_check_etat
                                            #print val
                                            written = ser.write(val.replace(' ', ''))
                                            BScenarioExecute = True

                                        ############  ACTION SI SCENARIO AVEC ACTION FOR XX MINUTES ##########################
                                        #if bTimer == True:
                                        #   Last_Action_Date = datetime.datetime.strptime(Last_Action_Date,"%Y-%m-%d %H:%M:%S")
                                        #   Next_Action_Date = Last_Action_Date + datetime.timedelta(minutes = tbtimer[1])
                                        #   if datetime.datetime.strptime(datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S"),"%Y-%m-%d %H:%M:%S") >= Next_Action_Date:
                                        #       if Type_Device == "Chauffage":
                                        #           c = str(int(not Etat_Value))
                                        #       else:
                                        #           BScenarioExecute = True
                                        #           val = str(CarteID) + "/" + str(DeviceID) + "@" + str(int(not Etat_Value)) + ":" + str(int(not Etat_Value)) + "\n"
                                        #           print val
                                        #           #written = ser.write(val.replace(' ',''))
                                if BScenarioExecute is True:
                                    now = datetime.datetime.now()
                                    sNow = now.strftime('%Y-%m-%d %H:%M:%S')
                                    if NextActionEvents is not None:
                                        NextEvent = now + datetime.timedelta(minutes=NextActionEvents)
                                        NextEvent = NextEvent.strftime('%Y-%m-%d %H:%M:%S')
                                        cursor.execute("UPDATE Scenario set LastTimeEvents=%s,NextTimeEvents=%s where XmlID=%s", (sNow, NextEvent, XmlID))
                                    else:
                                        cursor.execute("UPDATE Scenario set LastTimeEvents=%s where XmlID=%s", (sNow, XmlID))
                                    if BLog is True:
                                        cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (ID, sNow, val, "Scenario: " + ScenarioName + " " + str(CarteID) + " " + str(DeviceID) + " " + str(Actions_Device)))
                                    if ID != 'SendNotification':
                                        try:
                                            SendNotification("Scenario : " + ScenarioName, XmlID*9087)
                                        except:
                                            print "####### SCENARIO - Scenario: ScenarioName #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
                                            pass
                                time.sleep(1)
                                #print val
            ############  SI MEME SCENARIO ###############
            elif XmlID == Old_XmlID:
                print "Old_XmlID"
    except KeyboardInterrupt:
        print "Bye"
        sys.exit()
