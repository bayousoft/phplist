<?php

//require('requiredvars.php');

if ($_SESSION['dbCreatedSuccesfully'] == "0") {
  $yourValue = $_SESSION;
}
else {
  printf('<p>%s</p>',$GLOBALS["strPhplistDbCreation"]);
}
printf('<table><tr><td>%s</td><td><input name="database_name" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_host" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_user" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_password" type="text" value="%s"></td></tr>
<tr><td></td><td></td></tr></table>',
$GLOBALS["strDbname"], $_SESSION['database_name'], $GLOBALS["strDbhost"], $_SESSION['database_host'], $GLOBALS["strDbuser"], $_SESSION['database_user'], $GLOBALS["strDbpass"], $_SESSION['database_password']);

?>