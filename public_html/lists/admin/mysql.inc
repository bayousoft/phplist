<?

# sql functions, currently only set up to work with MySql
# replace functions in this file to make it work with other Databases

if (!function_exists("mysql_connect")) {
	print "Fatal Error: Mysql is not supported in your PHP, recompile and try again.";
  exit;
}

function Sql_Connect($host,$user,$password,$database) {
	if ($host && $user)
		$db = mysql_connect($host , $user ,$password );
  $errno = mysql_errno();
  if (!$errno) {
	  $res = mysql_select_db($database,$db);
	  $errno = mysql_errno();
  }
  if ($errno) {
    switch ($errno) {
      case 1049: # unknown database
        Fatal_Error("unknown database, cannot continue");
        exit;
      case 1045: # access denied
        Fatal_Error("Cannot connect to database, access denied. Please contact the administrator");
        exit;
      case 2002:
        Fatal_Error("Cannot connect to database, Sql server is not running. Please contact the administrator");
        exit;
      case 1040: # too many connections
        Fatal_Error("Sorry, the server is currently too busy, please try again later.");
        exit;
      case 0:
        break;
      default:
        SQL_Error($errno, "Server error:". mysql_error());
    }
    print "Cannot connect to Database, please check your configuration";
    exit;
  }
  if (!$db) {
    print "Cannot connect to Database, please check your configuration";
    exit;
  }
  return $db;
}

function Sql_has_error ($dbconnection) {
  return mysql_errno($dbconnection);
}

function Sql_Error ($dbconnection,$errno = 0) {
  $msg = mysql_error($dbconnection);
  return '<div id="dberror" style="position: relative;
    	background-color: #aa0000;
      border: 2px solid #000000;
      color: #ffffff;
      ">Database error '. $errno.' '.$msg.'</div>';
   if (function_exists("logevent")) {
     logevent("Database error: $msg");
   }
#  return "<table border=1><tr><td class=\"error\">Database Error</td></tr><tr><td><!--$errno: -->$msg</td></tr></table>";
}

function Sql_Check_error($dbconnection,$errno = 0) {
	if (!$errno)
	  $errno = Sql_has_error($dbconnection);
  if ($errno) {
    switch ($errno) {
    	case 1049: # unknown database
      	Fatal_Error("unknown database, cannot continue");
        exit;
      case 1045: # access denied
        Fatal_Error("Cannot connect to database, access denied. Please contact the administrator");
        exit;
      case 2002:
        Fatal_Error("Cannot connect to database, Sql server is not running. Please contact the administrator");
        exit;
      case 1040: # too many connections
        Fatal_Error("Sorry, the server is currently too busy, please try again later.");
        exit;
      case 0:
        break;
      default:
		    print Sql_error($dbconnection,$errno);
    }
    return 1;
  }
}

function Sql_Query($query,$ignore = 0) {
#  if (preg_match("/dev$/",VERSION))
#    print "<b>$query</b><br>\n";
  $result = mysql_query($query,$GLOBALS["database_connection"]);
  if (!$ignore) {
    if (Sql_Check_Error($GLOBALS["database_connection"]))
    	dbg("Sql error in $query");
  }
  return $result;
}

function db_db_Query($query,$database,$db_connection) {
  $res = mysql_db_query($database,$query,$db_connection);
  if (db_has_Error($db_connection))
    Sql_Error($db_connection);
  return $res;
}

function Sql_Verbose_Query($query) {
  if (preg_match("/dev$/",VERSION))
    print "<b>$query</b><br>\n";
  flush();
  return Sql_Query($query);
}

function Sql_Fetch_Array($dbresult) {
  return mysql_fetch_array($dbresult);
}

function Sql_Fetch_Row($dbresult) {
  return mysql_fetch_row($dbresult);
}

function Sql_Fetch_Row_Query($query) {
  $req = Sql_Query($query);
  return Sql_Fetch_Row($req);
}

function Sql_Fetch_Array_Query($query) {
  $req = Sql_Query($query);
  return Sql_Fetch_Array($req);
}

function Sql_Affected_Rows() {
  return mysql_affected_rows();
}

function Sql_Insert_id() {
  return mysql_insert_id();
}

function Sql_Result($result,$index,$column) {
  return mysql_result($result,$index,$column);
}

function Sql_Free_Result($dbresult) {
  mysql_free_result($dbresult);
};

function Sql_Table_exists($table) {
  $req = Sql_Query("show tables");
  while ($row = Sql_Fetch_Row($req)) {
    if ($row[0] == $table)
      return 1;
  }
  return 0;
}

function Sql_Table_Column_Exists($table,$column) {
  if (Sql_Table_exists($table)) {
    $req = Sql_Query("show columns from $table");
    while ($row = Sql_Fetch_Row($req)) {
      if ($row[0] == $column)
        return 1;
    }
  }
}

function Sql_Check_For_Table($table) {
  return Sql_Table_exists($table);
}

function Sql_create_Table ($table,$structure) {
  $query = "CREATE TABLE $table (\n";
  while (list($column, $val) = each($structure)) {
    $query .= "$column " . $structure[$column][0] . ",";
  }
  # get rid of the last ,
  $query = substr($query,0,-1);
  $query .= "\n)";
  # submit it to the database
  $res = Sql_Verbose_Query($query);
}

?>
