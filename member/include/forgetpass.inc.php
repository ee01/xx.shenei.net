<?php

$smarty -> cache_lifetime    = $mymps_cache['forgetpass']['time'];
$smarty -> caching           = $mymps_cache['forgetpass']['open'];

if ($action == "doreset") {
	
	if (empty($userpwd) ||empty($reuserpwd)){write_msg("密码输入不能为空");exit;}
	
	if ($userpwd != $reuserpwd){write_msg("两次密码输入不相同");exit;}
	
	if( strlen($userpwd) < 5 ){write_msg("你的新密码过短(不能少于5个字符)！");exit;}
	
	if($mymps_imgcode[forgetpass][open] == 1){
		mymps_chk_randcode();
	}
	
	$rs = CheckUserID($userid,'用户名');
	
	if($rs != 'ok'){
		write_msg($rs);
		exit;
	}
	
	if(!($db->getOne("SELECT id FROM `{$db_mymps}member` WHERE userid LIKE '$userid' "))){
		write_msg("你指定的用户名 {$userid} 不存在！");
		exit;
	}
	
	$get = $db -> getOne("SELECT count(id) FROM `{$db_mymps}member` WHERE userid = '$userid' AND safequestion = '$safequestion' AND safeanswer = '$safeanswer'");
	
	if($get){
	
		if($mymps_global['cfg_join_othersys'] == 'ucenter'){
					
			include MYMPS_ROOT.'/uc_client/client.php';
			
			$result =  uc_user_edit($userid, $userpwd, $userpwd, $email, 1);
							
			if($result == 1 || $result == 0) {
				$result = 1;
			} elseif ($result == -4) {
				write_msg('未定义错误：EMAIL格式有误！');
				exit;
			}  elseif ($result == -5) {
				write_msg('未定义错误：该email不允许注册！');
				exit;
			} elseif ($result == -6) {
				write_msg('未定义错误：该email已经被注册！');
				exit;
			} elseif ($result == -8) {
				write_msg('未定义错误：受保护的用户，您无权修改！');
				exit;
			} elseif ($result == -1) {
				write_msg('未定义错误：旧密码不正确！');
				exit;
			} else {
				write_msg('未定义错误，密码修改失败！');
			}
			
		}
	
		$userpwd=md5($userpwd);
		$res = $db->query("UPDATE `{$db_mymps}member` SET userpwd = '$userpwd' WHERE userid = '$userid'");
		write_msg("密码重设成功，请记住新密码","login.php");
		
	}else{
		write_msg("您的密码提示问题或答案输入错误!");
	}
	
}else {

	if(!$member_log->chk_in()){
	
		$smarty->cache_lifetime    = $mymps_cache['log']['time'];
		$smarty->caching           = $mymps_cache['log']['open'];
		
		if (!$smarty->is_cached(MYMPS_TPL.'/log/forgetpass.html',$part)){
		
			require(MYMPS_DATA.'/safequestions.php');
			$safequestion = $safequestions[$safequestion];
			$nav_bar = '<a href=../>网站首页</a> &raquo; 找回密码';
			mymps_global_assign();
			$smarty -> assign("nav_bar",$nav_bar);
			$smarty -> assign("safequestion",GetSafequestion());
			$smarty -> assign("mymps_imgcode",$mymps_imgcode[forgetpass]);
			
		}
		
		$smarty -> display(mymps_tpl("forgetpass","smarty"),$part);
		
	}else{
	
		write_msg('','update.php?part=password');
		
	}
	
}

?>