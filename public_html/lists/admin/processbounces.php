<?
require_once "accesscheck.php";

if (!$GLOBALS["commandline"]) {
  ob_end_flush();
  if (!MANUALLY_PROCESS_BOUNCES) {
    print "This page can only be called from the commandline";
    return;
  }
} else {
  ob_end_clean();
  print ClineSignature();
  ob_start();
}
print '<script language="Javascript" src="js/progressbar.js" type="text/javascript"></script>';
flush();
$outputdone =0;
function prepareOutput() {
	global $outputdone;
	if (!$outputdone) {
		$outputdone = 1;
		return formStart('name="outputform"').'<textarea name="output" rows=20 cols=50></textarea></form>';
	}
}

$report = "";
## some general functions
function finish ($flag,$message) {
  if ($flag == "error") {
    $subject = "Bounce processing error";
  } elseif ($flag == "info") {
    $subject = "Bounce Processing info";
  }
  if (!TEST && $message)
    sendReport($subject,$message);
}

function ProcessError ($message) {
  output( "$message");
  finish("error",$message);
  exit;
}

function my_shutdown() {
	global $report,$process_id;
  releaseLock($process_id);
 # $report .= "Connection status:".connection_status();
  finish("info",$report);
}

function output ($message,$reset = 0) {
  $infostring = "[". date("D j M Y H:i",time()) . "] [" . getenv("REMOTE_HOST") ."] [" . getenv("REMOTE_ADDR") ."]";
  #print "$infostring $message<br>\n";
  if ($GLOBALS["commandline"]) {
    ob_end_clean();
    print strip_tags($message) . "\n";
    ob_start();
  } else {
    if ($reset)
      print '<script language="Javascript" type="text/javascript">
        if (document.forms[0].name == "outputform") {
          document.outputform.output.value = "";
          document.outputform.output.value += "\n";
        }
      </script>'."\n";
    print '<script language="Javascript" type="text/javascript">
      if (document.forms[0].name == "outputform") {
        document.outputform.output.value += "'.$message.'";
        document.outputform.output.value += "\n";
      } else
        document.writeln("'.$message.'");
    </script>'."\n";
  }

  flush();
}

function processBounce ($link,$num,$header) {
	global $tables;
  $headerinfo = imap_headerinfo($link,$num);

  $body= imap_body ($link,$num);
  $msgid = 0;$user = 0;
  preg_match ("/X-MessageId: (.*)/i",$body,$match);
  if (is_array($match) && isset($match[1]))
	  $msgid= trim($match[1]);
  if (!$msgid) {
  	# older versions use X-Message
    preg_match ("/X-Message: (.*)/i",$body,$match);
	  if (is_array($match) && isset($match[1]))
  	  $msgid= trim($match[1]);
  }

  preg_match ("/X-ListMember: (.*)/i",$body,$match);
  if (is_array($match) && isset($match[1]))
	  $user = trim($match[1]);
  if (!$user) {
  	# older version use X-User
    preg_match ("/X-User: (.*)/i",$body,$match);
    if (is_array($match) && isset($match[1]))
	    $user = trim($match[1]);
  }

  # some versions used the email to identify the users, some the userid and others the uniqid
  # use backward compatible way to find user
  if (preg_match ("/.*@.*/i",$user,$match)) {
    $userid_req = Sql_Fetch_Row_Query("select id from {$tables["user"]} where email = \"$user\"");
    if (VERBOSE)
	    output("UID".$userid_req[0]." MSGID".$msgid);
    $userid = $userid_req[0];
  } elseif (preg_match("/^\d$/",$user)) {
		$userid = $user;
    if (VERBOSE)
	    output( "UID".$userid." MSGID".$msgid);
  } elseif ($user) {
    $userid_req = Sql_Fetch_Row_Query("select id from {$tables["user"]} where uniqid = \"$user\"");
    if (VERBOSE)
	    output( "UID".$userid_req[0]." MSGID".$msgid);
    $userid = $userid_req[0];
  } else {
  	$userid = '';
  }
  Sql_Query(sprintf('insert into %s (date,header,data)
  	values("%s","%s","%s")',
  	$tables["bounce"],
		date("Y-m-d H:i",strtotime($headerinfo->Date)),
    addslashes($header),
    addslashes($body)));

  $bounceid = Sql_Insert_id();
	if ($msgid == "systemmessage" && $userid) {
  	Sql_Query(sprintf('update %s
    	set status = "bounced system message",
      comment = "%s marked unconfirmed"
      where id = %d',
      $tables["bounce"],
      $userid,$bounceid));
   	logEvent("$userid system message bounced, user marked unconfirmed");
  	Sql_Query(sprintf('update %s
    	set confirmed = 0
      where id = %d',
      $tables["user"],
	    $userid));
  } elseif ($msgid && $userid) {
  	Sql_Query(sprintf('update %s
    	set status = "bounced list message %d",
      comment = "%s bouncecount increased"
      where id = %d',
      $tables["bounce"],
      $msgid,
      $userid,$bounceid));
  	Sql_Query(sprintf('update %s
    	set bouncecount = bouncecount + 1
      where id = %d',
      $tables["message"],
	    $msgid));
  	Sql_Query(sprintf('update %s
    	set bouncecount = bouncecount + 1
      where id = %d',
      $tables["user"],
	    $userid));
  	Sql_Query(sprintf('insert into %s
    	set user = %d, message = %d, bounce = %d',
      $tables["user_message_bounce"],
      $userid,$msgid,$bounceid));
  } elseif ($userid) {
  	Sql_Query(sprintf('update %s
    	set status = "bounced unidentified message",
      comment = "%s bouncecount increased"
      where id = %d',
      $tables["bounce"],
      $userid,$bounceid));
  	Sql_Query(sprintf('update %s
    	set bouncecount = bouncecount + 1
      where id = %d',
      $tables["user"],
	    $userid));
  } else {
  	Sql_Query(sprintf('update %s
    	set status = "unidentified bounce",
      comment = "not processed"
      where id = %d',
      $tables["bounce"],
			$bounceid));
   	return false;
	}
  return true;
}

