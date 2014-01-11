use amh;
CREATE TABLE IF NOT EXISTS `module_vpn` (
  `vpn_id` int(10) NOT NULL AUTO_INCREMENT,
  `vpn_user` varchar(45) NOT NULL,
  `vpn_pwd` varchar(45) NOT NULL,
  PRIMARY KEY (`vpn_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `module_vpn` (`vpn_id`, `vpn_user`, `vpn_pwd`) VALUES (1, 'vpntest', 'vpntest');