# o!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb
import sys
import time
import msql

try:
    db = MySQLdb.connect(msql.host, msql.usr, msql.pwd, msql.db)
    time.sleep(20)
    cursor = db.cursor()

except db.Error, e:
    print "Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)


# while True:
try:
    time.sleep(0.2)
    cursor.execute("""INSERT INTO Temperature (date,Temp,Lieux_ID,Cmd_device_ID) 
        SELECT DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') AS date , ROUND(AVG(Temperature_Temp.temp),1) AS avg, Temperature_Temp.Lieux_ID, Temperature_Temp.Cmd_device_ID
        FROM Temperature_Temp 
        INNER JOIN cmd_device ON cmd_device.Id = Temperature_Temp.Cmd_device_Id 
        INNER JOIN widget ON widget.id = cmd_device.widget_id
        WHERE
        Temperature_Temp.Date BETWEEN (SELECT DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL 15 MINUTE - INTERVAL 1 SECOND) AND (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00')) 
        AND widget.Type = "Text"
        GROUP BY Cmd_device_ID""")
    
    cursor.execute("""INSERT INTO Temperature (date,Temp,Lieux_ID,Cmd_device_ID) 
        SELECT Temperature_Temp.date , Temperature_Temp.temp, Temperature_Temp.Lieux_ID, Temperature_Temp.Cmd_device_ID
        FROM Temperature_Temp 
        INNER JOIN cmd_device ON cmd_device.Id = Temperature_Temp.Cmd_device_Id         
        INNER JOIN widget ON widget.id = cmd_device.widget_id 
        WHERE 
        Temperature_Temp.Date BETWEEN (SELECT DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL 15 MINUTE - INTERVAL 1 SECOND) AND (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00')) 
        AND widget.Type != "Text"
        """)
    
    cursor.execute("DELETE FROM Temperature_Temp WHERE Date between (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL  20 MINUTE - INTERVAL 1 SECOND) and (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00'))")
    db.commit()
    mon_fichier = open("/home/pi/text.txt", "w")
    mon_fichier.write("test")
    mon_fichier.close()
    cursor.close()
    db.close()
    time.sleep(1)
    sys.exit()
except KeyboardInterrupt:
    print "Bye"
sys.exit()
