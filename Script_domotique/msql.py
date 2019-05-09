#o!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb
import time 

host = "localhost"
usr = "{{usersql}}"
pwd = "{{pwdsql}}"
db = "{{bddsql}}"
idnotify = "{{idnotify}}"

DbConnect = None
while DbConnect is None:
    try:
        DbConnect = MySQLdb.connect(host, usr, pwd, db)
        time.sleep(0.2)
        cursor = DbConnect.cursor()
        try:
            cursor = cursor
        except:
            setError("Connexion DB Failed")
            pass
    except MySQLdb.Error:
        DbConnect = None
        time.sleep(10)
        