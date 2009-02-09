<?php
require("installer/lib/install-texts.inc");
require("installer/lib/mysql.inc");
require("installer/lib/steps-lib.inc");
require("languages.php");


@session_start();

$_SESSION["installType"] = (!$itype)?"BASIC":$itype;

include("omar/lib/header.inc");

if ($page == ""){
   include("installer/home.php");
}
else{
   if ($page != "end") echo breadcrumb($page);
   include("installer/install$page.php");
}

include('installer/lib/footer.inc');
?>
