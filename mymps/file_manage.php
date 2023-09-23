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
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_DATA."/config.inc.php");
require_once(MYMPS_INC."/db.class.php");

$part = $part ? $part : 'template' ;

if ($downfile) {
	if (!is_file($downfile)) {
		write_msg("��Ҫ���ص��ļ������ڣ�");
		exit();
	}
	if(FileExt($downfile)=='php'){write_msg("���ļ�����������!");exit();}
	$filename = basename($downfile);
	$filename_info = explode('.', $filename);
	$fileext = $filename_info[count($filename_info)-1];
	header('Content-type: application/x-'.$fileext);
	header('Content-Disposition: attachment; filename='.$filename);
	header('Content-Description: PHP3 Generated Data');
	readfile($downfile);
	exit();
}


if($delfile!=""){

	if($part == 'template'){
		write_msg("ģ���ļ�����ɾ�������ֶ���FTPĿ¼�н���ɾ����");
		exit();
	}
	
	if(FileExt($delfile) == 'php' || FileExt($delfile) == 'css' || FileExt($delfile) == 'js' || FileExt($delfile) == 'html'){
		write_msg("���ļ�������ɾ��������FTPĿ¼���ֶ�ɾ����");
		exit();
	}
	
	if(file_exists($delfile)) {
		@unlink($delfile);
		$msgs[]="�ɹ�ɾ���ļ�<br /><br />".$delfile;
		$msgs[]="<a href=\"".$url."\">��˷��� &raquo;</a>";
		show_msg($msgs);
	} else {
		write_msg("�ļ��Ѳ����ڣ�");
	}
	exit();
	
}

if($mymps_mymps[cfg_if_tpledit]==0){
	$cfg_if_tpledit = "<font color=green>�ѹر�</font>";
}else{
	$cfg_if_tpledit = "<font color=red>�ѿ���</font>";
}

switch ($part){
	case 'template':
		chk_admin_purview("purview_ģ�����");
		$here = "ģ�����߹���";
		$mulu = "Mympsģ��Ŀ¼";
		$showdir= MYMPS_TPL.'/'.$mymps_global[cfg_tpl_dir];
		
		if ($editfile&&$do=='update'){
		
			require_once(MYMPS_INC."/db.class.php");
			
			if($mymps_mymps['cfg_if_tpledit']=='0'){
				write_msg("����ʧ�ܣ�ϵͳ����Ա�ر������߱༭���Ĺ���!<br /><br />�������޸�/data/config.inc.php��������");
				exit();
			}
			
			$content=str_replace('&amp;','&',trim($content));
			$content=str_replace('&quot;','"',trim($content));
			$nowfile = trim($editfile);
			
			if(!is_file($nowfile)){
				write_msg("�Բ��𣬸��ļ������ڣ�");
				exit();
			}
			
			$norootfile = str_replace(MYMPS_ROOT."/template",'',$nowfile);
			
			if($db->getOne("SELECT content FROM `{$db_mymps}template` WHERE filepath LIKE '$norootfile'")){
				$update_sql=$db->query("UPDATE `{$db_mymps}template` SET content = '$content' WHERE filepath = '$norootfile'");
			}else{
				$db->query("INSERT INTO `{$db_mymps}template` (filepath,content) VALUES ('$norootfile','$content')");
			}

			$row=$db->getRow("SELECT filepath,content FROM `{$db_mymps}template` WHERE filepath = '$norootfile'");
			if(!$row){write_msg("����ʧ�ܣ�");exit();}
			$create_c=createfile($nowfile,$row[content]);
			if($create_c){
				write_msg("ģ���ļ�".$nowfile."<br /><br />�޸ĳɹ�",$url,"MyMps");
			}else{
				write_msg("ģ���ļ�".$nowfile."�޷��޸�<br /><br />����templateĿ¼�Ĳ���Ȩ��!");
			}
			exit();
			
		} elseif ($editfile&&empty($do)){
		
			$ext = FileExt($editfile);
			
			if($ext!="html" && $ext!="css" && $ext!="htm" && $ext!="js"){
				write_msg("���ļ��������߱༭!");
				exit();
			}
			
			$edit=file_get_contents($editfile);
			
			if(!edit){write_msg("���ļ����ɶ���������ļ��Ĳ���Ȩ��");exit();}
			$path = str_replace("/".end(explode("/",$editfile)),"",$editfile);
			$edit=htmlspecialchars($edit);
			$acontent = "<textarea name=\"content\" cols=\"110\" rows=\"25\">".$edit."</textarea>";
			include(mymps_tpl("template_edit"));
			exit();
			
		}
	break;
	case 'upload':
	
		chk_admin_purview("purview_��������");
		$here = "ϵͳ�ϴ���������";
		$mulu = "ϵͳ����Ŀ¼";
		$showdir= MYMPS_UPLOAD;
		
	break;
}

$path	   = trim($path) ? trim($path) : $showdir;
$LastPath  = str_replace("/".end(explode("/",$path)),"",$path);
$con 	   = explode($showdir,$CurrentPath);

include(mymps_tpl("file_manage"));
?>