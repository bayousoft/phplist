
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr>

<?php
require_once dirname(__FILE__).'/accesscheck.php';
$access = accessLevel("members");
$id = sprintf('%d',$_REQUEST["id"]);
switch ($access) {
  case "owner":
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
    if ($id) {
      $query = "select id from " . $tables['list'] . $subselect . " and id = ?";
      $rs = Sql_Query_Params($query, array($id));
      if (!Sql_Num_Rows($rs)) {
        Fatal_Error($GLOBALS['I18N']->get("You do not have enough priviliges to view this page"));
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
      Fatal_Error($GLOBALS['I18N']->get("You do not have enough priviliges to view this page"));
      return;
    }
    $subselect = " where id = 0";
    break;
}
function addUserForm ($listid) {
//nizar 'value'
  $html = formStart().'<input type=hidden name=listid value="'.$listid.'">
  '.$GLOBALS['I18N']->get("Add a user").': <input type=text name=new value="" size=40><input type=submit
 name=add value="'.$GLOBALS['I18N']->get('Add').'">
  </form>';
  return $html;
}
if (isset($id)) {
  print "<h3>".$GLOBALS['I18N']->get("Members of")." ".ListName($id)."</h3>";
  echo "<br />".PageLink2("editlist",$GLOBALS['I18N']->get("back to this list"),"id=$id");
  echo "<br />".PageLink2("export&list=$id",$GLOBALS['I18N']->get("Download users on this list as a CSV file"));
  print addUserForm($id);
} else {
  Fatal_Error($GLOBALS['I18N']->get("Please enter a listid"));
}
if (isset($_REQUEST["processtags"]) && $access != "view") {
  print $GLOBALS['I18N']->get("Processing")." .... <br/>";
  if ($_POST["tagaction"] && is_array($_POST["user"])) {
    switch ($_POST["tagaction"]) {
      case "move":
        $cnt = 0;
        foreach ($_POST["user"] as $key => $val) {
          Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
            $key");
          Sql_query("replace into {$tables["listuser"]} (listid,userid)
            values({$_POST["movedestination"]},$key)");
          if (Sql_Affected_rows() == 1) # 2 means they were already on the list
            $cnt++;
        }
        $msg = $cnt .' '.$GLOBALS['I18N']->get("users were moved to").' '.listName($_POST["movedestination"]);
        break;
      case "copy":
        $cnt = 0;
        foreach ($_POST["user"] as $key => $val) {
          Sql_query("replace into {$tables["listuser"]} (listid,userid)
            values({$_POST["copydestination"]},$key)");
          $cnt++;
        }
        $msg = $cnt .' '.$GLOBALS['I18N']->get("users were copied to").' '.listName($_POST["copydestination"]);
        break;
      case "delete":
        $cnt = 0;
        foreach ($_POST["user"] as $key => $val) {
          Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
            $key");
          if (Sql_Affected_rows())
            $cnt++;
        }
        $msg = $cnt.' '.$GLOBALS['I18N']->get("users were deleted from this list");
        break;
      default: # do nothing
        break;
    }
  }
  if ($_POST["tagaction_all"] != "nothing") {
    $query = sprintf('select userid from %s where listid = ?', $tables['listuser']);
    $req = Sql_Query_Params($query, array($id));
    switch ($_POST["tagaction_all"]) {
      case "move":
        $cnt = 0;
        while ($user = Sql_Fetch_Row($req)) {
          Sql_query("delete from {$tables["listuser"]} where listid = $id and userid =
            $user[0]");
          Sql_query("replace into {$tables["listuser"]} (listid,userid)
            values({$_POST["movedestination_all"]},$user[0])");
          if (Sql_Affected_rows() == 1) # 2 means they were already on the list
            $cnt++;
        }
        $msg = $cnt . ' '.$GLOBALS['I18N']->get("users were moved to").' '.listName($_POST["movedestination_all"]);
        break;
      case "copy":
        $cnt = 0;
        while ($user = Sql_Fetch_Row($req)) {
          Sql_query("replace into {$tables["listuser"]} (listid,userid)
            values({$_POST["copydestination_all"]},$user[0])");
          $cnt++;
        }
        $msg = $cnt .' '.$GLOBALS['I18N']->get("users were copied to").' '.listName($_POST["copydestination_all"]);
        break;
      case "delete":
        Sql_Query(sprintf('delete from %s where listid = %d',$tables["listuser"],$id));
        $msg = Sql_Affected_Rows().' '.$GLOBALS['I18N']->get("users were deleted from this list");
        break;
      default: # do nothing
    }
  }
  print $msg.'<br/>';
}
if (isset($_POST["add"])) {
  if ($_POST["new"]) {
    // TODO Validate email address.
    $query = sprintf('select * from %s where email = ?', $tables['user']);
    $result = Sql_Query_Params($query, array($_POST['new']));
    if (Sql_Num_rows($result)) {
      print '<p class="information">'.$GLOBALS['I18N']->get("Users found, click add to add this user").":<br /><ul>\n";
      while ($user = Sql_fetch_array($result)) {
        printf ("<li>[ ".PageLink2("members",$GLOBALS['I18N']->get("Add"),"add=1&id=$id&doadd=".$user["id"])." ] %s <br />\n",
 $user["email"]);
      }
      print "</ul>\n";
    } else {
      print '<p class="information">'.$GLOBALS['I18N']->get("No user found with that email").'</p><table class="membersForm">'.formStart();
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
      <!--nizar 5 lignes -->
      <tr><td colspan=2><input type=hidden name=action value="insert"><input
 type=hidden name=doadd value="yes"><input type=hidden name=id value="<?php echo
 $id ?>"><input type=submit name=subscribe value="<?php echo $GLOBALS['I18N']->get('add user')?>"></form></td></tr></table>
      <?php
      return;
    }
  }
}
if (isset($_REQUEST["doadd"])) {
  if ($_POST["action"] == "insert") {
    $email = trim($_POST["email"]);
    #TODO validate email address.
    print $GLOBALS['I18N']->get("Inserting user")." $email";
    Sql_Start_Transaction();
    $query
    = ' insert into %s (email, entered, confirmed, htmlemail, uniqid)'
    . ' values(?, current_timestamp, 1, ?, ?)';
    $query = sprintf($query, $tables['user']);
    $result = Sql_Query_Params($query, array($email, $htmlemail, getUniqid()));
    $userid = Sql_Insert_Id($tables['user'], 'id');
    $query
    = ' insert into %s (userid, listid, entered)'
    . ' values (?, ?, current_timestamp)';
    $query = sprintf($query, $tables['listuser']);
    $result = Sql_Query_Params($query, array($userid, $id));
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
      $res1 = Sql_Replace($tables['user_attribute'], array('attributeid' => $row['id'], 'userid' => $userid, 'value' => $value), 'id');
    }
  } else {
    $res2 = Sql_Replace($tables['listuser'], array('userid' => "'" . $_REQUEST['doadd'] . "'", 'listid' => $id, 'entered' => 'current_timestamp'), array('userid', 'listid'), false);
  }

  if ($database_module == 'adodb.inc')
  Sql_Commit_Transaction();

  echo "<br />".$GLOBALS['I18N']->get("User added")."<br />";
}
if (isset($_REQUEST["delete"])) {
  $delete = sprintf('%d',$_REQUEST["delete"]);
  # single delete the index in delete
  print $GLOBALS['I18N']->get("Deleting")." $delete ..\n";
  $query
  = ' delete from ' . $tables['listuser']
  . ' where listid = ?'
  . '   and userid = ?';
  $result = Sql_Query_Params($query, array($id, $delete));
  print "... ".$GLOBALS['I18N']->get("Done")."<br />\n";
  Redirect("members&id=$id");
}
if (isset($id)) {
  $query
  = ' select count(*)'
  . ' from %s lu'
  . '    join %s u'
  . '       on lu.userid = u.id'
  . ' where lu.listid = ?';
  $query = sprintf($query, $tables['listuser'], $tables['user']);
  $result = Sql_Query_Params($query, array($id));
  $row = Sql_Fetch_row($result);
  $total = $row[0];
  print "$total ".$GLOBALS['I18N']->get("Users on this list").'<p class="information">';
  $offset = 0;
  if ($total > MAX_USER_PP) {
    if (isset($start) && $start) {
      $listing = $GLOBALS['I18N']->get("Listing user")." $start ".$GLOBALS['I18N']->get("to")." " . ($start + MAX_USER_PP);
      $offset = $start;
    } else {
      $listing = $GLOBALS['I18N']->get("Listing user 1 to 50");
      $start = 0;
    }
  printf ('<table class="membersListing" border="1"><tr><td colspan="4" align="center">%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><hr>',
          $listing,
          PageLink2("members","&lt;&lt;","start=0&id=$id"),
          PageLink2("members","&lt;",sprintf('start=%d&id=%d',max(0,$start-MAX_USER_PP),$id)),
          PageLink2("members","&gt;",sprintf('start=%d&id=%d',min($total,$start+MAX_USER_PP),$id)),
          PageLink2("members","&gt;&gt;",sprintf('start=%d&id=%d',$total-MAX_USER_PP,$id)));
  }
//  $result = Sql_query("SELECT $tables[user].id,email,confirmed,rssfrequency FROM // so plugins can use all fields
  $query
  = ' select u.*'
  . " from %s lu"
  . "    join %s u"
  . '       on lu.userid = u.id'
  . ' where lu.listid = ?'
  . ' order by confirmed desc, email'
  . ' limit ' . MAX_USER_PP . ' offset ' . $offset;
// TODO Consider using a subselect.  select user where uid in select uid from list
  $query=sprintf($query, $tables['listuser'], $tables['user'] );
  $result = Sql_Query_Params($query, array($id));
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
  <input type=checkbox name="checkall" onClick="checkAll()"><?php echo $GLOBALS['I18N']->get("Tag all users in this page");?>
  <?php
  $columns = array();
  $columns = explode(',',getConfig('membership_columns'));
  $columns = array('country','Lastname');
  $ls = new WebblerListing($GLOBALS['I18N']->get("Members"));
  while ($user = Sql_fetch_array($result)) {
    $ls->addElement($user["email"],PageUrl2("user&id=".$user["id"]));
    $ls->addColumn($user["email"],$GLOBALS['I18N']->get("confirmed"),$user["confirmed"]?$GLOBALS["img_tick"]:$GLOBALS["img_cross"]);
    if ($access != "view")
    $ls->addColumn($user["email"],$GLOBALS['I18N']->get("tag"),sprintf('<input type=checkbox name="user[%d]" value="1">',$user["id"]));
    if ($access != "view")
       $ls->addColumn($user["email"],$GLOBALS['I18N']->get("del"),
       sprintf('<a href="javascript:deleteRec(\'%s\');">'.$GLOBALS['I18N']->get('Del').'</a>',
       PageURL2("members","","start=$start&id=$id&delete=".$user["id"])));
    $query
    = ' select count(*)'
    . ' from %s lm, %s um'
    . ' where lm.messageid = um.messageid'
    . '   and lm.listid = ?'
    . '   and um.userid = ?';
    // TODO: Could left join with above query.
    $query = sprintf($query, $tables['listmessage'], $tables['usermessage']);
    $rs = Sql_Query_Params($query, array($id, $user['id']));
    $msgcount = Sql_Fetch_Row($rs);
    $ls->addColumn($user["email"],$GLOBALS['I18N']->get("# msgs"),$msgcount[0]);

    ## allow plugins to add columns
    foreach ($GLOBALS['plugins'] as $plugin) {
      $plugin->displayUsers($user,  $user['email'], $ls);
    } 

// obsolete by rssmanager plugin
//    if (ENABLE_RSS) {
//      $msgcount = Sql_Fetch_Row_Query("select count(*) from {$tables["rssitem"]},{$tables["rssitem_user"]}
//        where {$tables["rssitem"]}.list = $id and {$tables["rssitem"]}.id = {$tables["rssitem_user"]}.itemid and
//        {$tables["rssitem_user"]}.userid = {$user["id"]}");
//      if ($msgcount[0])
//        $ls->addColumn($user["email"],$GLOBALS['I18N']->get("# rss"),$msgcount[0]);
//      if ($user["rssfrequency"])
//        $ls->addColumn($user["email"],$GLOBALS['I18N']->get("rss freq"),$user["rssfrequency"]);
//      $last = Sql_Fetch_Row_Query("select last from {$tables["user_rss"]} where userid = ".$user["id"]);
//      if ($last[0])
//        $ls->addColumn($user["email"],$GLOBALS['I18N']->get("last sent"),$last[0]);
//    }
    if (sizeof($columns)) {
      # let's not do this when not required, adds rather many db requests
      $attributes = getUserAttributeValues('',$user['id']);
#      foreach ($attributes as $key => $val) {
#          $ls->addColumn($user["email"],$key,$val);
#      }
        
      foreach ($columns as $column) {
        if (isset($attributes[$column]) && $attributes[$column]) {
          $ls->addColumn($user["email"],$column,$attributes[$column]);
        }
      }
    }
  }
  print $ls->display();
}
if ($access == "view") return;
?>
<hr>
<table class="membersProcess">
<tr><td colspan=2><h3><?php echo $GLOBALS['I18N']->get('What to do with "Tagged" users')?>:</h3>
<?php echo $GLOBALS['I18N']->get('This will only process the users in this page that have the "Tag" checkbox checked')?></td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('delete')?> (<?php echo $GLOBALS['I18N']->get('from this list')?>) <input type=radio name="tagaction"
 value="delete"></td></tr>
<?php
$html = '';
$res = Sql_Query("select id,name from {$tables["list"]} $subselect");
while ($row = Sql_Fetch_array($res)) {
  if ($row["id"] != $id)
    $html .= sprintf('<option value="%d">%s',$row["id"],$row["name"]);
}
if ($html) {
?>
  <tr><td><?php echo $GLOBALS['I18N']->get('move')?> <input type=radio name="tagaction" value="move"> </td><td><?php echo $GLOBALS['I18N']->get('to')?>
 <select name=movedestination>
  <?php echo $html ?>
</select></td></tr>
  <tr><td><?php echo $GLOBALS['I18N']->get('copy')?> <input type=radio name="tagaction" value="copy"> </td><td><?php echo $GLOBALS['I18N']->get('to')?>
 <select name=copydestination>
  <?php echo $html ?>
</select></td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('nothing')?> <input type=radio name="tagaction"
 value="nothing" checked></td></tr>
<?php } ?>
<tr><td colspan=2><hr></td></tr>
<tr><td colspan=2><h3><?php echo $GLOBALS['I18N']->get('What to do with all users')?></h3><?php echo $GLOBALS['I18N']->get('This will process all users on this list')?></td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('delete')?> (<?php echo $GLOBALS['I18N']->get('from this list')?>) <input type=radio name="tagaction_all"
 value="delete"></td></tr>
<?php

if ($html) {
?>
  <tr><td><?php echo $GLOBALS['I18N']->get('move')?> <input type=radio name="tagaction_all" value="move"> </td><td><?php echo $GLOBALS['I18N']->get('to')?>
 <select name="movedestination_all">
  <?php echo $html ?>
</select></td></tr>
  <tr><td><?php echo $GLOBALS['I18N']->get('copy')?> <input type=radio name="tagaction_all" value="copy"> </td><td><?php echo $GLOBALS['I18N']->get('to')?>
 <select name="copydestination_all">
  <?php echo $html ?>
</select></td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('nothing')?> <input type=radio name="tagaction_all"
 value="nothing" checked></td></tr>
<?php } ?>
<tr><td colspan=2><input type=submit name=processtags value="<?php echo $GLOBALS['I18N']->get('do it')?>"></td></tr>
</table>
</form>
</p>