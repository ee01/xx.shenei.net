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
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_INC."/db.class.php");
require_once(MYMPS_MEMBER."/include/mymps.menu.inc.php");

$do = $do ? $do : 'member';
switch ($do){

	case 'member':

		function get_member_level($id=''){
			global $db,$db_mymps;
			$member_level = $db -> getAll("SELECT id,levelname FROM `{$db_mymps}member_level`");
			$mymps .= "<select name=\"levelid\">";
			foreach($member_level as $k=>$value){
				$mymps .= "<option value=".$value[id]."";
				$mymps .= ($id==$value[id])?" selected style=\"background-color:#6EB00C;color:white\"":"";
				$mymps .= ">".$value[levelname]."</option>";
			}
			$mymps .= "</select>";
			return $mymps;
		}
		
		$part = $part?$part:'default';
		if($part == 'default'){
			chk_admin_purview("purview_会员列表");
			
			$page = empty($page) ? '1' : intval($page);
			$where="WHERE a.userid like '%".$userid."%' and a.levelid like '%".$levelid."%'";
			$sql = "SELECT a.id,a.money_own,a.userid,a.joinip,a.logintime,a.jointime,a.levelid,b.levelname FROM {$db_mymps}member AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id $where ORDER BY a.jointime DESC";
			$param=setParam(array('userid','levelid'));
			$rows_num = $db->getOne("SELECT COUNT(*) FROM `{$db_mymps}member` AS a $where");
			$member = array();
			foreach(page1($sql) as $k => $row){
				$arr['id']       	 = $row['id'];
				$arr['userid']    	 = $row['userid'];
				$arr['money_own']    = $row['money_own'];
				$arr['levelname']    = $row['levelname'];
				$arr['levelid']  	 = $row['levelid'];
				$arr['joinip']   	 = $row['joinip'];
				$arr['logintime'] 	 = date('Y-m-d h:i:s', $row['logintime']);
				$arr['jointime'] 	 = date('Y-m-d h:i:s', $row['jointime']);
				$member[]      		 = $arr;
			}
			
			$here="会员列表";
			include(mymps_tpl("member_default"));
			
		}elseif($part == 'add'){
		
			chk_admin_purview("purview_增加会员");
			$here = "新增会员";
			include(mymps_tpl("member_add"));
			
		}elseif($part == 'insert'){
		
			require_once(MYMPS_MEMBER.'/include/log.func.php');
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				if(!empty($activation) && ($activeuser = uc_get_user($activation))) {
				
					list($uid, $userid) = $activeuser;
					
				} else {
				
					if(uc_get_user($userid) && !$db->getOne("SELECT userid FROM {$db_mymps}member WHERE userid='$userid'")) {
						write_msg('该用户已存在于ucenter，您可以登录用户管理中心来激活该用户');
						exit;
					}
			
					$uid = uc_user_register($userid,$userpwd,$email);
					
					if($uid <= 0) {
					
						if($uid == -1) {
							write_msg('用户名不合法');
						} elseif($uid == -2) {
							write_msg( '包含要允许注册的词语');
						} elseif($uid == -3) {
							write_msg( '用户名已经存在');
						} elseif($uid == -4) {
							write_msg( 'Email 格式有误');
						} elseif($uid == -5) {
							write_msg( 'Email 不允许注册');
						} elseif($uid == -6) {
							write_msg( '该 Email 已经被注册');
						} else {
							write_msg( '未定义');
						}
						
					} else {
					
						$userid  = trim($userid);
						$userpwd = $userpwd ? trim(md5($userpwd)) : MD5(random());
						$email 	 = trim($email);
						
					}
					
				}
				
			} else {
				
				$rs	= CheckUserID($userid,'用户名');
				
				if($rs != 'ok'){
					write_msg($rs);
					exit;
				}
				
				if(strlen($userid) > 20){
					write_msg("你的用户名或昵称名称过长，不允许注册！");
					exit;
				}
				
				if(strlen($userid) < 3 || strlen($userpwd) < 5){
					write_msg("你的用户名或密码过短(不能少于3个字符)，不允许注册！");
					exit;
				}
				
				if(!is_email($email)){
					write_msg("Email格式不正确！");
					exit;
				}
				
				if($db->getOne("Select id From `{$db_mymps}member` where userid like '$userid' ")){
					write_msg("你指定的用户名 {$userid} 已存在，请使用别的用户名！");
					exit;
				}
			}
			
			$go_reg = member_reg($userid,$userpwd,$email);
			if($go_reg){
				write_msg("添加会员 <b>".$userid."</b> 成功","member.php","mymps");
			} else {
				write_msg("添加会员 <b>".$userid."</b> 失败！");
			}
			
		}elseif($part == 'edit'){
		
			$sql = "SELECT a.id,a.userid,a.money_own,a.cname,a.email,a.userpwd,b.id as levelid,b.levelname FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.id = '$id'";
			$edit = $db->getRow($sql);
			$here = "会员资料修改";
			include(mymps_tpl("member_edit"));
			
		}elseif($part == 'update'){
			
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				$result =  uc_user_edit($userid, $userpwd, $userpwd, $email, 1);
								
				if ($result == -4) {
					write_msg('未定义错误：EMAIL格式有误！');
					exit;
				} elseif ($result == -5) {
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
				} elseif ($result == -7) {
					write_msg('未定义错误：您没有对用户做任何修改！');
					exit;
				}
				
			} else {
			
				$rs = CheckUserID($userid,'用户名');
				if( $rs != 'ok'){
					write_msg($rs);
					exit;
				}
				
				if(!is_email($email)){write_msg("电子邮件格式不正确。");exit;}
				
				$old=$db->getRow("SELECT id,userid FROM `{$db_mymps}member` WHERE id = '$id'");
				if($db->getOne("SELECT id FROM `{$db_mymps}member` WHERE userid LIKE '$userid' AND userid != '$old[userid]'")){
					write_msg("你指定的用户名 {".$userid."} 已有其他用户使用！");
					exit;
				}
				
			}
			
			$userpwd = !empty($userpwd) ? "userpwd='".md5($userpwd)."'," :"";
			$sql = "UPDATE `{$db_mymps}member` SET {$userpwd} userid = '$userid', levelid='$levelid',cname='$cname',email='$email',money_own='$money_own' WHERE id = '$id'";
		
			$res = $db->query($sql);
			write_msg($cname."的用户信息修改成功","member.php?do=member&part=edit&id=".$id,'record');
			
		} elseif ($part == 'delall'){
		
			foreach ($_POST[id] as $k => $v){
				$row = $db->getRow("SELECT userid FROM `{$db_mymps}member` WHERE id ='$v'");
				$count = mymps_count("information","WHERE userid = '$row[userid]'");
				if($count > 0){
					write_msg("ID为".$v."的会员发布了 <b>".$count."</b> 条分类信息<br /><br />请先进入该会员管理中心将相关信息删除！");
					exit;
				}
			}
			
			write_msg('删除会员 '.mymps_del_all("member",$_POST[id]).' 成功',$url,"mymps");
			
		}
	break;
	
	case 'group':
		$part = $part ? $part : 'list' ;
		if ($part == 'list'){
		
			chk_admin_purview("purview_会员组列表");
			$sql = "SELECT * FROM {$db_mymps}member_level ORDER BY id desc";
			$group = $db->getAll($sql);
			$here = "注册用户组管理";
			include(mymps_tpl("member_group"));	
			
		}elseif($part == 'add'){
		
			chk_admin_purview("purview_增加会员组");
			$here = "新增用户组";
			include(mymps_tpl("member_group_add"));
			
		}elseif($part == 'insert'){
			$purview  = is_array($purview) ? implode(",", $purview) : '';
			$perday_maxpost = trim($perday_maxpost);
			
			if(!empty($levelname)){
				if($db->getOne("select count(*) from {$db_mymps}member_level where levelname = '$levelname'")){
					write_msg("已经存在此用户组，请重新输入！");
					exit;
				}
				if($db->query("INSERT INTO `{$db_mymps}member_level` (id,levelname,ifsystem,purviews,money_own,perday_maxpost) VALUES ('','$levelname','0','$purview','$money_own','$perday_maxpost')")){
					write_msg("添加用户组 ".$levelname." 成功","member.php?do=group","MyMPS");
				}else{
					write_msg("添加用户组 ".$levelname." 失败");
				}
			}else{
				write_msg("用户组名不能为空！");
			}
			
		}elseif($part == 'edit'){
		
			$group = $db->getRow("SELECT * FROM `{$db_mymps}member_level` WHERE id = '$id'");
			$purviews = explode(',',$group['purviews']);
			$here = "设置用户组权限";
			include(mymps_tpl("member_group_edit"));
			
		}elseif($part == 'update'){

			$purview = is_array($purview) ? implode(",", $purview) : '';
			if($db->query("UPDATE `{$db_mymps}member_level` SET levelname='$levelname',purviews='$purview',money_own='$money_own',perday_maxpost='$perday_maxpost' WHERE id = '$id'")){
				write_msg("用户组 ".$levelname." 权限设置成功","member.php?do=group&part=edit&id=".$id,"mymps");
			}else{
				write_msg("用户组 ".$levelname." 修改失败！");
			}
			
		}elseif($part == 'delete'){
		
			if(empty($id)){
				write_msg("没有选择记录");
			}elseif (mymps_count("member","WHERE levelid = '$id'")>0){
				write_msg("该用户组下尚有成员，不能删除！");
			}else{
				mymps_delete("member_level","WHERE id = '$id'");
				write_msg("删除用户组 $id 成功","?do=group","record");
			}
			
		}
	break;
}
?>