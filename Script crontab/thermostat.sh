#!/bin/bash

python -u /home/pi/Script_domotique/thermostat.py  >> /home/pi/Script\ crontab/debug/console.log 2>&1 &

