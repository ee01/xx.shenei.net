<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ������������ǽ�׷�����ķ�������!!
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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