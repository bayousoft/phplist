<?
ob_start();
$er = error_reporting(0);
# setup commandline
if ($_SERVER["USER"]) {
  for ($i=0; $i<$_SERVER['argc']; $i++) {
    $my_args = array();
    if (ereg("(.*)=(.*)",$_SERVER['argv'][$i], $my_args)) {
      $_GET[$my_args[1]] = $my_args[2];
      $_REQUEST[$my_args[1]] = $my_args[2];
    }
  }
  $GLOBALS["commandline"] = 1;
  $dir = dirname($_SERVER["SCRIPT_FILENAME"]);
  chdir($dir);
} else {
  $GLOBALS["commandline"] = 0;
}

header("Cache-Control: no-cache, must-revalidate");           // HTTP/1.1
header("Pragma: no-cache");                                   // HTTP/1.0
if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
	print '<!-- using '.$_SERVER["ConfigFile"].'-->'."\n";
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
#	print '<!-- using '.$_ENV["CONFIG"].'-->'."\n";
  include $_ENV["CONFIG"];
} elseif (is_file("../config/config.php")) {
	print '<!-- using ../config/config.php -->'."\n";
  include "../config/config.php";
} else {
	print "Error, cannot find config file\n";
  exit;
}
error_reporting($er);

if ($GLOBALS["commandline"]) {
  if (!in_array($_SERVER["USER"],$GLOBALS["commandline_users"])) {
    clineError("Sorry, You do not have sufficient permissions to run this script");
    exit;
  }
  $GLOBALS["require_login"] = 0;
  $opt = getopt("p:");
  if ($opt["p"]) {
		if (!in_array($opt["p"],$GLOBALS["commandline_pages"])) {
			clineError($opt["p"]." does not process commandline");
    } else {
	    $_GET["page"] = $opt["p"];
    }
  } else {
    clineUsage(" [other parameters]");
    exit;
  }
}

# fix for old PHP versions, although not failsafe :-(
if (!isset($_POST) && isset($HTTP_POST_VARS)) {
  include "commonlib/lib/oldphp_vars.php";
}
if (!ini_get("register_globals")) {
	# fix register globals, for now, should be phased out gradually
  # sure, this gets around the entire reason that register globals 
  # should be off, but going through three years of code takes a long time....
  foreach ($_REQUEST as $key => $val) {
  	$$key = $val;
#    print "$key = $val<br/>";
  }
}
include "commonlib/lib/interfacelib.php";
include "pluginlib.php";
include "pagetop.php";
if (!isset($_GET["page"]))
  $page = "home";
else
	$page = $_GET["page"];
preg_match("/([\w_]+)/",$page,$regs);
$page = $regs[1];
# stop login system when no admins exist
if (!Sql_Table_Exists($tables["admin"])) {
	$GLOBALS["require_login"] = 0;
} else {
  $num = Sql_Query("select * from {$tables["admin"]}");
  if (!Sql_Affected_Rows())
    $GLOBALS["require_login"] = 0;
}

$page_title = 'PHPlist';
include "lan/".$adminlanguage["iso"]."/pagetitles.php";

print '<script language="javascript" type="text/javascript" src="js/select_style.js"></script>';
print '<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">';           // HTTP/1.1
print '<meta http-equiv="Pragma" content="no-cache">';           // HTTP/1.1
print "<title>PHPlist :: ";
if (isset($GLOBALS["installation_name"]))
	print $GLOBALS["installation_name"] .' :: ';
print "$page_title</title>";

if (isset($GLOBALS["require_login"]) && $GLOBALS["require_login"]) {
  session_start();
  if (!$_SESSION["adminloggedin"] && $_REQUEST["login"] && $_REQUEST["password"]) {
    $userdata = Sql_Fetch_Row_Query(sprintf('select password,disabled,id from %s where loginname = "%s"',$tables["admin"],$_REQUEST["login"]));
    if ($userdata[1]) {
    	$_SESSION["adminloggedin"] = "";
    	$_SESSION["logindetails"] = "";
      $msg = "your account has been disabled";
      $page = "login";
    } elseif ($userdata[0] && $userdata[0] == $_REQUEST["password"] && strlen($userdata[0]) > 3) {
      $_SESSION["adminloggedin"] = getenv("REMOTE_ADDR");
      # assigning to $_SESSION this is broken in 4.2.3
      $_SESSION["logindetails"] = array(
        "adminname" => $_REQUEST["login"],
        "id" => $userdata[2]
      );
      if ($_POST["page"] && $_POST["page"] != "") {
      	$page = $_POST["page"];
      }
    } else {
    	$_SESSION["adminloggedin"] = "";
    	$_SESSION["logindetails"] = "";
      $msg = "invalid password";
      $page = "login";
    }
  } elseif ($_REQUEST["forgotpassword"]) {
  	$req = Sql_Query('select email,password,loginname from '.$tables["admin"].' where email = "'.$_REQUEST["forgotpassword"].'"');
    if (Sql_Affected_Rows()) {
    	$row = Sql_Fetch_Row($req);
			sendMail ($row[0],"Your password for PHPlist","\n\nYour loginname is $row[2]\nYour password is $row[1]",system_messageheaders(),$envelope_from);
      $msg = "Your password has been sent by email";
   	}
    $page = "login";
  } elseif (!session_is_registered("adminloggedin")) {
    $page = "login";
  } elseif (CHECK_SESSIONIP && $_SESSION["adminloggedin"] && $_SESSION["adminloggedin"] != getenv("REMOTE_ADDR")) {
  	$msg = "Your IP address has changed. For security reasons, please login again";
    $_SESSION["adminloggedin"] = "";
    $_SESSION["logindetails"] = "";
  	$page = "login";
  } elseif ($_SESSION["logindetails"]) {
    $noaccess_req = Sql_Fetch_Row_Query(sprintf('select id,disabled 
      from %s where id = "%s"',$tables["admin"],
      $_SESSION["logindetails"]["id"]));
    if (!$noaccess_req[0]) {
      session_unregister("adminloggedin");
      session_unregister("logindetails");
      $_SESSION["adminloggedin"] = "";
      $_SESSION["logindetails"] = "";
      $page = "login";
      $msg = "No such account";
    } elseif ($noaccess_req[1]) {
      session_unregister("adminloggedin");
      session_unregister("logindetails");
      $_SESSION["adminloggedin"] = "";
      $_SESSION["logindetails"] = "";
      $page = "login";
      $msg = "your account has been disabled";
    }
  }
}
include "header.inc";
if ($page != "") {
  preg_match("/([\w_]+)/",$page,$regs);
  $include = $regs[1];
  $include .= ".php";
  $include = $page . ".php";
} else {
	$include = "home.php";
}

