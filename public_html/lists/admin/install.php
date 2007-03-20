<?php


ob_start();

session_start();

if (isset($_POST['page']) && $_POST['page']!== 'write_install') {
  $Page = $_POST['page'];
  header("Location: ./?page=$Page");
}

foreach ($_POST as $key => $val) {
  $_SESSION[$key] = $val;
}
//print $_SERVER["ConfigFile"];
require dirname(__FILE__)."/../texts/english.inc";
require("mysql.inc");
//include('connect.php');
require("install/steps-lib.php");
include("install/header-install.inc");
require("install/requiredvars.php");
error_reporting(0);

?>
<div class="install_start wrong">

<?php

$listsDirPath = substr($_SERVER['PHP_SELF'], 0, -15);
$configFilePath = "../config/config.php";
if (!file_exists($configFilePath)) {
  if (!is_writable("../config")) {
    printf('%s<hr>',$GLOBALS["strConfigIsNotAndDirNotWri"]);
  }
  willNotContinue();
}
else {
  if (!is_writable($configFilePath)) {
    printf('%s',$GLOBALS["strConfigIsNotWritable"]);
    willNotContinue();
  }
  if (filesize($configFilePath) > 1) {
//    printf('<br><br>%s<hr><a href="/lists/admin/?page=addfeature">%s</a>',$GLOBALS["strConfigHasContent"], $GLOBALS['addFeature']);
    printf('<br><br>%s',$GLOBALS["strConfigHasContent"]);
//    require_once dirname(__FILE__).'/accesscheck.php';
    willNotContinue();
  }
}

?>
</div>
<?php


if (isset($_SESSION['page'])) {
  $page = $_SESSION['page'];
  preg_match("/([\w_]+)/",$page,$regs);
  $page = $regs[1];
  if (!is_file('install/'.$page.'.php') ) {
    $page = 'home';
  }
  getNextPageForm($page);
}

else {
  $page = 'home';
  getNextPageForm($page);
}

checkScalarInt($_SESSION, $GLOBALS['requiredVars']);

?>
<?php
include('install/footer.inc');

ob_end_flush();

?>
