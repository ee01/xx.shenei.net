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
require_once(dirname(__FILE__)."/../public/global.php");

function write_pwd_smarty($action='�޸�'){
	global $smarty,$post,$title,$cat,$part,$id;
	$title = "����������� - ".$action."��Ϣ - ".$post[title];
	$nav_bar = '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; ����������� &raquo; '.$action.'��Ϣ</li>';
	$post[part] = $part;
	mymps_global_assign();
	$smarty -> assign("title"	,$title);
	$smarty -> assign("post"	,$post);
	$smarty -> assign("nav_bar" ,$nav_bar);
	$smarty -> assign("cat"		,$cat);
	$smarty -> display(mymps_tpl("info_write_pwd","smarty"));
}

function check_info_contact(){
	global $qq,$email,$tel,$contact_who;
	if(empty($tel)||empty($contact_who)){write_msg("��ϵ�绰����ϵ�˲���Ϊ�գ�");exit();}
	//if(eregi("[^\x80-\xff]",$contact_who)){write_msg("��ϵ��ֻ�����뺺�֣�");exit();}
	if(!is_tel($tel)){write_msg("����ȷ����绰���룡");exit();}
}

function submit_check($var, $allowget = 0) {
    if(empty($GLOBALS[$var])) return false;
    if($allowget || ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) ||
            preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])))) {
        return true;
    } else {
        exit('Access Denied');
    }
}

if($part == 'edit'){
	require_once(MYMPS_INC."/upfile.fun.php");
	require_once(MYMPS_INC."/member.class.php");
}elseif($part == 'input'){
	require_once(MYMPS_INC."/upfile.fun.php");
	require_once(MYMPS_DATA."/config.imgcode.php");
	require_once(MYMPS_INC."/ip.class.php");
}

