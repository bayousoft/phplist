<?php

require_once dirname(__FILE__).'/../accesscheck.php';

class admin_auth {

  function validateLogin($login,$password) {
    $query
    = ' select password, disabled, id'
    . ' from %s'
    . ' where loginname = ?';
    $query = sprintf($query, $GLOBALS['tables']['admin']);
    $req = Sql_Query_Params($query, array($login));
    $admindata = Sql_Fetch_Array($req);
    if ($admindata["disabled"]) {
      return array(0,"your account has been disabled");
    } elseif ($admindata[0] && $admindata[0] == $password && strlen($admindata[0]) > 3) {
      return array($admindata["id"],"OK");
    } else {
      return array(0,"invalid password");
    }
    return array(0,"Login failed");
  }

  function getPassword($email) {
    $email = preg_replace("/[;,\"\']/","",$email);
    $query = sprintf('select email, password, loginname from %s where email = ?', $GLOBALS['tables']['admin']);
    $req = Sql_Query_Params($query, array($email));
    if (Sql_Num_Rows($req)) {
      $row = Sql_Fetch_Row($req);
      return $row[1];
    }
  }

  function validateAccount($id) {
    $query
    = ' select id, disabled'
    . ' from %s'
    . ' where id = ?';
    $query = sprintf($query, $GLOBALS['tables']['admin']);
    $req = Sql_Query_Params($query, array($id));
    $noaccess_req = Sql_Fetch_Row($req);
    if (!$noaccess_req[0]) {
      return array(0,"No such account");
    } elseif ($noaccess_req[1]) {
      return array(0,"your account has been disabled");
    }
    return array(1,"OK");
  }

  function adminName($id) {
    $req = Sql_Fetch_Row_Query(sprintf('select loginname from %s where id = %d',$GLOBALS["tables"]["admin"],$id));
    return $req[0] ? $req[0] : "Nobody";
  }
  
  function adminEmail($id) {
    $req = Sql_Fetch_Row_Query(sprintf('select email from %s where id = %d',$GLOBALS["tables"]["admin"],$id));
    return $req[0] ? $req[0] : "";
  }    

  function isSuperUser($id) {
    $req = Sql_Fetch_Row_Query(sprintf('select superuser from %s where id = %d',$GLOBALS["tables"]["admin"],$id));
    return $req[0];
  }

  function listAdmins() {
    $result = array();
    $req = Sql_Query("select id,loginname from {$GLOBALS["tables"]["admin"]} order by loginname");
    while ($row = Sql_Fetch_Array($req)) {
      $result[$row["id"]] = $row["loginname"];
    }
    return $result;
  }

}

?>
