<?php

class amproxys extends AmysqlModel
{
	// 反代列表
	function amproxy_list()
	{
		$cmd = 'amh module AMProxy-2.0 admin list';
		$cmd = Functions::trim_cmd($cmd);
		$result = trim(shell_exec($cmd), "\n");
		preg_match_all("/\n(.*)\[(Running|Stop)\]/", $result, $amproxy_list);
		foreach ($amproxy_list[1] as $key=>$val)
		{
			$val = trim($val);
			$amproxy = $this -> amproxy_get($val);
			$data[$val] = array(
				'name' => $val, 
				'status' => $amproxy_list[2][$key], 
				'cache_status' => $amproxy['proxy_cache'] == 'amproxy' ? 'Running' : 'Stop'
			);
		}
		ksort($data);
		Return $data;
	}

	// 状态
	function amproxy_run($run_name, $g)
	{
		$run_name = trim($run_name);
		$cmd = "amh module AMProxy-2.0 admin $g,$run_name";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}

	// 缓存状态
	function amproxy_run_cache($run_name, $g)
	{
		$run_name = trim($run_name);
		$cmd = "amh module AMProxy-2.0 admin {$g}-cache,$run_name";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}

	// 删除反代网站
	function amproxy_del($del_name)
	{
		$del_name = trim($del_name);
		$cmd = "amh module AMProxy-2.0 admin del,$del_name";
		$cmd = Functions::trim_cmd($cmd);
		$result = shell_exec($cmd);
		Return str_replace(array('[OK] AMProxy', '[OK] Nginx'), '', $result);
	}

	// 增加反代网站
	function amproxy_add($server_name, $proxy_pass)
	{
		$server_name = trim($server_name);
		$proxy_pass = trim($proxy_pass);
		$cmd = "amh module AMProxy-2.0 admin add,$server_name,$proxy_pass";
		$cmd = Functions::trim_cmd($cmd);
		$result = shell_exec($cmd);
		Return str_replace(array('[OK] AMProxy', '[OK] Nginx'), '', $result);
	}

	// 取得反代网站
	function amproxy_get($name)
	{
		$name = trim($name);
		$cmd = "amh module AMProxy-2.0 admin cat,$name";
		$cmd = Functions::trim_cmd($cmd);
		$result = trim(shell_exec($cmd), "\n");
		$result = Functions::trim_result($result);
		$result = preg_replace('/[\#]+.*/', '', $result);
		$list = array(
			'server_name',
			'subs_filter_types',
			'Referer',
			'Host',
			'proxy_pass',
			'echo_before_body',
			'echo_after_body',
			'proxy_cache',
		);
		foreach ($list as $val)
		{
			preg_match("/$val(.*);/", $result, $arr);
			$data[$val] = trim($arr[1]);
		}
		$data['subs_filter_types'] = str_replace(' ', ',', $data['subs_filter_types']);
		$data['AppendHtml_header'] = str_replace("\'", "'", substr($data['echo_before_body'], 1,-1));
		$data['AppendHtml_footer'] = str_replace("\'", "'", substr($data['echo_after_body'], 1,-1));
		Return $data;
	}

	// 编辑反代网站
	function amproxy_edit()
	{
		$list = array(
			'proxy_pass',
			'Referer',
			'Host',
			'subs_filter_types',
			'AppendHtml_header',
			'AppendHtml_footer',
			'subs_filter_add',
			'subs_filter_del'
		);

		$new_list = array(
			'AppendHtml_header' => 'AppendHtml,header',
			'AppendHtml_footer' => 'AppendHtml,footer',
			'subs_filter_add' => 'subs_filter,add',
			'subs_filter_del' => 'subs_filter,del'
		);

		foreach ($list as $key=>$val)
		{
			if (isset($_POST[$val]) && $_POST[$val] != $_POST[$val . '_hidden'])
			{
				$param = isset($new_list[$val]) ? $new_list[$val] : $val;
				// 特殊符号转义(同时AMH默认有转义)
				$_POST[$val] = str_replace(array('$', ';', '&', '|', '<', '>', '"', "'", "\n", "\r", "\t", ' '), array('\\\\\\$', '\;', '\&', '\|', '\<', '\>',  '\\\"', "\\'", '', '', '', '__'), $_POST[$val]);
				$_POST['server_name'] = Functions::trim_cmd($_POST['server_name']);
				$cmd = "amh 'module' 'AMProxy-2.0' 'admin' \"edit,{$_POST['server_name']},$param,{$_POST[$val]}\"";
				$result = shell_exec($cmd);
			}
		}

		Return str_replace(array('[OK] AMProxy', '[OK] Nginx'), '', $result);
	}

	// 正确判断utf8
	function is_utf8($word){ 
		// Return json_encode($word) == 'null' ? false : true;
		if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
			Return true; 
		Return false; 
	}

