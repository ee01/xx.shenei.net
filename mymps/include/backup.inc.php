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

if(!$act){
	chk_admin_purview("purview_数据库备份");
	$d->query("SHOW TABLE STATUS");
	$here = "Mymps系统之备份数据库";
	include(mymps_tpl("mymps_backup"));
}else{
	if($weizhi=="localpc"&&$fenjuan=='yes'){
		$msgs[]="只有选择备份到服务器，才能使用分卷备份功能";
		show_msg($msgs,"record");
		exit();
	}
	if($fenjuan=="yes"&&!$filesize){
		$msgs[]="您选择了分卷备份功能，但未填写分卷文件大小";
		show_msg($msgs,"record"); 
		exit();
	}
	if($weizhi=="server"&&!writeable($backup_dir)){
		$msgs[]="备份文件存放目录".$mymps_global['cfg_backup_dir']."不可写，请修改目录属性";
		show_msg($msgs,"record");
		exit();
	}
	if($bfzl=="all_quanbubiao" || $bfzl == "my_quanbubiao"){
		//如果不分卷
		if(!$fenjuan){
			if(!$tables=$d->query("SHOW TABLE STATUS")){
				$msgs[]="读数据库结构错误"; 
				show_msg($msgs,"record");
				exit();
			}
			
			$sql = '';
			
			switch ($bfzl){
			
				case 'all_quanbubiao':
				
					while($d->nextrecord($tables)){
						$table=$d->f("Name");
						$sql.=make_header($table);
						$d->query("SELECT * FROM $table");
						$num_fields=$d->nf();
						while($d->nextrecord()){
							$sql .= make_record($table,$num_fields);
						}
					}				

				break;
				
				default:
				
					while($d->nextrecord($tables)){
						$table=$d->f("Name");
						if (substr($table, 0, strlen($db_mymps)) == $db_mymps) {
							$sql.=make_header($table);
							$d->query("SELECT * FROM $table");
							$num_fields=$d->nf();
							while($d->nextrecord()){
							
								if (substr($table, 0, strlen($db_mymps)) == $db_mymps) {
									$sql .= make_record($table,$num_fields);
								}
								
							}
						}
					}
					
				break;
					
			}
			
			$filename=date("YmdHi",time()).$random.".sql";
			
			if($weizhi=="localpc") {
				down_file($sql,$filename);
			}elseif($weizhi=="server"){
				if(write_file($sql,$backup_dir,$filename)) 
				$msgs[]="全部数据表数据备份完成,生成备份文件<br><br>$backup_dir$filename<br><br><a href=database.php?part=backup>点此返回数据库备份页面</a>";
				else $msgs[]="备份全部数据表失败";
				show_msg($msgs,"record");
				exit();
			}
		} else {//如果分卷
			if(!$filesize){
				$msgs[]="请填写备份文件分卷大小";
				show_msg($msgs,"record");
				exit();
			}
			if(!$tables=$d->query("SHOW TABLE STATUS")){
				$msgs[]="读数据库结构错误";
				show_msg($msgs,"record");
				exit();
			}
			$sql="";
			$p=1;
			$filename=date("YmdHi",time()).$random;
			
			switch ($bfzl){
			
				case 'all_quanbubiao':
				
					while($d->nextrecord($tables)){
					
						$table=$d->f("Name");

						$sql.=make_header($table);
						$d->query("SELECT * FROM $table");
						$num_fields=$d->nf();
						while($d->nextrecord()){

							$sql.=make_record($table,$num_fields);
							if(strlen($sql)>=$filesize*1000){
								$filename.=("_v".$p.".sql");
								if(write_file($sql,$backup_dir,$filename))
								$msgs[]="[卷-".$p."] 数据备份完成,生成备份文件<br><br>
					$backup_dir$filename";
								else $msgs[]="备份表 [".$_POST['tablename']."] 失败";
								$p++;
								$filename=date("YmdHi",time()).$random;
								$sql="";
							}

						}
						
					}
					
				break;
				
				default:
				
					while($d->nextrecord($tables)){
					
						$table=$d->f("Name");
						
						if (substr($table, 0, strlen($db_mymps)) == $db_mymps) {
						
							$sql.=make_header($table);
							$d->query("SELECT * FROM $table");
							$num_fields=$d->nf();
							while($d->nextrecord()){
								if (substr($table, 0, strlen($db_mymps)) == $db_mymps) {
									$sql.=make_record($table,$num_fields);
									if(strlen($sql)>=$filesize*1000){
										$filename.=("_v".$p.".sql");
										if(write_file($sql,$backup_dir,$filename))
										$msgs[]="[卷-".$p."] 数据备份完成,生成备份文件<br><br>
							$backup_dir$filename";
										else $msgs[]="备份表 [".$_POST['tablename']."] 失败";
										$p++;
										$filename=date("YmdHi",time()).$random;
										$sql="";
									}
								}
							}
						}
						
					}
					
				break;
					
			}
			
			
			if($sql!=""){
				$filename.=("_v".$p.".sql");		
				if(write_file($sql,$backup_dir,$filename))
				$msgs[]="[卷-".$p."] 数据备份完成,生成备份文件<br /><br />$backup_dir$filename<br /><br />
			<strong>全部数据备份完成！！</strong><br /><br /><a href=database.php?part=backup>点此返回</a><br /><br />";
			}
			show_msg($msgs,"record");
		}
	}elseif($bfzl=="danbiao"){
		if(!$_POST['tablename']){
			$msgs[]="请选择要备份的数据表";
			show_msg($msgs,"record");
			exit();
		}
		if(!$fenjuan){
			$sql=make_header($_POST['tablename']);
			$d->query("SELECT * FROM ".$_POST['tablename']);
			$num_fields=$d->nf();
			while($d->nextrecord()){
				$sql.=make_record($_POST['tablename'],$num_fields);
			}
			$filename=date("YmdHi",time()).$random."_".$_POST['tablename'].".sql";
			if($weizhi=="localpc"){
				down_file($sql,$filename);
			} elseif ($weizhi=="server"){
				if(write_file($sql,$backup_dir,$filename))
				$msgs[]="表 [".$_POST['tablename']."] 数据备份完成,生成备份文件<br>
				<br>$backup_dir$filename<br><br><a href=database.php?part=backup>点此返回数据库备份页面</a>";
				else
				$msgs[]="备份表 [".$_POST['tablename']."] 失败";
				show_msg($msgs,"record");
				exit();
			}
		}else{
			if(!$filesize){
				$msgs[]="请填写备份文件分卷大小"; 
				show_msg($msgs,"record");
				exit();
			}
			$sql=make_header($_POST['tablename']); 
			$p=1;
			$filename=date("YmdHi",time()).$random."_".$_POST['tablename'];
			$d->query("SELECT * FROM ".$_POST['tablename']);
			$num_fields=$d->nf();
			while ($d->nextrecord()){	
				$sql.=make_record($_POST['tablename'],$num_fields);
				if(strlen($sql)>=$filesize*1000){
					$filename.=("_v".$p.".sql");
					if(write_file($sql,$backup_dir,$filename))
					$msgs[]="表 -".$_POST['tablename']." [卷-".$p."] 数据备份完成,生成备份文件'$backup_dir$filename'";
					else 
					$msgs[]="备份表 -".$_POST['tablename']."- 失败";
					$p++;
					$filename=date("YmdHi",time()).$random."_".$_POST['tablename'];
					$sql="";
				}
			}
			if($sql!=""){
				$filename.=("_v".$p.".sql");		
				if(write_file($sql,$backup_dir,$filename))
				$msgs[]="表 -".$_POST['tablename']." [卷-".$p."] 数据备份完成,生成备份文件<br>
				<br>$backup_dir$filename<br /><strong>全部数据备份完成！！</strong><br /><br />";
			}
			show_msg($msgs,"record");
		}
	}
}
?>