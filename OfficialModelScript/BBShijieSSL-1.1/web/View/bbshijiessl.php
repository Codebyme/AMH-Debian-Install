<?php include('header.php'); ?>	
<style>
.host_list {
	margin:1px;
}
#key_content {
	width: 500px;
	height:450px;
	display:inline-block;
}
</style>
<script>
if(!WindowLocation)	// AMH3.*
{
	var WindowLocation = function (url) {
		window.location = url;
	}
	var WindowOpen = function (url) {
		window.open(url);
	}
}
</script>
<div id="body">
<?php 
	$c_name = 'host';
	include('category_list.php'); 
?>
	<p>虚拟主机SSL管理:</p>
<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p style="display:inline-block" id="' . $status . '">' . $notice . '</p></div>';
?>
<div id="AMProxy_list">
	<table border="0" cellspacing="1"  id="STable" style="width:800px;">
	<tr>
	<th>ID</th>
	<th>文件位置</th>
	<th>已配置SSL的主机</th>
	<th>SSL主机管理</th>
	</tr>
	<?php 
	if(!is_array($ssl_list) || count($ssl_list) < 1)
	{
	?>
		<tr><td colspan="4" style="padding:10px;">暂无SSL主机。</td></tr>
	<?php	
	}
	else
	{
		foreach ($ssl_list as $key=>$val)
		{
			$key+=1;
	?>
			<tr>
			<th class="i"><?php echo $key;?></th>
			<td>/usr/local/nginx/conf/ssl/<?php echo $val;?>.pem<br />/usr/local/nginx/conf/ssl/<?php echo $val;?>.crt</td>
			<td><?php echo $val;?></td>
			<td>
			<a href="./index.php?c=bbshijiessl&a=bbshijie_ssl&name=<?php echo $val;?>" class="button"><span class="pen icon"></span> 查看编辑</a>
			<a href="./index.php?c=bbshijiessl&a=bbshijie_ssl&del=<?php echo $val;?>" class="button" onclick="return confirm('确认删除SSL主机:<?php echo $val;?> ?');"><span class="cross icon"></span> 删除</a>
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<button type="submit" class="primary button" onclick="WindowLocation('/index.php?c=bbshijiessl&a=bbshijie_ssl')" ><span class="home icon"></span>返回列表</button>
<button type="submit" class="primary button" onclick="WindowLocation('/index.php?c=bbshijiessl&a=bbshijie_ssl&check_tls=y')" ><span class="check icon"></span>检查是否支持多SSL主机</button> 

<br /><br /><br />
<form action="./index.php?c=bbshijiessl&a=bbshijie_ssl" method="POST" >
<p><?php echo isset($_GET['name']) ? '查看编辑' : '新增'; ?>SSL主机:</p>
<table border="0" cellspacing="1"  id="STable" style="width:800px;">

<tr>
<td>选择主机</td>
<td>
<?php if (isset($_GET['name'])) { ?>
	<!-- 编辑保存 -->
	<input type="text" id="ssl_vhost_new" value="<?php echo $_GET['name'];?>" class="input_text disabled" disabled />
	<input type="hidden" name="ssl_vhost_new" value="<?php echo $_GET['name'];?>" />
<?php } else {?>
	<select name="ssl_vhost_new" id="ssl_vhost_new">
	<?php
	foreach ($host_list as $key=>$val)
	{
		$v=$val['host_domain'];
		echo $val['host_nginx'] ? "<option value=\"{$v}\">{$v}</option>" : '';
	}
	?>
	</select>
<?php } ?>
&nbsp; <font class="red">*</font></td>
<td>需要启用SSL的主机。</td>
</tr>
<tr>
<td>密钥</td>
<td><textarea name="ssl_key" class="input_text"  id="key_content"><?php echo isset($_POST['ssl_key']) ? $_POST['ssl_key'] : '';?></textarea></td>
<td>请粘贴key或pem文件内容。</td>
</tr>
<tr>
<td>证书</td>
<td><textarea name="ssl_crt" class="input_text"  id="key_content"><?php echo isset($_POST['ssl_crt']) ? $_POST['ssl_crt'] : '';?></textarea></td>
<td>请粘贴crt文件内容，<br />必要情况下还需附带颁发机构证书。</td>
</tr>
<tr><th colspan="3" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="<?php echo isset($_GET['name']) ? 'save' : 'add';?>"><span class="check icon"></span><?php echo isset($_GET['name']) ? '保存' : '新增';?></button> 
</th></tr>
</table>
</form>
</div>



<div id="notice_message" style="width:660px;">
<h3>» BBShijieSSL 模块使用说明</h3>
1) 支持同一IP下多个SSL站点，前提是开启Nginx的TLS SNI。 <br />
2) 密钥和证书编辑保存后会直接生效(面板会平滑重载Nginx)，不需要您再额外重载Nginx。 <br />
3) 本模块的卸载&删除会清除已保存的SSL密钥和证书文件，并还原对应的虚拟主机配置。 <br />
4) 获得新版本支持或问题反馈，请联系作者或AMH官方网站。
<br />
</div>


</div>

<?php if (isset($_POST['reload_nginx'])) {?>
<script>
window.onload = function (){
	Ajax.get('/index.php?m=nginx&g=reload'); // 重载Nginx
}
</script>
<?php } ?>

<?php include('footer.php'); ?>
