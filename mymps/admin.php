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
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");

function get_admin_group($typeid=""){
	global $db,$db_mymps;
	$admin = $db->getAll("SELECT * FROM `{$db_mymps}admin_type` ORDER BY id desc");
	foreach($admin AS $row)
	{
		$mymps .= "<option value=\"".$row[id]."\"";
		$mymps .= ($typeid == $row[id])?"selected style=\"background-color:#6EB00C;color:white\"":"";
		$mymps .= ">".$row[typename]."</option>";
	}
	return $mymps;
}

switch ($do){
	case 'user':
		$part = $part ? $part : 'list' ;
		if($part == 'list'){
			chk_admin_purview("purview_用户列表");
			$where = $_GET['typeid']?"WHERE typeid = ".$_GET[typeid]."":"";
			$sql = "SELECT a.id,a.userid,a.uname,a.tname,a.logintime,a.loginip,a.typeid,b.typename FROM `{$db_mymps}admin` AS a LEFT JOIN `{$db_mymps}admin_type` AS b ON a.typeid = b.id  {$where} ORDER BY a.typeid Asc";
			
			$admin = $db->getAll($sql);
			$here = "管理员帐号管理";
			include(mymps_tpl("admin_user"));
		}elseif ($part=='add'){
			chk_admin_purview("purview_增加会员");
			$here = "新增网站管理员帐号";
			include(mymps_tpl("admin_user_add"));
		}elseif($part=='insert'){
			$pwd = md5(trim($pwd));
			if(!is_email($email)){write_msg("电子邮件格式不正确。");exit();}
			if(mymps_count("admin","WHERE userid LIKE '$userid'")>0){
				write_msg("已经存在此帐号，请选择其它用户名！");
				exit();
			}
			$db->query("INSERT INTO `{$db_mymps}admin`(userid,uname,tname,pwd,typeid,email)
				VALUES('$userid','$uname','$tname','$pwd','$typeid','$email'); ");
			write_admin_cache();
			write_msg("添加管理员 $userid 成功","?do=user","record");
		}elseif($part=='edit'){
			$sql = "SELECT * FROM {$db_mymps}admin WHERE id = '$id'";
			$admin = $db->getRow($sql);
			$here = "修改管理员帐号";
			include(mymps_tpl("admin_user_edit"));
		}elseif ($part == 'update'){
			if(!is_email($email)){write_msg("电子邮件格式不正确。");exit();}
			$pwd = !empty($pwd) ? "pwd='".md5($pwd)."'," :"";
			$sql = "UPDATE {$db_mymps}admin SET {$pwd} userid='$userid',uname='$uname',typeid='$typeid',tname='$tname',email='$email' WHERE id = '$id'";
			$res = $db->query($sql);
			write_admin_cache();
			write_msg("网站管理员 $uname 更改成功","admin.php?do=user&part=edit&id=".$id,"record");
		}elseif($part == 'delete'){
			if(empty($id)){
				write_msg("没有选择记录");
			}else{
				if(mymps_delete("admin","WHERE id = '$id'")){
					write_admin_cache();
					write_msg("删除管理员 $id 成功","?do=user","record");
				}else{
					write_msg("管理员删除失败！");
				}
			}
		}
	break;
	case 'group':
		require_once(dirname(__FILE__)."/include/mymps.menu.inc.php");
		$part = $part ? $part : 'list';
		if ($part == 'list'){
			chk_admin_purview("purview_用户组");
			$sql = "SELECT * FROM {$db_mymps}admin_type ORDER BY id desc";
			$group = $db->getAll($sql);
			$here = "系统用户组管理";
			include(mymps_tpl("admin_group"));
		}elseif($part == 'add'){
			chk_admin_purview("purview_增加会员组");
			$here = "新增用户组";
			include(mymps_tpl("admin_group_add"));
		}elseif($part == 'insert'){
			$purview  = is_array($_POST['purview']) ? implode(",", $_POST['purview']) : '';
			$typename = trim($_POST['typename']);
			$ifsystem = trim($_POST['ifsystem']);
			if(!empty($typename)){
				$sql = "select count(*) from {$db_mymps}admin_type where typename = '$typename'";
				if($db->getOne($sql)){
					write_msg("已经存在此用户组，请重新输入！");
					exit();
				}
			}
			$res = $db->query("Insert Into `{$db_mymps}admin_type`(id,typename,ifsystem,purviews)
				Values('','$typename','$ifsystem','$purview')");
			write_msg("添加用户组 $typename 成功","?do=group","record");
		}elseif($part == 'edit'){
			$sql = "SELECT * FROM {$db_mymps}admin_type WHERE id = '$id'";
			$group = $db->getRow($sql);
			$purview = explode(',',$group['purviews']);
			$here = "修改用户组权限";
			include(mymps_tpl("admin_group_edit"));
		}elseif($part=='update'){
			$purview = is_array($purview) ? implode(",", $purview) : '';
			$sql = "UPDATE `{$db_mymps}admin_type` SET typename='$typename',ifsystem='$ifsystem',purviews='$purview' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("用户组 $typename 修改成功","?do=group&part=edit&id=".$id,"record");
		}elseif($part == 'delete'){
			if(empty($id)){
				write_msg("没有选择记录");
			}elseif (mymps_count("admin","WHERE typeid = '$id'")>0){
				write_msg("该用户组下尚有成员，不能删除！");
			}else {
				if(mymps_delete("admin_type","WHERE id = '$id'")){
					write_msg("删除用户组 $id 成功","?do=group","record");
				}else{
					write_msg("管理员用户组删除失败！");
				}
			}
		}
	break;
}
?>