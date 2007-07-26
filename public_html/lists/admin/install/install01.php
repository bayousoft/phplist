<?php
error_reporting(63);

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

if (isset($_SESSION['database_root_user']) && isset($_SESSION['database_root_pass'])) {
  $test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass'], $_SESSION['database_name']);
  if ($test_connection != 2 && $test_connection != 1) {
    $test_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass']);
    $create_db = Sql_Create_Db($_SESSION['database_name']);
    $GLOBALS["database_connection"] = $test_connection;
  }
  $rootPriv = Sql_Query(sprintf("GRANT ALL ON %s.* TO '%s'@'localhost' IDENTIFIED BY '%s'",$_SESSION['database_name'],$_SESSION['database_user'],$_SESSION['database_password']));
  unset($_SESSION['database_root_user']);
  unset($_SESSION['database_root_pass']);
}
Sql_Close($test_connection);
sleep(2);
flush();
$test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);
switch ($test_connection) {
  case 2:
    $procedure = 2;
  break;
  case 1:
    $create_db = Sql_Create_Db($_SESSION['database_name']);
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
      $msg = $GLOBALS["strWrongHost"];
    case 1040: # too many connections
      $msg = $GLOBALS["strServerBusy"];
    case 1044: # access denied
    case 0:
    default:
      $msg = $GLOBALS["strAccessDenied"];
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