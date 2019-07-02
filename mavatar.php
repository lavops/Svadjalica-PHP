<?php

require_once('config.php');
require_once('incl/main.php');

neutral_dbconnect(); $settings=get_settings();

if(isset($_FILES['avupload']) && isset($_FILES['avupload']['size']) && isset($_POST['avatar']) && isset($_POST['stoken'])){

$stoken=explode('z',$_POST['stoken']); $id=(int)$stoken[0];
if(!isset($stoken[1]) || hash('sha256',$stoken[0].$settings['random_salt']) != $stoken[1]){die();}

$avatar2db=''; $motto2db=''; $image_pro=0;

$avatar_filename='attachments/'.substr($stoken[1],0,20);

if(move_uploaded_file($_FILES['avupload']['tmp_name'],$avatar_filename)){
chmod("$avatar_filename",0666); $image_pro=1;}

$maxsize=(int)$settings['avatar_msize'];

if($image_pro==1 && filesize($avatar_filename)<$maxsize){$image_pro=2;}

if($image_pro==2){

$smime=0;

if(function_exists('finfo_open') && function_exists('finfo_file')){
$finfo=finfo_open(FILEINFO_MIME_TYPE);$smime=finfo_file($finfo,$avatar_filename);}
elseif(function_exists('mime_content_type')){$smime=@mime_content_type($avatar_filename);}

$ext=explode('/',$smime);

if(isset($ext[1]) && (strtolower($ext[1])=='jpg' || strtolower($ext[1])=='jpeg' || strtolower($ext[1])=='png' || strtolower($ext[1])=='gif')){
@rename($avatar_filename,$avatar_filename.'.'.$ext[1]);
$avatar_filename=$avatar_filename.'.'.$ext[1]; $image_pro=3;}}

if($image_pro<3){@unlink($avatar_filename);}else{$avatar2db=$avatar_filename;}

if(strlen($_POST['avatar'])>5){$avatar2db=neutral_escape($_POST['avatar'],50,'str');
if(!file_exists($avatar2db)){$avatar2db='';}}

if(isset($_POST['motto']) && strlen(trim($_POST['motto']))>0){$motto2db=neutral_escape($_POST['motto'],64,'str');}

neutral_query('DELETE FROM '.$dbss['sprf'].'_uxtra WHERE id='.$id);
neutral_query('INSERT INTO '.$dbss['sprf']."_uxtra VALUES($id,'$avatar2db','$motto2db')");

redirect('blabax.php');die(); }


// ---------------

if(isset($_GET['list'])){
$list_pics='';

if(strlen($settings['avt_cache'])<1){$avt_set=array();} else{$avt_set=unserialize(base64_decode($settings['avt_cache']));}

shuffle($avt_set);

for($i=0;$i<count($avt_set);$i++){
$pic='<img src="avatars/'.$avt_set[$i].'" alt="" class="avatar_list" id="av'.$avt_set[$i].'" onclick="de(\'avupload\').value=\'\';de(\'avatar\').value=\'avatars/'.$avt_set[$i].'\';de(\'my_avatar_pic\').src=\'avatars/'.$avt_set[$i].'\';shoop(this,1,100);pan.scrollTop=0" /> ';
$list_pics.=$pic;
}
print $list_pics;}

?>