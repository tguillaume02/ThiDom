#!/bin/bash

python -u /home/pi/Script_domotique/monitor/RunMonitor.py  >> /home/pi/Script\ crontab/debug/console.log 2>&1 &
