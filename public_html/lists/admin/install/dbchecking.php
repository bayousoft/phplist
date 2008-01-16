<?php

require("commonlib/lib/interfacelib.php");
require("structure.php");

$usertable_prefix = $GLOBALS["requiredVars"]["usertable_prefix"]["values"];
$table_prefix = $GLOBALS["requiredVars"]["table_prefix"]["values"];
$GLOBALS["img_tick"] = '<img src="images/tick.gif" alt="Yes">';
$GLOBALS["img_cross"] = '<img src="images/cross.gif" alt="No">';

#print $usertable_prefix.$table_prefix;
$tables = array(
  "user" => $usertable_prefix . "user",
  "user_history" => $usertable_prefix . "user_history",
  "list" => $table_prefix . "list",
  "listuser" => $table_prefix . "listuser",
  "user_blacklist" => $table_prefix . "user_blacklist",
  "user_blacklist_data" => $table_prefix . "user_blacklist_data",
  "message" => $table_prefix . "message",
  "messagedata" => $table_prefix. "messagedata",
  "listmessage" => $table_prefix . "listmessage",
  "usermessage" => $table_prefix . "usermessage",
  "attribute" => $usertable_prefix . "attribute",
  "user_attribute" => $usertable_prefix . "user_attribute",
  "sendprocess" => $table_prefix . "sendprocess",
  "template" => $table_prefix . "template",
  "templateimage" => $table_prefix . "templateimage",
  "bounce" => $table_prefix ."bounce",
  "user_message_bounce" => $table_prefix . "user_message_bounce",
  "user_message_forward" => $table_prefix . 'user_message_forward',
  "config" => $table_prefix . "config",
  "admin" => $table_prefix . "admin",
  "adminattribute" => $table_prefix . "adminattribute",
  "admin_attribute" => $table_prefix . "admin_attribute",
  "admin_task" => $table_prefix . "admin_task",
  "task" => $table_prefix . "task",
  "subscribepage" => $table_prefix."subscribepage",
  "subscribepage_data" => $table_prefix . "subscribepage_data",
  "eventlog" => $table_prefix . "eventlog",
  "attachment" => $table_prefix."attachment",
  "message_attachment" => $table_prefix . "message_attachment",
#  "rssitem" => $table_prefix . "rssitem",
#  "rssitem_data" => $table_prefix . "rssitem_data",
#  "user_rss" => $table_prefix . "user_rss",
#  "rssitem_user" => $table_prefix . "rssitem_user",
#  "listrss" => $table_prefix . "listrss",
  "urlcache" => $table_prefix . "urlcache",
  'linktrack' => $table_prefix.'linktrack',
  'linktrack_forward' => $table_prefix.'linktrack_forward',
  'linktrack_userclick' => $table_prefix.'linktrack_userclick',
  'linktrack_ml' => $table_prefix.'linktrack_ml',
  'linktrack_uml_click' => $table_prefix.'linktrack_uml_click',
  'userstats' => $table_prefix .'userstats',
  'bounceregex' => $table_prefix.'bounceregex',
  'bounceregex_bounce' => $table_prefix.'bounceregex_bounce',
);

#print_r($DBstruct); exit;
/*while (list($table, $tablename) = each($tables)) {
 print "Table = $table / Tablename = $tablename\n<br/>";
}*/
$ls = new WebblerListing("Database Structure");

print $GLOBALS["I18N"]->get($GLOBALS["strDoingDbCheck"]);

while (list($table, $tablename) = each($tables)) {
  $ls->addElement($table);
  if ($table != $tablename) {
    $ls->addColumn($table,"real name",$tablename);
  }
	$query
	= " select column_name, data_type"
  . " from information_schema.columns"
	. " where table_schema = ?"
  . "   and table_name = ?";
  $req = Sql_Query_Params($query, array('phplist',$tablename));
  $columns = array();
  if (!Sql_Affected_Rows()) {
    $ls->addColumn($table,"exist",$GLOBALS["img_cross"]);
  }
  while ($row = Sql_Fetch_Array($req)) {
    $columns[strtolower($row["column_name"])] = $row["data_type"];
  }
  $tls = new WebblerListing($table);
  $struct = $DBstruct[$table];
  $haserror = 0;
  foreach ($struct as $column => $colstruct) {
    if (!ereg("index_",$column) && !ereg("^unique_",$column) && $column != "primary key") {
      $tls->addElement($column);
      $exist = isset($columns[strtolower($column)]);
      if ($exist) {
        $tls->addColumn($column,"exist",$GLOBALS["img_tick"]);
      } else {
        $haserror = 1;
        $tls->addColumn($column,"exist",$GLOBALS["img_cross"]);
      }
    }
  }
  if (!$haserror) {
    $tls->collapse();
    $ls->addColumn($table,"ok",$GLOBALS["img_tick"]);
  } else {
    $ls->addColumn($table,"ok",$GLOBALS["img_cross"]);
  }
  $ls->addColumn($table,"check",$tls->display());
}
print $ls->display();

