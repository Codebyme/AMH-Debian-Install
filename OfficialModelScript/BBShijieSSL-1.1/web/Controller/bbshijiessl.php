<?php
class bbshijiessl extends AmysqlController
{
	public $indexs = null;
	public $hosts = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> hosts = $this ->  _model('hosts');
	}


	function IndexAction()
	{
		$this -> title = 'AMH - BBShijieSSL';	
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$ssl_dir = '/usr/local/nginx/conf/ssl/';
		$vhost_dir = '/usr/local/nginx/conf/vhost/';
		$vhost_stop_dir = '/usr/local/nginx/conf/vhost_stop/';
		$ssl_tmpfile = "{$ssl_dir}tmp.conf";

		// 查看
		if (isset($_GET['name']))
		{
			$name = $_GET['name'];
			$file1 = "{$ssl_dir}{$name}.pem";
			$file2 = "{$ssl_dir}{$name}.crt";
			if (is_file($file1) && is_file($file2))
			{
				$_POST['ssl_key'] = file_get_contents($file1);
				$_POST['ssl_crt'] = file_get_contents($file2);
			}
		}

		// 删除
		if (isset($_GET['del']))
		{
			$del = $_GET['del'];
			if(strpos($del, '..') !== false || strpos($del, '/') !== false )
			{
				$this -> status = 'error';
				$this -> notice = "{$del}: 非法请求，删除SSL主机失败。";
			}
			else
			{
				//删除证书，还原主机conf(区分已启用和已停止的)
				$file1 = "{$ssl_dir}{$del}.pem";
				$file2 = "{$ssl_dir}{$del}.crt";
				$vhost_conf1 = "{$vhost_dir}{$del}.conf";
				$vhost_conf2 = "{$vhost_stop_dir}{$del}.conf";
				if((is_file($vhost_conf1) || is_file($vhost_conf2)) && is_file($file1) && is_file($file2))
				{
					@unlink($file1);
					@unlink($file2);

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
					$cmd = "amh module BBShijieSSL-1.1 admin restore,{$del},{$vhost_status};";
					$cmd = Functions::trim_cmd($cmd);
					shell_exec($cmd);

					if (!is_file($file1) && !is_file($file2))
					{
						$_POST = null;
						$_POST['reload_nginx'] = true;
						$this -> status = 'success';
						$this -> notice = "{$del}: 删除成功，SSL主机删除成功。";
					}
					else
					{
					    $this -> status = 'error';
						$this -> notice = "{$del}: 删除出错，SSL主机删除失败。";
					}
				}
				else
				{
					$this -> status = 'error';
					$this -> notice = "{$del}: 删除失败，SSL主机不存在。";
				}
			}
		}

		// 新增/保存SSL ***********
		if (isset($_POST['add']) || isset($_POST['save']))
		{
			if (!empty($_POST['ssl_vhost_new']))
			{
				$ssl_vhost_new= $_POST['ssl_vhost_new'];

				if(strpos($ssl_vhost_new, '..') !== false || strpos($ssl_vhost_new, '/') !== false)
				{
					$this -> status = 'error';
					$this -> notice = "标识域名{$ssl_vhost_new}存在非法字符，新增/保存SSL主机失败。";
				}
				else
				{
					$file1 = "{$ssl_dir}{$ssl_vhost_new}.pem";
					$file2 = "{$ssl_dir}{$ssl_vhost_new}.crt";
					$vhost_conf = "{$vhost_dir}{$ssl_vhost_new}.conf";
					$ssl_key = stripslashes($_POST['ssl_key']);
					$ssl_crt = stripslashes($_POST['ssl_crt']);

					if(isset($_POST['add']))
					{
						if (is_file($file1) || is_file($file2) || !is_file($vhost_conf))
						{
							$this -> status = 'error';
							$this -> notice = "{$ssl_vhost_new}: SSL主机已存在或标识域名不存在，新增SSL主机失败。";
						}
						else
						{
							//保存证书，修改主机conf
							file_put_contents($file1, $ssl_key);
							file_put_contents($file2, $ssl_crt);

							$ssl_conf = file_get_contents($vhost_conf);
							$ssl_conf = preg_replace('/error_log end/i', "error_log end\n#------SSL BEGIN\nlisten 443;\nssl on;\nssl_certificate {$file2};\nssl_certificate_key {$file1};\n#------SSL END", $ssl_conf);
							file_put_contents($ssl_tmpfile, $ssl_conf);
							$cmd = "amh module BBShijieSSL-1.1 admin append,{$ssl_vhost_new};";
							$cmd = Functions::trim_cmd($cmd);
							shell_exec($cmd);

							if (is_file($file1) && is_file($file2))
							{
								$_POST = null;
								$_POST['reload_nginx'] = true;
								$this -> status = 'success';
								$this -> notice = "{$ssl_vhost_new}: 新增SSL主机成功。";
							}
							else
							{
								$this -> status = 'error';
								$this -> notice = "{$ssl_vhost_new}: 新增SSL主机失败。";
							}
						}
					}
					elseif(isset($_POST['save']))
					{
						if(!is_file($file1) || !is_file($file2) || !is_file($vhost_conf))
						{
							$this -> status = 'error';
							$this -> notice = "{$ssl_vhost_new}: SSL主机不存在或标识域名不存在，保存SSL主机失败。";
						}
						else
						{
							//保存证书
							file_put_contents($file1, $ssl_key);
							file_put_contents($file2, $ssl_crt);

							if (is_file($file1) && is_file($file2))
							{
								$_POST = null;
								$_POST['reload_nginx'] = true;
								$this -> status = 'success';
								$this -> notice = "{$ssl_vhost_new}: 保存SSL主机成功。";
							}
							else
							{
								$this -> status = 'error';
								$this -> notice = "{$ssl_vhost_new}: 保存SSL主机失败。";
							}
						}
					}
				}
			}
			else
			{
				$this -> status = 'error';
			    $this -> notice = "{$ssl_vhost_new}: 新增/保存SSL主机失败，错误的主机名称。";
			}
		}

		//检查是否支持多SSL主机
		if (isset($_GET['check_tls']))
		{
			$amh_cmd = 'amh module BBShijieSSL-1.1 admin check_tls';
			$result = shell_exec($amh_cmd);
			$result = Functions::trim_result($result);
			if (strpos($result , 'TLS SNI support enabled') !== false)
			{
				$this -> status = 'success';
				$status = '[正确] Nginx已配置TLS SNI，支持多SSL主机。';
			}
			else
			{
				$this -> status = 'error';
				$status = '[警告] Nginx未配置TLS SNI，不支持多SSL主机。';
			}
			$this -> notice = $status . "\n" . $result;
		}


		// 列表 ***********
		$host_list = $this -> hosts -> host_list();

		if ($handle = opendir($ssl_dir)) {
			$ssl_list = array();
			while (false !== ($file = readdir($handle))) {
				if(strpos($file,'.pem') !== false){
					$ssl_list[]=substr($file,0,-4);
				}
			}
		}
		closedir($handle);

		$this -> indexs -> log_insert($this -> notice);
		$this -> host_list = $host_list;
		$this -> ssl_list = $ssl_list;
		$this -> _view('bbshijiessl');	
	}
}
?>
