
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr />

<?php
require_once "accesscheck.php";

if (!$id) {
  print "Please select a message to display\n";
  exit;
}

if ($require_login && !isSuperUser()) {
  $access = accessLevel("message");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$logindetails["id"];
      break;
    case "all":
      $subselect = "";break;
    case "none":
    default:
      $subselect = " where id = 0";
      break;
  }
}

if ($resend && is_array($list)) {
  if ($list[all]) {
    $res = Sql_query("select * from $tables[list]");
    while($list = Sql_fetch_array($res))
      if ($list["active"])
        $result = Sql_query("insert into $tables[listmessage] (messageid,listid,entered) values($id,$list[id],now())");
  } else {
    while(list($key,$val)= each($list))
      if ($val == "signup")
        $result = Sql_query("insert into $tables[listmessage] (messageid,listid,entered) values($id,$key,now())");
  }
  Sql_Query("update $tables[message] set status = \"submitted\" where id = $id");
}


require $coderoot . "structure.php";
echo "<table border=1>";

$result = Sql_query("SELECT * FROM {$tables["message"]} where id = $id");
while ($msg = Sql_fetch_array($result)) {
  while (list($field,$val) = each($DBstruct["message"])) {
    printf('<tr><td valign="top">%s</td><td valign="top">%s</td></tr>',$field,$msg["htmlformatted"]?stripslashes($msg[$field]):nl2br(stripslashes($msg[$field])));
  }
}
if (ALLOW_ATTACHMENTS) {
	print "<tr><td colspan=2><h3>Attachments for this message</h3></td></tr>";
  $req = Sql_Query("select * from {$tables["message_attachment"]},{$tables["attachment"]}
  	where {$tables["message_attachment"]}.attachmentid = {$tables["attachment"]}.id and
    {$tables["message_attachment"]}.messageid = $id");
  if (!Sql_Affected_Rows())
  	print '<tr><td colspan=2>No attachments</td></tr>';
  while ($att = Sql_Fetch_array($req)) {
  	printf ('<tr><td>Filename:</td><td>%s</td></tr>',$att["remotefile"]);
  	printf ('<tr><td>Size:</td><td>%s</td></tr>',formatBytes($att["size"]));
  	printf ('<tr><td>Mime Type:</td><td>%s</td></tr>',$att["mimetype"]);
  	printf ('<tr><td>Description:</td><td>%s</td></tr>',$att["description"]);
 	}
 # print '</table>';
}

print "<tr><td colspan=2><h3>Lists this message has been sent to:</h3></td></tr>";

$lists_done = array();
$result = Sql_Query("select $tables[list].name,$tables[list].id from $tables[listmessage],$tables[list] where $tables[listmessage].messageid = $id and $tables[listmessage].listid = $tables[list].id");
if (!Sql_Affected_Rows())
  print '<tr><td colspan=2>None yet</td></tr>';
while ($lst = Sql_fetch_array($result)) {
  array_push($lists_done,$lst[id]);
  printf ('<tr><td>%d</td><td>%s</td></tr>',$lst["id"],$lst["name"]);
}

?>
</table>

<a name="resend"></a><p>Send this (same) message to (a) new list(s):</P>
<?=formStart()?>
<input type=hidden name="id" value="<?=$id?>">
<ul>
<?php

$result = Sql_query("SELECT * FROM $tables[list] $subselect");
while ($row = Sql_fetch_array($result)) {
  if (!in_array($row[id],$lists_done)) {
    print "<li><input type=checkbox name=list[".$row["id"] . "] value=signup ";
    if ($list[$row["id"]] == "signup")
      print "checked";
    print ">".$row["name"];
    if ($row["active"])
      print " (<font color=red>List is Active</font>)";
    else
      print " (<font color=red>List is not Active</font>)";
    $some = 1;
  }
}
if (!$some)
  print '<b>Note:</b> this message has already been sent to all lists. To resend it to new users use the "Requeue" function.';
else
  print '<br /><input type=submit name="resend" value="Resend"></form>';

?>


