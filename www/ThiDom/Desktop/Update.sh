#!/bin/bash

sudo a2enmod expires
sudo a2enmod http2
sudo a2enmod headers

sudo a2dismod php7*
sudo a2dismod mpm_prefork
sudo a2enmod mpm_event

sudo apt install php-fpm
sudo a2enconf php*
sudo a2enmod proxy_fcgi setenvif

sudo systemctl restart apache2