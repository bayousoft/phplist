<?php
error_reporting(63);

print $_SESSION["printeable"];

if (isset($_SESSION['database_root_user']) && isset($_SESSION['database_root_pass'])) {
  $test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass'], $_SESSION['database_name']);
  if (!$test_connection) {
    $test_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass']);
    $create_db = Sql_Create_Db($_SESSION['database_name']);
  }
  $GLOBALS["database_connection"] = $test_connection;
/*  $rootUser = Sql_Query(sprintf("CREATE USER '%s'@'%%' IDENTIFIED BY '%s'",$_SESSION['database_user'],$_SESSION['database_password']));
#  if (!$rootUser) { print "no conn<br/>"; }
  $rootGrant = Sql_Query(sprintf("GRANT USAGE ON *.* TO '%s'@'%%' IDENTIFIED BY '%s'",$_SESSION['database_user'],$_SESSION['database_password']));*/
  #$rootDb = Sql_Query(sprintf("CREATE DATABASE IF NOT EXISTS '%s'",$_SESSION['database_name']));
  $rootPriv = Sql_Query(sprintf("GRANT ALL ON %s.* TO '%s'@'localhost' IDENTIFIED BY '%s'",$_SESSION['database_name'],$_SESSION['database_user'],$_SESSION['database_password']));
#  if (!$rootUser || !$rootGrant || !$rootDb || !$rootPriv) { print "algo mal"; foreach (array($rootUser,$rootGrant,$rootDB,$rootPriv) as $key) { print_r($key); echo "$key <br/>"; } exit;}
  unset($_SESSION['database_root_user']);
  unset($_SESSION['database_root_pass']);
}
Sql_Close($test_connection);
$test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);
#print "Hello"; exit;
$create_db = (!$test_connection ? (Sql_Create_Db($_SESSION['database_name'])?1:0) : 0);
if ($test_connection && $create_db == 0) {
  $_SESSION['dbCreatedSuccesfully'] = 1;
  printf('<p>%s</p>',$GLOBALS["dbAlreadyCreated"]);
}
elseif ($create_db == 1) {
  $_SESSION['dbCreatedSuccesfully'] = 0;
  printf('<p>%s</p><p>%s</p>', $userOk ? $GLOBALS["strUserCreatedOk"] : "",$GLOBALS["strDbCreatedOk"]);
}
elseif ($test_connection == 0 && $create_db == 0) {
  $_SESSION['dbCreatedSuccesfully'] = 0;
  printf('<div class="wrong">%s</div>',$GLOBALS["strDbCreatedKo"]);
  unset($_SESSION["printeable"]);
  getNextPageForm('install0');
  include("install/footer.inc");
  exit;
}


?>