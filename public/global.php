<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长） Author:steven chinawebmaster@yeah.net
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
define('IN_MYMPS', true);
require_once(dirname(__FILE__)."/../include/global.inc.php");
require_once(MYMPS_INC."/global.php");
require_once(MYMPS_DATA."/config.php");
require_once(MYMPS_DATA."/config.db.php");
require_once(MYMPS_INC."/db.class.php");
require_once(MYMPS_DATA."/category.inc.php");
require_once(MYMPS_DATA."/area.inc.php");
require_once(MYMPS_DATA."/announce.inc.php");
require_once(MYMPS_DATA."/config.cache.php");
require_once(MYMPS_SMARTY."/include.php");
header('content-type: text/html; charset='.$charset);
if($mymps_global['cfg_if_site_open'] != 1){
	$msgs[] = "<h4 style=\"color:red;margin:-15px 0 0 0\">网站已关闭，可能原因：<br />";
	$msgs[] = $mymps_global[cfg_site_open_reason];
	$msgs[] = "</h4>";
	show_msg($msgs);
	exit();
}
function if_other_site_post(){
	$servername=$HTTP_SERVER_VARS['SERVER_NAME'];
	$sub_from=$HTTP_SERVER_VARS["HTTP_REFERER"];
	$sub_len=strlen($servername);
	$checkfrom=substr($sub_from,7,$sub_len);
	if($checkfrom != $servername){
		return true;
	}else{
		return false;
	}
}
function HighLight($str, $keywords, $color = "red"){
	if (empty($keywords)) {
		return $str;
	}
	$keywords = split("[ \t\r\n,]+", $keywords);
	foreach($keywords as $val) {
		$tvar = eregi($val, $str, $regs);
		$finalrep    = "<font color=". $color . ">" . $regs[0] . "</font>";
	}
	$str = str_replace($regs[0], $finalrep, $str);
	return $str;
}
function convertip_tiny($ip, $ipdatafile){
	static $fp = NULL, $offset = array(), $index = NULL;

	$ipdot = explode('.', $ip);
	$ip    = pack('N', ip2long($ip));

	$ipdot[0] = (int)$ipdot[0];
	$ipdot[1] = (int)$ipdot[1];

	if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
		$offset = unpack('Nlen', fread($fp, 4));
		$index  = fread($fp, $offset['len'] - 4);
	}
	elseif($fp == FALSE) {
		return  '- Invalid IP data file';
	}
	$length = $offset['len'] - 1028;
	$start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8){
		if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip){
			$index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
			$index_length = unpack('Clen', $index{$start + 7});
			break;
		}
	}
	fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
	if($index_length['len']) {
		return fread($fp, $index_length['len']);
	} 
	else {
		return '- Unknown';
	}
}
function ip2area($ip){
	$return = '';
	if(preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {
		$iparray = explode('.', $ip);

		if($iparray[0] == 10 || $iparray[0] == 127 || ($iparray[0] == 192 && $iparray[1] == 168) || ($iparray[0] == 172 && ($iparray[1] >= 16 && $iparray[1] <= 31))) {
			$return = '- LAN';
		} elseif($iparray[0] > 255 || $iparray[1] > 255 || $iparray[2] > 255 || $iparray[3] > 255){
			$return = '- Invalid IP Address';
		} else {
			$tinyipfile = MYMPS_DATA.'/ipdat/tinyipdata.dat';
			$return = convertip_tiny($ip, $tinyipfile);
		}
	}
	return $return;
}

function verify_badwords_filter($ifopenname,$title='',$content=''){
	global $mymps_global,$cfg_badwords;
	require_once(MYMPS_DATA."/filter.php");
	if($ifopenname==1){
		$info_level = 0;
		if(is_array($cfg_badwords)){
			$title=$title?SpHtml2Text(filter_bad_words($title)):'';
			$content=$content?filter_bad_words($content):'';
		}
	}
	else{
		if(is_array($cfg_badwords)){
			if($cfg_badwords[0] && $cfg_badwords[2]==1){
				foreach(explode(',',$cfg_badwords[0]) as $k => $v){
					if(strstr($title,$v)||strstr($content,$v)){
						$info_level = 0;
						break;
					}else{
						$info_level = 1;
					}
				}
			}elseif($cfg_badwords[2]==0){
				$title=SpHtml2Text(filter_bad_words($title));
				$content=filter_bad_words($content);
				$info_level = 1;
			}else{
				$info_level = 1;
			}
		}else{
			$info_level = 1;
		}
	}
	$verified_result = array();
	$verified_result[title]	  =$title;
	$verified_result[content] =$content;
	$verified_result[level]	  =$info_level;
	return $verified_result;
}

$SiteName = $mymps_global[SiteName]." - 福州|大学城分类信息 - 舍内网 ｜".$mymps_global[SiteSeoName];	//Modify By 01

function mymps_get_focus($num=5){
	global $db,$db_mymps;
	return $db->getAll("SELECT * FROM `{$db_mymps}focus` ORDER BY focusorder DESC LIMIT 0,".$num."");
}

function mymps_get_flink($num=12,$type='wordlink'){
	global $db,$db_mymps;
	if ($type == "wordlink"){
		$where .= 'WHERE ischeck = "2" AND weblogo = ""';
	}elseif($type == "imglink"){
		$where .= 'WHERE ischeck = "2" AND weblogo !=""';
	}
	$flink = $db ->getAll("SELECT id,url,webname,ordernumber,weblogo FROM {$db_mymps}flink $where ORDER BY ordernumber ASC LIMIT 0,".$num."");
	return $flink ? $flink : false;
}

function mymps_get_faq($num=5,$typeid=''){
	global $db,$db_mymps;
	$where  = $typeid ? "WHERE typeid = $typeid" : '';
	$sql 	= "SELECT id,typeid,title FROM {$db_mymps}faq $where ORDER BY id ASC LIMIT 0,".$num;
	$do_mymps = $db -> query($sql);
	if($do_mymps){
		while($row = $db -> fetchRow($do_mymps)){
			$arr['id']        	= $row['id'];
			$arr['title'] 		= $row['title'];
			$arr['uri']      	= Rewrite('about',array('part'=>'faq','id'=>$row['id']));
			$faq_list[]      	= $arr;
		}
	}
	return $faq_list;
}

function mymps_get_announce($num=''){
	global $announce_cache,$mymps_global;
	$announce_num=$num?$num:$mymps_global[cfg_num_announce];
	if(is_array($announce_cache)){
		$annouce = array();
		foreach($announce_cache as $k => $v){
			$announce[$k]['uri']= Rewrite('about',array('part'=>'announce','id'=>$v['id'],'page'=>$page));
			$announce[$k]['id']         = $v['id'];
			$announce[$k]['title']      = $v['title'];
			$announce[$k]['titlecolor'] = $v['titlecolor'];
			$announce[$k]['pubdate'] 	= $v['pubdate'];
		}
	}
	return $announce;
}

function mymps_get_member_list($num='',$level=''){
	global $db,$db_mymps,$mymps_global;
	$where = $level?'WHERE levelid = '.$level.'':'';
	$sql = "SELECT id,userid,cname,jointime,logintime FROM `{$db_mymps}member` $where ORDER BY jointime DESC LIMIT 0,".$num;
	$do_mymps = $db -> query($sql);
	if($do_mymps){
		while($row = $db -> fetchRow($do_mymps)){
			$arr['id']        	= $row['id'];
			$arr['userid']   	= $row['userid'];
			$arr['cname'] 		= $row['cname'];
			$arr['jointime'] 	= $row['jointime'];
			$arr['logintime']	= $row['logintime'];
			$arr['uri']      	= Rewrite('space',array('user'=>$row['userid']));
			$member_list[]      = $arr;
		}
	}
	return $member_list;
}

function mymps_get_info_list($num=10,$info_level='',$upgrade_type='',$userid='',$catid=''){
	global $db,$db_mymps,$mymps_global;
	$where .= empty($info_level)? 'WHERE a.info_level > 0':'WHERE a.info_level = '.$info_level;
	$where .= $userid			? ' AND a.userid = "'.$userid.'"' : '';
	$where .= $catid			? ' AND a.catid IN ('.get_cat_children($catid).')':'';
	$where .= ($upgrade_type == '')? '' : ' AND a.upgrade_type = '.$upgrade_type.' AND a.upgrade_time >= '.time();
	$sql = "SELECT a.id,a.title,a.begintime,a.info_level,a.hit,b.areaname,c.catname FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}area` AS b ON a.areaid = b.areaid LEFT JOIN `{$db_mymps}category` AS c ON a.catid = c.catid $where ORDER BY a.begintime DESC LIMIT 0,".$num."";
	$do_mymps = $db -> query($sql);
	if($do_mymps){
		while($row = $db -> fetchRow($do_mymps)){
			$arr['id']        = $row['id'];
			$arr['title']     = $row['title'];
			$arr['hit']    	  = $row['hit'];
			$arr['begintime'] = GetTime($row['begintime']);
			$arr['areaname']  = $row['areaname'];
			$arr['catname']   = $row['catname'];
			$arr['info_level']= $row['info_level'];
			$arr['uri']       = Rewrite('info',array('id'=>$row['id']));
			$info_list[]      = $arr;
		}
	}
	return $info_list;
}

//取得点击最多的10篇新闻
function get_hotclick_info($num = 10){
	global $db,$db_mymps;
	$sql = "SELECT * FROM `{$db_mymps}information` WHERE info_level > 0 ORDER BY hit DESC,begintime DESC LIMIT 0,".$num;
	$do_mymps = $db -> query($sql);
	if($do_mymps){
		while($row = $db -> fetchRow($do_mymps)){
			$arr['id']        = $row['id'];
			$arr['title']     = $row['title'];
			$arr['hit']    	  = $row['hit'];
			$arr['begintime'] = GetTime($row['begintime']);
			$arr['areaname']  = $row['areaname'];
			$arr['catname']   = $row['catname'];
			$arr['info_level']= $row['info_level'];
			$arr['uri']       = Rewrite('info',array('id'=>$row['id']));
			$info_list[]      = $arr;
		}
	}
	return $info_list;
}

function FirstSecond($catid){
	global $db,$db_mymps;
	$row = $db->getRow("SELECT parentid FROM `{$db_mymps}category` WHERE catid = '$catid'");
	if(!$row){write_msg("您指定的分类不存在");exit();}
	$result = ($row[parentid] == 0)?"first":"second";
	return $result;
}

function mymps_global_header(){
	global $charset,$mymps_global;
	return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$charset."\" />
<meta http-equiv=\"Content-Language\" content=\"zh-CN\"/>
<meta name=\"generator\" content=\"".MPS_SOFTNAME.MPS_VERSION."\"/>
<meta name=\"author\" content=\"".MPS_SOFTNAME." DevTeam Corporation\" />
<link rel=\"shortcut icon\" href=\"".$mymps_global[SiteUrl]."/favicon.ico\" />";
}

function mymps_global_assign(){
	global $smarty,$mymps_global,$SiteName,$category,$category_cache,$area_cache,$cat,$catid,$areaid;
	$Shop_url = "http://shop.shenei.net/";	//Add By 01
	if(is_array($category_cache)){
		$category = array();
		foreach($category_cache as $k => $v){
			$category[$k]['uri'] 		= Rewrite('info',array('catid'=>$v['catid'],'page'=>$page));
			$category[$k]['catid'] 		= $v['catid'];
			$category[$k]['catname']	= $v['catname'];
			$category[$k]['parentid'] 	= $v['parentid'];
			$category[$k]['parentname'] = $v['parentname'];
		}
	}
	$about[aboutus_uri]				= Rewrite('about',array('part'=>'aboutus'));
	$about[faq_uri]					= Rewrite('about',array('part'=>'faq'));
	$about[friendlink_uri]			= Rewrite('about',array('part'=>'friendlink'));
	$about[guestbook_uri]			= Rewrite('about',array('part'=>'guestbook','page'=>'1'));
	$about[announce_uri]			= Rewrite('about',array('part'=>'announce','page'=>'1'));

	$smarty -> assign('category',$category);
	$smarty -> assign('about',$about);
	$smarty -> assign('mymps_global_header',mymps_global_header());
	$smarty -> assign('cat_option',cache_cat_options($cat[catid]));
	$smarty -> assign('area_option',area_options($areaid));
	$smarty -> assign("SiteName",$SiteName);
	$smarty -> assign("MPS_VERSION",MPS_VERSION);
	$smarty -> assign("mymps_global",$mymps_global);
	$smarty -> assign("Shop_url",$Shop_url);	//Add By 01
}

function get_info_option_des($identifier){
	global $db,$db_mymps;
	$mymps = $db->getRow("SELECT title,type,rules FROM `{$db_mymps}info_typeoptions` WHERE identifier = '$identifier'");
	$arr=array();
	$arr[title]  	= $mymps[title];
	$arr[type] 		= $mymps[type];
	$arr[rules]		= $mymps[rules];
	return $arr;
}

function clear_mirror($str){
	$str = str_replace('-','',$str);
	$str = str_replace('+','',$str);
	return $str;
}

function part_ip($ip){
	$reg = '/((?:\d+\.){3})\d+/';
	return preg_replace($reg, "\\1*",$ip);
}

function get_cat_info($catid = 0 ,$type = 'list'){
	global $db,$db_mymps,$category_cache,$category_cache_s;
	if($type == 'list'){
		if($catid == 0){
			$mymps = $category_cache;
		} else {
			$mymps = $category_cache_s[$catid];
		}
	}elseif($type == 'frow'){
		$mymps = $GLOBALS['db'] -> getRow("SELECT * FROM `{$GLOBALS['db_mymps']}category` WHERE catid = $catid");
	}elseif($type == 'srow'){
		$mymps = $GLOBALS['db'] -> getRow("SELECT a.catid as scatid,a.catname as scatname,b.catid,b.catname,a.notice,a.if_upimg,a.modid,a.keywords,a.description FROM `{$GLOBALS['db_mymps']}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = $catid");
	}
	return $mymps;
}
?>