# o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import time
import datetime
import requests
import sys
import msql
import json
import ssl
import urllib3
urllib3.disable_warnings()
#import ptvsd
#ptvsd.enable_attach(secret="my_secret")

print("####### Planning - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()))

##############  TRY CONNECT SQL ##################
cursor = msql.cursor
DbConnect = msql.DbConnect

def SendDataToUsb(moduleName, moduleCom, moduleBaudrate, data):    
    # if 'ser' not in locals() or not ser.is_open:
    ser = serial.Serial(port=moduleCom, baudrate=moduleBaudrate)
    ser.write(data)


def UpdateDateRaz(deviceId):
    dateRaz = (datetime.datetime.now()+datetime.timedelta(seconds=1)).strftime('%Y-%m-%d %H:%M:%S')
    try:
        cursor.execute(" update cmd_device set DateRAZ=%s where ID =%s", (str(dateRaz), str(cmd_device_id)))
    except:
        print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Erreur dans la requete UpdateDateRaz dateRaz= " + str(dateRaz)+ "  cmd_device_id= "+str(cmd_device_id)
        pass

def Action(New_status, DeviceID, CarteID, Nom, Type, Lieux, Device_Id, sensor_attach_value, ValueAct, EtatAct, ModuleName,  ModuleConfiguration):
    try:                                 
        Configuration = json.loads(ModuleConfiguration)
    except:
        Configuration = None
        
    if Type == "Thermostat":
        if float(sensor_attach_value) < float(New_Status):
            str_action = "1"
        else:
            str_action = "0"
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(str_action)+"\n"
        if (str(ValueAct) != str(New_Status) or str(str_action) != str(EtatAct)):
            SendDataToUsb(ModuleName, Configuration["com"], Configuration["baudrate"], val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
#            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (Device_Id, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))
            cursor.execute("INSERT INTO Log (DeviceID, DATE, Message) VALUES (%s, %s, %s)", (Device_Id, now, "Planning: "+Lieux+" "+Nom+" " + val))
    else:
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(New_Status)+"\n"
        if (ValueAct != New_Status or New_Status != EtatAct):    
            SendDataToUsb(ModuleName, Configuration["com"], Configuration["baudrate"], val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
#            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (Device_Id, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))
            cursor.execute("INSERT INTO Log (DeviceID, DATE, Message) VALUES (%s, %s, %s)", (Device_Id, now, "Planning: "+Lieux+" "+Nom+" " + val))

while True:
    try:
        time.sleep(0.1)

        sql = """ SELECT Status
                    ,cmd_device.id
                    ,cmd_device.DeviceID
                    ,Device.CarteID
                    ,Device.Nom
                    ,cmd_device.Type as TypeAction
                    ,widget.Name
                    ,widget.Id as widget_Id
                    ,Lieux.Nom
                    ,Device.ID
                    ,IFNULL(sensor_attach.value,'') as sensor_attachValue 
                    ,'' as Request
                    ,cmd_device.RAZ
                    ,cmd_device.Value
                    ,cmd_device.Etat
                    ,Module_Type.ModuleName
                    ,Module_Type.ModuleType
                    ,Module_Type.ModuleConfiguration
                FROM Planning
                    INNER JOIN cmd_device on cmd_device.ID = CmdDevice_Id AND Planning.Status != cmd_device.Etat
                    INNER JOIN Device on Device.ID = cmd_device.Device_ID
                    INNER JOIN Module_Type ON Device.Module_Id  = Module_Type.ID
                    INNER JOIN widget on widget.Id = cmd_device.Widget_Id
                    INNER JOIN Lieux on Lieux.Id = Device.Lieux_ID
                    LEFT JOIN cmd_device as sensor_attach on sensor_attach.ID = cmd_device.sensor_attachID
                WHERE 
                    (
                        (
                            DAYS  like '%"""+str(time.localtime().tm_wday)+"""%' 
                            OR Planning.date = '"""+time.strftime("%y/%m/%d")+"""'
                        ) 
                        AND HOURS ='"""+time.strftime('%H:%M', time.localtime())+""":00'
                        AND ACTIVATE = 1
                    )
            UNION
                SELECT Status
                ,cmd_device_id
                ,DeviceID
                ,CarteID
                ,Nom
                ,TypeAction
                ,WidgetName
                ,widget_Id
                ,LieuxNom
                ,Device_ID
                ,'' as sensor_attachValue
                ,IFNULL(Request,'') as sRequest
                ,RAZ
                ,Value
                ,Etat
                ,ModuleName
                ,ModuleType
                ,ModuleConfiguration
                FROM (
                    SELECT 0 as Status, cmd_device.id as cmd_device_id, cmd_device.DeviceID, Device.CarteID, Device.Nom, cmd_device.Type as TypeAction, widget.Name as WidgetName, widget.Id as widget_Id, Lieux.Nom as LieuxNom, Device.ID as Device_ID,Request, Date, IFNULL(DATE_ADD(Date ,INTERVAL RAZ SECOND),"1900/01/01 00:00:00") as DateToRaz, RAZ, DateRAZ, Value, Etat
                    ,Module_Type.ModuleName, Module_Type.ModuleType, Module_Type.ModuleConfiguration
                    FROM cmd_device
                        INNER JOIN Device on Device.ID = cmd_device.Device_ID
                        INNER JOIN Module_Type ON Device.Module_Id  = Module_Type.ID
                        LEFT JOIN widget on widget.Id = cmd_device.widget_Id
                        LEFT JOIN Lieux on Lieux.Id = Device.Lieux_ID
                ) as t
                WHERE RAZ IS NOT NULL
                    AND now() >= t.DateToRaz 
                    AND now() > t.DateRAZ
        #            AND (t.Status != t.Etat or t.widget_Id = 5)
                    AND (t.Status != t.Etat and  t.Etat != '' or t.Request != '')
                group by Device_ID, sRequest;"""

        cursor.execute(sql)
        for row in cursor.fetchall():
            New_Status = row[0]
            cmd_device_id = row[1]
            DeviceID = row[2]
            CarteID = row[3]
            Nom = row[4]
            TypeAction = row[5]
            WidgetName = row[6]
            Widget_Id = row[7]
            Lieux = row[8]
            Device_Id = row[9]
            sensor_attach_value = row[10]
            Request = row[11]
            RAZ = row[12]
            Value = row[13]
            Etat = row[14]
            ModuleName = row[15]
            ModuleType = row[16]
            ModuleConfiguration = row[17]

            if Request != "":
                #context = ssl._create_unverified_context()
                Request = json.loads(Request)
                url = "https://127.0.0.1/ThiDom/Core/"+Request["url_ajax"]
                data = Request["data"]
                postData = []

                if data != "":
                    tbData = data.split("&")
                    for x in tbData:
                        postData.append(x.split("="))

                if Device_Id:
                    postData.append(["Device_id", str(Device_Id)])

                try:
                    full_url = requests.post(url, data=postData, verify=False)
                    UpdateDateRaz(cmd_device_id)
                except requests.exceptions.RequestException as e:
                    print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning Exec url :" + url + url_values + " error : " + str(e.code) + "//" + str(e.read())
            #elif RAZ != "NULL" and New_Status == 0:
            elif ModuleType == "Communication":                                            
                Configuration = json.loads(ModuleConfiguration)
                Action(New_Status, DeviceID, CarteID, Nom, WidgetName, Lieux, Device_Id, sensor_attach_value, Value, Etat, ModuleName, ModuleConfiguration)
                if TypeAction == "Info":
                    dateRaz = (datetime.datetime.now()+datetime.timedelta(seconds=1)).strftime('%Y-%m-%d %H:%M:%S')
                    try:
                        cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' , DateRAZ=%s where ID =%s", (dateRaz, str(cmd_device_id)))
                    except:
                        print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Erreur dans la requete UpdateDateRaz1 dateRaz= " + dateRaz+ "  cmd_device_id= "+str(cmd_device_id)
                else:
                    UpdateDateRaz(cmd_device_id)
                # Minute = datetime.datetime.now().minute
                # cursor.execute("update cmd_device set date = (select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute % RAZ)+" SECOND)  where ID ="+str(cmd_device_id))
            # else:
                # Action(New_Status, DeviceID, CarteID, Nom, WidgetName, Lieux, Device_Id, sensor_attach_value, Value, Etat)
                # cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
        DbConnect.commit()
        time.sleep(0.5)
    except KeyboardInterrupt:
        print "Bye"
        sys.exit()
