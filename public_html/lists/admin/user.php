
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

if (!$_GET["id"] && !$_GET["delete"]) {
  Fatal_Error("No such user");
  return;
}
$id = sprintf('%d',$_GET["id"]);
$delete = sprintf('%d',$_GET["delete"]);

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
if ($access == "all") {
  $delete_message = '<br />Delete will delete user from the list<br />';
} else {
  $delete_message = '<br />Delete will delete user and all listmemberships<br />';
}

require $GLOBALS["coderoot"] . "structure.php";
$struct = $DBstruct["user"];

if ($list) 
  echo "<br />".PageLink2("members","Back to Members of this list","id=$list")."\n";
if (isset($start))
  echo "<br />".PageLink2("users","Back to the list of users","start=$start&unconfirmed=".$_GET["unconfirmed"])."\n";
if ($find)
  echo "<br />".PageLink2("users","Back to the search results","start=$start&find=".urlencode($find)."&findby=".urlencode($findby)."&unconfirmed=".$_GET["unconfirmed"]."\n");
if ($returnpage) {
	if ($returnoption) {
  	$more = "&option=".$returnoption;
 	}
	echo "<br/>".PageLink2("$returnpage$more","Return to $returnpage");
  $returnurl = "returnpage=$returnpage&returnoption=$returnoption";
}

