<?php

class amproxy extends AmysqlController
{
	public $indexs = null;
	public $amproxys = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> amproxys = $this ->  _model('amproxys');
	}


	function IndexAction()
	{
		$this -> amproxy_list();
	}

	// 反代列表
	function amproxy_list()
	{
		$this -> title = '反代列表 - AMProxy - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$this -> status = 'error';
	
		// 状态
		if (isset($_GET['run']))
		{
			$run_name = $_GET['run'];
			$run_zh = array('start' => '启动', 'stop' => '停止');
			if(!empty($run_name) && isset($run_zh[$_GET['g']]))
			{
				$g = $_GET['g'];
				if ($this -> amproxys -> amproxy_run($run_name, $g))
				{
					$this -> status = 'success';
					$this -> notice = $run_name . ' : AMProxy域名' . $run_zh[$g] . '成功。';
				}
				else
					$this -> notice = $run_name . ' : AMProxy域名' . $run_zh[$g] . '失败。';
			}
		}
		// 缓存状态
		if (isset($_GET['run_cache']))
		{
			$run_name = $_GET['run_cache'];
			$run_zh = array('start' => '启动', 'stop' => '停止');
			if(!empty($run_name) && isset($run_zh[$_GET['g']]))
			{
				$g = $_GET['g'];
				if ($this -> amproxys -> amproxy_run_cache($run_name, $g))
				{
					$this -> status = 'success';
					$this -> notice = $run_name . ' : AMProxy域名缓存' . $run_zh[$g] . '成功。';
				}
				else
					$this -> notice = $run_name . ' : AMProxy域名缓存' . $run_zh[$g] . '失败。';
			}
		}

		// 删除
		if (isset($_GET['del']))
		{
			$del_name = $_GET['del'];
			if(!empty($del_name))
			{
				$result = $this -> amproxys -> amproxy_del($del_name);
				if (strpos($result, '[OK]') !== false)
				{
					$this -> status = 'success';
					$this -> notice = $del_name . ' : AMProxy域名删除成功。';
				}
				else
					$this -> notice = $del_name . ' : AMProxy域名删除失败。';
			}
		}

		// 管理
		if (isset($_GET['admin']))
		{
			if (isset($_POST['edit']))
			{
				$result = $this -> amproxys -> amproxy_edit();
				if (strpos($result, '[OK]') !== false)
				{
					$this -> status = 'success';
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名编辑成功。';
				}
				else
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名编辑失败。';
			}
			
			$name = $_GET['admin'];
			$this -> amproxy_get = $this -> amproxys -> amproxy_get($name);
		}
		
		// 关键字
		if (isset($_GET['keyword']))
		{
			$name = $_GET['keyword'];

			// 删除
			if (isset($_GET['del_keyword']))
			{
				$_POST['server_name'] = $name;
				$_POST['subs_filter_del'] = $_GET['del_keyword'];
				$result = $this -> amproxys -> amproxy_edit();
				$_GET['del_keyword'] = $this -> amproxys -> is_utf8($_GET['del_keyword']) ? $_GET['del_keyword'] : iconv('GBK', 'UTF-8//IGNORE', $_GET['del_keyword']);
				if (strpos($result, '[OK]') !== false)
				{
					$this -> status = 'success';
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名删除关键字"' . $_GET['del_keyword'] . '"成功。';
				}
				else
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名删除关键字"' . $_GET['del_keyword'] . '"失败。';
			}

			// 增加
			if (isset($_POST['add_keyword']))
			{
				$_POST['server_name'] = $name;
				$tag_k = array('g', 'i', 'o', 'r');
				foreach ($tag_k as $val)
					$_POST['subs_filter_gior'] .= (isset($_POST['_'.$val])) ? $val : '';
				$_POST['replace_str'] = empty($_POST['replace_str']) ? ' ' : $_POST['replace_str'];
				$_POST['subs_filter_gior'] = empty($_POST['subs_filter_gior']) ? 'g' : $_POST['subs_filter_gior'];
				$_POST['subs_filter_add'] = "{$_POST['find_str']},{$_POST['replace_str']},{$_POST['subs_filter_gior']}";
				if ($_POST['str_char'] == 'gbk')
					$_POST['subs_filter_add'] = iconv('UTF-8', 'GBK//IGNORE', $_POST['subs_filter_add']);
				
				$result = $this -> amproxys -> amproxy_edit();
				if (strpos($result, '[OK]') !== false)
				{
					$this -> status = 'success';
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名新增关键字"' . $_POST['find_str'] . '"成功。';
				}
				else
					$this -> notice = $_POST['server_name'] . ' : AMProxy域名新增关键字"' . $_POST['find_str'] . '"失败。';
			}

			$keyword_list = $this -> amproxys -> keyword_list($name);
			$this -> keyword_list = $keyword_list;
		}

		// 新增
		if (isset($_POST['submit']))
		{
			$server_name = $_POST['server_name'];
			$proxy_pass = $_POST['proxy_pass'];
			if(!empty($server_name) && !empty($proxy_pass))
			{
				$result = $this -> amproxys -> amproxy_add($server_name, $proxy_pass);
				if (strpos($result, '[OK]') !== false)
				{
					$this -> status = 'success';
					$this -> notice = $server_name . ' : AMProxy域名增加成功。';
				}
				else
					$this -> notice = $server_name . ' : AMProxy域名增加失败。';
			}
			else
			{
				$this -> notice = '请填写完整数据。';
			}
		}

		$this -> indexs -> log_insert($this -> notice);
		$this -> amproxy_list = $this -> amproxys -> amproxy_list();
		$this -> _view('amproxy');
	} 
	
	// *********************************************************
	// 缓存设置
	function amproxy_cache()
	{
		$this -> title = '缓存设置 - AMProxy - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		// 保存
		if (isset($_POST['save']))
		{
			$this -> status = 'error';
			$field = array('levels', 'keys_zone', 'max_size', 'valid', 'inactive');
			foreach ($field as $key=>$val)
			{
				if (!isset($_POST[$val]) || empty($_POST[$val]))
				{
					$error = true;
					break;
				}
			}
			if (isset($error))
				$this -> notice = '请填写完整数据。';
			else
			{
				if ($this -> amproxys -> save_amproxy_cache())
				{
					$this -> status = 'success';
					$this -> notice = 'AMProxy 缓存设置成功。';
				}
				else
					$this -> notice = 'AMProxy 缓存设置失败。';
			}

		}

		$this -> indexs -> log_insert($this -> notice);
		$this -> amproxy_cache = $this -> amproxys -> get_amproxy_cache();
		$this -> _view('amproxy_cache');
	}

	// *********************************************************
	// 缓存索引
	function amproxy_cache_index()
	{
		$this -> title = '缓存索引 - AMProxy - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		if (isset($_POST['post_submit']))
		{
			if ($this -> amproxys -> create_amproxy_cache_index())
			{
				$this -> status = 'success';
				$this -> notice = 'AMProxy 创建缓存索引成功。';
			}
			else
			{
				$this -> status = 'error';
				$this -> notice = 'AMProxy 创建缓存索引失败。';
			}
		}

		$this -> indexs -> log_insert($this -> notice);
		$this -> amproxy_cache_index = $this -> amproxys -> get_amproxy_cache_index();
		$this -> _view('amproxy_cache_index');
	}
	
	// *********************************************************
	// 缓存删除
	function amproxy_cache_del()
	{
		$this -> title = '缓存删除 - AMProxy - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		// 删除缓存
		if (isset($_POST['post_delete']))
		{
			if ($this -> amproxys -> amproxy_cache_delete())
			{
				$this -> status = 'success';
				$this -> notice = 'AMProxy 缓存删除成功。';
			}
			else
			{
				$this -> status = 'error';
				$this -> notice = 'AMProxy 缓存删除失败。';
			}
		}

		// 取得域名列表与缓存文件类型
		$amproxy_cache_index = $this -> amproxys -> get_amproxy_cache_index();
		foreach ($amproxy_cache_index as $key=>$val)
		{
			$cache_host[] = $key;
			foreach ($val as $k=>$v)
				$cache_type[$k] = $k;
		}

		// 缓存列表
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$page_sum = 20;
		$amproxy_cache_list = $this -> amproxys -> get_amproxy_cache_list($page, $page_sum);
		$total_page = ceil($amproxy_cache_list['sum'] / $page_sum);						
		$page_list = Functions::page('AccountLog', $amproxy_cache_list['sum'], $total_page, $page);		// 分页列表
		$this -> page = $page;
		$this -> total_page = $total_page;
		$this -> page_list = $page_list;
		$this -> amproxy_cache_list = $amproxy_cache_list;

		$this -> cache_host = $cache_host;
		$this -> cache_type = $cache_type;

		$this -> indexs -> log_insert($this -> notice);
		$this -> _view('amproxy_cache_del');
	}

}

?>