<?
require_once "accesscheck.php";


if (TEST)
  print "Default login is <b>admin</b>, with password <b>phplist</b>";
  
if ($_GET["page"]) {
	$page = $_GET["page"];
  if (!is_file($page.".php") || $page == "logout") {
  	$page = "home";
  }
} else {
	$page = "home";
}
?>
<font class="error"><?=$msg?></font>
<p>
<form method=post action="./">
<input type=hidden name="page" value="<?=$page?>">
<table width=100% border=0 cellpadding=2 cellspacing=0>
<tr><td><span class="general">Name:</span></td></tr>
<tr><td><input type=text name="login" value="" size=30></td></tr>

<tr><td><span class="general">Password:</span></td></tr>
<tr><td><input type=password name="password" value="" size=30></td></tr>

<tr><td><input type=submit name="process" value="Enter"></td></tr></table>

<br/>
<p align="center"><hr width=50% size=3></p>
<b>Forgot Password?:</b><br/>
Enter your email: <input type=text name="forgotpassword" value="" size=30><br/><br/>
<input type=submit name="process" value="Send Password">

</form>

