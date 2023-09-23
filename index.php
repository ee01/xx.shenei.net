<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）Author:steven
 * 联系方式：chinawebmaster@yeah.net MSN:business@live.it
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
	$smarty -> assign('nav_bar'			, '网站首页');
	$smarty -> assign('scategory'		, $category_cache_s);
	$smarty -> assign('announce_global' , mymps_get_announce(6));//公告
	$smarty -> assign('faq_list' 		, mymps_get_faq(5));//网站帮助
	$smarty -> assign('first_cat'		, $first_cat);//行业类别
	$smarty -> assign('info_list'		, mymps_get_info_list(15,'','','',''));//最新信息
	$smarty -> assign('info_list_top'	, mymps_get_info_list(15,'',3,'',''));//头版头条
	$smarty -> assign('word_link'		, mymps_get_flink(9,'wordlink'));//文字链接
	$smarty -> assign('img_link'		, mymps_get_flink(9,'imglink'));//图片链接
	$smarty -> assign("focus"			, mymps_get_focus(3));//焦点图
	$smarty -> assign('count_info'		, mymps_count('information'));//信息统计
	/*
	$smarty -> assign('info_list_hot'	, mymps_get_info_list(8,2,'','',''));//推荐信息
	*/
	$smarty -> assign('info_list_hot'	, get_hotclick_info());//点击最多的信息
	$smarty -> assign('count_online'	, rand(5,35));//在线统计（欺骗	Add By 01
	
}

$smarty -> display(mymps_tpl("index","smarty"));
?>