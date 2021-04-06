# Setup MySQL server #

### Ubuntu ###
``
sudo apt-get update
sudo apt-get install -y software-properties-common apt-transport-https ca-certificates
sudo apt-key adv --fetch-keys 'http://mariadb.org/mariadb_release_signing_key.asc'
``
### Ubuntu 18.04 ###
``
sudo add-apt-repository 'deb [arch=amd64,arm64,ppc64el] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu bionic main'
``
### Ubuntu 16.04 ###
``
sudo add-apt-repository 'deb [arch=amd64,arm64,i386,ppc64el] http://sfo1.mirrors.digitalocean.com/mariadb/repo/10.4/ubuntu xenial main'
``
### Ubuntu ###
``
sudo apt-get update
sudo apt-get install -y mariadb-server mariadb-client
``
### Use mysql_secure_installation to secure the installation of the MariaDB server. ###
``
mysql_secure_installation
``
### Setup mysql_secure_installation MariaDB ###
``
Change the root password? [Y/n] n
Remove anonymous users? [Y/n] Y
Disallow root login remotely? [Y/n] n
Remove test database and access to it? [Y/n] Y
Reload privilege tables now? [Y/n] Y
Thanks for using MariaDB!
``

### How to Install Firewalld on Ubuntu 18.04 / Ubuntu 16.04 ###
``
sudo apt-get install firewalld
sudo systemctl enable firewalld
sudo systemctl start firewalld
sudo firewall-cmd --state
``

### Allow MySQL Port For All ###
``
firewall-cmd --zone=public --add-port=3306/tcp
firewall-cmd --permanent --zone=public --add-port=3306/tcp
``

### Create user (perm for all db) ###
``
CREATE USER 'mrvGP'@'%' IDENTIFIED BY 'breakPW';
GRANT ALL PRIVILEGES ON * . * TO 'mrvGP'@'%';
FLUSH PRIVILEGES;
``

### Show all users ###
``
SELECT User, Host FROM mysql.user;
``

### Change ROOT Host (Allow connect from all host) ###
``
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
``

Privilegija GRANT OPTION omogućuje vam davanje drugih korisnika ili uklanjanje privilegija koje sami posjedujete od drugih korisnika.
``
GRANT ALL PRIVILEGES ON $dbName.* TO '$dbUser'@'%' WITH GRANT OPTION;
``

### Create new MySQL DataBase & User ###

### Create DataBase
``
CREATE DATABASE breakDB;
``
### Create User
``
CREATE USER 'breakUser'@'%' IDENTIFIED BY 'breakPW';
GRANT ALL PRIVILEGES ON breakDB.* TO 'breakUser'@'%';
FLUSH PRIVILEGES;
``

### Delete MySQL User & DataBase ###
``
REVOKE ALL PRIVILEGES, GRANT OPTION FROM 'breakUser'@'%';
``
### Remove User
``
DROP USER 'breakUser'@'%';
``
OR
``
DROP USER breakUser;
DROP USER IF EXISTS breakUser;
``
### Remove DataBase
``
DROP DATABASE breakDB;
``
