
<?
require_once "accesscheck.php";

ob_end_flush();
if (Sql_Table_exists($tables["config"])) {
  $dbversion = getConfig("version");
  if ($dbversion != VERSION && !defined("IN_WEBBLER")) {
    Error("Your database is out of date: $dbversion, please make sure to upgrade");
    $upgrade_required = 1;
  }
} else {
	Error("Database has not been initialised, go to ".PageLink2("initialise","Initialise Database"). " to continue");
  $GLOBALS["firsttime"] = 1;
  return;
}

# check for latest version
$checkinterval = sprintf('%d',getConfig("check_new_version"));
if (!isset($checkinterval)) {
	$checkinterval = 7;
}
if ($checkinterval) {
  $needscheck = Sql_Fetch_Row_Query(sprintf('select date_add(value,interval %d day) < now() as needscheck from %s where item = "updatelastcheck"',$checkinterval,$tables["config"]));
  if ($needscheck[0]) {
    ini_set("user_agent",NAME." (PHPlist version ".VERSION.")");
    ini_set("default_socket_timeout",5);
    if ($fp = @fopen ("http://www.phplist.com/files/LATESTVERSION","r")) {
      $latestversion = fgets ($fp);
      $latestversion = preg_replace("/[^\.\d]/","",$latestversion);
      $v = VERSION;
      $v = str_replace("-dev","",$v);
      if (strcmp($v,$latestversion)) {
        print "<div align=center><font color=green size=2>A new version of PHPlist is available!</font><br/>";
        print "<br/>The new version may have fixed security issues,<br/>so it is recommended to upgrade as soon as possible";
        print "<br/>Your version: <b>".$v."</b>";
        print "<br/>Latest version: <b>".$latestversion."</b><br/>  ";
        print '<a href="http://www.phplist.com/files/changelog">View what has changed</a>&nbsp;&nbsp;';
        print '<a href="http://www.phplist.com/files/phplist-'.$latestversion.'.tgz">Download</a></div>';
      }
    }
    Sql_Query(sprintf('replace into %s (item,value,editable) values("updatelastcheck",now(),0)',
      $tables["config"]));
  }
}

?>
<br/><br/>
<?
#$ls = new WebblerListing("System Functions");

$some = 0;
$ls = new WebblerListing("System Functions");;
if (checkAccess("initialise") && !$_GET["pi"]) {
  $some = 1;
  $ls->addElement("setup",PageURL2("setup"));
  $ls->addColumn("setup","&nbsp","Setup ".NAME);
}
if (checkAccess("upgrade") && !$_GET["pi"] && $upgrade_required) {
  $some = 1;
  $ls->addElement("upgrade",PageURL2("upgrade"));
  $ls->addColumn("upgrade","&nbsp","Upgrade the ".NAME." system");
}

if (checkAccess("eventlog")) {
  $some = 1;
  $ls->addElement("eventlog",PageURL2("eventlog"));
  $ls->addColumn("eventlog","&nbsp","View the eventlog");
}
if (checkAccess("admin") && $GLOBALS["require_login"] && !isSuperUser()) {
  $some = 1;
  $ls->addElement("admin",PageURL2("admin"));
  $ls->addColumn("admin","&nbsp","Change your details (e.g. password)");;
}
if ($some)
  print $ls->display();
  
$some = 0;
$ls = new WebblerListing("Configuration functions");
if (checkAccess("configure")) {
  $some = 1;
  $ls->addElement("configure",PageURL2("configure"));
  $ls->addColumn("configure","&nbsp;","Configure ".NAME);
}
if (checkAccess("attributes") && !$_GET["pi"]) {
  $some = 1;
  $ls->addElement("attributes",PageURL2("attributes"));
  $ls->addColumn("attributes","&nbsp;","Configure Attributes");
  if (Sql_table_exists($tables["attribute"])) {
    $res = Sql_Query("select * from ".$tables["attribute"],0);
    while ($row = Sql_Fetch_array($res)) {
      if ($row["type"] != "checkbox" && $row["type"] != "textarea" && $row["type"] != "textline" && $row["type"] != "hidden") {
        $ls->addElement($row["name"],PageURL2("editattributes&id=".$row["id"]));
        $ls->addColumn($row["name"],"&nbsp;","Control values for ".$row["name"]);
      }
    }
  }
}
if (checkAccess("spage")) {
  $some = 1;
  $ls->addElement("spage",PageURL2("spage"));
  $ls->addColumn("spage","&nbsp;","Configure Subscribe pages");
}

if ($some)
  print $ls->display();

