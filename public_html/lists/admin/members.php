
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<hr>


<?php
require_once "accesscheck.php";

$access = accessLevel("members");

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
  case "view":
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

function addUserForm ($listid) {
  $html = formStart().'<input type=hidden name=listid value="'.$listid.'">
  Add a user: <input type=text name=new value="" size=40><input type=submit
 name=add value="Add">
  </form>';
  return $html;
}

if (isset($id)) {
	print "<h3>Members of ".ListName($id)."</h3>";
  echo "<br />".PageLink2("editlist","back to this list","id=$id");
  echo "<br />".PageLink2("export&list=$id","Download users on this list as a CSV
 file");
  print addUserForm($id);
} else {
  Fatal_Error("Please enter a listid");
}

if (isset($processtags) && $access != "view") {
	print "Processing .... <br/>";
	if ($tagaction && is_array($user)) {
		switch ($tagaction) {
			case "move":
				$cnt = 0;
				while (list($key,$val) = each ($user)) {
					Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
						$key");
					Sql_query("replace into {$tables["listuser"]} (listid,userid)
						values($movedestination,$key)");
					if (Sql_Affected_rows() == 1) # 2 means they were already on the list
						$cnt++;
				}
				$msg = $cnt .' users were moved to '.listName($movedestination);
				break;
			case "copy":
				$cnt = 0;
				while (list($key,$val) = each ($user)) {
					Sql_query("replace into {$tables["listuser"]} (listid,userid)
						values($copydestination,$key)");
          $cnt++;
				}
				$msg = $cnt .' users were copied to '.listName($copydestination);
				break;
			case "delete":
				$cnt = 0;
				while (list($key,$val) = each ($user)) {
					Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
						$key");
					if (Sql_Affected_rows())
						$cnt++;
				}
				$msg = $cnt.' users were deleted from this list';
				break;
			default: # do nothing
				break;
		}
	}

	if ($tagaction_all != "nothing") {
		$req = Sql_Query(sprintf('select userid from %s where listid = %d',$tables["listuser"],$id));
		switch ($tagaction_all) {
			case "move":
				$cnt = 0;
				while ($user = Sql_Fetch_Row($req)) {
					Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
						$user[0]");
					Sql_query("replace into {$tables["listuser"]} (listid,userid)
						values($movedestination_all,$user[0])");
					if (Sql_Affected_rows() == 1) # 2 means they were already on the list
						$cnt++;
				}
				$msg = $cnt . ' users were moved to '.listName($movedestination_all);
				break;
			case "copy":
				$cnt = 0;
				while ($user = Sql_Fetch_Row($req)) {
					Sql_query("replace into {$tables["listuser"]} (listid,userid)
						values($copydestination_all,$user[0])");
					$cnt++;
				}
				$msg = $cnt .' users were copied to '.listName($copydestination_all);
				break;
			case "delete":
				Sql_Query(sprintf('delete from %s where listid = %d',$tables["listuser"],$id));
				$msg = Sql_Affected_Rows().' users were deleted from this list';
				break;
			default: # do nothing
		}
	}
	print '<font class="info">'.$msg.'</font><br/>';
}

if (isset($add)) {
  if ($new) {
    $result = Sql_query("select * from {$tables["user"]} where email = \"$new\"");
    if (Sql_affected_rows()) {
      print "<p>Users found, click add to add this user:<br /><ul>\n";
      while ($user = Sql_fetch_array($result)) {
        printf ("<li>[ ".PageLink2("members","Add","add=1&id=$id&doadd=".$user["id"])." ] %s <br />\n",
 $user["email"]);
      }
      print "</ul>\n";
    } else {
      print '<p>No user found with that email</p><table>'.formStart();

      require $GLOBALS["coderoot"] . "subscribelib2.php";
      ?>
      <?php 
      # pass the entered email on to the form
      $_REQUEST["email"] = $_POST["new"];
  /*      printf('
        <tr><td><div class="required">%s</div></td>
        <td class="attributeinput"><input type=text name=email value="%s" size="%d">
        <script language="Javascript" type="text/javascript">addFieldToCheck("email","%s");</script></td></tr>',
        $strEmail,$email,$textlinewidth,$strEmail);
  */
        print ListAllAttributes();
      ?>

      <tr><td colspan=2><input type=hidden name=action value="insert"><input
 type=hidden name=doadd value="yes"><input type=hidden name=id value="<?php echo
 $id ?>"><input type=submit name=subscribe value="Add User"></form></td></tr></table>
      <?php
      return;
    }
  }
}
if ($doadd) {
  if ($action == "insert") {
    print "Inserting user $email";
    $result = Sql_query(sprintf('
	    insert into %s (email,entered,confirmed,htmlemail,uniqid)
 			values("%s",now(),1,%d,"%s")',
			$tables["user"],$email,$htmlemail,getUniqid()));
    $userid = Sql_insert_id();
    $query = "insert into $tables[listuser] (userid,listid,entered)
 values($userid,$id,now())";
    $result = Sql_query($query);
    # remember the users attributes
    $res = Sql_Query("select * from $tables[attribute]");
    while ($row = Sql_Fetch_Array($res)) {
      $fieldname = "attribute" .$row["id"];
      $value = $_POST[$fieldname];
      if (is_array($value)) {
        $newval = array();
        foreach ($value as $val) {
          array_push($newval,sprintf('%0'.$checkboxgroup_storesize.'d',$val));
        }
        $value = join(",",$newval);
      }
      Sql_Query(sprintf('replace into %s (attributeid,userid,value) values("%s","%s","%s")',
        $tables["user_attribute"],$row["id"],$userid,$value));
    }
  } else {
    $query = "replace into $tables[listuser] (userid,listid,entered)
 values($doadd,$id,now())";
    $result = Sql_query($query);
  }
  echo "<br /><font color=red size=+2>User added</font><br />";
}

if (isset($delete)) {
  # single delete the index in delete
  print "Deleting $delete ..\n";
  $query = "delete from {$tables["listuser"]} where listid = $id and userid = $delete";
  $result = Sql_query($query);
  print "..Done<br />\n";
	Redirect("members&id=$id");
}

if (isset($id)) {
  $result = Sql_query("SELECT count(*) FROM {$tables["listuser"]},{$tables["user"]}
		where listid = $id and userid = {$tables["user"]}.id");
  $row = Sql_Fetch_row($result);
  $total = $row[0];
  print "$total Users on this list<p>";
  if ($total > MAX_USER_PP) {
    if (isset($start) && $start) {
      $listing = "Listing user $start to " . ($start + MAX_USER_PP);
      $limit = "limit $start,".MAX_USER_PP;
    } else {
      $listing = "Listing user 1 to 50";
      $limit = "limit 0,50";
      $start = 0;
    }
  printf ('<table border=1><tr><td colspan=4 align=center>%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><p><hr>',
          $listing,
          PageLink2("members","&lt;&lt;","start=0&id=$id"),
          PageLink2("members","&lt;",sprintf('start=%d&id=%d',max(0,$start-MAX_USER_PP),$id)),
          PageLink2("members","&gt;",sprintf('start=%d&id=%d',min($total,$start+MAX_USER_PP),$id)),
          PageLink2("members","&gt;&gt;",sprintf('start=%d&id=%d',$total-MAX_USER_PP,$id)));

  }
	$result = Sql_query("SELECT $tables[user].id,email,confirmed,rssfrequency FROM
		{$tables["listuser"]},{$tables["user"]} where {$tables["listuser"]}.listid = $id and
		{$tables["listuser"]}.userid = {$tables["user"]}.id order by confirmed desc,email $limit");
  print formStart('name=users');
	printf('<input type=hidden name="id" value="%d">',$id);
  ?>
  <script language="Javascript" type="text/javascript">
  function checkAll() {
    for (i=0;i<document.users.length;i++) {
       document.users.elements[i].checked = document.users.checkall.checked;
    }
  }
  </script>
  <input type=checkbox name="checkall" onClick="checkAll()">Tag all users in this page
  <?php

	$ls = new WebblerListing("Members");
  while ($user = Sql_fetch_array($result)) {
 		$ls->addElement($user["email"],PageUrl2("user&id=".$user["id"]));
		$ls->addColumn($user["email"],"confirmed",$user["confirmed"]?$GLOBALS["img_tick"]:$GLOBALS["img_cross"]);
    if ($access != "view")
		$ls->addColumn($user["email"],"tag",sprintf('<input type=checkbox name="user[%d]" value="1">',$user["id"]));
    if ($access != "view")
 		$ls->addColumn($user["email"],"del",
			sprintf('<a href="javascript:deleteRec(\'%s\');">Delete</a>',
				PageURL2("members","","start=$start&id=$id&delete=".$user["id"])));
		$msgcount = Sql_Fetch_Row_Query("select count(*) from {$tables["listmessage"]},{$tables["usermessage"]}
			where {$tables["listmessage"]}.listid = $id and {$tables["listmessage"]}.messageid = {$tables["usermessage"]}.messageid
			and {$tables["usermessage"]}.userid = {$user["id"]}");
		$ls->addColumn($user["email"],"# msgs",$msgcount[0]);
		if (ENABLE_RSS) {
			$msgcount = Sql_Fetch_Row_Query("select count(*) from {$tables["rssitem"]},{$tables["rssitem_user"]}
				where {$tables["rssitem"]}.list = $id and {$tables["rssitem"]}.id = {$tables["rssitem_user"]}.itemid and
				{$tables["rssitem_user"]}.userid = {$user["id"]}");
			$ls->addColumn($user["email"],"# rss",$msgcount[0]);
			if ($user["rssfrequency"])
				$ls->addColumn($user["email"],"rss freq",$user["rssfrequency"]);
			$last = Sql_Fetch_Row_Query("select last from {$tables["user_rss"]} where userid = ".$user["id"]);
			if ($last[0])
				$ls->addColumn($user["email"],"last sent",$last[0]);
		}
  }
	print $ls->display();
}

if ($access == "view") return;
?>

<hr>
<table>
<tr><td colspan=2><h3>What to do with "Tagged" users:</h3>This will only process the users in this page that have the "Tag" checkbox checked</td></tr>
<tr><td colspan=2>Delete (from this list) <input type=radio name="tagaction"
 value="delete"></td></tr>
<?php

$res = Sql_Query("select id,name from {$tables["list"]} $subselect");
while ($row = Sql_Fetch_array($res)) {
  if ($row["id"] != $id)
    $html .= sprintf('<option value="%d">%s',$row["id"],$row["name"]);
}
if ($html) {
?>
  <tr><td>Move <input type=radio name="tagaction" value="move"> </td><td>to
 <select name=movedestination>
  <?php echo $html ?>
</select></td></tr>
  <tr><td>Copy <input type=radio name="tagaction" value="copy"> </td><td>to
 <select name=copydestination>
  <?php echo $html ?>
</select></td></tr>
<tr><td colspan=2>Nothing <input type=radio name="tagaction"
 value="nothing" checked></td></tr>
<?php } ?>
<tr><td colspan=2><hr></td></tr>
<tr><td colspan=2><h3>What to do with all users</h3>This will process all users on this list</td></tr>
<tr><td colspan=2>Delete (from this list) <input type=radio name="tagaction_all"
 value="delete"></td></tr>
<?php


if ($html) {
?>
  <tr><td>Move <input type=radio name="tagaction_all" value="move"> </td><td>to
 <select name="movedestination_all">
  <?php echo $html ?>
</select></td></tr>
  <tr><td>Copy <input type=radio name="tagaction_all" value="copy"> </td><td>to
 <select name="copydestination_all">
  <?php echo $html ?>
</select></td></tr>
<tr><td colspan=2>Nothing <input type=radio name="tagaction_all"
 value="nothing" checked></td></tr>
<?php } ?>

<tr><td colspan=2><input type=submit name=processtags value="Do it"></td></tr>
</table>
</form>

