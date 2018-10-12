<?php
/*
	[CTB] (C) 2007-2009 51shop.org jerry
	$Id: function_ftp.php 2009-4-21 14:31:07 jerry $
*/

!defined('IN_CTB') && die('Access Denied');

include_once(S_ROOT.'./data/data_setting.php');

//FTP上传
function ftpupload($source, $dest) {
	global $_TGLOBAL;

	if(empty($_TGLOBAL['ftpconnid']) && !($_TGLOBAL['ftpconnid'] = sftp_connect())) {
		return 0;
	} else {
		$ftpconnid = $_TGLOBAL['ftpconnid'];
	}
	$ftppwd = FALSE;
	$tmp = explode('/', $dest);
	$dest = array_pop($tmp);

	foreach ($tmp as $tmpdir) {
		if(!sftp_chdir($ftpconnid, $tmpdir)) {
			if(!sftp_mkdir($ftpconnid, $tmpdir)) {
				runlog('FTP', "MKDIR '$tmpdir' ERROR.", 0);
				return 0;
			}
			if(!function_exists('ftp_chmod') || !sftp_chmod($ftpconnid, 0777, $tmpdir)) {
				sftp_site($ftpconnid, "'CHMOD 0777 $tmpdir'");
			}
			if(!sftp_chdir($ftpconnid, $tmpdir)) {
				runlog('FTP', "CHDIR '$tmpdir' ERROR.", 0);
				return 0;
			}
			sftp_put($ftpconnid, 'index.htm', S_ROOT.'./data/index.htm', FTP_BINARY);
		}
	}

	if(sftp_put($ftpconnid, $dest, $source, FTP_BINARY)) {
		if(file_exists($source.'.thumb.jpg')) {
			if(sftp_put($ftpconnid, $dest.'.thumb.jpg', $source.'.thumb.jpg', FTP_BINARY)) {
				file_exists($source) && unlink($source);
				file_exists($source.'.thumb.jpg') && unlink($source.'.thumb.jpg');
				sftp_close($ftpconnid);
				return 1;
			} else {
				sftp_delete($ftpconnid, $dest);
			}
		} else {
			file_exists($source) && unlink($source);
			sftp_close($ftpconnid);
			return 1;
		}
	}
	runlog('FTP', "Upload '$source' To '$dest' error.", 0);
	return 0;
}

//FTP连接
function sftp_connect() {
	global $_TGLOBAL;

	@set_time_limit(0);

	$func = $_TGLOBAL['setting']['ftpssl'] && function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
	if($func == 'ftp_connect' && !function_exists('ftp_connect')) {
		runlog('FTP', "FTP NOT SUPPORTED.", 0);
	}
	if($ftpconnid = @$func($_TGLOBAL['setting']['ftphost'], intval($_TGLOBAL['setting']['ftpport']), 20)) {
		if($_TGLOBAL['setting']['ftptimeout'] && function_exists('ftp_set_option')) {
			@ftp_set_option($ftpconnid, FTP_TIMEOUT_SEC, $_TGLOBAL['setting']['ftptimeout']);
		}
		if(sftp_login($ftpconnid, $_TGLOBAL['setting']['ftpuser'], $_TGLOBAL['setting']['ftppassword'])) {
			if($_TGLOBAL['setting']['ftppasv']) {
				sftp_pasv($ftpconnid, TRUE);
			}
			if(sftp_chdir($ftpconnid, $_TGLOBAL['setting']['ftpdir'])) {
				return $ftpconnid;
			} else {
				runlog('FTP', "CHDIR '{$_TGLOBAL[setting][ftpdir]}' ERROR.", 0);
			}
		} else {
			runlog('FTP', '530 NOT LOGGED IN.', 0);
		}
	} else {
		runlog('FTP', "COULDN'T CONNECT TO {$_TGLOBAL[setting][ftphost]}:{$_TGLOBAL[setting][ftpport]}.", 0);
	}
	sftp_close($ftpconnid);
	return -1;
}

function sftp_mkdir($ftp_stream, $directory) {
	$directory = wipespecial($directory);
	return @ftp_mkdir($ftp_stream, $directory);
}

function sftp_rmdir($ftp_stream, $directory) {
	$directory = wipespecial($directory);
	return @ftp_rmdir($ftp_stream, $directory);
}

function sftp_put($ftp_stream, $remote_file, $local_file, $mode, $startpos = 0 ) {
	$remote_file = wipespecial($remote_file);
	$local_file = wipespecial($local_file);
	$mode = intval($mode);
	$startpos = intval($startpos);
	return @ftp_put($ftp_stream, $remote_file, $local_file, $mode, $startpos);
}

function sftp_size($ftp_stream, $remote_file) {
	$remote_file = wipespecial($remote_file);
	return @ftp_size($ftp_stream, $remote_file);
}

function sftp_close($ftp_stream) {
	return @ftp_close($ftp_stream);
}

function sftp_delete($ftp_stream, $path) {
	$path = wipespecial($path);
	return @ftp_delete($ftp_stream, $path);
}

function sftp_get($ftp_stream, $local_file, $remote_file, $mode, $resumepos = 0) {
	$remote_file = wipespecial($remote_file);
	$local_file = wipespecial($local_file);
	$mode = intval($mode);
	$resumepos = intval($resumepos);
	return @ftp_get($ftp_stream, $local_file, $remote_file, $mode, $resumepos);
}

function sftp_login($ftp_stream, $username, $password) {
	$username = wipespecial($username);
	$password = str_replace(array("\n", "\r"), array('', ''), $password);
	return @ftp_login($ftp_stream, $username, $password);
}

function sftp_pasv($ftp_stream, $pasv) {
	$pasv = intval($pasv);
	return @ftp_pasv($ftp_stream, $pasv);
}

function sftp_chdir($ftp_stream, $directory) {
	$directory = wipespecial($directory);
	return @ftp_chdir($ftp_stream, $directory);
}

function sftp_site($ftp_stream, $cmd) {
	$cmd = wipespecial($cmd);
	return @ftp_site($ftp_stream, $cmd);
}

function sftp_chmod($ftp_stream, $mode, $filename) {
	$mode = intval($mode);
	$filename = wipespecial($filename);
	if(function_exists('ftp_chmod')) {
		return @ftp_chmod($ftp_stream, $mode, $filename);
	} else {
		return sftp_site($ftp_stream, 'CHMOD '.$mode.' '.$filename);
	}
}

function wipespecial($str) {
	return str_replace(array('..', "\n", "\r"), array('', '', ''), $str);
}

?>