#!/bin/bash
SysBit='i386';
[ `getconf WORD_BIT` == '32' ] && [ `getconf LONG_BIT` == '64' ] && SysBit='x86_64';
yum remove -y pptpd ppp;
iptables --flush POSTROUTING --table nat;
iptables --flush FORWARD;
rm -rf /etc/pptpd.conf;
rm -rf /etc/ppp;
yum -y install make libpcap iptables gcc-c++ logrotate tar cpio perl pam tcp_wrappers;

ppp_name="ppp-2.4.4-14.1.rhel5";
pptpd_name="pptpd-1.3.4-1.rhel5.1";
yum info libpcap | grep 'Version' | awk '{print $3}' | grep '^1\.' && ppp_name="ppp-2.4.5-23.0.rhel6" && pptpd_name="pptpd-1.3.4-2.el6";

cd /root/amh/modules/LanYing-VPN-1.0/RPM;
[ ! -f 'dkms-2.0.17.5-1.noarch.rpm' ] && wget http://code.amysql.com/files/dkms-2.0.17.5-1.noarch.rpm;
[ ! -f 'kernel_ppp_mppe-1.0.2-3dkms.noarch.rpm' ] && wget http://code.amysql.com/files/kernel_ppp_mppe-1.0.2-3dkms.noarch.rpm;
[ ! -f "${ppp_name}.${SysBit}.rpm" ] && wget http://code.amysql.com/files/${ppp_name}.${SysBit}.rpm;
[ ! -f "${pptpd_name}.${SysBit}.rpm" ] && wget http://code.amysql.com/files/${pptpd_name}.${SysBit}.rpm;

rpm -ivh dkms-2.0.17.5-1.noarch.rpm;
rpm -ivh kernel_ppp_mppe-1.0.2-3dkms.noarch.rpm;
rpm -Uvh ${ppp_name}.${SysBit}.rpm;
rpm -ivh ${pptpd_name}.${SysBit}.rpm;
rpm -qa kernel_ppp_mppe;
rm -f /dev/ppp;
mknod /dev/ppp c 108 0;
echo 1 > /proc/sys/net/ipv4/ip_forward ;
echo "mknod /dev/ppp c 108 0" >> /etc/rc.local;
echo "echo 1 > /proc/sys/net/ipv4/ip_forward" >> /etc/rc.local;
echo "localip 172.16.36.1" >> /etc/pptpd.conf;
echo "remoteip 172.16.36.2-254" >> /etc/pptpd.conf;
echo "ms-dns 8.8.8.8" >> /etc/ppp/options.pptpd;
echo "ms-dns 8.8.4.4" >> /etc/ppp/options.pptpd;
echo "vpntest * vpntest *" > /etc/ppp/chap-secrets;
iptables -t nat -A POSTROUTING -s 172.16.36.0/24 -j SNAT --to-source `ifconfig  | grep 'inet addr:'| egrep -v 'addr:127\.|addr:10\.|addr:172\.|addr:192\.' | cut -d: -f2 | awk 'NR==1 { print $1}'`;
iptables -A FORWARD -p tcp --syn -s 172.16.36.0/24 -j TCPMSS --set-mss 1356;
iptables -I INPUT -p tcp --dport 1723 -j ACCEPT;
service iptables save;
chkconfig iptables on;
chkconfig pptpd on;
service iptables start;
service pptpd start;
/sbin/iptables-save > /etc/amh-iptables;
