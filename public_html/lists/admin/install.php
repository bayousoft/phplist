<?php
require("installer/lib/install-texts.inc");
//require("installer/lib/mysql.inc");
require("mysql.inc");
require("installer/lib/steps-lib.inc");
require("languages.php");


@session_start();


// Trata de tomar el paso de la instalacion por POST, sino tre nada, lo intenta por GET
$page     = (isset($_POST["page"]))?$_POST["page"]:"";
$submited = (isset($_POST["submited"]))?$_POST["submited"]:"";
$itype    = (isset($_GET["itype"]))?$_GET["itype"]:"";
$inTheSame= 0;

if (is_file(dirname(__FILE__) .'/../../../VERSION')) {
  $fd = fopen (dirname(__FILE__) .'/../../../VERSION', "r");
  while ($line = fscanf ($fd, "%[a-zA-Z0-9,. ]=%[a-zA-Z0-9,. ]")) {
    list ($key, $val) = $line;
    if ($key == "VERSION")
      $version = $val . "-";
  }
  fclose($fd);
} else {
  $version = "dev";
}


$_SESSION["installType"] = (!$itype)?"BASIC":$itype;
define("VERSION",$version.'dev');

if (!defined("NAME")) define("NAME",'phplist');

if (!isset($page) || $page == ""){
   $page     = (isset($_GET["page"]))?$_GET["page"]:"";
   $submited = (isset($_GET["submited"]))?$_GET["submited"]:"";
}


///// Variable usada para controlar que lo ingresado como $page sea numerico
$control = ($page * 1)."";


//include("installer/lib/header.inc");
include("header.inc");

if (!isset($page) || $page == "" || ($control != $page)){
   $page = ($control != $page)?"":$page;

   include("installer/home.php");
}
else{
   echo breadcrumb($page);
   include("installer/install$page.php");
}

//include('installer/lib/footer.inc');
include('footer.inc');
?>
