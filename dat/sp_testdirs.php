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
//check ifreadable ifwriteable of the file
//�����дȨ�޵��ļ�
//һ������£��뱣����Ĭ�ϣ����޸�
$sp_testdirs = array(
	'/dat',
	'/dat/cache',
	'/dat/sessions',
	'/dat/config.php',
	'/dat/config.cache.php',
	'/dat/category.inc.php',
	'/dat/announce.inc.php',
	'/dat/area.inc.php',
	'/dat/config.db.php',
	'/dat/backup',
	'/dat/upload',
	'/dat/upload/focus',
	'/dat/upload/information',
	'/dat/upload/member_logo',
	'/dat/mympstpl'
);
?>