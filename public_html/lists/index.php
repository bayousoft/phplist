<?php

ob_start();
$er = error_reporting(0); 
require_once dirname(__FILE__) .'/admin/commonlib/lib/magic_quotes.php';
require_once dirname(__FILE__).'/admin/init.php';
## none of our parameters can contain html for now
$_GET = removeXss($_GET);
$_POST = removeXss($_POST);
$_REQUEST = removeXss($_REQUEST);

if (isset($_SERVER["ConfigFile"]) && is_file($_SERVER["ConfigFile"])) {
#  print '<!-- using '.$_SERVER["ConfigFile"].'-->'."\n";
  include $_SERVER["ConfigFile"];
} elseif (isset($_ENV["CONFIG"]) && is_file($_ENV["CONFIG"])) {
#  print '<!-- using '.$_ENV["CONFIG"].'-->'."\n";
  include $_ENV["CONFIG"];
} elseif (is_file("config/config.php")) {
#  print '<!-- using config/config.php -->'."\n";
  include "config/config.php";
} else {
  print "Error, cannot find config file\n";
  exit;
}
if (0) {#isset($GLOBALS["developer_email"]) && $GLOBALS['show_dev_errors']) {
  error_reporting(E_ALL);
} else {
  if (isset($error_level)) {
    error_reporting($error_level);
  } else {
    error_reporting($er);
  }
}
$GLOBALS["database_module"] = basename($GLOBALS["database_module"]);
$GLOBALS["language_module"] = basename($GLOBALS["language_module"]);

require_once dirname(__FILE__).'/admin/'.$GLOBALS["database_module"];
require_once dirname(__FILE__)."/texts/english.inc";
if (is_file($_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS["language_module"])) {
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$GLOBALS["language_module"];
} else {
  include_once dirname(__FILE__)."/texts/".$GLOBALS["language_module"];
}
require_once dirname(__FILE__)."/admin/defaultconfig.inc";
require_once dirname(__FILE__).'/admin/connect.php';
include_once dirname(__FILE__)."/admin/languages.php";
include_once dirname(__FILE__)."/admin/lib.php";
$I18N= new phplist_I18N();

if ($require_login || ASKFORPASSWORD) {
  # we need session info if an admin subscribes a user
  if (!empty($GLOBALS["SessionTableName"])) {
    require_once dirname(__FILE__).'/admin/sessionlib.php';
  }
  @session_start(); # it may have been started already in languages
}

if (!isset($_POST) && isset($HTTP_POST_VARS)) {
    require "admin/commonlib/lib/oldphp_vars.php";
}

