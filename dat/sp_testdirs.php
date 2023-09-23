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
//check ifreadable ifwriteable of the file
//需检测读写权限的文件
//一般情况下，请保保持默认，勿修改
$sp_testdirs = array(
	'/dat',
	'/dat/cache',
	'/dat/sessions',
	'/dat/config.php',
	'/dat/config.cache.php',
	'/dat/category.inc.php',
	'/dat/announce.inc.php',
	'/dat/area.inc.php',
	'/dat/config.db.php',
	'/dat/backup',
	'/dat/upload',
	'/dat/upload/focus',
	'/dat/upload/information',
	'/dat/upload/member_logo',
	'/dat/mympstpl'
);
?>