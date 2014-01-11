<?php include('header.php'); ?>
<div id="body">
<?php include('amproxy_category.php'); ?>

<?php
	if (!empty($notice)) echo '<div style="margin:5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>AMProxy反向代理:</p>
<div id="AMProxy_list">
	<table border="0" cellspacing="1"  id="STable" style="width:550px;">
	<tr>
	<th>ID</th>
	<th>域名</th>
	<th>管理</th>
	</tr>
	<?php 
	if(!is_array($amproxy_list) || count($amproxy_list) < 1)
	{
	?>
		<tr><td colspan="3" style="padding:10px;">暂无绑定域名。</td></tr>
	<?php	
	}
	else
	{
		$k = 0;
		foreach ($amproxy_list as $key=>$val)
		{
	?>
			<tr>
			<th class="i"><?php echo ++$k;?></th>
			<td><a href="http://<?php echo $val['name'];?>" target="_blank"><?php echo $val['name'];?></a></td>
			<td>
			<a href="index.php?c=amproxy&run=<?php echo $val['name'];?>&g=<?php echo $val['status'] != 'Stop' ? 'stop' : 'start';?>" >
			<span <?php echo $val['status'] != 'Stop' ? 'class="run_start"' : 'class="run_stop"';?>><?php echo $val['status'] != 'Stop' ? '运行中' : '已停止' ;?></span>
			</a>
			<a href="index.php?c=amproxy&run_cache=<?php echo $val['name'];?>&g=<?php echo $val['cache_status'] != 'Stop' ? 'stop' : 'start';?>" >
			<span <?php echo $val['cache_status'] != 'Stop' ? 'class="run_start"' : 'class="run_stop"';?>><?php echo $val['cache_status'] != 'Stop' ? '有缓存' : '没缓存' ;?></span>
			</a>
			<a href="./index.php?c=amproxy&admin=<?php echo $val['name'];?>" class="button"><span class="cog icon"></span> 管理</a>
			<a href="./index.php?c=amproxy&keyword=<?php echo $val['name'];?>" class="button"><span class="tag icon"></span> 关键字替换</a>
			<a href="./index.php?c=amproxy&del=<?php echo $val['name'];?>" class="button" onclick="return confirm('确认删除域名:<?php echo $val['name'];?>?');"><span class="cross icon"></span> 删除</a>
			</td>
			</tr>
	<?php
		}
	}
	?>
</table>
<button type="button" class="primary button" onclick="WindowLocation('/index.php?c=amproxy')"><span class="home icon"></span> 返回列表</button>
<br />

<!-------------------------------------------------------------------------------------------------------->

