<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��Author:steven
 * ��ϵ��ʽ��chinawebmaster@yeah.net MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/public/global.php");

if(!is_file(MYMPS_DATA."/mymps.lock")){
	write_msg('','install/index.php');
}

if((!isset($category_cache) || !is_array($category_cache)) && is_file(MYMPS_DATA."/mymps.lock") && $db_intype == 1){
	write_class_cache();
	die(die_msg('categories have been cached,please refresh this page!<br /><br />categories caching time:'.GetTime(time())));
}

$smarty -> cache_lifetime    = $mymps_cache['site']['time'];
$smarty -> caching           = $mymps_cache['site']['open'];

if (!$smarty->is_cached(mymps_tpl('index','smarty'))){

	mymps_global_assign();
	$smarty -> assign('nav_bar'			, '��վ��ҳ');
	$smarty -> assign('scategory'		, $category_cache_s);
	$smarty -> assign('announce_global' , mymps_get_announce(6));//����
	$smarty -> assign('faq_list' 		, mymps_get_faq(5));//��վ����
	$smarty -> assign('first_cat'		, $first_cat);//��ҵ���
	$smarty -> assign('info_list'		, mymps_get_info_list(15,'','','',''));//������Ϣ
	$smarty -> assign('info_list_top'	, mymps_get_info_list(15,'',3,'',''));//ͷ��ͷ��
	$smarty -> assign('word_link'		, mymps_get_flink(9,'wordlink'));//��������
	$smarty -> assign('img_link'		, mymps_get_flink(9,'imglink'));//ͼƬ����
	$smarty -> assign("focus"			, mymps_get_focus(3));//����ͼ
	$smarty -> assign('count_info'		, mymps_count('information'));//��Ϣͳ��
	/*
	$smarty -> assign('info_list_hot'	, mymps_get_info_list(8,2,'','',''));//�Ƽ���Ϣ
	*/
	$smarty -> assign('info_list_hot'	, get_hotclick_info());//���������Ϣ
	$smarty -> assign('count_online'	, rand(5,35));//����ͳ�ƣ���ƭ	Add By 01
	
}

$smarty -> display(mymps_tpl("index","smarty"));
?>