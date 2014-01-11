<?php include('header.php'); ?>
<div id="body">
<?php include('amproxy_category.php'); ?>

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>反代缓存设置:</p>
<form action="./index.php?c=amproxy&a=amproxy_cache" method="POST" >
<table border="0" cellspacing="1"  id="STable" style="width:720px;">
<tr>
<th>名称</th>
<th>值</th>
<th>说明</th>
</tr>

<td>缓存数据目录</td>
<td style="padding:8px">
/home/amproxy_cache
</td>
<td>缓存数据保存的位置</td>
</tr>
<tr>
<td>数据目录层次(levels)</td>
<td><input type="text" name="levels" class="input_text" value="<?php echo $amproxy_cache['levels'];?>" /> <font class="red">*</font>
</td>
<td>缓存数据目录层次<br />允许使用1或2 / 最大3层
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: 1:2)</div>
</td>
</tr>
<tr>
<td>区域内存大小(keys_zone)</td>
<td><input type="text" name="keys_zone" class="input_text" value="<?php echo $amproxy_cache['keys_zone'];?>" /> <font class="red">*</font>
</td>
<td>设置缓存区域内存大小
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: 20m)</div>
</td>
</tr>
<tr>
<td>磁盘空间大小(max_size)</td>
<td><input type="text" name="max_size" class="input_text" value="<?php echo $amproxy_cache['max_size'];?>" /> <font class="red">*</font>
</td>
<td>限制缓存磁盘空间大小
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: 2g)</div>
</td>
</tr>
<tr>
<td>缓存文件有效时间(valid)</td>
<td><input type="text" name="valid" class="input_text" value="<?php echo $amproxy_cache['valid'];?>" /> <font class="red">*</font>
</td>
<td>设置缓存文件有效时间<br />
超过设定时间即重新请求
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: 12h)</div>
</td>
</tr>
<tr>
<td>缓存文件删除时间(inactive)</td>
<td><input type="text" name="inactive" class="input_text" value="<?php echo $amproxy_cache['inactive'];?>" /> <font class="red">*</font>
</td>
<td>缓存文件无新访问之后删除时间<br />
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: 10d)</div>
</td>
</tr>

<tr><th colspan="3" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="save"><span class="check icon"></span>保存</button> 
</th></tr>
</table>
</form>



<div id="notice_message" style="width:880px;">
<h3>» SSH AMProxy 缓存设置</h3>
cache: 设置缓存 (ssh命令: amh module AMProxy-2.0 admin cache,levels,keys_zone,max_size,valid,inactive)<br />
cache 参数说明： <br />
<ul>
<li>levels: 缓存数据目录层次，最多可为3层。示例值: 1:2</li>
<li>keys_zone: 缓存区域内存大小。示例值: 20m</li>
<li>max_size: 限制缓存磁盘空间大小。示例值：2g</li>
<li>valid: 设置缓存文件有效时间，超过设定时间即重新请求。示例值：12h</li>
<li>inactive: 缓存文件删除时间，超过设置的时间内如无新访问请求缓存将自动删除。示例值: 10d</li>
</ul>
start-cache: 域名开启缓存 (ssh命令: amh module AMProxy-2.0 admin start-cache,amysql.com) 启动amysql.com反代网站缓存。<br />
stop-cache: 域名停止缓存  (ssh命令: amh module AMProxy-2.0 admin stop-cache,amysql.com) 停止amysql.com反代网站缓存。<br />

</div>

</div>

<?php include('footer.php'); ?>