<?php if (isset($_GET['admin'])) { ?>
<br />
<form action="./index.php?c=amproxy&admin=<?php echo $_GET['admin'];?>" method="POST" >
<p>编辑反向代理网站:</p>
<table border="0" cellspacing="1"  id="STable" style="width:720px;">
<tr>
<th>名称</th>
<th>值</th>
<th>说明</th>
</tr>

<tr>
<td>绑定域名</td>
<td><input type="text" name="" class="input_text disabled" disabled="" value="<?php echo $amproxy_get['server_name'];?>" /> 
<input type="hidden" name="server_name"  value="<?php echo $amproxy_get['server_name'];?>" />
<font class="red">*</font></td>
<td>绑定的域名，不需加 http://
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: amysql.com)</div>
</td>
</tr>
<tr>
<td>配置路径</td>
<td style="padding:8px">
/usr/local/nginx/conf/proxy/<?php echo $amproxy_get['server_name'];?>.conf
</td>
<td>反代网站配置文件位置</td>
</tr>
<tr>
<td>反代域名</td>
<td><input type="text" name="proxy_pass" class="input_text" value="<?php echo $amproxy_get['proxy_pass'];?>" /> <font class="red">*</font>
<input type="hidden" name="proxy_pass_hidden" class="input_text" value="<?php echo $amproxy_get['proxy_pass'];?>" />
</td>
<td>反代的网站
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: http://nginx.org)</div>
</td>
</tr>
<tr>
<td>Referer定义</td>
<td><input type="text" name="Referer" class="input_text" value="<?php echo $amproxy_get['Referer'];?>" /> <font class="red">*</font>
<input type="hidden" name="Referer_hidden" class="input_text" value="<?php echo $amproxy_get['Referer'];?>" />
</td>
<td>定义来源网站
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: http://nginx.org)</div>
</td>
</tr>
<tr>
<td>Host定义</td>
<td><input type="text" name="Host" class="input_text" value="<?php echo $amproxy_get['Host'];?>" /> <font class="red">*</font>
<input type="hidden" name="Host_hidden" class="input_text" value="<?php echo $amproxy_get['Host'];?>" />
</td>
<td>定义主机的网站
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: nginx.org)</div>
</td>
</tr>
<tr>
<td>替换文件类型</td>
<td><input type="text" name="subs_filter_types" class="input_text" value="<?php echo $amproxy_get['subs_filter_types'];?>" /> <font class="red">*</font>
<input type="hidden" name="subs_filter_types_hidden" class="input_text" value="<?php echo $amproxy_get['subs_filter_types'];?>" />
</td>
<td>指定类型的文件内容替换
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: text/html,text/css,text/xml)</div>
</td>
</tr>
<tr>
<td>自定义头部HTML</td>
<td>
<textarea name="AppendHtml_header">
<?php echo $amproxy_get['AppendHtml_header'];?>
</textarea>
<textarea name="AppendHtml_header_hidden" style="display:none">
<?php echo $amproxy_get['AppendHtml_header'];?>
</textarea>
</td>
<td>自定义头部HTML代码
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: header_string)</div>
</td>
</tr>
<tr>
<td>自定义底部HTML</td>
<td>
<textarea name="AppendHtml_footer">
<?php echo $amproxy_get['AppendHtml_footer'];?>
</textarea>
<textarea name="AppendHtml_footer_hidden" style="display:none">
<?php echo $amproxy_get['AppendHtml_footer'];?>
</textarea>
</td>
<td>自定义低部HTML代码
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: footer_string)</div>
</td>
</tr>
<tr><th colspan="3" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="edit"><span class="check icon"></span>保存</button> 
<a href="./index.php?c=amproxy">取消返回</a>
</th></tr>
</table>
</form>

<?php } elseif (isset($_GET['keyword'])) { ?>
<!-------------------------------------------------------------------------------------------------------->
<br /><br />
<form action="./index.php?c=amproxy&keyword=<?php echo $_GET['keyword'];?>" method="POST" >
<p><?php echo $_GET['keyword'];?>: 关键字替换管理:</p>
<table border="0" cellspacing="1" class="AMProxy_keyword"  id="STable" style="width:90%;">
<tr>
<th>ID</th>
<th>查找字符</th>
<th>替换值</th>
<th>编码</th>
<th width="200">修饰符</th>
<th>管理</th>
</tr>

<?php 
	if(!is_array($keyword_list) || count($keyword_list) < 1)
	{
	?>
		<tr><td colspan="6" style="padding:10px;">暂无关键字设置。</td></tr>
	<?php	
	}
	else
	{
		$i = 1;
		foreach ($keyword_list as $val)
		{
	?>
			<tr>
			<th class="i"><?php echo $i;?></th>
			<td><?php echo $val[4][1];?></td>
			<td><?php echo (empty($val[4][2]) || $val[4][2] == ' ') ? '<i>空</i>' : $val[4][2];?></td>
			<td><?php echo $val[4][0];?></td>
			<td><?php echo $val[3];?></td>
			<td>
			<script>
			var notice<?php echo $i;?> = <?php echo json_encode('确认删除关键字:' . htmlspecialchars_decode($val[4][1]) . '?');?>
			</script>
			<a href="./index.php?c=amproxy&keyword=<?php echo $_GET['keyword'];?>&del_keyword=<?php echo urlencode(htmlspecialchars_decode($val[1]));?>" class="button" onclick="return confirm(notice<?php echo $i;?>);"><span class="cross icon"></span> 删除</a>
			</td>
			</tr>
	<?php
		++$i;
		}
	}
?>
<tr>
<th class="i">+</th>
<td><input type="text" name="find_str" class="input_text" style="width:160px"/></td>
<td><input type="text" name="replace_str" class="input_text" style="width:160px"/></td>
<td style="text-align:center" width="70">
<input type="radio" name="str_char" value="utf8" checked="" id="_utf8"/> <label for="_utf8" title="utf8" style="width:20px"> utf8</label><br />
<input type="radio" name="str_char" value="gbk" id="_gbk"/> <label for="_gbk" title="gbk" style="width:20px"> gbk</label>
</td>
<td>
<style>
label {
width: 60px;
display: inline-block;
text-align: left;
margin-left: 5px;
}
</style>
<input type="checkbox" name="_g" id="_g" /><label for="_g" title="全局替换"> 全局替换</label> 
<input type="checkbox" name="_i" id="_i" /><label for="_i" title="区分大小写"> 区分大小写</label><br />
<input type="checkbox" name="_o" id="_o" /><label for="_o" title="只替换第一个"> 首个替换</label> 
<input type="checkbox" name="_r" id="_r" /><label for="_r" title="使用正则替换"> 正则替换</label>
<td>+</td>
</td>

</tr>
<tr><th colspan="6" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="add_keyword"><span class="check icon"></span>添加</button> 
<a href="./index.php?c=amproxy">取消返回</a>
</th></tr>
</table>
</form>


<?php } else { ?>
<!-------------------------------------------------------------------------------------------------------->

<br />
<form action="./index.php?c=amproxy" method="POST" >
<p>新增AMProxy反向代理网站:</p>
<table border="0" cellspacing="1"  id="STable" style="width:660px;">
<tr>
<th>名称</th>
<th>值</th>
<th>说明</th>
</tr>

<tr>
<td>绑定域名</td>
<td><input type="text" name="server_name" class="input_text" /> <font class="red">*</font></td>
<td>绑定的域名，不需加 http://
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: amysql.com)</div>
</td>
</tr>
<tr>
<td>反代域名</td>
<td><input type="text" name="proxy_pass" class="input_text" /> <font class="red">*</font></td>
<td>反代的网站, 不需加 http://
<div style="font-size:11px;color:#848484;margin:5px;">(e.g: nginx.org)</div>
</td>
</tr>
<tr><th colspan="3" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="submit"><span class="check icon"></span>保存</button> 
</th></tr>
</table>
</form>
</div>
<?php } ?>