/*
  We request you retain the inclusion of pagetop below. This will add invisible
  additional information to your public pages.
  This not only gives respect to the large amount of time given freely
  by the developers  but also helps build interest, traffic and use of
  PHPlist, which is beneficial to it's future development.

  Michiel Dethmers, Tincan Ltd 2000,2006
*/
include "admin/pagetop.php";
if (isset($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
} else {
  $id = 0;
}
// What is id,
// What is uid
// What is userid
// Why is there GET(id) and REQUEST(id)?

if (isset($_GET['uid']) && $_GET["uid"]) {
  $req = Sql_Fetch_Row_Query(sprintf('select subscribepage,id,password,email from %s where uniqid = "%s"',
    $tables["user"],$_GET["uid"]));
  $id = $req[0];
  $userid = $req[1];
  $userpassword = $req[2];
  $emailcheck = $req[3];
} elseif (isset($_GET["email"])) {
  $req = Sql_Fetch_Row_Query(sprintf('select subscribepage,id,password,email from %s where email = "%s"',
    $tables["user"],$_GET["email"]));
  $id = $req[0];
  $userid = $req[1];
  $userpassword = $req[2];
  $emailcheck = $req[3];
} elseif (isset($_REQUEST["unsubscribeemail"])) {
  $req = Sql_Fetch_Row_Query(sprintf('select subscribepage,id,password,email from %s where email = "%s"',
    $tables["user"],$_REQUEST["unsubscribeemail"]));
  $id = $req[0];
  $userid = $req[1];
  $userpassword = $req[2];
  $emailcheck = $req[3];
/*
} elseif ($_SESSION["userloggedin"] && $_SESSION["userid"]) {
  $req = Sql_Fetch_Row_Query(sprintf('select subscribepage,id,password,email from %s where id = %d',
    $tables["user"],$_SESSION["userid"]));
  $id = $req[0];
  $userid = $req[1];
  $userpassword = $req[2];
  $emailcheck = $req[3];
*/
} else {
  $userid = "";
  $userpassword = "";
  $emailcheck = "";
}

if (isset($_REQUEST['id']) && $_REQUEST["id"]){
  $id = sprintf('%d',$_REQUEST["id"]);
}
# make sure the subscribe page still exists
$req = Sql_fetch_row_query(sprintf('select id from %s where id = %d',$tables["subscribepage"],$id));
$id = $req[0];
$msg = "";

if (!empty($_POST["sendpersonallocation"])) {
  if (isset($_POST['email']) && $_POST["email"]) {
    $uid = Sql_Fetch_Row_Query(sprintf('select uniqid,email,id from %s where email = "%s"',
      $tables["user"],$_POST["email"]));
    if ($uid[0]) {
      sendMail ($uid[1],getConfig("personallocation_subject"),getUserConfig("personallocation_message",$uid[2]),system_messageheaders(),$GLOBALS["envelope"]);
      $msg = $GLOBALS["strPersonalLocationSent"];
      addSubscriberStatistics('personal location sent',1);
    } else {
      $msg = $GLOBALS["strUserNotFound"];
    }
  }
}

if (isset($_GET['p']) && $_GET["p"] == "subscribe") {
  $_SESSION["userloggedin"] = 0;
  $_SESSION["userdata"] = array();
}

$login_required =
  (ASKFORPASSWORD && $userpassword && $_GET["p"] == "preferences") ||
  (ASKFORPASSWORD && UNSUBSCRIBE_REQUIRES_PASSWORD && $userpassword && $_GET["p"] == "unsubscribe");

if ($login_required && empty($_SESSION["userloggedin"])) {
  $canlogin = 0;
  if (!empty($_POST["login"])) {
    # login button pushed, let's check formdata
    
    if (empty($_POST["email"])) {
      $msg = $strEnterEmail;
    } elseif (empty($_POST["password"])) {
      $msg = $strEnterPassword;
    } else {
      if (ENCRYPTPASSWORD) {
        $canlogin = md5($_POST["password"]) == $userpassword && $_POST["email"] == $emailcheck;
      } else {
        $canlogin = $_POST["password"] == $userpassword && $_POST["email"] == $emailcheck;
      }
    }
    if (!$canlogin) {
      $msg = $strInvalidPassword;
    } else {
      loadUser($emailcheck);
      $_SESSION["userloggedin"] = $_SERVER["REMOTE_ADDR"];
     }
   } elseif (!empty($_POST["forgotpassword"])) {
    # forgot password button pushed
    if (!empty($_POST["email"]) && $_POST["email"] == $emailcheck) {
      sendMail ($emailcheck,$GLOBALS["strPasswordRemindSubject"],$GLOBALS["strPasswordRemindMessage"]." ".$userpassword,system_messageheaders());
      $msg = $GLOBALS["strPasswordSent"];
    } else {
      $msg = $strPasswordRemindInfo;
    }
  } elseif (isset($_SESSION["userdata"]["email"]["value"]) && $_SESSION["userdata"]["email"]["value"] == $emailcheck) {
    # Entry without any button pushed (first time) test and, if needed, ask for password
    $canlogin = $_SESSION["userloggedin"];
    $msg = $strEnterPassword;
  }
} else {
  # Logged into session or login not required 
  $canlogin = 1;
}

if (!$id) {
  # find the default one:
  $id = getConfig("defaultsubscribepage");
  # fix the true/false issue
  if ($id == "true") $id = 1;
  if ($id == "false") $id = 0;
  if (!$id) {
    # pick a first
    $req = Sql_Fetch_row_Query(sprintf('select ID from %s where active',$tables["subscribepage"]));
    $id = $req[0];
  }
}

if ($login_required && empty($_SESSION["userloggedin"]) && !$canlogin) {
  print LoginPage($id,$userid,$emailcheck,$msg);
} elseif (isset($_GET['p']) && preg_match("/(\w+)/",$_GET["p"],$regs)) {
  if ($id) {
    switch ($_GET["p"]) {
      case "subscribe":
        $success = require "admin/subscribelib2.php";
        if ($success != 2) {
          print SubscribePage($id);
        }
        break;
      case "preferences":
        if (!isset($_GET["id"]) || !$_GET['id']) $_GET["id"] = $id;
        $success = require "admin/subscribelib2.php";
        if (!$userid) {
#          print "Userid not set".$_SESSION["userid"];
          print sendPersonalLocationPage($id);
        } elseif (ASKFORPASSWORD && $userpassword && !$canlogin) {
          print LoginPage($id,$userid,$emailcheck);
        } elseif ($success != 3) { 
          print PreferencesPage($id,$userid);
        }
        break;
      case "forward":
         print ForwardPage($id);
        break;
      case "confirm":
         print ConfirmPage($id);
        break;
      case "unsubscribe":
        print UnsubscribePage($id);
        break;
      default:
        FileNotFound();
    }
  } else {
    FileNotFound();
  }
} else {
  if ($id) $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }
  print '<title>'.$GLOBALS["strSubscribeTitle"].'</title>';
  print $data["header"];
  $req = Sql_Query(sprintf('select * from %s where active',$tables["subscribepage"]));
  if (Sql_Affected_Rows()) {
    while ($row = Sql_Fetch_Array($req)) {
      $intro = Sql_Fetch_Row_Query(sprintf('select data from %s where id = %d and name = "intro"',$tables["subscribepage_data"],$row["id"]));
      print $intro[0];
      printf('<p><a href="./?p=subscribe&id=%d">%s</a></p>',$row["id"],$row["title"]);
     }
  } else {
    printf('<p><a href="./?p=subscribe">%s</a></p>',$strSubscribeTitle);
  }

  printf('<p><a href="./?p=unsubscribe">%s</a></p>',$strUnsubscribeTitle);
  print $PoweredBy;
  print $data["footer"];
}

