<?
ob_start();
$er = error_reporting(0); # some ppl have warnings on
if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
	print '<!-- using '.$_SERVER["ConfigFile"].'-->'."\n";
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
	print '<!-- using '.$_ENV["CONFIG"].'-->'."\n";
  include $_ENV["CONFIG"];
} elseif (is_file("config/config.php")) {
	print '<!-- using config/config.php -->'."\n";
  include "config/config.php";
} else {
	print "Error, cannot find config file\n";
  exit;
}
error_reporting($er);

if ($require_login || ASKFORPASSWORD) {
	# we need session info if an admin subscribes a user
	session_start();
}

if (!isset($_POST) && isset($HTTP_POST_VARS)) {
    require "admin/commonlib/lib/oldphp_vars.php";
}

/*
	We request you retain the inclusion of pagetop below. This will add invisible
  additional information to your public pages.
	This not only gives respect to the large amount of time given freely
  by the developers	but also helps build interest, traffic and use of
  PHPlist, which is beneficial to it's future development.

	Michiel Dethmers, Tincan Ltd 2003
*/
include "admin/pagetop.php";
$id = sprintf('%d',$_GET["id"]);

if ($_GET["uid"]) {
  $req = Sql_Fetch_Row_Query(sprintf('select subscribepage,id,password,email from %s where uniqid = "%s"',
    $tables["user"],$_GET["uid"]));
  $id = $req[0];
  $userid = $req[1];
  $passwordcheck = $req[2];
  $emailcheck = $req[3];
} else {
	$userid = "";
  $passwordcheck = "";
  $emailcheck = "";
}
# make sure the subscribe page still exists
$req = Sql_fetch_row_query(sprintf('select id from %s where id = %d',$tables["subscribepage"],$id));
$id = $req[0];

if ($_POST["sendpersonallocation"]) {
  if ($_POST["email"]) {
    $uid = Sql_Fetch_Row_Query(sprintf('select uniqid,email,id from %s where email = "%s"',
      $tables["user"],$_POST["email"]));
    if ($uid[0]) {
      sendMail ($uid[1],getConfig("personallocation_subject"),getUserConfig("personallocation_message",$uid[2]),system_messageheaders(),$GLOBALS["envelope"]);
      $msg = $GLOBALS["strPersonalLocationSent"];
    } else {
      $msg = $GLOBALS["strUserNotFound"];
    }
  }
}

