<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ������������ǽ�׷�����ķ�������!!
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/
$part = isset($part)? trim($part) : 'list' ;

require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

if($part=='phpinfo'){
	$here = "ϵͳ����";
	echo mymps_admin_tpl_global_head();
	phpinfo();
	echo mymps_admin_tpl_global_foot();
	exit();
}

if($part == 'http'){
	$here = $msg?$msg:"mymps�ٷ���վ";
	echo mymps_admin_tpl_global_head();
	echo "<iframe src='$url' frameborder='0' scrolling='yes' width='100%' height='100%'></iframe>";
	echo mymps_admin_tpl_global_foot();
	exit();
}

require_once(MYMPS_DATA."/filter.php");
require_once(MYMPS_DATA."/config.cache.php");

$cfg_badwords0=str_replace('��',',',trim($cfg_badwords0));
$cfg_badwords1=str_replace('��',',',trim($cfg_badwords1));
$cfg_badwords2=intval($cfg_badwords2);

require_once(dirname(__FILE__)."/include/config.inc.php");

if($part == 'list'){

	chk_admin_purview("purview_ϵͳ����");
	$sql = "SELECT description,value FROM {$db_mymps}config";
	$res = $db->query($sql);
	while($row = $db->fetchRow($res)){
		$config_global[$row['description']] = $row['value'];
	}
	$here = "Mymps ϵͳ��������";
	include(mymps_tpl("mymps_config"));
	
}elseif($part == 'update'){	

	$info .="\$mymps_global = array(\n\n";
	
	foreach($admin_global as $k => $a){
		$info_k = str_replace('��',',',my_addslashes($_POST[$k]));
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
	if(!$write_c){write_msg(MYMPS_DATA."/config.php �ļ�����д��������ӦȨ��");exit();}
	
	$badwords_settings='
$cfg_badwords = array();
$cfg_badwords[0]="'.$cfg_badwords0.'";
$cfg_badwords[1]= "'.$cfg_badwords1.'";
$cfg_badwords[2]= "'.$cfg_badwords2.'";
?>';
	$write_f=createfile(MYMPS_DATA.'/filter.php',$start.$badwords_settings);
	
	if(!$write_f){write_msg(MYMPS_DATA."/filter.php �ļ�����д��������ӦȨ��");exit();}
	$create_d=createdir(MYMPS_DATA.$mymps_global[cfg_backup_dir]);
	if(!$write_f){write_msg("�޷�����Ŀ¼�����ֶ��������ݿⱸ��Ŀ¼ ".MYMPS_DATA.$mymps_global[cfg_backup_dir]);exit();}
	write_msg ("ϵͳ�������óɹ���","mymps_config.php","record");
	
}elseif($part == 'cache'){

	chk_admin_purview("purview_��������");
	$here = "Mymps ϵͳ��������";
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
	if(!$create_c){write_msg(MYMPS_DATA."/config.cache.php �ļ�����д��������ӦȨ��");exit();}
	write_msg ("ϵͳ�������óɹ���","mymps_config.php?part=cache","record");
	
}elseif($part == 'update_cache'){

	write_class_cache();
	write_class_cache("area","area");
	write_announce_cache();
	write_msg('ϵͳ������³ɹ���','?part=cache');
	
}elseif($part == 'clearcache'){

	require_once(MYMPS_SMARTY."/include.php");
	if($action == 'all'){
		$mymps_do=$smarty->clear_all_cache();//
		if($mymps_do){
			write_msg("�ɹ����ϵͳ���л���ҳ��!","?part=cache","MyMps");
		}else{
			write_msg("����ҳ�����ʧ�ܣ�");
		}
	}else{
		$mymps_do=$smarty->clear_cache(MYMPS_TPL."/".$mymps_global[cfg_tpl_dir]."/".$action);
		if($mymps_do){
			write_msg("�ɹ������վ��ҳ����ҳ��!","?part=cache","MyMps");
		}else{
			write_msg("ָ������ҳ�����ʧ�ܣ�");
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
		if(!$create_c){write_msg(MYMPS_DATA."/config.imgcode.php �ļ�����д��������ӦȨ��");exit();}
		write_msg ("��֤����ʾ�������óɹ���","?part=imgcode","record");
		
	}else{
	
		$here = "Mymps ϵͳע������ʾ����";
		include(mymps_tpl("mymps_imgcode"));
		
	}
	
}
?>