function LoginPage($id,$userid,$email = "",$msg = "") {
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }
  list($attributes,$attributedata) = PageAttributes($data);
  $html = '<title>'.$GLOBALS["strLoginTitle"].'</title>';
  $html .= $data["header"];
  $html .= '<b>'.$GLOBALS["strLoginInfo"].'</b><br/>';
  $html .= $msg;
  if (isset($_REQUEST["email"])) {
    $email = $_REQUEST["email"];
  }
  if (!isset($_POST["password"])) {
    $_POST["password"] = '';
  }

  $html .= formStart('name="loginform"');
  $html .= '<table border=0>';
  $html .= '<tr><td>'.$GLOBALS["strEmail"].'</td><td><input type=text name="email" value="'.$email.'" size="30"></td></tr>';
  $html .= '<tr><td>'.$GLOBALS["strPassword"].'</td><td><input type=password name="password" value="'.$_POST["password"].'" size="30"></td></tr>';
  $html .= '</table>';
   $html .= '<p><input type=submit name="login" value="'.$GLOBALS["strLogin"].'"></p>';
  if (ENCRYPTPASSWORD) {
    $html .= sprintf('<a href="mailto:%s?subject=%s">%s</a>',getConfig("admin_address"),$GLOBALS["strForgotPassword"],$GLOBALS["strForgotPassword"]);
  } else {
    $html .= '<input type=submit name="forgotpassword" value="'.$GLOBALS["strForgotPassword"].'">';
  }
  $html .= '<br/><br/>
    <p><a href="'.getConfig("unsubscribeurl").'&id='.$id.'">'.$GLOBALS["strUnsubscribe"].'</a></p>';
  $html .= '</form>'.$GLOBALS["PoweredBy"];
  $html .= $data["footer"];
  return $html;
}

