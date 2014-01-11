<?php include('header.php'); ?>
<style>
#STable td.object_list {
	background:url("View/images/Listbj01.gif") repeat-x scroll left top white;
	padding:20px;
}
#STable td.object_list_last {
	background:url("View/images/Listbj.gif") repeat-x scroll left top white;
	padding:8px;
	text-align:right;
	line-height:18px;
	color:#607993;
}
#STable td.object_list_last font{
	font-size:14px;
}
#STable td.object_name, #STable th.object_name {
	text-align:left;
	padding-left:22px;
}
#STable .Object_list {
	width:930px;
	background:none;
	border:1px solid #E7E7E7;
}
#STable .Object_list td {
	border-bottom:1px solid #E0E0E0;
}
</style>
<script>
var cache_index_submit = function ()
{
	G('cache_index_button').innerHTML = '创建索引中…';
	G('cache_index_button').disabled = true;
}
</script>
<div id="body">
<?php include('amproxy_category.php'); ?>

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>反代缓存索引:</p>
<table border="0" cellspacing="1"  id="STable" style="width:720px;">
<tr>
<th>索引域名</th>
<th>总缓存数量</th>
<th>总使用空间</th>
</tr>

<?php 
$host_sum = 0;
$file_sum = 0;
$file_size = 0;
foreach ($amproxy_cache_index as $key=>$val)
{
	$file_sum += $val['all']['sum'];
	$file_size += $val['all']['size'];
	++$host_sum;
?>
<tr>
	<td><a href="http://<?php echo $key;?>" class="button" target="_blank"><span class="home icon"></span>主页</a> <?php echo $key;?></td>
	<td style="padding:8px">总缓存文件 <b><?php echo $val['all']['sum'];?></b> 个</td>
	<td>总使用空间 <b><?php echo $val['all']['size'];?></b> MB</td>
</tr>
<tr>
<td colspan="3" class="object_list">
<table border="0" cellspacing="0"  id="STable" style="width:580px;" class="Object_list">
<tr>
<th class="object_name">缓存文件类型</th>
<th>缓存数量</th>
<th>使用空间</th>
</tr>
<?php 
foreach ($val as $k=>$v)
{
	if($k != 'all')
	{
?>
<tr>
	<td class="object_name"><?php echo $k;?></td>
	<td style="padding:8px">缓存文件 <b><?php echo $v['sum'];?></b> 个</td>
	<td>使用空间 <b><?php echo $v['size'];?></b> MB</td>
</tr>
<?php
	}
}
?>
</table>
</td>
</tr>

<?php
}
?>
<tr>
	<td colspan="3" class="object_list_last"><font>共索引域名 <b><?php echo $host_sum;?></b> 个： </font>
	全部缓存文件 <b><?php echo $file_sum;?></b> 个  / 
	全部缓存文件已用 <b><?php echo $file_size;?></b> MB</td>
</tr>
</table>

<br /><br />
<form action="./index.php?c=amproxy&a=amproxy_cache_index" method="POST" id="cache_index_form" onsubmit="return cache_index_submit()">
<p>建立缓存索引:</p>
<table border="0" cellspacing="1"  id="STable" style="width:560px;">
<tr>
<th></th>
<th>时间单位</th>
<th>时间范围</th>
<th>使用模式</th>
</tr>
<tr>
<td><input type="text" name="cache_index_time_val" class="input_text" value="<?php echo isset($_POST['cache_index_time_val']) ? $_POST['cache_index_time_val'] : 2;?>" style="width:50px;"/></td>
<td><select name="cache_index_time_type" id="cache_index_time_type" style="width:110px;">
<option value="1">天</option>
<option value="2">小时</option>
<option value="3">分钟</option>
</select>
<script> 
	G('cache_index_time_type').value = '<?php echo isset($_POST['cache_index_time_type']) ? $_POST['cache_index_time_type'] : 1;?>';
</script>
</td>
<td><select name="cache_index_time_area" id="cache_index_time_area" style="width:110px;">
<option value="1">之内</option>
<option value="2">之外</option>
</select>
<script> 
	G('cache_index_time_area').value = '<?php echo isset($_POST['cache_index_time_area']) ? $_POST['cache_index_time_area'] : 1;?>';
</script>
</td>
<td><select name="cache_index_time_mode" id="cache_index_time_mode" style="width:110px;">
<option value="1">更新与追加</option>
<option value="2">清空后重建</option>
</select>
<script> 
	G('cache_index_time_mode').value = '<?php echo isset($_POST['cache_index_time_mode']) ? $_POST['cache_index_time_mode'] : 1;?>';
</script>
</td>
</tr>

</table>
<input type="hidden" name="post_submit" value="y"/>
<button type="submit" class="primary button"  id="cache_index_button"><span class="check icon"></span>建立索引</button> 
</form>


<div id="notice_message" style="width:880px;">
<h3>» SSH AMProxy 缓存索引</h3>
cache-index: 设置缓存 (ssh命令: amh module AMProxy-2.0 admin cache-index,cmin,mode)<br />
cache-index 参数说明： <br />
<ul>
<li>cmin: 索引缓存文件使用的时间，单位为分钟。可使用：+分钟 / -分钟</li>
<li>+分钟: 索引N分钟之前的缓存数据。</li>
<li>-分钟: 索引N分钟之内的缓存数据。</li>
<li>mode: 索引数据模式，可选参数。可用值：truncate (清空模式，清空旧索引数据后再建立索引)
</ul>

</div>

</div>

<?php include('footer.php'); ?>
