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
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
$information_level = array();
$information_level[0] = '<font color=red>����</font>';
$information_level[1] = '<font color=#006acd>����</font>';
$information_level[2] = '<font color=green>�Ƽ�</font>';

function GetInfoLevel($level='',$formname='info_level')
{
	global $information_level;
	$mymps .= "<select name='$formname' id='$formname'>";
	$mymps .= "<option value = \"\">��ѡ����Ϣ����</option>";
	foreach($information_level as $k=>$v)
	{
	 	if($k==$level) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}

//upgrade
$info_upgrade_level = array();
$info_upgrade_level[1] = '���ö�';
$info_upgrade_level[2] = '<font color=red>�����ö�</font>';
$info_upgrade_level[3] = '<font color=red><b>��ҳ�ö�</b></font>';

function GetUpgradeType($level='',$formname='upgrade_type')
{
	global $info_upgrade_level;
	$mymps .= "<select name='$formname' id='$formname'>";
	foreach($info_upgrade_level as $k=>$v)
	{
	 	if($k==$level) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}

//upgrade time
$info_upgrade_time = array();
$info_upgrade_time[1] = '1��';
$info_upgrade_time[3] = '3��';
$info_upgrade_time[7] = '7��';
$info_upgrade_time[30] = '30��';
$info_upgrade_time[90] = '90��';

function GetUpgradeTime($time='',$formname='upgrade_time')
{
	global $info_upgrade_time;
	$mymps .= "<select name='$formname' id='$formname'>";
	foreach($info_upgrade_time as $k=>$v)
	{
	 	if($k==$time) $mymps .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $mymps .= "<option value='$k'>$v</option>\r\n";
	}
	$mymps .= "</select>\r\n";
	return $mymps;
}
?>