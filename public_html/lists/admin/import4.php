<?php
require_once "accesscheck.php";

# import from a different PHPlist installation

if ($require_login && !isSuperUser()) {
  $access = accessLevel("import4");
  if ($access == "owner")
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];
  elseif ($access == "all")
    $subselect = "";
  elseif ($access == "none")
    $subselect = " where id = 0";
}

function connectLocal() {
	$database_connection = Sql_Connect(
  	$GLOBALS["database_host"],
    $GLOBALS["database_user"],
    $GLOBALS["database_password"],
    $GLOBALS["database_name"]);
 	return $database_connection;
}
function connectRemote() {
	return Sql_Connect($_POST["remote_host"],
  $_POST["remote_user"],
  $_POST["remote_password"],
  $_POST["remote_database"]);
}

$result = Sql_query("SELECT id,name FROM ".$tables["list"]." $subselect ORDER BY listorder");
while ($row = Sql_fetch_array($result)) {
	$available_lists[$row["id"]] = $row["name"];
  $some = 1;
}
if (!$some)
	echo 'No lists available, '.PageLink2("editlist","Add a list");
#foreach ($_POST as $key => $val) {
#	print "$key => $val<br/>";
#}

if (!$_POST["remote_host"] ||
	!$_POST["remote_user"] || 
  !$_POST["remote_password"] || !$_POST["remote_database"]) {
	printf( '
  <p>Please enter details of the remote Server</p>
  <form method=post>
  <table>
  <tr><td>Server:</td><td><input type=text name="remote_host" value="%s" size=30></td></tr>
  <tr><td>User:</td><td><input type=text name="remote_user" value="%s" size=30></td></tr>
  <tr><td>Password:</td><td><input type=text name="remote_password" value="%s" size=30></td></tr>
  <tr><td>Database Name:</td><td><input type=text name="remote_database" value="%s" size=30></td></tr>
  <tr><td>Table prefix:</td><td><input type=text name="remote_prefix" value="%s" size=30></td></tr>
  <tr><td>Usertable prefix:</td><td><input type=text name="remote_userprefix" value="%s" size=30></td></tr>
  ',$_POST["remote_server"],$_POST["remote_user"],$_POST["remote_password"],
  $_POST["remote_database"],$_POST["remote_prefix"],$_POST["remote_userprefix"]);
  $c = 0;
  print '<tr><td colspan=2>';
  if (sizeof($available_lists) > 1)
    print 'Select the lists to add the emails to<br/>';
  print '<ul>';
  foreach ($available_lists as $index => $name) {
    printf('<li><input type=checkbox name="lists[%d]" value="%d" %s>%s</li>',
      $c,$index,is_array($_POST["lists"]) && in_array($index,array_values($_POST["lists"]))?"checked":"",$name);
    $c++;
  }
  printf('
  <li><input type=checkbox name="copyremotelists" value="yes" %s>Copy lists from remote server (lists are matched by name)</li>
  </ul></td></tr>
<tr><td>Mark new users as HTML:</td><td><input type="checkbox" name="markhtml" value="yes" %s></td></tr>
<tr><td colspan=2>If you check "Overwrite Existing", information about a user in the database will be replaced by the imported information. Users are matched by email.</td></tr>
<tr><td>Overwrite Existing:</td><td><input type="checkbox" name="overwrite" value="yes" %s></td></tr>
	<tr><td colspan=2><input type=submit value="Continue"></td></tr>
  </table></form>
  ',$_POST["copyremotelists"] == "yes"?"checked":"",$_POST["markhtml"] == "yes"?"checked":"",$_POST["overwrite"] == "yes"?"checked":""
  );
} else {
  set_time_limit(600);
  ob_end_flush();
	include_once("structure.php");
	print "Making connection with remote database<br/>";
  flush();
	$remote = connectRemote();
  if (!$remote) {
  	Fatal_Error("cannot connect to remote database");
    return;
  }
  $remote_tables = array(
    "user" => $_POST["remote_userprefix"] . "user",
    "list" => $_POST["remote_prefix"] . "list",
    "listuser" => $_POST["remote_prefix"] . "listuser",
    "attribute" => $_POST["remote_userprefix"] . "attribute",
    "user_attribute" => $_POST["remote_userprefix"] . "user_attribute",
    "config" => $_POST["remote_prefix"] . "config",
  );
  print "Getting data from ".$_POST["remote_database"]."@".$_POST["remote_host"]."<br/>";

  $version = Sql_Fetch_Row_Query("select value from {$remote_tables["config"]} where item = \"version\"");
  print "Remote version is $version[0]<br/>\n";
  $usercnt = Sql_Fetch_Row_Query("select count(*) from {$remote_tables["user"]}");
  print "Remote version has $usercnt[0] users<br/>";
  if (!$usercnt[0]) {
  	Fatal_Error("No users to copy, is the prefix correct?");
    return;
  }
  $totalusers = $usercnt[0];
  $listcnt = Sql_Fetch_Row_Query("select count(*) from {$remote_tables["list"]}");
  print "Remote version has $listcnt[0] lists<br/>";

  flush();
  print '<h1>Copying lists</h1>';
  # first copy the lists across
  $listmap = array();
  $remote_lists = array();
  $lists_req = Sql_Query("select * from {$remote_tables["list"]}");
  while ($row = Sql_Fetch_Array($lists_req)) {
  	array_push($remote_lists,$row);
  }

	connectLocal();
  foreach ($remote_lists as $list) {
    $localid_req = Sql_Fetch_Row_Query(sprintf('select id from %s where name = "%s"',
    	$tables["list"],$list["name"]));
    if ($localid_req[0]) {
    	$listmap[$list["id"]] = $localid_req[0];
     	print "List ".$list["name"] . " exists locally<br/>\n";
    } elseif ($_POST["copyremotelists"]) {
    	$query = "";
    	foreach ($DBstruct["list"] as $colname => $colspec) {
      	if ($colname != "id" && $colname != "index" && $colname != "unique" && $colname != "primary key") {
        	$query .= sprintf('%s = "%s",',$colname,addslashes($list[$colname]));
       	}
     	}
      $query = substr($query,0,-1);
     	print "List ".$list["name"] . " created locally<br/>\n";
      Sql_Query("insert into {$tables["list"]} set $query");
      $listmap[$list["id"]] = Sql_Insert_id();
    } else {
     	print "Remote List ".$list["name"] . " not created<br/>\n";
    }
  }

  connectRemote();
  print '<h1>Copying attributes</h1>';
  # now copy the attributes
  $attributemap = array();
  $remote_atts = array();
  $att_req = Sql_Query("select * from {$remote_tables["attribute"]}");
  while ($row = Sql_Fetch_Array($att_req)) {
  	array_push($remote_atts,$row);
  }

	connectLocal();
  foreach ($remote_atts as $att) {
    $localid_req = Sql_Fetch_Row_Query(sprintf('select id from %s where name = "%s"',
    	$tables["attribute"],stripslashes($att["name"])));
    if ($localid_req[0]) {
    	$attributemap[$att["id"]] = $localid_req[0];
     	print "Attribute ".$att["name"] . " exists locally<br/>\n";
    } else {
    	$query = "";
    	foreach ($DBstruct["attribute"] as $colname => $colspec) {
      	if ($colname != "id" && $colname != "index" && $colname != "unique" && $colname != "primary key") {
        	$query .= sprintf('%s = "%s",',$colname,addslashes($att[$colname]));
       	}
     	}
      $query = substr($query,0,-1);#
     	print "Attribute ".$att["name"] . " created locally<br/>\n";
      Sql_Query("insert into {$tables["attribute"]} set $query");
      $attributemap[$att["id"]] = Sql_Insert_id();
      if ($att["type"] == "select" || $att["type"] == "radio" || $att["type"] == "checkboxgroup") {
        $query = "create table if not exists $table_prefix"."listattr_".$att["tablename"]."
        (id integer not null primary key auto_increment,
        name varchar(255) unique,listorder integer default 0)";
        Sql_Query($query,0);
        connectRemote();
        $attvalue_req = Sql_Query("select id,name,listorder from ".$_POST["remote_prefix"]."listattr_".$att["tablename"]);
        $values = array();
        while ($value = Sql_Fetch_Array($attvalue_req)) {
        	array_push($values,$value);
        }
        connectLocal();
        foreach ($values as $value) {
          Sql_Query(sprintf('replace into %slistattr_%s (name,id,listorder)
          	values("%s",%d,"%s")',$table_prefix,$att["tablename"],addslashes($value["name"]),$value["id"],$value["listorder"]));
				}
    	}
    }
  }

  print '<h1>Copying users</h1>';
  # copy the users
  $usercnt = 0;
  $existcnt = 0;
  $newcnt = 0;
  while ($usercnt < $totalusers) {
	  set_time_limit(60);
  	connectRemote();
  	$req = Sql_Query("select * from {$remote_tables["user"]} limit $usercnt,1");
    $user = Sql_Fetch_Array($req);
    $usercnt++;
    $new = 0;
    if ($usercnt % 20 == 0) {
    	print "$usercnt / $totalusers<br/>";
      flush();
    }
    connectLocal();
    $query = "";
    $exists = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"',$tables["user"],$user["email"]));
    if ($exists[0]) {
    	$existcnt++;
  #  	print $user["email"] ." exists locally ..";
      if ($_POST["overwrite"]) {
	#			print " .. overwriting local data<br/>";
        $query = "replace into ".$tables["user"] . " set id = ".$exists[0].", ";
      } else {
	#			print " .. keeping local data<br/>";
    	}
      $userid = $exists[0];
    } else {
    	$newcnt++;
      $new = 1;
  #  	print $user["email"] ." is a new user<br/>";
      $query = "insert into ".$tables["user"]. " set ";
    }
    if ($query) {
    	foreach ($DBstruct["user"] as $colname => $colspec) {
      	if ($colname != "id" && $colname != "index" && $colname != "unique" && $colname != "primary key") {
        	$query .= sprintf('%s = "%s",',$colname,addslashes($user[$colname]));
       	}
     	}
      $query = substr($query,0,-1);
      #print $query . "<br/>";
      Sql_Query("$query");
			$userid = Sql_Insert_id();
		}
    if ($userid && $_POST["markhtml"]) {
    	Sql_Query("update {$tables["user"]} set htmlemail = 1 where id = $userid");
    }

    if ($new || (!$new && $_POST["overwrite"])) {
      # now check for attributes and list membership
      connectRemote();
      $useratt = array();
      $req = Sql_Query("select * from {$remote_tables["user_attribute"]},
        {$remote_tables["attribute"]} where {$remote_tables["user_attribute"]}.attributeid =
        {$remote_tables["attribute"]}.id and {$remote_tables["user_attribute"]}.userid = $user[0]");
      while ($att = Sql_Fetch_Array($req)) {
        $value = "";
        switch ($att["type"]) {
          case "select":
          case "radio":
            $valreq = Sql_Fetch_Row_Query(sprintf('select name from %slistattr_%s where id = %d',
              $_POST["remote_prefix"],$att["tablename"],$att["value"]));
            $value = $valreq[0];
            break;
          case "checkboxgroup":
            $valreq = Sql_Query(sprintf('select name from %slistattr_%s where id in (%s)',
              $_POST["remote_prefix"],$att["tablename"],$att["value"]));
            while ($vals = Sql_fetch_Row($valreq)) {
              $value .= $vals[0].',';
            }
            break;
        }
        $att["displayvalue"] = $value;
        array_push($useratt,$att);
      }
      $userlists = array();
      $userlists = array_merge($_POST["lists"],$userlists);
      if ($_POST["copyremotelists"]) {
        $req = Sql_Query("select * from {$remote_tables["listuser"]},
          {$remote_tables["list"]} where {$remote_tables["listuser"]}.listid =
          {$remote_tables["list"]}.id and {$remote_tables["listuser"]}.userid = $user[0]");
        while ($list = Sql_Fetch_Array($req)) {
        #	print $list["name"]."<br/>";
          array_push($userlists,$list);
        }
      }
      connectLocal();
      foreach ($useratt as $att) {
      	$localattid = $attributemap[$att["attributeid"]];
        if (!localattid) {
        	print "Error, no mapped attribute for ".$att["name"]."<br/>";
        } else {
          $tname = Sql_Fetch_Row_Query("select tablename from {$tables["attribute"]} where id = $localattid");
          switch ($att["type"]) {
          	case "select":
            case "radio":
            	$valueid = Sql_Fetch_Row_Query(sprintf('select id from %slistattr_%s where name = "%s"',
	              $table_prefix,$tname[0],$att["displayvalue"]));
              if (!$valueid[0]) {
              	Sql_Query(sprintf('insert into %slistattr_%s set name = "%s"',
	              $table_prefix,$tname[0],$att["displayvalue"]));
                $att["value"] = Sql_Insert_id();
              } else {
              	$att["value"] = $valueid[0];
              }
              break;
            case "checkboxgroup":
            	$vals = explode(",",$att["displayvalue"]);
              array_pop($vals);
              $att["value"] = "";
              foreach ($vals as $val) {
                $valueid = Sql_Fetch_Row_Query(sprintf('select id from %slistattr_%s where name = "%s"',
                  $table_prefix,$tname[0],$val));
                if (!$valueid[0]) {
                  Sql_Query(sprintf('insert into %slistattr_%s set name = "%s"',
                  $table_prefix,$tname[0],$val));
                  $att["value"] .= Sql_Insert_id().',';
                } else {
                  $att["value"] .= $valueid[0].",";
                }
              }
              $att["value"] = substr($att["value"],0,-1);
              break;
          }
          if ($att["value"]) {
            Sql_Query(sprintf('replace into %s set
              attributeid = %d, userid = %d, value = "%s"',
              $tables["user_attribute"],$localattid,$userid,addslashes($att["value"])));
          }
        }
      }
    }
    if (is_array($userlists))
    foreach ($userlists as $list) {
    	if ($listmap[$list["listid"]]) {
        Sql_Query(sprintf('replace into %s (listid,userid) values(%d,%d)',
          $tables["listuser"],$listmap[$list["listid"]],$userid));
     	} else {
      	print "Error, no local list defined for ".$list["name"]."<br/>";
      }
    }
  }
  print "$totalusers / $totalusers<br/>";
  flush();
  printf('Done %d new users and %d existing users<br/>',$newcnt,$existcnt);
}
?>



