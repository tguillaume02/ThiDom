#o!/usr/bin/python
# -*- coding: utf-8 -*-

import datetime
import urllib2
import urllib
import unicodedata
import msql

def SendNotification(value, id_notif="now"):
    if msql.idnotify != "":        
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
