<?php
$log = $member_log->chk_in();

$userid 	= trim($userid);
$userpwd 	= trim($userpwd);
$reuserpwd 	= trim($reuserpwd);
$email	 	= trim($email);

if($mymps_imgcode[register][open] == 1 && empty($activation)){
	mymps_chk_randcode();
}

if($reuserpwd!=$userpwd && empty($activation)){
	write_msg("你两次输入的密码不一致！");
	exit();
}

if($mymps_global['cfg_join_othersys'] == 'ucenter'){

	include MYMPS_ROOT.'/uc_client/client.php';
	
	setcookie('myauth', '', -86400);
	
	//在UCenter注册用户信息
	if(!empty($activation) && ($activeuser = uc_get_user($activation))) {
	
		list($uid, $userid) = $activeuser;
		
	} else {
	
		if(uc_get_user($userid) && !$db->getOne("SELECT userid FROM {$db_mymps}member WHERE userid='$userid'")) {
			//判断需要注册的用户如果是需要激活的用户，则需跳转到登录页面
			write_msg("该用户无需注册，请重新登录",$mymps_global[SiteUrl]."/member/login.php");
			exit;
		}

		$uid = uc_user_register($userid,$userpwd, $email);
		
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
			$userpwd = $userpwd ? trim($userpwd) : MD5(random());
			$email 	 = trim($email);
			
		}
		
	}
	
} else {
	
	$rs	= CheckUserID($userid,'用户名');
	
	if($rs != 'ok'){
		write_msg($rs);
		exit();
	}
	
	if(strlen($userid) > 20){
		write_msg("你的用户名或昵称名称过长，不允许注册！");
		exit();
	}
	
	if(strlen($userid) < 3 || strlen($userpwd) < 5){
		write_msg("你的用户名或密码过短(不能少于3个字符)，不允许注册！");
		exit();
	}
	
	if(!is_email($email)){
		write_msg("Email格式不正确！");
		exit();
	}
	
	if($db->getOne("Select id From `{$db_mymps}member` where userid like '$userid' ")){
		write_msg("你指定的用户名 {$userid} 已存在，请使用别的用户名！");
		exit();
	}
}

if($userid) {

	member_reg($userid,md5($userpwd));
	if($mymps_global['cfg_join_othersys'] == 'ucenter'){
		setcookie('auth', uc_authcode($uid."\t".$userid, 'ENCODE'));
	}
	$member_log -> in($userid,$memory,$url,"reg_new");
	write_msg("恭喜你！注册成功,现在正转入用户管理中心",$mymps_global[SiteUrl]."/member/index.php");
	
}

?>