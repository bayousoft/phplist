<?

if (!function_exists("checkAccess")) {
  print "Invalid Request";
  exit;
}

function accessLevel($page) {
  global $logindetails,$tables,$access_levels;
  if (!$GLOBALS["require_login"] || isSuperUser())
    return "all";
  # check whether it is a page to protect
  Sql_Query("select id from {$tables["task"]} where page = \"$page\"");
  if (!Sql_Affected_Rows())
    return "all";
  $req = Sql_Query(sprintf('select level from %s,%s where adminid = %d and page = "%s" and %s.taskid = %s.id',
    $tables["task"],$tables["admin_task"],$logindetails["id"],$page,$tables["admin_task"],$tables["task"]));
  $row = Sql_Fetch_Row($req);
  return $access_levels[$row[0]];
}

function requireAccessLevel($page,$level) {
  $adminlevel = accessLevel($page);
  return $adminlevel == $level;
}

function isSuperUser() {
  global $logindetails,$tables;
  if (isset($_SESSION["logindetails"]["superuser"]))
    return $_SESSION["logindetails"]["superuser"];
  if ($GLOBALS["require_login"]) {
    $req = Sql_Fetch_Row_Query(sprintf('select superuser from %s where id = %d',$tables["admin"],$_SESSION["logindetails"]["id"]));
    $_SESSION["logindetails"]["superuser"] = $req[0];
    return $req[0];
  }
}


?>
