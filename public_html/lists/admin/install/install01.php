<?php
error_reporting(63);

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

if (isset($_SESSION['database_root_user']) && isset($_SESSION['database_root_pass'])) {
  $root_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass'], $_SESSION['database_name']);
  if ($root_connection != 2 && $root_connection != 1) {
    $root_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass']);
    $create_db = Sql_Create_Db($_SESSION['database_name']);
    $GLOBALS["database_connection"] = $root_connection;
  }
  $rootPriv = Sql_Query(sprintf("GRANT ALL PRIVILEGES ON %s.* TO '%s'@'localhost' IDENTIFIED BY '%s'",$_SESSION['database_name'],$_SESSION['database_user'],$_SESSION['database_password']));
  $root_error =  "$rootPriv error root<br/>";
  unset($_SESSION['database_root_user']);
  unset($_SESSION['database_root_pass']);
  sql_query("FLUSH PRIVILEGES");
}
Sql_Close($root_connection);
sleep(2);
flush();
$test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);

if (!$test_connection) {
  $test_connection = sql_connect_install($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'])
  $create_db = Sql_Create_Db($_SESSION['database_name']);
}
switch ($test_connection) {
  case 2:
    $procedure = 2;
  break;
  case 1:
    $create_db = Sql_Create_Db($_SESSION['database_name']);
    sql_query("FLUSH PRIVILEGES");
    $new_db_conn = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);
    if ($new_db_conn != 2)
      $procedure = $new_db_conn;
    else
      $procedure = 1;
  break;
  case 0:
  default:
    $procedure = $test_connection;
  break;
}
switch ($procedure) {
  case 2:
    $_SESSION['dbCreatedSuccesfully'] = 1;
    print $GLOBALS["I18N"]->get(sprintf('<p>%sA</p>',$GLOBALS["dbAlreadyCreated"]));
  break;
  case 1:
    $_SESSION['dbCreatedSuccesfully'] = 1;
    print $GLOBALS["I18N"]->get(sprintf('<p>%sB</p><p>%s</p>', $rootPriv ? $GLOBALS["strUserCreatedOk"] : "",$GLOBALS["strDbCreatedOk"]));
  break;
  case 0:
  default:
    switch ($procedure) {
    case 2005:
    case 2009:
      $msg = $procedure.$GLOBALS["strWrongHost"];
    case 1040: # too many connections
      $msg = $procedure.$GLOBALS["strServerBusy"];
    case 1044: # access denied
      $msg = $procedure . $GLOBALS["strAccessDenied"];
    break;
    case 0:
    default:
      $msg = $procedure.$GLOBALS["strAccessDenied"];
    break;
    }
  $_SESSION['dbCreatedSuccesfully'] = 0;
  print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s</div>',$msg));
  unset($_SESSION["printeable"]);
  getNextPageForm('install0');
  include("install/footer.inc");
  exit;
  break;
}


?>