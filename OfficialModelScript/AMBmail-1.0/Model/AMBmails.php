<?php

class AMBmails extends AmysqlModel
{
	// 取得AMBmail远程备份列表
	function get_AMBmail_list()
	{
		$sql = "SELECT * FROM amh_backup_remote WHERE remote_type = 'AMBmail' ORDER BY remote_id ASC ";
		Return $this -> _all($sql);	
	}

	// 新增AMBmail远程备份设置
	function AMBmail_insert()
	{
		$_POST['remote_type'] = 'AMBmail';
		$_POST['remote_pass_type'] = '3';
		$data_name = array('remote_type', 'remote_status', 'remote_ip', 'remote_path', 'remote_user', 'remote_pass_type', 'remote_password', 'remote_comment');
		foreach ($data_name as $val)
			$insert_data[$val] = $_POST[$val];
		Return $this -> _insert('amh_backup_remote', $insert_data);
	}

	// 取得AMBmail远程备份
	function get_AMBmail($remote_id)
	{
		$sql = "SELECT * FROM amh_backup_remote WHERE remote_id = '$remote_id' AND remote_type = 'AMBmail' ";
		Return $this -> _row($sql);
	}

	// 更新保存AMBmail配置
	function AMBmail_update()
	{
		$data_name = array('remote_status', 'remote_ip', 'remote_comment');
		foreach ($data_name as $val)
		{
			if($val != 'remote_password' || !empty($_POST['remote_password']))
				$update_data[$val] = $_POST[$val];
		}
		Return $this -> _update('amh_backup_remote', $update_data,  " WHERE remote_id = '$_POST[remote_id]' ");
	}

	// 删除ALiOSS配置
	function AMBmail_del($remote_id)
	{
		$sql = "DELETE FROM amh_backup_remote WHERE remote_id = '$remote_id' AND remote_type = 'AMBmail'";
		$this -> _query($sql);
		Return $this -> Affected;
	}

	// 是否正确email
	function is_email($email)
	{
		return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
	}



}

?>