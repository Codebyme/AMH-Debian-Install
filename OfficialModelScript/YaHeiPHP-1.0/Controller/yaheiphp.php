<?php
class yaheiphp extends AmysqlController
{
	function IndexAction()
	{
		$this -> title = 'AMH - YaHeiPHP-1.0';	// 面板模块标题
		$this -> _class('Functions');			// 载入面板函数
		Functions::CheckLogin();				// 面板登录检查函数
	
		$this -> login = true;					
		$this -> _view('yaheiphp');				// 加载模板视图模板文件
	}
}
?>
