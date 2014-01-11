<?php include('header.php'); ?>
<script src="View/js/backup_remote.js"></script>
<script>
if(!WindowLocation)
{
	var WindowLocation = function (url)
	{
		window.location = url;
	}
	var WindowOpen = function (url)
	{
		window.open(url);
	}
}
</script>
<div id="body">
<h2>AMH » AMBmail </h2>
<?php
	if (!empty($top_notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $top_notice . '</p></div>';
?>
<div id="AMBmail_list">
<p>AMBmail 远程备份服务:</p>
<table border="0" cellspacing="1"  id="STable" style="width:1080px;">
	<tr>
	<th>ID</th>
	<th>类型</th>
	<th>状态</th>
	<th>远程IP域名 / 目地</th>
	<th>保存位置 </th>
	<th>账号</th>
	<th>账号验证</th>
	<th>密码 / 密匙</th>
	<th>说明备注</th>
	<th>添加时间</th>
	<th>操作</th>
	</tr>
	<?php 
	if(!is_array($AMBmail_list_data) || count($AMBmail_list_data) < 1)
	{
	?>
		<tr><td colspan="11" style="padding:10px;">AMBmail 暂无远程备份设置</td></tr>
	<?php	
	}
	else
	{
		$remote_pass_type_arr = array(
			'1'	=> '密码',
			'2' => '<font color="green">密匙</font>',
			'3' => '<i>无</i>'
		);
		foreach ($AMBmail_list_data as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['remote_id'];?></th>
			<td><?php echo $val['remote_type'];?></td>
			<td><?php echo $val['remote_status'] == '1' ? '<font color="green">已开启</font>' : '<font color="red">已关闭</font>';?></td>
			<td><?php echo $val['remote_ip'];?></td>
			<td><?php echo !empty($val['remote_path']) ? $val['remote_path'] : '<i>无</i>';?></td>
			<td><?php echo !empty($val['remote_user']) ? $val['remote_user'] : '<i>无</i>';?></td>
			<td><?php echo $remote_pass_type_arr[$val['remote_pass_type']];?></td>
			<td><?php echo $val['remote_pass_type'] != '3' ? '******' : '<i>无</i>';?></td>
			<td><?php echo !empty($val['remote_comment']) ? $val['remote_comment'] : '<i>无</i>';?></td>
			<td><?php echo $val['remote_time'];?></td>
			<td>
			<a href="index.php?c=AMBmail&check=<?php echo $val['remote_id'];?>" class="button" onclick="return connect_check(this);"><span class="loop icon"></span>发信测试</a>
			<a href="index.php?c=AMBmail&edit=<?php echo $val['remote_id'];?>" class="button"><span class="pen icon"></span>编辑</a>
			<a href="index.php?c=AMBmail&del=<?php echo $val['remote_id'];?>" class="button" onclick="return confirm('确认删除AMBmail远程备份设置ID:<?php echo $val['remote_id'];?>?');"><span class="cross icon"></span>删除</a>
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<button type="button" class="primary button" onclick="WindowLocation('/index.php?c=AMBmail')"><span class="home icon"></span> 返回列表</button>
<button type="button" class="primary button" onclick="WindowOpen('/index.php?c=backup&a=backup_list&category=backup_remote')">查看所有远程设置</button>

<br /><br />

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>

<p>
<?php echo isset($edit_remote) ? '编辑' : '新增';?>AMBmail远程备份设置:<?php echo isset($edit_remote) ? 'ID' . $_POST['remote_id'] : '';?>
</p>
<form action="index.php?c=AMBmail" method="POST"  id="remote_edit"/>
<table border="0" cellspacing="1"  id="STable" style="width:700px;">
	<tr>
	<th> &nbsp; </th>
	<th>值</th>
	<th>说明 </th>
	</tr>

	<tr><td>是否启用	</td>
	<td>
	<select id="remote_status_dom" name="remote_status">
	<option value="1">开启</option>
	<option value="2">关闭</option>
	</select>
	<?php if(isset($_POST['remote_status'])) {?>
	<script>G('remote_status_dom').value = '<?php echo $_POST['remote_status'];?>';</script>
	<?php }?>
	</td>
	<td><p> &nbsp; <font class="red">*</font> 是否启用</p></td>
	</tr>

	<tr><td>Email地址</td>
	<td><input type="text" id="remote_ip" name="remote_ip" class="input_text" value="<?php echo isset($_POST['remote_ip']) ? $_POST['remote_ip'] : '';?>" /></td>
	<td><p> &nbsp; <font class="red">*</font> 备份数据将发送至此地址</p></td>
	</tr>

	<tr><td>说明备注	</td>
	<td><input type="text" name="remote_comment" class="input_text" value="<?php echo $_POST['remote_comment'];?>" /></td>
	<td><p> &nbsp;  添加说明备注</p></td>
	</tr>
	
</table>

<?php if (isset($edit_remote)) { ?>
	<input type="hidden" name="save_edit" value="<?php echo $_POST['remote_id'];?>" />
<?php } else { ?>
	<input type="hidden" name="save" value="y" />
<?php }?>

<button type="submit" class="primary button" name="submit"><span class="check icon"></span>保存</button> 
</form>


<div id="notice_message" style="width:680px;">
<h3>» AMBmail 远程备份</h3>
1) 首先请您确认系统是否已安装邮件软件(Sendmail或Exim、Qmail、Postfix...等)，确认可以正常发信。<br />
2) 系统www用户主目录/home/www不可删除，删除将会影响邮件发送。<br />
3) 支持所有国内外电子邮箱(163、qq、gmail、139…等)<br />
4) amh备份数据AMBmail会自动分卷发送，您将会收到多封邮件，备份文件大小无限制。<br />
5) 使用国内邮箱，每封邮件以49.8MB分卷，使用Gmail、Yahoo邮箱则以24.8MB分卷。<br />
6) 建议您启用邮箱会话功能，同一备份文件而多封数据邮件即可合并显示，方便查阅。 <br />
7) 您也可以启用邮件收件规则，统一分类AMBmail邮件到指定邮件文件夹，方便管理维护。<br />
8) 建议使用网易、139邮箱，收件较快。qq邮箱附件响应较慢，而Gmail收件延时较长。<br />
9) 设置完成后请进行邮件检测(您会收到一封测试邮件)，可测试Email是否能正常收件。<br />

<h3>» SSH AMBmail远程备份</h3>
<ul>
<li>测试Email是否能正常收件: amh module AMBmail-1.0 admin check,[Email-address] </li>
<li>AMBmail所有远程设置连接数据传输: amh module AMBmail-1.0 admin post,AMH-BackupFile-name  </li>
</ul>

</div>

</div>

</div>
<?php include('footer.php'); ?>


