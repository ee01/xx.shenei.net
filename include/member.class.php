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
class mymps_member_log
{
	var $db_mixcode;

    function __construct($db_mixcode)
    {
		$this->db_mixcode=$db_mixcode;
    }

    function mymps_member_log($db_mixcode)
    {
		$this->__construct($db_mixcode);
    }

	function GetValue($s_uid)
	{
		return $this->ValuePre().'_'.$s_uid;
	}
	
	function ValuePre()
	{
		global $softname;
		$softname = $softname ? $softname : 'mymps';
		return $softname.substr(md5($this->db_mixcode),0,10);
	}
	
	function PutLogin($s_uid,$memory)
	{
		if ($memory == "on")
		{
			setcookie("s_uid", $this->GetValue($s_uid) , time()+3600*24*30 , "/");
		}
		else
		{
			setcookie("s_uid", $this->GetValue($s_uid) ,"0" , "/");
		}
	}

	function in($s_uid,$memory="",$url="",$type="")
	{
		global $mymps_global;
		if(!empty($s_uid))
		{
			$this->PutLogin($s_uid,$memory);
			if(empty($url)&&empty($type))
			{
				echo mymps_goto($mymps_global[SiteUrl]."/member/index.php");
			}
			elseif(!empty($url)&&empty($type))
			{
				echo mymps_goto($url);
			}
		}
	}

	function out($url)
	{
		global $mymps_global;
		setcookie("s_uid", "",0,"/");
		if($mymps_global['cfg_join_othersys'] == 'ucenter'){
			include MYMPS_ROOT.'/uc_client/client.php';
			setcookie('Example_auth', '', -86400);
			//生成同步退出的代码
			$ucsynlogout = ($mymps_global['cfg_if_member_log_in'] == 1) ? uc_user_synlogout() : '';
		}
		
		if (empty($url)){
			write_msg("","login.php");
		} else {
			write_msg("",$url);
		}
	}

	function chk_in()
	{
		global $s_uid;
		$uid = explode("_",$_COOKIE['s_uid']);
		if(empty($_COOKIE['s_uid'])||$this->ValuePre()!=$uid[0])
		{
			setcookie("s_uid", "",0,"/");
			return false;
		}
		else
		{
			$s_uid = end($uid);
			return true;
		}
	}

	function get_info($type="")
	{
		global $s_uid,$db,$db_mymps;
		$s_uid=$_COOKIE['s_uid'];
		$s_uid = end(explode("_",$s_uid));
		if(!empty($type)&&!empty($s_uid)){
			$k = $db->getRow("SELECT $type FROM {$db_mymps}member WHERE userid = '$s_uid'");
			return $k[$type];
		}
	}
}
$member_log = new mymps_member_log($db_mixcode);
?>