
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr>

<?php
require_once "accesscheck.php";


print PageLink2("admin","Add new admin","start=$start".$remember_find);

if (isset($delete)) {
  # delete the index in delete
  print "Deleting $delete ..\n";
  Sql_query("delete from {$tables["admin"]} where id = $delete");
  Sql_query("delete from {$tables["admin_attribute"]} where adminid = $delete");
  Sql_query("delete from {$tables["admin_task"]} where adminid = $delete");
  print "..Done<br><hr><br>\n";
  Redirect("admins&start=$start");
}

ob_end_flush();

if (isset($add)) {
  if (isset($new)) {
    $query = "insert into ".$tables["admin"]." (email,entered) values(\"$new\",now())";
    $result = Sql_query($query);
    $userid = Sql_insert_id();
    $query = "insert into ".$tables["listuser"]." (userid,listid,entered) values($userid,$id,now())";
    $result = Sql_query($query);
  }
  echo "<br><font color=red size=+2>User added</font><br>";
}

if (!$find)
  $result = Sql_query("SELECT count(*) FROM ".$tables["admin"]);
else
  $result = Sql_query("SELECT count(*) FROM ".$tables["admin"]." where loginname like \"%$find%\" or email like \"%$find%\"");
$totalres = Sql_fetch_Row($result);
$total = $totalres[0];

print "<p>$total Administrators";
print $find ? " found</p>": "</p>";
if ($total > MAX_USER_PP) {
  if (isset($start) && $start) {
    $listing = "Listing admin $start to " . ($start + MAX_USER_PP);
    $limit = "limit $start,".MAX_USER_PP;
  } else {
    $listing = "Listing admin 1 to 50";
    $limit = "limit 0,50";
    $start = 0;
  }
  printf ('<table border=1><tr><td colspan=4 align=center>%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><p><hr>',
          $listing,
          PageLink2("admins","&lt;&lt;","start=0"),
          PageLink2("admins","&lt;",sprintf('start=%d',max(0,$start-MAX_USER_PP))),
          PageLink2("admins","&gt;",sprintf('start=%d',min($total,$start+MAX_USER_PP))),
          PageLink2("admins","&gt;&gt;",sprintf('start=%d',$total-MAX_USER_PP)));
  if ($find)
    $result = Sql_query("SELECT id,loginname,email FROM ".$tables["admin"]." where loginname like \"%$find%\" or email like \"%$find%\" order by loginname $limit");
  else
    $result = Sql_query("SELECT id,loginname,email FROM ".$tables["admin"]." order by loginname $limit");
} else {
  if ($find)
    $result = Sql_query("select id,loginname,email from ".$tables["admin"]." where loginname like \"%$find%\" or email like \"%$find%\" order by loginname");
  else
    $result = Sql_query("select id,loginname,email from ".$tables["admin"]." order by loginname");
}

?>
<table>
<tr><td colspan=4><?php echo formStart()?><input type=hidden name=id value="<?=$listid?>">
Find an admin: <input type=text name=find value="<?php echo $find?>" size=40><input type=submit value="Go">
</form></td></tr></table>
<?php
$ls = new WebblerListing("Administrators");
while ($admin = Sql_fetch_array($result)) {
  if ($find)
    $remember_find = "&find=".urlencode($find);
  $delete_url = sprintf("<a href=\"javascript:deleteRec('%s');\">del</a>",PageURL2("admins","Delete","start=$start&delete=".$admin["id"]));
  $ls->addElement($admin["loginname"],PageUrl2("admin","Show","start=$start&id=".$admin["id"].$remember_find));
  $ls->addColumn($admin["loginname"],"del",$delete_url);
}
print $ls->display();
print '<br/><hr/>';
print PageLink2("admin","Add new admin","start=$start".$remember_find);
print '<p>'.PageLink2("importadmin","Import list of admins").'</p>';

?>
