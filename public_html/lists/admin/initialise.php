<?
require_once "accesscheck.php";

include $GLOBALS["coderoot"] . "structure.php";
ob_end_flush();

print "<h3>Creating tables</h3><br />\n";
$success = 1;
while (list($table, $val) = each($DBstruct)) {
  if (isset($force)) {
  	if ($table == "attribute") {
      $req = Sql_Query("select tablename from {$tables["attribute"]}");
      while ($row = Sql_Fetch_Row($req))
        Sql_Query("drop table if exists $table_prefix"."listattr_$row[0]",1);
   	}
    Sql_query("drop table if exists $tables[$table]");
	}
  $query = "CREATE TABLE $tables[$table] (\n";
  while (list($column, $val) = each($DBstruct[$table])) {
    $query .= "$column " . $DBstruct[$table][$column][0] . ",";
  }
  # get rid of the last ,
  $query = substr($query,0,-1);
  $query .= "\n)";

  # submit it to the database
  echo "Initialising table <b>$table</b>";
  if (!isset($force) && Sql_Table_Exists($tables[$table])) {
    Error( 'Table already exists<br />');
	  echo ".. failed<br />\n";
    $success = 0;
  } else {
    $res = Sql_Query($query,0);
    $error = Sql_Has_Error($database_connection);
    $success = $force || ($success && !$error);
		if (!$error || $force) {
      if ($table == "admin") {
        # create a default admin
        Sql_Query(sprintf('insert into %s values(0,"%s","%s","%s",now(),now(),"%s","%s",now(),%d,0)',
          $tables["admin"],"admin","admin","",$adminname,"phplist",1));
      } elseif ($table == "task") {
        while (list($type,$pages) = each ($system_pages)) {
          foreach ($pages as $page => $access_level)
            Sql_Query(sprintf('replace into %s (page,type) values("%s","%s")',
              $tables["task"],$page,$type));
        }
      }

      echo ".. ok<br />\n";
    }
    else
		  echo ".. failed<br />\n";
  }
}
#

if ($success) {
  # mark the database to be our current version
  Sql_Query(sprintf('replace into %s (item,value,editable) values("version","%s",0)',
    $tables["config"],VERSION));
  # add a testlist
	$info = 'List for testing. If you don\'t make this list active and add yourself as a member, you can use this list to test a message. If the message comes out ok, you can resend it to other lists.';
  $result = Sql_query("insert into {$tables["list"]} (name,description,entered,active,owner) values(\"test\",\"$info\",now(),0,1)");
  $body = '
  	Version: '.VERSION."\r\n".
   ' Url: '.getConfig("website").$pageroot."\r\n";
  printf('<p>Success: <a href="mailto:phplist@tincan.co.uk?subject=Successful installation of PHPlist&body=%s">Tell us about it</a>. </p>', $body);
  printf('<p>
  	Please make sure to read the file README.security that can be found in the zip file.</p>');
  printf('<p>
  	Please make sure to
    <a href="mailto:phplist-announce-subscribe@tincan.co.uk?subject=Subscribe"> subscribe to the announcements list</a> to make sure you are updated when new versions come out.
  	Sometimes security bugs are found which make it important to upgrade. Traffic on the list is very low. </p>');
  print "<p>Continue with ".PageLink2("setup","PHPlist Setup")."</p>";
} else {
 print ('<ul><li>Maybe you want to '.PageLink2("upgrade","Upgrade").' instead?
    <li>'.PageLink2("initialise","Force Initialisation","force=yes").' (will erase all data!)'."</ul>\n");
}

?>
