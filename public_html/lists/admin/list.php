
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr><p>


<?php
require_once "accesscheck.php";

print formStart();

if (isset($delete)) {
  # delete the index in delete
  print "Deleting $delete ..\n";
  $result = Sql_query("delete from $tables[list] where id = $delete");
  $result = Sql_query("delete from $tables[listuser] where listid = $delete");
  $result = Sql_query("delete from $tables[listmessage] where listid = $delete");
  print "..Done<br><hr><br>\n";
}

if (is_array($listorder))
	while (list($key,$val) = each ($listorder))
	Sql_Query(sprintf('update %s set listorder = %d, active = %d where id = %d',
		$tables["list"],$val,$active[$key],$key));

$access = accessLevel("list");
switch ($access) {
  case "owner":
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];break;
  case "all":
    $subselect = "";break;
  case "none":
  default:
    $subselect = " where id = 0";break;
}

$html = '';
$result = Sql_query("SELECT * FROM $tables[list] $subselect order by listorder");
while ($row = Sql_fetch_array($result)) {
	$count = Sql_Fetch_Row_Query("select count(*) from {$tables["listuser"]} where listid = {$row["id"]} ");
  $desc = stripslashes($row["description"]);
  if ($row["rssfeed"]) {
    $feed = $row["rssfeed"];
    # reformat string, so it wraps if it's very long
    $feed = ereg_replace("/","/ ",$feed);
    $feed = ereg_replace("&","& ",$feed);
  	$desc = sprintf('RSS source: <a href="%s" target="_blank">%s</a><br/> ',$row["rssfeed"],$feed).
    PageLink2("viewrss&id=".$row["id"],"(View Items)").'<br/>'.
    $desc;
  }
  $html .= sprintf('<tr><td valign=top>%d</td><td valign=top><b>
    %s</b><br/>%d members</td><td valign=top><input type=text name="listorder[%d]" value="%d" size=5></td>
		<td valign=top>%s | %s | <a href="javascript:deleteRec(\'%s\');">delete</a></td>
		<td valign=top><input type=checkbox name="active[%d]" value="1" %s></td>
		<td valign=top>%s</td></tr><tr><td>&nbsp;</td><td colspan=5>%s</td></tr><tr><td colspan=6><hr width=50%% size=4></td></tr>',
    $row["id"],$row["name"],$count[0],
		$row["id"],$row["listorder"],
    PageLink2("editlist","edit","id=".$row["id"]),
    PageLink2("members","view members","id=".$row["id"]),
    PageURL2("list","","delete=".$row["id"]),
		$row["id"],
    $row["active"] ? "checked" : "",$GLOBALS["require_login"] ?
    adminName($row["owner"]):"n/a",
    $desc
    );
  $some = 1;
}

if (!$some)
  echo "No lists available, use Add to add one";
else
	echo '<table border=0><tr><td>No</td><td>Name</td><td>Order</td><td>Functions</td><td>
		Active</td><td>Owner</td><td>'.$html .
		'<tr><td colspan=6 align=center><input type=submit name="update" value="Save Changes"></td></tr></table>';

?>

</ul>
</form>
<p><?

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $numlists = Sql_Fetch_Row_query("select count(*) from {$tables["list"]} where owner = ".$_SESSION["logindetails"]["id"]);
  if ($numlists[0] < MAXLIST) {
    print PageLink2("editlist","Add a list");
  }
} else {
  print PageLink2("editlist","Add a list");
}

