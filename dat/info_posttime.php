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
$info_posttime = array();
/*信息检索的发布时间下拉框选项*/
//注意单位为天，一周则为7，一个月则为30，以此类推
$info_posttime[0] = '所有';
$info_posttime[3] = '3天内';
$info_posttime[7] = '一周内';
$info_posttime[30] = '一个月以内';
$info_posttime[90] = '三个月以内';


//以下不要修改
function GetInfoPostTime($posttime='',$formname='posttime'){
	global $info_posttime;
	$info_posttime_form = "<select name='$formname' id='$formname'>";
	foreach($info_posttime as $k=>$v){
	 	if($k==$posttime&&$k!='') $info_posttime_form .= "<option value='$k' selected style='background-color:#6EB00C;color:white'>$v</option>\r\n";
	 	else $info_posttime_form .= "<option value='$k'>$v</option>\r\n";
	}
	$info_posttime_form .= "</select>\r\n";
	return $info_posttime_form;
}

?>