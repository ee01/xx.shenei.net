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

function flink_pr(){
	$flink_pr=array('0','1','2','3','3-10');
	return $flink_pr;
}

function flink_dayip(){
	$flink_dayip=array('1000����','1000����');
	return $flink_dayip;
}

function apply_flink_pr($pr=""){
	$flink_pr=flink_pr();
	foreach ($flink_pr as $k => $value){
		$str .='<input type="radio" name="pr" value="'.$value.'" style="border:0" class="li"';
		$str .= ($value == $pr) ? "checked " : '';
		$str .= '>'.$value;
	}
	return $str;
}

function apply_flink_dayip($dayip=""){
	$flink_dayip=flink_dayip();
	foreach ($flink_dayip as $k => $value){
		$str .='<input type="radio" name="dayip" value="'.$value.'" style="border:0" class="li"';
		$str .= ($value == $dayip) ? "checked " : '';
		$str .= '>'.$value;
	}
	return $str;
}
?>