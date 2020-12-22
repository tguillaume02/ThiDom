#!/bin/bash

sudo a2enmod expires
sudo a2enmod http2
sudo a2enmod headers

sudo a2dismod php7*
sudo a2dismod mpm_prefork
sudo a2enmod mpm_event

sudo apt install php-fpm -y
sudo a2enconf php*-fpm
sudo a2enmod proxy_fcgi setenvif


sudo sed -i 's/pm = dynamic/pm = ondemand /g' /etc/php/7.*/fpm/pool.d/www.conf > /dev/null 2>&1 
sudo sed -i 's/pm.max_children = [^"]*/pm.max_children = 500/g' /etc/php/7.*/fpm/pool.d/www.conf > /dev/null 2>&1

sudo systemctl restart apache2
