<?php
require_once "accesscheck.php";

$_SESSION["adminloggedin"] = "";
$_SESSION["logindetails"] = "";
session_unregister("adminloggedin");
session_unregister("logindetails");
session_destroy();
?>

