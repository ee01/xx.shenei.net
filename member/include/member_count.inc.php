<?php
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}

$element = array(

	'������Ϣ'=>array(
			'style'=>'info',
			'url'=>'info.php?part=all',
			'table'=>'information',
			'type'=>''
			),
			
	'��Ϣ����'=>array(
			'style'=>'com',
			'url'=>'info.php?part=all',
			'table'=>'info_comment',
			'type'=>''
			),
			
	'����Ϣ'=>array(
			'style'=>'pm',
			'url'=>'pm.php',
			'table'=>'member_pm',
			'type'=>'',
			'where'=>' AND if_read = 0'
			),
			
	'�˻����'=>array(
			'style'=>'incomes',
			'url'=>'bank.php',
			'table'=>'member',
			'type'=>'money'
			)
			
);
		
function member_get_count()
{
	global $element,$s_uid,$money_own;
	foreach ($element as $k =>$value){
		if(empty($value[type])){
			$and = $value[where] ? $value[where] : ''; 
			$mymps_member_count .= "<li class=\"".$value[style]."\"><a href=\"".$value[url]."\">".$k."(".mymps_count($value[table],"WHERE userid = '$s_uid'$and").")</a></li>";
		} else {
			$mymps_member_count .= "<li class=\"".$value[style]."\"><a href=\"".$value[url]."\">".$k."(".$money_own.")</a></li>";
		}
	}
	return $mymps_member_count;
}
?>