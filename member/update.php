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
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
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

		if (eregi("[^\x80-\xff]",$cname)){write_msg("联系人只能输入汉字！");exit();}
		$sql = "UPDATE `{$db_mymps}member` SET cname='$cname',areaid='$areaid',address='$address',place='$place',sex='$sex',tel='$tel',qq='$qq',email='$email',msn='$msn',web='$web' WHERE userid = '$s_uid'";
		$res = $db->query($sql);
		write_msg("您的会员资料更新成功",$url);
	} else {
		chk_member_purview("purview_修改联系方式");
		$areaid		= $update[areaid];
		$info_area 	= area_options($areaid);
		$here 		= "修改联系方式";
		$tpl=mymps_tpl("update_contact");
		include(mymps_tpl("index"));
	}
}elseif ($part == 'password'){

	if ($action == "chk"){

		if (empty($userpwd)&&empty($safeanswer)){
			write_msg("您没有做任何修改！");
			exit();
		}
		
		if (!empty($userpwd)&&($userpwd != $reuserpwd)) {
			write_msg("两次密码输入不相同！");
			exit();
		}
		
		if (empty($safeanswer) && !empty($userpwd)) {
		
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				$result =  uc_user_edit($s_uid, $userpwd, $userpwd, $email, 1);
								
				if($result == 1) {
					$result = 1;
				} elseif ($result == -4) {
					write_msg('未定义错误：EMAIL格式有误！');
					exit;
				}  elseif ($result == -5) {
					write_msg('未定义错误：该email不允许注册！');
					exit;
				} elseif ($result == -6) {
					write_msg('未定义错误：该email已经被注册！');
					exit;
				} elseif ($result == -8) {
					write_msg('未定义错误：受保护的用户，您无权修改！');
					exit;
				} elseif ($result == -1) {
					write_msg('未定义错误：旧密码不正确！');
					exit;
				} else {
					write_msg('未定义错误，密码修改失败！');
				}
				
			}
			
			$sql = "UPDATE `{$db_mymps}member` SET userpwd='".md5($userpwd)."' WHERE userid = '$s_uid'";		
		} elseif (!empty($safeanswer)&&empty($userpwd)){
			$sql = "UPDATE `{$db_mymps}member` SET safequestion='$safequestion',safeanswer='$safeanswer' WHERE userid = '$s_uid'";
		} elseif (!empty($safeanswer)&&!empty($userpwd)){
			$sql = "UPDATE `{$db_mymps}member` SET userpwd='".md5($userpwd)."',safequestion='$safequestion',safeanswer='$safeanswer' WHERE userid = '$s_uid'";
		} else {
			write_msg("保存修改失败！");
			exit();
		}
		
		if ($db->query($sql)){
			write_msg("按照您的设置，已经保存修改成功！","update.php?part=password");
		} else {
			write_msg("保存修改失败！");
		}
		
	} else {
	
		chk_member_purview("purview_修改登录密码");
		require(MYMPS_DATA.'/safequestions.php');
		$here = "修改登录密码";
		$tpl=mymps_tpl("update_pass");
		include(mymps_tpl("index"));
		
	}
} elseif ($part == 'logo'){

	chk_member_purview("purview_修改形象照片");
	$action = $action ? $action : 'view' ;
	
	if ($action == 'view'){
		$getlogo = $db -> getRow("SELECT logo,prelogo FROM `{$db_mymps}member` WHERE userid ='$s_uid'");
		$logo =$getlogo['logo'];
		$prelogo =$getlogo['prelogo'];
		$here = "上传形象照片";
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
		
		write_msg("恭喜，您的形象照片更新成功","update.php?part=logo");
	}
	
} else {
	unknown_err_msg();
}
?>