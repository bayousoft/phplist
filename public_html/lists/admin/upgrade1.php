<?include "pagetop.php"?>
	<title>Upgrade from 1.0 to 1.1.x</title>
<? 
require_once "accesscheck.php";

function createTable ($table) {
  global $DBstruct,$table_prefix;
  $query = "CREATE TABLE $table_prefix$table (\n";
  while (list($column, $val) = each($DBstruct[$table])) {
    $query .= "$column " . $DBstruct[$table][$column][0] . ",";
  }
  # get rid of the last ,
  $query = substr($query,0,-1);
  $query .= "\n)";
  # submit it to the database
  $res = Sql_Verbose_Query($query);
}

require "config.php";
include "header.inc";
include "structure.php";

if (TEST)
  Fatal_Error("You didn't edit config.php did you?");
if (STRUCTUREVERSION != VERSION)
  Fatal_Error("Structure is not the right version");
if (Sql_Table_Exists("attribute"))
  Fatal_Error("I have a hunch that this system has already been upgraded");

Sql_Verbose_Query("alter table $tables[user] add column confirmed tinyint default 0");
Sql_Verbose_Query("alter table $tables[list] add column listorder integer");
Sql_Verbose_Query("alter table $tables[list] add column prefix varchar(10)");
Sql_Verbose_Query("alter table $tables[message] add column footer text");
Sql_Verbose_Query("alter table $tables[message] add column userselection text");
createTable("attribute");
createTable("user_attribute");
createTable("sendprocess");
# usermessage has changed more dramatically
Sql_Verbose_Query("alter table $tables[usermessage] change column messageid messageid integer not null");
Sql_Verbose_Query("alter table $tables[usermessage] change column userid userid integer not null");
Sql_Verbose_Query("alter table $tables[usermessage] change column id id integer not null");
Sql_Verbose_Query("alter table $tables[usermessage] drop primary key");
# ignoring duplicates should be fine
Sql_Verbose_Query("alter ignore table $tables[usermessage] add primary key (userid,messageid)");

# now we need to copy the user attributes
Sql_Verbose_Query('insert into '.$tables[attribute].' (name,type,tablename,required) values("UK Region","select","ukregion",1)');
$ukid = Sql_Insert_id();
Sql_Verbose_Query('insert into '.$tables[attribute].' (name,type,tablename,required) values("World Region","select","worldregio",1)');
$worldid = Sql_Insert_id();
Sql_Verbose_Query('insert into '.$tables[attribute].' (name,type,tablename) values("Occasionally we are approached by other organisations<br>that would like to send you information about their work.<br>Please check this box if you would like to be included in these mailings","checkbox","checkthisb")');
$chkbxid = Sql_Insert_id();

Sql_Verbose_Query("alter table $tables[ukregion] rename $table_prefix"."listattr_ukregion");
Sql_Verbose_Query("alter table $tables[worldregion] rename  $table_prefix"."listattr_worldregio");
Sql_Verbose_Query("create table  $table_prefix"."listattr_checkthisb (id integer not null primary key auto_increment,name varchar(255))");
Sql_Verbose_Query('insert into  $table_prefix"."listattr_checkthisb (name) values("Checked")');
$checked = Sql_Insert_id();
Sql_Verbose_Query('insert into  $table_prefix"."listattr_checkthisb (name) values("Unchecked")');
$unchecked = Sql_Insert_id();

$req = Sql_Verbose_Query("select id,ukregion,worldregion,externmail from $tables[user]");
while ($user = Sql_Fetch_Array($req)) {
  Sql_Verbose_Query(sprintf('insert into '.$tables[user_attribute].' (attributeid,userid,value) values(%d,%d,%d)',$ukid,$user["id"],$user["ukregion"]));
  Sql_Verbose_Query(sprintf('insert into '.$tables[user_attribute].' (attributeid,userid,value) values(%d,%d,%d)',$worldid,$user["id"],$user["worldregion"]));
  Sql_Verbose_Query(sprintf('insert into '.$tables[user_attribute].' (attributeid,userid,value) values(%d,%d,%d)',$chkbxid,$user["id"],$user["externmail"] ? $checked : $unchecked));
}

# now clean up a bit
Sql_Verbose_Query("alter table $tables[user] drop column ukregion");
Sql_Verbose_Query("alter table $tables[user]  drop column worldregion");
Sql_Verbose_Query("alter table $tables[user] drop column externmail");
# hmm, we're not keeping the message data, oops
Sql_Verbose_Query("drop table messageukregion");
Sql_Verbose_Query("drop table messageworldregion");
include "footer.inc";
?>

