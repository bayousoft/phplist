<?php

ob_start();
@session_start();

if (isset($_GET['page'])) {
  $Page = sprintf("%s",strip_tags($_GET['page']));
}
foreach ($_POST as $key => $val) {
  $_SESSION[$key] = strip_tags($val);
}
//print_r($_SESSION);
# for now just in english, I guess
require(dirname(__FILE__).'/install/english.inc');
require(dirname(__FILE__).'/install/mysql.inc');
require(dirname(__FILE__)."/install/steps-lib.php");
include(dirname(__FILE__)."/install/header-install.inc");
require(dirname(__FILE__)."/install/requiredvars.php");
require(dirname(__FILE__)."/languages.php");

//error_reporting(E_ALL & E_STRICT);
if (!isset($GLOBALS['developer_email'])) {
  error_reporting(0);
}
//checkSessionCheckboxes();
?>
<div class="install_start wrong">

<?php

if (!file_exists($configfile)) {
  if (!is_writable("../config")) {
    print $GLOBALS["I18N"]->get(sprintf('%s<hr>',$GLOBALS["strConfigIsNotAndDirNotWri"]));
  }
  willNotContinue();
}
else {
  if (!is_writable($configfile)) {
    print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strConfigIsNotWritable"]));
    willNotContinue();
  }
  if (filesize($configfile) > 1) {
    print $GLOBALS["I18N"]->get(sprintf('<br><br>%s',$GLOBALS["strConfigHasContent"]));
    willNotContinue();
  }
}

?>
</div>
<?php


if (!isset($_SESSION["history"])) {
  $_SESSION["history"] = array();
}
if (!empty($_SESSION["page"])) {
  array_push($_SESSION["history"],$_SESSION["page"]);
  $_SESSION["history"] = array_unique($_SESSION["history"]);
}

#$page2 = "pages";
if (isset($Page) && in_array($Page, $_SESSION["history"])) {
  $getpage = sprintf("%s",$Page);
  $page = $_SESSION["page"]!=$getpage?$getpage:$_SESSION["page"];
  if (preg_match("/([\w_]+)/",$page,$regs)) {
  $page = $regs[1];
  }
  getNextPageForm($page);
}

else {
  getNextPageForm("home");
}

print $GLOBALS["I18N"]->get(checkScalarInt($_SESSION, $GLOBALS['requiredVars']));
$_SESSION["printeable"] = '<table width=500><tr><td>';
for ($i=0;$i<count($_SESSION["history"]);$i++) {
  $_SESSION["printeable"] .= sprintf('<a href="./?page=%s">'.$GLOBALS["I18N"]->get($GLOBALS["strStep"]).' %s</a> >> ', $_SESSION["history"][$i], $i);
}
$_SESSION["printeable"] .= '</td></tr></table>';


?>
<?php
require_once("install/define.php");

include('install/footer.inc');
/*
print "<table>";
foreach ($GLOBALS["requiredVars"] as $key => $val) {
  print "<tr><td>POST name=$key val=".$_POST[$key]."</td>";
  print "<td>\nSESSION name=$key val=".$_SESSION[$key]."</td>";
  print "<td>\n RequiredVars name=$key val=".$val["values"]."</td></tr>";
}
print "</table>";
*/

ob_end_flush();

?>
