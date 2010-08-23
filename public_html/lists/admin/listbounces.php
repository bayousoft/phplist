<?php
#error_reporting(E_ALL);
require_once dirname(__FILE__).'/accesscheck.php';

$listid = empty($_GET['id']) ? 0 : sprintf('%d',$_GET['id']);
$download = isset($_GET['type']) && $_GET['type'] == 'dl';

if (!$listid) {
  $req = Sql_Query(sprintf('select listuser.listid,count(distinct userid) as numusers from %s listuser,
    %s umb, %s lm where listuser.listid = lm.listid and listuser.userid = umb.user group by listuser.listid
    order by listuser.listid',$GLOBALS['tables']['listuser'],$GLOBALS['tables']['user_message_bounce'],$GLOBALS['tables']['listmessage']));
  $ls = new WebblerListing($GLOBALS['I18N']->get('Choose a list'));
  while ($row = Sql_Fetch_Array($req)) {
    $element = $GLOBALS['I18N']->get('list').' '.$row['listid'];
    $ls->addElement($element,PageUrl2('listbounces&amp;id='.$row['listid']));
    $ls->addColumn($element,$GLOBALS['I18N']->get('name'),listName($row['listid']),PageUrl2('editlist&amp;id='.$row['listid']));
    $ls->addColumn($element,$GLOBALS['I18N']->get('# bounced'),$row['numusers']);
  }
  print $ls->display();
  return;
}

$query
= ' select lu.userid, count(umb.bounce) as numbounces'
. ' from %s lu'
. '    join %s umb'
. '       on lu.userid = umb.user'
. ' where '
#. ' current_timestamp < date_add(umb.time,interval 6 month) '
#. ' and ' 
. ' lu.listid = ? '
. ' group by lu.userid '
;
$query = sprintf($query, $GLOBALS['tables']['listuser'], $GLOBALS['tables']['user_message_bounce']);
#print $query;
$req = Sql_Query_Params($query, array($listid));
$total = Sql_Num_Rows($req);
$limit = '';
$numpp = 150;

$s = empty($_GET['s']) ? 0 : sprintf('%d',$_GET['s']);
if ($total > 500 && !$download) {
#  print Paging2('listbounces&amp;id='.$listid,$total,$numpp,'Page');
  $listing = sprintf($GLOBALS['I18N']->get("Listing %s to %s"),$s,$s+$numpp);
  $limit = "limit $s,".$numpp;
  print $total. " ".$GLOBALS['I18N']->get(" Total")."</p>";
  printf ('<table class="bouncesListing" border="1"><tr><td colspan="4" align="center">%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><hr/>',
          $listing,
          PageLink2('listbounces&amp;id='.$listid,"&lt;&lt;","s=0"),
          PageLink2('listbounces&amp;id='.$listid,"&lt;",sprintf('s=%d',max(0,$s-$numpp))),
          PageLink2('listbounces&amp;id='.$listid,"&gt;",sprintf('s=%d',min($total,$s+$numpp))),
          PageLink2('listbounces&amp;id='.$listid,"&gt;&gt;",sprintf('s=%d',$total-$numpp)));

  $query .= $limit;
  $req = Sql_Query_Params($query, array($listid));
}

print '<p class="button">'.PageLink2('listbounces','Select another list').'<br/>';
print '&nbsp;'.PageLink2('listbounces&amp;type=dl&amp;id='.$listid,'Download emails');
print '</p>';
if ($download) {
  ob_end_clean();
  Header("Content-type: text/plain");
  $filename = 'Bounces on '.listName($listid);
  header("Content-disposition:  attachment; filename=\"$filename\"");
}

$ls = new WebblerListing($GLOBALS['I18N']->get('Bounces on').' '.listName($listid));
while ($row = Sql_Fetch_Array($req)) {
  $userdata = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',
    $GLOBALS['tables']['user'],$row['userid']));
  if ($download) {
    print $userdata['email']."\n";
  }

  $ls->addElement($row['userid'],PageUrl2('user&amp;id='.$row['userid']));
  $ls->addColumn($row['userid'],$GLOBALS['I18N']->get('email'),$userdata['email']);
  $ls->addColumn($row['userid'],$GLOBALS['I18N']->get('# bounces'),$row['numbounces']);
}
if (!$download) {
  print $ls->display();
} else {
  exit;
}
