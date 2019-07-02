<?php

require_once 'config.php';
require_once 'incl/main.php';
require_once 'version.php';

function stripc($n){
$n=trim($n); $n=preg_replace('/[^\p{L}\p{N}]/u','',$n);
$n=substr($n,0,6); return $n;}


neutral_dbconnect(); $settings=get_settings();

require_once 'lang/admin_english.utf8';

/* --- */

if(isset($_COOKIE[$xcookie_uidhash[0]])){ require_once 'incl/cookieauth.php'; }else{ redirect('account.php');die();}
if(!isset($xuser['id']) || ($xuser['id']!=1 && $xuser['id']!=18)){header('location:info.php?q=nop');die();}

/* --- */

if(isset($_POST['1']) && isset($_POST['16']) && isset($_POST['colors'])){

$a=$settings['style_template'];

$b=neutral_escape($_POST['1'],2,'int');   $a=str_replace('[1]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=1");
$b=neutral_escape($_POST['2'],999,'str'); $a=str_replace('[2]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=2");
$b=neutral_escape($_POST['3'],9999,'str');$a=str_replace('[3]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=3");
$b=stripc($_POST['4']);   $a=str_replace('[4]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=4");
$b=stripc($_POST['5']);   $a=str_replace('[5]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=5");
$b=stripc($_POST['6']);   $a=str_replace('[6]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=6");
$b=stripc($_POST['7']);   $a=str_replace('[7]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=7");
$b=stripc($_POST['8']);   $a=str_replace('[8]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=8");
$b=stripc($_POST['9']);   $a=str_replace('[9]',$b,$a); neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=9");
$b=stripc($_POST['10']);  $a=str_replace('[10]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=10");
$b=stripc($_POST['11']);  $a=str_replace('[11]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=11");
$b=stripc($_POST['12']);  $a=str_replace('[12]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=12");
$b=stripc($_POST['13']);  $a=str_replace('[13]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=13");
$b=stripc($_POST['14']);  $a=str_replace('[14]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=14");
$b=neutral_escape($_POST['15'],3,'int');  $a=str_replace('[15]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=15");
$b=neutral_escape($_POST['16'],3,'int');  $a=str_replace('[16]',$b,$a);neutral_query('UPDATE '.$dbss['prfx']."_style SET value='$b' WHERE id=16");
$fgaccenttxt=makeclr(stripc($_POST['6'])); $a=str_replace('[0]',$fgaccenttxt,$a);

neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$a' WHERE id='style_delivery'");

$colors=neutral_escape($_POST['colors'],9999,'txt');
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$colors' WHERE id='colors'");

if(isset($_POST['webkit_css'])){ $x=neutral_escape($_POST['webkit_css'],9999,'str');
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='webkit_css'");}

redirect('admin.php?q=style&ok='.$timestamp); }

/* --- */

if(isset($_POST['notes'])){
$notes=neutral_escape($_POST['notes'],99999,'txt');
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$notes' WHERE id='notes'");
redirect('admin.php?ok='.$timestamp); }

/* --- */

if(isset($_GET['unban'])){ $id=(int)$_GET['unban'];
neutral_query('DELETE FROM '.$dbss['prfx']."_ban WHERE id=$id");
redirect('admin.php?q=logs&ok='.$timestamp); }

/* --- */

if(isset($_GET['ban']) && isset($_GET['period'])){ 
$id=(int)$_GET['ban']; $period=(int)$_GET['period'];
neutral_query('UPDATE '.$dbss['prfx']."_ban SET timestamp=timestamp+$period WHERE id=$id");
redirect('admin.php?q=logs&ok='.$timestamp); }


/* --- */
if(isset($_POST['edituser']) && isset($_POST['email'])){
$id=(int)$_POST['edituser']; $ok='';

$res=neutral_query('SELECT * FROM '.$dbss['prfx']."_users WHERE id=$id");
if(neutral_num_rows($res)<1){redirect('admin.php?q=users');die();}
$user=neutral_fetch_array($res);

if(isset($_POST['email']) && $_POST['email']!=$user['email'] && length($_POST['email'])>6 && stristr($_POST['email'],'@') && stristr($_POST['email'],'.')  && !stristr($_POST['email'],' ')){
$email=neutral_escape($_POST['email'],64,'str'); 
$res=neutral_query('SELECT * FROM '.$dbss['prfx']."_users WHERE email='$email'");
if(neutral_num_rows($res)<1){ $ok='&ok='.$timestamp;
neutral_query('UPDATE '.$dbss['prfx']."_users SET email='$email' WHERE id=$id");}}

if(isset($_POST['password']) && length($_POST['password'])>2){
$newpass=hash('sha256',trim($_POST['password']).$user['salt']); $ok='&ok='.$timestamp;
neutral_query('UPDATE '.$dbss['prfx']."_users SET password='$newpass' WHERE id=$id");} 

if(isset($_POST['question']) && isset($_POST['answer']) && length($_POST['question'])>0 && length($_POST['answer'])>0){
$question=neutral_escape($_POST['question'],128,'str');
$answer=hash('sha256',strtolower(trim($_POST['answer'])).$user['salt']);
$ok='&ok='.$timestamp;
neutral_query('UPDATE '.$dbss['prfx']."_users SET question='$question', answer='$answer' WHERE id=$id");} 

redirect('admin.php?q=user&id='.$id.$ok); }

/* --- */

if(isset($_POST['edituser']) && isset($_POST['motto']) && isset($_POST['avatar'])){
$id=(int)$_POST['edituser']; $ok='&ok='.$timestamp;
$avatar=neutral_escape($_POST['avatar'],50,'str'); $motto=neutral_escape($_POST['motto'],32,'str');
neutral_query('DELETE FROM '.$dbss['sprf']."_uxtra WHERE id=$id");
neutral_query('INSERT INTO '.$dbss['sprf']."_uxtra VALUES($id,'$avatar','$motto')");

redirect('admin.php?q=user&id='.$id.$ok);}

/* --- */

if(isset($_POST['fakeuser']) && isset($_POST['period']) && isset($_POST['status'])){
$id=$_POST['fakeuser']; $period=(int)$_POST['period']; $status=(int)$_POST['status'];
if($status<1 || $status>5){$status=2;}
if($period<1 || $period>99999){$period=2;} $period=$period*3600+$timestamp;
neutral_query('DELETE FROM '.$dbss['prfx']."_ufake WHERE id=$id");
neutral_query('INSERT INTO '.$dbss['prfx']."_ufake VALUES($id,'$status','$period')");

redirect('admin.php?q=users&ok='.$timestamp);}

/* --- */

if(isset($_POST['multimsg']) && is_array($_POST['multimsg']) && count($_POST['multimsg'])>0){
$multimsg=$_POST['multimsg'];
for($i=0;$i<count($multimsg);$i++){$multimsg[$i]=(int)$multimsg[$i];}
$dbinit=implode(',',$multimsg);
neutral_query('DELETE FROM '.$dbss['prfx']."_messages WHERE id IN ($dbinit)");
redirect('admin.php?q=messages&ok='.$timestamp); }

/* --- */

if(isset($_POST['whattodo']) && isset($_POST['multiusers']) && is_array($_POST['multiusers']) && count($_POST['multiusers'])>0){

$multiusers=$_POST['multiusers'];
for($i=0;$i<count($multiusers);$i++){$multiusers[$i]=(int)$multiusers[$i];}
$dbinit=implode(',',$multiusers);

if($_POST['whattodo']=='1'){neutral_query('UPDATE '.$dbss['prfx']."_users SET quarantine=0 WHERE id IN ($dbinit)");}
if($_POST['whattodo']=='2'){
neutral_query('DELETE FROM '.$dbss['prfx']."_users WHERE id>1 AND id IN ($dbinit)");
neutral_query('DELETE FROM '.$dbss['sprf']."_uxtra WHERE id>1 AND id IN ($dbinit)");
for($i=0;$i<count($multiusers);$i++){
$avfile=hash('sha256',$multiusers[$i].$settings['random_salt']);
$avfile='avatars/'.substr($avfile,0,20);
@unlink($avfile.'.png'); @unlink($avfile.'.jpg'); @unlink($avfile.'.jpeg'); @unlink($avfile.'.gif'); 
}} redirect('admin.php?q=users&ok='.$timestamp); }

/* --- */

if(isset($_GET['deloldms'])){
$delpoint=(int)$_GET['deloldms'];
if($delpoint>0){$delpoint=$timestamp-($delpoint*86400);
neutral_query('DELETE FROM '.$dbss['prfx']."_messages WHERE timestamp<$delpoint AND id>1");
redirect('admin.php?q=messages&ok='.$timestamp);}}

/* --- */

if(isset($_GET['delguests'])){

$gs=array();
$res=neutral_query('SELECT id FROM '.$dbss['prfx']."_users WHERE length(password)<1");
while($row=neutral_fetch_array($res)){ $gs[]=$row['id']; }

if(count($gs)>0){$gs=implode(',',$gs);
neutral_query('DELETE FROM '.$dbss['sprf']."_uxtra WHERE id IN ($gs)");}
neutral_query('DELETE FROM '.$dbss['prfx']."_users WHERE length(password)<1");
neutral_query('DELETE FROM '.$dbss['prfx'].'_iplog');

redirect('admin.php?q=users&ok='.$timestamp);
}

if(isset($_GET['delfake'])){
neutral_query('DELETE FROM '.$dbss['prfx']."_ufake");
redirect('admin.php?q=users&ok='.$timestamp);
}

/* --- */

if(isset($_GET['addroom'])){
$zcolor=array('E91E63','673AB7','8BC34A','FFC107','3F51B5','C0CA33','1E88E5','607D8B','009688','E53935');
$k=array_rand($zcolor); $zcolor=$zcolor[$k];$zorder=substr($timestamp,-4);if($zorder<1000){$zorder+=1000;}
neutral_query('INSERT INTO '.$dbss['prfx']."_rooms VALUES(NULL,'NEW ROOM','Description...','$zcolor',$zorder)");
redirect('admin.php?q=rooms&ok='.$timestamp); }

if(isset($_GET['delroom'])){
$del=(int)$_GET['delroom'];
if($del>1){neutral_query('DELETE FROM '.$dbss['prfx'].'_rooms WHERE id='.$del);
redirect('admin.php?q=rooms&ok='.$timestamp); }}

if(isset($_GET['delallrooms'])){
neutral_query('DELETE FROM '.$dbss['prfx'].'_rooms WHERE id>1');
redirect('admin.php?q=rooms&ok='.$timestamp); }

/* --- */

if(isset($_POST['showroombg']) && isset($_POST['roombgc']) && isset($_POST['roombgf']) && isset($_POST['roombgt'])){  

$showroombg=(int)$_POST['showroombg'];   
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$showroombg' WHERE id='showroombg'");

$roombgc=stripc($_POST['roombgc']);
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$roombgc' WHERE id='roombgc'");

$roombgf=strtolower($_POST['roombgf']);
if( !in_array($roombgf, array('serif','sans-serif','monospace'))){$roombgf='serif';}
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$roombgf' WHERE id='roombgf'");

$roombgt=(int)$_POST['roombgt'];
if($roombgt<10 || $roombgt>100){$roombgt=10;}
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$roombgt' WHERE id='roombgt'");

redirect('admin.php?q=rooms&ok='.$timestamp);
}

/* --- */

if(isset($_POST['editroom'])){
$rid=(int)$_POST['editroom'];
if(isset($_POST['name']) && strlen(trim($_POST['name']))>2){$name=neutral_escape($_POST['name'],40,'str');}else{$name='ROOM #'.$rid;}
if(isset($_POST['desc'])){$desc=neutral_escape($_POST['desc'],80,'str');}else{$desc='';}
if(isset($_POST['color'])){$color=stripc($_POST['color']);}else{$color='666';}
if(isset($_POST['zorder'])){$zorder=(int)$_POST['zorder'];}else{$zorder=1001;} 
if($zorder<1000){$zorder+=1000;} if($rid==1){$zorder=0;}

neutral_query('UPDATE '.$dbss['prfx']."_rooms SET name='$name',description='$desc',color='$color',zorder=$zorder WHERE id=$rid");

redirect('admin.php?q=rooms&ok='.$timestamp);
}

/* --- */

if(isset($_POST['ctab_display'])){
if(isset($_POST['ctab_display'])){  $x=neutral_escape($_POST['ctab_display'],1,'int'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ctab_display'");}
if(isset($_POST['ctab_default'])){  $x=neutral_escape($_POST['ctab_default'],1,'int'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ctab_default'");}
if(isset($_POST['ctab_icon'])){  $x=neutral_escape($_POST['ctab_icon'],64,'str'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ctab_icon'");}
if(isset($_POST['ctab_title'])){  $x=neutral_escape($_POST['ctab_title'],16,'str'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ctab_title'");}
if(isset($_POST['ctab_content'])){  $x=neutral_escape($_POST['ctab_content'],99999,'txt'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ctab_content'");}
redirect('admin.php?q=ctab&ok='.$timestamp);}

/* --- */

if(isset($_POST['html_title'])){

if(isset($_POST['rmb_unsent'])){   $x=neutral_escape($_POST['rmb_unsent'],1,'int'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='rmb_unsent'");}
if(isset($_POST['acp_css'])){      $x=neutral_escape($_POST['acp_css'],1,'int'); $x.='.css';  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='acp_css'");}
if(isset($_POST['html_title'])){   $x=neutral_escape($_POST['html_title'],512,'str');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='html_title'");}
if(isset($_POST['cookie_salt'])){  $x=neutral_escape($_POST['cookie_salt'],64,'str');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='cookie_salt'");}
if(isset($_POST['default_lang'])){ $x=neutral_escape($_POST['default_lang'],2,'int');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='default_lang'");}
if(isset($_POST['default_ampm'])){ $x=neutral_escape($_POST['default_ampm'],1,'int');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='default_ampm'");}
if(isset($_POST['default_sound'])){$x=neutral_escape($_POST['default_sound'],1,'int'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='default_sound'");}
if(isset($_POST['allow_guest'])){  $x=neutral_escape($_POST['allow_guest'],1,'int');   neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='allow_guest'");}
if(isset($_POST['mobile_effe'])){  $x=neutral_escape($_POST['mobile_effe'],1,'int');   neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='mobile_effe'");}
if(isset($_POST['dimonblur'])){    $x=neutral_escape($_POST['dimonblur'],1,'int');   neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='dimonblur'");}
if(isset($_POST['allow_reg'])){    $x=neutral_escape($_POST['allow_reg'],1,'int');     neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='allow_reg'");}
if(isset($_POST['keepiplg'])){     $x=neutral_escape($_POST['keepiplg'],9,'int'); if($x<86400){$x=86400;} neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='keepiplg'");}
if(isset($_POST['reglog_delay'])){ $x=neutral_escape($_POST['reglog_delay'],9,'int');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='reglog_delay'");}
if(isset($_POST['post_interval'])){$x=neutral_escape($_POST['post_interval'],3,'int'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='post_interval'");}
if(isset($_POST['userperhour'])){  $x=neutral_escape($_POST['userperhour'],3,'int');  if($x<1){$x=1;} neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='userperhour'");}
if(isset($_POST['wrongperhour'])){ $x=neutral_escape($_POST['wrongperhour'],3,'int'); if($x<1){$x=1;} neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='wrongperhour'");}
if(isset($_POST['ban_period'])){   $x=neutral_escape($_POST['ban_period'],9,'int'); if($x<60){$x=60;} neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='ban_period'");}
if(isset($_POST['avatar_msize'])){ $x=neutral_escape($_POST['avatar_msize'],9,'int'); if($x<102400){$x=102400;} neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='avatar_msize'");}
if(isset($_POST['acp_offset'])){   $x=neutral_escape($_POST['acp_offset'],5,'int');  neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='acp_offset'");}
if(isset($_POST['welcome_msg'])){  $x=stripslashes($_POST['welcome_msg']); $x=str_replace("'",'',$x); $x=str_replace("\r",'',$x); $x=str_replace("\n",' ',$x); $x=neutral_escape($x,2048,'txt'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='welcome_msg'");}
if(isset($_POST['mottos'])){  $x=stripslashes($_POST['mottos']); $x=str_replace("|",'',$x); $x=str_replace("\r",'',$x); $x=explode("\n",$x); $x=implode('|',$x); $x=neutral_escape($x,99999,'txt'); neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='mottos'");}
if(isset($_POST['drag2scroll'])){  $x=neutral_escape($_POST['drag2scroll'],1,'int');   neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='drag2scroll'");}
if(isset($_POST['whee2scroll'])){  $x=neutral_escape($_POST['whee2scroll'],1,'int');   neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='whee2scroll'");}

redirect('admin.php?q=settings&ok='.$timestamp);}

/* --- */

if(isset($_POST['announce'])){
$x=neutral_escape($_POST['announce'],9999,'txt'); 
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='announce'");
redirect('admin.php?q=gdpr&ok='.$timestamp);}

/* --- */

if(isset($_POST['msg_style']) && isset($_POST['msg_template'])){

$x=neutral_escape($_POST['msg_style'],512,'str'); 
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='msg_style'");

$x=neutral_escape($_POST['msg_template'],512,'str'); 
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$x' WHERE id='msg_template'");

redirect('admin.php?q=stylemsg&ok='.$timestamp);}

/* --- */

if(isset($_GET['uncache']) || strlen($settings['sticache1'])<1 || strlen($settings['sticache2'])<1 || strlen($settings['avt_cache'])<1){
$forcereload=substr(md5($timestamp),0,9);
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$forcereload' WHERE id='forcereload'");
require_once('admin/recache_as.inc');
redirect('admin.php?q=settings&ok='.$timestamp);}


/* --- */


if(!isset($_GET['q'])){$q=false;}else{$q=$_GET['q'];}

$ext_version=(float)$version;
$int_version=(float)$settings['version'];
if($int_version<$ext_version){$q='update';}

switch ($q){
case 'logs'     : $page='logs.pxtm';break;
case 'settings' : $page='settings.pxtm';break;
case 'ctab'     : $page='ctab.pxtm';break;
case 'gdpr'     : $page='gdpr.pxtm';break;
case 'style'    : $page='style.pxtm';break;
case 'stylemsg' : $page='stylemsg.pxtm';break;
case 'room'     : $page='room.pxtm';break;
case 'rooms'    : $page='rooms.pxtm';break;
case 'messages' : $page='messages.pxtm';break;
case 'user'     : $page='user.pxtm';break;
case 'users'    : $page='users.pxtm';break;
case 'update'   : $page='update.pxtm';break;
case 'vpro'     : $page='vpro.pxtm';break;
case 'mapps'    : $page='mapps.pxtm';break;
default         : $page='board.pxtm';break;
}

require_once 'admin/'.$page;

?>