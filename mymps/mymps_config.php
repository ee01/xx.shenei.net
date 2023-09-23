<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。否则我们将追究您的法律责任!!
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
$part = isset($part)? trim($part) : 'list' ;

require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

if($part=='phpinfo'){
	$here = "系统环境";
	echo mymps_admin_tpl_global_head();
	phpinfo();
	echo mymps_admin_tpl_global_foot();
	exit();
}

if($part == 'http'){
	$here = $msg?$msg:"mymps官方网站";
	echo mymps_admin_tpl_global_head();
	echo "<iframe src='$url' frameborder='0' scrolling='yes' width='100%' height='100%'></iframe>";
	echo mymps_admin_tpl_global_foot();
	exit();
}

require_once(MYMPS_DATA."/filter.php");
require_once(MYMPS_DATA."/config.cache.php");

$cfg_badwords0=str_replace('，',',',trim($cfg_badwords0));
$cfg_badwords1=str_replace('，',',',trim($cfg_badwords1));
$cfg_badwords2=intval($cfg_badwords2);

require_once(dirname(__FILE__)."/include/config.inc.php");

if($part == 'list'){

	chk_admin_purview("purview_系统配置");
	$sql = "SELECT description,value FROM {$db_mymps}config";
	$res = $db->query($sql);
	while($row = $db->fetchRow($res)){
		$config_global[$row['description']] = $row['value'];
	}
	$here = "Mymps 系统参数设置";
	include(mymps_tpl("mymps_config"));
	
}elseif($part == 'update'){	

	$info .="\$mymps_global = array(\n\n";
	
	foreach($admin_global as $k => $a){
		$info_k = str_replace('，',',',my_addslashes($_POST[$k]));
		$info .= "\"".$k."\"=>\"".$info_k."\",\n\r";
		if(mymps_count("config","WHERE description = '$k'") > 0){
			$sql = "UPDATE `{$db_mymps}config` SET value = '".$info_k."' WHERE description='".$k."'";
		}else{
			$sql ="INSERT INTO `{$db_mymps}config` (value,description) VALUES ('".$info_k."','".$k."')";
		}
		$db->query($sql);
	}
	
	$info .="\n);\n\r?>";
	$write_c=createfile(MYMPS_DATA.'/config.php',$start.$info);
	if(!$write_c){write_msg(MYMPS_DATA."/config.php 文件不可写，请检查相应权限");exit();}
	
	$badwords_settings='
$cfg_badwords = array();
$cfg_badwords[0]="'.$cfg_badwords0.'";
$cfg_badwords[1]= "'.$cfg_badwords1.'";
$cfg_badwords[2]= "'.$cfg_badwords2.'";
?>';
	$write_f=createfile(MYMPS_DATA.'/filter.php',$start.$badwords_settings);
	
	if(!$write_f){write_msg(MYMPS_DATA."/filter.php 文件不可写，请检查相应权限");exit();}
	$create_d=createdir(MYMPS_DATA.$mymps_global[cfg_backup_dir]);
	if(!$write_f){write_msg("无法建立目录，请手动建立数据库备份目录 ".MYMPS_DATA.$mymps_global[cfg_backup_dir]);exit();}
	write_msg ("系统参数设置成功！","mymps_config.php","record");
	
}elseif($part == 'cache'){

	chk_admin_purview("purview_缓存设置");
	$here = "Mymps 系统缓存设置";
	include(mymps_tpl("mymps_cache"));
	
}elseif($part == 'cacheupdate'){

	$info .="\$mymps_cache = array(";
	
	foreach($admin_cache as $q => $w){
		foreach($w as $k => $a){
			$info .= "//".$a["des"]."\n\"".$k."\"=> array(\"time\"=>\"".intval($_POST[$k."_time"])."\",";
			$info .= "\"open\"=>\"".intval($_POST[$k."_open"])."\"),\n\r";
		}
	}
	
	$info .="\n);\n\r?>";
	
	$create_c=createfile(MYMPS_DATA.'/config.cache.php',$start.$info);
	if(!$create_c){write_msg(MYMPS_DATA."/config.cache.php 文件不可写，请检查相应权限");exit();}
	write_msg ("系统缓存设置成功！","mymps_config.php?part=cache","record");
	
}elseif($part == 'update_cache'){

	write_class_cache();
	write_class_cache("area","area");
	write_announce_cache();
	write_msg('系统缓存更新成功！','?part=cache');
	
}elseif($part == 'clearcache'){

	require_once(MYMPS_SMARTY."/include.php");
	if($action == 'all'){
		$mymps_do=$smarty->clear_all_cache();//
		if($mymps_do){
			write_msg("成功清除系统所有缓存页面!","?part=cache","MyMps");
		}else{
			write_msg("缓存页面清除失败！");
		}
	}else{
		$mymps_do=$smarty->clear_cache(MYMPS_TPL."/".$mymps_global[cfg_tpl_dir]."/".$action);
		if($mymps_do){
			write_msg("成功清除网站首页缓存页面!","?part=cache","MyMps");
		}else{
			write_msg("指定缓存页面清除失败！");
		}
	}
	
}elseif($part == 'imgcode'){

	require_once(dirname(__FILE__)."/include/imgcode.inc.php");
	require_once(MYMPS_DATA."/config.imgcode.php");
	
	if(trim($action) == 'do_post'){
		$info .="\$mymps_imgcode = array(";
		foreach($imgcode_arr as $q => $w){
			foreach($w as $k => $a){
				$info .= "//".$a["name"]."\n\"".$k."\"=> array(";
				$info .= "\"open\"=>\"".intval($_POST[$k."_open"])."\"),\n\r";
			}
		}
		$info .="\n);\n\r?>";
		$create_c=createfile(MYMPS_DATA.'/config.imgcode.php',$start.$info);
		if(!$create_c){write_msg(MYMPS_DATA."/config.imgcode.php 文件不可写，请检查相应权限");exit();}
		write_msg ("验证码显示控制设置成功！","?part=imgcode","record");
		
	}else{
	
		$here = "Mymps 系统注册码显示控制";
		include(mymps_tpl("mymps_imgcode"));
		
	}
	
}
?>