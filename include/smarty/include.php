<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。否则我们将追究您的法律责任!!
 * ============================================================================
 * 软件作者: 彭介平（村长）
 * 联系方式：QQ:330647249 3037821 MSN:business@live.it
`*/
include_once(MYMPS_SMARTY.'/class/Smarty.class.php');
$smarty = new smarty();
$smarty->template_dir = MYMPS_ROOT.'/template/'.$smartytpl;
$smarty->compile_dir  = MYMPS_DATA.'/mympstpl/';
$smarty->config_dir   = MYMPS_ROOT.'/config/';
$smarty->cache_dir    = MYMPS_DATA.'/cache/';
$smarty->compile_check = true;
$smarty->left_delimiter = "{";
$smarty->right_delimiter = "}";
?>