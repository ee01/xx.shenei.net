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
error_reporting(E_ALL ^ E_NOTICE);
define("IN_MYMPS",true);
$part = trim($_GET['part']);
$part = $part ? $part : 'info';
$id = isset($_GET['id'])?intval($_GET['id']):'';

require_once(dirname(__FILE__)."/../include/global.inc.php");
require_once(MYMPS_DATA."/config.php");
require_once(MYMPS_DATA."/config.db.php");
require_once(MYMPS_INC."/db.class.php");

if($part == 'info' && !empty($id)){
  $db->query("UPDATE `{$db_mymps}information` SET hit = hit+1 WHERE id = '$id'");
  $row = $db->getRow("SELECT hit FROM `{$db_mymps}information` WHERE id = '$id'");
  echo "document.write('".$row[hit]."');";
}elseif($part == 'announce'  && !empty($id)){
  $db->query("UPDATE `{$db_mymps}announce` SET hits = hits WHERE id = '$id'");
  $row = $db->getRow("SELECT hits FROM `{$db_mymps}announce` WHERE id = '$id'");
  echo "document.write('".$row[hits]."');";
}elseif($part == 'space'){
	require_once(MYMPS_INC."/member.class.php");
	$userid = trim($_GET['userid']);
	$sql ="SELECT qq,email,address,tel FROM `{$db_mymps}member` WHERE userid = '$userid'";
	$member = $db -> getRow($sql);
	$action = trim($_GET['action']);
	$log = $member_log -> chk_in();
	$member[$action] = (!$log)?'��¼��ɼ�':$member[$action];
	echo "document.write('".$member[$action]."');";
}
?>