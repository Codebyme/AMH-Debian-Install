<?php
class bbshijiessh extends AmysqlController
{
	public $indexs = null;
	public $bbssh = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> bbssh = $this ->  _model('bbssh');
	}


	function IndexAction()
	{
		$this -> title = 'AMH - BBShijieSSH';	
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		//添加
		if (isset($_POST['add']))
		{
			//用户名和密码判断
			$ssh_user = $_POST['user'];
			$ssh_pass = $_POST['pass'];
			$ruser = "--{$ssh_user}--";
			$rpass = "--{$ssh_pass}--";
			$ruser=preg_match_all('/^--[a-zA-Z0-9]{1,10}--$/', $ruser, $result);
			$rpass=preg_match_all('/^--[a-zA-Z0-9]{6,15}--$/', $rpass, $result);

			if ($ruser > 0 && $rpass > 0)
			{
				$ssh = $this -> bbssh -> get_ssh_by_name($ssh_user);
				if (is_array($ssh))
				{
					$_POST = null;
					$this -> status = 'error';
					$this -> notice = "用户名已存在，添加梯子用户失败。";
				}else{
					$_POST = null;
					$this -> status = 'success';
					$this -> notice = "添加梯子用户tizi{$ssh_user}成功。";
					$cmd = "amh module BBShijieSSH-1.0 admin add,{$ssh_user},{$ssh_pass};";
					$cmd = Functions::trim_cmd($cmd);
					shell_exec($cmd);
				}
			}else{
				$_POST = null;
				$this -> status = 'error';
				$this -> notice = "用户名或密码不符合要求，添加梯子用户失败。";
			}
		}

		//编辑
		if (isset($_GET['sid']))
		{
			$ssh = $this -> bbssh -> get_ssh($_GET['sid']);
			$this -> ssh = $ssh;
		}

		// 修改保存
		if (isset($_POST['save']))
		{
			$ssh = $this -> bbssh -> get_ssh($_POST['sid']);
			if(!is_array($ssh))
			{
				//无此记录
				$_POST = null;
				$this -> status = 'error';
				$this -> notice = "用户不存在，修改梯子用户密码失败。";
			}else{
				//密码判断
				$ssh_pass = $_POST['pass'];
				$rpass = "--{$ssh_pass}--";
				$rpass=preg_match_all('/^--[a-zA-Z0-9]{6,15}--$/', $rpass, $result);
				if ($rpass > 0)
				{
					$ssh_user = $ssh['username'];
					$_POST = null;
					$this -> status = 'success';
					$this -> notice = "修改梯子用户密码成功。";
					$cmd = "amh module BBShijieSSH-1.0 admin edit,{$ssh_user},{$ssh_pass};";
					$cmd = Functions::trim_cmd($cmd);
					shell_exec($cmd);
				}else{
					$_POST = null;
					$this -> status = 'error';
					$this -> notice = "密码不符合要求，修改梯子用户密码失败。";
				}
			}
		}

		//删除
		if (isset($_GET['del']))
		{
			$ssh = $this -> bbssh -> get_ssh($_GET['del']);
			if(!is_array($ssh))
			{
				//无次记录
				$this -> status = 'error';
				$this -> notice = "用户不存在，删除梯子用户失败。";
			}else{
				//删除
				$ssh_user = $ssh['username'];
				$this -> status = 'success';
				$this -> notice = "删除梯子用户成功。";
				$cmd = "amh module BBShijieSSH-1.0 admin del,{$ssh_user};";
				$cmd = Functions::trim_cmd($cmd);
				shell_exec($cmd);
			}
		}

		//列表
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$page_sum = 20;
		$ssh_list = $this -> bbssh -> ssh_list($page, $page_sum);
		$total_page = ceil($ssh_list['sum'] / $page_sum);						
		$page_list = Functions::page('SSHLog', $ssh_list['sum'], $total_page, $page);		// 分页列表

		$this -> page = $page;
		$this -> total_page = $total_page;
		$this -> page_list = $page_list;
		$this -> ssh_list = $ssh_list;
		$this -> _view('bbshijiessh');
	}
}
?>
