#!/usr/bin/env python
# -*- coding: utf-8 -*-

import serial
import sys
import datetime
import time
import json
import msql
from SendNotification import SendNotification
#import ptvsd
#ptvsd.enable_attach(secret = 'my_secret')

print("####### READ USB - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()))


# #############  TRY CONNECT SQL ##################
cursor = msql.cursor
DbConnect = msql.DbConnect

SendNotification("Demarrage read usb", "-1")

ser = serial.Serial(port='/dev/ttyUSB1', baudrate=115200)


def ReadArduino():
    global Alert_LastTime
    Alert_LastTime = '1900-01-01 00:00:00'
    while True:
        try:
            x = ""
            x = ser.readline()  # read one byte
            str_usb_read = x
            mon_fichier = open("/home/pi/Script crontab/debug/toto.txt", "a")
            mon_fichier.write(getDate()+" : ")
            mon_fichier.write(x)
            mon_fichier.write("\r\n")
            mon_fichier.close()
            x = x.replace("\r\n", "")

            if ":" in x:
                widget_type = ""
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
                        setError("Split /", x)
                try:
                    pinID, status = x.split(":")
                except:
                    setError("Split :", x)

                if "_" in pinID:
                    try:
                        widget_type, pinID = pinID.split("_")
                    except:
                        setError("Split _", pinID)

                if "@" in pinID:
                    try:
                        pinID, value = pinID.split("@")
                    except:
                        setError("Split @", x)

#                ############ TEMPORAIRE A RETIRER QUAND CORRECTIF FAIT SUR ARDUINO

                if status == "":
                    status = value

                pinID = pinID.strip()
                widget_type = widget_type.strip()
                status = status.strip()
                value = value.strip()
                x = ""

                if widget_type == "Alert":
                    Alert(str_usb_read)
                else:
                    if SlaveCarteId != "" and pinID != "":
                        cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteId=%s and cmd_device.Device_ID = Device.ID", (value, status, getDate(), pinID, int(SlaveCarteId)))

                    elif pinID != "":
                        SlaveCarteId = "0"
#                        cursor.execute("UPDATE Etat_IO SET Value=%s, Etat=%s, Date=%s where DeviceID=%s and Carte_Id=0", (value,status,date,pinID))
                        cursor.execute("UPDATE cmd_device, Device SET Value=%s, Etat=%s, Date=%s WHERE cmd_device.DeviceId = %s and Device.CarteId=0 and cmd_device.Device_ID = Device.ID", (value, status, getDate(), pinID))
                    setHistory(SlaveCarteId, pinID, value, status)

                    if status == 0 or status == "0":
                        sstatus = "off"
                    elif status == 1 or status == "1":
                        sstatus = "on"
                    else:
                        status = status

                    try:
                        cursor.execute("SELECT cmd_device.ID, cmd_device.Nom, Lieux.Nom as Lieux, widget.Id, Device.Configuration, cmd_device.History, cmd_device.Notification, Device.History as Log FROM cmd_device LEFT JOIN Device on Device.ID= cmd_device.Device_ID LEFT JOIN widget on cmd_device.widget_Id = widget.Id LEFT JOIN Lieux on Lieux.ID = Device.Lieux_ID WHERE DeviceID=%s and Device.CarteID=%s", (pinID, SlaveCarteId))
                        for row in cursor.fetchall():
                            cmd_device_ID = row[0]
                            nom = row[1]
                            lieux = row[2]
                            widget_Id = row[3]
                            bHistory = row[5]
                            Notification = row[6]
                            bLog = row[7]
                           # try:
                           #     Configuration = json.loads(row[4])
                           # except ValueError, e:
                           #     Configuration = ''

                        if cmd_device_ID != "":
                            if widget_Id == "slider":
                                value = ""

                            if int(bLog) == 1:
                                setLog(cmd_device_ID, str_usb_read, nom, lieux, value, sstatus )

                            # if 'Notification' in Configuration:
                            #    if Configuration['Notification'] == '1':
                            if Notification == 1:
                                try:
                                    SendNotification(nom + " " + lieux + " " + str(value) + " : " + sstatus, str(cmd_device_ID))
                                except:
                                    setError("Send Notification New Status")
                                    pass
                        else:
                            NewDevice(SlaveCarteId, pinID, value, getModuleType(ser.port), widget_type)
                    except:
                        setError("New Status")
                        pass
                        try:
                            SendNotification("Erreur dans la requete read_usb", "-2")
                        except:
                            setError("Erreur dans la requete read_usb")
                            pass

#                    if (cmd_device_ID != ""):
#                        Alert('can Scenario cmd_device_id: '+str(cmd_device_ID))

            DbConnect.commit()
        except KeyboardInterrupt:
            print("Bye")
            sys.exit()


def Alert(str_usb_read):
    try:
        cursor.execute("INSERT INTO Log (DeviceID,DATE,ACTION,Message) VALUES (%s, %s, %s, %s)", ("", getDate(), "", "Alert : " + str_usb_read))
        try:
            SendNotification(str_usb_read)
        except:
            setError("Send Notification Alert")
#            print "####### READ USB -  Send Notification Alert #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error %d: %s" % (e.args[0], e.args[1])
            pass
    except:
        setError("Save Notification Alert")
#        print "####### READ USB - Save Notification Alert #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error %d: %s" % (e.args[0], e.args[1])
        pass


def NewDevice(SlaveCarteId, pinID, value, module_type, widget_type="-99"):
    try:
        cursor.execute("SELECT MAX(id)+1 as maxid from Device ")
        maxId = cursor.fetchone()[0]
        cursor.execute("INSERT INTO Device (ID, Nom, CarteId, Module_Id) VALUES (%s, %s, %s, %s) ", (maxId, "New Device", SlaveCarteId, module_type))
        cursor.execute("INSERT INTO cmd_device (Nom, Device_Id, DeviceId, Value, Date, Widget_Id) VALUES (%s, %s, %s, %s, %s, %s) ", ("New Device", maxId, pinID, value, getDate(), widget_type))
        setLog(maxId, '', 'New Device', '', value, value )
    except:
        setError("New Device")
        pass


def getModuleType(port):
    try:
        cursor.execute(" SELECT Module_Type.Id FROM Configuration INNER JOIN Module_Type on Configuration.Plugin = Module_Type.ModuleName WHERE Configuration.Value= %s and Configuration.Conf='port'", (port))
        if cursor.rowcount > 0:
            for row in cursor.fetchall():
                return row[0]
    except:
        setError("getModuleType")
        pass


def getDate():
    return datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')


def setHistory(CarteId, pinId, value, etat):
    cursor.execute(""" SELECT cmd_device.History, cmd_device.Id, Device.Lieux_Id, widget.Type FROM cmd_device
                        INNER JOIN Device on Device.Id = cmd_device.Device_Id
                        INNER JOIN widget ON cmd_device.Widget_Id = widget.Id
                        WHERE DeviceId = %s and Device.CarteId = %s""",
                    (pinId, CarteId)
                )
    result = cursor.fetchone()
    if result:
        if result[0] == 1:
            cursor.execute("INSERT INTO Temperature_Temp (Date, Temp, Lieux_Id, Cmd_device_Id) VALUES (%s, %s, %s, %s)", (getDate(), value if result[3] == "Text" else etat, result[2], result[1]))
    cursor.execute("delete from Log where Log.date < SUBDATE(CURRENT_DATE, INTERVAL 15 DAY)")

def setLog(cmd_device_ID, str_usb_read, nom, lieux, value, sstatus ):
    cursor.execute("INSERT INTO Log (DeviceId, DATE, ACTION, Message, Value, Etat) VALUES (%s, %s, %s, %s, %s, %s)", (cmd_device_ID, getDate(), str_usb_read, nom + " " + lieux + " " + str(value) + " : " + sstatus, str(value), sstatus))

def setError(situ, value=(sys.exc_info()[0])):
    print("####### READ USB  "+situ+" #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()) + " Error: %s" % value)


try:
    ReadArduino()
except KeyboardInterrupt:
    print("Bye")
    sys.exit()
