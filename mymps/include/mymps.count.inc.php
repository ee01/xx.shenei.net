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
 * 联系方式：QQ:330647249 3037821 MSN:business@live.it
`*/
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

$ele = array('information'=>'分类信息','member'=>'会员','siteabout'=>'站务');

$element[information] = array(
	'信息'=>array(
			'table'=>'information',
			'url'=>'information.php'
			),
	'评论'=>array(
			'table'=>'info_comment',
			'url'=>'comment.php'
			),
	'举报'=>array(
			'table'=>'info_report',
			'url'=>'information.php?part=report'
			)
		);
		
$element[member] = array(
	'会员'=>array(
			'table'=>'member',
			'url'=>'member.php'
			)
		);
		
$element[siteabout] = array(
	'公告'=>array(
			'table'=>'announce',
			'url'=>'announce.php'
			),
	'帮助'=>array(
			'table'=>'faq',
			'url'=>'faq.php'
			),
	'链接'=>array(
			'table'=>'flink',
			'url'=>'friendlink.php'
			),
	'留言'=>array(
			'table'=>'guestbook',
			'url'=>'guestbook.php'
			),
		);
?>