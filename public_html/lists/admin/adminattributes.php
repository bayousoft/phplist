<?
require_once "accesscheck.php";

ob_end_flush();
if (isset($_POST["action"]) && $_POST["action"] == "Save Changes") {
  if (isset($_POST["name"]))
    print '<script language="Javascript" type="text/javascript"> document.write(progressmeter); start();</script>';flush();
    while (list($id,$val) = each ($_POST["name"])) {
      if (!$id && isset($_POST["name"][0]) && $_POST["name"][0] != "") {
        # it is a new one
        $lc_name = substr(preg_replace("/\W/","", strtolower($_POST["name"][0])),0,10);
        if ($lc_name == "") Fatal_Error("Name cannot be empty: $lc_name");
        Sql_Query("select * from {$tables['adminattribute']} where tablename = \"$lc_name\"");
        if (Sql_Affected_Rows()) Fatal_Error("Name is not unique enough");

        $query = sprintf('insert into %s 
        (name,type,listorder,default_value,required,tablename)
        values("%s","%s",%d,"%s",%d,"%s")',
        $tables["adminattribute"],
        addslashes($_POST["name"][0]),
        $_POST["type"][0],
        $_POST["listorder"][0],
        addslashes($_POST["default"][0]),$_POST["required"][0],$lc_name);
        Sql_Query($query);
        $insertid = Sql_Insert_id();

        # text boxes and hidden fields do not have their own table
        if ($_POST["type"][$id] != "textline" && $_POST["type"]["id"] != "hidden") {
          $query = "create table $table_prefix"."adminattr_$lc_name
          (id integer not null primary key auto_increment,
          name varchar(255) unique,listorder integer default 0)";
          Sql_Query($query);
        } else {
          # and they cannot currently be required, changed 29/08/01,
          # insert javascript to require them, except for hidden ones :-)
          if ($_POST["type"]["id"] == "hidden")
            Sql_Query("update {$tables['attribute']} set required = 0 where id = $insertid");
        }
        if ($_POST["type"][$id] == "checkbox") {
          # with a checkbox we know the values
          Sql_Query('insert into '.$table_prefix.'adminattr_'.$lc_name.' (name) values("Checked")');
          Sql_Query('insert into '.$table_prefix.'adminattr_'.$lc_name.' (name) values("Unchecked")');
          # we cannot "require" checkboxes, that does not make sense
          Sql_Query("update {$tables['adminattribute']} set required = 0 where id = $insertid");
        }

      } elseif ($_POST["name"][$id] != "") {
        # it is a change
        $query = sprintf('update %s set name = "%s" ,listorder = %d,default_value = "%s" ,required = %d where id = %d',
        $tables["adminattribute"],addslashes($_POST["name"][$id]),
        $_POST["listorder"][$id],$_POST["default"][$id],$_POST["required"][$id],$id);
        Sql_Verbose_Query($query);
      }
    }
  if (isset($_POST["delete"]))
    while (list($id,$val) = each ($_POST["delete"])) {
      $res = Sql_Query("select tablename,type from {$tables['adminattribute']} where id = $id");
      $row = Sql_Fetch_Row($res);
      if ($row[1] != "hidden" && $row[1] != "textline")
        Sql_Query("drop table $table_prefix"."adminattr_$row[0]");
      Sql_Query("delete from {$tables['adminattribute']} where id = $id");
      # delete all admin attributes as well
      Sql_Query("delete from {$tables['admin_attribute']} where adminattributeid = $id");
    }
}
?>



<?
print formStart();
$res = Sql_Query("select * from {$tables['adminattribute']} order by listorder");
if (Sql_Affected_Rows())
  print "Existing attributes:<p>";
else {
  print "No Attributes have been defined yet<br>";
}
while ($row = Sql_Fetch_array($res)) {
  ?>
  <table border=1>
  <tr><td colspan=2>Attribute:<? echo $row["id"] ?></td><td colspan=2>Delete <input type="checkbox" name="delete[<? echo $row["id"] ?>]" value="1"></td></tr>
  <tr><td colspan=2>Name: </td><td colspan=2><input type=text name="name[<? echo $row["id"]?>]" value="<? echo htmlspecialchars(stripslashes($row["name"])) ?>" size=40></td></tr>
  <tr><td colspan=2>Type: </td><td colspan=2><input type=hidden name="type[<?=$row["id"]?>]" value="<?=$row["type"]?>"><?=$row["type"]?></td></tr>
  <tr><td colspan=2>Default Value: </td><td colspan=2><input type=text name="default[<? echo $row["id"]?>]" value="<? echo htmlspecialchars(stripslashes($row["default_value"])) ?>" size=40></td></tr>
  <tr><td>Order of Listing: </td><td><input type=text name="listorder[<? echo $row["id"]?>]" value="<? echo $row["listorder"] ?>" size=5></td>
  <td>Is this attribute required?: </td><td><input type=checkbox name="required[<? echo $row["id"]?>]" value="1" <? echo $row["required"] ? "checked": "" ?>></td></tr>
  </table><hr>
<? } ?>

<a name="new"></a>
<h3>Add a new Attribute:</h3>
<table border=1>
<tr><td colspan=2>Name: </td><td colspan=2><input type=text name="name[0]" value="" size=40></td></tr>
<tr><td colspan=2>Type: </td><td colspan=2><select name="type[0]">
<?
$types = array('checkbox','textline',"hidden");#'radio','select',
while (list($key,$val) = each($types)) {
  printf('<option value="%s" %s>%s',$val,"",$val);
}
?>
</select></td></tr>
<tr><td colspan=2>Default Value: </td><td colspan=2><input type=text name="default[0]" value="" size=40></td></tr>
<tr><td>Order of Listing: </td><td><input type=text name="listorder[0]" value="" size=5></td>
<td>Is this attribute required?: </td><td><input type=checkbox name="required[0]" value="1" checked></td></tr>
</table><hr>

<input type=submit name="action" value="Save Changes">
</form>

