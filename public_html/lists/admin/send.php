<?
require_once "accesscheck.php";
$access = accessLevel("send");
print $access;
switch ($access) {
  case "owner":
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
    break;
  case "all":
    $subselect = "";break;
  case "none":
  default:
    $subselect = " where id = 0";break;
}

include "send_core.php";

if ($done) {
	return;
}

?>
<p>Please select the lists you want to send it to:
<ul>
<li><input type=checkbox name="list[all]" value=signup
<?
  if ($_POST["list"]["all"] == "signup")
    print "checked";
?>

>All Lists</li>

<?php

$result = Sql_Verbose_query("SELECT * FROM $tables[list] $subselect");
while ($row = Sql_fetch_array($result)) {
  print "<li><input type=checkbox name=list[".$row["id"] . "] value=signup ";
  if ($_POST["list"][$row["id"]] == "signup")
    print "checked";
  print ">".$row["name"];
  if ($row["active"])
    print " (<font color=red>List is Active</font>)";
  else
    print " (<font color=red>List is not Active</font>)";

  $desc = nl2br(StripSlashes($row["description"]));

  echo "<br>$desc</li>";
  $some = 1;
}

if (!$some)
  echo "Sorry there are currently no lists available";

?>
</ul>


<p><input type=submit name=send value="Send Message to the Selected Mailinglists">
</form>

