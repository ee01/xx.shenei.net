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
$part = isset($part)? trim($part) : 'index' ;

require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_MEMBER."/include/member_count.inc.php");

$sql = "SELECT a.id,a.userid,a.jointime,a.money_own,a.levelid,a.cname,a.qq,a.tel,a.email,a.address,a.prelogo,b.levelname FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE userid = '$s_uid'";

$info = $db -> getRow($sql);
if(!$info){
	write_msg("����ǰ��¼�Ļ�Ա�Ѳ����ڣ����ϵͳ����Ա��ϵ��","login.php?part=out");
	exit();
}

$here 		= "������ҳ";
$money_own  = $info[money_own];

if(empty($info['cname'])||empty($info['tel'])||empty($info['qq'])){
	$info_input_url = "javascript:setbg('����������ϵ��ʽ',460,80,'../public/box.php?part=checkmemberinfo&url=info.php?part=input')";
} else {
	$info_input_url = "info.php?part=input";
}

$tpl = mymps_tpl("mymps_right");
include(mymps_tpl("index"));
?>