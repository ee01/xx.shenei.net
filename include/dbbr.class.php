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
class dbbr{
	var $linkid;
	var $sqlid;
	var $record;
	function dbbr($host="",$username="",$password="",$database="")
	{
		if(!$this->linkid)  @$this->linkid = mysql_connect($host, $username, $password) or die("���ӷ�����ʧ��.");
		@mysql_select_db($database,$this->linkid) or die("�޷������ݿ�");
		mysql_query('set names gbk');
		return $this->linkid;
	}
	
	function query($sql)
	{
		if($this->sqlid=mysql_query($sql,$this->linkid)) return $this->sqlid;
		else 
		{
			$this->err_report($sql,mysql_error);
			return false;
		}
	}
	
	function nr($sql_id="")
	{
		if(!$sql_id) $sql_id=$this->sqlid;
		return mysql_num_rows($sql_id);
	}
	
	function nf($sql_id="")
	{
		if(!$sql_id) $sql_id=$this->sqlid;
		return mysql_num_fields($sql_id);
	}
	
	function nextrecord($sql_id="")
	{
		if(!$sql_id) $sql_id=$this->sqlid;
		if($this->record=mysql_fetch_array($sql_id))  return $this->record;
		else return false;
	}
	
	function f($name)
	{
		if($this->record[$name]) return $this->record[$name];
		else return false;
	}
	
	function close()
	{
		mysql_close($this->linkid);
	}
	
	function lock($tblname,$op="WRITE")
	{
		if(mysql_query("lock tables ".$tblname." ".$op)) return true; else return false;
	}
	
	function unlock()
	{if(mysql_query("unlock tables")) return true; else return false;}
	
	function ar() 
	{
		return @mysql_affected_rows($this->linkid);
	}
	
	function i_id() 
	{
		return mysql_insert_id();
	}
	
	function err_report($sql,$err)
	{
		echo "Mysql��ѯ����<br>";
		echo "��ѯ��䣺".$sql."<br>";
		echo "������Ϣ��".$err;
	}
}

$d=new dbbr($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);
?>