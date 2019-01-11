#!/bin/sh
# EXPAND FILESYSTEM BEFORE

webserver=${1-apache}
ws_upname="$(echo ${webserver} | tr 'a-z' 'A-Z')"

VERT="\033[32m"
NORMAL="\033[0m"

init_msg()
{
	msg_yesno="Y / N : "
	msg_yes="Y"
	msg_no="N"
	msg_cancel_install="Annulation de l'installation"
	msg_answer_yesno="Répondez Y ou N"
	msg_installer_welcome="*Bienvenue dans l'assistant d'intallation/mise à jour de Thidom*"
	msg_question_install_thidom="Etes-vous sûr de vouloir installer Thidom?"
	msg_warning_install_thidom="Attention : cela écrasera la configuration par défaut de ${ws_upname} si elle existe !"
	msg_warning_overwrite_thidom="Attention : votre installation existante de Thidom va être écrasée !"
	msg_install_deps="*             Installation des dépendances             *"
	msg_passwd_mysql="Saisir le mot de passe que vous souhaitez attribuer à l'utilisateur SQL root ?"
	msg_confirm_passwd_mysql="Confirmez vous que le mot de passe est :"
	msg_bad_passwd_mysql="Le mot de passe MySQL fourni est invalide !"
	msg_setup_dirs_and_privs="* Création des répertoires et mise en place des droits *"
	msg_copy_thidom_files="*             Copie des fichiers de Thidom             *"
	msg_config_db="*             Configuration de la base de donnée       *"
	msg_unable_to_download_file="Impossible de télécharger le fichier"
	msg_install_thidom="*                Installation de Thidom                *"
	msg_setup_apache="*         Paramétrage de la configuration apache       *"
	msg_optimize_webserver_cache_opcache="* installation de l'optimisation de cache Zend OpCache *"
	msg_install_complete="*                Installation terminée                 *"
	msg_login_info1="Vous pouvez vous connecter sur Thidom en allant sur :"
	msg_login_info2="Votre utilisateur par défault est: "
	msg_login_info3="Votre mot de passe par défault est: "
	msg_install_sql="*                Installation de la base de données    *"
	msg_id_notify="Saisissez votre identifiant de notification (si vous n'en avez pas, ne rien renseigner)"
	End="L'installation de ThiDom est terminé, il est recommandé de redémarrer: sudo reboot"
	restart="Votre machine va maintenant redémarrer"
	restart1="Votre machine va redémarrer dans 1 seconde "
	restart2="Votre machine va redémarrer dans 2 secondes "
	restart3="Votre machine va redémarrer dans 3 secondes "
	restart4="Votre machine va redémarrer dans 4 secondes "
	restart5="Votre machine va redémarrer dans 5 secondes "
}

install_upgrade()
{
	echo "${VERT}"
	echo "********************************************************"
	echo "Demarrage des mises à jour"
	echo "********************************************************"
	echo "${NORMAL}"

	sudo apt-get update -y
	sudo apt-get upgrade -y
	sudo apt-get dist-upgrade -y	
}

install_dependance() {
	echo "${VERT}"
	echo "********************************************************"
	echo "${msg_install_deps}"
	echo "********************************************************"
	echo "${NORMAL}"

	sudo apt-get install resolvconf -y
	sudo apt-get install curl -y
	sudo apt-get install apache2 -y
	sudo apt-get install apache2-utils -y
	sudo apt-get install libexpat1 -y
	sudo apt-get install ssl-cert -y
	sudo apt-get install mysql-server -y
	sudo apt-get install python-mysqldb -y
	sudo apt-get install python-serial -y
	sudo apt-get install fail2ban -y
	sudo apt-get install htop -y
	sudo apt-get install python-jinja2 -y
	sudo apt-get install python-pip -y
	sudo apt-get install python-certbot-apache -y
	sudo apt-get install python-opencv -y
	sudo pip install -U pip 
	sudo pip install tweepy
	sudo pip install httplib2
	sudo pip install imutils
	sudo pip install pyudev
	sudo pip install --upgrade google-api-python-client
	sudo pip install requests
	sudo pip install unidecode
	install_php
	sudo apt-get install ca-certificates -y
	sudo apt-get install ntpdate -y
	sudo apt-get install mailutils mpack -y
	sudo apt-get install ssmtp -y

	#pecl install oauth
	#if [ $? -eq 0 ] ; then
		#for i in fpm cli ; do
			#PHP_OAUTH="`cat /etc/php5/${i}/php.ini | grep -e 'oauth.so'`"
			#if [ -z "${PHP_OAUTH}" ] ; then
				#echo "extension=oauth.so" >> /etc/php5/${i}/php.ini
			#fi
		#done
	#fi
		
	sudo apt-get autoremove -y 
	sudo apt-get autoclean -y 
	#sudo apt-get update -y 
	#sudo apt-get upgrade -y
	#sudo apt-get dist-upgrade -y
}

