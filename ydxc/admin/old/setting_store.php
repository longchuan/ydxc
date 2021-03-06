<?php
/*
	[TOUR] (C) 2007-2009 
	$Id: store.php 2009-6-14 17:54:52 jerry $
*/

!defined('IN_CTB') && die('Access Denied');

//权限检查
checkpermission(); //权限检查
loadcache('store'  ,'id', '', 'orderlist', 1);   // 加载缓存树状栏目

include_once(S_ROOT.'./lib/function/function_cache.php');
			 
if (isset($_POST['btnsubmit'])) {
	$aryid = simplode($_POST['itemid']);
	
	//判断是不是有子仓库
    $query = $_TGLOBAL['db']->query("SELECT NULL FROM ".tname('store')." WHERE pid IN($aryid)");									
    while ($value = $_TGLOBAL['db']->fetch_array($query)) {
        showmsg('存在子仓库，不能直接删除，请先删除子仓库！', $_TGLOBAL['refer']);
    } 
	
	//判断有没有垛位
    $query = $_TGLOBAL['db']->query("SELECT NULL FROM ".tname('crib')." WHERE storeid IN($aryid)");									
    while ($value = $_TGLOBAL['db']->fetch_array($query)) {
        showmsg('存在垛位，不能直接删除，请先删除垛位！', $_TGLOBAL['refer']);
    } 
	
	// 删除储存的二维码
    $query = $_TGLOBAL['db']->query("SELECT barcode FROM ".tname('store')." WHERE id IN($aryid)");									
    while ($value = $_TGLOBAL['db']->fetch_array($query)) {
        $_TGLOBAL['db']->query("DELETE FROM ".tname('barcode')." WHERE barcode = '$value[barcode]' AND itype=1 ");
    }
	
	$_TGLOBAL['db']->query("DELETE FROM ".tname('store')." WHERE id IN($aryid)");
	makecache('store','id', '', 'orderlist',1); //缓存
	showmsg('删除仓库成功！', $_TGLOBAL['refer']);
}

//排序方式
$field  = isset($_GET['field']) ? $_GET['field'] : 'display';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC' ;

$class_id = isset($_GET['class_id']) ? $_GET['class_id'] : '';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>TOUR V1.0</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK media=screen href="images/admin.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript src="js/common.js"></SCRIPT>
</HEAD>
<BODY onLoad="return write_nav('<?php echo $_TGLOBAL['menu']['main'][$ac]; ?> &raquo; <a href=\'module.php?ac=<?php echo $ac; ?>&mod=<?php echo $mod; ?>\' target=\'main\'><?php echo $_TGLOBAL['menu'][$ac][$mod]; ?></a> &raquo; ');">
<DIV class=datacontainer2 style="WIDTH: 97%">
  <DIV class=header><a href="module.php?ac=setting&mod=store_add" class="MustFill">添加新的仓库</a> <?php echo getclasscotename('news_class', $class_id); ?></DIV>
  <form  method="post" name="FormEdit">
    <table border="0" align="center" cellpadding="0" cellspacing="0" class="tab_01">
      <tbody>
        <tr>
          <td class="caption"><div align="center">选择</div></td>
          <td class="caption"><div align="center">仓库名称</div></td>
          <td class="caption"><div align="center">条形码编号</div></td>
          <!--<td class="caption"><div align="center">父级仓库</div></td>-->
          <!--<td class="caption"><div align="center">显示顺序</div></td>
          <td class="caption"><div align="center">是否显示</div></td>
          <td class="caption"><div align="center">显示方式</div></td>-->
          <td class="caption"><div align="center">创建人</div></td>
          <td class="caption"><div align="center">创建时间</div></td>
          <td class="caption"><div align="center">修改</div></td>
        </tr>
        <?php
foreach($_TGLOBAL['tree_store'] as $key=>$value){
	$html = '';
	if($value['sort']){
		$html = '&nbsp;&nbsp;'.$value['html'];
	}
?>
        <tr bgcolor="<?php echo $bgcolor = (isset($bgcolor) && $bgcolor == '#FFFFFF') ? '#F1F5FE' : '#FFFFFF'; ?>" onMouseOver="if (this.bgColor != '<?php echo $_TCONFIG['table_tr_click']; ?>') this.bgColor='#E4EAF2';" onMouseOut="if (this.bgColor != '<?php echo $_TCONFIG['table_tr_click']; ?>') this.bgColor='<?php echo $bgcolor; ?>';" onClick="this.bgColor=this.bgColor == '<?php echo $_TCONFIG['table_tr_click']; ?>' ? '<?php echo $bgcolor; ?>' : '<?php echo $_TCONFIG['table_tr_click']; ?>';">
          <td align="center"><input name="itemid[]" type="checkbox" id="itemid[]" value="<?php echo $value['id'];?>"></td>
          <td align="center"><?php echo $html.$value['name']; ?></td>
          <td align="center"><?php echo $value['barcode']; ?></td>
          <!--<td align="center"><a href="module.php?ac=setting&mod=store&id=<?php echo $value['id']; ?>">管理子类别</a></td>-->
		  <td align="center"><?php echo $value['createname']; ?></td>
		  <td align="center"><?php echo date("Y-m-d H:i" ,$value['date']); ?></td>
          <!--<td align="center"><?php echo $value['is_hidden']==1 ? '隐藏' : '显示'; ?></td>
          <td align="center"><?php echo $value['orderlist'] ? $value['orderlist'] : '-'; ?></td>
          <td align="center"><?php echo $value['template'] ? $_TGLOBAL['setting']['class_template'][$value['template']] : '&nbsp;'; ?></td>-->
          <td align="center">
			<a href="module.php?ac=setting&mod=store_edit&id=<?php echo $value['id']; ?>">修改</a>
			<?php if(!first_child($value['id'], $_TGLOBAL['tree_store'])): ?>
				&nbsp;
				<a href="module.php?ac=setting&mod=crib&storeid=<?php echo $value['id']; ?>">垛位</a>
			<?php endif; ?>
		  </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <input type="checkbox" name="checkbox" id="checkbox" onClick="chkbox_sel_all(this.form.name,'itemid[]', this.checked);">
    <label for="checkbox">全选</label>
    &nbsp;&nbsp;
    <input name="btnsubmit" type="submit" class="button" id="btnsubmit" value="删除" onClick="return submit_select_items(this.form.name,'itemid[]','操作');">
  </form>
  
  <BR>
  <u>帮助</u><span class="p14">： </span>
  <ul>
    <li>父级仓库下面有子仓库的时候不能直接删除，请先删除子仓库</li>
    <li>如果仓库下面设置有垛位的时候不能直接删除</li>
    <li>请先删除垛位在删除仓库</li>
  </ul>
</DIV>
<?php include 'footer.php'; ?>
</BODY>
</HTML>
