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
$info_posttime = array();
/*��Ϣ�����ķ���ʱ��������ѡ��*/
//ע�ⵥλΪ�죬һ����Ϊ7��һ������Ϊ30���Դ�����
$info_posttime[0] = '����';
$info_posttime[3] = '3����';
$info_posttime[7] = 'һ����';
$info_posttime[30] = 'һ��������';
$info_posttime[90] = '����������';


//���²�Ҫ�޸�
function GetInfoPostTime($posttime='',$formname='posttime'){
	global $info_posttime;
	$info_posttime_form = "<select name='$formname' id='$formname'>";
	foreach($info_posttime as $k=>$v){
	 	if($k==$posttime&&$k!='') $info_posttime_form .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $info_posttime_form .= "<option value='$k'>$v</option>\r\n";
	}
	$info_posttime_form .= "</select>\r\n";
	return $info_posttime_form;
}

?>