<?php
class AMBmail extends AmysqlController
{
	public $indexs = null;
	public $AMBmails = null;
	public $notice = null;
	public $top_notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> AMBmails = $this ->  _model('AMBmails');
	}

	function IndexAction()
	{
		$this -> AMBmail_list();
	}

	// AMBmail列表
	function AMBmail_list()
	{
		$this -> title = 'AMBmail 远程备份 - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$input_item = array('remote_status', 'remote_ip');

		// 保存新配置
		if (isset($_POST['save']))
		{
			$save = true;
			foreach ($input_item as $val)
			{
				if(empty($_POST[$val]))
				{
					$this -> status = 'error';
					$this -> notice = '新增AMBmail远程备份配置失败，请填写完整数据，*号为必填项。';
					$save = false;
					break;
				}
			}
			if (!$this -> AMBmails -> is_email($_POST['remote_ip']))
			{
				$this -> status = 'error';
				$this -> notice = '新增AMBmail远程备份配置失败，Email地址错误。';
				$save = false;
			}
			if($save)
			{
				$id = $this -> AMBmails -> AMBmail_insert();
				if ($id)
				{
					$this -> status = 'success';
					$this -> notice = 'ID:' . $id . ' 新增AMBmail远程备份配置成功。';
					$_POST = array();
				}
				else
				{
					$this -> status = 'error';
					$this -> notice = ' 新增AMBmail远程备份配置失败。';
				}
			}
		}

		// 连接测试
		if (isset($_GET['check']))
		{
			$id = (int)$_GET['check'];
			$data = $this -> AMBmails -> get_AMBmail($id);
			if (isset($data['remote_id']))
			{
				$cmd = "amh module AMBmail-1.0 admin check,{$data['remote_ip']}";
				$cmd = Functions::trim_cmd($cmd);
				$result = shell_exec($cmd);
				$result = trim(Functions::trim_result($result), "\n ");
				echo strpos($result, 'success.') !== false ? 'OK' : '';
			}
			exit();
		}

		// 编辑远程配置
		if (isset($_GET['edit']))
		{
			$id = (int)$_GET['edit'];
			$_POST = $this -> AMBmails -> get_AMBmail($id);
			if($_POST['remote_id'])
			{
				$this -> edit_remote = true;
			}
		}

		// 保存编辑远程配置
		if (isset($_POST['save_edit']))
		{
			$id = $_POST['remote_id'] = (int)$_POST['save_edit'];
			$save = true;
			foreach ($input_item as $val)
			{
				if(empty($_POST[$val]) && $val != 'remote_password')
				{
					$this -> status = 'error';
					$this -> notice = 'ID:' . $id . ' 编辑AMBmail远程备份配置失败。*号为必填项。';
					$save = false;
					$this -> edit_remote = true;
				}
			}
			if (!$this -> AMBmails -> is_email($_POST['remote_ip']))
			{
					$this -> status = 'error';
				$this -> notice = '编辑AMBmail远程备份配置失败，Email地址错误。';
				$save = false;
			}
			if ($save)
			{
				$result = $this -> AMBmails -> AMBmail_update();
				if ($result)
				{
					$this -> status = 'success';
					$this -> notice = 'ID:' . $id . ' 编辑AMBmail远程备份配置成功。';
					$_POST = array();
				}
				else
				{
					$this -> status = 'error';
					$this -> notice = 'ID:' . $id . ' 编辑AMBmail远程备份配置失败。';
					$this -> edit_remote = true;
				}
			}
		}

		// 删除远程配置
		if (isset($_GET['del']))
		{
			$id = (int)$_GET['del'];
			if(!empty($id))
			{
				$result = $this -> AMBmails -> AMBmail_del($id);
				if ($result)
				{
					$this -> status = 'success';
					$this -> top_notice = 'ID:' . $id . ' 删除AMBmail远程备份配置成功。';
				}
				else
				{
					$this -> status = 'error';
					$this -> top_notice = 'ID:' . $id . ' 删除AMBmail远程备份配置失败。';
				}
			}
		}

		$this -> AMBmail_list_data = $this -> AMBmails -> get_AMBmail_list();
		$this -> indexs -> log_insert($this -> notice);
		$this -> _view('AMBmail_list');
	}
}
?>
