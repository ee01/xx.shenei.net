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
/*��Ϣ�����ĳ���ʱ��������ѡ��*/
//ע�ⵥλΪ�죬һ����Ϊ7��һ������Ϊ30���Դ�����
//һ������£��뱣����Ĭ�ϣ����޸�
$info_lasttime = array();
$info_lasttime[7] 	= 'һ��';
$info_lasttime[30] 	= 'һ����';
$info_lasttime[60] 	= '������';
$info_lasttime[365] = 'һ��';


//��������Ϊ��Ϣ�������޸�ʱ�Ƿ���������Ч������Ը����Լ������ɾ�����������벻Ҫ�޸�
//$info_lasttime[0] = '������Ч';

//���²�Ҫ�޸�
function GetInfoLastTime($lasttime='',$formname='endtime'){
	global $info_lasttime;
	$info_lasttime_form = "<select name='$formname' id='$formname'>";
	foreach($info_lasttime as $k=>$v){
	 	if($k==$lasttime&&$k!='') $info_lasttime_form .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $info_lasttime_form .= "<option value='$k'>$v</option>\r\n";
	}
	$info_lasttime_form .= "</select>\r\n";
	return $info_lasttime_form;
}

?>