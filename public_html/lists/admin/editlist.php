<?php

require_once 'accesscheck.php';

if (!empty($_GET['id'])) {
  $id = sprintf('%d',$_GET["id"]);
} else {
  $id = 0;
}

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("list");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
      $subselect_and = " and owner = ".$_SESSION["logindetails"]["id"];
      if ($id) {
        Sql_Query("select id from ".$tables["list"]. $subselect . " and id = $id");
        if (!Sql_Affected_Rows()) {
          Fatal_Error($GLOBALS['I18N']->get('You do not have enough priviliges to view this page'));
          return;
        }
      } else {
        $numlists = Sql_Fetch_Row_query("select count(*) from {$tables['list']} $subselect");
        if (!($numlists[0] < MAXLIST)) {
          Fatal_Error($GLOBALS['I18N']->get('You cannot create a new list because you have reached maximum number of lists.'));
          return;
        }
      }
      break;
    case "all":
      $subselect = ""; 
      $subselect_and = "";
      break;
    case "none":
    default:
      $subselect_and = " and owner = -1";
      if ($id) {
        Fatal_Error($GLOBALS['I18N']->get('You do not have enough priviliges to view this page'));
        return;
      }
      $subselect = " where id = 0";
      break;
  }
}

if ($id) {
  echo "<br />".PageLink2("members",$GLOBALS['I18N']->get('Members of this list'),"id=$id");
}
echo "<hr />";
if (isset($_POST["save"]) && $_POST["save"] == $GLOBALS['I18N']->get('Save') && isset($_POST["listname"]) && $_POST["listname"]) {
  if ($GLOBALS["require_login"] && !isSuperUser()) {
    $owner = $_SESSION["logindetails"]["id"];
  }
  if (!isset($_POST["active"])) $_POST["active"] = 0;
  $_POST['listname'] = removeXss($_POST['listname']);
  ## prefix isn't used any more
  $_POST['prefix'] = '';
  
  $categories = listCategories();
  if (isset($_POST['category']) && in_array($_POST['category'],$categories)) {
    $category = $_POST['category'];
  } else {
    $category = '';
  }

  if ($id) {
    $query
    = ' update %s'
    . ' set name = ?, description = ?, active = ?,'
    . '     listorder = ?, prefix = ?, owner = ?, category = ?'
    . ' where id = ?';
    $query = sprintf($query, $tables['list']);
    $result = Sql_Query_Params($query, array($_POST['listname'],
       $_POST['description'], $_POST['active'], $_POST['listorder'],
       $_POST['prefix'], $_POST['owner'], $category, $id));
  } else {
    $query
    = ' insert into %s'
    . '    (name, description, entered, listorder, owner, prefix, active, category)'
    . ' values'
    . '    (?, ?, current_timestamp, ?, ?, ?, ?, ?)';
    $query = sprintf($query, $tables['list']);
#  print $query;
    $result = Sql_Query_Params($query, array($_POST['listname'],
       $_POST['description'], $_POST['listorder'], $_POST['owner'],
       $_POST['prefix'], $_POST['active'], $category));
  }
  if (!$id) {
    $id = Sql_Insert_Id($tables['list'], 'id');
    ## allow plugins to save their fields
    foreach ($GLOBALS['plugins'] as $plugin) {
      $result = $result && $plugin->processEditList($id);
    }

    $_SESSION['action_result'] = $GLOBALS['I18N']->get('Record Saved') . ": $id";
  }
  
  print '<h3>'.$_SESSION['action_result'].'</h3>';
  return;
  Redirect('list');
}

if (!empty($id)) {
  $result = Sql_Query("SELECT * FROM $tables[list] where id = $id");
  $list = Sql_Fetch_Array($result);
} else {
  $list = array(
    'name' => '',
//    'rssfeed' => '',  //Obsolete by rssmanager plugin
    'active' => 0,
    'listorder' => 0,
    'description' => '',
  );
}
ob_end_flush();

?>

<?php echo formStart(' class="editlistSave" ')?>
<input type="hidden" name="id" value="<?php echo $id ?>" />
<div><?php echo $GLOBALS['I18N']->get('List name'); ?>:</div><div><input type="text" name="listname" value="<?php echo  htmlspecialchars(StripSlashes($list["name"]))?>" /></div>
<div><?php echo $GLOBALS['I18N']->get('Public list (listed on the frontend)'); ?></div>
<div><input type="checkbox" name="active" value="1" <?php echo $list["active"] ? 'checked="checked"' : ''; ?> /></div>
<div><?php echo $GLOBALS['I18N']->get('Order for listing'); ?></div>
<div><input type="text" name="listorder" value="<?php echo $list["listorder"] ?>" size="5" /></div>
<?php if ($GLOBALS["require_login"] && (isSuperUser() || accessLevel("editlist") == "all")) {
  if (empty($list["owner"])) {
    $list["owner"] = $_SESSION["logindetails"]["id"];
  }
  print '<div>' . $GLOBALS['I18N']->get('Owner') . '</div><div><select name="owner">';
  $admins = $GLOBALS["admin_auth"]->listAdmins();
  foreach ($admins as $adminid => $adminname) {
    printf ('    <option value="%d" %s>%s</option>',$adminid,$adminid == $list["owner"]? 'selected="selected"':'',$adminname);
  }
  print '</select></div>';
} else {
  print '<input type="hidden" name="owner" value="'.$_SESSION["logindetails"]["id"].'" />';
}

$aListCategories = listCategories();
if (sizeof($aListCategories)) {
  print '<div>'.$GLOBALS['I18N']->get('Category').'</div>';
  print '<select name="category">';
  print '<option value="">-- '.$GLOBALS['I18N']->get('choose category').'</option>';
  foreach ($aListCategories as $category) {
    $category = trim($category);
    printf('<option value="%s" %s>%s</option>',$category,$category == $list['category'] ? 'selected="selected"':'',$category);
  }
  print '</select></div>';
}

  ### allow plugins to add rows
  foreach ($GLOBALS['plugins'] as $plugin) {
    print $plugin->displayEditList($list);
  }

?>
<div><?php echo $GLOBALS['I18N']->get('List Description'); ?></div>
<div><textarea name="description" cols="55" rows="15">
<?php echo htmlspecialchars(StripSlashes($list["description"])) ?></textarea></div>
<div><input class="submit" type="submit" name="save" value="<?php echo $GLOBALS['I18N']->get('Save'); ?>" /></div>
</form>
