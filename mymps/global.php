<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。否则我们将追究您的法律责任!!
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
define('IN_MYMPS', true);
require_once(dirname(__FILE__)."/../include/global.inc.php");
require_once(MYMPS_DATA."/config.php");
require_once(MYMPS_DATA."/config.db.php");
require_once(MYMPS_INC."/global.php");
require_once(MYMPS_INC."/admin.class.php");

if(!$mymps_admin -> mymps_admin_chk_getinfo()){
	$url = urlencode(GetUrl());
	write_msg("","index.php?do=login&url=".$url);
}

function sizeunit($filesize) {
	if($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 . ' GB';
	} elseif($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 . ' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize . ' bytes';
	}
	return $filesize;
}

function write_file($sql,$backup_dir,$filename){
	$re=true;
	if(!@$fp=fopen($backup_dir.$filename,"w+")) {$re=false; echo "打开文件失败";}
	if(!@fwrite($fp,$sql)) {$re=false; echo "写文件失败";}
	if(!@fclose($fp)) {$re=false; echo "关闭文件失败";}
	return $re;
}

function down_file($sql,$filename){
	ob_end_clean();
	header("Content-Encoding: none");
	header("Content-Type: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 'application/octet-stream'));
			
	header("Content-Disposition: ".(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'inline; ' : 'attachment; ')."filename=".$filename);
			
	header("Content-Length: ".strlen($sql));
	header("Pragma: no-cache");
			
	header("Expires: 0");
	echo $sql;
	$e=ob_get_contents();
	ob_end_clean();
}

function writeable($dir){	
	if(!is_dir($dir)){
		@mkdir($dir, 0777);
	}	
	if(is_dir($dir)){	
		if(is_writable($dir)){
			$writeable = 1;
		}else{
			$writeable = 0;
		}	
	}
	return $writeable;
}

function make_header($table){
	global $d;
	$sql="DROP TABLE IF EXISTS ".$table."\n";
	$d->query("show create table ".$table);
	$d->nextrecord();
	$tmp=preg_replace("/\n/","",$d->f("Create Table"));
	$sql.=$tmp."\n";
	return $sql;
}

function make_record($table,$num_fields){
	global $d;
	$comma="";
	$sql .= "INSERT INTO ".$table." VALUES(";
	for($i = 0; $i < $num_fields; $i++){
		$sql .= ($comma."'".mysql_escape_string($d->record[$i])."'"); 
		$comma = ",";
	}
	$sql .= ")\n";
	return $sql;
}

function import($fname){
	global $d;
	$sqls=file($fname);
	foreach($sqls as $sql)
	{
		str_replace("\r","",$sql);
		str_replace("\n","",$sql);
		if(!$d->query(trim($sql))) return false;
	}
	return true;
}

function chk_admin_purview($purview){
	global $db,$db_mymps,$admin_id;
	if(file_exists(MYMPS_DATA.'/admin.inc.php')) require_once MYMPS_DATA.'/admin.inc.php';
	if(is_array($admin_purview_cache)){
		!strstr($admin_purview_cache[$admin_id]['purviews'],$purview) && write_msg("很抱歉，您所在会员组没有该栏目的操作权限！");
	} else {
		$row = $db->getRow("SELECT a.typeid,b.purviews FROM `{$db_mymps}admin`  AS a LEFT JOIN `{$db_mymps}admin_type` AS b ON a.typeid = b.id WHERE a.userid='$admin_id'");
		!strstr($row['purviews'],$purview) && write_msg("很抱歉，您所在会员组没有该栏目的操作权限！");
	}
}

function mymps_admin_tpl_global_head($go=''){
	global $here,$charset;
	include (mymps_tpl("inc_head"));
}

function mymps_admin_tpl_global_foot(){
	global $mymps_starttime,$mtime;
	$mtime = explode(' ', microtime());
    $totaltime = number_format(($mtime[1] + $mtime[0] - $mymps_starttime), 6);
	
    $sitedebug = 'Processed in '.$totaltime.' second(s)';
	
	$mymps_admin_footer ="<div class=\"copyright\">Powered by <a href=\"http://www.mymps.com.cn/\" target=\"_blank\"><b style=\"color:#0070af\">mymps</b></a> <a href=\"http://bbs.mymps.com.cn/\" target=\"_blank\"><b style=\"color:#FF6600\">".MPS_VERSION."</b></a> , ".$sitedebug."</div></body></html>";
	
	return $mymps_admin_footer;
}

function FileImage($file){
	$ext = FileExt($file);
	if($ext == 'html'||$ext == 'htm'){
		$images='images/file_html.gif';
	}elseif($ext == 'gif'||$ext == 'png'){
		$images='images/file_gif.gif';
	}elseif($ext == 'bmp'){
		$images='images/file_bmp.gif';
	}elseif($ext == 'jpg'||$ext == 'jpeg'){
		$images='images/file_jpg.gif';
	}elseif($ext == 'swf'){
		$images='images/file_swf.gif';
	}elseif($ext == 'js'){
		$images='images/file_script.gif';
	}elseif($ext == 'css'){
		$images='images/file_css.gif';
	}elseif($ext == 'txt'){
		$images='images/file_txt.gif';
	}else{
		$images='images/file_unknow.gif';
	}
	return $images;
}

function is_pic($file){
	$ext = FileExt($file);
	if($ext == 'gif'||$ext == 'jpg'||$ext == 'jpeg'||$ext == 'png' ||$ext == 'bmp'){
		return "yes";
		exit();
	}
	return "no";
}

function getSize($fs){
	if($fs<1024){
		return $fs."Byte";
	}elseif($fs>=1024&&$fs<1024*1024){
		return @number_format($fs/1024, 3)." KB";
	}elseif($fs>=1024*1024 && $fs<1024*1024*1024){
		return @number_format($fs/1024*1024, 3)." M";
	}elseif($fs>=1024*1024*1024){
		return @number_format($fs/1024*1024*1024, 3)." G";
	}
}
?>