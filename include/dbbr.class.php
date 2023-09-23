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
class dbbr{
	var $linkid;
	var $sqlid;
	var $record;
	function dbbr($host="",$username="",$password="",$database="")
	{
		if(!$this->linkid)  @$this->linkid = mysql_connect($host, $username, $password) or die("连接服务器失败.");
		@mysql_select_db($database,$this->linkid) or die("无法打开数据库");
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
		echo "Mysql查询错误<br>";
		echo "查询语句：".$sql."<br>";
		echo "错误信息：".$err;
	}
}

$d=new dbbr($mysqlhost,$mysqluser,$mysqlpwd,$mysqldb);
?>