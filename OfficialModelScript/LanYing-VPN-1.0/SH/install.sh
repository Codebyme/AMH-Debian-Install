#!/bin/bash
#Disable SeLinux
if [ -s /etc/selinux/config ] && grep 'SELINUX=enforcing' /etc/selinux/config; then
sed -i 's/SELINUX=enforcing/SELINUX=disabled/g' /etc/selinux/config;
setenforce 0;
fi;
cd /root/amh/modules/LanYing-VPN-1.0;
\cp ./Controller/*  /home/wwwroot/index/web/Controller/;
\cp ./View/*  /home/wwwroot/index/web/View/;
\cp ./Model/*  /home/wwwroot/index/web/Model/;
MysqlPass=`cat /home/wwwroot/index/web/Amysql/Config.php | awk '{ FS="\047Password\047] = \047"; RS="\047;" } { print $2}' | sed '/^$/d'`;
mysql -uroot -p$MysqlPass < ./vpn.sql  --default-character-set=utf8;
touch ./status;