<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<?php
require_once "accesscheck.php";

function niceDateTime($datetime) {
  $year = substr($datetime,0,4);
  $month = substr($datetime,4,2);
  $day = substr($datetime,6,2);
#  $day = ereg_replace("^0","",$day);
  $hr = substr($datetime,8,2);
  $min = substr($datetime,10,2);

	return date("D M j G:i T Y",mktime($hr,$min,0,$month,$day,$year));
}

if (!$_GET["id"]) {
  Fatal_Error("No such user");
  return;
}

$access = accessLevel("user");
switch ($access) {
  case "owner":
    $subselect = " and ".$tables["list"].".owner = ".$_SESSION["logindetails"]["id"];break;
  case "all":
    $subselect = "";break;
  case "view":
    $subselect = "";
    if (sizeof($_POST)) {
      print Error("You only have privileges to view this page, not change any of the information");
      return;
    }
    break;
  case "none":
  default:
    $subselect = " and ".$tables["list"].".id = 0";break;
}

$result = Sql_query("SELECT * FROM {$tables["user"]} where id = $id");
if (!Sql_Affected_Rows()) {
  Fatal_Error("No such User");
  return;
}
$user = sql_fetch_array($result);
print '<h2>User '.PageLink2("user&id=".$user["id"],$user["email"]).'</h2>';

$bouncels = new WebblerListing("Bounces");
$bouncelist = "";
$bounces = array();
# check for bounces
$req = Sql_Query(sprintf('select * from %s where user = %d',$tables["user_message_bounce"],$user["id"]));
if (Sql_Affected_Rows()) {
  while ($row = Sql_Fetch_Array($req)) {
    $bouncels->addElement($row["bounce"],PageURL2("bounce","View","id=".$row["bounce"]));
    $bouncels->addColumn($row["bounce"],"msg",$row["message"]);
    $bouncels->addColumn($row["bounce"],"time",niceDateTime($row["time"]));
    $bounces[$row["message"]] = niceDateTime($row["time"]);
  }
}

$ls = new WebblerListing("Messages");
$msgs = Sql_Query(sprintf('select * from %s where userid = %d',$tables["usermessage"],$user["id"]));
$num = Sql_Affected_Rows();
printf('%d messages sent to this user<br/>', $num);
if ($num) {
  while ($msg = Sql_Fetch_Array($msgs)) {
  	$ls->addElement($msg["messageid"],PageURL2("message","View","id=".$msg["messageid"]));
    $ls->addColumn($msg["messageid"],"sent",niceDateTime($msg["entered"]));
    $ls->addColumn($msg["messageid"],"bounce",$bounces[$msg["messageid"]]);
  }
}

print $ls->display();
print $bouncels->display();
print "<h3>User Information</h3>";
$ls = new WebblerListing("Subscription History");
$req = Sql_Verbose_Query(sprintf('select * from %s where userid = %d order by date desc',$tables["user_history"],$user["id"]));
while ($row = Sql_Fetch_Array($req)) {
	$ls->addElement($row["id"]);
  $ls->addColumn($row["id"],"ip",$row["ip"]);
  $ls->addColumn($row["id"],"date",$row["date"]);
  $ls->addColumn($row["id"],"summary",$row["summary"]);
  $ls->addRow($row["id"],"detail",nl2br(htmlspecialchars($row["detail"])));
  $ls->addRow($row["id"],"info",nl2br($row["systeminfo"]));
}

print $ls->display();
?>