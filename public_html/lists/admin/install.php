<?php


ob_start();

session_start();

if (isset($_GET['page'])) {
  $Page = sprintf("%s",$_GET['page']);
}

foreach ($_POST as $key => $val) {
  $_SESSION[$key] = $val;
}
//print_r($_POST); # test
require("install/english.inc");
include("install/".$_SESSION["language_module"]."");
require("install/mysql.inc");
require("install/steps-lib.php");
include("install/header-install.inc");
require("install/requiredvars.php");
require("languages.php");
//error_reporting(E_ALL & E_STRICT);
error_reporting(0);
checkSessionCheckboxes();
?>
<div class="install_start wrong">

<?php

$listsDirPath = substr($_SERVER['PHP_SELF'], 0, -15);
$configFilePath = "../config/config.php";
if (!file_exists($configFilePath)) {
  if (!is_writable("../config")) {
    print $GLOBALS["I18N"]->get(sprintf('%s<hr>',$GLOBALS["strConfigIsNotAndDirNotWri"]));
  }
  willNotContinue();
}
else {
  if (!is_writable($configFilePath)) {
    print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strConfigIsNotWritable"]));
    willNotContinue();
  }
  if (filesize($configFilePath) > 1) {
    print $GLOBALS["I18N"]->get(sprintf('<br><br>%s',$GLOBALS["strConfigHasContent"]));
    willNotContinue();
  }
}

?>
</div>
<?php


if (!$_SESSION["history"]) {
$_SESSION["history"] = array();
}
if ($_SESSION["page"]) {
  array_push($_SESSION["history"],$_SESSION["page"]);
  $_SESSION["history"] = array_unique($_SESSION["history"]);
}


if (isset($Page) && in_array($Page, $_SESSION["history"])) {
  $getpage = sprintf("%s",$Page);
  $page = $_SESSION["page"]!=$getpage?$getpage:$_SESSION["page"];
  if (preg_match("/([\w_]+)/",$page,$regs)) {
  $page = $regs[1];
  }
  if (!is_file('install/'.$page.'.php') ) {
    $page = 'home';
  }
  getNextPageForm($page);
}

else {
  $page = 'home';
  getNextPageForm($page);
}

print checkScalarInt($_SESSION, $GLOBALS['requiredVars']);
$_SESSION["printeable"] = '<table width=500><tr><td>';
for ($i=0;$i<count($_SESSION["history"]);$i++) {
  $_SESSION["printeable"] .= sprintf('<a href="./?page=%s">Step %s</a> >> ', $_SESSION["history"][$i], $i);
}
$_SESSION["printeable"] .= '</td></tr></table>';


?>
<?php
require_once("install/define.php");

include('install/footer.inc');
//print_r($_SESSION); # test
ob_end_flush();

?>
