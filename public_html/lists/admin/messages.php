
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<hr>

<?php
require_once "accesscheck.php";

# remember last one listed
if (!$_GET["type"] && $_SESSION["lastmessagetype"]) {
  $_GET["type"] = $_SESSION["lastmessagetype"];
} elseif ($_GET["type"]) {
  $_SESSION["lastmessagetype"] = $_GET["type"];
}

#print '<p>'.PageLink2("messages&type=sent","Sent Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=draft","Draft Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=queue","Queued Messages").'&nbsp;&nbsp;&nbsp;';
#print PageLink2("messages&type=stat","Static Messages").'&nbsp;&nbsp;&nbsp;';
#if (ENABLE_RSS) {
#	print PageLink2("messages&type=rss","RSS Messages").'&nbsp;&nbsp;&nbsp;';
#}
#print '</p>';
$tabs = new WebblerTabs();
$tabs->addTab("sent",PageUrl2("messages&type=sent"));
$tabs->addTab("draft",PageUrl2("messages&type=draft"));
$tabs->addTab("queued",PageUrl2("messages&type=queued"));#
if (USE_PREPARE) {
  $tabs->addTab("static",PageUrl2("messages&type=static"));
}
if (ENABLE_RSS) {
  $tabs->addTab("rss",PageUrl2("messages&type=rss"));
}
$tabs->setCurrent($_GET["type"]);
  
print $tabs->display();

if ($delete) {
  # delete the index in delete
  print "Deleting $delete ..";
  $result = Sql_query("delete from ".$tables["message"]." where id = $delete");
  $suc6 = Sql_Affected_Rows();
  $result = Sql_query("delete from ".$tables["usermessage"]." where messageid = $delete");
  $result = Sql_query("delete from ".$tables["listmessage"]." where messageid = $delete");
  if ($suc6)
	  print "..Done";
  else
  	print "..failed";
  print "<br /><hr /><br />\n";
}

if ($resend) {
  # requeue the message in $resend
  print "Requeuing $resend ..";
  $result = Sql_query("update ".$tables["message"]." set status = \"submitted\" where id = $resend");
  $suc6 = Sql_Affected_Rows();
  # only send it again to users, if we are testing, otherwise only to new users
  if (TEST)
    $result = Sql_query("delete from ".$tables["usermessage"]." where messageid = $resend");
  if ($suc6)
	  print "..Done";
  else
  	print "..failed";
  print"<br /><hr /><br /><p>\n";
}

switch ($_GET["type"]) {
	case "queued":
		$subselect = ' status in ("submitted") and (rsstemplate is NULL or rsstemplate = "") ';
		$url_keep = '&type=queued';
		break;
	case "static":
		$subselect = ' status in ("prepared") ';
		$url_keep = '&type=static';
		break;
	case "rss":
		$subselect = ' rsstemplate != ""';
		$url_keep = '&type=sent';
		break;
	case "draft":
		$subselect = ' status in ("draft") ';
		$url_keep = '&type=draft';
		break;
	case "sent":
	default:
		$subselect = ' status in ("sent","inprocess") ';
		$url_keep = '&type=sent';
		break;
}

if( !$GLOBALS["require_login"] || $_SESSION["logindetails"]['superuser'] ){
	$subselect= ' where '.$subselect;
} else {
  $subselect = 'WHERE owner = ' . $_SESSION["logindetails"]['id'] .' and '.$subselect;
}
$req = Sql_query("SELECT count(*) FROM " . $tables["message"].' '.$subselect);

$total_req = Sql_Fetch_Row($req);
$total = $total_req[0];
if (isset($start) && $start > 0) {
  $listing = "Listing message $start to " . ($start + MAX_MSG_PP);
  $limit = "limit $start,".MAX_MSG_PP;
} else {
  $listing =  "Listing message 1 to ".MAX_MSG_PP;
  $limit = "limit 0,".MAX_MSG_PP;
  $start = 0;
}
	print $total. " Messages</p>";
if ($total)
  printf ('<table border=1><tr><td colspan=4 align=center>%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><p><hr>',
          $listing,
          PageLink2("messages$url_keep","&lt;&lt;","start=0"),
          PageLink2("messages$url_keep","&lt;",sprintf('start=%d',max(0,$start-MAX_MSG_PP))),
          PageLink2("messages$url_keep","&gt;",sprintf('start=%d',min($total,$start+MAX_MSG_PP))),
          PageLink2("messages$url_keep","&gt;&gt;",sprintf('start=%d',$total-MAX_MSG_PP)));

?>
<table border=1>
<tr>
<?php

if ($total) {
  print "<td>Message info</td><td>Status</td><td>Action</td></tr>";
  $result = Sql_query("SELECT * FROM ".$tables["message"]." $subselect order by entered desc $limit");
  while ($msg = Sql_fetch_array($result)) {
  	$uniqueviews = Sql_Fetch_Row_Query("select count(userid) from {$tables["usermessage"]} where viewed is not null and messageid = ".$msg["id"]);
    printf ('<tr><td valign="top"><table>
      <tr><td valign="top">From:</td><td valign="top">%s</td></tr>
      <tr><td valign="top">Subject:</td><td valign="top">%s</td></tr>
      <tr><td valign="top">Entered:</td><td valign="top">%s</td></tr>
      <tr><td valign="top">Embargo:</td><td valign="top">%s</td></tr>
      </table>
      </td><td>
      %s<br />
      </td><td>
      %s<br />
			%s<br />
      %s<br />
      <a href="javascript:deleteRec(\'%s\');">delete</a>
      </td>
      </tr>',
      stripslashes($msg["fromfield"]),
      stripslashes($msg["subject"]),
      $msg["entered"],
      $msg["embargo"],
      $msg["status"] == "sent"?
      	"Sent: ".$msg["sent"].'<br/>Time to send: '.timeDiff($msg["sendstart"],$msg["sent"]).
       	sprintf('<br /><table border=1>
        <tr><td>total</td><td>text</td><td>html</td><td>both</td><td>PDF</td><td>both</td></tr>
        <tr><td align="center"><b>%d</b></td><td align="center"><b>%d</b></td><td align="center"><b>%d</b></td><td align="center"><b>%d</b></td><td align="center"><b>%d</b></td><td align="center"><b>%d</b></td></tr>
        %s
        %s
        </table>'
        ,
        $msg["processed"],
        $msg["astext"],
        $msg["ashtml"],
        $msg["astextandhtml"],
        $msg["aspdf"],
        $msg["astextandpdf"],
        $msg["viewed"] ? sprintf('<tr><td></td><td align="right" colspan=2><b>Viewed</b></td><td align="center"><b>%d</b></td></tr>
        													<tr><td></td><td align="right" colspan=2><b>Unique Views</b></td><td align="center"><b>%d</b></td></tr>
        ',$msg["viewed"],$uniqueviews[0]
        ):"",
        $msg["bouncecount"] ? sprintf('<tr><td></td><td align="right" colspan=2><b>Bounced</b></td><td align="center"><b>%d</b></td></tr>
        ',$msg["bouncecount"]
        ):"")
        : $msg["status"]."<br/>".$msg["rsstemplate"],
      PageLink2("message","View","id=".$msg["id"]),
      $msg["status"] != "inprocess" ? PageLink2("messages","Requeue","resend=".$msg["id"]) : $msg["processed"] . " done",
      $msg["status"] != "prepared" ? PageLink2("send","Edit","id=".$msg["id"]) : PageLink2("preparesend","Edit","id=".$msg["id"]),
      PageURL2("messages$url_keep","","delete=".$msg["id"])
      );
  }
}

?>

</table>