if(!submit_check('mymps_submit')){
	//δ�ύ����
	
	if($part == 'input'){
		//��Ϣ����ҳ
		if(empty($catid)){
			//ѡ�����ҳ
			$smarty -> cache_lifetime    = $mymps_cache['select']['time'];
			$smarty -> caching           = $mymps_cache['select']['open'];
			
			if (!$smarty->is_cached(mymps_tpl('info_post','smarty'),$part)){
				
				$nav_bar = '<a href=../>��վ��ҳ</a> &raquo; ��д��Ϣ����';
				
				$title		 = "��һ�� - ѡ����Ϣ����";
				
				mymps_global_assign();
				$smarty -> assign("nav_bar"		,$nav_bar);
				$smarty -> assign("scategory"	,$category_cache_s);
				$smarty -> assign("FirstSecond" ,$FirstSecond);
				$smarty -> assign("title"		,$title);
				$smarty -> assign("first_cat"	,$first_cat);
			}
			
			$smarty -> display(mymps_tpl("info_post","smarty"),$part);
		} else {
			//��д��Ϣҳ
			
			$FirstSecond = FirstSecond($catid);
			require_once(MYMPS_INC."/member.class.php");
			require_once(MYMPS_DATA."/info_lasttime.php");
			require_once(MYMPS_DATA."/info.type.inc.php");
			
			$log = $member_log->chk_in();
			if($log)chk_member_purview("purview_����������Ϣ");
			
			$acontent = "<textarea name=\"content\" cols=\"100\" rows=\"10\"></textarea><br />
��������10�����֣����������������ϵ��ʽ�Լ���ַ��������ܱ�����Աɾ��";

			$cat = $db ->getRow("SELECT a.catid as scatid,a.modid,a.catname as scatname,a.if_upimg,b.catid,b.catname FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			
			if(!empty($mymps_global['cfg_allow_post_area'])){
			
				/*IP��ʹ���ж�*/
				$nowip = GetIP();
				if($mymps_mymps['ipdat_choose'] != 1){
					$now_area = ip2area($nowip);
				}else{
					$ipchoose = new ipLocation();
					$address  =	$ipchoose -> getaddress($nowip);
					$ipchoose =	NULL;
					$now_area = $address["area1"];
				}
				
				if(!strstr($now_area,$mymps_global['cfg_allow_post_area']))
				{
					write_msg("����ʧ�ܣ�<b style='color:red'>".$mymps_global['cfg_allow_post_area']."</b> �����������������Ϣ��");
					exit();
				}
			}
			
			if($log){
				
			
				//�жϽ���Ƿ��㹻
				$his_money = $member_log -> get_info("money_own");
				if(($his_money - $mymps_global[cfg_member_perpost_consume]) < 0){
					write_msg('�����û���� <font color=red><b>'.$his_money.'</b></font>���� �����ٷ�����Ϣ������ϵ����Ա��ֵ');
					exit();
				}
				
				//�ж��Ƿ��ܷ�����Ϣ
				$per = $db->getRow("SELECT b.perday_maxpost FROM `{$db_mymps}member` AS a LEFT JOIN `{$db_mymps}member_level` AS b ON a.levelid = b.id WHERE a.userid = '$s_uid'");
				$perday_maxpost = $per[perday_maxpost];
				
				if(!empty($perday_maxpost)){
					$count = mymps_count("information","WHERE userid LIKE '$s_uid' AND begintime > '".mktime(0,0,0)."'");
					if($count >= $perday_maxpost){
						write_msg("�ܱ�Ǹ������ǰ�Ļ�Ա����ÿ��ֻ�ܷ���".$perday_maxpost."��������Ϣ");
						exit();
					}
				}
				
				$onload 			= '';
				$areaid 			= $member_log -> get_info("areaid");
				$post[tel] 			= $member_log -> get_info("tel");
				$post[qq] 			= $member_log -> get_info("qq");
				$post[email] 		= $member_log -> get_info("email");
				$post[userid] 		= $member_log -> get_info("userid");
				$post[contact_who]  = $member_log -> get_info("cname");
				$post[ismember] 	= 1;
				
			}else{
			
				if(!empty($mymps_global[cfg_nonmember_perday_post])){
					$count = mymps_count("information","WHERE ip LIKE '$ip' AND begintime > '".mktime(0,0,0)."'");
					if($count >= $mymps_global[cfg_nonmember_perday_post]){
						write_msg("�ܱ�Ǹ���ǻ�Աÿ��ֻ�ܷ���".$mymps_global[cfg_nonmember_perday_post]."��������Ϣ");
						exit();
					}
				}
				
				if($mymps_global['cfg_if_nonmember_info']=='0'){
					write_msg("�Բ�������û�е�¼��������¼���ٷ�����Ϣ��","/member/login.php?url=".urlencode(getUrl()));
					exit();
				}
				
				$onload = ($mymps_global[cfg_if_nonmember_info_box] == 1)?"javascript:setbg('��������½���ٽ��д˴β���',450,100,'../public/box.php?part=memberinfopost&url=".urlencode(urlencode(getUrl()))."')":"";
				
				$post[manage_pwd] =  "<li><span class=\"span_1\"><font color=red>*</font>�������룺</span><span class=\"span_2\"><input type=\"password\" class=\"textInt\" name=\"manage_pwd\" size=\"40\"/>  �������������޸Ļ�ɾ������Ϣ</span></li>";
				
				$post[imgcode]= ($mymps_imgcode[post][open] == 1) ? "<li><span class=\"span_1\"><font color=red>*</font>��֤�룺</span><span class=\"span_2\"><input type=\"text\" name=\"checkcode\" class=\"textInt\" style=\"width:70px\"/> <img src=\"../include/chkcode.php\" alt=\"�����壬����ˢ��\" width=\"70\" height=\"25\" align=\"absmiddle\" style=\"cursor:pointer;\" onClick=\"this.src=this.src+'?'\"/></span></li>":"";	
					
				$post[ismember] = '0';
			}
			
			$nav_bar 	= '<a href=../>��վ��ҳ</a> &raquo; <a href=?part=input>ѡ����Ϣ����</a> &raquo; ��д��Ϣ����';
			$title 		= "��д��Ϣ���� - ".$cat[scatname]." - ����������Ϣ";
			$cat 		= get_cat_info($catid,'srow');

			if($FirstSecond == 'first'){
				write_msg('���ڶ�����Ŀ�·�����Ϣ��');
				exit();
			}elseif($FirstSecond == 'second'){
				$post[location]	=	$cat[catname].' &raquo '.$cat[scatname];
			}
			//get the mod
			$post[mymps_extra_value] = get_category_info_options($cat[modid]);
			$post[upload_img] 		 = get_upload_image_view($cat[if_upimg],$id);
			
			//Get information Last Time
			$post[GetInfoLastTime]	 = GetInfoLastTime();
			$post[part]		 	  	 ="input";
			$post[submit]		  	 = "����";
			$post[ip]				 = GetIP();
			$post[catid]			 = $catid;
			
			mymps_global_assign();
			$smarty -> assign("cat"		, $cat);
			$smarty -> assign("post"	, $post);
			$smarty -> assign("title"	, $title);
			$smarty -> assign("nav_bar"	, $nav_bar);
			$smarty -> assign("onload"	, $onload);
			$smarty -> assign("acontent", $acontent);
			$smarty -> display(mymps_tpl("info_post_write","smarty"));
			
		}
		
	} elseif ($part == 'all') {
	
		require_once("global.php");
		require_once(MYMPS_DATA."/info.level.inc.php");
		
		chk_member_purview("purview_�����ҵ���Ϣ");
		
		$here = "�ҷ����ķ�����Ϣ";
		$page = empty($page) ? '1' : intval($page);
		$where = "WHERE userid = '$s_uid'";
		$sql = "SELECT id,info_level,title,content,begintime,endtime,hit,upgrade_type,upgrade_time FROM {$db_mymps}information $where ORDER BY begintime DESC";
		$rows_num = mymps_count("information",$where);
		$param=setParam(array("part"));
		$art = array();
		foreach(page1($sql) as $k => $row){
			$arr['id']          = $row['id'];
			$arr['title']  	    = substring(SpHtml2Text($row['title']),0,20);
			$arr['content']     = substring(SpHtml2Text($row['content']),0,45);
			$arr['begintime']   = get_format_time($row['begintime']);
			$arr['endtime']  	= $row['endtime'];
			$arr['hit']  		= $row['hit'];
			$now 				= time();
			
			if($row[upgrage_type] != 1 && $row[upgrade_time] < $now){
				$db->query("UPDATE `{$Db_mymps}information` SET upgrade_type = 1, upgrade_time = '' WHERE id = '$row[id]'");
				
			}
			$arr['info_level']   = $information_level[$row['info_level']];
			
			$arr['upgrade_type'] = ($row[upgrade_type]>1&$row['upgrade_time']<time())?$info_upgrade_level[$row[upgrade_type]]."<br /><font color=#cccccc>�ѹ���</font>":$info_upgrade_level[$row[upgrade_type]];
			$art[]= $arr;
		}
		
		$tpl=mymps_tpl("info_all");
		include(mymps_tpl("index"));
		
	} elseif($part == 'edit') {
		
		require_once(MYMPS_DATA."/info_lasttime.php");
		$id 	= intval($id);
		$post 	= is_member_info($id);
		$catid 	= $post['catid'];
		$areaid = $post['areaid'];
		$cat = $db->getRow("SELECT a.if_upimg,a.modid,b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
		
		if($post[ismember] == 1){
		
			$log = $member_log -> chk_in();
			
			if(!$log){
				write_msg("����ʧ�ܣ�������¼����ִ�д˲�����");
				exit();
			}elseif($log && $s_uid != $post[userid]){
				write_msg("����ʧ�ܣ�����δ��¼���߸���Ϣ�����������ģ�","/member/index.php");
				exit();
			}
			
			$post[ismember] = 1;
			
			$nav_bar 		= '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; �޸���Ϣ';
			
		}elseif($post[ismember] == 0 &&!empty($manage_pwd)){
		
			if(if_other_site_post()){
				$msgs[]="�벻Ҫ���Դ�վ���ύ���ݣ�";
				show_msg($msgs);
				exit();
			}
			
			if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."' AND ismember = 0") == 0){
				write_msg("����ʧ�ܣ�������Ĺ������벻��ȷ��");
				exit();
			}
			
			$post[manage_pwd]= "<li><span class=\"span_1\">�������룺</span><span class=\"textInt\" ><input type=\"password\" class=\"textInt\"  name=\"manage_pwd\" size=\"40\" />
�粻�޸ģ�������</span></li>";
			$post[ismember] = 0; 
			$nav_bar 		= '<a href="../public/info.php?id='.$id.'">'.$post[title].'</a> &raquo; <a href="../member/info.php?part=edit&id='.$id.'">�����������</a> &raquo; �޸���Ϣ';
			
		}elseif($post[ismember] == 0 &&empty($manage_pwd)){
		
			write_pwd_smarty("�޸�");
			exit();
			
		}
		
		$acontent = "<textarea name=\"content\" cols=\"100\" rows=\"10\">".de_textarea_post_change($post[content])."</textarea><br />
��������10�����֣����������������ϵ��ʽ�Լ���ַ��������ܱ�����Աɾ��";
		$title 	  = "�޸���Ϣ���� - ".$post[title];
		$post[GetInfoLastTime]	 =	GetInfoLastTime();
		
		require_once(MYMPS_DATA."/info.type.inc.php");
		
		$post[mymps_extra_value] = get_category_info_options($cat[modid],$id);
		$post[upload_img] 		 = get_upload_image_edit($cat[if_upimg],$id);
		$post[part]				 = "edit";
		$post[submit] 			 = "�޸�";
		$post[location]			 =	"<select name=catid disabled>".get_cat_options($cat[catid])."</select> �����޸�";
		
		mymps_global_assign();
		
		$smarty -> assign("post"	,$post);
		$smarty -> assign("title"	,$title);
		$smarty -> assign("nav_bar"	,$nav_bar);
		$smarty -> assign("cat"		,$cat);
		$smarty -> assign("acontent",$acontent);
		$smarty -> display(mymps_tpl("info_post_write","smarty"));
	
	} elseif($part == 'del') {
	
		require_once(MYMPS_INC."/member.class.php");
		$id 	= intval($id);
		$post 	= is_member_info($id);
		
		if(!empty($id)&&empty($manage_pwd)&&$post['ismember']==0){
			$cat = $db->getRow("SELECT b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			write_pwd_smarty("ɾ��");
			exit();
		}elseif(!empty($id)&&(!empty($manage_pwd)||$post['ismember']==1)){
		
			if($post['ismember'] == 0){
				if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."'")<=0){
					write_msg("����ʧ�ܣ��������˴���Ĺ������룡");
					exit();
				}
			}
			if($post['ismember'] == 1){
				$log = $member_log -> chk_in();
				if(!$log || $s_uid != $post[userid])
				{
					write_msg("����ʧ�ܣ�����δ��¼���߸���Ϣ�����������ģ�");
					exit();
				}
			}
			
			$image = $db->getAll("SELECT id,path,prepath FROM `{$db_mymps}info_img` WHERE infoid = '$id'");
			
			if(is_array($image)){
			
				foreach ($image as $k => $v){
					if(file_exists(MYMPS_ROOT.$v[prepath])){
						@unlink(MYMPS_ROOT.$v[prepath]);
					}
					if(file_exists(MYMPS_ROOT.$v[path])){
						@unlink(MYMPS_ROOT.$v[path]);
					}
					mymps_delete("info_img","WHERE id = $v[id]");
				}
				
			}
			
			mymps_delete("information","WHERE id = '$id'");
			mymps_delete("info_extra","WHERE infoid = '$id'");
			
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$cat[catid],0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$cat[catid],$areaid);
			
			$url = ($post[ismember] == 1) ? 'info.php?part=all' : $mymps_global['SiteUrl'];
			write_msg("�ɹ�ɾ�����Ϊ $id ����Ϣ��",$url);
			
		}
	
	} elseif($part == 'clear') {
		
		unset($manage_pwd);
		write_msg("","?part=edit&id=".$id);
		
	} elseif($part == 'refresh') {
	
		require_once(MYMPS_INC."/member.class.php");
		
		$id		 = intval($id);
		$post	 = is_member_info($id);
		
		if(!empty($id)&&empty($manage_pwd)&&$post[ismember]==0){
		
			$cat = $db->getRow("SELECT b.catid FROM `{$db_mymps}category` AS a LEFT JOIN `{$db_mymps}category` AS b ON a.parentid = b.catid WHERE a.catid = '$catid'");
			write_pwd_smarty("ˢ��");
			exit();
			
		}elseif(!empty($id)&&(!empty($manage_pwd)||$post['ismember']==1)){
		
			if($post['ismember'] == 0){
				if(mymps_count("information","WHERE id = '$id' AND manage_pwd = '".md5($manage_pwd)."'")<=0){
					write_msg("����ʧ�ܣ��������˴���Ĺ������룡");
					exit();
				}
			}
			
			if($post['ismember'] == 1){
				if(!$member_log -> chk_in() || $s_uid != $post[userid]){
					write_msg("����ʧ�ܣ�����δ��¼���߸���Ϣ�����������ģ�");
					exit();
				}
			}
			
			$now = time();
			$row = $db -> getRow("SELECT begintime,endtime FROM `{$db_mymps}information` WHERE id = ".$id);
			$endtime = $row[endtime]==0?0:$row[endtime]-$row[begintime]+$now;
			$db->query("UPDATE `{$db_mymps}information` SET begintime = '$now' , endtime = '$endtime' WHERE id =".$id);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			$url = $url ? $url : Rewrite('info',array('id'=>$id));
			write_msg("���Ϊ $id ����Ϣ�ѳɹ�ˢ�£�",$url);
			
		}
	
	} elseif($part == 'upgrade') {
	
		require_once(dirname(__FILE__)."/global.php");
		$id 	= intval($id);
		$action = trim($action);
		if(empty($action)){
			require_once(MYMPS_DATA."/info.level.inc.php");
			require_once(MYMPS_INC."/member.class.php");
			$log = $member_log -> chk_in();
			$row = $db -> getRow("SELECT title,ismember,userid FROM `{$db_mymps}information` WHERE id = '$id' AND info_level != 0");
			
			if($row && $row[ismember] == 1 && $log && $s_uid == $row[userid]){
				$here 	= "<a href=\"?part=all\">�ҷ����ķ�����Ϣ</a> &raquo; ��Ϣ�ö�����";
				$money	= $member_log -> get_info("money_own");
				$tpl 	= dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
				include (mymps_tpl("index"));	
			}elseif($row && $row[ismember] == 1 && (!$log || $s_uid != $row[userid])){
				echo "<center><h2 style=\"color:red;\">����ʧ�ܣ���û�и���Ϣ�Ĳ���Ȩ�޻�������û�е�¼��</h2></center>";
			}elseif($row && $row[ismember] != 1){
				echo "<center><h2 style=color:red>����ʧ�ܣ��οͷ�������Ϣ���ܽ����ö�������</h2></center>";
			}elseif(!$row){
				echo "<center><h2 style=color:red>����ʧ�ܣ�����Ϣ�����ڻ���δͨ����ˣ�</h2></center>";
			}
		}elseif($action == 'do_post'){
		
			require_once(MYMPS_SMARTY."/include.php");
			$id				 = intval($id);
			$catid			 = intval($catid);
			$money_cost 	 = $upgrade_time * $upgrade_type;
			$index_up 	 	 = $mymps_global[cfg_member_upgrade_index_top];
			$list_up 		 = $mymps_global[cfg_member_upgrade_list_top];
			$upgrade_time 	 = ($upgrade_time*3600*24)+time();
			$money = $member_log -> get_info("money_own");
			
			if($money < $money_cost){
				if($do == 'setbg')write_msg("�����ʻ����㣬���ȳ�ֵ");
				else write_msg("�����ʻ����㣬���ȳ�ֵ","bank.php");
				exit();
			}
			//�ع�����
			$upgrade_arr 	= array($list_up=>'2',$index_up=>'3');
			$up_type 		= $upgrade_arr[$upgrade_type];//ȡ���ö�����
			$do_upgrade 	= $db->query("UPDATE `{$db_mymps}information` SET upgrade_type = '$up_type' , upgrade_time = '$upgrade_time' WHERE id = '$id' AND userid = '$userid'");
			
			if(!$do_upgrade){
				write_msg("����ʧ�ܣ��뷵�����²�����");
				exit();
			}
			
			$do_money = $db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - '$money_cost' WHERE userid = '$userid'");
			if($do_money){
			
				if($up_type == 2){
					$do_action = '�����ö�';
					$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
				}elseif($up_type == 3){
					$do_action = '����ҳ';
					$smarty->clear_cache(mymps_tpl('index','smarty'));
				}
				
				$url 	 = '?part=all';
				$do_type = $record_part['use'];
				write_money_use("���Ϊ $id ����Ϣ��ִ�� <b>".$do_action."</b> ����","<font color=red>�۳���� ".$money_cost." </font>",$do_type);
				write_msg("�����ɹ��������ʻ��ѿ۳���� <b style=color:red>".$money_cost."</b>",$url);
			}else{
				write_msg("����ʧ�ܣ��뷵�����²�����");
			}
		}
	
	}
	
} else {
	//���ύ����
	
	if($part == 'input'){
		
		!$catid && write_msg('��ѡ����Ҫ��������Ŀ���');
		
		if(!empty($mymps_global['cfg_allow_post_area'])){
		
			/*IP��ʹ���ж�*/
			$nowip = GetIP();
			if($mymps_mymps['ipdat_choose'] != 1){
				$now_area = ip2area($nowip);
			}else{
				$ipchoose = new ipLocation();
				$address  =	$ipchoose -> getaddress($nowip);
				$ipchoose =	NULL;
				$now_area = $address["area1"];
			}
			
			if(!strstr($now_area,$mymps_global['cfg_allow_post_area']))
			{
				write_msg("����ʧ�ܣ�<b style='color:red'>".$mymps_global['cfg_allow_post_area']."</b> �����������������Ϣ��");
				exit();
			}
		}
		
if(!empty($mymps_global['cfg_forbidden_post_ip'])){
			$nowip=GetIP();
			$forbiddenip=explode(',',$mymps_global['cfg_forbidden_post_ip']);
			if(in_array($nowip,$forbiddenip)){
				write_msg("����ǰ��IP <b style='color:red'>".$nowip."</b> �ѱ�����Ա������������������޸���Ϣ��");
				exit();
			}
		}
		
		mymps_check_upimage("mymps_img_");
		
		$areaid = intval($areaid);
		if(empty($areaid)){
			write_msg('��ѡ����Ҫ�����ĵ�����');
			exit();
		}
		
		$title = trim($title);
		if(empty($title)){
			write_msg("��������Ϣ����!");
			exit();
		}
		
		$content = textarea_post_change($content);
		if(empty($content)){
			write_msg("����û��������Ϣ����!");
			exit();
		}
		
		$begintime 	= time();
		$endtime	= intval($endtime);
		$endtime 	= ($endtime == 0)?0:(($endtime*3600*24)+$begintime);
		check_info_contact();
		$ismember	= intval($ismember);
		$ip 		= GetIP();
		$img_count 	= upload_img_num("mymps_img_");
		$result 	= verify_badwords_filter($mymps_global[cfg_if_info_verify],$title,$content);
		$title 		= $result[title];
		$content 	= $result[content];
		$info_level = $result[level];
		
		if(is_array($extra)){
			foreach ($extra as $k => $v){
				$row = $db -> getRow("SELECT title,required FROM `{$db_mymps}info_typeoptions` WHERE identifier = '$k'");
				if($row[required] == 'on' && empty($v)){write_msg ($row[title]."����Ϊ�գ�");exit();}
				if(strlen($v)>254){write_msg($row[title]."���ó���255���ַ���");exit();}
			}
		}
		
		/*
		if(mymps_count("information","WHERE title LIKE '$title' AND content LIKE '$content'")>0){
			write_msg("�벻Ҫ�����ظ����ݵ���Ϣ��");
			exit();
		}
		*/
		
		if ($ismember == 0 || $ismember == '' || empty($ismember)){
			
			if($mymps_global['cfg_if_nonmember_info']=='0'){
				write_msg("�Բ�������û�е�¼��������¼���ٷ�����Ϣ��","/member/login.php?url=".urlencode(getUrl()));
				exit();
			}
		
			if(empty($manage_pwd)){write_msg("���������Ĺ������룡�Ա����Ժ�Ը���Ϣ���޸ĺ�ɾ��");exit();}
			if(empty($contact_who)){write_msg("����д��ϵ�ˣ�");exit();}
			$manage_pwd = md5($manage_pwd);
			
			if($mymps_imgcode[post][open] == 1){
				mymps_chk_randcode();
			}
			
			$sql = "INSERT INTO `{$db_mymps}information` (title,content,catid,areaid,begintime,endtime,manage_pwd,ismember,ip,info_level,qq,email,tel,contact_who,img_count)VALUES('$title','$content','$catid','$areaid','$begintime','$endtime','$manage_pwd','$ismember','$ip','$info_level','$qq','$email','$tel','$contact_who',$img_count)";
			
		}elseif($ismember == 1){
		
			$perpost_money_cost = $mymps_global[cfg_member_perpost_consume];
			$userid = trim($userid);
			$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - ".$mymps_global[cfg_member_perpost_consume]." WHERE userid = '$userid'");
			$sql = "INSERT INTO `{$db_mymps}information` (title,content,begintime,endtime,catid,areaid,userid,ismember,ip,info_level,qq,email,tel,contact_who,img_count) Values ('$title','$content','$begintime','$endtime','$catid','$areaid','$userid','$ismember','$ip','$info_level','$qq','$email','$tel','$contact_who','$img_count')";

			if(!empty($perpost_money_cost)){
				$db->query("UPDATE `{$db_mymps}member` SET money_own = money_own - '$perpost_money_cost' WHERE userid = '$userid'");
			}
			
		}else {
			
			unknown_err_msg();
		
		}
		
		$db->query($sql);
		$id = $db -> insert_id();
		
		if(is_array($extra)){
			foreach ($extra as $k => $v){
				if(is_array($v)){
					$v = !empty($v) ? join(',', $v) : 0;
				}
				$db->query("INSERT INTO `{$db_mymps}info_extra` (infoid,name,value)VALUES('$id','$k','$v')");
			}
		}
		
		if(is_array($_FILES)){
			for($i=0;$i<count($_FILES);$i++){
				$name_file = "mymps_img_".$i;
				if($_FILES[$name_file]['name']){
					$destination="/information/".date('Ym')."/";
					$mymps_image=start_upload($name_file,$destination,$mymps_global[cfg_upimg_watermark],$mymps_mymps[cfg_information_limit][width],$mymps_mymps[cfg_information_limit][height]);
					$db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','".time()."')");
				}
			}
		}
		
		if($info_level != 0){$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);}
		write_msg("","../public/box.php?part=information&id=".$id."&title=".urlencode($title)."&level=".$info_level);
	
	} elseif($part == 'edit') {
	
		$id = intval($id);
		
		mymps_check_upimage("mymps_img_");
		
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
						
					} else {
						$db->query("INSERT INTO `{$db_mymps}info_img` (image_id,path,prepath,infoid,uptime) VALUES ('$i','$mymps_image[0]','$mymps_image[1]','$id','".time()."')");	
						
					}
				}
			}
		}
		
		$userid = trim($userid);
		$catid  = intval($catid);
		$areaid = intval($areaid);
		
		if(empty($areaid)){
			write_msg('��ѡ����Ҫ�����ĵ�����');
			exit();
		}
		
		$title = trim($title);
		
		if(empty($title)){
			write_msg("��������Ϣ����!");
			exit();
		}
		
		$content = textarea_post_change($content);
		if(empty($content)){
			write_msg("����û��������Ϣ����!");
			exit();
		}
		
		$begintime 	= time();
		$endtime 	= intval($endtime);
		$endtime 	= ($endtime == 0)?0:(($endtime*3600*24)+$begintime);
		check_info_contact();
		$ismember 	= intval($ismember);
		$ip 		= GetIP();
		$result 	= verify_badwords_filter($mymps_global[cfg_if_info_verify],$title,$content);
		$title 		= $result[title];
		$content 	= $result[content];
		$info_level = $result[level];
		
		if(empty($contact_who)){
			write_msg("����д��ϵ�ˣ�");
			exit();
		}
		
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
		
		$manage_pwd = empty($manage_pwd) ? "" : "manage_pwd='".md5($manage_pwd)."',";
		$userid 	= empty($userid) ? "" : "userid='$userid',";
		$img_count 	= mymps_count("info_img","WHERE infoid = '$id'");
		$sql 		= "UPDATE `{$db_mymps}information` SET {$manage_pwd} {$userid} title = '$title',content = '$content',catid = '$catid', areaid = '$areaid',begintime = '$begintime', endtime = '$endtime', ismember = '$ismember' , ip = '$ip' , info_level = '$info_level' , qq = '$qq' , email = '$email' , tel = '$tel' , contact_who = '$contact_who' , img_count = '$img_count' WHERE id = '$id'";
		
		$db->query($sql);
		
		if($info_level != 0){
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,0);
			$smarty->clear_cache(mymps_tpl('info_list','smarty'),$catid,$areaid);
			
		}
		write_msg("�����ɹ������Ѿ��ɹ��޸ĸ���Ϣ��",Rewrite('info',array('id'=>$id)));
	
	} else{

		unknown_err_msg();
	
	}
	
}
?>