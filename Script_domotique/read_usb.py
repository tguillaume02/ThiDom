#!/usr/bin/env python
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
import json
import msql
from SendNotification import SendNotification
#import ptvsd
#ptvsd.enable_attach(secret="my_secret")

processus = 'mysqld'
s = os.popen('ps ax').read()

while processus not in s:
    n = 1
    time.sleep(0.2)
    s = os.popen('ps ax').read()

print "####### READ USB - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime())

try:
    DbConnect = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
    cursor = DbConnect.cursor()

    try:
        SendNotification("Demarrage read usb", "-1")
    except:
        print "####### READ USB - Send Notification Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
        pass

except MySQLdb.Error, e:
    print "####### READ USB - Error Connection BDD #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)

ser = serial.Serial(port='/dev/ttyUSB1', baudrate=115200)

def ReadArduino():
    global Alert_LastTime
    Alert_LastTime = '1900-01-01 00:00:00'
    while True:
        try:
            x = ""
            x = ser.readline()  # read one byte
            str_usb_read = x
            date = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S:%f')
            mon_fichier = open("/home/pi/Script crontab/debug/toto.txt", "a")
            mon_fichier.write(date+" : ")
            mon_fichier.write(x)
            mon_fichier.write("\r\n")
            mon_fichier.close()
            x = x.replace("\r\n", "")

            if ":" in x:
                type = ""
                pinID = ""
                value = ""
                status = ""
                SlaveCarteId = ""
                cmd_device_ID = ""
                lieux = ""

                if "/" in x:
                    try:
                        x, SlaveCarteId = x.split("/")
                    except:
                        print "####### READ USB - Split / #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + "value: " + x
                try:
                    pinID, value = x.split(":")
                except:
                    print "####### READ USB - Split : #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + "value: " + x

                status = value

                if "_" in pinID:
                    try:
                        type, pinID = pinID.split("_")
                    except:
                        print "####### READ USB - Split _ #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + "value: " + pinID

                if "@" in pinID:
                    status = value
                    try:
                        pinID, value = pinID.split("@")
                    except:
                        print "####### READ USB - Split @ #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + "value: " + x
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

                if type == "Temp" or type == "0":
                    Temp(SlaveCarteId, pinID, value)
                elif type == "Alert":
                    Alert(str_usb_read)
                else:
                    date = time.strftime('%y-%m-%d %H:%M:%S', time.localtime())
                    if SlaveCarteId != "" and pinID != "":
                        cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteID=%s and cmd_device.Device_ID = Device.ID", (value, status, date, pinID, int(SlaveCarteId)))
                        now = datetime.datetime.now()
                        now = now.strftime('%Y-%m-%d %H:%M:%S')
                    elif pinID != "":
                        SlaveCarteId = "0"
                        #cursor.execute("UPDATE Etat_IO SET Value=%s, Etat=%s, Date=%s where DeviceID=%s and Carte_Id=0", (value,status,date,pinID))
                        cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteID=0 and cmd_device.Device_ID = Device.ID", (value, status, date, pinID))
                        now = datetime.datetime.now()
                        now = now.strftime('%Y-%m-%d %H:%M:%S')

                    if status == 0 or status == "0":
                        status = "off"
                    elif status == 1 or status == "1":
                        status = "on"
                    else:
                        status = status

                    try:
                        cursor.execute("SELECT cmd_device.ID, cmd_device.Nom, Lieux.Nom, Type_Device.widget_Id, Device.Configuration FROM cmd_device LEFT JOIN Device on Device.ID= cmd_device.Device_ID LEFT JOIN Type_Device on Device.Type_ID = Type_Device.ID LEFT JOIN Lieux on Lieux.ID = Device.Lieux_ID WHERE DeviceID=%s and Device.CarteID=%s", (pinID, SlaveCarteId))
                        for row in cursor.fetchall():
                            cmd_device_ID = row[0]
                            nom = row[1]
                            lieux = row[2]
                            widget_Id = row[3]
                            try:
                                Configuration = json.loads(row[4])
                            except ValueError, e:
                                Configuration = ''

                        if cmd_device_ID != "":
                            if widget_Id == "slider":
                                value = ""

                            now = datetime.datetime.now()
                            now = now.strftime('%Y-%m-%d %H:%M:%S')
                            cursor.execute("INSERT INTO Log (DeviceId, DATE, ACTION, Message) VALUES (%s, %s, %s, %s)", (cmd_device_ID, now, str_usb_read, nom + " " + lieux + " " + str(value) + " : " + status))

                            if 'Notification' in Configuration:
                                if Configuration['Notification'] == '1':
                                    try:
                                        SendNotification(nom + " " + lieux + " " + str(value) + " : " + status, str(cmd_device_ID))
                                    except:
                                        print "####### READ USB - Send Notification New Status #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
                                        pass
                        else:
                            NewDevice(SlaveCarteId, pinID, value,type)
                    except:
                        print "####### READ USB  New Status #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
                        pass
                        try:
                            SendNotification("Erreur dans la requete read_usb", "-2")
                        except:
                            print "####### READ USB - Erreur dans la requete read_usb #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
                            pass

                    if (cmd_device_ID != ""):
                        Alert('can Scenario cmd_device_id: '+str(cmd_device_ID))

            DbConnect.commit()
        except KeyboardInterrupt:
            print "Bye"
            sys.exit()

