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
$report_type = array();
$report_type[1] = 'Υ����Ϣ';
$report_type[2] = '�������';
$report_type[3] = '�����Ϣ';

$report_type[0] = '����ԭ��';

function get_report_type()
{
	global $report_type;
	$mymps .="<select name='report_type'>";
	foreach($report_type as $k => $value)
	{
		$mymps .= "<option value=\"".$k."\">".$value."</option>";
	}
	$mymps .="</select>";
	return $mymps;
}
?>