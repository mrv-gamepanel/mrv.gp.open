# MRV for Game Hosting [Open Source project] 
## [Website, Order, Online Payment, Game Panel, Admin Panel]

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
install: ``sudo apt-get install screen``

more: ``https://www.hostinger.com/tutorials/how-to-install-and-use-linux-screen/``

## [download]

#### Download gamepanel from github using this command line:
``wget https://github.com/mrv-gamepanel/mrv.gp.open/archive/refs/heads/master.zip``


### [database config]
config file: ``/core/inc/config.php``


### [upload problem fix]
using this command line: ``sudo chmod -R 777 /var/www``


### [setup game dirvers]
``https://linuxgsm.com/lgsm/sampserver/``





## CentOS 7+

#### install web server]
	sudo yum install httpd
	sudo chmod -R 777 /var/www

#### [install php]
	sudo install php
	yum install https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
	yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
	yum install yum-utils
	yum-config-manager --enable remi-php73
	yum install php php-mcrypt php-cli php-gd php-curl php-mysql php-ldap php-zip php-fileinfo

#### [enable .htaccess]
	sudo nano /etc/httpd/conf/httpd.conf
	
		<Directory /var/www/html>
			AllowOverride All
		</Directory>
	
	sudo systemctl restart httpd
(https://tweenpath.net/enable-mod_rewrite-apache-centos-7/)


#### [php - ssh2 lib]
	yum install gcc php-devel libssh2 libssh2-devel php-pear make
	pecl install -f ssh2
	echo extension=ssh2.so > /etc/php.d/ssh2.ini
	service httpd restart
(https://www.svnlabs.com/blogs/install-ssh2-extension-for-php-7-on-centos-7/)


#### [db server]
	sudo yum install mariadb-server
	sudo systemctl start mariadb.service
	sudo mysql_secure_installation


#### [phpmyadmin]
	sudo yum install phpmyadmin




#### You developer? Please fork and if found bugs fixed or report it. Thanks! <3

## Project for you guys, thanks for your time. <3