function sendPersonalLocationPage($id) {
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }
  list($attributes,$attributedata) = PageAttributes($data);
  $html = '<title>'.$GLOBALS["strPreferencesTitle"].'</title>';
  $html .= $data["header"];
  $html .= '<b>'.$GLOBALS["strPreferencesTitle"].'</b><br/>';
  $html .= $GLOBALS["msg"];
  if ($_REQUEST["email"]) {
    $email = $_REQUEST["email"];
  } elseif ($_SESSION["userdata"]["email"]["value"]) {
    $email = $_SESSION["userdata"]["email"]["value"];
  }
  $html .= $GLOBALS["strPersonalLocationInfo"];

  $html .= formStart('name="form"');
  $html .= '<table border=0>';
  $html .= '<tr><td>'.$GLOBALS["strEmail"].'</td><td><input type=text name="email" value="'.$email.'" size="30"></td></tr>';
  $html .= '</table>';
   $html .= '<p><input type=submit name="sendpersonallocation" value="'.$GLOBALS["strContinue"].'"></p>';
  $html .= '<br/><br/>
    <p><a href="'.getConfig("unsubscribeurl").'&id='.$id.'">'.$GLOBALS["strUnsubscribe"].'</a></p>';
  $html .= '</form>'.$GLOBALS["PoweredBy"];
  $html .= $data["footer"];
  return $html;
}

function preferencesPage($id,$userid) {
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }
  list($attributes,$attributedata) = PageAttributes($data);
  $selected_lists = explode(',',$data["lists"]);
  $html = '<title>'.$GLOBALS["strPreferencesTitle"].'</title>';
  $html .= $data["header"];
  $html .= '<b>'.$GLOBALS["strPreferencesInfo"].'</b>';
  $html .= '

<br/><font class="required">'.$GLOBALS["strRequired"].'</font><br/>
'.$GLOBALS["msg"].'

<script language="Javascript" type="text/javascript">

var fieldstocheck = new Array();
    fieldnames = new Array();

function checkform() {
  for (i=0;i<fieldstocheck.length;i++) {
    if (eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].value") == "") {
      alert("'.$GLOBALS["strPleaseEnter"].' "+fieldnames[i]);
      eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].focus()");
      return false;
    }
  }
';
if ($data['emaildoubleentry']=='yes')
{
$html .='
  if(! compareEmail())
  {
    alert("Email addresses you entered do not match");
    return false;
  }';
}

$html .='
  return true;
}

function addFieldToCheck(value,name) {
  fieldstocheck[fieldstocheck.length] = value;
  fieldnames[fieldnames.length] = name;
}

function compareEmail()
{
  return (document.subscribeform.elements["email"].value == document.subscribeform.elements["emailconfirm"].value);
}


</script>';
  $html .= formStart('name="subscribeform"');
  $html .= '<table border=0>';
  $html .= ListAttributes($attributes,$attributedata,$data["htmlchoice"],$userid,$data['emaildoubleentry']);
  $html .= '</table>';
  if (ENABLE_RSS) {
    $html .= RssOptions($data,$userid);
   }
  $html .= ListAvailableLists($userid,$data["lists"]);
  if (isBlackListedID($userid)) {
    $html .= $GLOBALS["strYouAreBlacklisted"];
  }

  $html .= '<p><input type=submit name="update" value="'.$GLOBALS["strUpdatePreferences"].'" onClick="return checkform();"></p>
    </form><br/><br/>
    <p><a href="'.getConfig("unsubscribeurl").'&id='.$id.'">'.$GLOBALS["strUnsubscribe"].'</a></p>
  '.$GLOBALS["PoweredBy"];
  $html .= $data["footer"];
  return $html;
}

function subscribePage($id) {
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }
  list($attributes,$attributedata) = PageAttributes($data);
  $selected_lists = explode(',',$data["lists"]);
  $html = '<title>'.$GLOBALS["strSubscribeTitle"].'</title>';
  $html .= $data["header"];
  $html .= $data["intro"];
  $html .= '

<br/><font class="required">'.$GLOBALS["strRequired"].'</font><br/>
'.$GLOBALS["msg"].'

<script language="Javascript" type="text/javascript">

function checkform() {
  for (i=0;i<fieldstocheck.length;i++) {
    if (eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].type") == "checkbox") {
      if (document.subscribeform.elements[fieldstocheck[i]].checked) {
      } else {
        alert("'.$GLOBALS["strPleaseEnter"].' "+fieldnames[i]);
        eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].focus()");
        return false;
      }
    }
    else {
      if (eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].value") == "") {
        alert("'.$GLOBALS["strPleaseEnter"].' "+fieldnames[i]);
        eval("document.subscribeform.elements[\'"+fieldstocheck[i]+"\'].focus()");
        return false;
      }
    }
  }
  for (i=0;i<groupstocheck.length;i++) {
    if (!checkGroup(groupstocheck[i],groupnames[i])) {
      return false;
    }
  }
  ';
