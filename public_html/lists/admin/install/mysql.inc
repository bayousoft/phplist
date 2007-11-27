<?php

# sql functions, currently only set up to work with MySql
# replace functions in this file to make it work with other Databases

if (!function_exists("mysql_connect")) {
  print "Fatal Error: Mysql is not supported in your PHP, recompile and try again.";
  exit;
}

function Sql_Connect($host,$user,$password,$database) {
  if ($host && $user)
    $db = mysql_connect($host , $user ,$password );
#  $errno = mysql_errno();
  if (!$db) {
    return FALSE;
  }
  $res = mysql_select_db($database,$db);
#    $errno = mysql_errno();
#  }
#  if ($errno) {
#    print "<p>$dbm</p><p>Cannot connect to Database, please check your configuration</p>";
#    return $errno;
#  }
  return $res;
}

function Sql_Error ($dbconnection,$errno = 0) {
  $msg = mysql_error($dbconnection);
  if (empty($GLOBALS['commandline'])) {
    return 'Database error '. $errno.' while doing query '.$GLOBALS['lastquery']. ' ' .$msg.'';
  }
}

function Sql_Query($query,$ignore = 0) {
  if (isset($GLOBALS['lastquery'])) {
    unset($GLOBALS['lastquery']);
  }
  if (isset($GLOBALS["developer_email"])) {
  #  if (preg_match("/dev$/",VERSION))
  #  print "<b>$query</b><br>\n";
  #  if ($GLOBALS["commandline"]) {
  #    ob_end_clean();
  #    print "Sql: $query\n";
  #    ob_start();
  #  }
    # time queries to see how slow they are, so they can
    # be optimized
    $now =  gettimeofday();
    $start = $now["sec"] * 1000000 + $now["usec"];
    $GLOBALS['lastquery'] = $query;
    # keep track of queries to see which ones to optimize
    if (function_exists('stripos')) {
      if (!stripos($query,'WebblerSessions') && !stripos($query,'cache')) {
        $store = $query;
        $store = preg_replace('/\d+/','X',$store);
        $store = trim($store);
        @mysql_query(sprintf('update querycount set count = count + 1 where query = "%s" and frontend = %d',$store,$frontend));
        if (mysql_affected_rows() != 2) {
          @mysql_query(sprintf('insert into querycount set count = count + 1 , query = "%s",phplist = 1',$store));
        }
      }
    }  
  }
  $GLOBALS["pagestats"]["number_of_queries"]++;
  $result = mysql_query($query,$GLOBALS["database_connection"]);
/*  if (!$ignore) {
    if (Sql_Check_Error($GLOBALS["database_connection"]))
      dbg("Sql error in $query");
  }*/
  if (isset($GLOBALS["developer_email"])) {
    # log time queries take
    $now = gettimeofday();
    $end = $now["sec"] * 1000000 + $now["usec"];
    $elapsed = $end - $start;
    if ($elapsed > 300000) {
      $query = substr($query,0,200);
      sqllog(' ['.$elapsed.'] '.$query,"/tmp/phplist-sqltimer.log");
    } else {
#      sqllog(' ['.$elapsed.'] '.$query,"/tmp/phplist-sqltimer.log");
    }
  }

  return $result;
}

function Sql_Fetch_Array($dbresult) {
  return mysql_fetch_array($dbresult);
}

function Sql_Fetch_Assoc($dbresult) {
  return mysql_fetch_assoc($dbresult);
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

function Sql_Fetch_Assoc_Query($query) {
  $req = Sql_Query($query);
  return Sql_Fetch_Assoc($req);
}

function Sql_Insert_id() {
  return mysql_insert_id($GLOBALS["database_connection"]);
}

function Sql_Result($result,$index,$column) {
  return mysql_result($result,$index,$column);
}

function Sql_Free_Result($dbresult) {
  mysql_free_result($dbresult);
}

function Sql_Affected_Rows() {
  return mysql_affected_rows($GLOBALS["database_connection"]);
}

function Sql_Num_Rows($result = "") {
  return mysql_num_rows($result);
}

function sql_errorno() {
  return mysql_errno();
}

?>