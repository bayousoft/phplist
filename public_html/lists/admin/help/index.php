<?
ob_start();
if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
	print '<!-- using '.$_SERVER["ConfigFile"].'-->'."\n";
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
	print '<!-- using '.$_ENV["CONFIG"].'-->'."\n";
  include $_ENV["CONFIG"];
} elseif (is_file("../../config/config.php")) {
	print '<!-- using ../../config/config.php-->'."\n";
  include "../../config/config.php";
} else {
	print "Error, cannot find config file\n";
  exit;
}
include "../pagetop.php";
if (!isset($_GET["topic"]))
  $topic = "home";
else
  $topic = $_GET["topic"];

preg_match("/([\w_]+)/",$topic,$regs);
$topic = $regs[1];

if ($topic) {
	if (is_file($adminlanguage["iso"].'/'.$topic.".php")) {
		$include = $adminlanguage["iso"].'/'.$topic . ".php";
  }
} else {
  $include = "";
}

?>
<HTML>
<HEAD>
<TITLE>help</TITLE>
<link rel="stylesheet" type="text/css" href="../styles/styles_help.css"></HEAD>
<BODY>
<!-- content -->
<?
print "<table width=100%><tr><td valign=top><h3>PHPlist Help: $topic</h3></td><td align=right valign=top>";
print '<A HREF="Javascript:close()">Close this window</A></td></tr></table>';
if ($include) {
	include $include;
} else {
	print "Sorry, help for this topic (<i>$topic</i>) is not available yet";
}
 ?>
<table><tr><td>
<?

ob_end_flush();
 ?>

</td></tr></table>
<HR WIDTH="75%">
<A HREF="Javascript:close()">Close this window</A>
</BODY></HTML>

