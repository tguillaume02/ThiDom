# o!/usr/bin/python
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
#import ptvsd
#ptvsd.enable_attach(secret="my_secret")

#processus = 'mysqld'
#s = os.popen('ps ax').read()

#while processus not in s:
    #n = 1
    #time.sleep(0.2)
    #s = os.popen('ps ax').read()

print "####### Planning - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime())

##############  TRY CONNECT SQL ##################
DbConnect = None
while DbConnect is None:
    try:
        DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
        time.sleep(0.1)
        cursor = DbConnect.cursor()
    except MySQLdb.Error, e:
        DbConnect = None
        #if msql.idnotify != "":
        #    urllib.urlopen("http://notify8702.freeheberg.org/?id="+msql.idnotify+"&notif=Erreur connection bdd Planning&id_notif:-4")
        #print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning %s" % (e.args[1])
        #sys.exit(1)

def SendDataToUsb(data):
    if 'ser' not in locals() or not ser.is_open:
        ser = serial.Serial(port='/dev/ttyUSB1', baudrate=115200)
    ser.write(data)


def Action(New_status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value, ValueAct, EtatAct):
    if Type == "Chauffage":
        if float(sensor_attach_value) < float(New_Status):
            str_action = "1"
        else:
            str_action = "0"
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(str_action)+"\n"
        if (str(ValueAct) != str(New_Status) or str(str_action) != str(EtatAct)):
            SendDataToUsb(val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (ID, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))
    else:
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(New_Status)+"\n"
        if (ValueAct != New_Status or New_Status != EtatAct):
            SendDataToUsb(val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (ID, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))

while True:
    try:
        time.sleep(0.1)

        sql = """ SELECT Status, cmd_device.id, cmd_device.DeviceID, Device.CarteID, Device.Nom, cmd_device.Type as TypeAction, Type_Device.Type, Type_Device.ID as Type_ID, Lieux.Nom, Device.ID, IFNULL(sensor_attach.value,'') as sensor_attachValue , '' as Request, cmd_device.RAZ, cmd_device.Value, cmd_device.Etat
        FROM Planning 
            INNER JOIN cmd_device on cmd_device.ID = CmdDevice_Id AND Planning.Status != cmd_device.Etat 
            INNER JOIN Device on Device.ID = cmd_device.Device_ID 
            INNER JOIN Type_Device on Type_Device.Id = Device.Type_ID 
            INNER JOIN Lieux on Lieux.Id = Device.Lieux_ID 
            LEFT JOIN cmd_device as sensor_attach on sensor_attach.ID = cmd_device.sensor_attachID 
        WHERE ((DAYS  like '%"""+str(time.localtime().tm_wday)+"""%' OR Planning.date = '"""+time.strftime("%y/%m/%d")+"""' ) AND HOURS ='"""+time.strftime('%H:%M', time.localtime())+""":00' AND ACTIVATE = 1) 
        UNION 
        SELECT Status, cmd_device_id, DeviceID, CarteID, Nom, TypeAction, Type, Type_ID, LieuxNom, Device_ID, '' as sensor_attachValue, IFNULL(Request,'') as sRequest, RAZ, Value, Etat 
        FROM ( 
            SELECT 0 as Status, cmd_device.id as cmd_device_id, cmd_device.DeviceID, Device.CarteID, Device.Nom, cmd_device.Type as TypeAction, Type_Device.Type, Device.Type_ID, Lieux.Nom as LieuxNom, Device.ID as Device_ID,Request, Date, IFNULL(DATE_ADD(Date ,INTERVAL RAZ SECOND),"01/01/1900 00:00:00") as DateRaz, RAZ, Value, Etat  
            FROM cmd_device 
                INNER JOIN Device on Device.ID = cmd_device.Device_ID 
                INNER JOIN Type_Device on Type_Device.Id = Device.Type_ID 
                LEFT JOIN Lieux on Lieux.Id = Device.Lieux_ID 
        ) as t 
        WHERE now() >= t.DateRaz and RAZ IS NOT NULL
            AND (t.Status != t.Etat or t.Type_ID = 8) 
        group by sRequest;"""
        cursor.execute(sql)
        for row in cursor.fetchall():
            New_Status = row[0]
            cmd_device_id = row[1]
            DeviceID = row[2]
            CarteID = row[3]
            Nom = row[4]
            TypeAction = row[5]
            Type = row[6]
            Type_ID = row[7]
            Lieux = row[8]
            ID = row[9]
            sensor_attach_value = row[10]
            Request = row[11]
            RAZ = row[12]
            Value = row[13]
            Etat = row[14]
            if RAZ != "NULL" and New_Status == 0 and Type_ID != 8:
                Action(New_Status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value, Value, Etat)
                if TypeAction == "Info":
                    cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
            elif Type_ID == 8:
                context = ssl._create_unverified_context()
                Request = json.loads(Request)
                url = "https://127.0.0.1/ThiDom/Core/"+Request["url_ajax"]+"?"
                data = Request["data"]
                # url_values = urllib.urlencode(data)
                url_values = data
                full_url = urllib2.Request(url, url_values)
                try:
                    exec_cmd = urllib2.urlopen(full_url, context=context)
                except urllib2.HTTPError as e:
                    print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning Exec url :" + url + url_values + " error : " + str(e.code) + "//" + str(e.read())
                except urllib2.URLError, e:
                    pass
                # Minute = datetime.datetime.now().minute
                # cursor.execute("update cmd_device set date = (select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute % RAZ)+" SECOND)  where ID ="+str(cmd_device_id))
            else:
                Action(New_Status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value, Value, Etat)
                # cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
        DbConnect.commit()
        time.sleep(0.5)
    except KeyboardInterrupt:
        print "Bye"
        sys.exit()
