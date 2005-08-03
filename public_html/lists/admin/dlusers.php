<?php
require_once dirname(__FILE__).'/accesscheck.php';

include dirname(__FILE__) . "/structure.php";

if (!$list)
  $filename = $GLOBALS['I18N']->get('PHPList Users').' '.date("Y-M-d").'.csv';
else {
  $res = Sql_Query("select name from {$tables['list']} where id = $list");
  $row = Sql_Fetch_Row($res);
  $filename = $GLOBALS['I18N']->get('PHPList Users').' '.$GLOBALS['I18N']->get('on').' '.$row[0].' '.date("Y-M-d").'.csv';
}
ob_end_clean();
header("Content-type: ".$GLOBALS["export_mimetype"]);
header("Content-disposition:  attachment; filename=\"$filename\"");

$cols = array();
while (list ($key,$val) = each ($DBstruct["user"])) {
  if (!ereg("sys:",$val[1])) {
    print $val[1]."\t";
     array_push($cols,$key);
  } elseif (ereg("sysexp:(.*)",$val[1],$regs)) {
    print $regs[1]."\t";
     array_push($cols,$key);
  }
}
$res = Sql_Query("select id,name,tablename,type from {$tables['attribute']}");
$attributes = array();
while ($row = Sql_fetch_array($res)) {
  print trim($row["name"]) ."\t";

 array_push($attributes,array("id"=>$row["id"],"table"=>$row["tablename"],"type"
=>$row["type"]));
}
print $GLOBALS['I18N']->get('List Membership')."\n";

if ($list)
 $result = Sql_query("SELECT {$tables['user']}.* FROM
 {$tables['user']},{$tables['listuser']} where {$tables['user']}.id =
 {$tables['listuser']}.userid and {$tables['listuser']}.listid = $list");
else
  $result = Sql_query("SELECT * FROM {$tables['user']}");

while ($user = Sql_fetch_array($result)) {
  set_time_limit(500);
  reset($cols);
  while (list ($key,$val) = each ($cols))
    print strtr($user[$val],"\t",",")."\t";
  reset($attributes);
  while (list($key,$val) = each ($attributes)) {
    print strtr(UserAttributeValue($user["id"],$val["id"]),"\t",",")."\t";
  }
  $lists = Sql_query("SELECT listid,name FROM
    {$tables['listuser']},{$tables['list']} where userid = ".$user["id"]." and
    {$tables['listuser']}.listid = {$tables['list']}.id");
  if (!Sql_Affected_rows($lists))
    print $GLOBALS['I18N']->get('No Lists');
  while ($list = Sql_fetch_array($lists)) {
    print $list["name"]." ";
  }
  print "\n";
}
exit;
