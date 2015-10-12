#!/bin/bash

  [ `find /var/www/nodejs/public_html/temperature.json -mmin +16` ] && touch /var/www/nodejs/public_html/cronNode.txt && strDate=$(date) && strAct=" Relance nodejs" && echo $strDate $strAct >> /var/www/nodejs/public_html/cronNode.txt && cd /var/www/nodejs/public_html/ && nohup nodejs /var/www/nodejs/public_html/index.js >>  /var/www/nodejs/log/console.log 2>&1 &



