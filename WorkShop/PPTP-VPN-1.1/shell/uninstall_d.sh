#!/bin/bash
rm -rf /etc/pptpd.conf
rm -rf /etc/ppp
apt-get -y remove ppp pptpd	
iptables -t nat -D POSTROUTING -s 192.168.99.0/24 -j SNAT --to-source `ifconfig  | grep 'inet addr:'| egrep -v 'addr:127\.|addr:10\.|addr:172\.|addr:192\.' | cut -d: -f2 | awk 'NR==1 { print $1}'`
sed -i /'net.ipv4.ip_forward = 1'/d /etc/sysctl.conf
sysctl -p
/sbin/iptables-save > /etc/amh-iptables;
