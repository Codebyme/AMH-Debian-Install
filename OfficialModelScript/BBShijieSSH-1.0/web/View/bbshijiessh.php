<?php include('header.php'); ?>
<style>
#STable td.td_block {
	padding:10px 20px;
	text-align:left;
	line-height:23px;
}
.ssh_list {
	margin:1px;
}
</style>

<div id="body">
	<h2>AMH » BBShijieSSH</h2>
<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p style="display:inline-block" id="' . $status . '">' . $notice . '</p></div>';
?>
<p>爬墙SSH帐号管理:</p>
<div id="ssh_list">
	<table border="0" cellspacing="1"  id="STable" style="width:660px;">
	<tr>
	<th>ID</th>
	<th>用户名</th>
	<th>密码</th>
	<th>管理</th>
	</tr>
	<?php 
	if(!is_array($ssh_list) || count($ssh_list) < 1)
	{
	?>
		<tr><td colspan="4" style="padding:10px;">暂无爬墙SSH帐号。</td></tr>
	<?php	
	}
	else
	{
		$i = 0;
		foreach ($ssh_list['data'] as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['sid'];?></th>
			<td>tizi<?php echo $val['username'];?></td>
			<td><?php echo $val['password'];?></td>
			<td>
			<a href="./index.php?c=bbshijiessh&sid=<?php echo $val['sid'];?>" class="button"><span class="pen icon"></span> 修改密码</a> 
			<a href="./index.php?c=bbshijiessh&del=<?php echo $val['sid'];?>" class="button"><span class="cross icon"></span>删除</a>
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<div id="page_list">总<?php echo $total_page;?>页 - <?php echo $ssh_list['sum'];?>记录 » 页码 <?php echo htmlspecialchars_decode($page_list);?> </div>


<br /><br /><br />
<form action="./index.php?c=bbshijiessh" method="POST" >
<p><?php echo isset($_GET['sid']) ? '修改' : '添加'; ?>SSH帐号:</p>
<table border="0" cellspacing="1"  id="STable" style="width:660px;">
<tr>
<th colspan="2">SSH爬墙专用帐号</th>
</tr>

<tr>
<td>用户名</td>
<td>
<?php if (isset($_GET['sid'])) { ?>
	<!-- 编辑保存 -->
	tizi<input type="text" name="user" value="<?php echo $ssh['username'];?>" class="input_text disabled" disabled />
	<input type="hidden" name="sid" value="<?php echo $_GET['sid'];?>" />
<?php } else {?>
	tizi<input type="text" name="user" value="" class="input_text" />
<?php } ?>
&nbsp; 1-10位字母、数字</td>
</tr>

<tr>
<td>密码</td>
<td>
	<input type="password" name="pass" value="" class="input_text" />
&nbsp; 6-15位字母、数字</td>
</tr>

<tr><th colspan="2" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="<?php echo isset($_GET['sid']) ? 'save' : 'add';?>"><span class="check icon"></span><?php echo isset($_GET['sid']) ? '保存' : '新增';?></button> 
</th></tr>
</table>
</form>
</div>



<div id="notice_message" style="width:660px;">
<h3>爬墙专用SSH帐号</h3>
1) 只对OpenSSH有效，如果您更改了系统的SSH服务器（如Dropbear），请勿使用本模块。<br />
2) 模块修改了SSH配置，已登录SSH帐号20分钟无动作将自动掉线。如果您不需要自动掉线，请删除/etc/ssh/sshd_config中的#BBShijieSSH段落。<br />
3）模块添加了任务计划，禁止同一帐号多点登录。如果您需要多点登录，请删除计划任务中的bbshijiessh行。<br />
4）添加的帐号仅能用于爬墙，无法登录服务器进行其他操作。<br />
5）为防止用户名和操作系统中已存在的用户冲突，自动在用户名前添加了字符"tizi"。<br />
6）卸载本模块，将删除已添加的SSH帐号、还原SSH配置、还原计划任务。<br />
</div>


</div>
<?php include('footer.php'); ?>