function processPop ($server,$user,$password) {
  $port =  "110/pop3/notls";
  set_time_limit(6000);

  if (!TEST) {
	  $link=imap_open("{".$server.":".$port."}INBOX",$user,$password,CL_EXPUNGE);
  } else {
	  $link=imap_open("{".$server.":".$port."}INBOX",$user,$password);
	}

  if (!$link) {
  	Error("Cannot create POP3 connection to $server: ".imap_last_error());
    return;
  }
	return processMessages($link,100000);
}

function processMbox ($file) {
  set_time_limit(6000);

  if (!TEST) {
	  $link=imap_open($file,"","",CL_EXPUNGE);
  } else {
	  $link=imap_open($file,"","");
  }
  if (!$link) {
  	Error("Cannot open mailbox file ".imap_last_error());
    return;
  }
	return processMessages($link,100000);
}

function processMessages($link,$max = 3000) {
	#error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	global $bounce_mailbox_purge_unprocessed,$bounce_mailbox_purge;
  $num = imap_num_msg($link);
	print $num . " bounces to fetch from the mailbox<br/>\n";
  print "Please do not interrupt this process<br/>";
  $report = $num . " bounces to process\n";
  if ($num > $max) {
  	print "Processing first $max bounces<br/>";
	  $report .= $num . " processing first $max bounces\n";
    $num = $max;
  }
  if (TEST) {
    print "Running in test mode, not deleting messages from mailbox<br/>";
  } else {
    print "Processed messages will be deleted from mailbox<br/>";
  }
	print prepareOutput();
	print '<script language="Javascript" type="text/javascript"> yposition = 10;document.write(progressmeter); start();</script>';
  flush();
	$nberror = 0;
#	for ($x=1;$x<150;$x++) {
  for($x=1; $x <= $num; $x++) {
	  set_time_limit(60);
  	$header = imap_fetchheader($link,$x);
    if ($x % 25 == 0)
	#    output( $x . " ". nl2br($header));
  		output($x . " done",1);
    print "\n";
    flush();
    $processed = processBounce($link,$x,$header);
    if ($processed) {
			if (!TEST && $bounce_mailbox_purge) {
        if (VERBOSE)
		      output( "Deleting message $x");
	      imap_delete($link,$x);
   		}
    } else {
			if (!TEST && $bounce_mailbox_purge_unprocessed) {
        if (VERBOSE)
	        output( "Deleting message $x");
	      imap_delete($link,$x);
   		}
		}
    flush();
  }
	flush();
  output("Closing mailbox, and purging messages");
  set_time_limit(60 * $num);
	imap_close($link);
	print '<script language="Javascript" type="text/javascript"> finish(); </script>';
  if ($num)
  	return $report;
}

