<?php

class AMPathinfo extends AmysqlController
{
	public $indexs = null;
	public $AMPathinfos = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> AMPathinfos = $this ->  _model('AMPathinfos');
	}


	function IndexAction()
	{
		$this -> title = 'AMH - AMPathinfo';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();

		if (isset($_GET['domain']) && !empty($_GET['domain']) && isset($_GET['mode']) && in_array($_GET['mode'], array('open', 'close')))
		
		{

			$domain = $_GET['domain'];
			
			$mode = $_GET['mode'];

			$mode_cn = array('open' => '开启', 'close' => '关闭');
			if ($this -> AMPathinfos -> AMPathinfo_edit($domain, $mode))
			
			{
				
				$this -> status = 'success';
				
				$this -> notice = " AMPathinfo设置：{$domain}域名PATHINFO{$mode_cn[$mode]}成功。";

			}
		
			else

			{
				
				$this -> status = 'error';
				
				$this -> notice = " AMPathinfo设置：{$domain}域名PATHINFO{$mode_cn[$mode]}失败。";
	
			}
		
		}
		$this -> indexs -> log_insert($this -> notice);
		
		$this -> AMPathinfo_list = $this -> AMPathinfos -> AMPathinfo_list();
		$this -> _view('AMPathinfo');
	} 
}

?>