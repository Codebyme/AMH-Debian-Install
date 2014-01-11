<?php

class AMPathinfos extends AmysqlModel
{
	// 主机列表
	function AMPathinfo_list()
	{
		$data = array();
		$cmd = 'amh module AMPathinfo-1.0 admin list';
		$cmd = Functions::trim_cmd($cmd);
		$result = trim(shell_exec($cmd), "\n");
		preg_match_all("/(.*\[(?:Open|Close)\])/", $result, $AMPathinfo_list);
		if (is_array($AMPathinfo_list[1]))
		{
			foreach ($AMPathinfo_list[1] as $key=>$val)
			{
				$rs = explode(' ', $val);
				$data[] = array($rs[0], (strpos($rs[1], 'Open') !== false));
			}
		}
		Return $data;
	}

	// 编辑主机运行环境
	function AMPathinfo_edit($domain, $mode)
	{
		$cmd = "amh module AMPathinfo-1.0 admin edit,$domain,$mode";
		$cmd = Functions::trim_cmd($cmd);
		exec($cmd, $tmp, $status);
		Return !$status;
	}
}

?>