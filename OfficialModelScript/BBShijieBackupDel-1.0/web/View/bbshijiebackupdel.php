<?php include('header.php'); ?>
<style>
#STable td.td_block {
	padding:10px 20px;
	text-align:left;
	line-height:23px;
}
</style>

<div id="body">
<?php 
	$c_name = 'backup';
	include('category_list.php'); 
?>

<?php !defined('_Amysql') && exit; ?>
<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p style="display:inline-block" id="' . $status . '">' . $notice . '</p></div>';
?>
<p>保留天数备份:</p>
<table border="0" cellspacing="1"  id="STable" style="width:660px;">
	<tr>
	<th>保留最近多少天的备份文件</th>
	</tr>
	<tr>
	<td class="td_block">
	<form action="index.php?c=bbshijiebackupdel&a=backup_list&category=bbshijiebackup_del" method="POST"  id="backup_del" />
	备份文件保留天数<br />
	<input type="text" class="input_text" name="backup_del_days" value="<?php echo isset($_POST['backup_del_days']) ? $_POST['backup_del_days'] : '5';?>" /> 默认5天 <br /> 
	<button type="submit" class="primary button" name="save"><span class="check icon"></span>保存</button> 
	</form>
	</td>
	</tr>
</table>
<br />

<div id="notice_message" style="width:660px;">
<h3>保留天数</h3>
1) 设置保留最近多少天的文件，如设置7，今天30号，那么24号之前的备份文件将被删除。<br />
2) 默认不会删除备份文件，需要调用命令。<br />

使用示例:<br />
amh module BBShijieBackupDel-1.0 admin 参数<br />
参数：dellocal / delftp / delall<br />
参数说明：dellocal 删除本地备份、delftp 删除远程FTP备份、delall 删除本地和远程FTP备份<br />
</div>


</div>
<?php include('footer.php'); ?>