if ($data['emaildoubleentry']=='yes')
{
$html .='
  if(! compareEmail())
  {
    alert("'.str_replace('"','\"',$GLOBALS["strEmailsNoMatch"]).'");
    return false;
  }';
}

$html .='
  return true;
}

var fieldstocheck = new Array();
var fieldnames = new Array();
function addFieldToCheck(value,name) {
  fieldstocheck[fieldstocheck.length] = value;
  fieldnames[fieldnames.length] = name;
}
var groupstocheck = new Array();
var groupnames = new Array();
function addGroupToCheck(value,name) {
  groupstocheck[groupstocheck.length] = value;
  groupnames[groupnames.length] = name;
}

function compareEmail()
{
  return (document.subscribeform.elements["email"].value == document.subscribeform.elements["emailconfirm"].value);
}
function checkGroup(name,value) {
  option = -1;
  for (i=0;i<document.subscribeform.elements[name].length;i++) {
    if (document.subscribeform.elements[name][i].checked) {
      option = i;
    }
  }
  if (option == -1) {
    alert ("'.$GLOBALS["strPleaseEnter"].' "+value);
    return false;
  }
  return true;
}

</script>';
  $html .= formStart('name="subscribeform"');
  # @@@ update
  if (isset($_SESSION["adminloggedin"]) && $_SESSION["adminloggedin"]) {
    $html .= '<style type="text/css">
      div.adminmessage {
        width: 100%;
        border: 2px dashed #000000;
        padding: 10px;
        margin-bottom: 15px;
        background-color: #E7BE8F;

      }
      </style>';
    $html .= '<div class="adminmessage"><p><b>You are logged in as administrator ('.$_SESSION["logindetails"]["adminname"].') of this phplist system</b></p>';
    $html .= '<p>You are therefore offered the following choice, which your users will not see when they load this page.</p>';
    $html .= '<p><a href="'.$GLOBALS['adminpages'].'">Go back to admin area</a></p>';
    $html .= '<p><b>Please choose</b>: <br/><input type=radio name="makeconfirmed" value="1"> Make this user confirmed immediately
      <br/><input type=radio name="makeconfirmed" value="0" checked> Send this user a request for confirmation email </p></div>';
  }
  $html .= '<table border=0>';
  $html .= ListAttributes($attributes,$attributedata,$data["htmlchoice"],0,$data['emaildoubleentry']);
  $html .= '</table>';
  if (ENABLE_RSS) {
    $html .= RssOptions($data);
   }
  $html .= ListAvailableLists("",$data["lists"]);

  if (empty($data['button'])) {
    $data['button'] = $GLOBALS['strSubmit'];
  }
  if (USE_SPAM_BLOCK)
    $html .= '<div style="display:none"><input type="text" name="VerificationCodeX" value="" size="20"></div>';
  $html .= '<p><input type=submit name="subscribe" value="'.$data["button"].'" onClick="return checkform();"></p>
    </form><br/><br/>
    <p><a href="'.getConfig("unsubscribeurl").'&id='.$id.'">'.$GLOBALS["strUnsubscribe"].'</a></p>
  '.$GLOBALS["PoweredBy"];
  $html .= $data["footer"];
  return $html;
}

