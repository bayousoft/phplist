<?
require_once "accesscheck.php";

# library used for plugging into the webbler, instead of "connect"
# depricated and should be removed

#error_reporting(63);

# set some defaults if they are not specified
if (!defined("REGISTER")) define("REGISTER",1);
if (!defined("USE_PDF")) define("USE_PDF",0);
if (!defined("ENABLE_RSS")) define("ENABLE_RSS",0);
if (!defined("ALLOW_ATTACHMENTS")) define("ALLOW_ATTACHMENTS",0);
if (!defined("EMAILTEXTCREDITS")) define("EMAILTEXTCREDITS",0);
if (!defined("PAGETEXTCREDITS")) define("PAGETEXTCREDITS",0);
if (!defined("USEFCK")) define("USEFCK",0);
if (!defined("ASKFORPASSWORD")) define("ASKFORPASSWORD",0);
if (!defined("UNSUBSCRIBE_REQUIRES_PASSWORD")) define("UNSUBSCRIBE_REQUIRES_PASSWORD",0);
if (!defined("ENCRYPTPASSWORD")) define("ENCRYPTPASSWORD",0);
if (!defined("PHPMAILER")) define("PHPMAILER",0);
if (!defined("MANUALLY_PROCESS_QUEUE")) define("MANUALLY_PROCESS_QUEUE",1);
if (!defined("CHECK_SESSIONIP")) define("CHECK_SESSIONIP",1);
if (!defined("FILESYSTEM_ATTACHMENTS")) define("FILESYSTEM_ATTACHMENTS",0);
if (!defined("MIMETYPES_FILE")) define("MIMETYPES_FILE","/etc/mime.types");
if (!defined("DEFAULT_MIMETYPE")) define("DEFAULT_MIMETYPE","application/octet-stream");
if (!defined("USE_REPETITION")) define("USE_REPETITION",0);
if (!defined("USE_EDITMESSAGE")) define("USE_EDITMESSAGE",0);
if (!defined("FCKIMAGES_DIR")) define("FCKIMAGES_DIR","uploadimages");
if (!defined("USE_MANUAL_TEXT_PART")) define("USE_MANUAL_TEXT_PART",0);
if (!defined("ALLOW_NON_LIST_SUBSCRIBE")) define("ALLOW_NON_LIST_SUBSCRIBE",0);
if (!defined("MAILQUEUE_BATCH_SIZE")) define("MAILQUEUE_BATCH_SIZE",0);
if (!defined("MAILQUEUE_BATCH_PERIOD")) define("MAILQUEUE_BATCH_PERIOD",3600);
if (!defined("NAME")) define("NAME",'PHPlist');

# keep it default to old behaviour for now
if (!defined("USE_PREPARE")) define("USE_PREPARE",1);

if (!isset($GLOBALS["export_mimetype"])) $GLOBALS["export_mimetype"] = 'application/csv';

if (!defined("WORKAROUND_OUTLOOK_BUG") && defined("USE_CARRIAGE_RETURNS")) {
	define("WORKAROUND_OUTLOOK_BUG",USE_CARRIAGE_RETURNS);
}

$domain = getConfig("domain");
$website = getConfig("website");
if (defined("IN_WEBBLER") && is_object($GLOBALS["config"]["plugins"]["phplist"])) {
	$GLOBALS["tables"] = $GLOBALS["config"]["plugins"]["phplist"]->tables;
  $GLOBALS["table_prefix"] = $GLOBALS["config"]["plugins"]["phplist"]->table_prefix;
}

function listName($id) {
  global $tables;
  $req = Sql_Fetch_Row_Query(sprintf('select name from %s where id = %d',$tables["list"],$id));
  return $req[0] ? $req[0] : "Unnamed List";
}

function HTMLselect ($name, $table, $column, $value) {
  $res = "<!--$value--><select name=$name>\n";
  $result = Sql_Query("SELECT id,$column FROM $table");
  while($row = Sql_Fetch_Array($result)) {
    $res .= "<option value=".$row["id"] ;
    if ($row["$column"] == $value)
      $res .= " selected";
    if ($row["id"] == $value)
      $res .= " selected";
    $res .= ">" . $row[$column] . "\n";
  }
  $res .= "</select>\n";
  return $res;
}

