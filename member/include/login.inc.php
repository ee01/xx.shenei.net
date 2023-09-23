<?php
$userid	 	= trim($userid);
$userpwd 	= trim($userpwd);
$loginip 	= GetIP();
$logintime  = time();
$memory 	= trim($memory);
$url 		= trim($url);

if ($mymps_imgcode[login][open] == 1){
	mymps_chk_randcode();
}

if ($userid =="" || $userpwd ==""){
	write_msg("用户帐号或密码不能为空");
	exit();
}

if($mymps_global['cfg_join_othersys'] == 'ucenter'){
	
	include MYMPS_ROOT.'/uc_client/client.php';

	//通过接口判断登录帐号的正确性，返回值为数组
	list($uid, $username, $password, $email) = uc_user_login($userid, $userpwd);

	setcookie('myauth', '', -86400);
	
	if($uid > 0) {
	
		if(!$db->getOne("SELECT count(*) FROM {$db_mymps}member WHERE userid='$userid'")) {
			//判断用户是否存在于用户表，不存在则注册
			member_reg($userid,random());
		}
		//用户登陆成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
		setcookie('myauth', uc_authcode($uid."\t".$userid, 'ENCODE'));
		//生成同步登录的代码
		$ucsynlogin = uc_user_synlogin($uid);
		
		$db->query("UPDATE `{$db_mymps}member` SET loginip = '$loginip',logintime = '$logintime' WHERE userid = '$userid'");
		
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','1')");
		
		$member_log -> in($userid,$memory,$url);
		
		write_msg('登录成功'.$ucsynlogin.'');
		
		exit;
		
	} else {
	
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','0')");
		
		if($uid == -1) {
			write_msg( '用户不存在,或者被删除');
			exit;
		} elseif ($uid == -2) {
			write_msg( '密码输入错误');
			exit;
		} else {
			write_msg( '未定义操作');
			exit;
		}
		
	}
	
} else {

	$row = $db -> getRow("SELECT userid,userpwd FROM `{$db_mymps}member` WHERE userid='$userid' AND userpwd='".md5($userpwd)."'");
	
	if($row){
	
		$db->query("UPDATE `{$db_mymps}member` SET loginip = '$loginip',logintime = '$logintime' WHERE userid = '$userid'");
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','1')");

		$s_uid = $row['userid'];
		$member_log -> in($s_uid,$memory,$url);
		
	}else{
	
		$db->query("INSERT INTO `{$db_mymps}member_record_login` (id,userid,userpwd,pubdate,ip,result) VALUES ('','$userid','$userpwd','$logintime','$loginip','0')");
		write_msg("登录失败，可能是您输入了错误的用户名或密码");
	}

}
?>