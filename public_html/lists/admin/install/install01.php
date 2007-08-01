<?php
error_reporting(63);

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

if (isset($_SESSION['database_root_user']) && isset($_SESSION['database_root_pass'])) {
  $root_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass'], $_SESSION['database_name']);
  if (!$root_connection) {
    $root_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass']);
    if ($root_connection) {
    $create_db = Sql_Create_Db($_SESSION['database_name']);
#    $GLOBALS["database_connection"] = $root_connection; # useless
# if error let's still try to connect with user's settings
    }
  }
  Sql_Query(sprintf("GRANT ALL PRIVILEGES ON %s.* TO '%s'@'localhost' IDENTIFIED BY '%s'", $_SESSION['database_name'], $_SESSION['database_user'], $_SESSION['database_password']));
#  if (!$rootPriv)  print '<script language="Javascript"> alert("No privileges given"); </script>'; # testing
  unset($_SESSION['database_root_user']);
  unset($_SESSION['database_root_pass']);
  sql_query("FLUSH PRIVILEGES"); # this will help on windows and mac machines
}
if ($root_connection) {
  Sql_Close($root_connection);
}
sleep(2);
#flush();
$test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);

if (!$test_connection) { // let's connect without a db
  $test_connection2 = sql_connect_install($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password']);
  if ($test_connection2) {
    $create_db = Sql_Create_Db($_SESSION['database_name']);
    if (!$create_db) {
      $errorno = sql_errorno();
      $procedure = 2;
    } else {
      $procedure = 1;
    }
  } else {
    $errorno = sql_errorno();
    $procedure = 3;
  }
} else {
  $procedure = 1;
}

/**
procedure 1 = all ok, connection && db
procedure 2 = connection ok && !db
procedure 3 = no connection
*/
if ($errorno) {
  switch($errorno) {
    case 2005:
    case 2009:
      $msg = $GLOBALS["strWrongHost"];
    case 1040: # too many connections
      $msg = $GLOBALS["strServerBusy"];
    case 1044: # access denied
      $msg = $GLOBALS["strAccessDenied"];
    break;
    case 0:
    default:
      $msg = $GLOBALS["strAccessDenied"];
    break;
  }
}

switch ($procedure) {
  case 1:
    $_SESSION['dbCreatedSuccesfully'] = 1;
    print $GLOBALS["I18N"]->get(sprintf('<p>%sA</p>',$GLOBALS["dbAlreadyCreated"]));
  break;
  case 2:
    $msg = $GLOBALS["strCuoldNotCreateDb"] . "<br/>" . $msg;
    $_SESSION['dbCreatedSuccesfully'] = 0;
  case 3:
  default:
    $_SESSION['dbCreatedSuccesfully'] = 0;
  break;
}
if (!$_SESSION['dbCreatedSuccesfully']) {
  print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s</div>',$msg));
  unset($_SESSION["printeable"]);
  getNextPageForm('install0');
  include("install/footer.inc");
  exit;
  break;
}


?>