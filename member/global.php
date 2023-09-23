<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
error_reporting(0);
define('IN_MYMPS', true);
require_once(dirname(__FILE__)."/../include/global.inc.php");
require_once(MYMPS_DATA."/config.php");
require_once(MYMPS_DATA."/config.db.php");
require_once(MYMPS_INC."/db.class.php");
require_once(MYMPS_INC."/global.php");
require_once(MYMPS_MEMBER."/include/mymps.menu.inc.php");
require_once(MYMPS_INC."/member.class.php");

$log = $member_log->chk_in();
if(!$log){
	$url = urlencode(GetUrl());
	write_msg("","login.php?url=$url");
	exit();
}

function write_money_use($info,$cost,$type=2){
	global $db,$db_mymps,$s_uid;
	if($db -> query("INSERT INTO `{$db_mymps}member_record_pay` (type,userid,paycost,subject,pubtime) VALUES ('$type','$s_uid','$cost','$info','".time()."')"))return true;
	else return false;
}

$record_part = array('pay'=>'1','use'=>'2');
?>