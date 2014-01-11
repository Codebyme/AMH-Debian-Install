<?php

class amapis extends AmysqlModel
{
	
	// 生成密钥
	function amapi_makepass()
	{
		$sql = "SELECT * FROM amh_config WHERE config_name = 'AMAPI_PASSA'";
		$sum = $this -> _sum($sql);
		if ($sum != 0) Return false;

		$amapi_passb = str_shuffle(md5(rand(1000,9999)));	// 密钥
		$amapi_passa = md5($amapi_passb);					// 私钥

		$this -> _insert('amh_config', array('config_name' => 'AMAPI_PASSB', 'config_value' => $amapi_passb));
		$this -> _insert('amh_config', array('config_name' => 'AMAPI_PASSA', 'config_value' => $amapi_passa));
	}

	// 取得密钥
	function amapi_getpass()
	{
		$sql = "SELECT config_name, config_value, config_time FROM amh_config WHERE config_name IN ('AMAPI_PASSA', 'AMAPI_PASSB') ";
		$tmp = $this -> _all($sql);
		foreach ($tmp as $key=>$val)
			$data[$val['config_name']] = $val;
		Return $data;
	}

	// 删除密钥
	function amapi_detpass()
	{
		$sql = "DELETE FROM amh_config WHERE config_name IN ('AMAPI_PASSA', 'AMAPI_PASSB')";
		Return $this -> _query($sql);
	}


	// 取得IP列表
	function amapi_iplist()
	{
		$sql = "SELECT config_name, config_value, config_time FROM amh_config WHERE config_name ='AMAPI_IP' ";
		Return $this -> _all($sql);
	}

	// 添加IP
	function amapi_addip($ip)
	{
		Return $this -> _insert('amh_config', array('config_name' => 'AMAPI_IP', 'config_value' => $ip));
	}

	// 删除IP
	function amapi_delip($ip)
	{
		$sql = "DELETE FROM amh_config WHERE config_name ='AMAPI_IP' AND config_value = '$ip'";
		Return $this -> _query($sql);
	}
	
}

?>