if ($change && ($access == "owner"|| $access == "all")) {
  # read the current values to compare changes
  $old_data = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',$tables["user"],$id));
  $old_data = array_merge($old_data,getUserAttributeValues('',$id));
  while (list ($key,$val) = each ($struct)) {
    list($a,$b) = explode(":",$val[1]);
    if (!ereg("sys",$a) && $val[1]) {
    	if ($key == "password" && ENCRYPTPASSWORD) {
      	if ($_POST[$key])
		      Sql_Query("update {$tables["user"]} set $key = \"".md5($$key)."\" where id = $id");
    	} else {
	      Sql_Query("update {$tables["user"]} set $key = \"".$$key."\" where id = $id");
     	}
    }
    elseif ((!$require_login || ($require_login && isSuperUser())) && $key == "confirmed")
      Sql_Query("update {$tables["user"]} set $key = \"".$$key."\" where id = $id");

  }
	if (is_array($attribute))
  while (list($key,$val) = each ($attribute)) {
    Sql_Query(sprintf('replace into %s (userid,attributeid,value)
    	values(%d,%d,"%s")',$tables["user_attribute"],$id,$key,$val));
  }
  if (is_array($cbattribute)) {
    while (list($key,$val) = each ($cbattribute)) {
      if ($attribute[$key] == "on")
        Sql_Query(sprintf('replace into %s (userid,attributeid,value)
          values(%d,%d,"on")',$tables["user_attribute"],$id,$key));
      else
        Sql_Query(sprintf('replace into %s (userid,attributeid,value)
          values(%d,%d,"")',$tables["user_attribute"],$id,$key));
    }
  }

  if (is_array($cbgroup)) {
		while (list($key,$val) = each ($cbgroup)) {
    	$field = "cbgroup".$val;
      if (is_array($_POST[$field])) {
      	$newval = array();
      	foreach ($_POST[$field] as $fieldval) {
        	array_push($newval,sprintf('%0'.$checkboxgroup_storesize.'d',$fieldval));
       	}
      	$value = join(",",$newval);
      }
      else
      	$value = "";
			Sql_Query(sprintf('replace into %s (userid,attributeid,value)
				values(%d,%d,"%s")',$tables["user_attribute"],$id,$val,$value));
		}
  }
  # submitting page now saves everything, so check is not necessary
  Sql_Query("delete from {$tables["listuser"]} where userid = $id");
  if (is_array($subscribe)) {
    foreach ($subscribe as $ind => $lst) {
      Sql_Query("insert into {$tables["listuser"]} (userid,listid) values($id,$lst)");
      print "User added to ".ListName($lst)."<br/>";
    }
  }
  $history_entry = '';
  $current_data = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',$tables["user"],$id));
  $current_data = array_merge($current_data,getUserAttributeValues('',$id));
  foreach ($current_data as $key => $val) {
  	if (!is_numeric($key))
  	if ($old_data[$key] != $val && $key != "modified") {
    	$history_entry .= "$key = $val\nchanged from $old_data[$key]\n";
   	}
  }
  addUserHistory($email,"Update by ".adminName($_SESSION["logindetails"]["id"]),$history_entry);
  Info("Changes saved");
}


if (isset($delete) && $delete && $access != "view") {
  # delete the index in delete
  print "Deleting $delete ..\n";
  if ($require_login && !isSuperUser()) {
    $lists = Sql_query("SELECT listid FROM {$tables["listuser"]},{$tables["list"]} where userid = ".$delete." and $tables[listuser].listid = $tables[list].id $subselect ");
    while ($lst = Sql_fetch_array($lists))
      Sql_query("delete from {$tables["listuser"]} where userid = $delete and listid = $lst[0]");
  } else {
  	deleteUser($delete);
  }
  print "..Done<br /><hr><br />\n";
}

if ($id) {
  $result = Sql_query("SELECT * FROM {$tables["user"]} where id = $id");
  if (!Sql_Affected_Rows()) {
    Fatal_Error("No such User");
    return;
  }
  $user = sql_fetch_array($result);
  $lists = Sql_query("SELECT listid,name FROM {$tables["listuser"]},{$tables["list"]} where userid = ".$user["id"]." and $tables[listuser].listid = $tables[list].id $subselect ");
  $membership = "";$subscribed = array();
  while ($lst = Sql_fetch_array($lists)) {
    $membership .= "<li>".PageLink2("editlist",$lst["name"],"id=".$lst["listid"]);
    array_push($subscribed,$lst["listid"]);
  }
  if (!$membership)
    $membership = "No Lists";
  if ($access != "view")
  printf( "<br /><hr/>%s<li><a href=\"javascript:deleteRec('%s');\">delete</a> %s\n",$delete_message,PageURL2("user","","delete=$id&$returnurl"),$user["email"]);
  printf('&nbsp;&nbsp;<a href="%s">update page</a>',getConfig("preferencesurl").'&uid='.$user["uniqid"]);
  printf('&nbsp;&nbsp;<a href="%s">unsubscribe page</a>',getConfig("unsubscribeurl").'&uid='.$user["uniqid"]);
  print '&nbsp;&nbsp;'.PageLink2("userhistory&id=$id","History");
  print "<p><h3>User Details</h3>".formStart()."<table border=1>";
  print "<input type=hidden name=list value=$list><input type=hidden name=id value=$id>";
  print "<input type=hidden name=returnpage value=$returnpage><input type=hidden name=returnoption value=$returnoption>";
  reset($struct);
  while (list ($key,$val) = each ($struct)) {
    list($a,$b) = explode(":",$val[1]);
    if ($key == "confirmed") {
      if (!$require_login || ($require_login && isSuperUser())) {
        printf('<tr><td>%s (1/0)</td><td><input type="text" name="%s" value="%s" size=5></td></tr>'."\n",$b,$key,$user[$key]);
      } else {
        printf('<tr><td>%s</td><td>%s</td></tr>',$b,$user[$key]);
      }
    } elseif ($key == "password" && ENCRYPTPASSWORD) {
      printf('<tr><td>%s (encrypted)</td><td><input type="text" name="%s" value="%s" size=30></td></tr>'."\n",$val[1],$key,"");
    } else {
      if ($key != "unique" && $key != "index" && $key != "primary key")
      if (ereg("sys",$a))
        printf('<tr><td>%s</td><td>%s</td></tr>',$b,$user[$key]);
      elseif ($val[1])
        printf('<tr><td>%s</td><td><input type="text" name="%s" value="%s" size=30></td></tr>'."\n",$val[1],$key,$user[$key]);
    }
  }
  $res = Sql_Query("select * from $tables[attribute] order by listorder");
  while ($row = Sql_fetch_array($res)) {
    $val_req = Sql_Fetch_Row_Query("select value from $tables[user_attribute] where userid = $id and attributeid = $row[id]");
    $row["value"] = $val_req[0];

    if ($row["type"] == "checkbox") {
      $checked = $row["value"] == "on" ?"checked":"";
      printf('<tr><td>%s</td><td><input style="attributeinput" type=hidden name="cbattribute[%d]" value="%d"><input style="attributeinput" type=checkbox name="attribute[%d]" value="on" %s></td></tr>'."\n",stripslashes($row["name"]),$row["id"],$row["id"],$row["id"],$checked);
    } elseif ($row["type"] == "checkboxgroup") {
      printf ("<tr><td valign=top>%s</td><td>%s</td></tr>\n",stripslashes($row["name"]),UserAttributeValueCbGroup($id,$row["id"]));
    } elseif ($row["type"] == "textarea") {
      printf ('<tr><td valign=top>%s</td><td><textarea name="attribute[%d]" rows="10" cols="40" wrap=virtual>%s</textarea></td></tr>',stripslashes($row["name"]),$row["id"],htmlspecialchars($row["value"]));
    } else {
    if ($row["type"] != "textline" && $row["type"] != "hidden")
      printf ("<tr><td>%s</td><td>%s</td></tr>\n",stripslashes($row["name"]),UserAttributeValueSelect($id,$row["id"]));
    else
      printf('<tr><td>%s</td><td><input style="attributeinput" type=text name="attribute[%d]" value="%s" size=30></td></tr>'."\n",$row["name"],$row["id"],htmlspecialchars($row["value"]));
    }
  }
  if ($access != "view")
  print '<tr><td colspan=2><input type=submit name=change value="Save Changes"></td></tr>';
  print '</table>';

  print "<h3>Mailinglist Membership:</h3>";
  print "<table border=1><tr>";
  $req = Sql_Query("select * from {$tables["list"]} order by listorder,name");
  $c = 0;
  while ($row = Sql_Fetch_Array($req)) {
    if (in_array($row["id"],$subscribed)) {
      $bgcol = '#F7E7C2';
      $subs = "checked";
    } else {
      $bgcol = '#ffffff';
      $subs = "";
    }
    printf ('<td bgcolor="%s"><input type=checkbox name="subscribe[]" value="%d" %s> %s</td>',
      $bgcol,$row["id"],$subs,PageLink2("editlist",$row["name"],"id=".$row["id"]));
    $c++;
    if ($c % 4 == 0)
      print '</tr><tr>';
  }
  print '</tr>';

  if ($access != "view")
    print '<tr><td><input type=submit name="change" value="Save Changes"></td></tr>';

  print '</table></form>';
}
?>


