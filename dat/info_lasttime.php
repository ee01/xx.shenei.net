<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对代码作修改后以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:3037821 MSN:business@live.it
`*/
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
/*信息发布的持续时间下拉框选项*/
//注意单位为天，一周则为7，一个月则为30，以此类推
//一般情况下，请保保持默认，勿修改
$info_lasttime = array();
$info_lasttime[7] 	= '一周';
$info_lasttime[30] 	= '一个月';
$info_lasttime[60] 	= '二个月';
$info_lasttime[365] = '一年';


//以下设置为信息发布或修改时是否允许长期有效，你可以根据自己的情况删除或保留，但请不要修改
//$info_lasttime[0] = '长期有效';

//以下不要修改
function GetInfoLastTime($lasttime='',$formname='endtime'){
	global $info_lasttime;
	$info_lasttime_form = "<select name='$formname' id='$formname'>";
	foreach($info_lasttime as $k=>$v){
	 	if($k==$lasttime&&$k!='') $info_lasttime_form .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $info_lasttime_form .= "<option value='$k'>$v</option>\r\n";
	}
	$info_lasttime_form .= "</select>\r\n";
	return $info_lasttime_form;
}

?>