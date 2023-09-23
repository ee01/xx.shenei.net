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
//您可以根据自己的需要修改以下设置
$mymps_mymps=array();

$mymps_mymps['cfg_if_tpledit'] = "0";
//是否开启模板风格在线编辑功能，0为关闭，1为开启，如非十分必要，建议关闭该功能

$mymps_mymps['cfg_record_save'] = "500";
/*
设置后台管理员操作记录至少保存多少条最新记录
默认500条，当少于500条的时候不允许删除记录
便于您查看后台操作记录
*/


$mymps_mymps['cfg_record_save'] = "500";
/*
设置后台管理员操作记录至少保存多少条最新记录
默认500条，当少于500条的时候不允许删除记录
便于您查看后台操作记录
*/

$mymps_mymps['ipdat_choose'] = "1";
/*
使用精简ip库还是纯真ip库，纯真ip库比较大，如果你需要使用比较精确的纯真IP库，请前往官方论坛下载，程序包中默认只含有精简ip库;

0为使用精简ip库，1为使用纯真ip库
纯真IP库相比精简IP库要精确，但程序加载时间可能会延长
*/


//以下设置请保持默认，一般情况下请不要修改
$mymps_mymps['cfg_focus_limit']['width']='370';
$mymps_mymps['cfg_focus_limit']['height']='170';
//首页焦点图尺寸

$mymps_mymps['cfg_memberlogo_limit']['width']='100';
$mymps_mymps['cfg_memberlogo_limit']['height']='100';
//会员头像尺寸

$mymps_mymps['cfg_information_limit']['width']='240';
$mymps_mymps['cfg_information_limit']['height']='180';
//会员头像尺寸

$mymps_mymps['cfg_session_dir'] = MYMPS_DATA."/sessions/";
//session保存路径，请不要修改此项
?>