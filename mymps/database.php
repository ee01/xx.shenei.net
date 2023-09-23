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
define('CURSCRIPT','database');
error_reporting(0);
set_time_limit(0);

require_once(dirname(__FILE__)."/global.php");

if (!in_array($part,array('backup','restore','optimize'))){
	write_msg('FORBIDDEN');
	exit;
}

$mysqlhost	= $db_host;
$mysqluser	= $db_user;
$mysqlpwd	= $db_pass; 
$mysqldb	= $db_name;

require_once(MYMPS_INC."/dbbr.class.php");
require_once(MYMPS_INC."/db.class.php");

if($part == 'backup') {
	$random = random();
} elseif($part == 'restore') {
	session_start();
}

$backup_dir = MYMPS_DATA.$mymps_global[cfg_backup_dir]."/";
require_once(dirname(__FILE__)."/include/".$part.".inc.php");
?>