<?
require_once "accesscheck.php";

# this file is shared between the webbler and PHPlist
# $Id: editattributes.php,v 1.1.1.1 2003-11-21 12:50:22 mdethmers Exp $


ob_end_flush();
function adminMenu() {
	global $adminlevel,$config;

  if ($adminlevel == "superuser"){
		$html .= menuLink("admins","administrators");
		$html .= menuLink("groups","groups");
		$html .= menuLink("users","users");
		$html .= menuLink("userattributes","user attributes");
    $req = Sql_Query('select * from attribute where type = "select" or type = "radio" or type = "checkboxgroup"');
    while ($row = Sql_Fetch_Array($req)) {
    	$html .= menuLink("editattributes&id=".$row["id"],"&gt;&nbsp;".$row["name"]);
    }

		$html .= menuLink("branches","branch fields","option=branchfields");
		$html .= menuLink("templates","templates");
  }
  return $html;
}

if (!$id)
  Fatal_Error("No such attribute: $id");

if (!isset($tables["attribute"])) {
	$tables["attribute"] = "attribute";
  $tables["user_attribute"]  = "user_attribute";
}
if (!isset($table_prefix )) {
	$table_prefix = 'phplist_';
}

$res = Sql_Query("select * from $tables[attribute] where id = $id");
$data = Sql_Fetch_array($res);
$table = $table_prefix ."listattr_".$data["tablename"];
?>
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<br><?=PageLink2("editattributes","add new","id=$id&action=new")?> <?=$data["name"]?>
<br><a href="javascript:deleteRec2('Are you sure you want to delete all
 records?','<?=PageURL2("editattributes","delete all","id=$id&deleteall=yes")?>');">delete all</a>
<hr><p>
<?=formStart()?>
<input type=hidden name="action" value="add">
<input type=hidden name="id" value="<?=$id?>">



<?php

if (isset($action) && $action == "add") {
  $items = explode("\n", $itemlist);
  while (list($key,$val) = each($items)) {
    $val = clean($val);
    if ($val != "") {
      $query = sprintf('INSERT into %s (name) values("%s")',$table,$val);
      $result = Sql_query($query);
    }
  }
}

if (is_array($listorder))
  while (list($key,$val) = each ($listorder))
    Sql_Query("update $table set listorder = $val where id = $key");

function giveAlternative($table,$delete,$attributeid) {
  print "Alternatively you can replace all values with another one:".formStart();
  print '<select name=replace><option value="0">-- Replace with</option>';
  $req = Sql_Query("select * from $table order by listorder,name");
  while ($row = Sql_Fetch_array($req))
    if ($row[id] != $delete)
      printf('<option value="%d">%s</option>',$row[id],$row[name]);
  print "</select>";
  printf('<input type=hidden name="delete" value="%d">',$delete);
  printf('<input type=hidden name="id" value="%d">',$attributeid);
  print '<input type=submit name="action" value="Delete and Replace"></form>';
}

function deleteItem($table,$attributeid,$delete) {
  global $tables,$replace;
  # delete the index in delete
  $valreq = Sql_Fetch_Row_query("select name from $table where id = $delete");
  $val = $valreq[0];

  # check dependencies
  $dependencies = array();
  $result = Sql_query("select distinct userid from $tables[user_attribute] where
  attributeid = $attributeid and value = $delete");
  while ($row =  Sql_fetch_array($result)) {
    array_push($dependencies,$row["userid"]);
  }

  if (sizeof($dependencies) == 0)
    $result = Sql_query("delete from $table where id = $delete");
  else if ($replace) {
    $result = Sql_Query("update $tables[user_attribute] set value = $replace where value = $delete");
    $result = Sql_query("delete from $table where id = $delete");
  } else {
    ?>
    Cannot delete <b><?=$val?></b><br />
    The Following record(s) are dependent on this value<br />
    Update the record(s) to not use this attribute value and try again<p>
    <?php

    for ($i=0;$i<sizeof($dependencies);$i++) {
      print PageLink2("user","User ".$dependencies[$i],"id=$dependencies[$i]")."<br />\n";
      if ($i>10) {
        print "* Too many to list, total dependencies:
 ".sizeof($dependencies)."<br /><br />";
        giveAlternative($table,$delete,$attributeid);
        return 0;
      }
    }
    print "</p><br />";
    giveAlternative($table,$delete,$attributeid);

  }
  return 1;
}

if (isset($delete)) {
  deleteItem($table,$id,$delete);
} elseif(isset($deleteall)) {
  $count = 0;
  $errcount = 0;
  $res = Sql_Query("select id from $table");
  while ($row = Sql_Fetch_Row($res)) {
    if (deleteItem($table,$id,$row[0])) {
      $count++;
    } else {
      $errcount++;
      if ($errcount > 10) {
        print "* Too many errors, quitting<br /><br /><br />\n";
        break;
      }
    }
  }
}

if (isset($action) && $action == "new") {

  // ??
  ?>

  <p>add new <? echo $data["name"] ?>, one per line<br />
  <textarea name="itemlist" rows=20 cols=50></textarea><br />
  <input type="Submit" name="submit" value="add new <? echo $data["name"] ?>s"><br />

<?php
}

$result = Sql_query("SELECT * FROM $table order by listorder,name");
$num = Sql_Affected_Rows();
if ($num < 100 && $num > 25)
  print '<input type=submit name=action value="change order"><br />';

while ($row = Sql_Fetch_array($result)) {
  printf( '<a href="javascript:deleteRec(\'%s\');">delete</a> |',PageURL2("editattributes","","id=$id&delete=".$row["id"]));
  if ($num < 100)
    printf(' <input type=text name="listorder[%d]" value="%s" size=5>',$row[id],$row[listorder]);
  printf(' %s %s <br />', $row[name],($row[name] == $data["default_value"]) ? "(default)":"");
}
if ($num && $num < 100)
  print '<input type=submit name=action value="change order">';

?>
</form>