function confirmPage($id) {
  global $tables,$envelope;
  if (!$_GET["uid"]) {
    FileNotFound();
  }
  $req = Sql_Query("select * from {$tables["user"]} where uniqid = \"".$_GET["uid"]."\"");
  $userdata = Sql_Fetch_Array($req);
  if ($userdata["id"]) {
    $blacklisted = isBlackListed($userdata["email"]);
    $html = '<ul>';
    $lists = '';
    Sql_Query("update {$tables["user"]} set confirmed = 1,blacklisted = 0 where id = ".$userdata["id"]);
    # just in case the DB is not updated, should be merged with the above later
    Sql_Query("update {$tables["user"]} set optedin = 1 where id = ".$userdata["id"],1);

    $subscriptions = array();
    $req = Sql_Query(sprintf('select list.id,name,description from %s list, %s listuser where listuser.userid = %d and listuser.listid = list.id and list.active',$tables['list'],$tables['listuser'],$userdata['id']));
    if (!Sql_Affected_Rows()) {
      $lists = "\n * ".$GLOBALS["strNoLists"];
      $html .= '<li>'.$GLOBALS["strNoLists"].'</li>';
    }
    while ($row = Sql_fetch_array($req)) {
      array_push($subscriptions,$row['id']);
      $lists .= "\n *".stripslashes($row["name"]);
      $html .= '<li class="list">'.stripslashes($row["name"]).'<div class="listdescription">'.stripslashes($row["description"]).'</div></li>';
    }
    $html .= '</ul>';
    if ($blacklisted) {
      unBlackList($userdata['id']);
      addUserHistory($userdata["email"],"Confirmation","User removed from Blacklist for manual confirmation of subscription");
    }
    addUserHistory($userdata["email"],"Confirmation","Lists: $lists");

    $spage = $userdata["subscribepage"];

    $confirmationmessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("confirmationmessage:$spage",$userdata["id"]));

    if (!TEST) {
      sendMail($userdata["email"], getConfig("confirmationsubject:$spage"), $confirmationmessage,system_messageheaders(),$envelope);
      $adminmessage = $userdata["email"] . " has confirmed their subscription";
      if ($blacklisted) {
        $adminmessage .= "\nUser has been removed from blacklist";
      }
      sendAdminCopy("List confirmation",$adminmessage,$subscriptions);
      addSubscriberStatistics('confirmation',1);
    }
    $info = $GLOBALS["strConfirmInfo"];
  } else {
    logEvent("Request for confirmation for invalid user ID: ".substr($_GET["uid"],0,150));
    $html .= 'Error: '.$GLOBALS["strUserNotFound"];
    $info = $GLOBALS["strConfirmFailInfo"];
  }
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }

  $res = '<title>'.$GLOBALS["strConfirmTitle"].'</title>';
  $res .= $data["header"];
  $res .= '<h1>'.$info.'</h1>';
  $res .= $html;
  $res .= "<P>".$GLOBALS["PoweredBy"].'</p>';
  $res .= $data["footer"];
  return $res;
}

