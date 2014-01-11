<?php

/************************************************
 * Amysql Host - AMH 4.1
 * By: BBShijie 
 * @param Object ssh user (proxy only) 爬墙专用SSH帐号数据模型
 * Update:2013-10-25
 * 
 */

class bbssh extends AmysqlModel
{

	// ssh帐号列表
	function ssh_list($page = 1, $page_sum = 20)
	{
		$sql = "SELECT * FROM module_bbshijiessh WHERE";
		$sum = $this -> _sum($sql);

		$limit = ' LIMIT ' . ($page-1)*$page_sum . ' , ' . $page_sum;
		$sql = "SELECT * FROM module_bbshijiessh ORDER BY sid DESC $limit";

		Return array('data' => $this -> _all($sql), 'sum' => $sum);
	}

	// 取得某一ssh帐号 by id
	function get_ssh($sid)
	{
		$sid = (int)$sid;
		$sql = "SELECT * FROM module_bbshijiessh WHERE sid = '$sid'";
		Return $this -> _row($sql);
	}

	// 取得某一ssh帐号 by username
	function get_ssh_by_name($username)
	{
		$sql = "SELECT * FROM module_bbshijiessh WHERE username = '$username'";
		Return $this -> _row($sql);
	}
}

?>