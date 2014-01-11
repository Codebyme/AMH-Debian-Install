<?php include('header.php'); ?>
<style>
#body input.input_text {
	width: 222px;
}
</style>
<div id="body">
<h2>AMH » AMAPI</h2>

<?php
	if (!empty($notice)) echo '<div style="5px 2px;width:500px;"><p id="' . $status . '">' . $notice . '</p></div>';
?>
<p>AMAPI管理设置</p>
<form action="./index.php?c=amapi" method="POST" >
<table border="0" cellspacing="1"  id="STable" style="width:720px">
<tr>
<th>名称</th>
<th>值</th>
<th>生成时间</th>
<th>说明</th>
</tr>

<tr>
<td>私钥</td>
<td><input type="text" name="" class="input_text disabled" disabled="" value="<?php echo $getpass['AMAPI_PASSA']['config_value'];?>" /> 
<font class="red"></font></td>
<td><?php echo $getpass['AMAPI_PASSA']['config_time'];?></td>
<td>AMAPI接口私钥
<div style="font-size:11px;color:#848484;margin:5px;">(用于接口验证)</div>
</td>
</tr>
<tr>
<td>密钥</td>
<td><input type="text" name="" class="input_text "  value="<?php echo $getpass['AMAPI_PASSB']['config_value'];?>" /> 
<font class="red"></font></td>
<td><?php echo $getpass['AMAPI_PASSB']['config_time'];?></td>
<td>AMAPI接口密钥
<div style="font-size:11px;color:#848484;margin:5px;">(调用接口时使用，请不要泄露他人)</div>
</td>
</tr>
<tr><th colspan="4" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="uppass"><span class="check icon"></span>更新密钥</button> 
</th></tr>
</table>
</form>

<br /><br />
<p>允许调用来源IP设置</p>
<form action="./index.php?c=amapi" method="POST" >
<table border="0" cellspacing="1"  id="STable" style="width:670px">
<tr>
<th>IP地址</th>
<th>添加时间</th>
<th>管理</th>
</tr>

<?php foreach ($iplist as $key=>$val) { ?>
	
<tr>
<td><?php echo $val['config_value'];?>
<font class="red"></font></td>
<td><?php echo $val['config_time'];?></td>
<td>
<a href="./index.php?c=amapi&delip=<?php echo $val['config_value'];?>" class="button" onclick="return confirm('确认删除IP:<?php echo $val['config_value'];?>?');"><span class="cross icon"></span> 删除</a>
</td>
</tr>

<?php } ?>
<tr>
<td><input type="text" name="ip" class="input_text "  value="" /> 
<font class="red"></font></td>
<td><?php echo date('Y-m-d H:i:s', time());?></td>
<td>添加允许调用IP
<div style="font-size:11px;color:#848484;margin:5px;">(无设置允许调用来源IP即所有IP都允许访问)</div>
</td>
</tr>
<tr><th colspan="4" style="padding:10px;text-align:left;">
<button type="submit" class="primary button" name="addip"><span class="check icon"></span>添加</button> 
</th></tr>
</table>
</form>


<div id="notice_message" style="width:880px;">
<h3>» AMAPI 使用说明</h3>
1) 调用AMAPI接口允许执行AMH所有命令。 <br />
2) 每执行一命令系统都有日志记录，如发现异常或密钥泄露请更新密钥生成新密钥，避免非法调用。 <br />
3) 建议添加调用IP限制，允许可信任来源IP地址调用AMAPI接口，如无添加即不做IP限制。 <br />
 <br />

4) AMAPI 调用说明
<ul>
<li>AMP接口调用地址: http://ip:端口号/index.php?c=amapi&a=call</li>
<li>必须以POST方式提交数据，提交两个参数amapi_pass与amh_cmd。</li>
<li>amapi_pass为密钥，从模块管理中获取。</li>
<li>amh_cmd为AMH命令，AMH命令需使用base64_encode函数转码后提交。</li>

</ul>

5) 调用例子或更多帮助说明、问题反馈请联系AMH官方网站。 <br />

</div>


</div>
<?php include('footer.php'); ?>


