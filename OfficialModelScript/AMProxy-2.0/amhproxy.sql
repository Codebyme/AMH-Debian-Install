use amh;
CREATE TABLE IF NOT EXISTS `module_amproxy` (
  `amproxy_id` int(10) NOT NULL AUTO_INCREMENT,
  `amproxy_file` varchar(45) NOT NULL,
  `amproxy_key` varchar(300) NOT NULL,
  `amproxy_http_s` varchar(5) NOT NULL,
  `amproxy_type` varchar(30) NOT NULL,
  `amproxy_size` varchar(10) NOT NULL,
  `amproxy_time` datetime NOT NULL,
  PRIMARY KEY (`amproxy_id`),
  UNIQUE KEY `file` (`amproxy_file`),
  KEY `key` (`amproxy_key`),
  KEY `http_s` (`amproxy_http_s`),
  KEY `type` (`amproxy_type`),
  KEY `size` (`amproxy_size`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;