<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>


<?php
require_once "accesscheck.php";

if ($_GET["filter"]) {
	if ($_GET["exclude"])
		$where = ' where page not like "%'.$_GET["filter"].'%" and entry not like "%'.$_GET["filter"].'%"';
  else
		$where = ' where page like "%'.$_GET["filter"].'%" or entry like "%'.$_GET["filter"].'%"';
  $find_url = '&filter='.urlencode($_GET["filter"]).'&exclude='.$_GET["exclude"];
}
$order = ' order by entered desc';

if (isset($delete) && $delete) {
  # delete the index in delete
  print "Deleting $delete ..\n";
  if ($require_login && !isSuperUser()) {
  } else {
    Sql_query("delete from {$tables["eventlog"]} where id = $delete");
  }
  print "..Done<br /><hr><br />\n";
}

if ($action) {
	switch($action) {
  	case "deleteprocessed":
    	Sql_Query(sprintf('delete from %s where date_add(entered,interval 2 month) < now()',$tables["eventlog"]));
      break;
  	case "deleteall":
    	Sql_Query(sprintf('delete from %s %s',$tables["eventlog"],$where));
      break;
 	}
}

# view events
$count = Sql_Query("select count(*) from {$tables["eventlog"]} $where");
$totalres = Sql_fetch_Row($count);
$total = $totalres[0];

print $total . " events<br/>";
if ($total > MAX_USER_PP) {
  if (isset($start) && $start) {
    $listing = "Listing $start to " . ($start + MAX_USER_PP);
    $limit = "limit $start,".MAX_USER_PP;
  } else {
    $listing = "Listing 1 to 50";
    $limit = "limit 0,50";
    $start = 0;
  }
  printf ('<table border=1><tr><td colspan=4 align=center>%s</td></tr><tr><td>%s</td><td>%s</td><td>
          %s</td><td>%s</td></tr></table><p><hr>',
          $listing,
          PageLink2("eventlog","&lt;&lt;","start=0".$find_url),
          PageLink2("eventlog","&lt;",sprintf('start=%d',max(0,$start-MAX_USER_PP)).$find_url),
          PageLink2("eventlog","&gt;",sprintf('start=%d',min($total,$start+MAX_USER_PP)).$find_url),
          PageLink2("eventlog","&gt;&gt;",sprintf('start=%d',$total-MAX_USER_PP).$find_url));
  $result = Sql_query(sprintf('select * from %s %s order by entered desc %s',$tables["eventlog"],$where,$limit));
} else {
  $result = Sql_Query(sprintf('select * from %s %s order by entered desc',$tables["eventlog"],$where));
}

printf("[ <a href=\"javascript:deleteRec2('Are you sure you want to delete all events older than 2 months?','%s');\">Delete all (&gt; 2 months old)</a> |
   <a href=\"javascript:deleteRec2('Are you sure you want to delete all events matching this filter?','%s');\">Delete all</a> ] ",
   PageURL2("eventlog","Delete","start=$start&action=deleteprocessed"),
   PageURL2("eventlog","Delete","start=$start&action=deleteall$find_url"));

if (!Sql_Affected_Rows())
	print "<p>No events available</p>";

print '<br/><br/>';
printf('<form method=get>
<input type=hidden name="page" value="eventlog">
<input type=hidden name="s" value="%d">
Filter: <input type=text name="filter" value="%s"> Exclude filter <input type=checkbox name="exclude" value="1" %s>
</form><br/>',$_GET["s"],$_GET["filter"],$_GET["exclude"] == 1? "checked":"");
$ls = new WebblerListing("Events");
while ($event = Sql_fetch_array($result)) {
  $ls->addElement($event["id"]);
  $ls->addColumn($event["id"],"del",
    sprintf('<a href="javascript:deleteRec(\'%s\');">del</a>',
      PageURL2("eventlog","delete","start=$start&delete=".$event["id"])));
  $ls->addColumn($event["id"],"page",$event["page"]);
  $ls->addColumn($event["id"],"date",$event["entered"]);
  $ls->addColumn($event["id"],"message",$event["entry"]);
}
print $ls->display();
?>