function sendMail ($to,$subject,$message,$header = "",$parameters = "") {
	# global function to capture sending emails, to avoid trouble with
	# older (and newer!) php versions
	if (TEST)
		return 1;
  if (!$to)  {
		logEvent("Error: empty To: in message with subject $subject to send");
  	return 0;
  } elseif (!$subject) {
		logEvent("Error: empty Subject: in message to send to $to");
  	return 0;
  }    
	$v = phpversion();
	$v = preg_replace("/\-.*$/","",$v);
	if ($GLOBALS["message_envelope"]) {
  	$header = rtrim($header);
    if ($header)
      $header .= "\n";
		$header .= "Errors-To: ".$GLOBALS["message_envelope"];
		if (!$parameters || !ereg("-f".$GLOBALS["message_envelope"],$parameters)) {
			$parameters = '-f'.$GLOBALS["message_envelope"];
		}
	}

  if (WORKAROUND_OUTLOOK_BUG) {
  	$header = rtrim($header);
    if ($header)
      $header .= "\n";
 		$header .= "X-Outlookbug-fixed: Yes";
		$message = preg_replace("/\r?\n/", "\r\n", $message);
  }

  # version 4.2.3 (and presumably up) does not allow the fifth parameter in safe mode
  # make sure not to send out loads of test emails to ppl when developing
  if (!ereg("dev",VERSION)) {
    if ($v > "4.0.5" && !ini_get("safe_mode")) {
    	if (mail($to,$subject,$message,$header,$parameters))
      	return 1;
      else
      	return mail($to,$subject,$message,$header);
    }
    else
      return mail($to,$subject,$message,$header);
  } else {
  	# send mails to one place when running a test version
    $message = "To: $to\n".$message;
    if ($GLOBALS["developer_email"]) {
     	return mail($GLOBALS["developer_email"],$subject,$message,$header,$parameters);
    } else {
      print "Error: Running CVS version, but developer_email not set";
    }
  }
}

function sendAdminCopy($subject,$message) {
	$sendcopy = getConfig("send_admin_copies");
  if ($sendcopy == "true") {
  	$admin_mail = getConfig("admin_address");
    $mails = explode(",",getConfig("admin_addresses"));
    array_push($mails,$admin_mail);
    $sent = array();
    foreach ($mails as $admin_mail) {
    	if (!$sent[$admin_mail]) {
	  	  sendMail($admin_mail,$subject,$message,system_messageheaders($admin_mail));
        $sent[$admin_mail] = 1;
     	}
   	}
  }
}


function safeImageName($name) {
  $name = "image".ereg_replace("\.","DOT",$name);
  $name = ereg_replace("-","DASH",$name);
  $name = ereg_replace("_","US",$name);
  $name = ereg_replace("/","SLASH",$name);
  return $name;
}

function clean2 ($value) {
  $value = trim($value);
  $value = ereg_replace("\r","",$value);
  $value = ereg_replace("\n","",$value);
  $value = ereg_replace('"',"&quot;",$value);
  $value = ereg_replace("'","&rsquo;",$value);
  $value = ereg_replace("`","&lsquo;",$value);
  $value = stripslashes($value);
  return $value;
}

if (TEST && REGISTER)
  $pixel = '<img src="http://phplist.tincan.co.uk/images/pixel.gif" width=1 height=1>';

function timeDiff($time1,$time2) {
	if (!$time1 || !$time2) {
  	return "Unknown";
 	}
	$t1 = strtotime($time1);
  $t2 = strtotime($time2);

  if ($t1 < $t2) {
  	$diff = $t2 - $t1;
  } else {
  	$diff = $t1 - $t2;
  }
  if ($diff == 0)
  	return "very little time";
  $hours = (int)($diff / 3600);
  $mins = (int)(($diff - ($hours * 3600)) / 60);
  $secs = (int)($diff - $hours * 3600 - $mins * 60);

  if ($hours)
  	$res = $hours . " hours";
  if ($mins)
  	$res .= " ".$mins . " mins";
  if ($secs)
  	$res .= " ".$secs . " secs";
  return $res;
}

function previewTemplate($id,$adminid = 0,$text = "", $footer = "") {
  global $tables;
  $tmpl = Sql_Fetch_Row_Query(sprintf('select template from %s where id = %d',$tables["template"],$id));
  $template = stripslashes($tmpl[0]);
  $img_req = Sql_Query(sprintf('select id,filename from %s where template = %d order by filename desc',$tables["templateimage"],$id));
  while ($img = Sql_Fetch_Array($img_req)) {
    $template = preg_replace("#".preg_quote($img["filename"])."#","?page=image&id=".$img["id"],$template);
  }
  if ($adminid) {
    $att_req = Sql_Query("select name,value from {$tables["adminattribute"]},{$tables["admin_attribute"]} where {$tables["adminattribute"]}.id = {$tables["admin_attribute"]}.adminattributeid and {$tables["admin_attribute"]}.adminid = $adminid");
    while ($att = Sql_Fetch_Array($att_req)) {
      $template = preg_replace("#\[LISTOWNER.".strtoupper(preg_quote($att["name"]))."\]#",$att["value"],$template);
    }
  }
  if ($footer)
	  $template = eregi_replace("\[FOOTER\]",$footer,$template);
  $template = preg_replace("#\[CONTENT\]#",$text,$template);
  $template = eregi_replace("\[UNSUBSCRIBE\]",sprintf('<a href="%s">%s</a>',getConfig("unsubscribeurl"),$GLOBALS["strThisLink"]),$template);
  $template = eregi_replace("\[PREFERENCES\]",sprintf('<a href="%s">%s</a>',getConfig("preferencesurl"),$GLOBALS["strThisLink"]),$template);
  if (!EMAILTEXTCREDITS) {
	  $template = eregi_replace("\[SIGNATURE\]",$GLOBALS["PoweredByImage"],$template);
  } else {
	  $template = eregi_replace("\[SIGNATURE\]",$GLOBALS["PoweredByText"],$template);
  }
  $template = ereg_replace("\[[A-Z\. ]+\]","",$template);
  $template = ereg_replace('<form','< form',$template);
  $template = ereg_replace('</form','< /form',$template);

  return $template;
}