function unsubscribePage($id) {
  $pagedata = pageData($id);
  if (isset($pagedata['language_file']) && is_file(dirname(__FILE__).'/texts/'.$pagedata['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$pagedata['language_file'];
  }
  global $tables;
  $res .= '<title>'.$GLOBALS["strUnsubscribeTitle"].'</title>';
  $res = $pagedata["header"];
  if (isset($_GET["uid"])) {
    $req = Sql_Query("select * from $tables[user] where uniqid = \"".$_GET["uid"]."\"");
    $userdata = Sql_Fetch_Array($req);
    $email = $userdata["email"];
    if (UNSUBSCRIBE_JUMPOFF) {
      $_POST["unsubscribe"] = 1;
      $_POST["email"] = $email;
      $_POST["unsubscribereason"] = '"Jump off" set, reason not requested';
    }
  }

  if (isset($_POST["unsubscribe"]) && (isset($_POST["email"]) || isset($_POST["unsubscribeemail"])) && isset($_POST["unsubscribereason"])) {
    if (isset($_POST["email"])) {
      $email = trim($_POST["email"]);
    } else {
      $email = $_POST["unsubscribeemail"];
    }
    $query = Sql_Fetch_Row_Query("select id,email from {$tables["user"]} where email = \"$email\"");
    $userid = $query[0];
    $email = $query[1];
    if (!$userid) {
      $res .= 'Error: '.$GLOBALS["strUserNotFound"];
      logEvent("Request to unsubscribe non-existent user: ".substr($_POST["email"],0,150));
    } else {
      $subscriptions = array();
      $listsreq = Sql_Query(sprintf('select listid from %s where userid = %d',$GLOBALS['tables']['listuser'],$userid));
      while ($row = Sql_Fetch_Row($listsreq)) {
        array_push($subscriptions,$row[0]);
      }

      $result = Sql_query("delete from {$tables["listuser"]} where userid = \"$userid\"");
      $lists = "  * ".$GLOBALS["strAllMailinglists"]."\n";
      # add user to blacklist
      addUserToBlacklist($email,nl2br(strip_tags($_POST['unsubscribereason'])));

      addUserHistory($email,"Unsubscription","Unsubscribed from $lists");
      $unsubscribemessage = ereg_replace("\[LISTS\]", $lists,getUserConfig("unsubscribemessage",$userid));
      sendMail($email, getConfig("unsubscribesubject"), stripslashes($unsubscribemessage), system_messageheaders($email));
      $reason = $_POST["unsubscribereason"] ? "Reason given:\n".stripslashes($_POST["unsubscribereason"]):"No Reason given";
      sendAdminCopy("List unsubscription",$email . " has unsubscribed\n$reason",$subscriptions);
      addSubscriberStatistics('unsubscription',1);
    }

    if ($userid)
      $res .= '<h1>'.$GLOBALS["strUnsubscribeDone"] ."</h1><P>";
    $res .= $GLOBALS["PoweredBy"].'</p>';
    $res .= $pagedata["footer"];
    return $res;
  } elseif (isset($_POST["unsubscribe"]) && !$_POST["unsubscribeemail"]) {
    $msg = '<span class="error">'.$GLOBALS["strEnterEmail"]."</span><br>";
  } elseif (!empty($_GET["email"])) {
    $email = trim($_GET["email"]);
  } else {
    if (isset($_REQUEST["email"])) {
      $email = $_REQUEST["email"];
    } elseif (isset($_REQUEST['unsubscribeemail'])) {
      $email = $_REQUEST['unsubscribeemail'];
    } elseif (!isset($email)) {
      $email = '';
    }
  }
  if (!isset($msg)) {
    $msg = '';
  }

  $res .= '<b>'. $GLOBALS["strUnsubscribeInfo"].'</b><br>'.
  $msg.formStart();
  $res .= '<table>
  <tr><td>'.$GLOBALS["strEnterEmail"].':</td><td colspan=3><input type=text name="unsubscribeemail" value="'.$email.'" size=40></td></tr>
  </table>';

  if (!$email) {
    $res .= "<input type=submit name=unsubscribe value=\"$GLOBALS[strContinue]\"></form>\n";
    $res .= $GLOBALS["PoweredBy"];
    $res .= $pagedata["footer"];
    return $res;
  }

  $current = Sql_Fetch_Array_query("SELECT list.id as listid,user.uniqid as userhash, user.password as password FROM $tables[list] as list,$tables[listuser] as listuser,$tables[user] as user where list.id = listuser.listid and user.id = listuser.userid and user.email = \"$email\"");
  $some = $current["listid"];
  if (ASKFORPASSWORD && !empty($user['password'])) {
    # it is safe to link to the preferences page, because it will still ask for
    # a password
    $hash = $current["userhash"];
  } elseif (isset($_GET['uid']) && $_GET['uid'] == $current['userhash']) {
    # they got to this page from a link in an email
    $hash = $current['userhash'];
  } else {
    $hash = '';
  }

  $finaltext = $GLOBALS["strUnsubscribeFinalInfo"];
  $pref_url = getConfig("preferencesurl");
  $sep = ereg('\?',$pref_url)?'&':'?';
  $finaltext = eregi_replace('\[preferencesurl\]',$pref_url.$sep.'uid='.$hash,$finaltext);

  if (!$some) {
    $res .= "<b>".$GLOBALS["strNoListsFound"]."</b></ul>";
    $res .= '<p><input type=submit value="'.$GLOBALS["strResubmit"].'">';
  } else {
    list($r,$c) = explode(",",getConfig("textarea_dimensions"));
    if (!$r) $r = 5;
    if (!$c) $c = 65;
    $res .= $GLOBALS["strUnsubscribeRequestForReason"];
    $res .= sprintf('<br/><textarea name="unsubscribereason" cols="%d" rows="%d" wrap="virtual"></textarea>',$c,$r).'

    '.$finaltext.'

    <p><input type=submit name="unsubscribe" value="'.$GLOBALS["strUnsubscribe"].'"></p>';
  }

  $res .= '<p>'.$GLOBALS["PoweredBy"].'</p>';
  $res .= $pagedata["footer"];
  return $res;
}

function forwardPage($id) {
  global $tables,$envelope;
  $html = '';
  $subtitle = '';
  if (!isset($_GET["uid"]) || !$_GET['uid'])
    FileNotFound();

  $forwardemail = '';
  if (isset($_GET['email'])) {
    $forwardemail = $_GET['email'];
  }
  $mid = 0;
  if (isset($_GET['mid'])) {
    $mid = sprintf('%d',$_GET['mid']);
    $req = Sql_Query(sprintf('select * from %s where id = %d',$tables["message"],$mid));
    $messagedata = Sql_Fetch_Array($req);
    $mid = $messagedata['id'];
    if ($mid) {
      $subtitle = $GLOBALS['strForwardSubtitle'].' '.stripslashes($messagedata['subject']);
    }
  }
  $req = Sql_Query("select * from {$tables["user"]} where uniqid = \"".$_GET["uid"]."\"");
  $userdata = Sql_Fetch_Array($req);

  $req = Sql_Query(sprintf('select * from %s where email = "%s"',$tables["user"],$forwardemail));
  $forwarduserdata = Sql_Fetch_Array($req);

  if ($userdata["id"] && $mid) {
    if (!is_email($forwardemail)) {
      $info = $GLOBALS['strForwardEnterEmail'];
      $html .= '<form method="get">';
      $html .= sprintf('<input type=hidden name="mid" value="%d">',$mid);
      $html .= sprintf('<input type=hidden name="id" value="%d">',$id);
      $html .= sprintf('<input type=hidden name="uid" value="%s">',$userdata['uniqid']);
      $html .= sprintf('<input type=hidden name="p" value="forward">');
      $html .= sprintf('<input type=text name="email" value="%s" size=35 class="attributeinput">',$forwardemail);
      $html .= sprintf('<input type=submit value="%s"></form>',$GLOBALS['strContinue']);
    } else {
      # check whether the email to forward exists and whether they have received the message
      if ($forwarduserdata['id']) {
        $sent = Sql_Fetch_Row_Query(sprintf('select entered from %s where userid = %d and messageid = %d',
          $tables['usermessage'],$forwarduserdata['id'],$mid));
        # however even if that's the case, we don't want to reveal this information
      }

      $done = Sql_Fetch_Array_Query(sprintf('select user,status,time from %s where forward = "%s" and message = %d',
        $tables['user_message_forward'],$forwardemail,$mid));
      if ($done['status'] === 'sent') {
        $info = $GLOBALS['strForwardAlreadyDone'];
      } else {
        $messagelists = array();
        $messagelistsreq = Sql_Query(sprintf('select listid from %s where messageid = %d',$GLOBALS['tables']['listmessage'],$mid));
        while ($row = Sql_Fetch_Row($messagelistsreq)) {
          array_push($messagelists,$row[0]);
        }

        if (!TEST) {
          # forward the message
          require 'admin/sendemaillib.php';
          # sendEmail will take care of blacklisting
          if (sendEmail($mid,$forwardemail,'forwarded',$userdata['htmlemail'],array(),$userdata)) {
            $info = $GLOBALS["strForwardSuccessInfo"];
            sendAdminCopy("Message Forwarded",$userdata["email"] . " has forwarded a message $mid to $forwardemail",$messagelists);
            Sql_Query(sprintf('insert into %s (user,message,forward,status,time)
              values(%d,%d,"%s","sent",now())',
              $tables['user_message_forward'],$userdata['id'],$mid,$forwardemail));
          } else {
            $info = $GLOBALS["strForwardFailInfo"];
            sendAdminCopy("Message Forwarded",$userdata["email"] . " tried forwarding a message $mid to $forwardemail but failed",$messagelists);
            Sql_Query(sprintf('insert into %s (user,message,forward,status,time)
              values(%d,%d,"%s","failed",now())',
              $tables['user_message_forward'],$userdata['id'],$mid,$forwardemail));
          }
        }
      }

    }

  } else {
    logEvent("Forward request from invalid user ID: ".substr($_GET["uid"],0,150));
    $info = $GLOBALS["strForwardFailInfo"];
  }
  $data = PageData($id);
  if (isset($data['language_file']) && is_file(dirname(__FILE__).'/texts/'.$data['language_file'])) {
    @include dirname(__FILE__).'/texts/'.$data['language_file'];
  }

  $res = '<title>'.$GLOBALS["strForwardTitle"].'</title>';
  $res .= $data["header"];
  $res .= '<h1>'.$subtitle.'</h1>';
  $res .= '<h2>'.$info.'</h2>';
  $res .= $html;
  $res .= "<P>".$GLOBALS["PoweredBy"].'</p>';
  $res .= $data["footer"];
  return $res;
}
?>
