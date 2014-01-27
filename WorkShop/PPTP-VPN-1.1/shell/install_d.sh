#!/bin/bash
apt-get update
rm -rf /etc/pptpd.conf
rm -rf /etc/ppp
apt-get -y remove ppp pptpd	
apt-get -y install ppp pptpd iptables
echo ms-dns 208.67.222.222 >> /etc/ppp/pptpd-options
echo ms-dns 208.67.220.220 >> /etc/ppp/pptpd-options
echo localip 192.168.99.1 >> /etc/pptpd.conf
echo remoteip 192.168.99.9-99 >> /etc/pptpd.conf
iptables -t nat -A POSTROUTING -s 192.168.99.0/24 -j SNAT --to-source `ifconfig  | grep 'inet addr:'| egrep -v 'addr:127\.|addr:10\.|addr:172\.|addr:192\.' | cut -d: -f2 | awk 'NR==1 { print $1}'`
echo net.ipv4.ip_forward = 1 >> /etc/sysctl.conf
sysctl -p
echo vpn \* amysql_${RANDOM} \* >> /etc/ppp/chap-secrets
/etc/init.d/pptpd restart
/sbin/iptables-save > /etc/amh-iptables;