install_php() {
	sudo apt-get install php7.0 -y 
	sudo apt-get install php7.0-curl -y 
	sudo apt-get install php7.0-gd -y
	sudo apt-get install php7.0-imap -y
	sudo apt-get install php7.0-json -y
	sudo apt-get install php7.0-mcrypt -y
	sudo apt-get install php7.0-mysql -y
	sudo apt-get install php7.0-xml -y
	sudo apt-get install php7.0-opcache -y
	sudo apt-get install php7.0-soap -y
	sudo apt-get install php7.0-xmlrpc -y
	sudo apt-get install libapache2-mod-php7.0 -y
	sudo apt-get install php7.0-common -y
	sudo apt-get install php7.0-dev -y
	sudo apt-get install php7.0-zip -y
	sudo apt-get install php7.0-ssh2 -y
	sudo apt-get install php7.0-calendar -y
	sudo apt-get install php7.0-intl -y
	if [ $? -ne 0 ]; then
		sudo apt-get install libapache2-mod-php5 -y
		sudo apt-get install php5 -y
		sudo apt-get install php5-common -y
		sudo apt-get install php5-curl -y
		sudo apt-get install php5-dev -y
		sudo apt-get install php5-gd -y
		sudo apt-get install php5-json -y
		sudo apt-get install php5-memcached -y
		sudo apt-get install php5-mysql -y
		sudo apt-get install php5-cli -y
		sudo apt-get install php5-ssh2 -y		

		echo "${VERT}********************************************************"
		echo "${msg_optimize_webserver_cache_opcache}"
		echo "********************************************************${NORMAL}"

		sudo pecl channel-update pecl.php.net 
		yes '' | pecl install -fs zendopcache-7.0.3
		#for i in fpm cli ; do
		for file in $(find /etc/ -iname php.ini -type f); do
			echo "zend_extension=opcache.so" >> ${file}
			echo "opcache.memory_consumption=256"  >> ${file}
			echo "opcache.interned_strings_buffer=8"  >> ${file}
			echo "opcache.max_accelerated_files=4000"  >> ${file}
			echo "opcache.revalidate_freq=1"  >> ${file}
			echo "opcache.fast_shutdown=1"  >> ${file}
			echo "opcache.enable_cli=1"  >> ${file}
			echo "opcache.enable=1"  >> ${file}
		done
		#done
	else

		echo "${VERT}********************************************************"
		echo "${msg_optimize_webserver_cache_opcache}"
		echo "********************************************************${NORMAL}"

		for file in $(find /etc/ -iname php.ini -type f); do
			echo "zend_extension=opcache.so" >> ${file}
			echo "opcache.memory_consumption=256"  >> ${file}
			echo "opcache.interned_strings_buffer=8"  >> ${file}
			echo "opcache.max_accelerated_files=4000"  >> ${file}
			echo "opcache.revalidate_freq=1"  >> ${file}
			echo "opcache.fast_shutdown=1"  >> ${file}
			echo "opcache.enable_cli=1"  >> ${file}
			echo "opcache.enable=1"  >> ${file}
			echo "opcache.file_cache= .opcache" >>  ${file}
		done
	fi	
	sudo apt-get install php-curl -y
	sudo apt-get install php-pear build-essential -y
}

init_msg

echo "${VERT}****************************************************************"
echo "${msg_installer_welcome}"
echo "****************************************************************${NORMAL}"

# Check for root priviledges

if [ $(id -u) != 0 ] ; then
	echo "Super-user (root) privileges are required to install Thidom"
	echo "Please run 'sudo $0' or log in as root, and rerun $0"
	exit 1
