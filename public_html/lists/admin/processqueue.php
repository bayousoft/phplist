<?php
require_once "accesscheck.php";

#if (!MANUALLY_PROCESS_QUEUE) {
#	print "Error, process queue is set to be processed automatically. Loading of this page is not allowed");
#  return;
#}
?>
<script language="javascript" type="text/javascript">
onerror = null;
this.onerror = null;
</script>
<?php
if (!$GLOBALS["commandline"]) {
  ob_end_flush();
  if (!MANUALLY_PROCESS_QUEUE) {
    print "This page can only be called from the commandline";
    return;
  }
} else {
  ob_end_clean();
  print ClineSignature();
  ob_start();
}
# once and for all get rid of those questions why they do not receive any emails :-)
if (TEST)
	print '<font color=red size=5>Running in testmode, no emails will be sent. Check your config file.</font>';

$num_per_batch = 0;
$batch_period = 0;
$script_stage = 0; # start
if (ini_get("safe_mode")) {
	# keep an eye on timeouts
	$safemode = 1;
	$num_per_batch = 100;
#	Fatal_Error("Process queue will not work in safe mode");
#  return;
	print "Running in safe mode<br/>";
}

$maxbatch = -1;
$minbatchperiod = -1;
# check for batch limits
if ($fp = @fopen("/etc/phplist.conf","r")) {
  $contents = fread($fp, filesize("/etc/phplist.conf"));
  fclose($fp);
  $lines = explode("\n",$contents);
  foreach ($lines as $line) {
    list($key,$val) = explode("=",$line);
    switch ($key) {
      case "maxbatch": $maxbatch = sprintf('%d',$val);break;
      case "minbatchperiod": $minbatchperiod = sprintf('%d',$val);break;
      case "lockfile": $ISPlockfile = $val;
    }
  }
}

if (MAILQUEUE_BATCH_SIZE) {
  if ($maxbatch > 0) {
    $num_per_batch = min(MAILQUEUE_BATCH_SIZE,$maxbatch);
  } else {
    $num_per_batch = sprintf('%d',MAILQUEUE_BATCH_SIZE);
  }
} else {
  if ($maxbatch > 0) {
    $num_per_batch = $maxbatch;
  }
}

if (MAILQUEUE_BATCH_PERIOD) {
  if ($minbatchperiod > 0) {
    $batch_period = max(MAILQUEUE_BATCH_PERIOD,$minbatchperiod);
  } else {
    $batch_period = MAILQUEUE_BATCH_PERIOD;
  }
}
if ($num_per_batch && $batch_period) {
  # check how many were sent in the last batch period and take off that
  # amount from this batch
  $original_num_per_batch = $num_per_batch;
  $recently_sent = Sql_Fetch_Row_Query(sprintf('select count(*) from %s where date_add(entered,interval %d second) > now()',
    $tables["usermessage"],$batch_period));
  $num_per_batch -= $recently_sent[0];
  
  # if this ends up being 0 or less, don't send anything at all
  if ($num_per_batch == 0) {
    $num_per_batch = -1;
  }
}
  
print '<script language="Javascript" src="js/progressbar.js" type="text/javascript"></script>';
print formStart('name="outputform"').'<textarea name="output" rows=22 cols=75></textarea></form>';

# report keeps track of what is going on
$report = "";
$nothingtodo = 0;
$cached = array(); # cache the message from the database to avoid reloading it every time

require_once $GLOBALS["coderoot"] ."sendemaillib.php";
if (ENABLE_RSS) {
	require_once $GLOBALS["coderoot"] ."rsslib.php";
}

