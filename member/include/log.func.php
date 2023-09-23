<?php

function member_reg($userid,$userpwd,$email='',$safequestion='',$safeanswer=''){

	global $mymps_global,$db,$db_mymps,$member_log;
	
	$safeanswer = ($safeanswer == 0) ? '' : $safeanswer;
	$row 		= $db->getRow("SELECT money_own FROM `{$db_mymps}member_level` WHERE id = 1"); 
	$money_own	= $row[money_own];
	$now 		= time();
	$face 		= "/images/nophoto.jpg";
	
	$sql 		= "INSERT INTO `{$db_mymps}member`(id,userid,userpwd,logo,prelogo,email,safequestion,safeanswer,levelid,joinip,loginip,jointime,logintime,money_own) VALUES ('','$userid','$userpwd','$face','$face','$email','$safequestion','$safeanswer','1','".GetIP()."','".GetIP()."','$now','$now','$money_own')";
	
	if(!$mymps = $db -> query($sql)){
		return false;
	}else{
		return true;
	}
}

?>