fi

echo "${msg_question_install_thidom}"
echo "${msg_warning_install_thidom}"
webserver_home="/var/www"
[ -d "${webserver_home}/ThiDom/" ]  && echo "${msg_warning_overwrite_thidom}"
while true ; do
	echo -n "${msg_yesno}"
	read ANSWER < /dev/tty
	case $ANSWER in
		${msg_yes})
			break
			;;
		${msg_no})
			echo "${msg_cancel_install}"
			exit 1
			;;
	esac
echo "${msg_answer_yesno}"
done

install_upgrade
install_dependance

echo  "${VERT}**********************************************************************"
echo "${msg_passwd_mysql}"
echo "****************************************************************${NORMAL}"
while true ; do
	read MySQL_root < /dev/tty
	echo "${msg_confirm_passwd_mysql} ${MySQL_root}"
	while true ; do
		echo -n "${msg_yesno}"
		read ANSWER < /dev/tty
		case $ANSWER in
			${msg_yes})				
				sudo mysqladmin -uroot password ${MySQL_root}
				break
				;;
			${msg_no})
				echo "${msg_passwd_mysql}"
				break
				;;
		esac
echo "${msg_answer_yesno}"
done    
if [ "${ANSWER}" = "${msg_yes}" ] ; then
        # Test access immediately
        # to ensure that the provided password is valide
        echo "show databases;" | sudo mysql -uroot -p"${MySQL_root}"
        if [ $? -eq 0 ] ; then		
			echo "DROP USER 'root'@'localhost'; CREATE USER 'root'@'localhost' IDENTIFIED BY '${MySQL_root}'; GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;" | sudo mysql -uroot -p"${MySQL_root}"
            # good password
            break
        else
        	echo "${msg_bad_passwd_mysql}"
        	echo "${msg_passwd_mysql}"
        	continue
        fi
    fi
done

echo "${VERT}********************************************************"
echo "${msg_setup_dirs_and_privs}"
echo "********************************************************${NORMAL}"

sudo mkdir -p "${webserver_home}"
cd "${webserver_home}"
sudo chown www-data:www-data -R "${webserver_home}"
sudo usermod -a -G dialout www-data

echo "${VERT}********************************************************"
echo "${msg_copy_thidom_files}"
echo "********************************************************${NORMAL}"

#mkdir /tmp/ThiDom
#cd /tmp/ThiDom

#wget --no-check-certificate https://github.com/tguillaume02/ThiDom/archive/master.zip

#if [  $? -ne 0 ] ; then    
	#wget --no-check-certificate https://github.com/tguillaume02/ThiDom/archive/master.zip
    #if [  $? -ne 0 ] ; then
        #echo "${msg_unable_to_download_file}"
        #exit 0
    #fi
#fi

#unzip master.zip -d ThiDom
#cd /tmp/ThiDom

#cd "${webserver_home}/ThiDom"

#if [ -d "Thidom" ] ; then
    #rm -rf Thidom
#fi

#mkdir /home/pi/

#sudo mkdir ${webserver_home}/ThiDom

