<?
require_once "accesscheck.php";

include "structure.php";
ob_end_flush();
if (TEST)
  Fatal_Error("You didn't edit config.php did you?");
if (STRUCTUREVERSION != VERSION)
  Fatal_Error("Structure is not the right version");

if ($table_prefix) {
  # we're now using prefixes, rename all tables
  include "structure.php";
  while(list($table,$val) = each($DBstruct))
    Sql_Verbose_Query("alter table $table rename $tables[$table]");

  $req = Sql_Query("select tablename from $tables[attribute]");
  while ($row = Sql_Fetch_Row($req)) 
    Sql_Verbose_Query("alter table listattr_$row[0] rename $table_prefix"."listattr_".$row[0]);
}

Sql_Verbose_Query("alter table $tables[user_attribute] change column value value varchar(255)");
Sql_Verbose_Query("alter ignore table $tables[user] change column email email varchar(255) not null unique");
Sql_Verbose_Query("alter table $tables[attribute] change column type type varchar(30)");
Sql_Verbose_Query("update $tables[user] set confirmed = 1");
Sql_Verbose_Query("alter table $tables[user] add column htmlemail tinyint default 0");
Sql_Verbose_Query("alter table $tables[message] add column htmlformatted tinyint default 0");
Sql_Verbose_Query("alter table $tables[message] add column sendformat varchar(20)");

$c = 1;
if (0)
foreach (array("name","address1","address2","postcode","town") as $item) {
  Sql_Verbose_Query(sprintf('insert into %s (name,type,listorder,required) values("%s","textline",%d,0)',$tables[attribute],$item,$c));
  $c++;
  $attribute_id = Sql_Insert_id();
  print "<P>Now copying user data to new table<br>";
  $req = Sql_Query("select id,$item from $tables[user]");
  while ($user = Sql_Fetch_Row($req))
    Sql_Query(sprintf('insert into %s values(%d,%d,"%s")',$tables[user_attribute],$attribute_id,$user[0],addslashes($user[1])));
  Sql_Verbose_Query("alter table $tables[user] drop column $item");
} 

?>

