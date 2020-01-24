# o!/usr/bin/python
# -*- coding: utf-8 -*-
import serial
import time
import sys
import msql
import json

#import ptvsd
#ptvsd.enable_attach(address=('localhost', 5678), redirect_output=True)
#ptvsd.wait_for_attach()
#ptvsd.break_into_debugger()

urlnotify = "http://notify8702.freeheberg.org/"
print("####### Thermostat - Start #######" + time.strftime('%A %d. %B %Y  %H:%M', time.localtime()))

# #############  TRY CONNECT SQL ##################
cursor = msql.cursor
DbConnect = msql.DbConnect
    
def SendDataToUsb(moduleName, moduleConfirmation, data):    
    # if 'ser' not in locals() or not ser.is_open:
    if moduleConfirmation != None:
        ser = serial.Serial(port=moduleConfirmation["com"], baudrate=moduleConfirmation["baudrate"])
        ser.write(data.encode())

timeout = 0
hysteresis = 0
Configuration = {}

while True:
    try:
        time.sleep(0.2)
        if time.time() > timeout or time.strftime('%S', time.localtime()) == '00':
            #sql = "select Etat_IO.DeviceID, Etat_IO.Value as Thermo, temp.Value, Etat_IO.Etat as status from Etat_IO inner join Etat_IO as temp on temp.ID = Etat_IO.sensor_attachID where Etat_IO.sensor_attachID <> 0 and Etat_IO.Type = 'Chauffage'"
            sql = """SELECT cmd_device.DeviceId, Device.Guid, Device.CarteId, cmd_device.Widget_Id, cmd_device.Value AS Thermo, temp.Value, cmd_device.Etat AS status, cmd_device.Request, Module_Type.ModuleName, Module_Type.ModuleType, Module_Type.ModuleConfiguration, Device.Configuration
                        FROM cmd_device
                            inner join Device ON Device.Id = cmd_device.Device_Id
                            inner join cmd_device AS temp ON temp.Id = cmd_device.sensor_attachId
                            inner join widget on widget.Id = cmd_device.Widget_Id
                            inner join Module_Type on Module_Type.ID = Device.Module_Id
                        WHERE cmd_device.sensor_attachId <> 0  and widget.Name='Thermostat' ;"""
#           print sql
            cursor.execute(sql)
            timeout = time.time() + 60
            for row in cursor.fetchall():
                DeviceID = row[0]
                GUID = row[1]
                CarteId = row[2]
                WidgetId = row[3]
                Thermo = float(row[4])
                TempValue = float(row[5])
                status = row[6]
                Request = row[7]
                ModuleName = row[8]
                ModuleType = row[9]
                ModuleConfiguration = row[10]
                DeviceConfiguration = row[11]
#               print New_Status
#               print row[4]
#               print Nom
#               print Type
#               print Lieux
#               print TempValue
#               print Thermo
#               print status

                try:
                    mode = Request["mode"]
                    pass
                except:
                    mode = ""
                    pass
                
                try:
                    conf = json.loads(DeviceConfiguration)
                    hysteresis = float(conf["Hysteresis"])
                    pass
                except:
                    hysteresis = float(0)
                    pass
                
                if ModuleType == "Communication":  
                    try:                                          
                        Configuration = json.loads(ModuleConfiguration)
                    except:
                        Configuration = None

                if mode != "manu":
                    if TempValue < Thermo-hysteresis and int(status) == 0:
                        val = str(CarteId)+"/"+str(GUID)+"_"+str(WidgetId)+"_"+str(DeviceID)+"@"+str(Thermo)+":1\n"
                       # print val
                        SendDataToUsb(ModuleName, Configuration, val)
                    elif TempValue >= Thermo+hysteresis and int(status) == 1:
                        val = str(CarteId)+"/"+str(GUID)+"_"+str(WidgetId)+"_"+str(DeviceID)+"@"+str(Thermo)+":0\n"
                        # print val
                        SendDataToUsb(ModuleName, Configuration, val)
            DbConnect.commit()
            time.sleep(1)
            # db.autocommit(True)
    except KeyboardInterrupt:
        print("Bye")
        DbConnect.close()
        sys.exit()

    # try:
        # if time.time() > timeout:
            # timeout = time.time() + 60*5
            # time.sleep(0.2)
                # cursor.execute("SELECT STATUS, Etat_IO.DeviceID, Etat_IO.Nom, Etat_IO.Type, Etat_IO.Lieux FROM planning inner join Etat_IO on Etat_IO.ID = ETAT_IO_ID WHERE DAYS like '%"+str(time.localtime().tm_wday)+"%' and HOURS ='"+time.strftime('%H:%M',time.localtime())+":00'")

            # for row in cursor.fetchall():
                # New_Status = row[0]
                # DeviceID = row[1]
                # Nom = row[2]
                # Type = row[3]
                # Lieux = row[4]
                # if Type == "Chauffage":
                    # val = str(DeviceID)+"#"+str(New_Status)+"/n"
                    # written = ser.write(val)
                # else:
                    # val = str(DeviceID)+":"+str(New_Status)+"/n"
                    # writen = ser.write(val)
        # except KeyboardInterrupt:
            # print "Bye"
            # sys.exit()
