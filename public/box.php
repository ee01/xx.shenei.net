<?php 
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/
require_once(dirname(__FILE__)."/global.php");
require_once(MYMPS_DATA."/report.type.inc.php");

if($part == 'upgrade'){
	if(empty($action)){
		require_once(MYMPS_DATA."/info.level.inc.php");
		require_once(MYMPS_INC."/member.class.php");
		require_once(MYMPS_DATA."/config.imgcode.php");
		$log = $member_log -> chk_in();
		$row = $db -> getRow("SELECT title,ismember,userid FROM `{$db_mymps}information` WHERE id = '$id' AND info_level != 0");
		if($row && $row[ismember] == 1 && $log == 'true' && $s_uid == $row[userid]){
			$money = $member_log -> get_info("money_own");
			include dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
		}
		elseif($row && $row[ismember] == 1 && ($log == 'false' || $s_uid != $row[userid])){
			
			include dirname(__FILE__)."../".(mymps_tpl("box/login","1"));
		}
		elseif($row && $row[ismember] != 1){
			echo "<center><h2 style=color:red>����ʧ�ܣ��οͷ�������Ϣ���ܽ����ö�������</h2></center>";
		}
		elseif(!$row){
			echo "<center><h2 style=color:red>����ʧ�ܣ�����Ϣ�����ڻ���δͨ����ˣ�</h2></center>";
		}
	}
	exit();
}

if($part == 'do_report'){
	$report_type = trim($_POST['report_type']);
	$ip 		 = GetIP();
	//check if is reported
	if(mymps_count("info_report","WHERE infoid = '$infoid' AND ip = '$ip' AND pubtime > '".mktime(0,0,0)."'") > 0){echo "<center style=\"color:red; font-size:12px; font-weight:bold\">����ʧ�ܣ�����Ϣ���Ѿ��ٱ����ˣ�</font>";exit();}
	$db->query("INSERT INTO `{$db_mymps}info_report` (report_type,content,infoid,infotitle,ip,pubtime)VALUES('$report_type','$content','$infoid','$infotitle','$ip','".time()."')");
	echo "<div style=\"margin:0 15px\"><font style=\"color:red; font-size:12px\"><h1>��л���ľٱ� :)</h1><br />�� ��".$mymps_global[SiteName]."��ÿ������ǧ��Υ����Ϣͨ���û��ٱ���ɾ����<br /><br />�� ������ǲ�С�ĵ���˾ٱ���ť�����ġ�ֻ�е�һ����Ϣ�յ�һ�������ľٱ�ʱ�Żᱻɾ����<br /><br />���������������<a href=\"../public/about.php?part=guestbook\" target=\"_blank\">���������д</a></font></div>";
	exit();
}

if($part == 'information'){
	//information inputed show message
	//related file /template/infobox.html
	$mymps_box[id] = intval($id);
	$mymps_box[infotitle] = trim($title); 
	$mymps_box[seotitle] = "�����ɹ� - ����������Ϣ";
	$mymps_box[notice] = ($level==0)?"<h2 class=\"h\">��Ϣ�����ɹ�</h2>":"<h1 class=\"h\">��Ϣ�����ɹ�</h1>";
	$mymps_box[content] = ($level==0)?"<p>��������ID��Ϊ <strong>".$mymps_box[id]."</strong> &nbsp;����Ϣ�������п��ܰ���Υ�������תΪ<b style=color:red>����״̬</b>������Ա���ͨ���󣬾ͻ��ڱ���վ����ʾ��<a href='../member/info.php?part=input'>�ٷ���һ����Ϣ&raquo;</a></p>":"<p>������Ϣ  <a target=\"_blank\"  href=\"".Rewrite('info',array('id'=>$mymps_box[id]))."\"><b>".$mymps_box[infotitle]."</b></a> &nbsp;�Ѿ��ɹ�����</p><p><b><font color=\"#FF0000\">��ܰ��ʾ��</font></b> ����Ϣ����3���Ӻ���ʾ��Ƶ���б�ҳ�ϣ�</p><p><a href='../member/info.php?part=input'>��Ҫ�ٷ���һ����Ϣ&raquo;</a></p><p style=\"padding:15px 0 0\"><a class=\"button a xxl\" href=\"javascript:setbg('�ö�����Ϣ',710,430,'../public/box.php?part=upgrade&id=".$mymps_box[id]."&do=setbg');\" style=\"width:190px; margin:auto\"><span><i></i>�����ö�����Ϣ</span></a><a class=\"button c xxl\" href=\"".Rewrite('about',array('part'=>'faq','id'=>'9'))."\" target=\"_blank\"><span><i></i>ʲô���ö���Ϣ</span></a></p>";
	//
	$nav_bar = '��Ϣ����״̬��ʾ';
	mymps_global_assign();
	$smarty -> assign("nav_bar",$nav_bar);
	$smarty -> assign("mymps_box",$mymps_box);
	$smarty -> display(mymps_tpl("info_post_write_ok","smarty"));
	exit();
}

