<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<?
require_once "accesscheck.php";


if (isset($delete) && $delete) {
  # delete the index in delete
  print "Deleting $delete ..\n";
  if ($GLOBALS["require_login"] && !isSuperUser()) {
  } else {
    Sql_query("delete from {$tables["bounce"]} where id = $delete");
  }
  print "..Done<br /><hr><br />\n";
}

if ($action) {
	switch($action) {
  	case "deleteprocessed":
    	Sql_Query(sprintf('delete from %s where comment != "not processed" and date_add(date,interval 2 month) < now()',$tables["bounce"]));
      break;
  	case "deleteall":
    	Sql_Query(sprintf('delete from %s',$tables["bounce"]));
      break;
    case "reset":
    	Sql_Query(sprintf('update %s set bouncecount = 0',$tables["user"]));
    	Sql_Query(sprintf('update %s set bouncecount = 0',$tables["message"]));
    	Sql_Query(sprintf('delete from %s',$tables["bounce"]));
    	Sql_Query(sprintf('delete from %s',$tables["user_message_bounce"]));
 	}
}

# view bounces
$count = Sql_Query(sprintf('select count(*) from %s',$tables["bounce"]));
$totalres = Sql_fetch_Row($count);
$total = $totalres[0];

print $total . " bounces<br/>";
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
          PageLink2("bounces","&lt;&lt;","start=0".$find_url),
          PageLink2("bounces","&lt;",sprintf('start=%d',max(0,$start-MAX_USER_PP)).$find_url),
          PageLink2("bounces","&gt;",sprintf('start=%d',min($total,$start+MAX_USER_PP)).$find_url),
          PageLink2("bounces","&gt;&gt;",sprintf('start=%d',$total-MAX_USER_PP).$find_url));
  $result = Sql_query(sprintf('select * from %s order by date desc %s',$tables["bounce"],$limit));
} else {
  $result = Sql_Query(sprintf('select * from %s order by date desc',$tables["bounce"]));
}
#  $result = Sql_Verbose_Query(sprintf('select * from %s where status not like "bounced list message%%" order by date desc',$tables["bounce"]));
#  $result = Sql_Verbose_Query(sprintf('select * from %s where data like "%%systemmessage%%" order by date desc',$tables["bounce"]));

printf("[ <a href=\"javascript:deleteRec2('Are you sure you want to delete all bounces older than 2 months?','%s');\">Delete all processed (&gt; 2 months old)</a> |
   <a href=\"javascript:deleteRec2('Are you sure you want to delete all bounces,\\n even the ones that have not been processed?','%s');\">Delete all</a> |
   <a href=\"javascript:deleteRec2('Are you sure you want to reset all counters?','%s');\">Reset bounces</a> ] ",
   PageURL2("bounces","Delete","start=$start&action=deleteprocessed"),
   PageURL2("bounces","Delete","start=$start&action=deleteall"),
   PageURL2("bounces","Delete","start=$start&action=reset"));

if (!Sql_Affected_Rows())
	print "<p>No unprocessed bounces available</p>";

print "<table><tr><td></td><td>Message</td><td>User</td><td>Date</td></tr>";
while ($bounce = Sql_fetch_array($result)) {
	if (preg_match("#bounced list message ([\d]+)#",$bounce["status"],$regs)) {
  	$messageid = sprintf('<a href="./?page=message&id=%d">%d</a>',$regs[1],$regs[1]);
	} elseif ($bounce["status"] == "bounced system message") {
  	$messageid = "System Message";
	} else {
  	$messageid = "Unknown";
 	}
  if (preg_match("#([\d]+) bouncecount increased#",$bounce["comment"],$regs)) {
  	$userid = sprintf('<a href="./?page=user&id=%d">%d</a>',$regs[1],$regs[1]);
  } elseif (preg_match("#([\d]+) marked unconfirmed#",$bounce["comment"],$regs)) {
  	$userid = sprintf('<a href="./?page=user&id=%d">%d</a>',$regs[1],$regs[1]);
  } else {
  	$userid = "Unknown";
  }

  printf( "<tr><td>[ <a href=\"javascript:deleteRec('%s');\">delete</a> |
   %s ] </td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
   PageURL2("bounces","delete","start=$start&delete=".$bounce["id"]),
   PageLink2("bounce","show","start=$start&id=".$bounce["id"]),
   $messageid,
   $userid,
   $bounce["date"]
   );
}
print "</table>";
?>