<div id="notice_message" style="width:880px;">
<h3>» SSH AMProxy</h3>
1) 有步骤提示操作: <br />
ssh执行命令: amh module AMProxy-2.0<br />
然后选择对应的操作选项进行管理。<br />
<br />
2) 或直接操作: <br />
<ul>
<li>AMProxy管理: amh module AMProxy-2.0 [info / install / admin / uninstall / status]</li>
</ul>

3) AMProxy admin管理选项说明：
<br />执行 amh module AMProxy-2.0 admin 提示输入管理参数(例如, list、add,amysql.com,nginx.org等)，可以不输入直接回车显示下面选项菜单。
<ul>
<li>list: 列出反向代理网站列表 (ssh命令: amh module AMProxy-2.0 admin list)</li>
<li>add: 新增反向代理网站 (ssh命令: amh module AMProxy-2.0 admin add,amysql.com,nginx.org) amysql,com为绑定域名，nginx.org为反代域名。</li>
<li>start: 启动反向代理网站  (ssh命令: amh module AMProxy-2.0 admin start,amysql.com) 启动amysql.com反代网站。</li>
<li>stop: 停止反向代理网站  (ssh命令: amh module AMProxy-2.0 admin stop,amysql.com) 停止amysql.com反代网站。</li>
<li>edit: 编辑反向代理网站 (ssh命令: amh module AMProxy-2.0 admin edit,amysql.com,参数名,参数值,参数值...) amysql.com为绑定域名，<br />其它参数名与值可参考提示使用。</li>
<li>del: 删除反向代理网站  (ssh命令: amh module AMProxy-2.0 admin del,amysql.com) 删除绑定amysql.com反代网站。</li>
</ul>

4) 替换字符只允许单行替换、命令操作需注意各特殊符号转义，例如替换&lt;hr&gt;为line： amh module AMProxy-2.0 admin 'edit,test.com,subs_filter,add,\&lt;hr\&gt;,line,g'
<br />
5) 更多使用帮助与新版本支持或问题反馈，请联系AMH官方网站。
</div>
</div>

</div>
<?php include('footer.php'); ?>


