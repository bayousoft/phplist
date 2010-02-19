
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<script language="Javascript" type="text/javascript">
  function updateStatus(id,content) {
    var el = document.getElementById("messagestatus"+id);
    el.innerHTML = content;
  }

  function statusError(id) {
    var el = document.getElementById("messagestatus"+id);
    el.innerHTML = "Unable to fetch progress";
  }

  function fetchProgress(id) {
    var req = new AjaxRequest();
    AjaxRequest.get(
      {
        'id': id
        ,'url':'./?page=msgstatus'
        ,'onSuccess':function(req){ updateStatus(id,req.responseText) }
        ,'onError':function(req){ statusError(); }
      }
    );
  }

</script>


<hr/>

<?php

require_once dirname(__FILE__).'/accesscheck.php';

$subselect = $where = '';

if( !$GLOBALS["require_login"] || $_SESSION["logindetails"]['superuser'] ){
  $ownerselect_and = '';
  $ownerselect_where = '';
} else {
  $ownerselect_where = ' where owner = ' . $_SESSION["logindetails"]['id'];
  $ownerselect_and = ' and owner = ' . $_SESSION["logindetails"]['id'];
}
if (isset($_GET['start'])) {
  $start = sprintf('%d',$_GET['start']);
} else {
  unset($start);
}

# remember last one listed
if (!isset($_GET["type"]) && !empty($_SESSION["lastmessagetype"])) {
  $_GET["type"] = $_SESSION["lastmessagetype"];
} elseif (isset($_GET["type"])) {
  $_SESSION["lastmessagetype"] = $_GET["type"];
}

#print '<p class="x">'.PageLink2("messages&type=sent","Sent Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=draft","Draft Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=queue","Queued Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=stat","Static Messages").'&nbsp;&nbsp;&nbsp;';
//obsolete, moved to rssmanager plugin
#if (ENABLE_RSS) {
#  print PageLink2("messages&type=rss","rss Messages").'&nbsp;&nbsp;&nbsp;';
#}
#print '</p>';

### Print tabs
$tabs = new WebblerTabs();
$tabs->addTab($GLOBALS['I18N']->get("sent"),PageUrl2("messages&amp;type=sent"));
$tabs->addTab($GLOBALS['I18N']->get("active"),PageUrl2("messages&amp;type=active"));
$tabs->addTab($GLOBALS['I18N']->get("draft"),PageUrl2("messages&amp;type=draft"));
#$tabs->addTab($GLOBALS['I18N']->get("queued"),PageUrl2("messages&amp;type=queued"));#
if (USE_PREPARE) {
  $tabs->addTab($GLOBALS['I18N']->get("static"),PageUrl2("messages&amp;type=static"));
}
//obsolete, moved to rssmanager plugin
#if (ENABLE_RSS) {
#  $tabs->addTab("rss",PageUrl2("messages&amp;type=rss"));
#}
if (!empty($_GET['type'])) {
  $tabs->setCurrent($_GET["type"]);
} else {
  $_GET['type'] = 'sent';
  $tabs->setCurrent('sent');
}

print $tabs->display();

### Process 'Action' requests
if (!empty($_GET["delete"])) {
  $todelete = array();
  if ($_GET["delete"] == "draft") {
    $req = Sql_Query(sprintf('select id from %s where status = "draft" and (subject = "" or subject = "(no subject)") %s',$GLOBALS['tables']["message"],$ownerselect_and));
    while ($row = Sql_Fetch_Row($req)) {
      array_push($todelete,$row[0]);
    }
  } else {
    array_push($todelete,sprintf('%d',$_GET["delete"]));
  }
  foreach ($todelete as $delete) {
    print $GLOBALS['I18N']->get("Deleting")." $delete ...";
    $del = deleteMessage($delete);
    if ($del)
      print "... ".$GLOBALS['I18N']->get("Done");
    else
      print "... ".$GLOBALS['I18N']->get("failed");
    print '<br/>';
  }
  print "<hr /><br />\n";
}

