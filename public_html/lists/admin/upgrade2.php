<? include "pagetop.php"?>
	<title>Upgrade from 1.0 to 1.1.x</title>

<? 
require_once "accesscheck.php";

function createTable ($table) {
  global $DBstruct;
  $query = "CREATE TABLE $table (\n";
  while (list($column, $val) = each($DBstruct[$table])) {
    $query .= "$column " . $DBstruct[$table][$column][0] . ",";
  }
  # get rid of the last ,
  $query = substr($query,0,-1);
  $query .= "\n)";
  # submit it to the database
  $res = Sql_Verbose_Query($query);
}

function copyTable($table) {
  copyTables($table,$table);
}

function copyTables($oldtable,$newtable) {
  global $old_database_name,$database_name,$olddb,$newdb;
  print "Copying $oldtable to $newtable<br>\n";
  global $DBstruct;
  $res = Sql_Sql_query("select * from $oldtable",$old_database_name,$olddb);
  while ($row = Sql_fetch_array($res)) {
    flush();
    $cols = "";
    $vals = "";
    if (isset($DBstruct[$newtable])) {
      reset($DBstruct[$newtable]);
      while (list($key,$val) = each($DBstruct[$newtable])) {
        if ($key != "primary key") {
          $cols .= $key . ',';
          $vals .= '"'.addslashes($row[$key]).'",';
        }
      }
      $cols = substr($cols,0,-1);
      $vals = substr($vals,0,-1);
    } else {
      $cols = "id,name";
      $vals = $row["id"].',"'.$row["name"].'"';
    }
    print "Cols: $cols<br>";
    print "Vals: $vals<br>";
    print "Adding ".$row["id"]." to $newtable<br>";
    flush();
    $insert = Sql_Sql_query("replace into $newtable ($cols) values($vals)",$database_name,$newdb);
  }
}

require "config.php";
include "header.inc";
include "structure.php";

if (TEST)
  Fatal_Error("You didn't edit config.php did you?");
if (STRUCTUREVERSION != VERSION)
  Fatal_Error("Structure is not the right version");

if ($old_database_host == $database_host && $old_database_user == $database_user && $old_database_name == $database_name)
  Fatal_Error("Databases are the same, so you cannot use this option");
$olddb = Sql_Connect($old_database_host,$old_database_user,$old_database_password,$old_database_name);
if (!$olddb)
  Fatal_Error("Cannot connect to old database");
$newdb = Sql_Connect($database_host,$database_user,$database_password,$database_name);
if (!$newdb)
  Fatal_Error("Cannot connect to new database");
if (Sql_Table_Exists("attribute"))
  Fatal_Error("I have a hunch that this system has already been upgraded");

# create the tables in the new database
print "<h3>Creating tables</h3><br>\n";
while (list($table, $val) = each($DBstruct)) {
  if (isset($force))
    db_db_query("drop table $table",$database_name,$newdb);
  echo "Initialising table <b>$table</b> ";
  createTable($table);
  echo "ok ..<br>\n";
}

# copy lists
copyTable("list");
copyTable("user");
copyTable("usermessage");
copyTable("message");
copyTable("listuser");
copyTable("listmessage");

# now we need to copy the user attributes
Sql_Verbose_Query('insert into attribute (name,type,tablename,required) values("UK Region","select","ukregion",1)');
$ukid = Sql_Insert_id();
Sql_Verbose_Query('insert into attribute (name,type,tablename,required) values("World Region","select","worldregio",1)');
$worldid = Sql_Insert_id();
Sql_Verbose_Query('insert into attribute (name,type,tablename) values("Occasionally we are approached by other organisations<br>that would like to send you information about their work.<br>Please check this box if you would like to be included in these mailings","checkbox","checkthisb")');
$chkbxid = Sql_Insert_id();

Sql_Sql_Query("create table listattr_ukregion (id integer not null primary key auto_increment,name varchar(255))",$database_name,$newdb);
Sql_Sql_Query("create table listattr_worldregio (id integer not null primary key auto_increment,name varchar(255))",$database_name,$newdb);
Sql_Sql_Query("create table listattr_checkthisb (id integer not null primary key auto_increment,name varchar(255))",$database_name,$newdb);
copyTables("ukregion","listattr_ukregion");
copyTables("worldregion","listattr_worldregio");
Sql_Sql_Query('insert into listattr_checkthisb (name) values("Checked")',$database_name,$newdb);
$checked = Sql_Insert_id();
Sql_Sql_Query('insert into listattr_checkthisb (name) values("Unchecked")',$database_name,$newdb);
$unchecked = Sql_Insert_id();

$req = Sql_Sql_Query("select id,ukregion,worldregion,externmail from user",$old_database_name,$olddb);
while ($user = Sql_Fetch_Array($req)) {
  Sql_Sql_Query(sprintf('insert into user_attribute (attributeid,userid,value) values(%d,%d,%d)',$ukid,$user["id"],$user["ukregion"]),$database_name,$newdb);
  Sql_Sql_Query(sprintf('insert into user_attribute (attributeid,userid,value) values(%d,%d,%d)',$worldid,$user["id"],$user["worldregion"]),$database_name,$newdb);
  Sql_Sql_Query(sprintf('insert into user_attribute (attributeid,userid,value) values(%d,%d,%d)',$chkbxid,$user["id"],$user["externmail"] ? $checked : $unchecked),$database_name,$newdb);
}

# now clean up a bit
# hmm, we're not keeping the message data, oops
include "footer.inc";
?>

