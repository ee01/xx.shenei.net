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
//check if is qq
function is_qq($qq)
{
	if(ereg("^[1-9][0-9]{4,}$",$qq)) 
	{
		return true;
	}
	else 
	{
		return false;
	}
}
//check if is email address 
function is_email($C_mailaddr)
{ 
	if (!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*$",$C_mailaddr)) 
	{ 
		return false; 
	} 
	return true; 
}
//check if is www address
function is_www($C_weburl)
{ 
	if (!ereg("^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $C_weburl)) 
	{ 
		return false; 
	} 
	return true; 
} 
//check if is password
function is_pwd($C_passwd) 
{ 
	if (!CheckLengthBetween($C_passwd, 4, 20)) return false; //宽度检测 
	if (!ereg("^[_a-zA-Z0-9]*$", $C_passwd)) return false; //特殊字符检测 
	return true; 
}
//check if is telephone number
function is_tel($C_telephone) 
{ 
if (!ereg("^[+]?[0-9]+([xX-][0-9]+)*$", $C_telephone)) return false; 
return true; 
} 
//check the memberID
function CheckUserID($uid,$msgtitle='用户名')
{
	for($i=0;isset($uid[$i]);$i++)
	{
			if(ord($ck_uid[$i]) > 0x80)
			{
				if(isset($uid[$i+1]) && ord($uid[$i+1])>0x40)
				{
					$i++;
				}
				else
				{
					return $msgtitle.'可能含有乱码，建议你改用英文字母和数字组合！';
				}
			}
	}
	return 'ok';
}
?>