if (isset($_GET['resend'])) {
  $resend = sprintf('%d',$_GET['resend']);
  # requeue the message in $resend
  print $GLOBALS['I18N']->get("Requeuing")." $resend ..";
  $result = Sql_Query("update ${tables['message']} set status = 'submitted', sendstart = current_timestamp where id = $resend");
  $suc6 = Sql_Affected_Rows();
  # only send it again to users, if we are testing, otherwise only to new users
  if (TEST)
    $result = Sql_query("delete from ${tables['usermessage']} where messageid = $resend");
  if ($suc6)
    print "... ".$GLOBALS['I18N']->get("Done");
  else
    print "... ".$GLOBALS['I18N']->get("failed");
  print '<br /><hr /><br />\n';
}

if (isset($_GET['suspend'])) {
  $suspend = sprintf('%d',$_GET['suspend']);
  print $GLOBALS['I18N']->get('Suspending')." $suspend ..";
  $result = Sql_query(sprintf('update %s set status = "suspended" where id = %d and (status = "inprocess" or status = "submitted")',$tables["message"],$suspend));
  $suc6 = Sql_Affected_Rows();
  if ($suc6)
    print "... ".$GLOBALS['I18N']->get("Done");
  else
    print "... ".$GLOBALS['I18N']->get("failed");
  print'<br /><hr /><br />\n';
}
#0012081: Add new 'Mark as sent' button
if (isset($_GET['markSent'])) {
  $markSent = sprintf('%d',$_GET['markSent']);
  print $GLOBALS['I18N']->get('Marking as sent ')." $markSent ..";
  $result = Sql_query(sprintf('update %s set status = "sent" where id = %d and (status = "suspended")',$tables["message"],$markSent));
  $suc6 = Sql_Affected_Rows();
  if ($suc6)
    print "... ".$GLOBALS['I18N']->get("Done");
  else
    print "... ".$GLOBALS['I18N']->get("Failed");
  print '<br /><hr /><br />\n';
}

$cond = array();
### Switch tab
switch ($_GET["type"]) {
  case "queued":
#    $subselect = ' status in ("submitted") and (rsstemplate is NULL or rsstemplate = "") ';
    $cond[] = " status in ('submitted', 'suspended') ";
    $url_keep = '&amp;type=queued';
    break;
  case "static":
    $cond[] = " status in ('prepared') ";
    $url_keep = '&amp;type=static';
    break;
#  case "rss":
#    $subselect = ' rsstemplate != ""';
#    $url_keep = '&amp;type=sent';
#    break;
  case "draft":
    $cond[] = " status in ('draft') ";
    $url_keep = '&amp;type=draft';
    break;
  case "active":
    $cond[] = " status in ('inprocess','submitted', 'suspended') ";
    $url_keep = '&amp;type=active';
    break;
  case "sent":
  default:
    $cond[] = " status in ('sent') ";
    $url_keep = '&amp;type=sent';
    break;
}

### Query messages from db
if ($GLOBALS['require_login'] && !$_SESSION['logindetails']['superuser']) {
  $cond[] = ' owner = ' . $_SESSION['logindetails']['id'];
}
$where = ' where ' . join(' and ', $cond);

$req = Sql_query('select count(*) from ' . $tables['message']. $where);
$total_req = Sql_Fetch_Row($req);
$total = $total_req[0];
$end = isset($start) ? $start + MAX_MSG_PP : MAX_MSG_PP;
if ($end > $total) $end = $total;

## Browse buttons table
$limit = MAX_MSG_PP;
$offset = 0;
if (isset($start) && $start > 0) {
  $listing = $GLOBALS['I18N']->get("Listing message")." $start ".$GLOBALS['I18N']->get("to")." " . $end;
  $offset = $start;
} else {
  $listing =  $GLOBALS['I18N']->get("Listing message 1 to")." ".$end;
  $start = 0;
}
  print $total. " ".$GLOBALS['I18N']->get("Messages")."</p>";
