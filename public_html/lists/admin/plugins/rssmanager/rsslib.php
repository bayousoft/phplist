<?php
//require_once dirname(__FILE__) . '/../../accesscheck.php';

trigger_error('Remove rsslib from your includes, it\'s functions are now static functions of the rssmanager plugin objectl', E_USER_WARNING);

//function parseRSSTemplate($template, $data) {
//  foreach ($data as $key => $val) {
//    if (!preg_match("#^\d+$#", $key)) {
//      #      print "$key => $val<br/>";
//      $template = preg_replace('#\[' . preg_quote($key) . '\]#i', $val, $template);
//    }
//  }
//  $template = eregi_replace("\[[A-Z\. ]+\]", "", $template);
//  return $template;
//}
//
//function updateRSSStats($items, $type) {
//  global $tables;
//  if (!is_array($items))
//    return;
//  foreach ($items as $item) {
//    Sql_Query("update {$tables["rssitem"]} set $type = $type + 1 where id = $item");
//  }
//}
//
//function rssUserHasContent($userid, $messageid, $frequency) {
//  global $tables;
//
//  # get selection string for mysql data_add function
//  switch ($frequency) {
//    case "weekly" :
//      $interval = 'interval 7 day';
//      break;
//    case "monthly" :
//      $interval = 'interval 1 month';
//      break;
//    case "daily" :
//    default :
//      $interval = 'interval 1 day';
//      break;
//  }
//
//  $cansend_req = Sql_Query(sprintf('
//    SELECT date_add(last,%s) < current_timestamp FROM %s 
//    WHERE userid = %d', 
//    $interval, $tables["user_rss"], $userid));
//  $exists = Sql_Affected_Rows();
//  $cansend = Sql_Fetch_Row($cansend_req);
//  if (!$exists || $cansend[0]) {
//    # we can send this user as far as the frequency is concerned
//    # now check whether there is actually some content
//
//    # check what lists to use. This is the intersection of the lists for the
//    # user and the lists for the message
//    $lists = array ();
//    $listsreq = Sql_Query(sprintf('
//          SELECT %s.listid FROM %s,%s 
//          WHERE %s.listid = %s.listid and %s.userid = %d AND %s.messageid = %d', 
//          $tables["listuser"], $tables["listuser"], $tables["listmessage"], $tables["listuser"], $tables["listmessage"], $tables["listuser"], $userid, $tables["listmessage"], $messageid));
//    while ($row = Sql_Fetch_Row($listsreq)) {
//      array_push($lists, $row[0]);
//    }
//    if (!sizeof($lists))
//      return 0;
//    $liststosend = join(",", $lists);
//    
//    # request the rss items that match these lists and that have not been sent to this user
//    $itemstosend = array ();
//    $max = sprintf('%d', getConfig("rssmax"));
//    if (!$max) {
//      $max = 30;
//    }
//    $itemreq = Sql_Query("
//      SELECT {$tables["rssitem"]}.* FROM {$tables["rssitem"]}
//      WHERE {$tables["rssitem"]}.list IN ($liststosend) ORDER BY added desc, list, title LIMIT $max");
//    while ($item = Sql_Fetch_Array($itemreq)) {
//      Sql_Query("SELECT * FROM {$tables["rssitem_user"]} WHERE itemid = {$item["id"]} AND userid = $userid");
//      if (!Sql_Affected_Rows()) {
//        array_push($itemstosend, $item["id"]);
//      }
//    }
//    #  print "<br/>Items to send for user $userid: ".sizeof($itemstosend);
//    # if it is less than the threshold return nothing
//    $threshold = getConfig("rssthreshold");
//    if (sizeof($itemstosend) >= $threshold)
//      return $itemstosend;
//    else
//      return array ();
//  }
//  return array ();
//}
?>
