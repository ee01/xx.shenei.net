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
require_once(MYMPS_DATA."/config.inc.php");

$do 	= $do ? $do : 'admin';

switch ($do){
	case 'admin':
		if(!empty($part)&&$action=='delrecord'){
			$total_count=mymps_count("admin_record_".$part);
			if($total_count<$mymps_mymps['cfg_record_save']){
				write_msg("����ʧ�ܣ������¼���� ".$mymps_mymps['cfg_record_save']." ����");
				exit();
			}
			$limit = $total_count - $mymps_mymps['cfg_record_save'];
			$delrecord=$db->getAll("SELECT id FROM `{$db_mymps}admin_record_".$part."` ORDER BY ID DESC LIMIT ".$mymps_mymps['cfg_record_save'].",".$total_count);
			foreach ($delrecord as $k => $value){
				$id .= $value[id].",";
			}
			$id = substr($id,0,-1);
			if(mymps_delete("admin_record_".$part,"WHERE id IN (".$id.")")){
				write_msg("�����ɹ���",$url,"MyMPS");
			}else{
				write_msg("����Ա��¼ɾ��ʧ��");
			}
			exit();
		}
		if ($part == 'login'){
			chk_admin_purview("purview_�����¼��¼");
			$here = "����Ա��̨�����½��¼";
			$where = "WHERE result like '%".$result."%' AND (id like '%".$keywords."%' OR adminid like '%".$keywords."%' OR adminpwd like '%".$keywords."%' OR pubdate like '%".$keywords."%' OR ip like '%".$keywords."%')";
			
			$sql = "SELECT * FROM {$db_mymps}admin_record_login $where ORDER BY id desc";
			$rows_num = $db->getOne("SELECT COUNT(*) FROM `{$db_mymps}admin_record_login` $where");
			$param=setParam(array('do','part','result'));
			$record= array();
			foreach(page1($sql) as $k => $row){
				$arr['id']     	   = $row['id'];
				$arr['adminid']    = $row['adminid'];
				$arr['adminpwd']   = $row['adminpwd'];
				$arr['pubdate']    = GetTime($row['pubdate']);
				$arr['ip'] 		   = $row['ip'];
				$arr['result'] 	   = $row['result'];
				$record[]      	   = $arr;
			}
			include (mymps_tpl("record_login"));
			
		}elseif ($part == 'action'){
		
			chk_admin_purview("purview_���������¼");
			$here 		= "����Ա��̨���������¼";

			$where = "WHERE a.id like '%".$keywords."%' OR a.adminid like '%".$keywords."%' OR a.action like '%".$keywords."%' OR a.pubdate like '%".$keywords."%' OR a.ip like '%".$keywords."%' ORDER BY a.id desc";
			$sql = "SELECT a.id,a.adminid,a.action,a.pubdate,a.ip,b.typeid,c.typename FROM {$db_mymps}admin_record_action AS a LEFT JOIN {$db_mymps}admin AS b ON a.adminid = b.userid  LEFT JOIN {$db_mymps}admin_type AS c ON b.typeid = c.id $where";
			$rows_num = $db->getOne("SELECT COUNT(*) FROM `{$db_mymps}admin_record_action` AS a $where ");
			$param=setParam(array('do','part','result'));
			$record= array();
			foreach(page1($sql) as $k => $row){
				$arr['id']       = $row['id'];
				$arr['adminid']    = $row['adminid'];
				$arr['typename']    = $row['typename'];
				$arr['action']   = $row['action'];
				$arr['pubdate'] = GetTime($row['pubdate']);
				$arr['ip'] = $row['ip'];
				$record[]      = $arr;
			}
			include (mymps_tpl("record_action"));
		}
	break;	
	case 'member':
		if ($part == 'login'){
		
			chk_admin_purview("purview_��Ա��¼��¼");
			
			if (trim($action)=='delall'){
				write_msg('ɾ����ͨ��Ա������־ '.mymps_del_all("member_record_".$part,$id).' �ɹ�',$url,"mymps");
				exit();
			}
			
			$here = "��ͨ��Ա��½��¼";
			$where = "WHERE result like '%".$result."%' AND (id like '%".$keywords."%' OR userid like '%".$keywords."%' OR userpwd like '%".$keywords."%' OR pubdate like '%".$keywords."%' OR ip like '%".$keywords."%')";
			$sql = "SELECT * FROM {$db_mymps}member_record_login $where ORDER BY id DESC";
			$rows_num = mymps_count("member_record_login",$where);
			$param=setParam(array('do','part','result'));
			$record= array();
			foreach(page1($sql) as $k => $row){
				$arr['id']      	= $row['id'];
				$arr['adminid']     = $row['userid'];
				$arr['adminpwd']    = $row['userpwd'];
				$arr['pubdate'] 	= GetTime($row['pubdate']);
				$arr['ip'] 			= $row['ip'];
				$arr['result']		= $row['result'];
				$record[]      		= $arr;
			}
			include (mymps_tpl("record_login"));
			
		}elseif($part == 'money'){
			if($type == 'use'){chk_admin_purview("purview_��Ա���Ѽ�¼");}elseif($type == 'pay'){chk_admin_purview("purview_��Ա��ֵ��¼");}
			$here = ($type == 'pay')?"��Ա��ֵ��¼":"��Ա���Ѽ�¼";
			if (trim($action)=='delall'){
				write_msg('ɾ�� <b>'.$here.'</b> '.mymps_del_all("member_record_pay",$id).' �ɹ�',$url,"mymps");
				exit();
			}
			$record_part = array('pay'=>'1','use'=>'2');
			$where = "WHERE type = '$record_part[$type]'";
			$sql = "SELECT * FROM `{$db_mymps}member_record_pay` $where ORDER BY pubtime DESC";
			$rows_num = mymps_count("member_record_pay",$where);
			$param=setParam(array('do','part','type'));
			$get = array();
			foreach(page1($sql) as $k => $row){
				$arr['id']         = $row['id'];
				$arr['userid']     = $row['userid'];
				$arr['type']       = $row['type'];
				$arr['subject']    = $row['subject'];
				$arr['paycost']    = $row['paycost'];
				$arr['pubtime']    = GetTime($row['pubtime']);
				$get[]      = $arr;
			}
			include (mymps_tpl("record_bank"));
		}
	break;
}
?>