function my_shutdown () {
	global $script_stage,$reload;
#	output( "Script status: ".connection_status(),0); # with PHP 4.2.1 buggy. http://bugs.php.net/bug.php?id=17774
	output( "Script stage: ".$script_stage,0);
  global $report,$send_process_id,$tables,$nothingtodo,$invalid,$notsent,$sent,$unconfirmed,$num_per_batch,$batch_period;
  $some = $sent;# || $invalid || $notsent;
	if (!$some) {
		output("Finished, Nothing to do",0);
		$nothingtodo = 1;
	}

	output(sprintf('%d messages sent and %d messages skipped',$sent,$notsent),$sent);
	if ($invalid)
		output(sprintf('%d invalid emails',$invalid));
	if ($unconfirmed)
		output(sprintf('%d emails unconfirmed (not sent)',$unconfirmed));

  releaseLock($send_process_id);

  finish("info",$report);
	if ($script_stage < 5 && !$nothingtodo) {
		output ("Warning: script never reached stage 5\nThis may be caused by a too slow or too busy server \n");
	} elseif( $script_stage == 5 && !$nothingtodo)	{
		# if the script timed out in stage 5, reload the page to continue with the rest
    $reload++;
    if (!$GLOBALS["commandline"] && $num_per_batch && $batch_period) {
      if ($sent + 10 < $GLOBALS["original_num_per_batch"]) {
        output("Less than batch size were sent, so reloading imminently");
        $delaytime = 10000;
      } else {
        output("Waiting for $batch_period seconds before reloading");
        $delaytime = $batch_period * 1000;
      }
      output("Do not reload this page yourself, because that will cause processing to start from the beginning");
      printf( '<script language="Javascript" type="text/javascript">
        function reload() {
          var query = window.location.search;
          query = query.replace(/&reload=\d+/,"");
          query = query.replace(/&lastsent=\d+/,"");
          query = query.replace(/&lastskipped=\d+/,"");
          document.location = document.location.pathname + query + "&reload=%d&lastsent=%d&lastskipped=%d";
        }
        setTimeout("reload()",%d);
      </script>',$reload,$sent,$notsent,$delaytime);
    } else {
      printf( '<script language="Javascript" type="text/javascript">
        var query = window.location.search;
        query = query.replace(/&reload=\d+/,"");
        query = query.replace(/&lastsent=\d+/,"");
        query = query.replace(/&lastskipped=\d+/,"");
        document.location = document.location.pathname + query + "&reload=%d&lastsent=%d&lastskipped=%d";
      </script>',$reload,$sent,$notsent);
      output("Reload required");
	  }
  #	print '<script language="Javascript" type="text/javascript">alert(document.location)</script>';
	}	elseif ($script_stage == 6 || $nothingtodo)
		output("Finished, All done",0);
	else
		output("Script finished, but not all messages have been sent yet.");
}

register_shutdown_function("my_shutdown");

## some general functions
function finish ($flag,$message) {
	global $nothingtodo;
  if (!$GLOBALS["commandline"]) {
    print '<script language="Javascript" type="text/javascript"> finish(); </script>';
  }
  if ($flag == "error") {
    $subject = "Maillist Errors";
  } elseif ($flag == "info") {
    $subject = "Maillist Processing info";
  }
  output("Finished this run");
  if (!TEST && !$nothingtodo)
    sendReport($subject,$message);
}

function ProcessError ($message) {
  global $report;
  $report .= $message;
  output( "$message");
#  finish("error",$message);
  include "footer.inc";
  exit;
}

function output ($message,$logit = 1) {
  global $report;
  if ($GLOBALS["commandline"]) {
    ob_end_clean();
    print $message . "\n";
    ob_start();
  } else {
    $infostring = "[". date("D j M Y H:i",time()) . "] [" . $_SERVER["REMOTE_ADDR"] ."]";
    #print "$infostring $message<br>\n";
    print '<script language="Javascript" type="text/javascript">
      if (document.forms[0].name == "outputform") {
        document.outputform.output.value += "'.$message.'";
        document.outputform.output.value += "\n";
      } else
        document.writeln("'.$message.'");
    </script>'."\n";
  }
  
  $report .= "\n$infostring $message";
  if ($logit)
  	logEvent($message);
  flush();
}

function sendEmailTest ($messageid,$email) {
  global $report;
  if (VERBOSE)
    output("(test) Would have sent $messageid to $email");
  else
    $report .= "\n(test) Would have sent $messageid to $email";
}

# we don not want to timeout or abort
$abort = ignore_user_abort(1);
set_time_limit(600);
flush();

output("Started",0);
if (is_file($ISPlockfile)) {
  ProcessError("Processing has been suspended by your ISP, please try again later",1);
}
if ($num_per_batch > 0) {
  if ($original_num_per_batch != $num_per_batch) {
    output("Sending in batches of $original_num_per_batch emails",0);
    $diff = $original_num_per_batch - $num_per_batch;
    output("This batch will be $num_per_batch emails, because in the last $batch_period seconds $diff emails were sent",0);
  } else {
    output("Sending in batches of $num_per_batch emails",0);
  }
} elseif ($num_per_batch < 0) {
  output("In the last $batch_period seconds more emails were sent ($recently_sent[0]) than is currently allowed per batch ($original_num_per_batch).",0);
}
$rss_content_treshold = sprintf('%d',getConfig("rssthreshold"));
if ($reload) {
#	output("Reload count: $reload");
	output("Total processed: ".$reload * $num_per_batch);
  output("Sent in last run: $lastsent");
	output("Skipped in last run: $lastskipped");
}

# check for other processes running
$send_process_id = getPageLock();
$script_stage = 1; # we are active

$messages = Sql_query("select id,userselection,rsstemplate from ".$tables["message"]." where status != \"draft\" and status != \"sent\" and status != \"prepared\" and embargo < now() order by entered");
$num = Sql_affected_rows();
if (Sql_Has_Error($database_connection)) {  ProcessError(Sql_Error($database_connection)); }

if ($num) {
  output("Processing has started, $num message(s) to process.");
  if (!$safemode)
    output("It is safe to click your stop button now, report will be sent by email to ".getConfig("report_address"));
  else
    output("Your webserver is running in safe_mode. Please keep this window open. It may reload several times to make sure all messages are sent. Reports will be sent by email to ".getConfig("report_address"));
}

print '<script language="Javascript" type="text/javascript"> yposition = 10;document.write(progressmeter); start();</script>';
flush();

Sql_query("SET SQL_BIG_TABLES=1");
$script_stage = 2; # we know the messages to process

while ($message = Sql_fetch_array($messages)) {
  $messageid = $message["id"];
  $userselection = $message["userselection"];
  $rssmessage = $message["rsstemplate"];
  output("Processing message $messageid");
  if (ENABLE_RSS && $message["rsstemplate"]) {
    $processrss = 1;
    output("Message $messageid is an RSS feed for $rssmessage");
  } else {
    $processrss = 0;
  }

  flush();
  keepLock($send_process_id);
  $status = Sql_query('update '.$tables["message"].' set status = "inprocess",sendstart = now() where id = '.$messageid);
  output( "Looking for users");
  if (Sql_Has_Error($database_connection)) {  ProcessError(Sql_Error($database_connection)); }

  # make selection on attribute, users who at least apply to the attributes
  # lots of ppl seem to use it as a normal mailinglist system, and do not use attributes.
  # Check this and take anyone in that case.
  $numattr = Sql_Fetch_Row_Query("select count(*) from ".$tables["attribute"]);

  if ($userselection && $numattr[0]) {
    $res = Sql_query($userselection);
    $num = Sql_Affected_rows($res);
    output("$num users apply for attributes, now checking lists");
    $user_list = "";
    while ($row = Sql_Fetch_row($res)) {
      $user_list .= $row[0] . ",";
    }
    $user_list = substr($user_list,0,-1);
    if ($user_list)
      $user_attribute_query = " and ".$tables["listuser"].".userid in ($user_list)";
    else {
      output("No users apply for attributes");
      $status = Sql_query("update {$tables["message"]} set status = \"sent\",sent = now() where id = \"$messageid\"");
      finish("info","Message $messageid: \nNo users apply for attributes, ie nothing to do");
      $script_stage = 6;
      return;
    }
  }
	$script_stage = 3; # we know the users by attribute
  
  # when using commandline we need to exclude users who have already received
  # the email
  # we don't do this otherwise because it slows down the process, possibly
  # causing us to not find anything at all
  $exclusion = "";
  if ($GLOBALS["commandline"] && !$processrss) {
    $doneusers = array();
    $req = Sql_Query("select userid from {$tables["usermessage"]} where messageid = $messageid");
    while ($row = Sql_Fetch_Row($req)) {
      array_push($doneusers,$row[0]);
    }
    # also exclude unconfirmed users, otherwise they'll block the process
    $req = Sql_Query("select id from {$tables["user"]} where !confirmed");
    while ($row = Sql_Fetch_Row($req)) {
      array_push($doneusers,$row[0]);
    }
    if (sizeof($doneusers))
      $exclusion = " and {$tables['listuser']}.userid not in (".join(",",$doneusers).")";
  }
  
  $query = "select distinct {$tables['listuser']}.userid
  	from {$tables['listuser']},{$tables['listmessage']} ";
  $query .= "where {$tables['listmessage']}.messageid = $messageid and
		{$tables['listmessage']}.listid = {$tables['listuser']}.listid $exclusion ";

  $query .= " $user_attribute_query";

  $userids = Sql_query($query);
  if (Sql_Has_Error($database_connection)) {  ProcessError(Sql_Error($database_connection)); }

  # now we have all our users to send the message to
  $num = Sql_affected_rows();
  output( "Found them: $num to process");
  if ($num_per_batch) {
  	# send in batches of $num_per_batch users
    $batch_total = $num;
    if ($num_per_batch > 0) {
      $query .= sprintf(' limit %d,%d',$reload * $num_per_batch,$num_per_batch);
      $userids = Sql_query("$query");
      if (Sql_Has_Error($database_connection)) {  ProcessError(Sql_Error($database_connection)); }
    } else {
    	output("No users to process for this batch");
      $userids = Sql_Query(sprintf('select * from %s where id = 0',$tables["user"]));
    }
  }
  while ($userdata = Sql_fetch_row($userids)) {
		$userid = $userdata[0];    # id of the user
	  $some = 1;
    #set_time_limit(60);
    # check if we have been "killed"
    $alive = checkLock($send_process_id);
    if ($alive)
      keepLock($send_process_id);
    else
      ProcessError("Process Killed by other process");

    # check if the message we are working on is still there
    $res = Sql_query("select id from {$tables['message']} where id = $messageid");
    if (!Sql_Affected_Rows())
      ProcessError("Message I was working on has disappeared");
		flush();

		# check whether the user has already received the message
		$um = Sql_query("select entered from {$tables['usermessage']} where userid = $userdata[0] and messageid = $messageid");
		if (!Sql_Affected_Rows() || $processrss) {
			if ($script_stage < 4)
				$script_stage = 4; # we know a user
			$someusers = 1;
			$users = Sql_query("select id,email,uniqid,htmlemail,rssfrequency,confirmed from {$tables['user']} where id = $userdata[0]");

			# pick the first one
			$user = Sql_fetch_row($users);
			if ($user[5] && is_email($user[1])) {
				$userid = $user[0];    # id of the user
				$useremail = $user[1]; # email of the user
				$userhash = $user[2];  # unique string of the user
				$htmlpref = $user[3];  # preference for HTML emails
        $rssfrequency = $user[4];

        if (ENABLE_RSS && $processrss) {
        	if ($rssfrequency == $message["rsstemplate"]) {
          	# output("User matches message frequency");
	        	$rssitems = rssUserHasContent($userid,$messageid,$rssfrequency);
            $cansend = sizeof($rssitems) && (sizeof($rssitems) > $rss_content_treshold);
#            if (!$cansend)
#            	output("No content to send for this user ".sizeof($rssitems));
          } else {
          	$cansend = 0;
          }
        } else {
        	$cansend = 1;
        }

        if ($cansend) {
          $sent++;
          if (!TEST) {
            if (VERBOSE)
              output("Sending $messageid to $useremail");
            sendEmail($messageid,$useremail,$userhash,$htmlpref,$rssitems);
          } else {
            sendEmailTest($messageid,$useremail);
          }
          $status = Sql_query("update {$tables['message']} set processed = processed +1 where id = $messageid");
          $um = Sql_query("replace into {$tables['usermessage']} (userid,messageid) values($userid,$messageid)");
	        if (ENABLE_RSS && $processrss) {
          	foreach ($rssitems as $rssitemid) {
		          $status = Sql_query("update {$tables['rssitem']} set processed = processed +1 where id = $rssitemid");
    		      $um = Sql_query("replace into {$tables['rssitem_user']} (userid,itemid) values($userid,$rssitemid)");
            }
            Sql_Query("replace into {$tables["user_rss"]} (userid,last) values($userid,date_sub(now(),interval 15 minute))");
         	}
          $script_stage = 5; # we have actually sent one user
        }

				# update possible other users matching this email as well,
				# to avoid duplicate sending when people have subscribed multiple times
				# bit of legacy code after making email unique in the database
#				$emails = Sql_query("select * from {$tables['user']} where email =\"$useremail\"");
#				while ($email = Sql_fetch_row($emails))
#					Sql_query("replace into {$tables['usermessage']} (userid,messageid) values($email[0],$messageid)");
			}	else {
      	# some "invalid emails" are entirely empty, ah, that is because they are unconfirmed
        if (!$user[5]) {
          if (VERBOSE)
            output("Unconfirmed user: $user[1], $user[0]");
        	$unconfirmed++;
          # when running from commandline we mark it as sent, otherwise we might get
          # stuck when using batch processing
          if ($GLOBALS["commandline"]) {
            $um = Sql_query("replace into {$tables['usermessage']} (userid,messageid) values($userid,$messageid)");
          }
        } elseif ($user[1] || $user[0]) {
          if (VERBOSE)
            output("Invalid email: $user[1], $user[0]");
          logEvent("Invalid email, userid $user[0], email $user[1]");
          # mark it as sent anyway
          if ($userid)
            $um = Sql_query("replace into {$tables['usermessage']} (userid,messageid) values($userid,$messageid)");
          $invalid++;
        }
			}
		} else {
    	$um = Sql_Fetch_Row($um);
			$notsent++;
			if (VERBOSE)
				output( "Not sending to $userdata[0], already sent ".$um[0]);
		}
  }
  if (!$num || !$num_per_batch || $batch_total < ($reload * $num_per_batch)) {
    if (!$someusers)
      output( "Hmmm, No users found to send to");
    $someusers = 0;
    if (ENABLE_RSS && $rssmessage) {
	    $status = Sql_query("update {$tables['message']} set status = \"submitted\",sent = now() where id = \"$messageid\"");
    } else {
    	repeatMessage($messageid);
	    $status = Sql_query("update {$tables['message']} set status = \"sent\",sent = now() where id = \"$messageid\"");
    }
    $timetaken = Sql_Fetch_Row_query("select sent,sendstart from {$tables['message']} where id = \"$messageid\"");
    output("It took ".timeDiff($timetaken[0],$timetaken[1])." to send this message");
    sendMessageStats($messageid);
  } else {
  	$script_stage = 5;
  }
}

if (!$num_per_batch || $batch_total < $reload * $num_per_batch)
	$script_stage = 6; # we are done
#print "$safemode_total, ".$reload * $num_per_batch;
# shutdown will take care of reporting

?>