$some = 0;
$ls = new WebblerListing("List and user functions");
if (checkAccess("list")) {
  $some = 1;
  $ls->addElement("list",PageURL2("list"));
  $ls->addColumn("list","&nbsp;","List the current lists");
}
if (checkAccess("users")) {
  $some = 1;
  $ls->addElement("users",PageURL2("users"));
  $ls->addColumn("users","&nbsp;","List all Users");
}
if (checkAccess("reconcileusers")) {
  $some = 1;
  $ls->addElement("reconcileusers",PageURL2("reconcileusers"));
  $ls->addColumn("reconcileusers","&nbsp;","Reconcile the user database");
}
if (checkAccess("import") && !$_GET["pi"]) {
  $some = 1;
  $ls->addElement("import",PageURL2("import"));
  $ls->addColumn("import","&nbsp;","Import Users");
}
if (checkAccess("export") && !$_GET["pi"]) {
  $some = 1;
  $ls->addElement("export",PageURL2("export"));
  $ls->addColumn("export","&nbsp;","Export Users");
}
if ($some)
  print $ls->display();

if ($GLOBALS["require_login"] && !$_GET["pi"]) {
  $some = 0;
  $ls = new WebblerListing("Administrator functions");
  if (checkAccess("admins")) {
    $some = 1;
    $ls->addElement("admins",PageURL2("admins"));
    $ls->addColumn("admins","&nbsp;","Add, edit and remove Administrators");
  }
  if (checkAccess("adminattributes")) {
    $some = 1;
    $ls->addElement("adminattributes",PageURL2("adminattributes"));
    $ls->addColumn("adminattributes","&nbsp;","Configure attributes for administrators");
  }
  if ($some)
    print $ls->display();
}

$some = 0;
$ls = new WebblerListing("Message functions");
if (checkAccess("send")) {
  $some = 1;
  $ls->addElement("send",PageURL2("send"));
  $ls->addColumn("send","&nbsp;","Send a message");
}
if (USE_PREPARE) {
  if (checkAccess("preparesend")) {
    $some = 1;
    $ls->addElement("preparesend",PageURL2("preparesend"));
    $ls->addColumn("preparesend","&nbsp;","Prepare a message");
  }
  if (checkAccess("sendprepared")) {
    $some = 1;
    $ls->addElement("sendprepared",PageURL2("sendprepared"));
    $ls->addColumn("sendprepared","&nbsp;","Send a prepared message");
  }
}
if (checkAccess("templates")) {
  $some = 1;
  $ls->addElement("templates",PageURL2("templates"));
  $ls->addColumn("templates","&nbsp;","Configure Templates");
}
if (checkAccess("messages")) {
  $some = 1;
  $ls->addElement("messages",PageURL2("messages"));
  $ls->addColumn("messages","&nbsp;","List all Messages");
}
if (checkAccess("processqueue") && MANUALLY_PROCESS_QUEUE) {
  $some = 1;
  $ls->addElement("processqueue",PageURL2("processqueue"));
  $ls->addColumn("processqueue","&nbsp;","Process the Message queue");
  if (TEST) {
   $ls->addColumn("processqueue","warning",'You have set TEST in config.php to 1, so it will only show what would be sent');
  }
}
if (checkAccess("processbounces") && MANUALLY_PROCESS_BOUNCES) {
  $some = 1;
  $ls->addElement("processbounces",PageURL2("processbounces"));
  $ls->addColumn("processbounces","&nbsp;","Process Bounces");
}
if (checkAccess("bounces")) {
  $some = 1;
  $ls->addElement("bounces",PageURL2("bounces"));
  $ls->addColumn("bounces","&nbsp;","View Bounces");
}
if ($some)
  print $ls->display();

$some = 0;
$ls = new WebblerListing("RSS Functions");
if (checkAccess("getrss") && MANUALLY_PROCESS_RSS) {
  $some = 1;
  $ls->addElement("getrss",PageURL2("getrss"));
  $ls->addColumn("getrss","&nbsp;","Get RSS feeds");
}
if (checkAccess("viewrss")) {
  $some = 1;
  $ls->addElement("viewrss",PageURL2("viewrss"));
  $ls->addColumn("viewrss","&nbsp;","View RSS items");
}

if ($some && ENABLE_RSS)
	print $ls->display();


$ls = new WebblerListing("Plugins");
if (sizeof($GLOBALS["plugins"])) {
  foreach ($GLOBALS["plugins"] as $pluginName => $plugin) {
    $menu = $plugin->adminmenu();
    if (is_array($menu)) {
      foreach ($menu as $page => $desc) {
        $ls->addElement($desc,PageUrl2("$page&pi=$pluginName"));
#        $ls->addColumn($page,"&nbsp;",$desc);
      }
    }
  }
}  
print $ls->display();

?>


