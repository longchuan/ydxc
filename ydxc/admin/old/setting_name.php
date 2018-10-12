<?php
/*
	[CTB] (C) 2007-2009 
	$Id: member_group_add.php 2010-6-9 20:32:10 jerry $
*/

!defined('IN_CTB') && die('Access Denied');

checkpermission(); //权限检查
loadcache('class'  ,'id', '', 'orderlist', 1);   // 加载缓存树状
loadcache('brand'  ,'id', '', 'orderlist');   

//include_once(S_ROOT.'./lib/function/function_cache.php');

if(isset ($_POST['attrsubmit'])) {
	$aryid = simplode($_POST['itemid']);
	
	//判断现在的物资中还存不存在这样的物资
    $query = $_TGLOBAL['db']->query("SELECT NULL FROM ".tname('product')." WHERE state NOT IN($aryid)");									
    while ($value = $_TGLOBAL['db']->fetch_array($query)) {
        showmsg('要删除的物资在库存中存在，不能删除！', $_TGLOBAL['refer']);
    } 
	
	// 删除物资名录 属性 配件
    $_TGLOBAL['db']->query("DELETE FROM ".tname('name_attr')." WHERE nameid IN ($aryid) "); 
    $_TGLOBAL['db']->query("DELETE FROM ".tname('name_parts')." WHERE nameid IN ($aryid) "); 
    $_TGLOBAL['db']->query("DELETE FROM ".tname('name')." WHERE id IN ($aryid) ");
	showmsg('物资名录删除操作成功！', $_TGLOBAL['refer']);
}


