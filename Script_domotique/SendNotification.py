#o!/usr/bin/python
# -*- coding: utf-8 -*-

import datetime
import urllib2
import urllib
import unicodedata
import ssl
import msql

def SendNotification(value, id_notif="now"):
    cursor = msql.cursor
    sql = """select Device.Id from Device
                INNER JOIN Module_Type on ModuleName = "Telegram" and Device.Module_Id = Module_Type.Id
                WHERE Configuration != ''
            LIMIT 1"""
    cursor.execute(sql)
    if cursor.rowcount:
        for row in cursor.fetchall():            
            url = "https://localhost/ThiDom/Core/plugins/Telegram/Desktop/Telegram_ajax.php"
            data = {}
            data['act'] = "sendMessage"
            data['Device_id'] = row[0]
            data['msg'] = value
            url_values = urllib.urlencode(data)        
            context = ssl._create_unverified_context()
            req = urllib2.Request(url, url_values)
            try:
                urllib2.urlopen(req, context=context)
            except urllib2.URLError as e:
                #print "######  SendNotification Telegram Error value:"+value+"/url = "+url+" /data = "+url_values+" /"
                print e.reason
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
        url_values = urllib.urlencode(data)
        full_url = url + '?' + url_values
        try:
            urllib2.urlopen(full_url)
        except:
            print "######  SendNotification Error value:"+value




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