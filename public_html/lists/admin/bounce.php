
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<?php
require_once "accesscheck.php";


if (!$id && !$delete)
  Fatal_Error("No such record");

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("bounce");
  switch ($access) {
    case "all":
      $subselect = "";break;
    case "none":
    default:
      $subselect = " and ".$tables["list"].".id = 0";break;
  }
} 
if (isset($start))
  echo "<br />".PageLink2("bounces","Back to the list of bounces","start=$start")."\n";

echo "<hr />$delete_message";


if (isset($doit) && (($GLOBALS["require_login"] && isSuperUser()) || !$GLOBALS["require_login"])) {
	if ($useremail) {
  	$req = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"',
    	$tables["user"],$useremail));
   	$userid = $req[0];
    if (!$userid) {
    	print "$useremail => Not Found<br/>\n";
    }
	}
  if ($userid && $amount) {
  	Sql_Query(sprintf('update %s set bouncecount = bouncecount + %d where id = %d',
    	$tables["user"],$amount,$userid));
   	if (Sql_Affected_Rows()) {
    	print "Added $amount to bouncecount for user $userid<br/>\n";
    } else {
    	print "Could not add $amount to bouncecount for user $userid<br/>\n";
    }
  }
  
  if ($userid && $unconfirm) {
  	Sql_Query(sprintf('update %s set confirmed = 0 where id = %d',
    	$tables["user"],$userid));
   	print "Made user $userid unconfirmed<br/>";
  }

  if ($userid && $maketext) {
  	Sql_Query(sprintf('update %s set htmlemail = 0 where id = %d',
    	$tables["user"],$userid));
   	print "Made user $userid to receive text<br/>";
  }

  if ($userid && $deleteuser) {
  	deleteUser($userid);
    print "Deleted user $userid<br/>\n";
  }

  if ($deletebounce) {
    print "Deleting bounce $id ..\n";
    Sql_query("delete from {$tables["bounce"]} where id = $id");
    print "..Done, loading next bounce..<br /><hr><br />\n";
    print PageLink2("bounces","Back to the list of bounces");
    $next = Sql_Fetch_Row_query(sprintf('select id from %s where id > %d',$tables["bounce"],$id));
    $id = $next[0];
    if (!$id) {
      $next = Sql_Fetch_Row_query(sprintf('select id from %s order by id desc limit 0,5',$tables["bounce"],$id));
      $id = $next[0];
    }
  }
}

if ($id) {
  $result = Sql_query("SELECT * FROM {$tables["bounce"]} where id = $id");
  if (!Sql_Affected_Rows())
    Fatal_Error("No such Record");
  $bounce = sql_fetch_array($result);
 #printf( "<br /><li><a href=\"javascript:deleteRec('%s');\">Delete</a>\n",PageURL2("bounce","","delete=$id"));
  if (preg_match("#([\d]+) bouncecount increased#",$bounce["comment"],$regs)) {
  	$guessedid = $regs[1];
    $emailreq = Sql_Fetch_Row_Query(sprintf('select email from %s where id = %d',
    	$tables["user"],$guessedid));
    $guessedemail = $emailreq[0];
  }

 	print '<form method=get>';
  print '<input type=hidden name=page value="'.$page.'">';
  print '<input type=hidden name=id value="'.$id.'">';
  print '<table><tr><td>Possible Actions:</td></tr>';
  print '<tr><td>For user with email</td><td><input type=text name="useremail" value="'.$guessedemail.'" size=35></td></tr>';
  print '<tr><td>Increase bouncecount with</td><td><input type=text name=amount value="1" size=5> (use negative numbers to decrease)</td></tr>';
  print '<tr><td>Mark user as unconfirmed </td><td><input type=checkbox name=unconfirm value="1"> (so you can resend the request for confirmation)</td></tr>';
  print '<tr><td>Set user to receive text instead of HTML </td><td><input type=checkbox name=maketext value="1"></td></tr>';
  print '<tr><td>Delete user </td><td><input type=checkbox name=deleteuser value="1"></td></tr>';
  print '<tr><td>Delete this bounce and go to the next </td><td><input type=checkbox name=deletebounce value="1" checked></td></tr>';
  print '<tr><td><input type=submit name="doit" value="Do the above"></td></tr>';
	print "</table></form>";
  print "<p>Bounce Details:<table border=1>";
  printf ('
  <tr><td valign="top">ID</td><td valign="top">%d</td></tr>
  <tr><td valign="top">Date</td><td valign="top">%s</td></tr>
  <tr><td valign="top">Status</td><td valign="top">%s</td></tr>
  <tr><td valign="top">Comment</td><td valign="top">%s</td></tr>
  <tr><td valign="top">Header</td><td valign="top">%s</td></tr>
  <tr><td valign="top">Body</td><td valign="top">%s</td></tr>',$id,
  $bounce["date"],$bounce["status"],$bounce["comment"],
  nl2br(htmlspecialchars($bounce["header"])),nl2br(htmlspecialchars($bounce["data"])));
#   print '<tr><td colspan=2><input type=submit name=change value="Save Changes">';
	print '</table>';
}
?>


