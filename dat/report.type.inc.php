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
$report_type = array();
$report_type[1] = '违法信息';
$report_type[2] = '分类错误';
$report_type[3] = '虚假信息';

$report_type[0] = '其它原因';

function get_report_type()
{
	global $report_type;
	$mymps .="<select name='report_type'>";
	foreach($report_type as $k => $value)
	{
		$mymps .= "<option value=\"".$k."\">".$value."</option>";
	}
	$mymps .="</select>";
	return $mymps;
}
?>