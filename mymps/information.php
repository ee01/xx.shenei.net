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
require_once(MYMPS_DATA."/report.type.inc.php");
require_once(MYMPS_DATA."/info.level.inc.php");
require_once(MYMPS_DATA."/category.inc.php");
require_once(MYMPS_DATA."/area.inc.php");
require_once(MYMPS_SMARTY."/include.php");

$action = $action ? $action : 'list' ;

switch ($part){
	case 'report':
		if($action == 'list'){
			chk_admin_purview("purview_��Ϣ�ٱ�");
			$here 	= "��Ϣ�ٱ��б�";
			$page 	= empty($page) ? 1 : intval($page);
			$type 	= $_GET['report_type'];
			$where 	= "WHERE report_type LIKE '%".$type."%'";
			$rows_num = mymps_count("info_report",$where);
			$param	=setParam(array("part","type"));
			$report = array();
			
			foreach(page1("SELECT * FROM `{$db_mymps}info_report` $where ORDER BY pubtime DESC") as $k => $row){
				$arr['id']         = $row['id'];
				$arr['infoid']     = $row['infoid'];
				$arr['infotitle']  = $row['infotitle'];
				$arr['content']    = $row['content'];
				$arr['type']   	   = "<a href=?part=report&report_type=".$row['report_type'].">".$report_type[$row['report_type']]."</a>";
				$arr['pubtime']    = GetTime($row['pubtime']);
				$arr['ip']  	   = $row['ip'];
				$report[]     	   = $arr;
			}
			
			include(mymps_tpl("info_report"));
			
		} elseif ($action == 'del'){
			mymps_delete("info_report","WHERE id='$id'");
			write_msg("�ٱ���¼".$id."�ѳɹ�ɾ����",$url,"MYMPS_record");
		} elseif ($action == 'delall'){
			write_msg("�ٱ���¼".mymps_del_all("info_report",$id)."ɾ���ɹ���",$url,"Mymps");
		}
	break;
	
	default:
		if($action == 'delall'){
		
			require_once(MYMPS_SMARTY."/include.php");
			$id 	   = explode(',',$id);

			foreach ($id as $a => $w){
			
				if(mymps_count("info_img","WHERE infoid = '$w'")>0){
					$del = $db->getAll("SELECT path,prepath FROM `{$db_mymps}info_img` WHERE infoid='$w'");
					foreach ($del as $k => $v){
						if(file_exists(MYMPS_ROOT.$v[path])){
							@unlink(MYMPS_ROOT.$v[path]) ;
						}
						if(file_exists(MYMPS_ROOT.$v[prepath])){
							@unlink(MYMPS_ROOT.$v[prepath]);
						}
					}
					mymps_delete("info_img","WHERE infoid = '$w'");
				}
				
				$get_row = is_member_info($w,'no_level_limit');
				
				if($get_row[ismember] == 1){
					$userid = $get_row['userid'];
					if($if_money == 1){
						$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own {$money_num} WHERE userid = '$userid'");
					}
					if($if_pm == 1){
						$msg = "�������� <b>".$get_row[title]."</b> �Ѿ���ɾ��������ԭ�� <b>".$msg."</b>";
						$msg .= ($if_money == 1)?"<br />��ұ仯��<b style=color:red>".$money_num."</b>":'';
						$db->query("INSERT INTO `{$db_mymps}member_pm` (userid,msg,pubtime)VALUES('$userid','$msg','".time()."')");
					}
				}
				
				mymps_delete("info_extra","WHERE infoid = '$w'");
				$row = $db->getRow("SELECT catid,areaid FROM `{$db_mymps}information` WHERE id=".$w);
				$smarty->clear_cache(mymps_tpl('info_list','smarty'),$row[catid],0);
				$smarty->clear_cache(mymps_tpl('info_list','smarty'),$row[catid],$row[areaid]);
				
			}
			write_msg("������Ϣ ".mymps_del_all("information",$id)."ɾ���ɹ���",$url,"mymps");
			
		} elseif (strstr($action,'level')){
			
			$action = FileExt($action);
			$id = explode(',',$id);
			if(!is_array($id)){write_msg("��û��ѡ���κμ�¼��");exit();}
			
			foreach ($id as $k => $v){
				$get_row = is_member_info($v,'no_level_limit');
				if($get_row[ismember] == 1){
					$userid = $get_row['userid'];
					if($if_money == 1){
						$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own {$money_num} WHERE userid = '$userid'");
					}
					if($if_pm == 1){
						$msg = "�������� <b>".$get_row[title]."</b> ������Ա����Ϊ <b>".$information_level[$action]."</b> ������ԭ�� <b>".$msg."</b>";
						$msg .= ($if_money == 1)?"<br />��ұ仯��<b style=color:red>".$money_num."</b>":'';
						$db->query("INSERT INTO `{$db_mymps}member_pm` (userid,msg,pubtime)VALUES('$userid','$msg','".time()."')");
					}
				}
				$db->query("UPDATE `{$db_mymps}information` SET info_level = '$action' WHERE id = '$v'");
			}
			
			$id = empty($id)?0:join(',',$id);
			write_msg("��Ϣ ".$id." ״̬תΪ ".$information_level[$action]." �ɹ���",$url,"REcord");
			
		} elseif ($action == "list"){
		
			chk_admin_purview("purview_������Ϣ");
			
			$here = "������Ϣ�б�";
			$where .="WHERE a.title LIKE '%".$title."%'";
			$where .=$catid?" and a.catid IN (".get_cat_children($catid).")":'';
			$where .=($info_level != '')?"and a.info_level = '".$info_level."'":'';
			$sql = "SELECT a.id,a.userid,a.title,a.upgrade_type,a.upgrade_time,a.contact_who,a.ismember,a.info_level,a.begintime,a.areaid,a.ip,b.catname FROM `{$db_mymps}information` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.catid = b.catid $where ORDER BY a.upgrade_type,a.begintime DESC";
			$rows_num = $db -> getOne("SELECT COUNT(*) FROM `{$db_mymps}information` AS a $where");
			$param	= setParam(array("part","title","catid","areaid","info_level"));
			$information = array();
			foreach(page1($sql) as $k =>$row){
				$arr['id']     		 = $row['id'];
				$arr['levelid']      = $row['levelid'];
				$arr['contact_who']  = ($row[ismember]==1)?"<a href=\"javascript:setbg('Mymps��Ա����',400,110,'../public/box.php?part=member&userid=$row[userid]')\">".$row[userid]."</a>":$row['contact_who'];
				$arr['title']   	 = $row['title'];
				$arr['catid']  		 = $row['catid'];
				$arr['catname']  	 = $row['catname'];
				$arr['nowprice']     = $row['nowprice'];
				$arr['begintime']    = GetTime($row['begintime']);
				$arr['ip']           = $row['ip'];
				$arr['info_level']   = $information_level[$row[info_level]];
				$arr['upgrade_type'] = ($row[upgrade_type]>1&&$row['upgrade_time']<time())?$info_upgrade_level[$row[upgrade_type]]."<br /><font color=#cccccc>�ѹ���</font>":$info_upgrade_level[$row[upgrade_type]];
				$information[]      = $arr;
			}
			include(mymps_tpl("information_list"));
			
		} elseif ($action == 'edit'){
		
			switch($do){
				case 'post':
					require_once(MYMPS_SMARTY."/include.php");
					require_once(MYMPS_INC."/upfile.fun.php");
					mymps_check_upimage("mymps_img_");
					
					if(empty($areaid)){write_msg('��ѡ�񷢲�������');exit();}
					$content 	  = textarea_post_change($content);
					$begintime    = time();
					
					$endtime 	  = ($endtime == 0)?0:(($endtime*3600*24)+$begintime);
					$upgrade_type = intval($upgrade_type);
					$upgrade_time = ($upgrade_type==1)?'':(($upgrade_time*3600*24)+$begintime);
					
					if(empty($contact_who)){write_msg("����д��ϵ�ˣ�");exit();}
					if(is_array($extra)){
						foreach ($extra as $k => $v){
							$row = $db -> getRow("SELECT title,required FROM `{$db_mymps}info_typeoptions` WHERE identifier = '$k'");
							if($row[required] == 'on' && empty($v)){write_msg ($row[title]."����Ϊ�գ�");exit();}
							if(strlen($v)>254){write_msg($row[title]."���ó���255���ַ���");exit();}
							if(is_array($v)){
								$v = !empty($v) ? join(',', $v) : 0;
							}
							//$db->query("REPLACE INTO `{$db_mymps}info_extra` (name,value,infoid)VALUES('$k','$v','$id')");
							$db->query("UPDATE `{$db_mymps}info_extra` SET value = '$v' WHERE name = '$k' AND infoid = '$id'");
						}
					}
					$manage_pwd = (is_member == 0&&$manage_pwd) ? "manage_pwd='".md5($manage_pwd)."'," : "";
					$userid = empty($userid) ? "" : "userid='$userid',";
					$sql = "UPDATE `{$db_mymps}information` SET {$manage_pwd} {$userid} title = '$title',content = '$content',catid = '$catid', areaid = '$areaid',begintime = '$begintime', endtime = '$endtime', ismember = '$ismember' , info_level = '$info_level' , qq = '$qq' , email = '$email' , tel = '$tel' , contact_who = '$contact_who' , upgrade_type = '$upgrade_type' , upgrade_time = '$upgrade_time' WHERE id = '$id'";
					$db->query($sql);
					if(is_array($_FILES)){
						for($i=0;$i<count($_FILES);$i++){
							$name_file = "mymps_img_".$i;
							if($_FILES[$name_file]['name']){
								$destination="/information/".date('Ym')."/";
								$mymps_image=start_upload($name_file,$destination,$mymps_global[cfg_upimg_watermark],$mymps_mymps[cfg_information_limit][width],$mymps_mymps[cfg_information_limit][height]);
								if($row = $db -> getRow("SELECT path,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$id' AND image_id = '$i'")){
									@unlink(MYMPS_ROOT.$row[path]);
									@unlink(MYMPS_ROOT.$row[prepath]);
									$db->query("UPDATE `{$db_mymps}info_img` SET image_id = '$i' , path = '$mymps_image[0]' , prepath = '$mymps_image[1]' , uptime = '".time()."' WHERE image_id = '$i' AND infoid = '$id'");
								}else{
									$db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','".time()."')");	
								}
							}
						}
					}
					if($info_level != 0){
						$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
						$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
						$smarty->clear_cache(MYMPS_TPL.'/index.html');
					}
					write_msg("�����ɹ������Ѿ��ɹ��޸ĸ���Ϣ��","?action=list");
				break;
				default:
					require_once(MYMPS_DATA."/info_lasttime.php");
					require_once(MYMPS_DATA."/info.type.inc.php");

					$post 	= is_member_info($id,'admin');
					$catid 	= $post['catid'];
					$areaid = $post['areaid'];
					$cat 	= $db->getRow("SELECT a.if_upimg,a.modid,b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
					$acontent 	= "<textarea name=\"content\" cols=\"60\" rows=\"10\">".de_textarea_post_change($post[content])."</textarea>";
					$last_time 	= ($post[endtime]==0)?0:($post[endtime]-$post[begintime])/3600/24;
					$now = time();
					if($post[upgrage_type] != 1 && $post[upgrade_time] < $now){
						$db->query("UPDATE `{$db_mymps}information` SET upgrade_type = 1, upgrade_time = '' WHERE id = '$id'");
					}
					$post[upgrade_time] = ($post[upgrade_time]==0)?0:($post[upgrade_time]-$post[begintime])/3600/24;
					$post[GetInfoLastTime]	 = GetInfoLastTime($last_time);
					$post[upgrade_type] 	 = GetUpgradeType($post[upgrade_type]);
					$post[mymps_extra_value] = get_category_info_options($cat[modid],$id);
					$post[upload_img] 		 = get_upload_image_edit($cat[if_upimg],$id,'MyMps');
					$post[manage_pwd] 		 = ($post[ismember]==1)?"":'<tr bgcolor="#f5fbff"><td height="25" width="19%">��������</td><td><input type="text" name="manage_pwd" class="text" />  �����޸�������</td></tr>';
					$post[part]="edit";
					$post[submit] = "�޸�";
					$here = "��Ϣ�����޸�";
					include(mymps_tpl("information_edit"));
				break;
			}
		} elseif ($action == 'pm'){

			if(!is_array($id)){write_msg("��û��ѡ���κμ�¼��");exit();}
			$id 	= !empty($id) ? join(',', $id) : 0;
			require_once(dirname(__FILE__)."/include/info_do_type.inc.php");
			$here = "��Ϣ�������Ӵ���";
			include(mymps_tpl("information_pm"));
		}
	break;
}
?>