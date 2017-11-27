# o!/usr/bin/python
# -*- coding: utf-8 -*-

import serial
import MySQLdb
import time
import datetime
import urllib
import urllib2
import sys
import msql
import json
import ssl
#import ptvsd
#ptvsd.enable_attach(secret="my_secret")

print("####### Planning - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()))

##############  TRY CONNECT SQL ##################
DbConnect = None
while DbConnect is None:
    try:
        DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
        time.sleep(0.1)
        cursor = DbConnect.cursor()
    except MySQLdb.Error, e:
        DbConnect = None


def SendDataToUsb(data):
    if 'ser' not in locals() or not ser.is_open:
        ser = serial.Serial(port='/dev/ttyUSB1', baudrate=115200)
    ser.write(data)


def Action(New_status, DeviceID, CarteID, Nom, Type, Lieux, Device_Id, sensor_attach_value, ValueAct, EtatAct):
    if Type == "Thermostat":
        if float(sensor_attach_value) < float(New_Status):
            str_action = "1"
        else:
            str_action = "0"
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(str_action)+"\n"
        if (str(ValueAct) != str(New_Status) or str(str_action) != str(EtatAct)):
            SendDataToUsb(val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (Device_Id, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))
    else:
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(New_Status)+"\n"
        if (ValueAct != New_Status or New_Status != EtatAct):
            SendDataToUsb(val)
            now = datetime.datetime.now()
            now = now.strftime('%Y-%m-%d %H:%M:%S')
            cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (Device_Id, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))

while True:
    try:
        time.sleep(0.1)

        sql = """ SELECT Status, cmd_device.id, cmd_device.DeviceID, Device.CarteID, Device.Nom, cmd_device.Type as TypeAction, widget.Name, widget.Id as widget_Id, Lieux.Nom, Device.ID, IFNULL(sensor_attach.value,'') as sensor_attachValue , '' as Request, cmd_device.RAZ, cmd_device.Value, cmd_device.Etat
        FROM Planning
            INNER JOIN cmd_device on cmd_device.ID = CmdDevice_Id AND Planning.Status != cmd_device.Etat
            INNER JOIN Device on Device.ID = cmd_device.Device_ID
            INNER JOIN widget on widget.Id = cmd_device.Widget_Id
            INNER JOIN Lieux on Lieux.Id = Device.Lieux_ID
            LEFT JOIN cmd_device as sensor_attach on sensor_attach.ID = cmd_device.sensor_attachID
        WHERE ((DAYS  like '%"""+str(time.localtime().tm_wday)+"""%' OR Planning.date = '"""+time.strftime("%y/%m/%d")+"""' ) AND HOURS ='"""+time.strftime('%H:%M', time.localtime())+""":00' AND ACTIVATE = 1)
        UNION
        SELECT Status, cmd_device_id, DeviceID, CarteID, Nom, TypeAction, WidgetName, widget_Id, LieuxNom, Device_ID, '' as sensor_attachValue, IFNULL(Request,'') as sRequest, RAZ, Value, Etat
        FROM (
            SELECT 0 as Status, cmd_device.id as cmd_device_id, cmd_device.DeviceID, Device.CarteID, Device.Nom, cmd_device.Type as TypeAction, widget.Name as WidgetName, widget.Id as widget_Id, Lieux.Nom as LieuxNom, Device.ID as Device_ID,Request, Date, IFNULL(DATE_ADD(Date ,INTERVAL RAZ SECOND),"1900/01/01 00:00:00") as DateRaz, RAZ, Value, Etat
            FROM cmd_device
                INNER JOIN Device on Device.ID = cmd_device.Device_ID
                LEFT JOIN widget on widget.Id = cmd_device.widget_Id
                LEFT JOIN Lieux on Lieux.Id = Device.Lieux_ID
        ) as t
        WHERE now() >= t.DateRaz and RAZ IS NOT NULL
#            AND (t.Status != t.Etat or t.widget_Id = 5)
            AND (t.Status != t.Etat and  t.Etat != '' or t.Request != '')
        group by sRequest;"""

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

            if Request != "":
                context = ssl._create_unverified_context()
                Request = json.loads(Request)
                url = "https://127.0.0.1/ThiDom/Core/"+Request["url_ajax"]
                data = Request["data"]
                # url_values = urllib.urlencode(data)
                url_values = data
                if Device_Id:
                    if data != "":
                        url_values += "&"
                    url_values += 'Device_id='+str(Device_Id)
                full_url = urllib2.Request(url, url_values)
                try:
                    exec_cmd = urllib2.urlopen(full_url, context=context)
                except urllib2.HTTPError as e:
                    print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning Exec url :" + url + url_values + " error : " + str(e.code) + "//" + str(e.read())
                except urllib2.URLError, e:
                    pass
            elif RAZ != "NULL" and New_Status == 0:
                Action(New_Status, DeviceID, CarteID, Nom, WidgetName, Lieux, Device_Id, sensor_attach_value, Value, Etat)
                if TypeAction == "Info":
                    cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
                # Minute = datetime.datetime.now().minute
                # cursor.execute("update cmd_device set date = (select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute % RAZ)+" SECOND)  where ID ="+str(cmd_device_id))
            else:
                Action(New_Status, DeviceID, CarteID, Nom, WidgetName, Lieux, Device_Id, sensor_attach_value, Value, Etat)
                # cursor.execute(" update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
        DbConnect.commit()
        time.sleep(0.5)
    except KeyboardInterrupt:
        print "Bye"
        sys.exit()
