<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * 版权所有 mymps研发团队，保留所有权利。
 * 网站地址: http://www.mymps.com.cn；
 * 交流论坛：http://bbs.mymps.com.cn；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用。
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * 软件作者: 彭介平（村长）Author:steven
 * 联系方式：chinawebmaster@yeah.net MSN:business@live.it
`*/
if (!defined('IN_MYMPS')){
    die('FORBIDDEN');
}

$gd_info 		= gd_info();
$gd_version 	= is_array($gd_info) ? $gd_info['GD Version'] : '<font color=red>不支持GD库</font>';
$cfg_if_tpledit = ($mymps_mymps[cfg_if_tpledit]==0)?"<font color=green>关闭</font>":"<font color=red>开启</font>";
$if_del_install = (!is_file(MYMPS_ROOT."/install/index.php")) ? "<font color=green>已删除</font>":"<font color=red>未删除</font>";

$welcome = array(
	'快捷操作'=>'
		<div class="mainnav">
		<ul>
		<li><a href="'.$mymps_global[SiteUrl].'" target="_blank"><img border="0" src="images/default/home.gif" />网站首页</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'member.php\'"><img border="0" src="images/default/user.png" alt="审核注册" />审核注册</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'announce.php?part=add\'"><img border="0" src="images/default/tpc.png" alt="审核主题" />发布公告</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'information.php\'"><img border="0" src="images/default/post.png"/>分类信息</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'friendlink.php\'"><img border="0" src="images/default/share.png" />审核连接</a></li>
		<li><a href="#" onclick="parent.framRight.location=\'guestbook.php\'"><img border="0" src="images/default/report.png"/>网站留言</a></li>
		</ul>
		</div>
			',
	'安全建议'=>'
		<span>在线编辑模板功能</span> 当前：'.$cfg_if_tpledit.'。建议您只有在十分必要的时候才开启它。请修改 /dat/config.inc.php 关闭此功能<br />
<span>Mymps install目录</span> 当前：'.$if_del_install.'。为防止站外人员利用，建议您安装完成后，删除该目录
			',
	'系统说明'=>'
		<span>系统整合功能</span> mymps当前支持整合UCENTER，整合接口以及整合教程请前往官方论坛下载<br />
<span>精确（纯真）IP库</span> mymps默认使用精简IP库，并且程序包中不含有纯真数据库，如果你想使用更加精确IP数据，请前往官方论坛下载<br /><span>伪静态规则</span> 1.5正式版之后将不再与程序包一同下载，下载伪静态规则以及使用教程请前往官方论坛<br />
<span>捐助蚂蚁(mymps)</span>如果你觉得本软件好用或向为蚂蚁团队研发出一份力，<a href=http://bbs.mymps.com.cn/thread-536-1-1.html target=_blank>请捐助本系统&raquo;</a>
			',
	'服务器相关'=>'
		<span>服务器环境:</span>'.$_SERVER['SERVER_SOFTWARE'].'<br />
		<span>服务器系统:</span>'.PHP_OS.'<br />
		<span>当前时间:</span>'.GetTime(time())." ".date("星期N",time()).'<br />
		<span>PHP程式版本:</span>'.PHP_VERSION.'<br />
		<span>MYSQL版本:</span>'.$db->version().'<br />
		<span>mymps所在目录: </span>'.MYMPS_ROOT.'<br />
		<span>使用域名: </span>'.$_SERVER["SERVER_NAME"].'<br />
		<span>脚本超时时间：</span>'.ini_get('max_execution_time').'<br />
		<span>附件上传上限</span>'.ini_get('upload_max_filesize').'<br />
		<span>GD库版本</span>'.$gd_version.'<br />
		<span>检测文件读写权限</span><a href=\'javascript:setbg("检测文件读写权限",330,380,"../public/box.php?part=sp_testdirs")\' class="icon_open" id="spanmymsg" >点此检测</a>
			',
	'研发团队'=>'
		<span>版权所有：</span> mymps研发团队 Mymps DevTeam Corporation<br />
		<span>总策划兼项目经理：</span> 彭介平 (村长) <br />
		<span>mymps版本：</span> '.MPS_VERSION.' [<a href="http://bbs.mymps.com.cn/forum-6-1.html" target=_blank>查看最新版本</a>]<br />
		<span>最后更新：</span> '.MPS_RELEASE.' <br />
		<span>官方网站：</span> <a href="http://www.mymps.com.cn" target="_blank">http://www.mymps.com.cn</a> <br />
		<span>官方论坛：</span> <a href="http://bbs.mymps.com.cn" target="_blank">http://bbs.mymps.com.cn</a>
					'
		);
?>