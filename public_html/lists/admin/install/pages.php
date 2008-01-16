<?php

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);
$type = "text";
switch ($actualPage) {

  case "install0":
    $editable = "";
    /*if ($_SESSION['dbCreatedSuccesfully'] == 0) {
      $yourValue = $_SESSION;*/
      print $GLOBALS["I18N"]->get(sprintf('<table width=500><tr><td><div class="explain">%s</div></td></tr></table>', $GLOBALS['strDbExplain']));
    // }
    // else {
    //   print $GLOBALS["I18N"]->get(sprintf('<p>%s</p>',$GLOBALS["strPhplistDbCreation"]));
    // }
    
    print $GLOBALS["I18N"]->get(sprintf('<style type="text/css">table tr td input { float:right; }</style><table width=500><tr><td>%s</td><td><input name="database_name" type="text" value="%s"></td></tr>
    <tr><td>%s</td><td><input name="database_host" type="text" value="%s"></td></tr>
    <tr><td>%s</td><td><input name="database_schema" type="text" value="%s"></td></tr>
    <tr><td>%s</td><td><input name="database_user" type="text" value="%s"></td></tr>
    <tr><td>%s</td><td><input name="database_password" type="text" value="%s"></td></tr>
    <tr><td></td><td></td></tr></table>'
, $GLOBALS["strDbname"], $_SESSION['database_name']
, $GLOBALS["strDbhost"], $_SESSION['database_host']
, $GLOBALS["strDbschema"], $_SESSION['database_schema']
, $GLOBALS["strDbuser"], $_SESSION['database_user']
, $GLOBALS["strDbpass"], $_SESSION['database_password']));
    
    print $GLOBALS["I18N"]->get(sprintf('<table width=500>
    <tr><td colspan=2><div class="explain">%s</div></td></tr>
    <tr><td>%s</td><td><input name="database_root_user" type="text" value="%s"></td></tr>
    <tr><td>%s</td><td><input name="database_root_pass" type="text" value="%s"></td></tr>
    </table>',$GLOBALS["strDbroot"],$GLOBALS["strDbrootuser"], $_SESSION['database_root_user'], $GLOBALS["strDbrootpass"], $_SESSION['database_root_pass']));
  break;
  case "install01":
    $editable = "";
    if (isset($_SESSION['database_root_user']) && isset($_SESSION['database_root_pass'])) {
      $root_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass'], $_SESSION['database_name']);
      if (!$root_connection) {
        $root_connection = Sql_Connect_Install($_SESSION['database_host'], $_SESSION['database_root_user'], $_SESSION['database_root_pass']);
        $GLOBALS["database_connection"] = $test_connection;
        if ($root_connection) {
        $create_db = Sql_Create_Db($_SESSION['database_name']);
    # if error let's still try to connect with user's settings
        }
      }
      $GLOBALS["database_connection"] = $root_connection; # useless
      Sql_Query(sprintf("GRANT ALL PRIVILEGES ON %s.* TO '%s'@'localhost' IDENTIFIED BY '%s'", $_SESSION['database_name'], $_SESSION['database_user'], $_SESSION['database_password']));
    #  if (!$rootPriv)  print '<script language="Javascript"> alert("No privileges given"); </script>'; # testing
      unset($_SESSION['database_root_user']);
      unset($_SESSION['database_root_pass']);
      sql_query("FLUSH PRIVILEGES"); # this will help on windows and mac machines
      Sql_Close($root_connection);
    }
    sleep(2);
    #flush();
    $test_connection = Sql_Connect($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password'], $_SESSION['database_name']);
    Sql_Set_Search_Path($_SESSION['database_schema']);
    
    if ($test_connection) {
    $GLOBALS["database_connection"] = $test_connection;
    include("install/dbchecking.php");
    }
    
    if (!$test_connection) { // let's connect without a db
      $test_connection2 = sql_connect_install($_SESSION['database_host'], $_SESSION['database_user'], $_SESSION['database_password']);
      if ($test_connection2) {
        $GLOBALS["database_connection"] = $test_connection2;
        $create_db = Sql_Create_Db($_SESSION['database_name']);
        if (!$create_db) {
          $errorno = sql_errorno();
          $procedure = 2;
        } else {
          $procedure = 1;
        }
      } else {
        $errorno = sql_errorno();
        $procedure = 3;
      }
    } else {
      $errorno = sql_errorno();
      $procedure = $errorno ? 2 : 1;
    }
    
    if (!$_SESSION['database_host'] || !$_SESSION['database_user'] || !$_SESSION['database_password'] || !$_SESSION['database_name'])
      $errorno = 666;
    
    /**
    procedure 1 = all ok, connection && db
    procedure 2 = connection ok && !db
    procedure 3 = no connection
    */
    if ($errorno) {
      switch($errorno) {
        case 666:
          $msg = $GLOBALS["strEmptyField"];
        break;
        case 2005:
        case 2009:
          $msg = $GLOBALS["strWrongHost"];
        break;
        case 1040: # too many connections
          $msg = $GLOBALS["strServerBusy"];
        break;
        case 1044: # access denied
        case 1045: # access denied
          $msg = $GLOBALS["strAccessDenied"];
        break;
        case 0:
    //     default:
          $msg = $GLOBALS["strAccessDenied"];
        break;
      }
    }
    
    switch ($procedure) {
      case 1:
        $_SESSION['dbCreatedSuccesfully'] = 1;
        print $GLOBALS["I18N"]->get(sprintf('<p>%s</p>',$GLOBALS["dbAlreadyCreated"]));
      break;
      case 2:
        $msg = $GLOBALS["strCuoldNotCreateDb"] . "<br/>" . $msg;
        $_SESSION['dbCreatedSuccesfully'] = 0;
      case 3:
      default:
        $_SESSION['dbCreatedSuccesfully'] = 0;
      break;
    }
    if (!$_SESSION['dbCreatedSuccesfully']) {
      print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s</div>',$msg));
      unset($_SESSION["printeable"]);
      include('install/install0.php');
    }
  break;
  case "install1":
    $editable = "general,bounces";
//    $type = "hidden";
    print $GLOBALS["I18N"]->get(sprintf('<div class="explain">%s</div>', $GLOBALS['strExplainInstall1']));
  break;
  case "install2":
    $editable = "security";
    $bouncesTest = processPopTest($_SESSION["bounce_mailbox_host"], $_SESSION["bounce_mailbox_user"], $_SESSION["bounce_mailbox_password"]);
    
    if ($bouncesTest == TRUE) {
      print $GLOBALS["I18N"]->get(sprintf('<p class="explain">'.$GLOBALS["popAccountOk"].'</p>'));
    }
    else {
      print $GLOBALS["I18N"]->get(sprintf('<p class="allwrong explain">%s%s</p>', $GLOBALS["popAccountKo"], $_SESSION["bounce_mailbox_host"]));
    }
    
    $test_connection2 = Sql_Connect($_SESSION["database_host"], $_SESSION["database_user"],$_SESSION["database_password"], $_SESSION["database_name"]);
    
    if ($test_connection2 == FALSE) {
      print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s</div><br>',$GLOBALS["strStillNoConnection"]));
      $canNotConnect = 1;
      willNotContinue();
    }
  break;
  case "install3":
    $editable = "debbuging";
  break;
  case "install4":
    $editable = "feedback";
  break;
  case "install5":
    $editable = "miscellaneous";
  break;
  case "install6":
    $editable = "experimental";
  break;
  case "install7":
    $editable = "advance";
    $tmpDir = '/tmp';
    
    if (!is_writable($tmpDir)) {
      print $GLOBALS["I18N"]->get(sprintf('<p class="wrong">%s</p>',$GLOBALS["strTmpNotWritable"]));
    }
    else {
      print $GLOBALS["I18N"]->get(sprintf('%s',$$GLOBALS["strTmpIsOk"]));
    }
  break;
  case "final_install":
    $editable = "";
    checkSessionCheckboxes();
    print $GLOBALS["I18N"]->get(sprintf('<div class="explain">%s</div>',$GLOBALS["strFinalValuesText"]));
    $showfinalvalues = 1; 
 break;
  case "write_install":
    writeToConfig($_SESSION, $GLOBALS["requiredVars"]);
    $yourPath = $_SESSION['adminpages'];
    print $GLOBALS["I18N"]->get(sprintf('<br><br>%s<a href="%s">here</a>',$GLOBALS['strGoToInitialiseDb'], $yourPath));
    include('install/footer.inc');
    cleanSession();
    exit;

  break;

  case "home":
  default:
    require("install/home.php");
  break;


}

if ($editable) {
  $edit = explode(",",$editable);
  foreach ($edit as $section) {
    print $GLOBALS["I18N"]->get(editVariable($GLOBALS["requiredVars"],'name', 'text', $section));
  }
}

if ($showfinalvalues) {

  print $GLOBALS["I18N"]->get(showFinalValues($GLOBALS["requiredVars"],'name', $_SESSION));

}