sudo cp -Rf /tmp/ThiDom/www/* "${webserver_home}"

sudo mkdir /home/ThiDom
sudo mkdir /home/ThiDom/Script\ crontab/
sudo cp -Rf /tmp/ThiDom/Script\ crontab/* /home/ThiDom/Script\ crontab/
sudo chmod +x /home/ThiDom/Script\ crontab/*

sudo mkdir /home/ThiDom/Script_domotique/
sudo cp -Rf /tmp/ThiDom/Script_domotique/* /home/ThiDom/Script_domotique/
sudo chmod +x /home/ThiDom/Script_domotique/*

#mkdir "${webserver_home}"/thidom/tmp
chmod 775 -R "${webserver_home}"
chown -R www-data:www-data "${webserver_home}"

echo "${VERT}********************************************************"
echo "${msg_config_db}"
echo "********************************************************${NORMAL}"

bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'thidom'@'localhost';" | sudo mysql -uroot -p"${MySQL_root}"
echo "CREATE USER 'thidom'@'localhost' IDENTIFIED BY '${bdd_password}';" | sudo mysql -uroot -p"${MySQL_root}"
echo "DROP DATABASE IF EXISTS thidom;" | sudo mysql -uroot -p"${MySQL_root}"
echo "CREATE DATABASE thidom;" | sudo mysql -uroot -p"${MySQL_root}"
echo "GRANT ALL PRIVILEGES ON thidom.* TO 'thidom'@'localhost';" | sudo mysql -uroot -p"${MySQL_root}"
echo "FLUSH PRIVILEGES;" | sudo mysql -uroot -p"${MySQL_root}"


echo "${VERT}********************************************************"
echo "${msg_install_thidom}"
echo "********************************************************${NORMAL}"

cd "${webserver_home}/ThiDom/Core/class/"

sed -i 's/{{usersql}}/thidom/' db.class.php
sed -i 's/{{pwdsql}}/'${bdd_password}'/' db.class.php 
sed -i 's/{{bddsql}}/thidom/' db.class.php

# chown www-data:www-data connect.php 

sed -i 's/{{pwdsql}}/'${bdd_password}'/' /home/ThiDom/Script_domotique/msql.py
sed -i 's/{{usersql}}/thidom/' /home/ThiDom/Script_domotique/msql.py
sed -i 's/{{bddsql}}/thidom/' /home/ThiDom/Script_domotique/msql.py

echo ""
echo "${VERT}"
echo "${msg_id_notify}"
read idnotify < /dev/tty

sed -i 's/{{idnotify}}/'${idnotify}'/' /home/ThiDom/Script_domotique/msql.py

echo "${VERT}********************************************************"
echo "${msg_setup_apache}"
echo "********************************************************${NORMAL}"

sudo cp /tmp/ThiDom/etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/000-default.conf
sudo cp /tmp/ThiDom/etc/apache2/sites-available/default-ssl.conf  /etc/apache2/sites-available/default-ssl.conf 
sudo sed -i '/<Directory \/var\/www\/>/a \\tOptions +MultiViews' /etc/apache2/apache2.conf
sudo cp /tmp/ThiDom/etc/apache2/ports.conf  /etc/apache2/ports.conf
sudo mkdir /etc/apache2/ssl
sudo openssl req -x509 -nodes -days 3095 -newkey rsa:2048 -out /etc/apache2/ssl/server.crt -keyout /etc/apache2/ssl/server.key && sudo openssl genrsa -out client.key 2048 

#sudo openssl req  -new -key client.key -out client.req && sudo openssl x509 -req -in client.req -CA ca.cer -CAkey ca.key -set_serial 101  -extensions client -days 3650 -outform PEM -out client.cer && sudo openssl pkcs12 -export -inkey client.key -in client.cer -out client.p12 

sudo a2enmod ssl
sudo a2ensite default-ssl
sudo a2enmod rewrite
sudo systemctl restart apache2
sudo systemctl reload apache2
sudo systemctl restart apache2

sudo mkdir /etc/fw
sudo cp /tmp/ThiDom/etc/fw/* /etc/fw/
sudo cp /tmp/ThiDom/etc/init.d/* /etc/init.d/
sudo cp /tmp/ThiDom/etc/rc.local /etc/
sudo cp /tmp/ThiDom/etc/fail2ban/jail.local /etc/fail2ban/
#cp /tmp/ThiDom/etc/ssh/sshd_config /etc/ssh
sudo crontab -u $USER /tmp/ThiDom/crontab.txt


echo "${VERT}********************************************************"
echo "${msg_install_sql}"
echo "********************************************************${NORMAL}"

sudo mysql -uroot -p"${MySQL_root}" thidom < /tmp/ThiDom/Thidom.sql

sudo cp /etc/resolvconf/resolv.conf.d/original /etc/resolvconf/resolv.conf.d/base

rm -rf /tmp/ThiDom

#sudo pip install MySQL-python
echo "${VERT}********************************************************"
echo "${msg_install_complete}"
echo "********************************************************${NORMAL}"

IP=$(ifconfig eth0 | grep 'inet adr:' | cut -d: -f2 | awk '{print $1}')
if  [ -z $IP ]; then
	IP=$(ifconfig wlan0 | grep 'inet adr:' | cut -d: -f2 | awk '{print $1}')
fi

if  [ -z $IP ]; then
	IP="localhost"
fi
echo "${VERT}"
HOST=$(hostname -f)
echo "${msg_login_info1}"
echo "\n\t\thttp://$IP/ThiDom ${msg_or} http://$HOST/ThiDom\n"
echo "${msg_login_info2} admin"
echo "${msg_login_info3} admin"
echo ""

############# DEFINE STATIC ID TO USB  ##############
	# sudo udevadm info --query=all --name=ttyUSB0
	# sudo lsusb -v | more
	# Notez ou copiez les lignes, (avancez par appui sur la touche espace) et trouvez
	#  - idVendor
	#  - idProduct
	#  - iSerial (éventuellement)
	# On configure  donc  le fichier /etc/udev/rules.d/99-usb-serial.rules -
	#sudo echo 'SUBSYSTEM=="tty", ATTRS{idVendor}=="2341", SYMLINK+="ttyUSB1"' >>  /etc/udev/rules.d/99-usb-serial.rules

	#sudo udevadm control --reload

	#sudo chmod 777 /dev/ttyUSB1


###### MYSQL #######
# mettre en commentaire dans /etc/mysql/my.cnf la ligne bind-address		= 127.0.0.1
sudo sed -e '/bind-address/ s/^#*/#/' -i /etc/mysql/my.cnf