def Temp(SlaveCarteId, pinID, value):
    global Alert_LastTime
    cmd_device_ID = ''
    lieux = ''
    cursor.execute(" SELECT Lieux.Nom,Lieux.ID as Lieux_id,cmd_device.ID, COALESCE(Alert_Time,'0000-00-00 00:00:00') as Alert_Time  from cmd_device INNER JOIN Device on cmd_device.Device_ID = Device.ID INNER JOIN Lieux on Lieux.Id = Device.Lieux_ID where cmd_device.DeviceId = %s", (pinID))
    if cursor.rowcount > 0:
        bNotInBdd = False
        for row in cursor.fetchall():
            lieux = row[0]
            Lieux_ID = row[1]
            cmd_device_ID = row[2]
            Alert_Time = row[3]
    else:
        bNotInBdd = True
        lieux = pinID

    date = time.strftime('%y-%m-%d %H:%M:%S', time.localtime())

    if pinID == 'Exterieur':
        cmd_device_ID = 0
        Lieux_ID = 5
        #if float(value) < 0:
            #value = float(value) * -1
            #value = str(value)
    elif bNotInBdd is True:
        NewDevice(SlaveCarteId, pinID, value, type)
    cursor.execute("UPDATE cmd_device SET Value=%s, Etat=%s, Date=%s where ID=%s", (value, value, date, cmd_device_ID))
    if cmd_device_ID != '':
        cursor.execute("INSERT INTO Temperature_Temp VALUES (%s, %s, %s, %s, %s)", (date, value, lieux, Lieux_ID, cmd_device_ID))
    cursor.execute("delete from Log where Log.date < SUBDATE(CURRENT_DATE, INTERVAL 15 DAY)")

def Alert(str_usb_read):
    try:
        now = datetime.datetime.now()
        now = now.strftime('%Y-%m-%d %H:%M:%S')
        cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s, %s, %s, %s)", ("", now, "", "Alert : " + str_usb_read))
        try:
            SendNotification(str_usb_read)
        except IOError, e:
            print "####### READ USB - Send Notification Alert #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error %d: %s" % (e.args[0], e.args[1])
            pass
    except IOError, e:
        print "####### READ USB - Save Notification Alert #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error %d: %s" % (e.args[0], e.args[1])
        pass

def NewDevice(SlaveCarteId, pinID, value, type):
    try:
        date = time.strftime('%y-%m-%d %H:%M:%S', time.localtime())
        cursor.execute("SELECT MAX(id)+1 as maxid from Device ")
        maxId = cursor.fetchone()[0]
        cursor.execute("INSERT INTO Device (ID, Nom, CarteId, Type_Id) VALUES (%s, %s, %s, %s) ", (maxId, "New Device", SlaveCarteId, type))
        cursor.execute("INSERT INTO cmd_device (Nom, Device_Id, DeviceId, Type_Id, Value, Date, Visible) VALUES (%s, %s, %s, %s, %s, %s, %s) ", ("New Device", maxId, pinID, type, value, date, "-99"))
    except:
        print "####### READ USB - New Device #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % (sys.exc_info()[0])
        pass

try:
    ReadArduino()
except KeyboardInterrupt:
    print "Bye"
    sys.exit()
