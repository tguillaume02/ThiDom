# o!/usr/bin/python
# -*- coding: utf-8 -*-

import sys
import time
import msql

cursor = msql.cursor
db = msql.DbConnect

def insertData(year, data, lieux_id, cmd_device_id, reason):        
    if data != "":        
        cursor.execute(" SELECT data FROM HistoryData WHERE Lieux_Id = %s and Year = %s and Cmd_device_Id = %s" , (lieux_id, year, cmd_device_id))    
        if cursor.rowcount > 0:
            for row in cursor.fetchall():
                datas = row[0]
                if data not in datas:
                    dataNew = datas + data
                    cursor.execute(""" UPDATE HistoryData set Data = %s where Lieux_Id = %s and  Year = %s and Cmd_device_Id = %s """ , (dataNew, lieux_id, year, cmd_device_id))    
        else:
            cursor.execute(""" INSERT INTO HistoryData ( Year, Data, Lieux_id, Cmd_device_Id) VALUES (%s, %s, %s, %s)""",  (year, data, lieux_id, cmd_device_id))    

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

    cursor.execute("SELECT *, UNIX_TIMESTAMP(DATE_FORMAT(date, '1987-%m-%d %H:%i:%s')) as unixtimestamp FROM Temperature WHERE Temperature.Date BETWEEN (SELECT DATE_FORMAT(now(), concat('%Y-%m-%d %H:',(select case  when DATE_FORMAT(now(), '%i') < 15 then '00:00' when DATE_FORMAT(now(), '%i') < 30 then '15:00' when DATE_FORMAT(now(), '%i') < 45 then '30:00'  when DATE_FORMAT(now(), '%i') = 0 then '45:00' end)))) AND (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00')) ORDER BY Cmd_device_id, Lieux_Id, Date  ")
    
    oldyear = -99
    oldCmdDevice = -99
    oldLieux = -99
    data =""
    count = 0
    for row in cursor.fetchall():        
        if oldyear == -99 and oldCmdDevice == -99 and oldLieux == -99:
            oldyear = row[1].year
            oldCmdDevice = row[4]
            oldLieux = row[3]

        if row[3] != oldLieux:
            insertData(oldyear, data, oldLieux, oldCmdDevice,"Lieux")
            data = ""
            count = 0
            oldyear = row[1].year
            oldCmdDevice = row[4]   
            oldLieux = row[3]
        if row[4] != oldCmdDevice:
            insertData(oldyear, data, oldLieux, oldCmdDevice,"cmd")
            data = ""
            count = 0
            oldyear = row[1].year
            oldCmdDevice = row[4]
            oldLieux = row[3]
        if row[1].year != oldyear:
            insertData(oldyear, data, oldLieux, oldCmdDevice,"year")
            data = ""
            count = 0
            oldyear = row[1].year
            oldCmdDevice = row[4]
            oldLieux = row[3]

        date = row[1]
        temp = row[2]
        count += 1
        date = date.replace(year=1988)
        data += "[" +str(int(row[5])*1000) +","+ str(temp) +"],"
    insertData(oldyear, data, oldLieux, oldCmdDevice,"end")

    cursor.execute("DELETE FROM Temperature_Temp WHERE Date between (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00') - INTERVAL  20 MINUTE - INTERVAL 1 SECOND) and (select DATE_FORMAT(now(), '%Y-%m-%d %H:%i:00'))")
    db.commit()
    # mon_fichier = open("/home/pi/text.txt", "w")
    # mon_fichier.write("test")
    # mon_fichier.close()
    cursor.close()
    db.close()
    time.sleep(1)
    sys.exit()
except KeyboardInterrupt:
    print ("Bye")
    db.close()
    sys.exit()