##### ADD IPTABLES RULES ON START
update-rc.d DefaultFirewall defaults

#echo "${restart5}"
#sleep 1

#echo "${restart4}"
#sleep 1

#echo "${restart3}"
#sleep 1

#echo "${restart2}"
#sleep 1

#echo "${restart1}"
#sleep 1

#echo "${restart}"

#sudo reboot


#sudo PRUNE_MODULES=1 rpi-update

echo "${End}"

##############################################################################################
# Modifier le fichier /etc/apache2/ports.conf
                    #/etc/apache2/sites-available
	# APACHE MODIFIER CHEMIN FICHIER LOG EN /VAR/LOG/
	
#sudo service apache2 force-reload
#sudo /etc/init.d/apache2 reload
#sudo apt-get install samba -y
 # fichier /etc/samba/smb.conf à remplacer
 # creer utilisateur sudo smbpasswd -a pi
 
 
# sudo apt-get install kodi -y
# sudo apt-get install pyload -y


#######  SI ERREUR APT UPDATE #######
#Verifier que /etc/resolv.conf n'est pas vide
#sinon mettre
#domain home
#search home
#nameserver 192.168.1.1
#nameserver 192.168.1.1
	## ECRIRE AVEC BASH ###
	#echo  'domain home
	#search home
	#nameserver 192.168.1.1
	#nameserver 192.168.1.1' >> /etc/resolv.conf



######################## FICHIER MYSQL  ###############
	#sudo chown pi /etc/mysql
	#### A MODIFIER #####
		#/etc/mysql/my.cnf
	### A REMPLACER #####
		#/var/lib/mysql/

	#sudo chown -R mysql:mysql /var/lib/mysql
	#sudo chmod 755 /var/lib/mysql
	#sudo chmod 755 /var/lib/mysql/mysql
	#sudo chmod 660 /var/lib/mysql/*/*
	#sudo service mysql stop
	#sudo service mysql start
	
########### INSTALL NO IP ###################

#cd /tmp/ && sudo wget http://www.no-ip.com/client/linux/noip-duc-linux.tar.gz && sudo tar vzxf noip-duc-linux.tar.gz && cd noip-2.1.9-1 && sudo make && sudo make install



########### METTRE LES LOGS EN RAM #####################
	#Mettre dans /etc/fstab

	#tmpfs /tmp tmpfs defaults,noatime,nosuid,size=10m 0 0
	#tmpfs /var/tmp tmpfs defaults,noatime,nosuid,size=10m 0 0
	#tmpfs /var/log tmpfs defaults,noatime,nosuid,mode=0755,size=10m 0 0

	####### AJOUTER LE SCRIPT /etc/init.d/apache2-tmpfs #########
		 #sudo chmod 0755 /etc/init.d/apache2-tmpfs


	##### DEMARRER ET ARRETER AVANT QUE LE SERVICE APACHE DEMARRE OU S'ARRETE #######
		#sudo update-rc.d apache2-tmpfs defaults 90 10