if (!function_exists("imap_open")) {
	Error('IMAP is not included in your PHP installation, cannot continue<br/>Check out <a href="http://www.php.net/manual/en/ref.imap.php">http://www.php.net/manual/en/ref.imap.php</a>');
	return;
}

if (!$bounce_mailbox && (!$bounce_mailbox_host || !$bounce_mailbox_user || !$bounce_mailbox_password)) {
	Error("Bounce mechanism not properly configured");
	return;
}

# lets not do this unless we do some locking first
$abort = ignore_user_abort(1);
register_shutdown_function("my_shutdown");
$process_id = getPageLock();

switch ($bounce_protocol) {
	case "pop":
    $download_report =	processPop ($bounce_mailbox_host,$bounce_mailbox_user,$bounce_mailbox_password);
		break;
	case "mbox":
		$download_report = processMbox($bounce_mailbox);
		break;
	default:
		Error("bounce_protocol not supported");
		return;
}

# now we have filled database with all available bounces
# have a look who should be flagged as unconfirmed
print '<script language="Javascript" type="text/javascript"> yposition = 10;document.write(progressmeter); start();</script>';
print prepareOutput();

output("Identifying consecutive bounces");

# we only need users who are confirmed at the moment
$userid_req = Sql_Query(sprintf('select distinct %s.user from %s,%s
	where %s.id = %s.user and %s.confirmed',
  $tables["user_message_bounce"],
	$tables["user_message_bounce"],
	$tables["user"],
	$tables["user"],
	$tables["user_message_bounce"],
	$tables["user"]
));
$total = Sql_Affected_Rows();
if (!$total)
	output("Nothing to do");

$usercnt = 0;
$unsubscribed_users = "";
while ($user = Sql_Fetch_Row($userid_req)) {
	# give messages five days to bounce, most MTAs keep them in the queue for 4 days
	# before giving up.
  keepLock($process_id);
  set_time_limit(600);
	$msg_req = Sql_Query(sprintf('select * from
		%s left join %s on %s.messageid = %s.message
		where (date_add(entered,INTERVAL 5 day) < now())
		and userid = %d
		and (userid = user or user is null) order by entered desc',
		$tables["usermessage"],$tables["user_message_bounce"],
		$tables["usermessage"],$tables["user_message_bounce"],
		$user[0]));
	$cnt = 0;
  $alive = 1;$removed = 0;
	while ($alive && !$removed && $bounce = Sql_Fetch_Array($msg_req)) {
    $alive = checkLock($process_id);
    if ($alive)
      keepLock($process_id);
    else
      ProcessError("Process Killed by other process");
		if (sprintf('%d',$bounce["bounce"]) == $bounce["bounce"]) {
			$cnt++;
      if ($cnt >= $bounce_unsubscribe_treshold) {
        $removed = 1;
        output(sprintf('unsubscribing %d -> %d bounces',$user[0],$cnt));
        $userurl = PageLink2("user&id=$user[0]",$user[0]);
        logEvent("User $userurl has consecutive bounces ($cnt) over treshold, user marked unconfirmed");
        $emailreq = Sql_Fetch_Row_Query("select email from {$tables["user"]} where id = $user[0]");
        addUserHistory($emailreq[0],"Auto Unsubscribed","User auto unsubscribed for $cnt consecutive bounces");
        Sql_Query(sprintf('update %s set confirmed = 0 where id = %d',$tables["user"],$user[0]));
        $email_req = Sql_Fetch_Row_Query(sprintf('select email from %s where id = %d',$tables["user"],$user[0]));
        $unsubscribed_users .= $email_req[0] . " [$user[0]] ($cnt)\n";
      }
		} elseif ($bounce["bounce"] == "") {
			$cnt = 0;
		}
	}
	if ($usercnt % 10 == 0) {
		output("Identifying consecutive bounces");
    output("$usercnt of $total users processed",1);
  }
	$usercnt++;
	flush();
}
output("Identifying consecutive bounces");
output("$total users processed",1);

if ($download_report) {
	$report = "Report:\n$download_report\n";
}

if ($unsubscribed_users) {
	$report .= "\nBelow are users who have been marked unconfirmed. The number in [] is their userid, the number in () is the number of consecutive bounces\n";
  $report .= "\n$unsubscribed_users";
}
print '<script language="Javascript" type="text/javascript"> finish(); </script>';
# shutdown will take care of reporting
#finish("info",$report);

?>


