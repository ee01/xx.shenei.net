<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
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
	if (!CheckLengthBetween($C_passwd, 4, 20)) return false; //��ȼ�� 
	if (!ereg("^[_a-zA-Z0-9]*$", $C_passwd)) return false; //�����ַ���� 
	return true; 
}
//check if is telephone number
function is_tel($C_telephone) 
{ 
if (!ereg("^[+]?[0-9]+([xX-][0-9]+)*$", $C_telephone)) return false; 
return true; 
} 
//check the memberID
function CheckUserID($uid,$msgtitle='�û���')
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
					return $msgtitle.'���ܺ������룬���������Ӣ����ĸ��������ϣ�';
				}
			}
	}
	return 'ok';
}
?>