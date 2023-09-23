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
define('MYMPS_INC'		, ereg_replace("[/\\]{1,}",'/',dirname(__FILE__) ) );
define('MYMPS_ROOT'		, ereg_replace("[/\\]{1,}",'/',substr(MYMPS_INC,0,-8) ) );
define('MYMPS_DATA'		, MYMPS_ROOT.'/dat');
define('MYMPS_MEMBER'	, MYMPS_ROOT.'/member');
define('MYMPS_UPLOAD'	, MYMPS_DATA.'/upload');
define('MYMPS_SMARTY'	, MYMPS_INC.'/smarty');
define('MYMPS_TPL'		, MYMPS_ROOT.'/template');
define('MYMPS_ADMIN'	, 'mymps');//后台管理目录，如果你修改了后台管理目录，请也修改此处
?>