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
//check the uploaded images #single
function check_upimage($file="filename")
{
	global $mymps_global;
	$size=$mymps_global[cfg_upimg_size]*1024;
	$upimg_allow = explode(',',$mymps_global[cfg_upimg_type]);
	if($_FILES[$file]['size']>$size){
		write_msg('�ϴ��ļ�ӦС��'.$mymps_global[cfg_upimg_size].'KB');
		exit();
	}
	
	if(!in_array(FileExt($_FILES[$file]['name']),$upimg_allow)){
		write_msg('ϵͳֻ�����ϴ�'.$mymps_global[cfg_upimg_type].'��ʽ��ͼƬ��');
		exit();
	}
	
	if(!preg_match('/^image\//i',$_FILES[$file]['type']))//����Ƿ�Ϊ��ͼƬ
	{
		write_msg ('�ܱ�Ǹ��ϵͳ�޷�ʶ�����ϴ����ļ��ĸ�ʽ���뻻һ��ͼƬ�ϴ���');
		exit();
	}
	return true;
}
//check the uploaded images #lots
function mymps_check_upimage($file="filename")
{
	if(is_array($_FILES)){
		for($i=0;$i<count($_FILES);$i++){
			if($_FILES[$file.$i]['name']){
				check_upimage($file.$i);
			}
		}
	}
}
//check if uploaded the images
function upload_img_num($file="filename")
{
	if(is_array($_FILES)){
		$num = 0;
		for($i=0;$i<count($_FILES);$i++){
			$num = ($_FILES[$file.$i]['error'] != 4) ? ($num + 1) : $num;
		}
		return $num;
	}
	else return 0;
}
//stared uploading the images
function start_upload($file_name,$destination_folder,$watermark,$limit_width=240,$limit_height=180,$edit_filename='',$edit_pre_filename='')
{
	global $mymps_global,$db,$db_mymps;
	if (!is_uploaded_file($_FILES[$file_name][tmp_name])){
         write_msg ("������ѡ����Ҫ�ϴ���ͼƬ!");
         exit();
    }
    $file = $_FILES[$file_name];
	createdir(MYMPS_UPLOAD.$destination_folder);
    $file_name=$file["tmp_name"];
    $pinfo=pathinfo($file["name"]);
    $ftype=$pinfo['extension'];
    $fname=$pinfo[basename];
	if($edit_filename==''){
		$destination_file = time().random().".".$ftype;
		$destination = MYMPS_UPLOAD.$destination_folder.$destination_file;
		$small_destination = MYMPS_UPLOAD.$destination_folder."pre_".$destination_file;
	}else{
		$destination = MYMPS_ROOT.$edit_filename;
		$small_destination = MYMPS_ROOT.$edit_pre_filename;
		@unlink($destination);
		@unlink($small_destination);//delete old
	}
	
	//the full path of the new images (small images
    if (file_exists($destination) && $overwrite != true){
        write_msg("ͬ��ͼƬ�Ѵ��ڣ�������ѡ����Ҫ�ϴ���ͼƬ��");
        exit();
    }
	
    if(!move_uploaded_file($file_name, $destination)){
        write_msg("ͼƬ�ϴ�ʧ�ܣ�������ѡ����Ҫ�ϴ���ͼƬ��");
        exit();
    }
	//watermark
	if($watermark == 1 && function_exists("gd_info")){
		$ttf = MYMPS_DATA."/ttf/english.ttf";
		$image_size  = getimagesize($destination);
		$image_width = $image_size[0];
		$image_height= $image_size[1];
		$image_ext   = $image_size[2];
		switch ($image_ext){
			case 1:
				$simage =imagecreatefromgif($destination);
			break;
			
			case 2:
				$simage =imagecreatefromjpeg($destination);
			break;
			
			case 3:
				$simage =imagecreatefrompng($destination);
			break;
			
			case 6:
				$simage =imagecreatefromwbmp($destination);
			break;
			
			default:
				write_msg("��֧�ֵ��ļ�����");
				exit();
			break;
		}
		
		$nimage=imagecreatetruecolor($image_width,$image_height);
		imagecopy($nimage,$simage,'','',0,0,$image_width,$image_height);
		switch($mymps_global[cfg_upimg_watermark_position]){
			case '1'://���½�
				$dst_x ='5';
				$dst_y =$image_height-5;
			break;
			//220,5
			case '2'://���½�
				$dst_x =$image_width-245;
				$dst_y =$image_height-5;
			break;
			
			case '3'://���Ͻ�
				$dst_x ='5';
				$dst_y ='15';
			break;
			
			case '4'://���Ͻ�
				$dst_x =$image_width-245;
				$dst_y ="15";
			break;
			
			case '5'://����
				$dst_x =$image_width*0.5;
				$dst_y =$image_height*0.5;
			break;
		}
		
		switch($mymps_global[cfg_upimg_watermark_color]){
			case '1':
				$color=imagecolorallocate($nimage,255,255,255);//white
			break;
			
			case '2':
				$color=imagecolorallocate($nimage,255,0,0);//red
			break;
			
			case '3':
				$color=imagecolorallocate($nimage,0,0,0);//black
			break;
		}
		
		if(function_exists("imagettftext")){
			//���֧��freetype
			imagettftext($nimage,14,0,$dst_x,$dst_y,$color,$ttf,$mymps_global[cfg_upimg_watermark_value]);
		}else{
			//�����֧��freetype
			imagestring($image,5,0,5,$mymps_global[cfg_upimg_watermark_value],$color);
		}
		
      	switch ($image_ext){
            case 1:
            //imagegif($nimage, $destination);
         		imagejpeg($nimage, $destination);
            break;
			
            case 2:
          		imagejpeg($nimage, $destination);
            break;
			
            case 3:
           		imagepng($nimage, $destination);
            break;
			
            case 6:
           		imagewbmp($nimage, $destination);
            //imagejpeg($nimage, $destination);
            break;
        }
		imagedestroy($nimage);
	}else{
		$image_size  = getimagesize($destination);
		$image_width = $image_size[0];
		$image_height= $image_size[1];
		$image_ext   = $image_size[2];
		switch ($image_ext){
			case 1:
				$simage =imagecreatefromgif($destination);
			break;
			
			case 2:
				$simage =imagecreatefromjpeg($destination);
			break;
			
			case 3:
				$simage =imagecreatefrompng($destination);
			break;
			
			case 6:
				$simage =imagecreatefromwbmp($destination);
			break;
			
			default:
				write_msg("��֧�ֵ��ļ�����");
				exit();
			break;
		}
	}
	//small images
	if($image_width > $limit_width){
		if(($limit_width/$image_width)*$image_height>$limit_height){
			$proportion = $limit_height / $image_height;
		}else{
			$proportion = $limit_width / $image_width;
		}
	}else{
		if($image_height <= $limit_height){
			$proportion = 1;
		}else{
			$proportion = $limit_height / $image_height;
		}
	}
	if($proportion != 1){
		//imagecopyresampled
		if(function_exists("imagecopyresampled"))
		{
			$new = imagecreatetruecolor($image_width*$proportion,$image_height*$proportion);
			$white=imagecolorallocate($new,255,255,255);
			imagefill($new,0,0,$white);
			imagecopyresampled($new,$simage,0,0,0,0,$image_width*$proportion,$image_height*$proportion,$image_width,$image_height);
		}else{
			$new = imagecreate($image_width*$proportion,$image_height*$proportion);
			$white=imagecolorallocate($new,255,255,255);
			imagefill($new,0,0,$white);
			imagecopyresized($new,$simage,0,0,0,0,$image_width*$proportion,$image_height*$proportion,$image_width,$image_height);
		}
		switch ($image_ext){
			case 1:
			//imagegif($nimage, $destination);
				imagejpeg($new, $small_destination);
			break;
			
			case 2:
				imagejpeg($new, $small_destination);
			break;
			
			case 3:
				imagepng($new, $small_destination);
			break;
			
			case 6:
				imagewbmp($new, $small_destination);
			//imagejpeg($nimage, $destination);
			break;
		}
		imagedestroy($simage);
	}
	$mymps_image = array();
	$mymps_image[0]=$edit_filename?$edit_filename:"/dat/upload".$destination_folder.$destination_file;
	$mymps_image[1]=($proportion != 1)?$edit_pre_filename?$edit_pre_filename:"/dat/upload".$destination_folder."pre_".$destination_file:$mymps_image[0];
    return $mymps_image;
}
?>