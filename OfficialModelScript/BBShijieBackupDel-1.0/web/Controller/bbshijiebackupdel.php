<?php
class bbshijiebackupdel extends AmysqlController
{
	public $indexs = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
	}


	function IndexAction()
	{
		$this -> title = 'AMH - BBShijieBackupDel';	
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		$backupdel_dir = '/usr/local/nginx/conf/bbshijiedel/';
		$set_file = "{$backupdel_dir}setting";

		// 保存
		if (isset($_POST['save']))
		{
			if (empty($_POST['backup_del_days']) || !is_numeric($_POST['backup_del_days']))
			{
				$this -> status = 'error';
			    $this -> notice = "保存备份天数失败，非法或错误的参数。";
			    $backup_del_days = 5;
			}
			else
			{
				$backup_del_days = (int)$_POST['backup_del_days'];

				if($backup_del_days < 1){
					$this -> status = 'error';
			   		$this -> notice = "保存备份天数失败，错误的参数。";
			   		$backup_del_days = 5;
				}
				else
				{
					$_POST = null;
					$this -> status = 'success';
					$this -> notice = "保存备份天数成功，{$backup_del_days}天。";
				}
			}
			$cmd = "amh module BBShijieBackupDel-1.0 admin set,{$backup_del_days};";
			$cmd = Functions::trim_cmd($cmd);
			shell_exec($cmd);
		}

		// 查看
		if (is_file($set_file))
		{
			$_POST['backup_del_days'] = file_get_contents($set_file);
		}

		$this -> indexs -> log_insert($this -> notice);
		$this -> category = 'bbshijiebackup_del';
		$this -> _view('bbshijiebackupdel');	
	}
}
?>
