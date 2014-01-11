<?php
class vpns extends AmysqlModel{
	//取得用户列表
	function get_vpn_list($page = 1, $page_sum = 20)
	{
		$limit = ' LIMIT ' . ($page-1)*$page_sum . ' , ' . $page_sum;
		$sql = "SELECT * FROM `module_vpn`";
		$sum = $this -> _sum($sql);
		$sql = "SELECT * FROM `module_vpn` $limit";
		Return array('data' => $this -> _all($sql), 'sum' => $sum);
	}
    
	// 增加
	function vpn_add($vpn_user, $vpn_pwd)
	{
		$vpn_user = trim($vpn_user);
		$vpn_pwd  = trim($vpn_pwd);

        if(strlen($vpn_pwd)<4 || strlen($vpn_user)<4){
          Return '用户名，密码长度不能小于4位。';
          continue;
        }
        
        $sql = "SELECT `vpn_user` FROM `module_vpn` WHERE `vpn_user`='$vpn_user';";
        //$this -> _query($sql);    // 执行Sql
        $num = $this -> _sum($sql);    //取得数据总数
        
        if($num>0){
            Return '代理用户名（'.$vpn_user.'）已存在！';
            continue;
        }else{
            $data = array('vpn_id' => '','vpn_user' => $vpn_user,'vpn_pwd' => $vpn_pwd);            
            $this -> _insert('module_vpn', $data);
            Return '[OK]';
        }
	}
    
	//取得单条数据
	function vpn_get($vid){
        if(!is_numeric($vid)){
            Return '非法提交数据！';   
        }else{
            $sql = "SELECT * FROM `module_vpn` WHERE `vpn_id`='$vid';";            
            $num = $this -> _sum($sql);            
            if($num<1){
                Return ' : 非法提交数据！';
                continue;
            }else{
                Return $this -> _row($sql);
            }
        }		
	}
    
    
	//编辑数据
	function vpn_edit($vid, $vpn_user, $vpn_pwd){
        if(strlen($vpn_pwd)<4 || strlen($vpn_user)<4){
          Return '用户名，密码长度不能小于4位。';
          continue;
        }        
        if(!is_numeric($vid)){
            Return '非法提交数据！'; 
            continue;  
        }else{
            
            $sql = "SELECT * FROM `module_vpn` WHERE `vpn_id`='$vid';";
            $num = $this -> _sum($sql);    //取得数据总数          
            if($num>0){                
                $num = $this -> _sum("SELECT * FROM `module_vpn` WHERE `vpn_user`='$vpn_user' and not `vpn_id`='$vid';");               
                if($num>0){
                    Return '代理用户名（'.$vpn_user.'）已存在！';
                    continue;                    
                }else{
                    // 更新数据 $table表名，$data数据array('field' => 'value'), $where条件Sql
                    $data = array('vpn_user' => $vpn_user,'vpn_pwd' => $vpn_pwd);  
                    $where = "WHERE `vpn_id` ='$vid';";            
                    $this -> _update('module_vpn', $data, $where);             
                    Return $this -> vpn_get($vid);                   
                }
            }

        }
	}
    
	//删除
	function vpn_del($vid){  
        if(!is_numeric($vid)){
            Return '非法提交数据！'; 
            continue;  
        }else{           
            $sql = "DELETE FROM `module_vpn` WHERE `vpn_id`='$vid';"; 
            $query = $this -> _query($sql);
            $sum = mysql_affected_rows();  
            Return $sum;                    
        }
	}
    
	// 更新
	function vpn_make(){
		$cmd = "amh module LanYing-VPN-1.0 admin make";
		$cmd = Functions::trim_cmd($cmd);
		$result = shell_exec($cmd);
	}  
    
}
?>