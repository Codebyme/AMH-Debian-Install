<?php

class amapi extends AmysqlController
{
	public $indexs = null;
	public $amapis = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> amapis = $this ->  _model('amapis');
	}


	// 管理
	function IndexAction()
	{
		$this -> title = 'AMH - AMAPI';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$this -> status = 'error';

		// 更新密钥
		if (isset($_POST['uppass']))
		{
			if($this -> amapis -> amapi_detpass())
			{
				$this -> notice = '更新AMAPI密钥成功。';
				$this -> status = 'success';
			}
			else 
				$this -> notice = '更新AMAPI密钥失败。';
		}

		// 添加IP
		if (isset($_POST['addip']))
		{
			$ip = $_POST['ip'];
			if($this -> amapis -> amapi_addip($ip))
			{
				$this -> notice = 'AMAPI添加IP成功:' . $ip;
				$this -> status = 'success';
			}
			else
				$this -> notice = 'AMAPI添加IP失败:' . $ip;
		}

		// 删除IP
		if (isset($_GET['delip']))
		{
			$ip = $_GET['delip'];
			if($this -> amapis -> amapi_delip($ip))
			{
				$this -> notice = 'AMAPI删除IP成功:' . $ip;
				$this -> status = 'success';
			}
			else
				$this -> notice = 'AMAPI删除IP失败:' . $ip;
		}


		$this -> amapis -> amapi_makepass();
		$getpass = $this -> amapis -> amapi_getpass();
		$iplist = $this -> amapis -> amapi_iplist();


		$this -> indexs -> log_insert($this -> notice);
		$this -> getpass = $getpass;
		$this -> iplist = $iplist;
		$this -> _view('amapi');
	}
		

	// 调用
	function call()
	{
		$this -> AmysqlModelBase();
		$getpass = $this -> amapis -> amapi_getpass();
		$iplist = $this -> amapis -> amapi_iplist();

		$amapi_pass = (isset($_POST['amapi_pass']) && !empty($_POST['amapi_pass']) ) ? $_POST['amapi_pass'] : 'NULL';
		$amh_cmd = (isset($_POST['amh_cmd']) && !empty($_POST['amh_cmd']) ) ? $_POST['amh_cmd'] : 'NULL';
		$amh_cmd = trim(base64_decode($amh_cmd));


		// IP判断
		if (is_array($iplist) && count($iplist) > 0)
		{
			$error = '不允许调用IP:' . $_SERVER["REMOTE_ADDR"];
			foreach ($iplist as $key=>$val)
			{
				if($val['config_value'] == $_SERVER["REMOTE_ADDR"])
				{
					unset($error);
					break;
				}
			}
		}

		// 命令判断
		$split = array(';', '&', '|', '&&', '||');
		foreach ($split as $key=>$val)
		{
			$amh_cmd_arr = explode($val, $amh_cmd);
			foreach ($amh_cmd_arr as $k=>$v)
			{
				$v = trim($v);
				if (substr($v, 0, 3) != 'amh' && !empty($v) && substr($amh_cmd_arr[$k-1], -1, 1) != '\\')
				{
					$error = '只允许执行AMH命令，非法命令:' . $v;
					break;
				}
			}
		}

		// 密钥判断
		if(md5($amapi_pass) != $getpass['AMAPI_PASSA']['config_value'])
			$error = '密钥错误:' . $amapi_pass;

		if (isset($error))
		{
			$log = '[AMAPI]' . $error;
			echo $log;
		}
		else
		{
			echo shell_exec($amh_cmd);
			$log = 'AMAPI接口执行命令:' . $amh_cmd;
		}
		$this -> indexs -> log_insert($log);
	}


}

?>