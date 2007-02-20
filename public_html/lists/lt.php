<?php
ob_start();
$er = error_reporting(0); # some ppl have warnings on
require_once dirname(__FILE__) .'/admin/commonlib/lib/magic_quotes.php';
require_once dirname(__FILE__).'/admin/init.php';
## none of our parameters can contain html for now
$_GET = removeXss($_GET);
$_POST = removeXss($_POST);
$_REQUEST = removeXss($_REQUEST);

if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
  include $_ENV["CONFIG"];
} elseif (is_file("config/config.php")) {
  include "config/config.php";
}
error_reporting($er);

require_once dirname(__FILE__).'/admin/'.$GLOBALS["database_module"];
require_once dirname(__FILE__)."/texts/english.inc";
include_once dirname(__FILE__)."/texts/".$GLOBALS["language_module"];
require_once dirname(__FILE__)."/admin/defaultconfig.inc";
require_once dirname(__FILE__).'/admin/connect.php';
include_once dirname(__FILE__)."/admin/languages.php";

$id = sprintf('%s',$_GET['id']);
if ($id != $_GET['id']) {
  print "Invalid Request";
  exit;
}

$track = base64_decode($id);
$track = $track ^ XORmask;
@list($msgtype,$fwdid,$messageid,$userid) = explode('|',$track);
$userid = sprintf('%d',$userid);
$fwdid = sprintf('%d',$fwdid);
$messageid = sprintf('%d',$messageid);
/*$linkdata = Sql_Fetch_array_query(sprintf('select * from %s where linkid = %d and userid = %d and messageid = %d',
  $GLOBALS['tables']['linktrack'],$linkid,$userid,$messageid));*/
$linkdata = Sql_Fetch_array_query(sprintf('select * from %s where id = %d',$GLOBALS['tables']['linktrack_forward'],$fwdid));

if (!$fwdid || $linkdata['id'] != $fwdid || !$userid || !$messageid) {
  FileNotFound();
#  echo 'Invalid Request';
  # maybe some logging?
  exit;
}
#print "$track<br/>";
#print "User $userid, Mess $messageid, Link $linkid";

$ml = Sql_Fetch_Array_Query(sprintf('select * from %s where messageid = %d and forwardid = %d',
  $GLOBALS['tables']['linktrack_ml'],$messageid,$fwdid));

if (empty($ml['firstclick'])) {
  Sql_query(sprintf('update %s set firstclick = now(),latestclick = now(),clicked = clicked + 1 where forwardid = %d and messageid = %d',
    $GLOBALS['tables']['linktrack_ml'],$fwdid,$messageid));
} else {
  Sql_query(sprintf('update %s set clicked = clicked + 1, latestclick = now() where forwardid = %d and messageid = %d',
  $GLOBALS['tables']['linktrack_ml'],$fwdid,$messageid));
}

if ($msgtype == 'H') {
  Sql_query(sprintf('update %s set htmlclicked = htmlclicked + 1 where forwardid = %d and messageid = %d',
    $GLOBALS['tables']['linktrack_ml'],$fwdid,$messageid));
} elseif ($msgtype == 'T') {
  Sql_query(sprintf('update %s set textclicked = textclicked + 1 where forwardid = %d and messageid = %d',
    $GLOBALS['tables']['linktrack_ml'],$fwdid,$messageid));
}
   
$viewed = Sql_Fetch_Row_query(sprintf('select viewed from %s where messageid = %d and userid = %d',
  $GLOBALS['tables']['usermessage'], $messageid, $userid));
if (!$viewed[0]) {
  Sql_Query(sprintf('update %s set viewed = now() where messageid = %d and userid = %d', 
    $GLOBALS['tables']['usermessage'], $messageid, $userid));
  Sql_Query(sprintf('update %s set viewed = viewed + 1 where id = %d', 
    $GLOBALS['tables']['message'], $messageid));
}

$uml = Sql_Fetch_Array_Query(sprintf('select * from %s where messageid = %d and forwardid = %d and userid = %d',
  $GLOBALS['tables']['linktrack_uml_click'],$messageid,$fwdid,$userid));

if (empty($uml['firstclick'])) {
  Sql_query(sprintf('insert into %s set firstclick = now(), forwardid = %d, messageid = %d, userid = %d',
    $GLOBALS['tables']['linktrack_uml_click'],$fwdid,$messageid,$userid));
} 
Sql_query(sprintf('update %s set clicked = clicked + 1, latestclick = now() where forwardid = %d and messageid = %d and userid = %d',$GLOBALS['tables']['linktrack_uml_click'],$fwdid,$messageid,$userid));

if ($msgtype == 'H') {
  Sql_query(sprintf('update %s set htmlclicked = htmlclicked + 1 where forwardid = %d and messageid = %d and userid = %d',
    $GLOBALS['tables']['linktrack_uml_click'],$fwdid,$messageid,$userid));
} elseif ($msgtype == 'T') {
  Sql_query(sprintf('update %s set textclicked = textclicked + 1 where forwardid = %d and messageid = %d and userid = %d',
    $GLOBALS['tables']['linktrack_uml_click'],$fwdid,$messageid,$userid));
}

$url = $linkdata['url'];
if ($linkdata['personalise']) {
  $uid = Sql_Fetch_Row_Query(sprintf('select uniqid from %s where id = %d',$GLOBALS['tables']['user'],$userid));
  if ($uid[0]) {
    if (strpos($url,'?')) {
      $url .= '&uid='.$uid[0];
    } else {
      $url .= '?uid='.$uid[0];
    }
  }
}
#print "$url<br/>";
if (!isset($_SESSION['entrypoint'])) {
  $_SESSION['entrypoint'] = $url;
}

header("Location: " . $url);
exit;
?>
