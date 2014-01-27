#!/bin/bash
rm -f /home/wwwroot/index/web/Controller/vpn.php /home/wwwroot/index/web/Model/vpns.php /home/wwwroot/index/web/View/vpn_index.php;
MysqlPass=`cat /home/wwwroot/index/web/Amysql/Config.php | awk '{ FS="\047Password\047] = \047"; RS="\047;" } { print $2}' | sed '/^$/d'`;
mysql -uroot -p${MysqlPass} -B -N -e "DROP TABLE amh.module_vpn";	
rm -f /root/amh/modules/LanYing-VPN-1.0/status;