if (ASKFORPASSWORD) {
  $canlogin = 0;
	if ($_POST["login"]) {
    if (!$_POST["email"]) {
      $msg = $strEnterEmail;
    } elseif (!$_POST["password"]) {
      $msg = $strEnterPassword;
    } else {
      if (ENCRYPTPASSWORD) {
        $canlogin = md5($_POST["password"]) == $passwordcheck && $_POST["email"] == $emailcheck;
      } else {
        $canlogin = $_POST["password"] == $passwordcheck && $_POST["email"] == $emailcheck;
      }
    }
    if (!$canlogin) {
    	$msg = $strInvalidPassword;
    } else {
			loadUser($emailcheck);
 		}
 	} elseif ($_POST["forgotpassword"]) {
  	if ($_POST["email"] && $_POST["email"] == $emailcheck) {
    	sendMail ($emailcheck,$GLOBALS["strPasswordRemindSubject"],$GLOBALS["strPasswordRemindMessage"]." ".$passwordcheck,system_messageheaders(),$GLOBALS["envelope"]);
	  	$msg = $GLOBALS["strPasswordSent"];
    } else {
    	$msg = $strPasswordRemindInfo;
    }
  } elseif ($_SESSION["userdata"]["email"]["value"] == $emailcheck) {
  	$canlogin = 1;
  }
} else {
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

if (preg_match("/(\w+)/",$_GET["p"],$regs)) {
  if ($id) {
    switch ($_GET["p"]) {
      case "subscribe":
			  require "admin/subscribelib2.php";
		   	print SubscribePage($id);
      	break;
      case "preferences":
      	if (!$_GET["id"]) $_GET["id"] = $id;
			  require "admin/subscribelib2.php";
        if (!$userid) {
        	print sendPersonalLocationPage($id);
        } elseif (ASKFORPASSWORD && $passwordcheck && !$canlogin) {
        	print LoginPage($id,$userid,$emailcheck);
        } else {
			   	print PreferencesPage($id,$userid);
       	}
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
	print '<title>'.$GLOBALS["strSubscribeTitle"].'</title>';
  print $data["header"];
	$req = Sql_Query(sprintf('select * from %s where active',$tables["subscribepage"]));
  if (Sql_Affected_Rows()) {
  	while ($row = Sql_Fetch_Array($req)) {
			printf('<p><a href="./?p=subscribe&id=%d">%s</a></p>',$row["id"],$row["title"]);
   	}
	} else {
	  printf('<p><a href="./?p=subscribe">%s</a></p>',$strSubscribeTitle);
	}

	printf('<p><a href="./?p=unsubscribe">%s</a></p>',$strUnsubscribeTitle);
  print $PoweredBy;
	print $data["footer"];
}

function LoginPage($id,$userid,$email = "") {
	$data = PageData($id);
  list($attributes,$attributedata) = PageAttributes($data);
	$html = '<title>'.$GLOBALS["strLoginTitle"].'</title>';
  $html .= $data["header"];
  $html .= '<b>'.$GLOBALS["strLoginInfo"].'</b><br/>';
  $html .= $GLOBALS["msg"];
  if ($_REQUEST["email"]) {
  	$email = $_REQUEST["email"];
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
		<p><a href="'.getConfig("unsubscribeurl").'">'.$GLOBALS["strUnsubscribe"].'</a></p>';
  $html .= '</form>'.$GLOBALS["PoweredBy"];
	$html .= $data["footer"];
  return $html;
}

function sendPersonalLocationPage($id) {
	$data = PageData($id);
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
		<p><a href="'.getConfig("unsubscribeurl").'">'.$GLOBALS["strUnsubscribe"].'</a></p>';
  $html .= '</form>'.$GLOBALS["PoweredBy"];
	$html .= $data["footer"];
  return $html;
}

function preferencesPage($id,$userid) {
	$data = PageData($id);
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
  return true;
}
function addFieldToCheck(value,name) {
  fieldstocheck[fieldstocheck.length] = value;
  fieldnames[fieldnames.length] = name;
}

</script>';
	$html .= formStart('name="subscribeform"');
	$html .= '<table border=0>';
  $html .= ListAttributes($attributes,$attributedata,$data["htmlchoice"],$userid);
	$html .= '</table>';
  if (ENABLE_RSS) {
		$html .= RssOptions($data,$userid);
 	}
	$html .= ListAvailableLists($userid,$data["lists"]);

	$html .= '<p><input type=submit name="update" value="'.$GLOBALS["strUpdatePreferences"].'" onClick="return checkform();"></p>
		</form><br/><br/>
		<p><a href="'.getConfig("unsubscribeurl").'">'.$GLOBALS["strUnsubscribe"].'</a></p>
  '.$GLOBALS["PoweredBy"];
	$html .= $data["footer"];
  return $html;
}

function subscribePage($id) {
	$data = PageData($id);
  list($attributes,$attributedata) = PageAttributes($data);
  $selected_lists = explode(',',$data["lists"]);
	$html = '<title>'.$GLOBALS["strSubscribeTitle"].'</title>';
  $html .= $data["header"];
  $html .= $data["intro"];
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
  return true;
}
function addFieldToCheck(value,name) {
  fieldstocheck[fieldstocheck.length] = value;
  fieldnames[fieldnames.length] = name;
}

</script>';
	$html .= formStart('name="subscribeform"');
  if ($_SESSION["adminloggedin"]) {
  	$html .= '<p><b>You are logged in as '.$_SESSION["logindetails"]["adminname"].'</b></p>';
  	$html .= '<p><b>Please choose</b>: <br/><input type=radio name="makeconfirmed" value="1"> Make confirmed immediately
    	<br/><input type=radio name="makeconfirmed" value="0"> Send request for confirmation email </p>';
  }
	$html .= '<table border=0>';
  $html .= ListAttributes($attributes,$attributedata,$data["htmlchoice"]);
	$html .= '</table>';
  if (ENABLE_RSS) {
		$html .= RssOptions($data);
 	}
	$html .= ListAvailableLists("",$data["lists"]);

	$html .= '<p><input type=submit name="subscribe" value="'.$data["button"].'" onClick="return checkform();"></p>
		</form><br/><br/>
		<p><a href="'.getConfig("unsubscribeurl").'">'.$GLOBALS["strUnsubscribe"].'</a></p>
  '.$GLOBALS["PoweredBy"];
	$html .= $data["footer"];
  return $html;
}

function confirmPage($id) {
  global $tables,$envelope;
  if (!$_GET["uid"])
  	FileNotFound();
  $req = Sql_Query("select * from {$tables["user"]} where uniqid = \"".$_GET["uid"]."\"");
  $userdata = Sql_Fetch_Array($req);
  if ($userdata["id"]) {
  	$html = '<ul>';
    Sql_Query("update {$tables["user"]} set confirmed = 1 where id = ".$userdata["id"]);
    $req = Sql_Query("select name,description from $tables[list],$tables[listuser] where $tables[listuser].userid = ".$userdata["id"] ." and $tables[listuser].listid = $tables[list].id");
    if (!Sql_Affected_Rows()) {
      $lists = "\n * ".$GLOBALS["strNoLists"];
      $html .= '<li>'.$GLOBALS["strNoLists"];
    }
    while ($row = Sql_fetch_array($req)) {
      $lists .= "\n *".$row["name"];
      $html .= '<li class="list">'.$row["name"].'<div class="listdescription">'.stripslashes($row["description"]).'</div></li>';
		}
    $html .= '</ul>';

    $spage = $userdata["subscribepage"];

    $confirmationmessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("confirmationmessage:$spage",$userdata["id"]));

    if (!TEST) {
      sendMail($userdata["email"], getConfig("confirmationsubject:$spage"), $confirmationmessage,system_messageheaders(),$envelope);
      sendAdminCopy("List confirmation",$userdata["email"] . " has confirmed their subscription");
  	}
    $info = $GLOBALS["strConfirmInfo"];
  } else {
    $html .= 'Error: '.$GLOBALS["strUserNotFound"];
    $info = $GLOBALS["strConfirmFailInfo"];
  }
  $data = PageData($id);

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
	global $tables;
  $res = $pagedata["header"];
  $res .= '<title>'.$GLOBALS["strUnsubscribeTitle"].'</title>';
  if ($_POST["unsubscribe"] && eregi(".+\@.+\..+",$_POST["email"]) && $_POST["list"]) {
  	$email = trim($_POST["email"]);
    $result = Sql_query("SELECT * FROM $tables[list]");
    while ($row = Sql_fetch_array($result)) {
      if ($row["active"])
        $availlists[$row["id"]] = $row["name"];
    }

    $query = Sql_Fetch_Row_Query("select id from {$tables["user"]} where email = \"$email\"");
    $userid = $query[0];

    if ($_POST["list"] && !$_POST["list"]["none"]) {
      if ($_POST["list"]["all"]) {
        $result = Sql_query("delete from {$tables["listuser"]} where userid = \"$userid\"");
        $lists = "  * All mailinglists\n";
      } else {
        while(list($key,$val)= each($_POST["list"])) {
          if ($val == "signoff") {
            $result = Sql_query("delete from $tables[listuser] where userid = \"$userid\" and listid = \"$key\"");
            $lists .= "  * ".$availlists[$key] . "\n";
          }
        }
      }
      $unsubscribemessage = ereg_replace("\[LISTS\]", $lists,getUserConfig("unsubscribemessage",$userid));
      sendMail($email, getConfig("unsubscribesubject"), $unsubscribemessage, system_messageheaders($email));
      sendAdminCopy("List unsubscription",$email . " has unsubscribed from\n $lists");
    }

    $res .= '<h1>'.$GLOBALS["strUnsubscribeDone"] ."</h1><P>";
    $res .= $GLOBALS["PoweredBy"].'</p>';
    $res .= $pagedata["footer"];
    return $res;
  } elseif ($_POST["unsubscribe"] && !$_POST["email"]) {
    $msg = '<span class="error">'.$GLOBALS["strEnterEmail"]."</span><br>";
  } elseif ($_GET["uid"]) {
    $req = Sql_Query("select * from $tables[user] where uniqid = \"".$_GET["uid"]."\"");
    $userdata = Sql_Fetch_Array($req);
    $email = $userdata["email"];
  } elseif ($_GET["email"]) {
    $email = trim($_GET["email"]);
  } else {
    $email = $_POST["email"];
  }
    
  $res .= '<b>'. $GLOBALS["strUnsubscribeInfo"].'</b><br>'.
  $msg.formStart();
  $res .= '<table>
  <tr><td>'.$GLOBALS["strEnterEmail"].':</td><td colspan=3><input type=text name=email value="'.$email.'" size=40></td></tr>
  </table>';

  if (!$email) {
    $res .= "<input type=submit name=unsubscribe value=\"$GLOBALS[strContinue]\"></form>\n";
    $res .= $GLOBALS["PoweredBy"];
    $res .= $pagedata["footer"];
    return $res;
  }

  $res .= $GLOBALS["strUnsubscribeSelect"].':';
  $res .= '<ul>';
  $result = Sql_query("SELECT $tables[list].id as id, $tables[list].name as name, $tables[list].description as description FROM $tables[list],$tables[listuser],$tables[user] where $tables[list].id = $tables[listuser].listid and $tables[user].id = $tables[listuser].userid and $tables[user].email = \"$email\"");
  $num = Sql_Affected_Rows();
	$hidesinglelist = getConfig("hide_single_list");
  $hide =  $num == 1 && $hidesinglelist == "true";

  if (!$hide) {
    $out = ' <li><input type=checkbox name=list[all] value=signoff>'.$GLOBALS["strAllLists"].'
      <li><input type=checkbox name=list[none] value=signoff>'.$GLOBALS["strNoLists"];
  }

  while ($row = Sql_fetch_array($result)) {
  	if (!$hide) {
      $out .= "<li><input type=checkbox name=list[".$row["id"] . "] value=signoff>".$row["name"] ." \n";
      $desc = nl2br(StripSlashes($row["description"]));
      $out .= "<dd>$desc\n";
    } else {
    	$out .= "<input type=hidden name=list[".$row["id"] . "] value=signoff>";
    }
    $some = 1;
  }

  if (!$some) {
    $res .= "<b>".$GLOBALS["strNoListsFound"]."</b>";
    $res .= '<p><input type=submit value="'.$GLOBALS["strResubmit"].'">';
  } else {
    $res .= $out;
    $res .= '</ul>
    <p><input type=submit name=unsubscribe value="'.$GLOBALS["strUnsubscribeSubmit"].'">';
  }

  $res .= '<p>'.$GLOBALS["PoweredBy"].'</p>';
  $res .= $pagedata["footer"];
  return $res;
}
?>
