<?php
/*
	[CTB] (C) 2007-2009 copytaobao.com
	$Id: sign_init_add.php 2013-11-25 0:32:35 jerry $
*/

!defined('IN_CTB') && die('Access Denied');

//权限检查
checkpermission(); //权限检查


if (isset($_POST['usage'])) {

	$id = isset($_POST['id']) ? (int)$_POST['id'] : 0 ;
	
	if ($id <= 0) { //添加

		$arr = array(
			'name'             => 	$_POST['name'],
			'intro'            => 	$_POST['intro'],
			'liucheng'         => 	$_POST['liucheng'],
			'price'            => 	$_POST['price'],
			'lowpay'           => 	$_POST['lowpay'],
			'displayorder'     => 	$_POST['displayorder'],
			'date'             => 	$_TGLOBAL['timestamp'],
				);
		$id = inserttable('sign', $arr, 1);
		$result = '报名处理添加成功！';

	} else { //更新
		
		$arr = array(
			'coach_id'             => 	$_POST['coach_id'],
				);
		updatetable('sign', $arr, array('id' => $id));
		$result = '教练修改成功！';
	}

	showmsg($result, 'module.php?ac=change&mod=update');

}

//取出修改时的信息
$id = @(int)$_GET['id'];
$sign = $_TGLOBAL['db']->getrow("SELECT * FROM ".tname('sign')." WHERE id='$id'");
$coach = $_TGLOBAL['db']->getrow("SELECT * FROM ".tname('news')." WHERE id='".$sign['coach_id']."'");
$coach_all = $_TGLOBAL['db']->getall("SELECT * FROM ".tname('news')." WHERE class_id=3");
if ($id > 0) {
	$query = $_TGLOBAL['db']->query("SELECT * FROM ".tname('sign')." WHERE id='$id'");
	@extract($_TGLOBAL['db']->fetch_array($query));
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<TITLE>CTB V2.0</TITLE>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<LINK media=screen href="images/admin.css" type=text/css rel=stylesheet>
<SCRIPT language=JavaScript src="js/common.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/jquery.js"></SCRIPT>
<SCRIPT language=JavaScript src="js/validator.js"></SCRIPT>
<script language="JavaScript" src="js/popcalendar.js"></script>
</HEAD>
<BODY onLoad="return write_nav('<?php echo $_TGLOBAL['menu']['main'][$ac]; ?> &raquo; <?php echo $_TGLOBAL['menu'][$ac][$mod]; ?> &raquo; ');">
<DIV class=datacontainer2 style="WIDTH: 98%">
  <DIV class=header>更换教练 </DIV>
  <form  method="post" enctype="multipart/form-data" name="FormEdit" onSubmit="return Validator.Validate(this,3)">
    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="tab_01">
      <tbody>
        <tr>
          <td width="14%" class="caption"> 班级</td>
          <td width="36%"><?php echo @$courseid ? $_TGLOBAL['course'][$courseid]['name'] : '&nbsp;'; ?></td>
		  <!--<td width="14%" class="caption"> 报名人数</td>
          <td width="36%"><?php echo @$num; ?></td>-->
        </tr>
        <tr>
          <td class="caption"> 姓名</td>
          <td><?php echo @$name; ?></td>
		  <!--<td class="caption"> 性别</td>
          <td><?php echo @$sex ? $_TGLOBAL['setting']['sex'][$sex] : '&nbsp;'; ?></td>-->
        </tr>
        <!--<tr>
          <td class="caption"> 证件类型</td>
          <td>身份证</td>
		  <td class="caption"> 性别</td>
          <td><?php echo @$cardno; ?></td>
        </tr>-->
        <tr>
          <!--<td class="caption"> 联系电话</td>
          <td><?php echo @$tel; ?></td>-->
		  <td class="caption"> 手机</td>
          <td><?php echo @$mobile; ?></td>
        </tr>
        <tr>
		  <td class="caption"> 更换教练</td>
          <td>
          	<?php
				$selet1 = '<select name="coach_id">';
				foreach($coach_all as $vo){
					if($coach['id']==$vo['id']){
						$html1 = '<option value="'.$vo['id'].'">'.$vo['xingming'].'</option>';
					} else {
					   $html .= '<option value="'.$vo['id'].'">'.$vo['xingming'].'</option>';
					}
				}
				$selet2= '</select>';
				 
				echo $selet1.$html1.$html.$selet2;
			?>
          </td>
        </tr>
        <!--<tr>
          <td class="caption"> 区域</td>
          <td><?php echo @$area; ?></td>
		  <td class="caption"> 服务点</td>
          <td><?php echo @$point; ?></td>
        </tr>-->
        <!--<tr>
          <td class="caption"> QQ</td>
          <td><?php echo @$qq; ?></td>
		  <td class="caption"> 手机</td>
          <td><?php echo @$mobile; ?></td>
        </tr>
        <tr>
          <td class="caption"> 报名时间</td>
          <td><?php echo @$date ? date("Y-m-d H:i", $date) : '&nbsp;'; ?></td>
		  <td class="caption"> 是否缴费</td>
          <td><?php echo @$ispay ? '是' : '否'; ?></td>
        </tr>
        <tr>
          <td class="caption"> 缴费多少</td>
          <td><?php echo @$ispay ? $money : '&nbsp;'; ?></td>
		  <td class="caption"> 缴费时间</td>
          <td><?php echo @$ispay ? date("Y-m-d H:i", $paydate) : '&nbsp;'; ?></td>
        </tr>-->
		
		
        

        <tr>
          <td class="caption">&nbsp;</td>
          <td><input name="button" type="submit" class="button" id="button" value="提交">
            <input name="id" type="hidden" id="id" value="<?php echo @$id; ?>">
            <input name="usage" type="hidden" id="usage" value="1"></td>
        </tr>
      </tbody>
    </table>
  </form>
</DIV>
<?php include 'footer.php'; ?>

</BODY>
</HTML>
