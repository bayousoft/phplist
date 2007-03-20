<?php


$test_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);
$create_db = Sql_Create_Db($_SESSION['database_name']);

if ($test_connection == TRUE) {
  $_SESSION['dbCreatedSuccesfully'] = TRUE;
  printf('<h2>%s</h2>',$GLOBALS["dbAlreadyCreated"]);
}
elseif ($create_db == TRUE) {
  $_SESSION['dbCreatedSuccesfully'] = TRUE;
  printf('%s',$GLOBALS["strDbCreatedOk"]);
}
elseif ($test_connection == FALSE && $create_db == FALSE) {
  $_SESSION['dbCreatedSuccesfully'] = FALSE;
  printf('<div class="wrong">%s</div>',$GLOBALS["strDbCreatedKo"]);
  getNextPageForm('install0');
  include("install/footer.inc");
  exit;
}


?>