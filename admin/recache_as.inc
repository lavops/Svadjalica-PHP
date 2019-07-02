<?php

// prepare stickers to cache
$sticker_dirs=array();
$sticker_sets=array();
$sticker_java=array();

$handle=opendir('stickers');

// make an array of sticker dirs
while($entry=readdir($handle)){
if($entry!='.' && $entry!='..' && is_dir('stickers/'.$entry) && abc123($entry,'')==$entry && !stristr($entry,' ')){
$sticker_dirs[]=$entry;
$sticker_sets[$entry]=array();
}}
closedir($handle);

foreach ($sticker_dirs as &$value) {
$curr_dir='stickers/'.$value;

$handle=opendir($curr_dir);

while($entry=readdir($handle)){
$txt='_'.$entry;
if(is_file($curr_dir.'/'.$entry) && (stristr($txt,'svg')||stristr($txt,'png')||stristr($txt,'jpg')||stristr($txt,'jpeg')||stristr($txt,'gif'))){
$sticker_sets[$value][]='<img src="'.$curr_dir.'/'.$entry.'" alt="" class="chat_list_sticker" onclick="add_sti(\\\''.$curr_dir.'/'.$entry.'\\\')" /> ';
$sticker_java[]=$curr_dir.'/'.$entry;
}}

closedir($handle);
sort($sticker_sets[$value]);
$sticker_sets[$value]=implode('',$sticker_sets[$value]);
}

$stick2js=implode("','",$sticker_java);
$stick2js="stickers=new Array('$stick2js');";
ksort($sticker_sets);

$stickers_db=base64_encode(serialize($sticker_sets));
$stick2js_db=base64_encode($stick2js);

neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$stickers_db' WHERE id='sticache1'");
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$stick2js_db' WHERE id='sticache2'");


// prepare avatars to cache

$allp=array();

$handle=opendir("avatars");

while($entry=readdir($handle)){
$txt='_'.$entry;
if(is_file('avatars/'.$entry) && !stristr($txt,'00') && (stristr($txt,'svg')||stristr($txt,'png')||stristr($txt,'jpg')||stristr($txt,'jpeg')||stristr($txt,'gif'))){
$allp[]=$entry;}}
closedir($handle);

$avatars_db=base64_encode(serialize($allp));
neutral_query('UPDATE '.$dbss['prfx']."_settings SET value='$avatars_db' WHERE id='avt_cache'");

?>