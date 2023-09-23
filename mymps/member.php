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
			chk_admin_purview("purview_��Ա�б�");
			
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
			
			$here="��Ա�б�";
			include(mymps_tpl("member_default"));
			
		}elseif($part == 'add'){
		
			chk_admin_purview("purview_���ӻ�Ա");
			$here = "������Ա";
			include(mymps_tpl("member_add"));
			
		}elseif($part == 'insert'){
		
			require_once(MYMPS_MEMBER.'/include/log.func.php');
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				if(!empty($activation) && ($activeuser = uc_get_user($activation))) {
				
					list($uid, $userid) = $activeuser;
					
				} else {
				
					if(uc_get_user($userid) && !$db->getOne("SELECT userid FROM {$db_mymps}member WHERE userid='$userid'")) {
						write_msg('���û��Ѵ�����ucenter�������Ե�¼�û�����������������û�');
						exit;
					}
			
					$uid = uc_user_register($userid,$userpwd,$email);
					
					if($uid <= 0) {
					
						if($uid == -1) {
							write_msg('�û������Ϸ�');
						} elseif($uid == -2) {
							write_msg( '����Ҫ����ע��Ĵ���');
						} elseif($uid == -3) {
							write_msg( '�û����Ѿ�����');
						} elseif($uid == -4) {
							write_msg( 'Email ��ʽ����');
						} elseif($uid == -5) {
							write_msg( 'Email ������ע��');
						} elseif($uid == -6) {
							write_msg( '�� Email �Ѿ���ע��');
						} else {
							write_msg( 'δ����');
						}
						
					} else {
					
						$userid  = trim($userid);
						$userpwd = $userpwd ? trim(md5($userpwd)) : MD5(random());
						$email 	 = trim($email);
						
					}
					
				}
				
			} else {
				
				$rs	= CheckUserID($userid,'�û���');
				
				if($rs != 'ok'){
					write_msg($rs);
					exit;
				}
				
				if(strlen($userid) > 20){
					write_msg("����û������ǳ����ƹ�����������ע�ᣡ");
					exit;
				}
				
				if(strlen($userid) < 3 || strlen($userpwd) < 5){
					write_msg("����û������������(��������3���ַ�)��������ע�ᣡ");
					exit;
				}
				
				if(!is_email($email)){
					write_msg("Email��ʽ����ȷ��");
					exit;
				}
				
				if($db->getOne("Select id From `{$db_mymps}member` where userid like '$userid' ")){
					write_msg("��ָ�����û��� {$userid} �Ѵ��ڣ���ʹ�ñ���û�����");
					exit;
				}
			}
			
			$go_reg = member_reg($userid,$userpwd,$email);
			if($go_reg){
				write_msg("��ӻ�Ա <b>".$userid."</b> �ɹ�","member.php","mymps");
			} else {
				write_msg("��ӻ�Ա <b>".$userid."</b> ʧ�ܣ�");
			}
			
		}elseif($part == 'edit'){
		
			$sql = "SELECT a.id,a.userid,a.money_own,a.cname,a.email,a.userpwd,b.id as levelid,b.levelname FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.id = '$id'";
			$edit = $db->getRow($sql);
			$here = "��Ա�����޸�";
			include(mymps_tpl("member_edit"));
			
		}elseif($part == 'update'){
			
			if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			
				include MYMPS_ROOT.'/uc_client/client.php';
				
				$result =  uc_user_edit($userid, $userpwd, $userpwd, $email, 1);
								
				if ($result == -4) {
					write_msg('δ�������EMAIL��ʽ����');
					exit;
				} elseif ($result == -5) {
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
				} elseif ($result == -7) {
					write_msg('δ���������û�ж��û����κ��޸ģ�');
					exit;
				}
				
			} else {
			
				$rs = CheckUserID($userid,'�û���');
				if( $rs != 'ok'){
					write_msg($rs);
					exit;
				}
				
				if(!is_email($email)){write_msg("�����ʼ���ʽ����ȷ��");exit;}
				
				$old=$db->getRow("SELECT id,userid FROM `{$db_mymps}member` WHERE id = '$id'");
				if($db->getOne("SELECT id FROM `{$db_mymps}member` WHERE userid LIKE '$userid' AND userid != '$old[userid]'")){
					write_msg("��ָ�����û��� {".$userid."} ���������û�ʹ�ã�");
					exit;
				}
				
			}
			
			$userpwd = !empty($userpwd) ? "userpwd='".md5($userpwd)."'," :"";
			$sql = "UPDATE `{$db_mymps}member` SET {$userpwd} userid = '$userid', levelid='$levelid',cname='$cname',email='$email',money_own='$money_own' WHERE id = '$id'";
		
			$res = $db->query($sql);
			write_msg($cname."���û���Ϣ�޸ĳɹ�","member.php?do=member&part=edit&id=".$id,'record');
			
		} elseif ($part == 'delall'){
		
			foreach ($_POST[id] as $k => $v){
				$row = $db->getRow("SELECT userid FROM `{$db_mymps}member` WHERE id ='$v'");
				$count = mymps_count("information","WHERE userid = '$row[userid]'");
				if($count > 0){
					write_msg("IDΪ".$v."�Ļ�Ա������ <b>".$count."</b> ��������Ϣ<br /><br />���Ƚ���û�Ա�������Ľ������Ϣɾ����");
					exit;
				}
			}
			
			write_msg('ɾ����Ա '.mymps_del_all("member",$_POST[id]).' �ɹ�',$url,"mymps");
			
		}
	break;
	
	case 'group':
		$part = $part ? $part : 'list' ;
		if ($part == 'list'){
		
			chk_admin_purview("purview_��Ա���б�");
			$sql = "SELECT * FROM {$db_mymps}member_level ORDER BY id desc";
			$group = $db->getAll($sql);
			$here = "ע���û������";
			include(mymps_tpl("member_group"));	
			
		}elseif($part == 'add'){
		
			chk_admin_purview("purview_���ӻ�Ա��");
			$here = "�����û���";
			include(mymps_tpl("member_group_add"));
			
		}elseif($part == 'insert'){
			$purview  = is_array($purview) ? implode(",", $purview) : '';
			$perday_maxpost = trim($perday_maxpost);
			
			if(!empty($levelname)){
				if($db->getOne("select count(*) from {$db_mymps}member_level where levelname = '$levelname'")){
					write_msg("�Ѿ����ڴ��û��飬���������룡");
					exit;
				}
				if($db->query("INSERT INTO `{$db_mymps}member_level` (id,levelname,ifsystem,purviews,money_own,perday_maxpost) VALUES ('','$levelname','0','$purview','$money_own','$perday_maxpost')")){
					write_msg("����û��� ".$levelname." �ɹ�","member.php?do=group","MyMPS");
				}else{
					write_msg("����û��� ".$levelname." ʧ��");
				}
			}else{
				write_msg("�û���������Ϊ�գ�");
			}
			
		}elseif($part == 'edit'){
		
			$group = $db->getRow("SELECT * FROM `{$db_mymps}member_level` WHERE id = '$id'");
			$purviews = explode(',',$group['purviews']);
			$here = "�����û���Ȩ��";
			include(mymps_tpl("member_group_edit"));
			
		}elseif($part == 'update'){

			$purview = is_array($purview) ? implode(",", $purview) : '';
			if($db->query("UPDATE `{$db_mymps}member_level` SET levelname='$levelname',purviews='$purview',money_own='$money_own',perday_maxpost='$perday_maxpost' WHERE id = '$id'")){
				write_msg("�û��� ".$levelname." Ȩ�����óɹ�","member.php?do=group&part=edit&id=".$id,"mymps");
			}else{
				write_msg("�û��� ".$levelname." �޸�ʧ�ܣ�");
			}
			
		}elseif($part == 'delete'){
		
			if(empty($id)){
				write_msg("û��ѡ���¼");
			}elseif (mymps_count("member","WHERE levelid = '$id'")>0){
				write_msg("���û��������г�Ա������ɾ����");
			}else{
				mymps_delete("member_level","WHERE id = '$id'");
				write_msg("ɾ���û��� $id �ɹ�","?do=group","record");
			}
			
		}
	break;
}
?>