
<hr/>

<?php
require_once dirname(__FILE__).'/accesscheck.php';

print formStart('class="listListing"');
$some = 0;
if (isset($_GET['s'])) {
  $s = sprintf('%d',$_GET['s']);
} else {
  $s = 0;
}
$baseurl = './?page=list';


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
    $subselect_and = ' and owner = ' . $_SESSION['logindetails']['id'];
    break;
  case 'all':
    $subselect = '';
    $subselect_and = '';
    break;
  case 'none':
  default:
    $subselect = ' where id = 0';
    $subselect_and = ' and id = 0';
    break;
}

print PageLinkButton('catlists',$I18N->get('Categorise lists')).'<br/>';
$canaddlist = false;
if ($GLOBALS['require_login'] && !isSuperUser()) {
  $numlists = Sql_Fetch_Row_query("select count(*) from {$tables['list']} where owner = " . $_SESSION['logindetails']['id']);
  if ($numlists[0] < MAXLIST) {
    print PageLinkButton("editlist",$GLOBALS['I18N']->get('Add a list'));
    $canaddlist = true;
  }
} else {
  print PageLinkButton('editlist',$GLOBALS['I18N']->get('Add a list'));
  $canaddlist = true;
}

if (isset($_GET['delete'])) {
  $delete = sprintf('%d',$_GET['delete']);
  # delete the index in delete
  print $GLOBALS['I18N']->get('Deleting') . " $delete ..\n";
  $result = Sql_query(sprintf('delete from '.$tables['list'].' where id = %d %s',$delete,$subselect_and));
  $done = Sql_Affected_Rows();
  if ($done) {
    $result = Sql_query('delete from '.$tables['listuser']." where listid = $delete $subselect_and");
    $result = Sql_query('delete from '.$tables['listmessage']." where listid = $delete $subselect_and");
  }
  print '..' . $GLOBALS['I18N']->get('Done') . "<br /><hr /><br />\n";
}

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
/*
 *
 * hmm, if lists are marked for a category, which is then removed, this would
 * cause them to not show up
  if (!in_array($current,$aConfiguredListCategories)) {
    $current = '';#$aListCategories[0];
  }
*/
  $_SESSION['last_list_category'] = $current;
  
  if ($subselect == '') {
    $subselect = ' where category = "'.$current.'"';
  } else {
    $subselect .= ' and category = "'.$current.'"';
  }
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
$countquery
= ' select *'
. ' from ' . $tables['list']
. $subselect;
$countresult = Sql_query($countquery);
$total = Sql_Num_Rows($countresult);

print '<p>'.$total .' '. $GLOBALS['I18N']->get('Lists').'</p>';
$limit = '';
/*
 * paging is now dealt with by categories
if ($total > 10) {
  $limit = ' limit 10 offset '.$s;
} else {
  $limit = '';
}
print Paging($baseurl,$s,$total);
*/

$query
= ' select *'
. ' from ' . $tables['list']
. $subselect
. ' order by listorder '.$limit;
$result = Sql_query($query);
$ls = new WebblerListing($GLOBALS['I18N']->get('Lists'));
$ls->noShader();
while ($row = Sql_fetch_array($result)) {
  $query
  = ' select count(*)'
  . ' from ' . $tables['listuser']
  . ' where listid = ?';
  $rsc = Sql_Query_Params($query, array($row["id"]));
  $membercount = Sql_Fetch_Row($rsc);
  if ($membercount[0]<=0) {
    $members = $GLOBALS['I18N']->get('None yet');
  } else {
    $members = $membercount[0];
  }

/*
  $query = sprintf('
  select count(distinct userid) as bouncecount from %s listuser,
  %s umb where listuser.userid = umb.user and listuser.listid = ? ',
  $GLOBALS['tables']['listuser'],$GLOBALS['tables']['user_message_bounce'],$row['id'])

  print $query;
*/
  $bouncecount =
    Sql_Fetch_Row_Query(sprintf('select count(distinct userid) as bouncecount from %s listuser, %s umb where listuser.userid = umb.user and listuser.listid = %s ',$GLOBALS['tables']['listuser'],$GLOBALS['tables']['user_message_bounce'],$row['id']));
  if ($bouncecount[0]<=0) {
    $bounces = $GLOBALS['I18N']->get('None yet');
  } else {
    $bounces = $bouncecount[0];
  }

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

  $element = '<!-- '.$row['id'].'-->'.$row['name'];
  $ls->addElement($element,PageUrl2("editlist&amp;id=".$row["id"]));
  

/*
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
*/
    

/*
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Name'),
      PageLink2("editlist",stripslashes($row['name']),"id=".$row["id"]));
*/
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Order'),
      sprintf('<input type="text" name="listorder[%d]" value="%d" size="5" />',$row['id'],$row['listorder']));
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Members'),
      PageLink2("members",$members,"id=".$row["id"]).' '.PageLink2('importsimple&list='.$row["id"],$GLOBALS['I18N']->get('add')));
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Bounces'),
      PageLink2("listbounces",$bounces,"id=".$row["id"]));#.' '.PageLink2('listbounces&id='.$row["id"],$GLOBALS['I18N']->get('view'))
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Public'),sprintf('<input type="checkbox" name="active[%d]" value="1" %s />',$row["id"],
    $row["active"] ? 'checked="checked"' : ''));
    $ls->addColumn($element,
      $GLOBALS['I18N']->get('Owner'),$GLOBALS['require_login'] ? adminName($row['owner']):$GLOBALS['I18N']->get('n/a'));
    if (trim($desc) != '') {
      $ls->addRow($element,
        $GLOBALS['I18N']->get('Description'),$desc);
    }


  $some = 1;
}
$ls->addSubmitButton('update',$GLOBALS['I18N']->get('Save Changes'));

if (!$some) {
  echo $GLOBALS['I18N']->get('No lists, use Add List to add one');
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
<p>
<?php
if ($canaddlist) {
  print PageLinkButton('editlist',$GLOBALS['I18N']->get('Add a list'));
}
?>
</p>
