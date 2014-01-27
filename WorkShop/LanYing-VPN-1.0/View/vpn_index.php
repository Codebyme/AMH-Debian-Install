<?php include('header.php'); ?>
<style>
#STable td.object_list_last {
	background:url("View/images/Listbj.gif") repeat-x scroll left top white;
	padding:8px;
	text-align:right;
	line-height:18px;
	color:#607993;
}
</style>
<div id="body">
<h2>AMH » LanYing-VPN-1.0</h2>
<div id="category" style="margin-top: 10px;">
<a href="index.php?c=vpn" id="vpnlist">查看用户</a>
<a href="index.php?c=vpn&vpnadd=vpnadd" id="vpnadd">添加用户</a>
</div>
<script>
var action_dom = G('<?php echo isset($_GET['vpnadd']) ? 'vpnadd' : 'vpnlist';?>');
action_dom.className = 'activ';
</script>
<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<style>
#STable td {
	text-align:left;
	padding-left:10px;
}
.add td {height: 26px;}
</style>
<p>VPN代理账号列表:</p>
<!------------------------------>
<?php if (isset($_GET['vpnadd'])) { ?>
<form action="./index.php?c=vpn&vpnadd=<?php echo $_GET['vpnadd'];?>" method="POST" >
<table border="0" cellspacing="1"  id="STable" class="add" style="width:500px;">
<tr>
<th width="150">名称</th>
<th>值（只能是A-Z 0-9）</th>
</tr>
<tr>
<td style="text-align: center;">代理用户名：</td>
<td><input type="text" name="vpn_user" class="input_text" style="width: 220px;" /> <font class="red"> *</font></td>
</tr>
<tr>
<td style="text-align: center;">代理密码项：</td>
<td><input type="password" name="vpn_pwd" class="input_text" style="width: 220px;" /> <font class="red"> *</font></td>
</tr>
<tr><th colspan="2" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="vpnadd"><span class="check icon"></span>添加保存</button> 
</th></tr>
</table>
</form>
<br />
<?php } elseif (isset($_GET['vpnedit'])) { ?>
<form action="./index.php?c=vpn&vpnedit=vpnedit&vid=<?php echo $_GET['vid'];?>" method="POST" >
<input type="hidden" name="vid" value="<?php echo $_GET['vid'];?>" />
<table border="0" cellspacing="1"  id="STable" class="add" style="width:460px;">
<tr>
<th width="150">名称</th>
<th>值（只能是A-Z 0-9）</th>
</tr>
<tr>
<td style="text-align: center;">代理用户名：</td>
<td><input type="text" name="vpn_user" class="input_text" value="<?php echo $vpninfo['vpn_user']; ?>" style="width: 220px;" /> <font class="red"> *</font></td>
</tr>
<tr>
<td style="text-align: center;">代理密码项：</td>
<td><input type="password" name="vpn_pwd" class="input_text" value="<?php echo $vpninfo['vpn_pwd']; ?>" style="width: 220px;" /> <font class="red"> *</font></td>
</tr>
<tr><th colspan="2" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="vpnedits"><span class="check icon"></span>编辑保存</button> 
</th></tr>
</table>
</form>
<br />
<?php } ?>
<!------------------------------>
<div id="AMProxy_list">
	<table border="0" cellspacing="1"  id="STable" style="width:500px;">
	<tr>
	<th>编号</th>
	<th>用户</th>
	<th>密码</th>
    <th>管理</th>
	</tr>
	<?php 
	if(!is_array($userdata) || count($userdata) < 1)
	{
	?>
		<tr><td colspan="4" style="padding:10px;text-align:center;">没找到数据。</td></tr>
	<?php	
	}
	else
	{
		$k = 0;
		foreach ($userdata as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['vpn_id'];?></th>
			<td><?php echo $val['vpn_user'];?></td>
			<td><?php echo substr($val['vpn_pwd'], 0, 2) . '***' . substr($val['vpn_pwd'], -2);?></td>
            <td style="text-align:center;"><a href="./index.php?c=vpn&vpnedit=vpnedit&vid=<?php echo $val['vpn_id'];?>" class="button"><span class="pen icon"></span> 编辑</a>  <a href="./index.php?c=vpn&vpndel=vpndel&vid=<?php echo $val['vpn_id'];?>" onclick="return confirm('确定要删除吗?');" class="button"><span class="cross icon"></span> 删除</a></td>
			</tr>
	<?php
		}
	}
	?>
</table>
<div id="page_list">总<?php echo $total_page;?>页 - <?php echo $vpn_list['sum'];?>记录 » 页码 <?php echo htmlspecialchars_decode($page_list);?> </div>
<button type="button" class="primary button" onclick="WindowLocation('/index.php?c=vpn')"><span class="home icon"></span> 返回列表</button>




<div id="notice_message" style="width:470px;">
<h3>VPN 代理服务器管理</h3>
VPN账号保存文件 <font class="red">/etc/ppp/chap-secrets</font> 文件。 
 <br />
</div>
</div>

</div>
<?php include('footer.php'); ?>