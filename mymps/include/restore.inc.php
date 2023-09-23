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
if(!$act&&!$_SESSION['data_file']){
	chk_admin_purview("purview_数据库还原");
	$here ="Mymps系统之数据库还原";
	include(mymps_tpl("mymps_restore"));
}

if($act=="dodo"){

	if($restorefrom=="server"){
	
		if(!$serverfile){
			$msgs[]="您选择从服务器文件恢复备份，但没有指定备份文件";
			show_msg($msgs,"record"); 
			exit();	
		}
		
		if(!eregi("_v[0-9]+",$serverfile)){
		
			$filename=$backup_dir.$serverfile;
			if(import($filename)) $msgs[]="备份文件 [".$serverfile."] 成功导入数据库<br /><br /><a href=database.php?part=restore>点此返回</a>";
			else $msgs[]="备份文件 [".$serverfile."] 导入失败<br /><br /><a href=database.php?part=restore>点此返回</a>";show_msg($msgs,"record"); 
			exit();	
			
		}else{
		
			$filename=$backup_dir.$serverfile;
			if(import($filename)) $msgs[]="备份文件 [".$serverfile."] 成功导入数据库";
			else {
				$msgs[]="备份文件 [".$serverfile."] 导入失败<br /><br /><a href=database.php?part=restore>点此返回</a>";show_msg($msgs,"record");exit();
			}
			
			$voltmp=explode("_v",$serverfile);
			$volname=$voltmp[0];
			$volnum=explode(".sq",$voltmp[1]);
			$volnum=intval($volnum[0])+1;
			$tmpfile=$volname."_v".$volnum.".sql";
			if(file_exists($backup_dir.$tmpfile)){
				$msgs[]="程序将在3秒钟后自动开始导入此分卷备份的下一部份：文件 [".$tmpfile."] ，请勿手动中止程序的运行，以免数据库结构受损";
				$_SESSION['data_file']=$tmpfile;
				show_msg($msgs,"record");
				sleep(3);
				echo mymps_goto('database.php?part=restore');
			}else{
				$msgs[]="<strong>全部数据导入成功！！</strong><br /><br /><a href=database.php?part=restore>点此返回</a>";
				show_msg($msgs,"record");
			}
		}
	}
	if($restorefrom=="localpc"){
	
		switch ($_FILES['myfile']['error']){
			case 1:
			case 2:
			$msgs[]="您上传的文件大于服务器限定值，上传未成功<br /><br /><a href=database.php?part=restore>点此返回</a>";
			break;
			case 3:
			$msgs[]="未能从本地完整上传备份文件<br /><br /><a href=database.php?part=restore>点此返回</a>";
			break;
			case 4:
			$msgs[]="从本地上传备份文件失败<br /><br /><a href=database.php?part=restore>点此返回</a>";
			break;
			case 0:
			break;
			
		}
		
		if($msgs){
			show_msg($msgs,"record");
			exit();
		}
		
		$fname=date("Ymd",time())."_up.sql";
		
		if(FileExt($_FILES['myfile']['name'])!='sql'){
			write_msg("您指定的文件不允许上传！！！！！");
			exit();
		}
		
		if (is_uploaded_file($_FILES['myfile']['tmp_name'])){
			copy($_FILES['myfile']['tmp_name'], $backup_dir.$fname);
		}
		
		if (file_exists($backup_dir.$fname)){
			$msgs[]="本地备份文件上传成功";
			if(import($backup_dir.$fname)){$msgs[]="本地备份文件成功导入数据库"; @unlink($backup_dir.$fname);}
			else $msgs[]="本地备份文件导入数据库失败";
		}
		
		else($msgs[]="从本地上传备份文件失败");
		show_msg($msgs,"record");
	}
	
}

if(!$act&&$_SESSION['data_file']){

	$filename=$backup_dir.$_SESSION['data_file'];
	if(import($filename)) $msgs[]="备份文件 [".$_SESSION['data_file']."] 成功导入数据库";
	else {$msgs[]="备份文件".$_SESSION['data_file']."导入失败";show_msg($msgs,"record");exit();}
	$voltmp=explode("_v",$_SESSION['data_file']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists($backup_dir.$tmpfile)){
		$msgs[]="程序将在3秒钟后自动开始导入此分卷备份的下一部份：文件 [".$tmpfile."] ，请勿手动中止程序的运行，以免数据库结构受损";
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs,"record");
		sleep(3);
		echo mymps_goto('database.php?part=restore');
	}else{
		$msgs[]="<strong>全部数据导入成功！！</strong><br /><br /><a href=database.php?part=restore>点此返回</a>";
		unset($_SESSION['data_file']);
		show_msg($msgs,"record");
	}
	
}
?>