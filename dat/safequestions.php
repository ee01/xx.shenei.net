<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ��������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * �������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * ��������: ����ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
//��ȫ��ʾ���⣬�����䲻Ҫ�޸�
$safequestions = array();
$safequestions[0] = 'û��ȫ��ʾ����';

//��������ÿ��Ը������Լ�����Ҫ�޸ģ�һ���������ñ���Ĭ��
$safequestions[1] = '����ϲ���ĸ���ʲô��';
$safequestions[2] = '������������ʲô��';
$safequestions[3] = '�����Сѧ��ʲô��';
$safequestions[4] = '��ĸ��׽�ʲô���֣�';
$safequestions[5] = '���ĸ�׽�ʲô���֣�';
$safequestions[6] = '����ϲ����ż����˭��';
$safequestions[7] = '����ϲ���ĸ�����ʲô��';

//���²�Ҫ�޸�
function GetSafequestion($selid=0,$formname='safequestion')
{
	global $safequestions;
	$safequestions_form = "<select name='$formname' id='$formname'>";
	foreach($safequestions as $k=>$v)
	{
	 	if($k==$selid&&$k!='0') $safequestions_form .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $safequestions_form .= "<option value='$k'>$v</option>\r\n";
	}
	$safequestions_form .= "</select>\r\n";
	return $safequestions_form;
}

?>