<?php

/*

	[CTB] (C) 2007-2015 www.51shop.org jerry V2.0

	$Id: config.php 2012-9-7 16:45:15 jerry $

*/



//CTB配置参数

$_CTB = array();
$_CTB['dbhost']  		= 'localhost'; //服务器地址 120.25.59.75
$_CTB['dbuser']  		= 'root'; //用户名 
$_CTB['dbpassword']		= 'root'; //密码
$_CTB['dbcharset'] 		= 'utf8'; //字符集
$_CTB['pconnect'] 		= 0; //是否持续连接
$_CTB['dbname']  		= 'ydxctrue'; //数据库

$_CTB['tablepre'] 		= 'ctb_'; //表名前缀

$_CTB['charset'] 		= 'utf-8'; //页面字符集



$_CTB['gzipcompress'] 	= 0; //启用gzip



$_CTB['cookiepre'] 		= 'ctb_'; //COOKIE前缀

$_CTB['cookiedomain'] 	= ''; //COOKIE作用域

$_CTB['cookiepath'] 	= '/'; //COOKIE作用路径



$_CTB['siteurl']		= ''; //站点的访问URL地址(http:// 开头的绝对地址, 末尾加 "/")，为空的话，系统会自动识别。

//$_CTB['socket_address'] = 'djjx.xibuhaitao.com'; //socket提交地址


$_CTB['founder'] 		= ''; //创始人 UID, 可以支持多个创始人，之间使用 “,” 分隔。部分管理功能只有创始人才可操作。



$_CTB['attachdir']		= './upload/'; //附件本地保存位置(服务器路径, 属性 777, 必须为 web 可访问到的目录, 相对目录务必以 "./" 开头, 末尾加 "/")

$_CTB['attachurl']		= 'upload/'; //附件本地URL地址(可为当前 URL 下的相对地址或 http:// 开头的绝对地址, 末尾加 "/")

$_TCONFIG['maxpage']    = 9999;


?>