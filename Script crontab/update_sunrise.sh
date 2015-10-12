#!/bin/bash
sunrise=$(curl -s http://weather.yahooapis.com/forecastrss?w=613814|grep astronomy| awk -F\" '{s1= $2 ;("date --date=\""s1"\" +%T") |getline s1; print s1 }')
sunset=$(curl -s http://weather.yahooapis.com/forecastrss?w=613814|grep astronomy| awk -F\" '{s2=$4;("date --date=\""s2"\" +%T") |getline s2;print s2}')
condition=$(curl -s http://weather.yahooapis.com/forecastrss?w=613814|grep condition| awk -F\" '{print $2}')

python /home/pi/Script_domotique/sunrise.py "$sunrise" "$sunset" "$condition"

