<?php !defined('_Amysql') && exit; ?>
<style>
#AMProxy_list input.input_text {
	width: 292px;
}
#AMProxy_list textarea {
	display:inline;
}
</style>
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
<h2>AMH » AMProxy</h2>
<div id="category">
<a href="index.php?c=amproxy&a=amproxy_list" id="amproxy_list" >反代列表</a>
<a href="index.php?c=amproxy&a=amproxy_cache" id="amproxy_cache">缓存设置</a>
<a href="index.php?c=amproxy&a=amproxy_cache_index" id="amproxy_cache_index">缓存索引</a>
<a href="index.php?c=amproxy&a=amproxy_cache_del" id="amproxy_cache_del">缓存删除</a>
<script>
var action = '<?php echo $_GET['a'];?>';
var action_dom = G(action) ? G(action) : G('amproxy_list');
action_dom.className = 'activ';
</script>
</div>