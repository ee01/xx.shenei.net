<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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