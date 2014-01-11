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
<script>
var cache_host;
var cache_key;
var cache_host_js = <?php echo json_encode($cache_host);?>;
window.onload = function ()
{
	cache_host = G('cache_host');
	cache_key = G('cache_key');
	cache_host.onchange = function ()
	{
		
		for (var k in cache_host_js)
		{
			var reg = new RegExp('http://' + cache_host_js[k], "gmi");
			cache_key.value = cache_key.value.replace(reg,'');
		}
		if (cache_host.value != '')
			cache_key.value = 'http://' + cache_host.value + cache_key.value + (cache_key.value == '' ? '%' : '');
		cache_key.value = cache_key.value == '%' ? '' : cache_key.value;
	}
}
var cache_delete_submit = function ()
{
	if (!confirm('删除当前搜索结果(缓存索引&缓存文件) <?php echo $amproxy_cache_list['sum'];?> 项吗?'))
		return false;
	G('cache_delete_button').innerHTML = '删除缓存中…';
	G('cache_delete_button').disabled = true;
	return true;
}
</script>
<div id="body">
<?php include('amproxy_category.php'); ?>

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>

<div>
<p>搜索查看缓存与删除</p>
<form action="" method="GET">
<input type="hidden" value="amproxy_cache_del" name="a"/>
<input type="hidden" value="amproxy" name="c"/>
索引域名 <select name="cache_host" id="cache_host" style="width:180px;">
<?php if (is_array($cache_host))
{
	foreach ($cache_host as $key=>$val)
	{
?>
	<option value="<?php echo $val;?>"><?php echo $val;?></option>
<?php		
	}
}
?>
<option value="">所有</option>
</select> &nbsp; 
<script>G('cache_host').value = '<?php echo isset($_GET['cache_host']) ? $_GET['cache_host'] : '';?>';</script>
网址匹配 (可使用%通配符)  <input type="text" name="cache_key" id="cache_key" class="input_text" value="<?php echo isset($_GET['cache_key']) ? $_GET['cache_key'] : '';?>" style="width:250px;"/> &nbsp; 
文件类型 <select name="cache_type" id="cache_type" style="width:180px;">
<?php if (is_array($cache_type))
{
	foreach ($cache_type as $key=>$val)
	{
		if($val != 'all') {
?>
	<option value="<?php echo $val;?>"><?php echo $val;?></option>
<?php		
		}
	}
}
?>
<option value="all">所有</option>
</select> &nbsp; 
<script>G('cache_type').value = '<?php echo isset($_GET['cache_type']) ? $_GET['cache_type'] : all;?>';</script>
<button type="submit" class="primary button" >搜索</button> 
</form>
</div>

<style>
#STable td {
	text-align:left;
	padding-left:10px;
}
</style>
<div id="AMProxy_list">
	<table border="0" cellspacing="1"  id="STable" style="width:1150px;">
	<tr>
	<th>编号</th>
	<th>缓存文件</th>
	<th width="380">网址</th>
	<th>状态码</th>
	<th>文件类型</th>
	<th>大小 / MB</th>
	<th>创建时间</th>
	</tr>
	<?php 
	if(!is_array($amproxy_cache_list['data']) || count($amproxy_cache_list['data']) < 1)
	{
	?>
		<tr><td colspan="7" style="padding:10px;text-align:center;">没找到缓存索引数据。</td></tr>
	<?php	
	}
	else
	{
		$k = 0;
		foreach ($amproxy_cache_list['data'] as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo $val['amproxy_id'];?></th>
			<td><?php echo $val['amproxy_file'];?></td>
			<td><a href="<?php echo $val['amproxy_key'];?>" target="_blank"><?php echo $val['amproxy_key'];?></a></td>
			<td><?php echo $val['amproxy_http_s'];?></td>
			<td><?php echo $val['amproxy_type'];?></td>
			<td><?php echo !empty($val['amproxy_size']) ? $val['amproxy_size'] : 0;?></td>
			<td><?php echo $val['amproxy_time'];?></td>
			</tr>
	<?php
		}
	}
	?>
<tr>
<td colspan="7" class="object_list_last">
<form action="" method="POST"  onsubmit="return cache_delete_submit()">
删除当前搜索结果(缓存索引&缓存文件) <?php echo $amproxy_cache_list['sum'];?> 项
<button type="submit" class="primary button"  id="cache_delete_button"> 确认删除</button>
<input type="hidden" name="post_delete" value="y"/>
<input type="hidden" value="<?php echo $_GET['cache_type'];?>" name="cache_type"/>
<input type="hidden" value="<?php echo $_GET['cache_key'];?>" name="cache_key"/>
</form>
</td>
</tr>
</table>
<div id="page_list">总<?php echo $total_page;?>页 - <?php echo $amproxy_cache_list['sum'];?>记录 » 页码 <?php echo htmlspecialchars_decode($page_list);?> </div>
<button type="button" class="primary button" onclick="WindowLocation('/index.php?c=amproxy&a=amproxy_cache_del')"><span class="home icon"></span> 返回列表</button>

<div id="notice_message" style="width:880px;">
<h3>» SSH AMProxy 缓存删除</h3>
cache-delete: 缓存删除 (ssh命令: amh module AMProxy-2.0 admin cache-delete,url-param,file-type)<br />
cache-delete 参数说明： <br />
<ul>
<li>url-param: 网址匹配参数，可使用%通配符进行匹配删除。预设参数值有: -all (使用-all即匹配所有网址)</li>
<li>file-type: 缓存文件类型匹配参数，可匹配text/html、image/gif等相关文件类型进行删除。预设参数值有: -all (使用-all即匹配所有文件类型)</li>
</ul>

</div>
</div>

</div>
<?php include('footer.php'); ?>


