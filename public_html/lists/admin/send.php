<?php
require_once "accesscheck.php";

$access = accessLevel("send");
switch ($access) {
  case "owner":
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
    $ownership = ' and owner = '.$_SESSION["logindetails"]["id"];
    break;
  case "all":
    $subselect = "";break;
  case "none":
  default:
    $subselect = " where id = 0";
    $ownership = " and id = 0";
    break;
}

# handle commandline
if ($GLOBALS["commandline"]) {
#  error_reporting(63);
  $cline = parseCline();
  reset($cline);
  if (!$cline || !is_array($cline) || !$cline["s"] || !$cline["l"]) {
    clineUsage("-s subject -l list [-f from] < message");
    exit;
  }

	$listnames = explode(" ",$cline["l"]);
  $listids = array();
  foreach ($listnames as $listname) {
  	if (!is_numeric($listname)) {
      $listid = Sql_Fetch_Array_Query(sprintf('select * from %s where name = "%s"',
        $tables["list"],$listname));
      if ($listid["id"]) {
        $listids[$listid["id"]] = $listname;
      }
   	} else {
      $listid = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',
        $tables["list"],$listname));
      if ($listid["id"]) {
	    	$listids[$listid["id"]] = $listid["name"];
    	}
    }
  }

  $_POST["list"] = array();
  foreach ($listids as $key => $val) {
  	$_POST["list"][$key] = "signup";
    $lists .= '"'.$val.'"' . " ";
  }

  if ($cline["f"]) {
	  $_POST["from"] = $cline["f"];
  } else {
  	$_POST["from"] = getConfig("message_from_name") . ' '.getConfig("message_from_address");
  }
  $_POST["subject"] = $cline["s"];
  $_POST["send"] = "1";
  $_POST["footer"] = getConfig("messagefooter");
  while (!feof (STDIN)) {
    $_POST["message"] .= fgets(STDIN, 4096);
  }

#  print clineSignature();
#  print "Sending message with subject ".$_POST["subject"]. " to ". $lists."\n";
}

include "send_core.php";

if ($done) {
	if ($GLOBALS["commandline"]) {
  	ob_end_clean();
    print clineSignature();
    print "Message with subject ".$_POST["subject"]. " was sent to ". $lists."\n";
    exit;
  }
	return;
}

?>
<p>Please select the lists you want to send it to:
<ul>
<li><input type=checkbox name="list[all]" value=signup
<?php
  if ($_POST["list"]["all"] == "signup")
    print "checked";
?>

>All Lists</li>

<?php

$result = Sql_query("SELECT * FROM $tables[list] $subselect");
while ($row = Sql_fetch_array($result)) {
  # check whether this message has been marked to send to a list (when editing)
  $checked = 0;
  if ($_GET["id"]) {
    $sendtolist = Sql_Query(sprintf('select * from %s where
      messageid = %d and listid = %d',$tables["listmessage"],$_GET["id"],$row["id"]));
    $checked = Sql_Affected_Rows();
  }
  print "<li><input type=checkbox name=list[".$row["id"] . "] value=signup ";
  if ($checked || $_POST["list"][$row["id"]] == "signup")
    print "checked";
  print ">".stripslashes($row["name"]);
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

