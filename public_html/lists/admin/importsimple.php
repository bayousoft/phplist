<?php
require_once dirname(__FILE__).'/accesscheck.php';

$subselect = '';
@ob_end_flush();

if (!ALLOW_IMPORT) {
  print '<p class="information">'.$GLOBALS['I18N']->get('import is not available').'</p>';
  return;
}

## most basic possible import: big text area to paste emails in
$selected_lists = array();
if (!empty($_POST['importlists'])) {
  $selected_lists = getSelectedLists('importlists');
}

if (!empty($_POST['addnewlist'])) {
  include "editlist.php";
  $selected_lists = array($_SESSION['newlistid'] => $_SESSION['newlistid']);
}

if (!empty($_POST['importcontent'])) {
  $lines = explode("\n",$_POST['importcontent']);
  $count['imported'] = 0;
  $count['duplicate'] = 0;
  $count['processed'] = 0;
  
  $total = count($lines);
  foreach ($lines as $line) {
    if (trim($line) == '') continue;
    $uniqid = getUniqid();
    ## I guess everyone will import all their users wanting to receive HTML ....
    $query = sprintf('insert into %s (email,entered,htmlemail,confirmed,uniqid)
              values("%s",now(),1,1,"%s")', $tables["user"], $line, $uniqid);
    $result = Sql_query($query, 1);
    $userid = Sql_insert_id();
    if (empty($userid)) {
      $count['duplicate']++;
      $idreq = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"', $tables["user"], $line));
      $userid = $idreq[0];
    } else {
      $count['imported']++;
    }

    foreach($selected_lists as $k => $listid) {
      $query = "replace into ".$tables["listuser"]." (userid,listid,entered) values($userid,$listid,current_timestamp)";
      $result = Sql_query($query);
    }

    $count['processed']++;
    if ($count['processed'] % 100 == 0) {
      print $count['processed'].' / '.$total.' '.$GLOBALS['I18N']->get('Imported').'<br/>';
      flush();
    }
  }
  $report = sprintf($GLOBALS['I18N']->get('%d lines processed')."\n",$count['processed']);
  $report .= sprintf($GLOBALS['I18N']->get('%d emails imported')."\n",$count['imported']);
  $report .= sprintf($GLOBALS['I18N']->get('%d duplicates')."\n",$count['duplicate']);

  print ActionResult(nl2br($report));
  sendMail(getConfig("admin_address"), $GLOBALS['I18N']->get('phplist Import Results'), $report);
  return;
}

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("import1");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$_SESSION["logindetails"]["id"];break;
    case "all":
      $subselect = "";break;
    case "none":
    default:
      $subselect = " where id = 0";break;
  }
}

if (isset($_GET['list'])) {
  $id = sprintf('%d',$_GET['list']);
  if (!empty($subselect)) {
    $subselect .= ' and id = '.$id;
  } else {
    $subselect .= ' where id = '.$id;
  }
} 
print PageLinkDialog('addlist',$GLOBALS['I18N']->get('Add a new list'));
print FormStart(' enctype="multipart/form-data" name="import"');

$result = Sql_query("SELECT id,name FROM ".$tables["list"]."$subselect ORDER BY listorder");
$total = Sql_Num_Rows($result);
$c=0;
if ($total == 1) {
  $row = Sql_fetch_array($result);
  printf('<input type="hidden" name="listname[%d]" value="%s"><input type="hidden" name="importlists[%d]" value="%d">'.$GLOBALS['I18N']->get('adding_users').' <b>%s</b>',$c,stripslashes($row["name"]),$c,$row["id"],stripslashes($row["name"]));
} else {
  print '<p class="button">'.$GLOBALS['I18N']->get('Select the lists to add the emails to').'</p>';

  print ListSelectHTML($selected_lists,'importlists',$subselect);
}

print '<p class="information">'.
$GLOBALS['I18N']->get('Please enter the emails to import in the box below, one per line, and click "Import Emails"').'<br/>'.
$GLOBALS['I18N']->get('<b>Warning</b>: the emails you import will not be checked on validity. You can do this later on the "reconcile subscribers" page.').
'</p>';
print '<br/><input type="submit" name="doimport" value="'.$GLOBALS['I18N']->get('Import Emails').'" >';
print '<textarea name="importcontent" rows="10" cols="40"></textarea>';
print '</form>';
