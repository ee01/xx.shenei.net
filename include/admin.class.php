<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ��������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * �������Դ������޸ĺ����κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * ��������: ����ƽ���峤��
 * ��ϵ��ʽ��QQ:3037821 MSN:business@live.it
`*/
if (!defined('IN_MYMPS'))
{
    die('FORBIDDEN');
}
class mymps_admin_log
{
	var $db_mixcode;
	//php5
    function __construct($db_mixcode)
    {
		$this->db_mixcode=$db_mixcode;
    }
	//php4
    function mymps_member_log($db_mixcode)
    {
		$this->__construct($db_mixcode);
    }
	
	function GetValue($admin)
	{
		return $this->ValuePre().'_'.$admin;
	}
	
	function ValuePre()
	{
		global $softname;
		$softname = $softname ? $softname : 'mymps';
		return $softname.substr(md5($this->db_mixcode),0,10);
	}
	
	function PutLogin($admin_id,$admin_name)
	{
		session_start();
		//put sessions
		$_SESSION['admin_id']  = $admin_id;
		$_SESSION['admin_name'] = $admin_name;
		//put cookies
		/*
		setcookie("admin_id", $this->GetValue($admin_id) ,"0" , "/");
		setcookie("admin_name", $this->GetValue($admin_name) ,"0" , "/");
		*/
	}
	
	/*admin_login*/
	function mymps_admin_login($admin_id,$admin_name)
	{
		global $admin_id,$admin_name;
		if(!empty($admin_id)&&!empty($admin_name)){
			$this->PutLogin($admin_id,$admin_name);
		}
	}
	
	/*admin_out*/
	function mymps_admin_logout()
	{
		//session protected
		session_start();
		session_destroy();
		//cookies protected
		/*
		setcookie("admin_id", "",0,"/");
		setcookie("admin_name", "",0,"/");
		*/
	}
	
	/*chk admin login and get the info of admin*/
	function mymps_admin_chk_getinfo()
	{
		session_start();
		global $admin_id,$admin_name,$url;
		/*$id = explode("_",$_COOKIE['admin_id']);
		$name = explode("_",$_COOKIE['admin_name']);
		$id_pre = $id[0];
		$name_pre = $name[0];
		*/
		if(empty($_SESSION['admin_name'])||empty($_SESSION['admin_id']))
		{
			$this -> mymps_admin_logout();
			return false;
		}
		else
		{
			$admin_id 	= $_SESSION['admin_id'];
			$admin_name = $_SESSION['admin_name'];
			return true;
		}
	}
}
$mymps_admin = new mymps_admin_log($db_mixcode);
?>