if ($total > MAX_MSG_PP)
  printf ('<table class="messagesListing" border="1"><tr><td colspan="4" align=center>%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><hr/>',
          $listing,
          PageLink2("messages$url_keep","&lt;&lt;","start=0"),
          PageLink2("messages$url_keep","&lt;",sprintf('start=%d',max(0,$start-MAX_MSG_PP))),
          PageLink2("messages$url_keep","&gt;",sprintf('start=%d',min($total,$start+MAX_MSG_PP))),
          PageLink2("messages$url_keep","&gt;&gt;",sprintf('start=%d',$total-MAX_MSG_PP)));
if ($_GET["type"] == "draft") {
  print '<p class="delete">'.PageLink2("messages&amp;delete=draft",$GLOBALS['I18N']->get("Delete all draft messages without subject")).'</p>';
}

?>

<table class="messagesListing" border="1">
<tr>
<?php

$ls = new WebblerListing($I18N->get('messages'));

## messages table
if ($total) {
  print "<td>".$GLOBALS['I18N']->get("Message info")."</td><td>".$GLOBALS['I18N']->get("Status")."</td><td>".$GLOBALS['I18N']->get("Action")."</td></tr>";
  $result = Sql_query("SELECT * FROM ".$tables["message"]." $where order by status,entered desc limit $limit offset $offset");
  while ($msg = Sql_fetch_array($result)) {
    $listingelement = $msg['id'];

    $uniqueviews = Sql_Fetch_Row_Query("select count(userid) from {$tables["usermessage"]} where viewed is not null and messageid = ".$msg["id"]);

    ## need a better way to do this, it's way too slow '
    #$clicks = Sql_Fetch_Row_Query("select sum(clicked) from {$tables["linktrack"]} where messageid = ".$msg["id"]);
    $clicks = array(0);

    $messagedata = loadMessageData($msg['id']);
    printf ('<tr><td valign="top"><table class="messagesListing">
      <tr><td valign="top">'.$GLOBALS['I18N']->get("From:").'</td><td valign="top">%s</td></tr>
      <tr><td valign="top">'.$GLOBALS['I18N']->get("Subject:").'</td><td valign="top">%s</td></tr>
      <tr><td valign="top">'.$GLOBALS['I18N']->get("Entered:").'</td><td valign="top">%s</td></tr>
      <tr><td valign="top">'.$GLOBALS['I18N']->get("Embargo:").'</td><td valign="top">%s</td></tr>
      </table>
      </td>',
      stripslashes($msg["fromfield"]),
      stripslashes($msg["subject"]),
      $msg["entered"],
      $msg["embargo"]
    );

    if ($clicks[0]) {
      $clicked = sprintf('<tr><td></td>
        <td align="right" colspan="2">
        <b>'.$GLOBALS['I18N']->get('Clicks').'</b></td>
        <td align="center"><b>%d</b></td></tr>
        ',$clicks[0]);
    } else {
      $clicked = '';
    }

    $status = '';
    ## Rightmost two columns per message
    if ($msg['status'] == 'sent') {
      $status = $GLOBALS['I18N']->get("Sent").": ".$msg['sent'].'<br/>'.$GLOBALS['I18N']->get("Time to send").': '.timeDiff($msg["sendstart"],$msg["sent"]);

      if ($msg['viewed']) {
        $viewed = sprintf('<tr><td></td>
          <td align="right" colspan="2">
          <b>'.$GLOBALS['I18N']->get("Viewed").'</b></td>
          <td align="center"><b>%d</b></td></tr>
          <tr><td></td><td align="right" colspan="2">
          <b>'.$GLOBALS['I18N']->get("Unique Views").'</b></td>
          <td align="center"><b>%d</b></td></tr>
          ',$msg["viewed"],$uniqueviews[0]);
      } else {
        $viewed = '';
      }

      $sendstats =
        sprintf('<br /><table class="messageStats" border="1">
        <tr>
          <td>'.$GLOBALS['I18N']->get("total").'</td>
          <td>'.$GLOBALS['I18N']->get("text").'</td>
          <td>'.$GLOBALS['I18N']->get("html").'</td>
          <td>'.$GLOBALS['I18N']->get("PDF").'</td>
          <td>'.$GLOBALS['I18N']->get("both").'</td>
        </tr>
        <tr>
          <td align="center"><b>%d</b></td>
          <td align="center"><b>%d</b></td>
          <td align="center"><b>%d</b></td>
          <td align="center"><b>%d</b></td>
          <td align="center"><b>%d</b></td>
        </tr>
        %s
        %s
        %s
        </table>',
        $msg["processed"],
        $msg["astext"],
        $msg["ashtml"] + $msg["astextandhtml"], //bug 0009687
        $msg["aspdf"],
        $msg["astextandpdf"],
        $viewed,
        $clicked,
        $msg["bouncecount"] ? sprintf('<tr><td></td><td align="right" colspan="2"><b>'.$GLOBALS['I18N']->get("Bounced").'</b></td><td align="center"><b>%d</b></td></tr>
        ',$msg["bouncecount"]):""
        );
    } else { ##Status <> sent
//      $status = $msg['status'].'<br/>'.$msg['rsstemplate']; //Obsolete by rssmanager plugin
      if ($msg['status'] == 'inprocess') {
/*        $status .= '<br/>'.
        '<meta http-equiv="Refresh" content="300" />'.
        $messagedata['to process'].' '.$GLOBALS['I18N']->get('still to process').'<br/>'.
        $GLOBALS['I18N']->get('ETA').': '.$messagedata['ETA'].'<br/>'.
        $GLOBALS['I18N']->get('Processing').' '.sprintf('%d',$messagedata['msg/hr']).' '.$GLOBALS['I18N']->get('msgs/hr')
        ;*/
        ## try with ajax
      #  $status .= '<br/>';
        $status = '
        <script type="text/javascript">
        function fetchProgress'.$msg['id'].'() {
          fetchProgress('.$msg['id'].');
          setTimeout(fetchProgress'.$msg['id'].',3000);
        }
        fetchProgress'.$msg['id'].'();
        </script>
        ';
        $status .= '<div id="messagestatus'.$msg['id'].'"></div>';
      }
      $sendstats = '';
    }
    if ($msg['status'] == 'inprocess' || $msg['status'] == 'submitted') {
      $status .= '<br/>'.
        PageLink2('messages&suspend='.$msg['id'],$GLOBALS['I18N']->get('Suspend Sending'));
    }
    #0012081: Add new 'Mark as sent' button
    if ($msg['status'] == 'suspended') {
      $status .= '<br/>'.
        PageLink2('messages&markSent='.$msg['id'],$GLOBALS['I18N']->get('Mark as sent'));
    }

    $totalsent = $msg['astext'] + $msg['ashtml'] + $msg['astextandhtml'] + $msg['aspdf'] + $msg['astextandpdf'];

    ## allow plugins to add information
    foreach ($GLOBALS['plugins'] as $plugin) {
      if (method_exists($plugin,'displayMessages')) {
        $plugin->displayMessages($msg, $status);
      }
    }

    $deletelink = '';
    if ($msg['status'] == 'draft') {
      ## only draft messages should be deletable, the rest isn't
      $deletelink = sprintf('<a href="javascript:deleteRec(\'%s\');">'.$GLOBALS['I18N']->get("delete").'</a>',
PageURL2("messages$url_keep","","delete=".$msg["id"]));
    }

    printf('
      <td>
      %s<br />
      </td><td>
      %s<br />
      %s<br />
      %s<br />
      %s
      %s
      </td>
      </tr>',
      $status.
      $sendstats,
      PageLink2("message",$GLOBALS['I18N']->get("View"),"id=".$msg["id"]),
      $msg['status'] != 'inprocess' ? PageLink2("messages",$GLOBALS['I18N']->get("Requeue"),"resend=".$msg["id"]) : $totalsent." ".$GLOBALS['I18N']->get("sent"),
      $msg["status"] != 'prepared' ? PageLink2("send",$GLOBALS['I18N']->get("Edit"),"id=".$msg["id"]) : PageLink2("preparesend",$GLOBALS['I18N']->get("Edit"),"id=".$msg["id"]),
      $clicks[0] && CLICKTRACK ? PageLink2("mclicks",$GLOBALS['I18N']->get("click stats"),"id=".$msg["id"]).'<br/>':'',
      $deletelink
    );
  }
}

?>

</table>


