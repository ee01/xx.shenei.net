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
define('MYMPS_INC'		, ereg_replace("[/\\]{1,}",'/',dirname(__FILE__) ) );
define('MYMPS_ROOT'		, ereg_replace("[/\\]{1,}",'/',substr(MYMPS_INC,0,-8) ) );
define('MYMPS_DATA'		, MYMPS_ROOT.'/dat');
define('MYMPS_MEMBER'	, MYMPS_ROOT.'/member');
define('MYMPS_UPLOAD'	, MYMPS_DATA.'/upload');
define('MYMPS_SMARTY'	, MYMPS_INC.'/smarty');
define('MYMPS_TPL'		, MYMPS_ROOT.'/template');
define('MYMPS_ADMIN'	, 'mymps');//��̨����Ŀ¼��������޸��˺�̨����Ŀ¼����Ҳ�޸Ĵ˴�
?>