########### INSTALLER DRIVER CLE WIFI SAGEM ########################

	#wget http://daemonizer.de/prism54/prism54-fw/fw-usb/2.13.1.0.lm86.arm --no-check-certificate 
	#wget http://daemonizer.de/prism54/prism54-fw/fw-usb/2.13.25.0.lm87.arm --no-check-certificate
	#sudo mv 2.13.1.0.lm86.arm /lib/firmware/isl3886usb
	#sudo mv 2.13.25.0.lm87.arm /lib/firmware/isl3887usb


###### A METTRE DANS /etc/network/interfaces ####

	#auto lo

	#iface lo inet loopback
	#iface eth0 inet dhcp

	#allow-hotplug wlan0
	#auto wlan0
	##iface wlan0 inet dhcp
	 ##       wpa-ssid "NOM DE LA BOX"
	  ##      wpa-psk CLE DE LA BOX

	#iface wlan0 inet static
	        #address 192.168.1.25
	        #netmask 255.255.255.0
	        #gateway 192.168.1.1
	        #wpa-ssid "NOM DE LA BOW "
	        #wpa-psk CLE DE LA BOX

	#iface default inet dhcp

####### DESACTIVER IPV6 ###################
	#sudo echo 1 > /proc/sys/net/ipv6/conf/all/disable_ipv6
	#sudo echo 0 > /proc/sys/net/ipv6/conf/all/autoconf
	#sudo echo 1 > /proc/sys/net/ipv6/conf/default/disable_ipv6
	#sudo echo 0 > /proc/sys/net/ipv6/conf/default/autoconf
	#sudo echo '1' > /proc/sys/net/ipv6/conf/lo/disable_ipv6   
	#sudo echo '1' > /proc/sys/net/ipv6/conf/all/disable_ipv6   
	#sudo echo '1' > /proc/sys/net/ipv6/conf/default/disable_ipv6

######## STATUS RC.LOCAL ##############
	#sudo systemctl status -l rc-local.service
	
	
############# INSTALL PYTHON 3.4 IF NOT AVAILABLE WITH apt-get INSTALL ##########################

	## Download latest Python sources
	#curl -O https://www.python.org/ftp/python/3.4.3/Python-3.4.3.tgz

	## Unpack sources archive
	#tar xf Python-3.4.3.tgz

	## Install packages needed for Python build process
	## (build-essential is maybe already preinstalled on Raspbian)
	#sudo apt-get install build-essential
	#sudo apt-get install python3-dev

	# Build Python
	#cd Python-3.4.3
	#./configure
	#make

	# Make independent python3.4 virtual environment in your home directory
	#mkdir ~/python
	#./python -m venv --copies ~/python/python3.4

	# Activate python3.4 virtual environment
	#source ~/python/python3.4/bin/activate

	# Upgrade setuptools and pip
	#pip install -U setuptools
	#pip install -U pip



######## RUN PYTHON 3.4 #################

	#run source ~/python/python3.4/bin/activate

########### STOP PYTHON 3.4 ##########

	#deactivate 

##########  ENVOYE DE MAIL LORS D'UNE CONNEXION SSH ############

# sudo nano /etc/bash/bash.bashrc
# echo 'Acces SSH en '`id | cut -d "(" -f2 | cut -d ")" -f1`' sur '`hostname`' le: ' `date` `who` | mail -s "NOTIFICATION - Connexion en "`id | cut -d '(' -f2 | cut -d ')' -f1`" via SSH depuis: `echo $SSH_CONNECTION | cut -d " " -f 1`" ICI_ADRESSE_MAIL


#########SSH CONFIGURATION ###########

	#ssh-keygen -t rsa -b 4096

	#La clé privée sera stockée dans ~/.ssh/id_rsa et la clé publique dans ~/.ssh/id_rsa.pub.

	#Renommer le rsa_id.pub en authorized_keys 
	#copier le rsa_id (la clé privée) sous Windows
	#Utiliser Puttygen pour importer la clé: votre clé RSA est valide, mais n’est pas encore une clé au format Putty.
	#Faites “Sauver la clé privée sous…” et enregistrez-là à l’emplacement de votre choix.