//排序方式
$field = isset($_GET['field']) ? $_GET['field'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC' ;

$keywords = trim(isset($_POST['keywords']) ? $_POST['keywords'] : (isset($_GET['keywords']) ? $_GET['keywords'] : '' ));
$date_start = trim(isset($_POST['date_start']) ? $_POST['date_start'] : (isset($_GET['date_start']) ? $_GET['date_start'] : '' ));
$date_end = trim(isset($_POST['date_end']) ? $_POST['date_end'] : (isset($_GET['date_end']) ? $_GET['date_end'] : '' ));
$brandid  = trim(isset($_POST['brandid']) ? $_POST['brandid'] : (isset($_GET['brandid']) ? $_GET['brandid'] : '' ));
$tree['classid']  = trim(isset($_POST['classid']) ? $_POST['classid'] : (isset($_GET['classid']) ? $_GET['classid'] : '' ));

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>CTB V2.0</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK media=screen href="images/admin.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript src="js/common.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/validator.js"></SCRIPT>
<script language="JavaScript" src="js/jsAddress.js"></script>
<script language="JavaScript" src="js/selectTime.js"></script>
</HEAD>
<BODY onLoad="return write_nav('<?php echo $_TGLOBAL['menu']['main'][$ac]; ?> &raquo; <a href=\'module.php?ac=<?php echo $ac; ?>&mod=<?php echo $mod; ?>\' target=\'main\'><?php echo $_TGLOBAL['menu'][$ac][$mod]; ?></a> &raquo; ');">
  <DIV class=datacontainer2 style="WIDTH: 97%">
	  
	  <!--<DIV class=header><a href="module.php?ac=setting&mod=name_add">添加物资名录</a> </DIV>-->
	  <DIV class=header><a href="module.php?ac=setting&mod=name_addclassid">添加物资名录</a> </DIV>
    <form  method="post" name="FormEdit1" onSubmit="return Validator.Validate(this,3)">
      <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tab_01">
	  
    &nbsp;
    关键词：<input placeholder="关键词" name="keywords" type="text" id="keywords" value="<?php echo $keywords; ?>" size="30">
    &nbsp;
    录入日期：<input placeholder="" name="date_start" type="text" value="<?php echo $date_start; ?>" size="10" maxlength="10" onClick="SelectDate(this,'yyyy-MM-dd');">
    -
    <input placeholder="" name="date_end" type="text" value="<?php echo $date_end; ?>" size="10" maxlength="10" onClick="SelectDate(this,'yyyy-MM-dd');">
	&nbsp;
		物资类别：<?php 
		//$tree['classid'] = isset($tree['classid']) ? $tree['classid'] : 0 ;
		echo makeselect((array)$_TGLOBAL['tree_class'],'classid', $tree['classid'],'datatype="False" msg="选择物资类别."','name', 'id','选择物资类别', '0', 1) ;?>
	&nbsp;
		品牌：<?php 
		//$brandid = isset($_GET['brandid']) ? $_GET['brandid'] : 0 ;
		echo makeselect((array)$_TGLOBAL['brand'],'brandid', $brandid,'datatype="False" msg="请选择品牌."','name', 'id','选择品牌', '0', 1) ;?>
	&nbsp;
    <input name="button" type="submit" id="button" value="查询" class="button">
	
	
        <tr>
          <td class="caption"><div align="center">选择</div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("类别名称", "classid", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("品牌", "brandid", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("物资名称", "name", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("预警数量", "warnnum", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("一次性物资", "isone", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("创建人", "createname", $field, $order); ?></div></td>
          <td class="caption"><div align="center"><?php echo GetOrder("创建时间", "date", $field, $order); ?></div></td>
          <td class="caption"><div align="center">修改</div></td>
        </tr>
<?php
		
	$perpage = 20;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	$page<1 && $page = 1;
	$start = ($page-1)*$perpage;
	//检查开始数
	ckstart($start, $perpage);
	$where = ' WHERE 1';

	if ($keywords) {
		$where .= " AND (";
		$where .= " name LIKE '%$keywords%'";
		$where .= " OR warnnum LIKE '%$keywords%'";
		$where .= " OR descript LIKE '%$keywords%')";
	}
	
	if ($tree['classid']) {
		$where .= " AND classid = '$tree[classid]' ";
	}
	
	if ($brandid) {
		$where .= " AND brandid = '$brandid' ";
	}
	
    //处理日期段搜索
    if ($date_start && $date_end) {
		$date_start = strtotime($date_start.' 00:00:00'); //转换输入的时间为时间戳的方式
		$date_end = strtotime($date_end. ' 23:59:59');
		$where .= " AND (date between '$date_start' and '$date_end')"; 
    }
	
	$count = $_TGLOBAL['db']->result($_TGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('name')." ".$where), 0);	
	$query = $_TGLOBAL['db']->query("SELECT * FROM ".tname('name')." $where  ORDER BY $field $order LIMIT $start,$perpage ");
	$multi = multi($count, $perpage, $page, get_current_url());
	
while ($value = $_TGLOBAL['db']->fetch_array($query)) {	
?>
        <tr bgcolor="<?php echo $bgcolor = (isset($bgcolor) && $bgcolor == '#FFFFFF') ? '#F1F5FE' : '#FFFFFF'; ?>" onMouseOver="if (this.bgColor != '<?php echo $_TCONFIG['table_tr_click']; ?>') this.bgColor='#E4EAF2';" onMouseOut="if (this.bgColor != '<?php echo $_TCONFIG['table_tr_click']; ?>') this.bgColor='<?php echo $bgcolor; ?>';" onClick="this.bgColor=this.bgColor == '<?php echo $_TCONFIG['table_tr_click']; ?>' ? '<?php echo $bgcolor; ?>' : '<?php echo $_TCONFIG['table_tr_click']; ?>';">
          <td align="center"><input name="itemid[]" type="checkbox" id="itemid[]" value="<?php echo $value['id'];?>"></td>
          <td align="center"><?php echo get_pos($value['classid'], $_TGLOBAL['tree_class']) ; ?></td>
          <td align="center"><?php echo $value['brandid'] ? $_TGLOBAL['brand'][$value['brandid']]['name'] : '&nbsp;'; ?></td>
		  <td align="center"><?php echo $value['name'] ? $value['name'] : '&nbsp;'; ?></td>
		  <td align="center"><?php echo $value['warnnum'] ? $value['warnnum'] : 0; ?></td>
		  <td align="center"><?php echo $value['isone'] ? $_TGLOBAL['setting']['isone'][$value['isone']] : 0; ?></td>
          <td align="center"><?php echo $value['createname'] ? $value['createname'] : '&nbsp;'; ?></td>
          <td align="center"><?php echo $value['date'] ? date("Y-m-d H:i" ,$value['date']) : '&nbsp'; ?></td>
          <td align="center"><a href="module.php?ac=setting&mod=name_edit&classid=<?php echo $value['classid'] ;?>&id=<?php echo $value['id']; ?>">修改</a></td>
        </tr>
        <?php } ?>
      </table>
		<input type="checkbox" name="checkbox" id="checkbox" onClick="chkbox_sel_all(this.form.name,'itemid[]', this.checked);">
		<label for="checkbox">全选</label>
		&nbsp;&nbsp;
		<input name="attrsubmit" type="submit" class="button" id="attrsubmit" value="删除" onClick="return submit_select_items(this.form.name,'itemid[]','操作');">
    </form>

  <BR>
  <u>帮助</u><span class="p14">： </span>
  <ul>
    <li>如果某个物资名录存在物资，则不能删除。</li>
    <li>删除物资名录后，查询以前的物资变化信息将会出错，谨慎操作。</li>
  </ul>
	
	<div align="center"><span class="page"><?php echo $multi; ?></span></div>
  </DIV>
  <?php include 'footer.php'; ?>
</BODY>
</HTML>