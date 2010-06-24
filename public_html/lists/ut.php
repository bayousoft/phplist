<?php
ob_start();
$er = error_reporting(0); 
require_once dirname(__FILE__) .'/admin/commonlib/lib/unregister_globals.php';
require_once dirname(__FILE__) .'/admin/commonlib/lib/magic_quotes.php';
require_once dirname(__FILE__).'/admin/init.php';

## none of our parameters can contain html for now
$_GET = removeXss($_GET);
$_POST = removeXss($_POST);
$_REQUEST = removeXss($_REQUEST);

if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
  include $_SERVER["ConfigFile"];
} elseif (is_file("config/config.php")) {
  include "config/config.php";
} else {
  print "Error, cannot find config file\n";
  exit;
}
#error_reporting($er);
if (isset($GLOBALS["developer_email"]) && $GLOBALS['show_dev_errors']) {
  error_reporting(E_ALL);
} else {
  error_reporting(0);
}

require_once dirname(__FILE__).'/admin/'.$GLOBALS["database_module"];
require_once dirname(__FILE__)."/texts/english.inc";
include_once dirname(__FILE__)."/texts/".$GLOBALS["language_module"];
require_once dirname(__FILE__)."/admin/defaultconfig.inc";
require_once dirname(__FILE__).'/admin/connect.php';
include_once dirname(__FILE__)."/admin/languages.php";

if ($_GET["u"] && $_GET["m"]) {
  $_GET['u'] = preg_replace('/\W/','',$_GET['u']);
  $query = sprintf('select id from %s where uniqid = ?', $GLOBALS['tables']['user']);
  $rs = Sql_Query_Params($query, array($_GET['u']));
  $userid = Sql_Fetch_Row($rs);
  if ($userid[0]) {
    $query
    = ' update %s set viewed = current_timestamp'
    . ' where messageid = ? and userid = ?';
    $query = sprintf($query, $GLOBALS['tables']['usermessage']);
    Sql_Query_Params($query, array($_GET['m'], $userid[0]));
    $query
    = ' update %s set viewed = viewed + 1'
    . ' where id = ?';
    $query = sprintf($query, $GLOBALS['tables']['message']);
    Sql_Query_Params($query, array($_GET['m']));
  }
}

@ob_end_clean();
header("Content-Type: image/png");
print base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABGdBTUEAALGPC/xhBQAAAAZQTFRF////AAAAVcLTfgAAAAF0Uk5TAEDm2GYAAAABYktHRACIBR1IAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH0gQCEx05cqKA8gAAAApJREFUeJxjYAAAAAIAAUivpHEAAAAASUVORK5CYII=');
