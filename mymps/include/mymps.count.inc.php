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
 * ��ϵ��ʽ��QQ:330647249 3037821 MSN:business@live.it
`*/
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

$ele = array('information'=>'������Ϣ','member'=>'��Ա','siteabout'=>'վ��');

$element[information] = array(
	'��Ϣ'=>array(
			'table'=>'information',
			'url'=>'information.php'
			),
	'����'=>array(
			'table'=>'info_comment',
			'url'=>'comment.php'
			),
	'�ٱ�'=>array(
			'table'=>'info_report',
			'url'=>'information.php?part=report'
			)
		);
		
$element[member] = array(
	'��Ա'=>array(
			'table'=>'member',
			'url'=>'member.php'
			)
		);
		
$element[siteabout] = array(
	'����'=>array(
			'table'=>'announce',
			'url'=>'announce.php'
			),
	'����'=>array(
			'table'=>'faq',
			'url'=>'faq.php'
			),
	'����'=>array(
			'table'=>'flink',
			'url'=>'friendlink.php'
			),
	'����'=>array(
			'table'=>'guestbook',
			'url'=>'guestbook.php'
			),
		);
?>