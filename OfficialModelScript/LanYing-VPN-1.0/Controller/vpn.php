<?php
class vpn extends AmysqlController
{
	public $indexs = null;
	public $vpns = null;
	public $notice = null;

	// Model
	function AmysqlModelBase()
	{
		if($this -> indexs) return;
		$this -> _class('Functions');
		$this -> indexs = $this ->  _model('indexs');
		$this -> vpns = $this ->  _model('vpns');
	}


	function IndexAction()
    {
		$this -> vpn_list();
	}
    
	// 用户列表
	function vpn_list()
	{
		$this -> title = 'LanYing-VPN-1.0 - AMH';
		$this -> AmysqlModelBase();
		Functions::CheckLogin();
        $this -> status = 'error';

			//增加
			if (isset($_POST['vpnadd'])){				
                $vpn_user = $_POST['vpn_user'];
                $vpn_pwd  = $_POST['vpn_pwd'];                
        			if(!empty($vpn_pwd) && !empty($vpn_user)){                       
        				$result = $this -> vpns -> vpn_add($vpn_user, $vpn_pwd);
        				if (strpos($result, '[OK]') !== false){
        					$this -> status = 'success';
        					$this -> notice = 'LanYing-VPN：' . $vpn_user . ' 代理用户增加成功。';
                            $this -> vpns ->vpn_make();
        				}else{
        					$this -> notice = 'LanYing-VPN：' . $result;
                        }
        			}else{
        				$this -> notice = 'LanYing-VPN：请填写完整数据。';
        			}
            }

			//编辑显示
			if (isset($_GET['vpnedit'])){				
                $vid = trim($_GET['vid']);                
                $result = $this -> vpns -> vpn_get($vid);
                    if (is_array($result)){
    					$this -> vpninfo = $result;
    				}else{
    					$this -> notice = 'LanYing-VPN：' . $result;
                        $result = null;
                    }                
            }
            
			//编辑保存
			if (isset($_POST['vpnedits'])){				
                $vid = trim($_POST['vid']);
                $vpn_user = trim($_POST['vpn_user']);
                $vpn_pwd = trim($_POST['vpn_pwd']);                
    			if(!empty($vpn_pwd) && !empty($vpn_user)  && !empty($vid) ){
    				$result = $this -> vpns -> vpn_edit($vid, $vpn_user, $vpn_pwd);
    				if (is_array($result)){    				    
    					$this -> status = 'success';
    					$this -> notice = 'LanYing-VPN：代理用户名（'.$vpn_user.'）修改成功。';
                        $this -> vpninfo = $result;
                        $this -> vpns ->vpn_make();
    				}else{
    					$this -> notice = 'LanYing-VPN：' . $result;
                    }
    			}else{
    				$this -> notice = 'LanYing-VPN：请填写完整数据。';
    			}            
            }   
            
			//删除
			if (isset($_GET['vpndel'])){				
                $vid = trim($_GET['vid']);                
                $result = $this -> vpns -> vpn_del($vid);                
                    if ($result>0){
                        $this -> vpns ->vpn_make();
                        $this -> status = 'success';
						$this -> notice = 'LanYing-VPN：代理用户删除成功。';   
    				}else{
    					$this -> notice = 'LanYing-VPN：非法提交数据！';
                    }
            }        
        

		//列表
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$page_sum = 10;
		$vpn_list = $this -> vpns -> get_vpn_list($page, $page_sum);
        
		$total_page = ceil($vpn_list['sum'] / $page_sum);						
		$page_list = Functions::page('AccountLog', $vpn_list['sum'], $total_page, $page);		// 分页列表
		$this -> page = $page;
		$this -> total_page = $total_page;
		$this -> page_list = $page_list;
		$this -> vpn_list = $vpn_list;
        $this -> indexs -> log_insert($this -> notice);
        $this -> userdata = $vpn_list['data'];   
		$this -> _view('vpn_index');
	}
}
?>