//verify the testdirs of mymps
require_once(MYMPS_DATA."/sp_testdirs.php");

//member_redirect redirect to power.php or space

if(empty($part)&&empty($url)&&empty($userid)){
	unknown_err_msg();
}

//check remember
if($part == 'chk_remember'){
	$userid = trim($userid);
	if(empty($userid)){
		echo "<font style='font-size:12px; color:red; margin-left:10px'>�ܱ�Ǹ������д�û��������ύ��⣡</font>";
	} else {
	
		if($mymps_global['cfg_join_othersys'] == 'ucenter'){
		
			include MYMPS_ROOT.'/uc_client/client.php';
			
			if(uc_get_user($userid)){
				echo "<font style='font-size:12px; color:red; margin-left:10px'>���ź������û����ѱ�ע�ᣡ</font>";
			}else{
				echo "<font style='font-size:12px; color:green; margin-left:10px'>��ϲ�㣡���û�����δ��ע�ᣡ</font>";
			}
			
		} else {
			$check = CheckUserID($uid,"�û���");
			if(strlen($userid) < 3 || strlen($userid) > 20){
				echo "<font style='font-size:12px; color:red; margin-left:10px'>�ܱ�Ǹ���û������������ 3 - 20 ���ַ����ڣ�</font>";
				exit();
			}
			if($check == 'ok'){
				$re=$db->getOne("SELECT * FROM {$db_mymps}member WHERE userid like '$userid'");
				if(!$re){
					echo "<font style='font-size:12px; color:green; margin-left:10px'>��ϲ�㣡���û�����δ��ע�ᣡ</font>";
				}else{
					echo "<font style='font-size:12px; color:red; margin-left:10px'>�ܱ�Ǹ�����û����Ѿ���ע�ᣡ</font>";
				}
			}else{
				echo "<font style='font-size:12px; color:red; margin-left:10px'>".$check."</font>";
			}
		}
		
	}
	exit();
}

//member before input info,check the update
if($part == 'checkmemberinfo')
{
	echo "<div style=\"font-size:12px; font-weight:100; margin:-10px 10px;\"><img src=../images/warn.gif align=absmiddle>&nbsp;ϵͳ��⵽������ϵ��ʽ��û����д������<br /><br />����������ϵ��ʽ������ÿ�η�����Ϣ��ʱ�򶼲���Ҫ������д�����<a href=\"../member/update.php?part=contact&url=info.php?part=input\" target=_top>���� &raquo;</a></div>";
	exit();
}

//memberinfopost
//related file:/template/info/post.html
if($part == 'memberinfopost'){
	echo "<div style=\"font-size:12px; font-weight:100; margin:-10px 10px;\"><img src=../images/warn.gif align=absmiddle>&nbsp;����û�е�¼��Ա����,��վ����ǿ��Ҫ��������¼��Ա����ܷ�����Ϣ<br /><br />����ע���Ա�������Ը�����ع����Լ���������Ϣ�����<a href=\"../member/login.php?url=".$url."\" target=_top>���� &raquo;</a></div>";
	exit();
}
//
include dirname(__FILE__)."../".(mymps_tpl("box/".$part,"1"));
?>