<?
require_once "accesscheck.php";

ob_end_flush();
include "structure.php";
print '<script language="Javascript" src="js/progressbar.js" type="text/javascript"></script>';
print '<script language="Javascript" type="text/javascript"> document.write(progressmeter); start();</script>';
ignore_user_abort();
set_time_limit(500);

switch (VERSION) {
  case "1.3.9":$changed_tables = array("user","message","usermessage","template","templateimage");break;
  case "1.3.8":$changed_tables = array("user","message");break;
  case "dev":$changed_tables = array("user","message","usermessage","template","templateimage");break;
  default : $changed_tables = array();
}

while (list($table,$value) = each ($DBstruct)) {
  set_time_limit(500);
  if ($table_prefix)
    Sql_Query("alter table $table rename $tables[$table]",0);
  if (in_array($table,$changed_tables)) {
    print "<br>Upgrading $table<br />";
    upgradeTable($tables[$table],$DBstruct[$table]);
  }
}
$req = Sql_Query("select tablename from $tables[attribute]");
while ($row = Sql_Fetch_Row($req)) {
  set_time_limit(500);
  if (Sql_Table_Exists("listattr_".$row[0]) && $table_prefix)
    Sql_Verbose_Query("alter table listattr_$row[0] rename $table_prefix"."listattr_".$row[0]);
  if (Sql_Check_For_Table($table_prefix."listattr_".$row[0]))
    Sql_Query("alter table $table_prefix"."listattr_".$row[0]." add column listorder integer default 0",0);
}

print "<p>All done";
print '<script language="Javascript" type="text/javascript"> finish(); </script>';

?>
