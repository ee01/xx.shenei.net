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

function flink_pr(){
	$flink_pr=array('0','1','2','3','3-10');
	return $flink_pr;
}

function flink_dayip(){
	$flink_dayip=array('1000以下','1000以上');
	return $flink_dayip;
}

function apply_flink_pr($pr=""){
	$flink_pr=flink_pr();
	foreach ($flink_pr as $k => $value){
		$str .='<input type="radio" name="pr" value="'.$value.'" style="border:0" class="li"';
		$str .= ($value == $pr) ? "checked " : '';
		$str .= '>'.$value;
	}
	return $str;
}

function apply_flink_dayip($dayip=""){
	$flink_dayip=flink_dayip();
	foreach ($flink_dayip as $k => $value){
		$str .='<input type="radio" name="dayip" value="'.$value.'" style="border:0" class="li"';
		$str .= ($value == $dayip) ? "checked " : '';
		$str .= '>'.$value;
	}
	return $str;
}
?>