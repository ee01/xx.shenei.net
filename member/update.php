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
include("global.php");
require_once(MYMPS_INC."/fckeditor/fckeditor.php");
require_once(MYMPS_DATA."/area.inc.php");

$part = $part ? $part : 'about' ;
$sql="SELECT a.id,a.userid,a.cname,a.areaid,a.sex,a.tel,a.address,a.place,a.qq,a.msn,a.email,a.web,b.areaname,a.jointime,a.levelid,a.safequestion FROM `{$db_mymps}member` AS a LEFT JOIN {$db_mymps}area AS b ON a.areaid = b.areaid WHERE userid ='$s_uid'";
$update = $db -> getRow($sql);

if ($part == 'contact'){
	$url=trim($_GET['url']);
	$url = $url ? $url : 'update.php?part=contact' ;
	if ($_POST[action]=='contactchk'){

		$areaid = intval($areaid);

		if (eregi("[^\x80-\xff]",$cname)){write_msg("��ϵ��ֻ�����뺺�֣�");exit();}
		$sql = "UPDATE `{$db_mymps}member` SET cname='$cname',areaid='$areaid',address='$address',place='$place',sex='$sex',tel='$tel',qq='$qq',email='$email',msn='$msn',web='$web' WHERE userid = '$s_uid'";
		$res = $db->query($sql);
		write_msg("���Ļ�Ա���ϸ��³ɹ�",$url);
	} else {
		chk_member_purview("purview_�޸���ϵ��ʽ");
		$areaid		= $update[areaid];
		$info_area 	= area_options($areaid);
		$here 		= "�޸���ϵ��ʽ";
		$tpl=mymps_tpl("update_contact");
		include(mymps_tpl("index"));
	}
}elseif ($part == 'password'){

	if ($action == "chk"){

		if (empty($userpwd)&&empty($safeanswer)){
			write_msg("��û�����κ��޸ģ�");
			exit();
		}
		
		if (!empty($userpwd)&&($userpwd != $reuserpwd)) {
			write_msg("�����������벻��ͬ��");
			exit();
		}
		
		if (empty($safeanswer) && !empty($userpwd)) {
		
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				$result =  uc_user_edit($s_uid, $userpwd, $userpwd, $email, 1);
								
				if($result == 1) {
					$result = 1;
				} elseif ($result == -4) {
					write_msg('δ�������EMAIL��ʽ����');
					exit;
				}  elseif ($result == -5) {
					write_msg('δ������󣺸�email������ע�ᣡ');
					exit;
				} elseif ($result == -6) {
					write_msg('δ������󣺸�email�Ѿ���ע�ᣡ');
					exit;
				} elseif ($result == -8) {
					write_msg('δ��������ܱ������û�������Ȩ�޸ģ�');
					exit;
				} elseif ($result == -1) {
					write_msg('δ������󣺾����벻��ȷ��');
					exit;
				} else {
					write_msg('δ������������޸�ʧ�ܣ�');
				}
				
			}
			
			$sql = "UPDATE `{$db_mymps}member` SET userpwd='".md5($userpwd)."' WHERE userid = '$s_uid'";		
		} elseif (!empty($safeanswer)&&empty($userpwd)){
			$sql = "UPDATE `{$db_mymps}member` SET safequestion='$safequestion',safeanswer='$safeanswer' WHERE userid = '$s_uid'";
		} elseif (!empty($safeanswer)&&!empty($userpwd)){
			$sql = "UPDATE `{$db_mymps}member` SET userpwd='".md5($userpwd)."',safequestion='$safequestion',safeanswer='$safeanswer' WHERE userid = '$s_uid'";
		} else {
			write_msg("�����޸�ʧ�ܣ�");
			exit();
		}
		
		if ($db->query($sql)){
			write_msg("�����������ã��Ѿ������޸ĳɹ���","update.php?part=password");
		} else {
			write_msg("�����޸�ʧ�ܣ�");
		}
		
	} else {
	
		chk_member_purview("purview_�޸ĵ�¼����");
		require(MYMPS_DATA.'/safequestions.php');
		$here = "�޸ĵ�¼����";
		$tpl=mymps_tpl("update_pass");
		include(mymps_tpl("index"));
		
	}
} elseif ($part == 'logo'){

	chk_member_purview("purview_�޸�������Ƭ");
	$action = $action ? $action : 'view' ;
	
	if ($action == 'view'){
		$getlogo = $db -> getRow("SELECT logo,prelogo FROM `{$db_mymps}member` WHERE userid ='$s_uid'");
		$logo =$getlogo['logo'];
		$prelogo =$getlogo['prelogo'];
		$here = "�ϴ�������Ƭ";
		$tpl=mymps_tpl("update_logo");
		include(mymps_tpl("index"));
		
	}elseif ($action =='up'){

		require_once(MYMPS_INC."/upfile.fun.php");
		$name_file = "mymps_member_logo";
		
		if ($_FILES[$name_file]["name"]){
			check_upimage($name_file);
			$destination="/member_logo/".date('Ym')."/";
			$mymps_image=start_upload($name_file,$destination,0,$mymps_mymps[cfg_memberlogo_limit][width],$mymps_mymps[cfg_memberlogo_limit][height]);
			$db->query("UPDATE `{$db_mymps}member` SET logo='$mymps_image[0]',prelogo='$mymps_image[1]' WHERE userid = '$s_uid'");
		}
		
		if ($oldlogo!='' && file_exists(MYMPS_ROOT.$oldlogo)){
			@unlink(MYMPS_ROOT.$oldlogo);
			@unlink(MYMPS_ROOT.$prelogo);
		}
		
		write_msg("��ϲ������������Ƭ���³ɹ�","update.php?part=logo");
	}
	
} else {
	unknown_err_msg();
}
?>