<?php
//bbshijiessl module uninstall, for AMHScript
$ssl_dir = '/usr/local/nginx/conf/ssl/';
$vhost_dir = '/usr/local/nginx/conf/vhost/';
$vhost_stop_dir = '/usr/local/nginx/conf/vhost_stop/';
$ssl_tmpfile = "{$ssl_dir}tmp.conf";

//scan ssl dir, get all ssl hosts
if ($handle = opendir($ssl_dir)) {
	$ssl_list = array();
	while (false !== ($file = readdir($handle))) {
		if(strpos($file,'.pem') !== false){
			$ssl_list[]=substr($file,0,-4);
		}
	}
}
closedir($handle);

//restore all ssl hosts to normal(http)
foreach ($ssl_list as $key=>$val)
{
	$vhost_conf1 = "{$vhost_dir}{$val}.conf";
	$vhost_conf2 = "{$vhost_stop_dir}{$val}.conf";
	if(is_file($vhost_conf1) || is_file($vhost_conf2))
	{
		if(is_file($vhost_conf1))
		{
			$vhost_conf = $vhost_conf1;
			$vhost_status = '';
		}
		else
		{
			$vhost_conf = $vhost_conf2;
			$vhost_status = '_stop';
		}
		$ssl_conf = file_get_contents($vhost_conf);
		$ssl_conf = preg_replace('/\n#------SSL BEGIN(.*)#------SSL END/is', '', $ssl_conf);
		file_put_contents($ssl_tmpfile, $ssl_conf);
		$cmd = "amh module BBShijieSSL-1.1 admin restore,{$val},{$vhost_status};";
		//$cmd = trim_cmd($cmd);
		shell_exec($cmd);
	}
}
$cmd = 'amh nginx reload';
shell_exec($cmd);

/*
function trim_cmd($cmd)
	{
		$cmd = str_replace(array(';', '&', '|', '`'), ' ', trim($cmd));
		$cmd = str_replace(array('#'), array('\\\\#'), trim($cmd));
		$cmd = ereg_replace("[ ]{1,}", " ", $cmd);
		Return $cmd;
	}
*/
?>