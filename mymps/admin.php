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
			chk_admin_purview("purview_�û��б�");
			$where = $_GET['typeid']?"WHERE typeid = ".$_GET[typeid]."":"";
			$sql = "SELECT a.id,a.userid,a.uname,a.tname,a.logintime,a.loginip,a.typeid,b.typename FROM `{$db_mymps}admin` AS a LEFT JOIN `{$db_mymps}admin_type` AS b ON a.typeid = b.id  {$where} ORDER BY a.typeid Asc";
			
			$admin = $db->getAll($sql);
			$here = "����Ա�ʺŹ���";
			include(mymps_tpl("admin_user"));
		}elseif ($part=='add'){
			chk_admin_purview("purview_���ӻ�Ա");
			$here = "������վ����Ա�ʺ�";
			include(mymps_tpl("admin_user_add"));
		}elseif($part=='insert'){
			$pwd = md5(trim($pwd));
			if(!is_email($email)){write_msg("�����ʼ���ʽ����ȷ��");exit();}
			if(mymps_count("admin","WHERE userid LIKE '$userid'")>0){
				write_msg("�Ѿ����ڴ��ʺţ���ѡ�������û�����");
				exit();
			}
			$db->query("INSERT INTO `{$db_mymps}admin`(userid,uname,tname,pwd,typeid,email)
				VALUES('$userid','$uname','$tname','$pwd','$typeid','$email'); ");
			write_admin_cache();
			write_msg("��ӹ���Ա $userid �ɹ�","?do=user","record");
		}elseif($part=='edit'){
			$sql = "SELECT * FROM {$db_mymps}admin WHERE id = '$id'";
			$admin = $db->getRow($sql);
			$here = "�޸Ĺ���Ա�ʺ�";
			include(mymps_tpl("admin_user_edit"));
		}elseif ($part == 'update'){
			if(!is_email($email)){write_msg("�����ʼ���ʽ����ȷ��");exit();}
			$pwd = !empty($pwd) ? "pwd='".md5($pwd)."'," :"";
			$sql = "UPDATE {$db_mymps}admin SET {$pwd} userid='$userid',uname='$uname',typeid='$typeid',tname='$tname',email='$email' WHERE id = '$id'";
			$res = $db->query($sql);
			write_admin_cache();
			write_msg("��վ����Ա $uname ���ĳɹ�","admin.php?do=user&part=edit&id=".$id,"record");
		}elseif($part == 'delete'){
			if(empty($id)){
				write_msg("û��ѡ���¼");
			}else{
				if(mymps_delete("admin","WHERE id = '$id'")){
					write_admin_cache();
					write_msg("ɾ������Ա $id �ɹ�","?do=user","record");
				}else{
					write_msg("����Աɾ��ʧ�ܣ�");
				}
			}
		}
	break;
	case 'group':
		require_once(dirname(__FILE__)."/include/mymps.menu.inc.php");
		$part = $part ? $part : 'list';
		if ($part == 'list'){
			chk_admin_purview("purview_�û���");
			$sql = "SELECT * FROM {$db_mymps}admin_type ORDER BY id desc";
			$group = $db->getAll($sql);
			$here = "ϵͳ�û������";
			include(mymps_tpl("admin_group"));
		}elseif($part == 'add'){
			chk_admin_purview("purview_���ӻ�Ա��");
			$here = "�����û���";
			include(mymps_tpl("admin_group_add"));
		}elseif($part == 'insert'){
			$purview  = is_array($_POST['purview']) ? implode(",", $_POST['purview']) : '';
			$typename = trim($_POST['typename']);
			$ifsystem = trim($_POST['ifsystem']);
			if(!empty($typename)){
				$sql = "select count(*) from {$db_mymps}admin_type where typename = '$typename'";
				if($db->getOne($sql)){
					write_msg("�Ѿ����ڴ��û��飬���������룡");
					exit();
				}
			}
			$res = $db->query("Insert Into `{$db_mymps}admin_type`(id,typename,ifsystem,purviews)
				Values('','$typename','$ifsystem','$purview')");
			write_msg("����û��� $typename �ɹ�","?do=group","record");
		}elseif($part == 'edit'){
			$sql = "SELECT * FROM {$db_mymps}admin_type WHERE id = '$id'";
			$group = $db->getRow($sql);
			$purview = explode(',',$group['purviews']);
			$here = "�޸��û���Ȩ��";
			include(mymps_tpl("admin_group_edit"));
		}elseif($part=='update'){
			$purview = is_array($purview) ? implode(",", $purview) : '';
			$sql = "UPDATE `{$db_mymps}admin_type` SET typename='$typename',ifsystem='$ifsystem',purviews='$purview' WHERE id = '$id'";
			$res = $db->query($sql);
			write_msg("�û��� $typename �޸ĳɹ�","?do=group&part=edit&id=".$id,"record");
		}elseif($part == 'delete'){
			if(empty($id)){
				write_msg("û��ѡ���¼");
			}elseif (mymps_count("admin","WHERE typeid = '$id'")>0){
				write_msg("���û��������г�Ա������ɾ����");
			}else {
				if(mymps_delete("admin_type","WHERE id = '$id'")){
					write_msg("ɾ���û��� $id �ɹ�","?do=group","record");
				}else{
					write_msg("����Ա�û���ɾ��ʧ�ܣ�");
				}
			}
		}
	break;
}
?>