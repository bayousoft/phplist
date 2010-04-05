
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr/>

<?php
require_once dirname(__FILE__).'/accesscheck.php';

print formStart('class="listListing"');
$some = 0;

if (isset($_GET['delete'])) {
  $delete = sprintf('%d',$_GET['delete']);
  # delete the index in delete
  print $GLOBALS['I18N']->get('Deleting') . " $delete ..\n";
  $result = Sql_Query_Params("delete from $tables[list] where id = ?", array($delete));
  $result = Sql_Query_Params("delete from $tables[listuser] where listid = ?", array($delete));
  $result = Sql_Query_Params("delete from $tables[listmessage] where listid = ?", array($delete));
  print '..' . $GLOBALS['I18N']->get('Done') . "<br /><hr /><br />\n";
}

## quick DB fix
if (!Sql_Table_Column_Exists($tables['list'],'category')) {
  Sql_Query('alter table '.$tables['list'].' add column category varchar(255) default ""');
}

if (isset($_POST['listorder']) && is_array($_POST['listorder']))
  while (list($key,$val) = each ($_POST['listorder'])) {
    $active = empty($_POST['active'][$key]) ? '0' : '1';
    $query
    = ' update %s'
    . ' set listorder = ?, active = ?'
    . ' where id = ?';
    $query = sprintf($query, $tables['list']);
    Sql_Query_Params($query, array($val, $active, $key));
  }

$access = accessLevel('list');
switch ($access) {
  case 'owner':
    $subselect = ' where owner = ' . $_SESSION['logindetails']['id'];
    break;
  case 'all':
    $subselect = '';
    break;
  case 'none':
  default:
    $subselect = ' where id = 0';
    break;
}

print PageLink2('catlists',$I18N->get('Categorise lists'));

$html = '';

$aConfiguredListCategories = listCategories();
  
$aListCategories = array();
$req = Sql_Query(sprintf('select distinct category from %s',$tables['list']));
while ($row = Sql_Fetch_Row($req)) {
  array_push($aListCategories,$row[0]);
}

if (sizeof($aListCategories)) {
  if (isset($_GET['tab'])) {
    $current = $_GET['tab'];
  } elseif (isset($_SESSION['last_list_category'])) {
    $current = $_SESSION['last_list_category'];
  } else {
    $current = '';
  }
  if (!in_array($current,$aConfiguredListCategories)) {
    $current = '';#$aListCategories[0];
  }
  $_SESSION['last_list_category'] = $current;
  
  if ($subselect == '') {
    $subselect = ' where category = "'.$current.'"';
  } else {
    $subselect .= ' and category = "'.$current.'"';
  }
  $baseurl = './?page=list';
  $tabs = new WebblerTabs();
  foreach ($aListCategories as $category) {
    $category = trim($category);
    if ($category == '') {
      $category = $GLOBALS['I18N']->get('Uncategorised');
    }

    $tabs->addTab($category,$baseurl.'&amp;tab='.urlencode($category));
  }
  $tabs->setCurrent($current);
  print $tabs->display();
}

