<?php
require_once "accesscheck.php";

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("list");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
      if ($id) {
        Sql_Query("select id from ".$tables["list"]. $subselect . " and id = $id");
        if (!Sql_Affected_Rows()) {
          Fatal_Error("You do not have enough priviliges to view this page");
          return;
        }
      }
      break;
    case "all":
      $subselect = "";break;
    case "none":
    default:
      if ($id) {
        Fatal_Error("You do not have enough priviliges to view this page");
        return;
      }
      $subselect = " where id = 0";
      break;
  }
}

if ($id)
  echo "<br />".PageLink2("members","Members of this list","id=$id");
echo "<hr />";

if (isset($save) && isset($listname) && $listname) {
  if ($GLOBALS["require_login"] && !isSuperUser())
    $owner = $_SESSION["logindetails"]["id"];
  if (!isset($active)) $active = 0;
  if (isset($id) && $id) {
    $query = sprintf('update %s set name="%s",description="%s",active=%d,listorder=%d,prefix = "%s", owner = %d, rssfeed = "%s" where id=%d',$tables["list"],addslashes($listname),addslashes($description),$active,$listorder,$prefix,$owner,$rssfeed,$id);
  } else {
    $query = sprintf('insert into %s 
    	(name,description,entered,listorder,owner,prefix,rssfeed,active)
    	values("%s","%s",now(),%d,%d,"%s","%s",%d)',
      $tables["list"],addslashes($listname),addslashes($description),
      $listorder,$owner,$prefix,$rssfeed,$active);
  }
#  print $query;
  $result = Sql_Query($query);
  if (!$id)
    $id = sql_insert_id();
  Redirect("list");
  echo "<br><font color=red size=+1>Record Saved: $id</font><br>";
}

if (isset($id)) {
  $result = Sql_Query("SELECT * FROM $tables[list] where id = $id");
  $list = Sql_Fetch_Array($result);
}
ob_end_flush();

?> 

<?=formStart()?>
<input type=hidden name=id value="<?php echo $id ?>">
<table border=0>
<tr><td>List name:</td><td><input type=text name="listname" value="<?php echo  htmlspecialchars(StripSlashes($list["name"]))?>"></td></tr>
<tr><td>Check this box to make this list active (listed)</td><td><input type=checkbox name=active value="1" <?php echo $list["active"] ? "checked" : ""; ?>></td></tr>
<tr><td>Order for listing</td><td><input type=text name="listorder" value="<? echo $list["listorder"] ?>" size=5></td></tr>
<!--tr><td>Subject Prefix</td><td><input type=text name="prefix" value="<? echo $list["prefix"] ?>" size=5></td></tr-->
<? if ($GLOBALS["require_login"] && (isSuperUser() || accessLevel("editlist") == "all")) {
  print '<tr><td>Owner</td><td><select name="owner">';
  $req = Sql_Query("select id,loginname from {$tables["admin"]} order by loginname");
  while ($row = Sql_Fetch_Array($req))
    printf ('<option value="%d" %s>%s</option>',$row["id"],$row["id"] == $list["owner"]? "selected":"",$row["loginname"]);
  print '</select></td></tr>';
}
if (ENABLE_RSS) {
 if ($list["rssfeed"]) {
 	 $validate = sprintf('(<a href="http://feedvalidator.org/check?url=%s" target="_blank">validate</a>)',$list["rssfeed"]);
	 $viewitems = PageLink2("viewrss&id=".$id,"View Items");
 }
 printf('<tr><td>RSS Source %s %s</td><td><input type=text name="rssfeed" value="%s" size=50></td></tr>',
 	 $validate,$viewitems,htmlspecialchars($list["rssfeed"]));
}

?>
<tr><td colspan=2>List Description</td></tr>
<tr><td colspan=2><textarea name=description cols=55 rows=15><?php echo htmlspecialchars(StripSlashes($list["description"])) ?></textarea></td></tr>
<tr><td align=center><input type=submit name=save value="Save"></td><td align=right><input type=reset></td></tr>
</table>
</form>