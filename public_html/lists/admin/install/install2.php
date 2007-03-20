<?php
require('configvars.php');

$test_connection2 = Sql_Connect_Install($_SESSION["database_host"], $_SESSION["database_user"],$_SESSION["database_password"], $_SESSION["database_name"]);

if ($test_connection2 == FALSE) {
  printf('<div class="wrong">%s</div><br>',$GLOBALS["strStillNoConnection"]);
  $canNotConnect = 1;
  willNotContinue();
}

print editVariable($securityVars,'name', 'text');

$bouncesTest = processPopTest($_SESSION["bounce_mailbox_host"], $_SESSION["bounce_mailbox_user"], $_SESSION["bounce_mailbox_password"]);

if ($bouncesTest == TRUE) {
  printf('<p>'.$GLOBALS["popAccountOk"].'</p>');
}
else {
  printf('<p class="allwrong">'.$GLOBALS["popAccountKo"].$_SESSION["bounce_mailbox_host"].'</p>');
}

?>