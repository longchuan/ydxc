<?php
/* Smarty version 3.1.30, created on 2018-08-25 06:24:20
  from "/usr/share/nginx/html/ydxc/ydxc/template/wap/my_tj_code.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5b80f614be7628_31169811',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd9db903fdf6e624054e31a3e6921f3a0de56633d' => 
    array (
      0 => '/usr/share/nginx/html/ydxc/ydxc/template/wap/my_tj_code.html',
      1 => 1534741554,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:header.html' => 1,
  ),
),false)) {
function content_5b80f614be7628_31169811 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

	<body>
		<!--[if lt IE 9]>对不起，浏览器版本太低~！<![endif]-->
		<div class="container">
			<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tj_news']->value, 'item');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
?>
			<div class="row info">
				<p class="col-xs-12"><?php echo date("Y-m-d H:i:s",$_smarty_tpl->tpl_vars['item']->value['inputtime']);?>
</p>
				<div class="col-xs-12">
					<p><?php echo $_smarty_tpl->tpl_vars['item']->value['content'];?>
</p>
				</div>
			</div>
			<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

		</div>
		
	</body>
</html>
<?php }
}