	// 关键字列表
	function keyword_list($name)
	{
		$cmd = "amh module AMProxy-2.0 admin edit,$name,subs_filter,list";
		$cmd = Functions::trim_cmd($cmd);
		$result = trim(shell_exec($cmd), "\n");
		preg_match_all("/subs_filter (.*);/", $result, $arr);
		if (is_array($arr[1]))
		{
			foreach ($arr[1] as $key=>$val)
			{
				preg_match("/'(.*)' '(.*)' (.*)/", $val, $data);
				unset($data[0]);
				$arr[1][$key] = $data;

				// 修饰符
				$tag_k = array('g', 'i', 'o', 'r');
				$tag = array('全局替换', '区分大小写', '首个替换', '正则替换');
				foreach ($tag_k as $k=>$v)
				{
					if(strpos($arr[1][$key][3], $v) !== false) 
						$_tmp[] = $tag[$k];
				}

				$arr[1][$key][3] = implode(' / ', $_tmp);
				$arr[1][$key][4][0] = (!$this -> is_utf8($arr[1][$key]['1'])) ? 'gbk' : 'utf8';
				$arr[1][$key][4][1] = $arr[1][$key][4][0] == 'gbk' ? iconv('GBK', 'UTF-8//IGNORE', $arr[1][$key]['1']) : $arr[1][$key][1];
				$arr[1][$key][4][2] = $arr[1][$key][4][0] == 'gbk' ? iconv('GBK', 'UTF-8//IGNORE', $arr[1][$key]['2']) : $arr[1][$key][2];
				$_tmp = null;
			}
			Return $arr[1];
		}
		Return array();
	}

	// *********************************************************
	// 取得缓存
	function get_amproxy_cache()
	{
		$name = trim($name);
		$cmd = "amh cat_nginx";
		$cmd = Functions::trim_cmd($cmd);
		$result = trim(shell_exec($cmd), "\n");
		$result = Functions::trim_result($result);
		preg_match("/amproxy_cache levels=(.*)keys_zone=amproxy:(.*)inactive=(.*)max_size=(.*);/", $result, $arr);
		preg_match("/proxy_cache_valid 200 304(.*);/", $result, $arr2);
		$data['levels'] = $arr[1];
		$data['keys_zone'] = $arr[2];
		$data['inactive'] = $arr[3];
		$data['max_size'] = $arr[4];
		$data['valid'] = $arr2[1];
		foreach ($data as $key=>$val)
			$data[$key] = trim($val);			
		Return $data;
	}

	// 保存缓存
	function save_amproxy_cache()
	{
		$run_name = trim($run_name);
		$cmd = "amh module AMProxy-2.0 admin cache,{$_POST['levels']},{$_POST['keys_zone']},{$_POST['max_size']},{$_POST['valid']},{$_POST['inactive']}";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}


	// *********************************************************
	// 取得缓存索引
	function get_amproxy_cache_index()
	{
		// $sql = "SELECT count(*) FROM module_amproxy GROUP BY amproxy_type ";
		$sql = "SELECT * FROM module_amproxy";
		$result = $this -> _query($sql);
		while ($rs = mysql_fetch_assoc($result))
		{
			$url_info = parse_url($rs['amproxy_key']);
			if (isset($url_info['host']) && !empty($url_info['host']))
			{
				++$data[$url_info['host']]['all']['sum'];
				$data[$url_info['host']]['all']['size'] += $rs['amproxy_size'];

				++$data[$url_info['host']][$rs['amproxy_type']]['sum'];
				$data[$url_info['host']][$rs['amproxy_type']]['size'] += $rs['amproxy_size'];
			}
		}
		Return $data;
	}

	// 创建缓存索引
	function create_amproxy_cache_index()
	{
		$val = (int)$_POST['cache_index_time_val'];
		$type = array('1' => 60*24, '2' => 60, '3' => 1);
		$area = array('1' => '-', '2' => '+');
		$val = isset($type[$_POST['cache_index_time_type']]) ? $val * $type[$_POST['cache_index_time_type']] : $val * $type[1];	// 时间类型
		$val = isset($area[$_POST['cache_index_time_type']]) ? $area[$_POST['cache_index_time_area']] . $val : '-' . $val;		// 时间范围
		$mode = $_POST['cache_index_time_mode'] == '2' ? 'truncate' : '';

		$cmd = "amh module AMProxy-2.0 admin cache-index,$val,$mode";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}

	// *********************************************************
	// 取得缓存列表
	function get_amproxy_cache_list($page = 1, $page_sum = 20)
	{
		$where = '';

		if (isset($_GET['cache_key']) && !empty($_GET['cache_key']))
		{
			$_GET['cache_key'] = trim($_GET['cache_key']);
			$where .= " AND amproxy_key LIKE '{$_GET['cache_key']}'";
		}

		if (isset($_GET['cache_type']) && $_GET['cache_type'] != 'all')
			$where .= " AND amproxy_type LIKE '{$_GET['cache_type']}'";

		$limit = ' LIMIT ' . ($page-1)*$page_sum . ' , ' . $page_sum;
		$sql = "SELECT * FROM module_amproxy WHERE 1 $where";
		$sum = $this -> _sum($sql);

		$sql = "SELECT * FROM module_amproxy WHERE 1 $where $limit";
		Return array('data' => $this -> _all($sql), 'sum' => $sum);
	}
	
	// 删除缓存
	function amproxy_cache_delete()
	{
		$url_param = (isset($_POST['cache_key']) && !empty($_POST['cache_key'])) ? $_POST['cache_key'] : '-all';
		$file_type = (isset($_POST['cache_type']) && !empty($_POST['cache_type']) && $_POST['cache_type'] != 'all') ? $_POST['cache_type'] : '-all';
		$cmd = "amh module AMProxy-2.0 admin cache-delete,$url_param,$file_type";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}
}

?>