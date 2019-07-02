<?php

require_once('version.php');

function abc123($n,$s){
$n=trim($n);
$n=preg_replace('/[^\p{L}\p{N} -]/u',$s,$n);
$n=preg_replace('/([\s])\1+/',' ',$n);
return $n;}

// ------------------

function redirect($u){
header('location:'.$u);}

// ------------------

function makeclr($x){        
$r_rgb=hexdec(substr($x,0,2));
$g_rgb=hexdec(substr($x,2,2));
$b_rgb=hexdec(substr($x,4,2));
$yiq=(($r_rgb*299)+($g_rgb * 587)+($b_rgb * 114))/1000;
if($yiq>=128){$y='000';}else{$y='fff';}
return $y;}

// ------------------

function length($x){
if(function_exists('mb_strlen')){
$y=mb_strlen(trim($x));}
else{$y=strlen(trim($x));}
return $y;}

// ------------------

function process_error($s){
global $error_log;
if(is_writeable($error_log)){
$s="\r\n".date('Y-m-d H:i:s').' '.$s;
$fd=fopen($error_log,"a");
$fout=fwrite($fd,$s);fclose($fd);}
die('SQL error... Please check your error log file for details...');}

// ------------------

function rand_str($l){
$l=(int)$l;if($l<5){$l=5;}
$str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$len = strlen($str); $randstr = '';
for ($i = 0; $i < $l; $i++) {$randstr.=$str[rand(0,$len-1)];}
return $randstr;}

// ------------------

function get_settings(){

global $dbss;
$settings=array(); 

$query='SELECT * FROM '.$dbss['prfx'].'_settings';
$result=neutral_query($query);

while($row=neutral_fetch_array($result)){$settings[$row['id']]=$row['value'];}

return $settings;}

// ------------------

function get_options(){

global $settings, $xcookie_options;
$options=array();

if(isset($_COOKIE[$xcookie_options[0]])){$options=explode('z',$_COOKIE[$xcookie_options[0]]);}

if(!isset($options[4])){
$options[0]=$settings['default_lang'];
$options[1]=$settings['default_ampm'];
$options[2]=$settings['default_sound'];
$options[3]='0';
$options[4]=$settings['default_sound'];}

for($i=0;$i<5;$i++){$options[$i]=(int)$options[$i];}

return $options;}

// ------------------

function get_language(){

global $options,$lang,$js_lang,$lang_list,$lang_abbr,$lang_name;
require_once('lang/languages.php');

$lang_abbr =         $lang_list[0][0];
$lang_name =         $lang_list[0][1];
$lang_file = 'lang/'.$lang_list[0][2];

if(isset($lang_list[$options[0]])){
$lang_abbr =         $lang_list[$options[0]][0];
$lang_name =         $lang_list[$options[0]][1];
$lang_file = 'lang/'.$lang_list[$options[0]][2];
}

require_once($lang_file);}

// ------------------

$timestamp=time();
$random=mt_rand(1,999999); 
$ipaddr=$_SERVER['REMOTE_ADDR'];
if(isset($norealips) && $norealips>0){$ipaddr=sha1($ipaddr);}
if(!isset($ping_period) || $ping_period<3 || $ping_period>20){$ping_period=5;}
if(!isset($ping_multpl) || $ping_multpl<3 || $ping_multpl>20){$ping_multpl=5;}

require_once 'incl/mysqli_functions.php';

if(!headers_sent()){
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Content-type: text/html; charset=UTF-8");}

?>