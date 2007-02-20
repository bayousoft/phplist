<?php

# click stats per message
require_once dirname(__FILE__).'/accesscheck.php';

if (isset($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
} else {
  $id = 0;
}

$access = accessLevel('mclicks');
switch ($access) {
  case 'owner':
    $subselect = ' and owner = ' . $_SESSION["logindetails"]["id"];
    if ($id) {
      $allow = Sql_Fetch_Row_query(sprintf('select owner from %s where id = %d %s',$GLOBALS['tables']['message'],$id,$subselect));
      if ($allow[0] != $_SESSION["logindetails"]["id"]) {
        print $GLOBALS['I18N']->get('You do not have access to this page');
        return;
      }
    }
    break;
  case 'all':
    $subselect = '';
    break;
  case 'none':
  default:
    $subselect = ' where id = 0';
    print $GLOBALS['I18N']->get('You do not have access to this page');
    return;
    break;
}

if (!$id) {
  print $GLOBALS['I18N']->get('Select Message to view');
/*  $req = Sql_Query(sprintf('select distinct messageid, subject, sum(clicked) as totalclicks, count(distinct userid) as users, count(distinct linkid) as linkcount from %s as linktrack, %s as message
    where clicked and linktrack.messageid = message.id %s group by messageid order by entered desc limit 50',
    $GLOBALS['tables']['linktrack'],$GLOBALS['tables']['message'],$subselect));*/
  $req = Sql_Query(sprintf('select distinct messageid, subject, total, count(forwardid) as linkcount,clicked as totalclicks,htmlclicked,textclicked from %s as linktrack_ml, %s as message
    where clicked and linktrack_ml.messageid = message.id %s group by messageid order by entered desc limit 50',
    $GLOBALS['tables']['linktrack_ml'],$GLOBALS['tables']['message'],$subselect));
  if (!Sql_Affected_Rows()) {
    print '<p>'.$GLOBALS['I18N']->get('There are currently no messages to view').'</p>';
  }
  $ls = new WebblerListing($GLOBALS['I18N']->get('Available Messages'));
  while ($row = Sql_Fetch_Array($req)) {
    $ls->addElement($row['messageid'].' '.substr($row['subject'],0,50),PageURL2('mclicks&amp;id='.$row['messageid']));
    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('links'),$row['linkcount']);
    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('clicks'),$row['totalclicks']);
#    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('users'),$row['users']);
    $perc = sprintf('%0.2f',($row['totalclicks'] / $row['total'] * 100));
    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('rate'),$perc.' %');
    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('html'),$row['htmlclicked']);
    $ls->addColumn($row['messageid'].' '.substr($row['subject'],0,50),$GLOBALS['I18N']->get('text'),$row['textclicked']);
  }
  print $ls->display();
  return;
}

print '<h1>'.$GLOBALS['I18N']->get('Click Details for a Message').'</h1>';
$messagedata = Sql_Fetch_Array_query("SELECT * FROM {$tables['message']} where id = $id $subselect");
print '<table>
<tr><td>'.$GLOBALS['I18N']->get('Subject').'<td><td>'.$messagedata['subject'].'</td></tr>
<tr><td>'.$GLOBALS['I18N']->get('Entered').'<td><td>'.$messagedata['entered'].'</td></tr>
<tr><td>'.$GLOBALS['I18N']->get('Sent').'<td><td>'.$messagedata['sent'].'</td></tr>
</table><hr/>';

$ls = new WebblerListing($GLOBALS['I18N']->get('Message Click Statistics'));

$req = Sql_Query(sprintf('select url,firstclick,date_format(latestclick,
  "%%e %%b %%Y %%H:%%i") as latestclick,total,clicked,htmlclicked,textclicked,forwardid from %s ml, %s forward  where ml.messageid = %d and ml.forwardid = forward.id',$GLOBALS['tables']['linktrack_ml'],$GLOBALS['tables']['linktrack_forward'],$id));
$summary = array();
while ($row = Sql_Fetch_Array($req)) {

  if (CLICKTRACK_SHOWDETAIL) {
    $uniqueclicks = Sql_Fetch_Array_Query(sprintf('select count(distinct userid) as users from %s
      where messageid = %d and forwardid = %d',
      $GLOBALS['tables']['linktrack_uml_click'],$id,$row['forwardid']));
  }
#  $element = sprintf('<a href="%s" target="_blank" class="url" title="%s">%s</a>',$row['url'],$row['url'],substr(str_replace('http://','',$row['url']),0,50));

  $url = $row['url'];
  $url = str_replace('http://','',$url);
  if (strlen($url) > 50) {
    $display = substr($url,0,20).'...'.substr($url,-25);
  } else {
    $display = $url;
  }

  $element = sprintf('<div title="%s" ondblclick="alert(\'%s\');">%s</div>',htmlspecialchars($row['url']),htmlspecialchars($row['url']),$display);
  $ls->addElement($element);
  $ls->addColumn($element,$GLOBALS['I18N']->get('firstclick'),formatDateTime($row['firstclick'],1));
  $ls->addColumn($element,$GLOBALS['I18N']->get('latestclick'),$row['latestclick']);
  $ls->addColumn($element,$GLOBALS['I18N']->get('sent'),$row['total']);
  $ls->addColumn($element,$GLOBALS['I18N']->get('clicks'),$row['clicked']);
  $ls->addColumn($element,$GLOBALS['I18N']->get('html'),$row['htmlclicked']);
  $ls->addColumn($element,$GLOBALS['I18N']->get('text'),$row['textclicked']);
  $perc = sprintf('%0.2f',($row['clicked'] / $row['total'] * 100));
  $ls->addColumn($element,$GLOBALS['I18N']->get('clickrate'),$perc.'%');
  if (CLICKTRACK_SHOWDETAIL) {
    $ls->addColumn($element,$GLOBALS['I18N']->get('unique clicks'),$uniqueclicks['users']);
    $perc = sprintf('%0.2f',($uniqueclicks['users'] / $row['total'] * 100));
    $ls->addColumn($element,$GLOBALS['I18N']->get('unique clickrate'),$perc.'%');
    $summary['uniqueclicks'] += $uniqueclicks['users'];
  }
  $summary['totalclicks'] += $row['clicked'];
  $ls->addColumn($element,$GLOBALS['I18N']->get('who'),
    PageLink2('userclicks&amp;msgid='.$id.'&amp;fwdid='.$row['forwardid'],$GLOBALS['I18N']->get('view users')));
}
$ls->addElement('total');
$ls->addColumn('total',$GLOBALS['I18N']->get('clicks'),$summary['totalclicks']);
if (CLICKTRACK_SHOWDETAIL) {
  $ls->addColumn('total',$GLOBALS['I18N']->get('unique clicks'),$summary['uniqueclicks']);
}
print $ls->display();
?>