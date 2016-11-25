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

ser = serial.Serial(port='/dev/ttyUSB1', baudrate=9600)

def SendDataToUsb(data):
    if 'ser' not in locals():
        ser = serial.Serial(port='/dev/ttyUSB1', baudrate=9600)
    ser.write(data)
def Action(New_status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value):
    if Type == "Chauffage":
        #val = "Chauffage_"+str(DeviceID)+"#"+str(New_Status)+"\n"
        if float(sensor_attach_value) < float(New_Status):
            str_action = "1"
        else:
            str_action = "0"
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str_action+"\n"
        #print val
        SendDataToUsb(val)
    else:
        val = str(CarteID)+"/"+str(DeviceID)+"@"+str(New_Status)+":"+str(New_Status)+"\n"
        #print val
        SendDataToUsb(val)
    now = datetime.datetime.now()
    now = now.strftime('%Y-%m-%d %H:%M:%S')
    cursor.execute("INSERT INTO Log (DeviceID, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (ID, now, val, "Planning: "+Lieux+" "+Nom+" " + str(CarteID) + " " + str(DeviceID) + " " + str(New_Status)))

while True:
    try:
        time.sleep(0.1)
        try:
            DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
            time.sleep(0.1)
            cursor = DbConnect.cursor()
        except MySQLdb.Error, e:
            if msql.idnotify != "":
                urllib.urlopen("http://notify8702.freeheberg.org/?id="+msql.idnotify+"&notif=Erreur connection bdd planning&id_notif:-3")
            print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning %s" % (e.args[1])
            sys.exit(1)

        sql = " SELECT STATUS, cmd_device.id, cmd_device.DeviceID, Device.CarteID, Device.Nom, Type_Device.Type, Type_Device.ID as Type_ID, Lieux.Nom, Device.ID, IFNULL(sensor_attach.value,'') as sensor_attachValue , '' as Request, cmd_device.RAZ FROM planning inner join cmd_device on cmd_device.ID = ETAT_IO_ID AND planning.STATUS != cmd_device.Value inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.Id = Device.Type_ID inner join Lieux on Lieux.Id = Device.Lieux_ID left join cmd_device as sensor_attach on sensor_attach.ID = cmd_device.sensor_attachID WHERE (DAYS  like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M', time.localtime())+":00' and ACTIVATE = 1) UNION SELECT STATUS,cmd_device_id,DeviceID,CarteID,Nom,Type,Type_ID,LieuxNom  ,Device_ID, '' as sensor_attachValue, IFNULL(Request,'') as sRequest, RAZ  from ( Select 0 as STATUS,cmd_device.id as cmd_device_id, cmd_device.DeviceID,Device.CarteID,Device.Nom,Type,Device.Type_ID, Lieux.Nom as LieuxNom, Device.ID as Device_ID,Request,Date,Date_ADD(date, INTERVAL RAZ SECOND) as DateRaz, RAZ, Etat from cmd_device inner join Device on Device.ID = cmd_device.Device_ID inner join Type_Device on Type_Device.Id = Device.Type_ID left join Lieux on Lieux.Id = Device.Lieux_ID ) as t where now() >= t.DateRaz and (t.STATUS != t.Etat or t.Type_ID = 8) group by sRequest;"
        cursor.execute(sql)
        for row in cursor.fetchall():
            New_Status = row[0]
            cmd_device_id = row[1]
            DeviceID = row[2]
            CarteID = row[3]
            Nom = row[4]
            Type = row[5]
            Type_ID = row[6]
            Lieux = row[7]
            ID = row[8]
            sensor_attach_value = row[9]
            Request = row[10]
            RAZ = row[11]
            if RAZ != "NULL" and New_Status == 0 and Type_ID != 8:
                Action(New_Status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value)
                #cursor.execute("update cmd_device set cmd_device.Etat ='0', cmd_device.Value ='0' where ID ="+str(cmd_device_id))
            elif Type_ID == 8:
                context = ssl._create_unverified_context()
                Request = json.loads(Request)
                url = "https://localhost/ThiDom/"+Request["url_ajax"]
                data = Request["data"]
                #url_values = urllib.urlencode(data)
                url_values = data
                full_url = urllib2.Request(url, url_values)
                try:
                    exec_cmd = urllib2.urlopen(full_url, context=context)
                except urllib2.HTTPError as e:
                    print time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error planning Exec url :" + url + url_values + " error : " + str(e.code) + "//" + str(e.read())
                Minute = datetime.datetime.now().minute
                #print sql
                #print "-------------"
                #print "update cmd_device set date =(select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute%RAZ)+" MINUTE)  where ID ="+str(cmd_device_id)
                cursor.execute("update cmd_device set date = (select DATE_FORMAT(now(), '%Y-%m-%d %H:00:00') - INTERVAL "+str(Minute % RAZ)+" SECOND)  where ID ="+str(cmd_device_id))
            else:
                Action(New_Status, DeviceID, CarteID, Nom, Type, Lieux, ID, sensor_attach_value)

        DbConnect.close()
        time.sleep(0.5)
        # db.autocommit(True)
    except KeyboardInterrupt:
        print "Bye"
        sys.exit()