print '<p class="leaftitle">PHPlist - '.strtolower($page_title).'</p>';

if ($GLOBALS["require_login"] && $page != "login") {
	if ($_GET["page"] == "logout") {
  	$greeting = "goodbye";
  } else {
  	$hr = date("G");
    if ($hr > 0 && $hr < 12) {
    	$greeting = "good morning";
    } elseif ($hr <= 18) {
    	$greeting = "good afternoon";
    } else {
     	$greeting = "good evening";
   	}
  }
  print '<div><font style="font-size : 12px;font-family : Arial, Helvetica, sans-serif;	font-weight : bold;"> '.$greeting." ".adminName($_SESSION["logindetails"]["id"]). "</font></div>";
  if ($_REQUEST["page"] != "logout")
  print '<div align="right">'.PageLink2("logout","Logout").'</div>';
}

# include some information
if (is_file("info/".$adminlanguage["info"]."/$include")) {
	@include "info/".$adminlanguage["info"]."/$include";
} else {
#	print "Not a file: "."info/".$adminlanguage["info"]."/$include";
}

#if (!ini_get("register_globals") && WARN_ABOUT_PHP_SETTINGS)
#	Error("Register Globals in your php.ini needs to be <b>on</b>");
if (ini_get("safe_mode") && WARN_ABOUT_PHP_SETTINGS)
	Warn("In safe mode, not everything will work as expected");
if (!ini_get("magic_quotes_gpc") && WARN_ABOUT_PHP_SETTINGS)
	Warn("Things will work better when PHP magic_quotes_gpc = on");
if (defined("ENABLE_RSS") && ENABLE_RSS && !function_exists("xml_parse") && WARN_ABOUT_PHP_SETTINGS)
	Warn("XML is not supported");

if (ALLOW_ATTACHMENTS && (!is_dir($GLOBALS["attachment_repository"]) || !is_writeable ($GLOBALS["attachment_repository"]))) {
	Warn("The attachment repository doesn't exist or isn't writable");
}
;

if (USEFCK) {
	$imgdir = getenv("DOCUMENT_ROOT").$GLOBALS["pageroot"].'/'.FCKIMAGES_DIR.'/';
  if (!is_dir($imgdir) || !is_writeable ($imgdir)) {
		Warn("The FCK image directory does not exist, or is not writable");
	}
}

if (defined("USE_PDF") && USE_PDF && !defined('FPDF_VERSION')) {
	Warn("You are trying to use PDF support without having FPDF loaded");
}

$this_doc = getenv("REQUEST_URI");
if (preg_match("#(.*?)/admin#i",$this_doc,$regs)) {
	$check_pageroot = $pageroot;
  $check_pageroot = preg_replace('#/$#','',$check_pageroot);
	if ($check_pageroot != $regs[1] && WARN_ABOUT_PHP_SETTINGS)
  	Warn("The pageroot in your config doesn't seem to match the current location<br/>Check your config file");
}
clearstatcache();
if (checkAccess($page,"")) {
  if (is_file($include) || is_link($include)) {
    # check whether there is a language file to include
    if (is_file("lan/".$adminlanguage["iso"]."/".$include)) {
      include "lan/".$adminlanguage["iso"]."/".$include;
    }
  #	print "Including $include<br/>";
    if (!@include "$include") {
      #print "Error including $include";
    }
  #  print "End of inclusion<br/>";
  } elseif ($_GET["pi"] && is_object($GLOBALS["plugins"][$_GET["pi"]])) {
    $plugin = $GLOBALS["plugins"][$_GET["pi"]];
    $menu = $plugin->adminmenu();
    if (is_file($plugin->coderoot . "$include")) {
      include ($plugin->coderoot . "$include");
    } elseif ($include == "main.php") {
    	print '<h1>'.$plugin->name.'</h1><ul>';
			foreach ($menu as $page => $desc) {
				print '<li>'.PageLink2($page,$desc).'</li>';
			}
      print '</ul>';
    } else {
      print "$page -&gt; Sorry this page was not found in the plugin<br/>";
      #print $plugin->coderoot . "$include";
    }
  } else {
    if ($GLOBALS["commandline"]) {  
      clineError("Sorry, that module does not exist");
      exit;
    }

    print "$page -&gt; Sorry not Implemented yet";
  }
} else {
  Error("You do not have enough privileges to access this page");
}
if ($GLOBALS["commandline"]) {
  ob_clean();
  exit;
} elseif (!$_GET["omitall"]) {
  ob_end_flush();
  include "footer.inc";
} 
