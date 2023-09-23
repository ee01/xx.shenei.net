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
require_once(MYMPS_DATA."/config.inc.php");

$do 	= $do ? $do : 'admin';

switch ($do){
	case 'admin':
		if(!empty($part)&&$action=='delrecord'){
			$total_count=mymps_count("admin_record_".$part);
			if($total_count<$mymps_mymps['cfg_record_save']){
				write_msg("操作失败！管理记录不满 ".$mymps_mymps['cfg_record_save']." 条！");
				exit();
			}
			$limit = $total_count - $mymps_mymps['cfg_record_save'];
			$delrecord=$db->getAll("SELECT id FROM `{$db_mymps}admin_record_".$part."` ORDER BY ID DESC LIMIT ".$mymps_mymps['cfg_record_save'].",".$total_count);
			foreach ($delrecord as $k => $value){
				$id .= $value[id].",";
			}
			$id = substr($id,0,-1);
			if(mymps_delete("admin_record_".$part,"WHERE id IN (".$id.")")){
				write_msg("操作成功！",$url,"MyMPS");
			}else{
				write_msg("管理员记录删除失败");
			}
			exit();
		}
		if ($part == 'login'){
			chk_admin_purview("purview_管理登录记录");
			$here = "管理员后台管理登陆记录";
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
		
			chk_admin_purview("purview_管理操作记录");
			$here 		= "管理员后台管理操作记录";

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
		
			chk_admin_purview("purview_会员登录记录");
			
			if (trim($action)=='delall'){
				write_msg('删除普通会员操作日志 '.mymps_del_all("member_record_".$part,$id).' 成功',$url,"mymps");
				exit();
			}
			
			$here = "普通会员登陆记录";
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
			if($type == 'use'){chk_admin_purview("purview_会员消费记录");}elseif($type == 'pay'){chk_admin_purview("purview_会员充值记录");}
			$here = ($type == 'pay')?"会员充值记录":"会员消费记录";
			if (trim($action)=='delall'){
				write_msg('删除 <b>'.$here.'</b> '.mymps_del_all("member_record_pay",$id).' 成功',$url,"mymps");
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