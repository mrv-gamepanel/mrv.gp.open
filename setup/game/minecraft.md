# Setup Minecraft server #


### Ubuntu 64-bit (Drivers) ###
``
sudo dpkg --add-architecture i386; sudo apt update; sudo apt install mailutils postfix curl wget file tar bzip2 gzip unzip bsdmainutils python util-linux ca-certificates binutils bc jq tmux netcat default-jre
``

### Povuci MC Files (minecraft_server.1.16.3.jar) ###
``
wget https://launcher.mojang.com/v1/objects/f02f4473dbf152c23d7d484952121db0b36698cb/server.jar
``

### First time run app for creating gamefiles (fix) ###
``
sudo bash -c "echo eula=true > /home/srv_1_2_519uj/eula.txt"
``

### Run MC server command ###
``
su -lc 'screen -dmSL srv_1_2_519uj java -Xms512M -Xmx1024M -jar server.jar nogui' srv_1_2_519uj
chown -R srv_1_2_519uj /home/srv_1_2_519uj
``

### Kill MC server command ###
``
su -lc 'killall screen' srv_1_2_519uj
``
or
``
ps -A | grep java
kill -9 TASK_ID
``