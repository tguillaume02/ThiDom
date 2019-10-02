#o!/usr/bin/python
# -*- coding: utf-8 -*-

import datetime
import urllib3
import urllib
import unicodedata
import ssl
import msql
import sys
import json
import unidecode
# import Scenario
# import ptvsd
# ptvsd.enable_attach(secret="my_secret")

global oldDate
global oldMessage
# global channelId

oldDate = datetime.datetime.now().strftime("%s")
oldMessage = ""
# channelId = ""

# #############  TRY CONNECT SQL ##################
cursor = msql.cursor
DbConnect = msql.DbConnect
urlPool = urllib3.PoolManager(assert_hostname=False)
urllib3.disable_warnings()

def SendNotification(value, id_notif="now", to=""):
    global channelId
    # value = unicodedata.normalize('NFKD', unicode(value,).encode('ASCII', 'ignore')
    if to != "":
        sql = """SELECT cmd_device.Id FROM cmd_device
                INNER JOIN Device ON Device.Id = cmd_device.Device_Id
                INNER JOIN Module_Type ON ModuleName = "Telegram" AND Device.Module_Id = Module_Type.Id
                where cmd_device.Id = """+to
    else:
        sql = """SELECT cmd_device.Id from Device
                    INNER JOIN Module_Type ON ModuleName = "Telegram" AND Device.Module_Id = Module_Type.Id
                    INNER JOIN cmd_device ON Device.Id = cmd_device.Device_Id
                    WHERE Configuration != ''
                    LIMIT 1"""
    cursor.execute(sql)
    if cursor.rowcount:
        for row in cursor.fetchall():        
            url = "https://localhost/ThiDom/Core/plugins/Telegram/Desktop/Telegram_ajax.php"
            data = {}
            data['act'] = "sendMessage"
            data['cmdDeviceId'] = row[0]
            data['msg'] = value.replace("\xb0", "°")
            # try:
                # data['channelId'] =  Scenario.channelId
            # except:
                # data['channelId'] = ""
            if sys.version_info[0] < 3:
                url_values = urllib.urlencode(data) 
            else:               
                url_values = urllib.parse.urlencode(data)
            context = ssl._create_unverified_context()
            try:
                url = url + "?" + url_values
                req = urlPool.request("POST",url)
                channelId  = ""
            except urllib3.exceptions.NewConnectionError:
                #print "######  SendNotification Telegram Error value:"+value+"/url = "+url+" /data = "+url_values+" /"
                print ("######  SendNotification Error value:"+value)
    elif msql.idnotify != "":        
        url = "http://notify8702.freeheberg.org/"
        data = {}
        data['id'] = msql.idnotify
        data['notif'] = value
        if id_notif == "now":
            now = datetime.datetime.now()
            now = now.strftime("%Y%m%d%H%M%S")
            id_notif = str(now)
        data['id_notif'] = id_notif
        url_values = urllib.parse.urlencode(data)
        
        try:         
            url = url + "?" + url_values
            urlPool.request("POST",url)
            # urllib3.urlopen(full_url)
        except:
            print ("######  SendNotification Error value:"+value)

#def ReceivedNotification():
#    global oldDate
#    global oldMessage
#    global channelId
#    try:
#        sql = """ SELECT Configuration FROM thidom.Device
#                INNER JOIN Module_Type ON  ModuleName="Telegram" and Device.Module_Id = Module_Type.Id"""
#        cursor.execute(sql)
#        if cursor.rowcount:
#            for row in cursor.fetchall():       
#                jsonBot = json.loads(row[0])
#                botToken  = jsonBot["BotToken"]
            
#            url = "https://api.telegram.org/bot"+botToken+"/getUpdates"            
#            req = urllib2.Request(url)
#            try:
#                data = urllib2.urlopen(req)
#                data = json.load(data)
#                # data["result"][0]["channel_post"]["chat"]["id"]
#                lastData =  data["result"][len(data["result"])-1]
#                channelId  = ""
#                if "message" in lastData:                        
#                    channelId = data["result"][len(data["result"])-1]["message"]["chat"]["id"]
#                    msgText = data["result"][len(data["result"])-1]["message"]["text"]
#                    msgDate = data["result"][len(data["result"])-1]["message"]["date"]
#                else:
#                    msgDate =  data["result"][len(data["result"])-1]["channel_post"]["date"]
#                    msgText = data["result"][len(data["result"])-1]["channel_post"]["text"]
#                if  msgDate > oldDate or msgText != oldMessage:
#                    oldDate = msgDate
#                    oldMessage = msgText
#                    Scenario.channelId = channelId
#                    Scenario.main(msgText)
#            except:
#                pass
#                # print ("######  SendNotification Telegram Error ReceivedNotification MainScenario")
#    except:
#        print ("######  SendNotification Telegram Error  ReceivedNotification")


#if __name__ == "__main__":
#    while True:
#        try:
#            ReceivedNotification()
#        except KeyboardInterrupt:
#            print ("Bye")
#            sys.exit()

 #   sql = """select Device.Id from Device
 #           inner join Module_Type on ModuleName = "Telegram" and Device.Module_Id = Module_Type.Id
 ###           where Configuration != ''
 ###           LIMIT 1"""
 ###   cursor.execute(sql)
 ##   if cursor.rowcount:
 #       for row in cursor.fetchall():            
 #           url = "http://localhost/ThiDom/Core/plugins/Telegram/Desktop/Telegram_ajax.php"
 #           data = {}
 #           data['act'] = "sendMessage"
 #           data['Device_id'] = row[0]
 #           data['msg'] = value
 #           url_values = urllib.urlencode(data)            
 #           req = urllib2.Request(url, url_values)
 #           try:
 #               urllib2.urlopen(req)
 #           except:
 #               print "######  SendNotification Telegram Error value:"+value
 #   el
