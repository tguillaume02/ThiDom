#!/bin/bash

cd /var/www/nodejs/public_html/ && nohup nodejs /var/www/nodejs/public_html/index.js >> /var/www/nodejs/log/console.log 2>&1 &
