<?php

//require('requiredvars.php');
print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

/*if ($_SESSION['dbCreatedSuccesfully'] == 0) {
  $yourValue = $_SESSION;*/
  print $GLOBALS["I18N"]->get(sprintf('<table width=500><tr><td><div class="explain">%s</div></td></tr></table>', $GLOBALS['strDbExplain']));
// }
// else {
//   print $GLOBALS["I18N"]->get(sprintf('<p>%s</p>',$GLOBALS["strPhplistDbCreation"]));
// }

print $GLOBALS["I18N"]->get(sprintf('<style type="text/css">table tr td input { float:right; }</style><table width=500><tr><td>%s</td><td><input name="database_name" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_host" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_user" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_password" type="text" value="%s"></td></tr>
<tr><td></td><td></td></tr></table>', $GLOBALS["strDbname"], $_SESSION['database_name'], $GLOBALS["strDbhost"], $_SESSION['database_host'], $GLOBALS["strDbuser"], $_SESSION['database_user'], $GLOBALS["strDbpass"], $_SESSION['database_password']));

print $GLOBALS["I18N"]->get(sprintf('<table width=500>
<tr><td colspan=2><div class="explain">%s</div></td></tr>
<tr><td>%s</td><td><input name="database_root_user" type="text" value="%s"></td></tr>
<tr><td>%s</td><td><input name="database_root_pass" type="text" value="%s"></td></tr>
</table>',$GLOBALS["strDbroot"],$GLOBALS["strDbrootuser"], $_SESSION['database_root_user'], $GLOBALS["strDbrootpass"], $_SESSION['database_root_pass']));

?>