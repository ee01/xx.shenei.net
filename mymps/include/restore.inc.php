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
if(!$act&&!$_SESSION['data_file']){
	chk_admin_purview("purview_���ݿ⻹ԭ");
	$here ="Mympsϵͳ֮���ݿ⻹ԭ";
	include(mymps_tpl("mymps_restore"));
}

if($act=="dodo"){

	if($restorefrom=="server"){
	
		if(!$serverfile){
			$msgs[]="��ѡ��ӷ������ļ��ָ����ݣ���û��ָ�������ļ�";
			show_msg($msgs,"record"); 
			exit();	
		}
		
		if(!eregi("_v[0-9]+",$serverfile)){
		
			$filename=$backup_dir.$serverfile;
			if(import($filename)) $msgs[]="�����ļ� [".$serverfile."] �ɹ��������ݿ�<br /><br /><a href=database.php?part=restore>��˷���</a>";
			else $msgs[]="�����ļ� [".$serverfile."] ����ʧ��<br /><br /><a href=database.php?part=restore>��˷���</a>";show_msg($msgs,"record"); 
			exit();	
			
		}else{
		
			$filename=$backup_dir.$serverfile;
			if(import($filename)) $msgs[]="�����ļ� [".$serverfile."] �ɹ��������ݿ�";
			else {
				$msgs[]="�����ļ� [".$serverfile."] ����ʧ��<br /><br /><a href=database.php?part=restore>��˷���</a>";show_msg($msgs,"record");exit();
			}
			
			$voltmp=explode("_v",$serverfile);
			$volname=$voltmp[0];
			$volnum=explode(".sq",$voltmp[1]);
			$volnum=intval($volnum[0])+1;
			$tmpfile=$volname."_v".$volnum.".sql";
			if(file_exists($backup_dir.$tmpfile)){
				$msgs[]="������3���Ӻ��Զ���ʼ����˷־��ݵ���һ���ݣ��ļ� [".$tmpfile."] �������ֶ���ֹ��������У��������ݿ�ṹ����";
				$_SESSION['data_file']=$tmpfile;
				show_msg($msgs,"record");
				sleep(3);
				echo mymps_goto('database.php?part=restore');
			}else{
				$msgs[]="<strong>ȫ�����ݵ���ɹ�����</strong><br /><br /><a href=database.php?part=restore>��˷���</a>";
				show_msg($msgs,"record");
			}
		}
	}
	if($restorefrom=="localpc"){
	
		switch ($_FILES['myfile']['error']){
			case 1:
			case 2:
			$msgs[]="���ϴ����ļ����ڷ������޶�ֵ���ϴ�δ�ɹ�<br /><br /><a href=database.php?part=restore>��˷���</a>";
			break;
			case 3:
			$msgs[]="δ�ܴӱ��������ϴ������ļ�<br /><br /><a href=database.php?part=restore>��˷���</a>";
			break;
			case 4:
			$msgs[]="�ӱ����ϴ������ļ�ʧ��<br /><br /><a href=database.php?part=restore>��˷���</a>";
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
			write_msg("��ָ�����ļ��������ϴ�����������");
			exit();
		}
		
		if (is_uploaded_file($_FILES['myfile']['tmp_name'])){
			copy($_FILES['myfile']['tmp_name'], $backup_dir.$fname);
		}
		
		if (file_exists($backup_dir.$fname)){
			$msgs[]="���ر����ļ��ϴ��ɹ�";
			if(import($backup_dir.$fname)){$msgs[]="���ر����ļ��ɹ��������ݿ�"; @unlink($backup_dir.$fname);}
			else $msgs[]="���ر����ļ��������ݿ�ʧ��";
		}
		
		else($msgs[]="�ӱ����ϴ������ļ�ʧ��");
		show_msg($msgs,"record");
	}
	
}

if(!$act&&$_SESSION['data_file']){

	$filename=$backup_dir.$_SESSION['data_file'];
	if(import($filename)) $msgs[]="�����ļ� [".$_SESSION['data_file']."] �ɹ��������ݿ�";
	else {$msgs[]="�����ļ�".$_SESSION['data_file']."����ʧ��";show_msg($msgs,"record");exit();}
	$voltmp=explode("_v",$_SESSION['data_file']);
	$volname=$voltmp[0];
	$volnum=explode(".sq",$voltmp[1]);
	$volnum=intval($volnum[0])+1;
	$tmpfile=$volname."_v".$volnum.".sql";
	if(file_exists($backup_dir.$tmpfile)){
		$msgs[]="������3���Ӻ��Զ���ʼ����˷־��ݵ���һ���ݣ��ļ� [".$tmpfile."] �������ֶ���ֹ��������У��������ݿ�ṹ����";
		$_SESSION['data_file']=$tmpfile;
		show_msg($msgs,"record");
		sleep(3);
		echo mymps_goto('database.php?part=restore');
	}else{
		$msgs[]="<strong>ȫ�����ݵ���ɹ�����</strong><br /><br /><a href=database.php?part=restore>��˷���</a>";
		unset($_SESSION['data_file']);
		show_msg($msgs,"record");
	}
	
}
?>