$query
= ' select *'
. ' from ' . $tables['list']
. $subselect
. ' order by listorder';
$result = Sql_query($query);
$ls = new WebblerListing($GLOBALS['I18N']->get('Lists'));
while ($row = Sql_fetch_array($result)) {
  $query
  = ' select count(*)'
  . ' from ' . $tables['listuser']
  . ' where listid = ?';
  $rsc = Sql_Query_Params($query, array($row["id"]));
  $membercount = Sql_Fetch_Row($rsc);

/*
  $query = sprintf('
  select count(distinct userid) as bouncecount from %s listuser,
  %s umb where listuser.userid = umb.user and listuser.listid = ? ',
  $GLOBALS['tables']['listuser'],$GLOBALS['tables']['user_message_bounce'],$row['id'])

  print $query;
*/
  $bouncecount =
    Sql_Fetch_Row_Query(sprintf('select count(distinct userid) as bouncecount from %s listuser, %s umb where listuser.userid = umb.user and listuser.listid = %s ',$GLOBALS['tables']['listuser'],$GLOBALS['tables']['user_message_bounce'],$row['id']));

  $desc = stripslashes($row['description']);

//Obsolete by rssmanager plugin 
//  if ($row['rssfeed']) {
//    $feed = $row['rssfeed'];
//    # reformat string, so it wraps if it's very long
//    $feed = ereg_replace("/","/ ",$feed);
//    $feed = ereg_replace("&","& ",$feed);
//    $desc = sprintf('%s: <a href="%s" target="_blank">%s</a><br /> ', $GLOBALS['I18N']->get('rss source'), $row['rssfeed'], $feed) .
//    PageLink2("viewrss&amp;id=".$row["id"],$GLOBALS['I18N']->get('(View Items)')) . '<br />'.
//    $desc;
//  }

  ## allow plugins to add columns
  foreach ($GLOBALS['plugins'] as $plugin) {
    $desc = $plugin->displayLists($row) . $desc;
  }

  $ls->addElement($row["id"],PageUrl2("editlist&amp;id=".$row["id"]));
  

  $html .= sprintf('
    <tr>
      <td valign="top">%s</td><td valign="top"><b>%s</b><br/>%d %s </td>
      <td valign="top"><input type="text" name="listorder[%d]" value="%d" size="5" /></td>
    <td valign="top">%s<br/>%s<br/>%s<br/><a href="javascript:deleteRec(\'%s\');">%s</a></td>
    <td valign="top"><input type="checkbox" name="active[%d]" value="1" %s /></td>
    <td valign="top">%s</td></tr><tr><td>&nbsp;</td>
      <td colspan="5">%s</td></tr><tr><td colspan="6"><hr width="50%%" size="4"></td>
    </tr>',
    PageLink2("editlist",$row["id"],"id=".$row["id"]),
    PageLink2("editlist",stripslashes($row['name']),"id=".$row["id"]),
    $membercount[0],
    $GLOBALS['I18N']->get('members'),
   $row['id'],
    $row['listorder'],
    '',#PageLink2("editlist",$GLOBALS['I18N']->get('edit'),"id=".$row["id"]),
    PageLink2("members",$GLOBALS['I18N']->get('view members'),"id=".$row["id"]),
    PageLink2("listbounces",$GLOBALS['I18N']->get('view bounces'),"id=".$row["id"]),
    PageURL2("list","","delete=".$row["id"]),
    $GLOBALS['I18N']->get('delete'),
   $row["id"],
    $row["active"] ? 'checked="checked"' : '',
    $GLOBALS['require_login'] ? adminName($row['owner']):$GLOBALS['I18N']->get('n/a'),
    $desc
    );
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Name'),
      PageLink2("editlist",stripslashes($row['name']),"id=".$row["id"]));
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Order'),
      sprintf('<input type="text" name="listorder[%d]" value="%d" size="5" />',$row['id'],$row['listorder']));
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Members'),
      PageLink2("members",$membercount[0],"id=".$row["id"]));
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Bounces'),
      PageLink2("listbounces",$bouncecount[0],"id=".$row["id"]));
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Public'),sprintf('<input type="checkbox" name="active[%d]" value="1" %s />',$row["id"],
    $row["active"] ? 'checked="checked"' : ''));
    $ls->addColumn($row['id'],
      $GLOBALS['I18N']->get('Owner'),$GLOBALS['require_login'] ? adminName($row['owner']):$GLOBALS['I18N']->get('n/a'));
    if (trim($desc) != '') {
      $ls->addRow($row['id'],
        $GLOBALS['I18N']->get('Description'),$desc);
    }


  $some = 1;
}
$ls->addSubmitButton('update',$GLOBALS['I18N']->get('Save Changes'));

if (!$some) {
  echo $GLOBALS['I18N']->get('No lists available, use Add to add one');
}  else {
  print $ls->display();
}
/*
  echo '<table class="x" border="0">
      <tr>
        <td>'.$GLOBALS['I18N']->get('No').'</td>
        <td>'.$GLOBALS['I18N']->get('Name').'</td>
        <td>'.$GLOBALS['I18N']->get('Order').'</td>
        <td>'.$GLOBALS['I18N']->get('Functions').'</td>
        <td>'.$GLOBALS['I18N']->get('Active').'</td>
        <td>'.$GLOBALS['I18N']->get('Owner').'</td>
        <td>'.$html . '
    <tr>
        <td colspan="6" align="center">
        <input type="submit" name="update" value="'.$GLOBALS['I18N']->get('Save Changes').'"></td>
      </tr>
    </table>';
}
*/
?>

</form>
<p class="button">
<?php
if ($GLOBALS['require_login'] && !isSuperUser()) {
  $numlists = Sql_Fetch_Row_query("select count(*) from {$tables['list']} where owner = " . $_SESSION['logindetails']['id']);
  if ($numlists[0] < MAXLIST) {
    print PageLink2("editlist",$GLOBALS['I18N']->get('Add a list'));
  }
} else {
  print PageLink2('editlist',$GLOBALS['I18N']->get('Add a list'));
}
?>
</p>
