# MRV for Game Hosting # Site, Order, Online Payment, Game Panel, Admin Panel [Open Source]

### About us
``uskoro bato, no sikiriki``

#### Frontend: `Radisic` & `Gari`;
#### Backend: `ME`;


## [sys config]
#### apache2
	Web server (apache2): https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04
	Install php-ssh2 extension:
		sudo apt-get update -y
		sudo apt-get install -y php-ssh2

	Enable .htaccess:
		sudo a2enmod rewrite
		sudo systemctl restart apache2
		sudo nano /etc/apache2/apache2.conf
		scroll to down: <Directory /var/www/> &
		replace this line: AllowOverride None to AllowOverride All

#### mariadb & phpmyadmin
	Database (MariaDB) & phpmyadmin: https://www.digitalocean.com/community/tutorials/how-to-install-and-secure-phpmyadmin-on-ubuntu-20-04
	For fix problem "Not found" with phpmyadmin: https://stackoverflow.com/a/46151947
	For problem with root login: https://stackoverflow.com/a/52382478

#### proftpd
	install: sudo apt-get install -y proftpd
	start: 	systemctl start proftpd
	enable: systemctl enable proftpd
	status: systemctl status proftpd

	chnage:
		from:	#DefaultRoot ~
		to: 	DefaultRoot ~
		( remove "#" )

	more settings: https://mxforge.com/linux-windows/install-proftpd-with-tls-on-ubuntu-20-04-lts/

#### zip, unzip
``sudo apt-get install -y zip, unzip``

#### screen
install: ``sudo apt install screen``

more: ``https://www.hostinger.com/tutorials/how-to-install-and-use-linux-screen/``

## [download]

#### Download gamepanel from github using this command line:
``wget https://github.com/mskoko/MRV-Hosting.com/archive/refs/heads/master.zip``


### [database config]
config file: ``/core/inc/config.php``


### [upload problem fix]
using this command line: ``sudo chmod -R 777 /var/www``


### [setup game dirvers]
``https://linuxgsm.com/lgsm/sampserver/``

#### You developer? Please fork and if found bugs fixed or report it. Thanks! <3

## Project for you guys, thanks for your time. <3
