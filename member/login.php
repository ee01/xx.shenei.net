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
require_once(MYMPS_INC."/member.class.php");
require_once(MYMPS_DATA."/config.imgcode.php");
require_once(MYMPS_MEMBER.'/include/log.func.php');
$part = $part ? $part : 'default' ;

if ($part == 'register') {

	if ($mymps_global[cfg_if_member_register] != 1) {
		write_msg("����ʧ�ܣ�ϵͳ����Ա�ر����»�Աע�ᣡ");
		exit;
	}
	
	if ($member_log->chk_in()) {
		write_msg('','index.php');
		exit;
	}

	require(MYMPS_DATA.'/safequestions.php');
	$nav_bar = '<a href=../>��վ��ҳ</a> &raquo; ��Աע��';
	mymps_global_assign();
	$smarty -> assign("safequestion",GetSafequestion(0,'safequestion'));
	$smarty -> assign("mymps_imgcode",$mymps_imgcode[register]);
	$smarty -> assign('submit','����ע��');
	$smarty -> assign("nav_bar",$nav_bar);
	$smarty -> display(mymps_tpl("register","smarty"));
	
} elseif($part== 'reg_new') {

	include(MYMPS_MEMBER.'/include/reg.inc.php');
	
} elseif ($part == 'out') {

	if($mymps_global['cfg_join_othersys'] == 'login'){
		setcookie('myauth', '', -86400);
	}
	
	$member_log -> out(trim($url));

} elseif ($part == 'chk_remember') {
	
	if(strlen($userid) < 5){
		$msgs[]="����û������̣�������ע�ᣡ";
		show_msg($msgs);
		exit;
	}
	
	if($db->getOne("SELECT * FROM `{$db_mymps}member` WHERE userid='$userid'")){
		$msgs[]="��ָ�����û��� {$userid} �Ѵ��ڣ���ʹ�ñ���û�����";
		show_msg($msgs);
		exit;
	} else {
		$msgs[]="��ϲ�㣬���û�����δע�ᣡ";
		show_msg($msgs);
		exit;
	}
	
} elseif ($part == 'forgetpass') {

	include(MYMPS_MEMBER.'/include/'.$part.'.inc.php');
	
} elseif ($part == 'islogin') {

	if(!$member_log->chk_in()){
		echo "document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?url=".$url."\\\">��¼<\\/a><\\/li><li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?part=register\\\">ע��<\\/a><\\/li>\")";
	}else{
		//$count_message = mymps_count("member_pm","WHERE userid ='$s_uid' AND if_read = 0");
		echo "document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/index.php\\\" target=_blank>".$s_uid."<\\/a> [<a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/login.php?part=out&url=".$url."\\\">�˳�<\\/a>]<\\/li>\");
		document.writeln(\"<li><a href=\\\"\\".$mymps_global[SiteUrl]."/member\\/index.php\\\" target=_blank>�������<\\/a><\\/li>\");";
	}
	
} elseif ($part == 'default'){

	if($mymps_global[cfg_if_member_log_in] != 1){
		write_msg("����ʧ�ܣ�ϵͳ����Ա�ر��˻�Ա��¼���ܣ�");
		exit;
	}

	if ($action=="dopost"){
	
		include(MYMPS_MEMBER.'/include/login.inc.php');
		
	} else {
	
		$smarty -> cache_lifetime    = $mymps_cache['login']['time'];
		$smarty -> caching           = $mymps_cache['login']['open'];

		if(!$member_log->chk_in()){
			$url=trim($url);
			if (!$smarty->is_cached(mymps_tpl('login','smarty'),$url)){
				mymps_global_assign();
				$nav_bar = '<a href=../>��վ��ҳ</a> &raquo; ��Ա��¼';
				$smarty -> assign("mymps_imgcode",$mymps_imgcode[login]);
				$smarty -> assign("url",$url);
				$smarty -> assign("nav_bar",$nav_bar);
			}
			$smarty -> display(mymps_tpl("login","smarty"),$url);
		} else {
			write_msg("","index.php");
		}			
	}
	
} else {
	unknown_err_msg();
}
?>