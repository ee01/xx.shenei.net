<?php
/*
 * this is not a freeware, use is subject to license terms
 * ============================================================================
 * ��Ȩ���� mymps�з��Ŷӣ���������Ȩ����
 * ��վ��ַ: http://www.mymps.com.cn��
 * ������̳��http://bbs.mymps.com.cn��
 * ----------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�á�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ============================================================================
 * �������: ���ƽ���峤��Author:steven
 * ��ϵ��ʽ��chinawebmaster@yeah.net MSN:business@live.it
`*/

if(!$act){
	chk_admin_purview("purview_���ݿⱸ��");
	$d->query("SHOW TABLE STATUS");
	$here = "Mympsϵͳ֮�������ݿ�";
	include(mymps_tpl("mymps_backup"));
}else{
	if($weizhi=="localpc"&&$fenjuan=='yes'){
		$msgs[]="ֻ��ѡ�񱸷ݵ�������������ʹ�÷־��ݹ���";
		show_msg($msgs,"record");
		exit();
	}
	if($fenjuan=="yes"&&!$filesize){
		$msgs[]="��ѡ���˷־��ݹ��ܣ���δ��д�־��ļ���С";
		show_msg($msgs,"record"); 
		exit();
	}
	if($weizhi=="server"&&!writeable($backup_dir)){
		$msgs[]="�����ļ����Ŀ¼".$mymps_global['cfg_backup_dir']."����д�����޸�Ŀ¼����";
		show_msg($msgs,"record");
		exit();
	}
	if($bfzl=="all_quanbubiao" || $bfzl == "my_quanbubiao"){
		//������־�
		if(!$fenjuan){
			if(!$tables=$d->query("SHOW TABLE STATUS")){
				$msgs[]="�����ݿ�ṹ����"; 
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
				$msgs[]="ȫ�����ݱ����ݱ������,���ɱ����ļ�<br><br>$backup_dir$filename<br><br><a href=database.php?part=backup>��˷������ݿⱸ��ҳ��</a>";
				else $msgs[]="����ȫ�����ݱ�ʧ��";
				show_msg($msgs,"record");
				exit();
			}
		} else {//����־�
			if(!$filesize){
				$msgs[]="����д�����ļ��־��С";
				show_msg($msgs,"record");
				exit();
			}
			if(!$tables=$d->query("SHOW TABLE STATUS")){
				$msgs[]="�����ݿ�ṹ����";
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
								$msgs[]="[��-".$p."] ���ݱ������,���ɱ����ļ�<br><br>
					$backup_dir$filename";
								else $msgs[]="���ݱ� [".$_POST['tablename']."] ʧ��";
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
										$msgs[]="[��-".$p."] ���ݱ������,���ɱ����ļ�<br><br>
							$backup_dir$filename";
										else $msgs[]="���ݱ� [".$_POST['tablename']."] ʧ��";
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
				$msgs[]="[��-".$p."] ���ݱ������,���ɱ����ļ�<br /><br />$backup_dir$filename<br /><br />
			<strong>ȫ�����ݱ�����ɣ���</strong><br /><br /><a href=database.php?part=backup>��˷���</a><br /><br />";
			}
			show_msg($msgs,"record");
		}
	}elseif($bfzl=="danbiao"){
		if(!$_POST['tablename']){
			$msgs[]="��ѡ��Ҫ���ݵ����ݱ�";
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
				$msgs[]="�� [".$_POST['tablename']."] ���ݱ������,���ɱ����ļ�<br>
				<br>$backup_dir$filename<br><br><a href=database.php?part=backup>��˷������ݿⱸ��ҳ��</a>";
				else
				$msgs[]="���ݱ� [".$_POST['tablename']."] ʧ��";
				show_msg($msgs,"record");
				exit();
			}
		}else{
			if(!$filesize){
				$msgs[]="����д�����ļ��־��С"; 
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
					$msgs[]="�� -".$_POST['tablename']." [��-".$p."] ���ݱ������,���ɱ����ļ�'$backup_dir$filename'";
					else 
					$msgs[]="���ݱ� -".$_POST['tablename']."- ʧ��";
					$p++;
					$filename=date("YmdHi",time()).$random."_".$_POST['tablename'];
					$sql="";
				}
			}
			if($sql!=""){
				$filename.=("_v".$p.".sql");		
				if(write_file($sql,$backup_dir,$filename))
				$msgs[]="�� -".$_POST['tablename']." [��-".$p."] ���ݱ������,���ɱ����ļ�<br>
				<br>$backup_dir$filename<br /><strong>ȫ�����ݱ�����ɣ���</strong><br /><br />";
			}
			show_msg($msgs,"record");
		}
	}
}
?>