function parseMessage($content,$template,$adminid = 0) {
  global $tables;
  $tmpl = Sql_Fetch_Row_Query("select template from {$tables["template"]} where id = $template");
  $template = $tmpl[0];
  $template = preg_replace("#\[CONTENT\]#",$content,$template);
  $att_req = Sql_Query("select name,value from {$tables["adminattribute"]},{$tables["admin_attribute"]} where {$tables["adminattribute"]}.id = {$tables["admin_attribute"]}.adminattributeid and {$tables["admin_attribute"]}.adminid = $adminid");
  while ($att = Sql_Fetch_Array($att_req)) {
    $template = preg_replace("#\[LISTOWNER.".strtoupper(preg_quote($att["name"]))."\]#",$att["value"],$template);
  }
  return $template;
}

function listOwner($listid = 0) {
  global $tables;
  $req = Sql_Fetch_Row_Query("select owner from {$tables["list"]} where id = $listid");
  return $req[0];
}

function system_messageHeaders($useremail = "") {
  $from_address = getConfig("message_from_address");
  $from_name = getConfig("message_from_name");
  if ($from_name)
  	$additional_headers = "From: \"$from_name\" <$from_address>\n";
  else
	  $additional_headers = "From: $from_address\n";
  $message_replyto_address = getConfig("message_replyto_address");
  if ($message_replyto_address)
    $additional_headers .= "Reply-To: $message_replyto_address\n";
  else
    $additional_headers .= "Reply-To: $from_address\n";
  $v = VERSION;
  $v = ereg_replace("-dev","",$v);
  $additional_headers .= "X-Mailer: PHPlist version $v (www.phplist.com)\n";
	$additional_headers .= "X-MessageID: systemmessage\n";
	if ($useremail)
		$additional_headers .= "X-User: ".$useremail."\n";
  return $additional_headers;
}

function logEvent($msg) {
	global $tables;
  if (Sql_Table_Exists($tables["eventlog"]))
	Sql_Query(sprintf('insert into %s (entered,page,entry) values(now(),"%s","%s")',$tables["eventlog"],
  	$GLOBALS["page"],addslashes($msg)));
}

### process locking stuff
function getPageLock() {
	global $tables;
	$thispage = $GLOBALS["page"];
  $running_req = Sql_query("select now() - modified,id from ".$tables["sendprocess"]." where page = \"$thispage\" and alive order by started desc");
  $running_res = Sql_Fetch_row($running_req);
	$waited = 0;
  while ($running_res[1]) { # a process is already running
    output ("A process for this page is already running and it was still alive $running_res[0] seconds ago");
    output ("Sleeping for 20 seconds, aborting will now quit");
    $abort = ignore_user_abort(0);
    sleep(20);
		$waited++;
		if ($waited > 10) {
			# we have waited 10 cycles, abort and quit script
			output("We've been waiting too long, I guess the other script is still going ok");
			exit;
		}
    $running_req = Sql_query("select now() - modified,id from ".$tables["sendprocess"]." where page = \"$thispage\" and alive order by started desc");
    $running_res = Sql_Fetch_row($running_req);
    if ($running_res[0] > 1200) # some sql queries can take quite a while
      # process has been inactive for too long, kill it
      Sql_query("update {$tables["sendprocess"]} set alive = 0 where id = $running_res[1]");
  }
  $res = Sql_query('insert into '.$tables["sendprocess"].' (started,page,alive,ipaddress) values(now(),"'.$thispage.'",1,"'.getenv("REMOTE_ADDR").'")');
  $send_process_id = Sql_Insert_Id();
  $abort = ignore_user_abort(1);
  return $send_process_id;
}

function keepLock($processid) {
	global $tables;
	$thispage = $GLOBALS["page"];
  Sql_query("Update ".$tables["sendprocess"]." set alive = alive + 1 where id = $processid");
}

function checkLock($processid) {
	global $tables;
	$thispage = $GLOBALS["page"];
  $res = Sql_query("select alive from {$tables['sendprocess']} where id = $processid");
  $row = Sql_Fetch_Row($res);
  return $row[0];
}

function releaseLock($processid) {
	global $tables;
  if (!$processid) return;
  Sql_query("delete from {$tables["sendprocess"]} where id = $processid");
}

function adminName($id) {
	if (!$id) {
  	$id = $_SESSION["logindetails"]["id"];
  }
  global $tables;
  $req = Sql_Fetch_Row_Query(sprintf('select loginname from %s where id = %d',$tables["admin"],$id));
  return $req[0] ? $req[0] : "<font color=red>Nobody</font>";
}

if (!function_exists("dbg")) {
  function dbg($msg) {
  }
}


?>
