<?php include('header.php'); ?>
<div id="body">
<h2>AMH » AMPathinfo</h2>

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>虚拟主机列表:</p>
<div id="AMPathinfo_list">
	<table border="0" cellspacing="1"  id="STable" style="width:600px;">
	<tr>
	<th>ID</th>
	<th>主标识域名</th>
	<th>是否开启PATHINFO</th>
	</tr>
	<?php 
	if(!is_array($AMPathinfo_list) || count($AMPathinfo_list) < 1)
	{
	?>
		<tr><td colspan="3" style="padding:10px;">暂无虚拟主机。</td></tr>
	<?php	
	}
	else
	{
		foreach ($AMPathinfo_list as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $key+1;?></th>
			<td><?php echo $val[0];?></td>
			<td>
			<a href="./index.php?c=AMPathinfo&domain=<?php echo $val[0];?>&mode=open"><span class="<?php echo $val[1] ? 'run_start' : '';?>" >开启</span></a>
			<a href="./index.php?c=AMPathinfo&domain=<?php echo $val[0];?>&mode=close"><span class="<?php echo $val[1] ? '' : 'run_start';?>" >关闭</span></a>
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<br />
<button type="button" class="primary button" onclick="WindowLocation('./index.php?c=AMPathinfo')"><span class="home icon"></span> 首页</button>

<div id="notice_message" style="width:880px;">
<h3>» AMPathinfo 使用说明</h3>
1) 开启：虚拟主机支持PATHINFO。<br />
2) 关闭：虚拟主机不支持PATHINFO。<br />
<h3>» SSH AMPathinfo</h3>
1) 有步骤提示操作: <br />
ssh执行命令: amh module AMPathinfo-1.0<br />
然后选择对应的操作选项进行管理。<br />
<br />
2) 或直接操作: <br />
<ul>
<li>AMPathinfo管理: amh module AMPathinfo-1.0 [info / install / admin / uninstall / status]</li>
<li>虚拟主机运行列表: amh module AMPathinfo-1.0 admin list</li>
<li>编辑虚拟主机是否开启PATHINFO: amh module AMPathinfo-1.0 admin edit,domain.com,open</li>
</ul>

3) AMPathinfo admin管理选项说明：
<br />执行 amh module AMPathinfo-1.0 admin 提示输入管理参数(list,edit)，可以不输入直接回车显示提示选项进行操作。
<br />
4) 更多使用帮助与新版本支持或问题反馈，请联系AMH官方网站。
</div>
</div>
</div>
<?php include('footer.php'); ?>


