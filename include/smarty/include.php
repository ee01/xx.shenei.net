<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ������������ǽ�׷�����ķ�������!!
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:330647249 3037821 MSN:business@live.it
`*/
include_once(MYMPS_SMARTY.'/class/Smarty.class.php');
$smarty = new smarty();
$smarty->template_dir = MYMPS_ROOT.'/template/'.$smartytpl;
$smarty->compile_dir  = MYMPS_DATA.'/mympstpl/';
$smarty->config_dir   = MYMPS_ROOT.'/config/';
$smarty->cache_dir    = MYMPS_DATA.'/cache/';
$smarty->compile_check = true;
$smarty->left_delimiter = "{";
$smarty->right_delimiter = "}";
?>