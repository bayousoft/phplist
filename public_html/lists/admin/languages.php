<?php
if ( is_file("accesscheck.php") ) {
  require_once "accesscheck.php";
} elseif ( is_file("admin/accesscheck.php") ) {
  require_once "admin/accesscheck.php";
} 
/*

Languages, countries, and the charsets typically used for them
http://www.w3.org/International/O-charset-lang.html

*/

$LANGUAGES = array(
"af" => array("Afrikaans","iso-8859-1, windows-1252"),
"sq" => array("Albanian","iso-8859-1, windows-1252"),
"ar" => array("Arabic","iso-8859-6"),
"eu" => array("Basque","iso-8859-1, windows-1252"),
"bg" => array("Bulgarian","iso-8859-5"),
"be" => array("Byelorussian","iso-8859-5"),
"ca" => array("Catalan","iso-8859-1, windows-1252"),
"hr" => array("Croatian"," iso-8859-2, windows-1250 "),
"cs" => array("Czech "," iso-8859-2 "),
"da" => array("Danish "," iso-8859-1, windows-1252 "),
"nl"=> array("Dutch "," iso-8859-1, windows-1252 "),
"en" => array("English "," iso-8859-1, windows-1252 "),
"eo" => array("Esperanto "," iso-8859-3* "),
"et" => array("Estonian ","iso-8859-15 "),
"fo" => array("Faroese "," iso-8859-1, windows-1252 "),
"fi"=> array("Finnish "," iso-8859-1, windows-1252 "),
"fr"=>array("French ","iso-8859-1, windows-1252 "),
"gl"=>array("Galician "," iso-8859-1, windows-1252 "),
"de" => array("German "," iso-8859-1, windows-1252 "),
"el"=> array("Greek "," iso-8859-7 "),
"iw"=> array("Hebrew "," iso-8859-8 "),
"hu"=>array("Hungarian "," iso-8859-2 "),
"is"=>array("Icelandic "," iso-8859-1, windows-1252 "),
"ga"=>array("Irish "," iso-8859-1, windows-1252 "),
"it"=>array("Italian "," iso-8859-1, windows-1252 "),
"ja"=>array("Japanese "," shift_jis, iso-2022-jp, euc-jp"),
"lv"=> array("Latvian ","iso-8859-13, windows-1257"),
"lt"=> array("Lithuanian "," iso-8859-13, windows-1257"),
"mk"=> array("Macedonian ","iso-8859-5, windows-1251"),
"mt"=> array("Maltese ","iso-8859-3"),
"no"=>array("Norwegian ","iso-8859-1, windows-1252"),
"pl"=>array("Polish ","iso-8859-2"),
"pt"=>array("Portuguese "," iso-8859-1, windows-1252"),
"ro"=>array("Romanian "," iso-8859-2"),
"ru"=>array("Russian "," koi8-r, iso-8859-5"),
"gd"=>array("Scottish "," iso-8859-1, windows-1252"),
"srcyrillic"=>array("Serbian "," windows-1251, iso-8859-5"),
"srlatin"=>array("Serbian "," iso-8859-2, windows-1250"),
"sk"=>array( "Slovak "," iso-8859-2"),
"sl"=>array( "Slovenian "," iso-8859-2, windows-1250"),
"es"=>array("Spanish "," iso-8859-1, windows-1252"),
"sv"=>array("Swedish "," iso-8859-1, windows-1252"),
"tr"=> array("Turkish "," iso-8859-9, windows-1254"),
"uk"=>array("Ukrainian "," iso-8859-5")
);

