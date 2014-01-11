#!/bin/bash
iptables --flush POSTROUTING --table nat;
iptables --flush FORWARD;
rm -rf /etc/pptpd.conf;
rm -rf /etc/ppp;

ppp_name="ppp-2.4.4-14.1.rhel5";
pptpd_name="pptpd-1.3.4-1.rhel5.1";
yum info libpcap | grep 'Version' | awk '{print $3}' | grep '^1\.' && ppp_name="ppp-2.4.5-23.0.rhel6" && pptpd_name="pptpd-1.3.4-2.el6";

cd /root/amh/modules/LanYing-VPN-1.0/RPM;
rpm -e kernel_ppp_mppe-1.0.2-3dkms;
rpm -e dkms-2.0.17.5-1;
rpm -e $pptpd_name;
rpm -e $ppp_name;
yum remove -y pptpd ppp;

rm -rf /proc/sys/net/ipv4/ip_forward ;
sed  -i '/mknod \/dev\/ppp c 108 0/d' /etc/rc.local;
sed  -i '/echo 1 > \/proc\/sys\/net\/ipv4\/ip_forward/d' /etc/rc.local;
iptables -t nat -D POSTROUTING -s 172.16.36.0/24 -j SNAT --to-source `ifconfig  | grep 'inet addr:'| egrep -v 'addr:127\.|addr:10\.|addr:172\.|addr:192\.' | cut -d: -f2 | awk 'NR==1 { print $1}'`;
iptables -D FORWARD -p tcp --syn -s 172.16.36.0/24 -j TCPMSS --set-mss 1356;
iptables -D INPUT -p tcp --dport 1723 -j ACCEPT;
service iptables save;
chkconfig iptables on;
service iptables start;
/sbin/iptables-save > /etc/amh-iptables;