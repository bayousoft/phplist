<?
require_once "accesscheck.php";

print '<script language="Javascript" src="js/progressbar.js" type="text/javascript"></script>';

ignore_user_abort();
set_time_limit(500);
ob_end_flush();
?>
<p>

<?php

if(isset($import)) {

  $test_import = (isset($_POST["import_test"]) && $_POST["import_test"] == "yes");
 /*
  if (!is_array($_POST["lists"]) && !$test_import) {
    Fatal_Error("Please select at least one list");
    return;
  }
 */
  if(!$_FILES["import_file"]) {
    Fatal_Error("File is either too large or does not exist.");
    return;
  }
  if(empty($_FILES["import_file"])) {
    Fatal_Error("No file was specified. Maybe the file is too big? ");
    return;
  }
  if (filesize($_FILES["import_file"]['tmp_name']) > 1000000) {
  	Fatal_Error("File too big, please split it up into smaller ones");
    return;
  }
  if( !preg_match("/^[0-9A-Za-z_\.\-\/\s \(\)]+$/", $_FILES["import_file"]["name"]) ) {
    Fatal_Error("Use of wrong characters: ".$_FILES["import_file"]["name"]);
    return;
  }
  if (!$_POST["notify"] && !$test_import) {
    Fatal_Error("Please choose whether to sign up immediately or to send a notification");
    return;
  }

  if ($_FILES["import_file"]) {
    $fp = fopen ($_FILES["import_file"]['tmp_name'], "r");
    $email_list = fread($fp, filesize ($_FILES["import_file"]['tmp_name']));
    fclose($fp);
    unlink($_FILES["import_file"]['tmp_name']);
  }

  // Clean up email file
  $email_list = trim($email_list);
  $email_list = str_replace("\r","\n",$email_list);
  $email_list = str_replace("\n\r","\n",$email_list);
  $email_list = str_replace("\n\n","\n",$email_list);

  // Change delimiter for new line.
  if(isset($import_record_delimiter) && $import_record_delimiter != "") {
    $email_list = str_replace($import_record_delimiter,"\n",$email_list);
  };

  if (!isset($import_field_delimiter) || $import_field_delimiter == "" || $import_field_delimiter == "TAB")
    $import_field_delimiter = "\t";

  // Check file for illegal characters
  $illegal_cha = array(",", ";", ":", "#","\t");
  for($i=0; $i<count($illegal_cha); $i++) {
    if( ($illegal_cha[$i] != $import_field_delimiter) && ($illegal_cha[$i] != $import_record_delimiter) && (strpos($email_list, $illegal_cha[$i]) != false) ) {
      Fatal_Error("Some characters that are not valid have been found. These might be delimiters. Please check the file and select the right delimiter. $import_field_delimiter, $import_record_delimiter");return;
    }
  };

  // Split file/emails into array
  $email_list = explode("\n",$email_list);

  // Parse the lines into records
  $hasinfo = 0;
  foreach ($email_list as $line) {
    $uservalues = explode($import_field_delimiter,$line);
    $email = array_shift($uservalues);
    $info = join(" ",$uservalues);
    $hasinfo = $hasinfo || $info != "";
    $user_list[$email] = array (
      "info" => $info
    );
  }

  if (sizeof($email_list) > 300 && !$test_import) {
    # this is a possibly a time consuming process, so lets show a progress bar
    print '<script language="Javascript" type="text/javascript"> document.write(progressmeter); start();</script>';
    flush();
    # increase the memory to make sure we are not running out
    ini_set("memory_limit","16M");
  }

  // View test output of emails
  if($test_import) {
    print 'Test output:<br>There should only be ONE email per line.<br>If the output looks ok, go <a href="javascript:history.go(-1)">Back</a> to resubmit for real<br><br>';
    $i = 1;
    while (list($email,$data) = each ($user_list)) {
      $email = trim($email);
      if(strlen($email) > 4) {
        print "<b>$email</b><br>";
        $html = "";
        foreach (array("info") as $item)
          if ($user_list[$email][$item])
            $html .= "$item -> ".$user_list[$email][$item]."<br>";
        if ($html) print "<blockquote>$html</blockquote>";
      };
      if($i == 50) {break;};
      $i++;
    };

  // Do import
  } else {
    $count_email_add = 0;
    $count_list_add = 0;
    $num_lists = sizeof($lists);
    if ($hasinfo) {
      # we need to add an info attribute if it does not exist
      $req = Sql_Query("select id from ".$tables["attribute"]." where name = \"info\"");
      if (!Sql_Affected_Rows()) {
        # it did not exist
        Sql_Query(sprintf('insert into %s (name,type,listorder,default_value,required,tablename)
         values("info","textline",0,"",0,"info")', $tables["attribute"]));
      }
    }

    # which attributes were chosen, apply to all users
    $res = Sql_Query("select * from ".$tables["attribute"]);
    $attributes = array();
    while ($row = Sql_Fetch_Array($res)) {
      if ($row["tablename"] != "")
        $fieldname = $row["tablename"];
      else
        $fieldname = "attribute" .$row["id"];
      $attributes[$row["id"]] = $_POST[$fieldname];
    }

    while (list($email,$data) = each ($user_list)) {
      if(strlen($email) > 4) {
        // Annoying hack => Much to time consuming. Solution => Set email in users to UNIQUE()
        $result = Sql_query("SELECT id,uniqid FROM ".$tables["user"]." WHERE email = '$email'");
        if (Sql_affected_rows()) {
          // Email exist, remember some values to add them to the lists
          $user = Sql_fetch_array($result);
          $userid = $user["id"];
          $uniqid = $user["uniqid"];
        } else {

          // Email does not exist

          // Create unique number
          mt_srand((double)microtime()*1000000);
          $randval = mt_rand();
          include_once "userlib.php";
          $uniqid = getUniqid();

          $query = sprintf('INSERT INTO %s (email,entered,confirmed,uniqid,htmlemail) values("%s",now(),%d,"%s","%s")',
          	$tables["user"],$email,$notify != "yes",$uniqid,$htmlemail);
          $result = Sql_query($query);
          $userid = Sql_insert_id();

	        $count_email_add++;
          $some = 1;

          # add the attributes for this user
          reset($attributes);
          while (list($attr,$value) = each($attributes))
            Sql_query(sprintf('replace into %s (attributeid,userid,value) values("%s","%s","%s")',
              $tables["user_attribute"],$attr,$userid,$value));
        }

        #add this user to the lists identified
        reset($lists);
        $addition = 0;
        $listoflists = "";
        while (list($key,$listid) = each($lists)) {
          $query = "replace INTO ".$tables["listuser"]." (userid,listid,entered) values($userid,$listid,now())";
          $result = Sql_query($query);
          # if the affected rows is 2, the user was already subscribed
          $addition = $addition || Sql_Affected_Rows() == 1;
          $listoflists .= "  * ".$listname[$key]."\n";
        }
        if ($addition)
          $additional_emails++;
        $subscribemessage = ereg_replace('\[LISTS\]', $listoflists, getUserConfig("subscribemessage",$userid));
        if (!TEST && $notify == "yes" && $addition)
          sendMail($email, getConfig("subscribesubject"), $subscribemessage,system_messageheaders(),$envelope);

      }; // end if
    }; // end while

    print '<script language="Javascript" type="text/javascript"> finish(); </script>';
    # lets be gramatically correct :-)
    $displists = ($num_lists == 1) ? "list": "lists";
    $dispemail = ($count_email_add == 1) ? "new email was ": "new emails were ";
    $dispemail2 = ($additional_emails == 1) ? "email was ":"emails were ";

    if(!$some && !$additional_emails) {
      print "<br>All the emails already exist in the database.";
    } else {
      print "$count_email_add $dispemail succesfully imported to the database and added to $num_lists $displists.<br>$additional_emails $dispemail2 subscribed to the $displists";
    }
  }; // end else
  print '<p>'.PageLink2("import","Import some more emails").'</p>';


} else {
?>


<ul>
<?=FormStart(' enctype="multipart/form-data" name="import"')?>
<?php
if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("import1");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$logindetails["id"];break;
    case "all":
      $subselect = "";break;
    case "none":
    default:
      $subselect = " where id = 0";break;
  }
}

$result = Sql_query("SELECT id,name FROM ".$tables["list"]."$subselect ORDER BY listorder");
$c=0;
if (Sql_Affected_Rows() == 1) {
	$row = Sql_fetch_array($result);
	printf('<input type=hidden name="listname[%d]" value="%s"><input type=hidden name="lists[%d]" value="%d">Adding users to list <b>%s</b>',$c,$row["name"],$c,$row["id"],$row["name"]);;
} else {
	print '<p>Select the lists to add the emails to</p>';
	while ($row = Sql_fetch_array($result)) {
		printf('<li><input type=hidden name="listname[%d]" value="%s"><input type=checkbox name="lists[%d]" value="%d">%s',$c,$row["name"],$c,$row["id"],$row["name"]);;
		$some = 1;$c++;
	}

	if (!$some)
		echo 'No lists available, '.PageLink2("editlist","Add a list");
}

?>

</ul>

<table border="1">
<tr><td colspan=2>The file you upload will need to contain the emails you want to add to these lists. Anything after the email will be added as attribute "Info" of the user. You can specify the rest of the attributes of these users below. <b>Warning</b>: the file needs to be plain text. Do not upload binary files like a Word Document.</td></tr>
<tr><td>File containing emails:</td><td><input type="file" name="import_file"></td></tr>
<tr><td>Field Delimiter:</td><td><input type="text" name="import_field_delimiter" size=5> (default is TAB)</td></tr>
<tr><td>Record Delimiter:</td><td><input type="text" name="import_record_delimiter" size=5> (default is line break)</td></tr>
<tr><td colspan=2>If you check "Test Output", you will get the list of parsed emails on screen, and the database will not be filled with the information. This is useful to find out whether the format of your file is correct. It will only show the first 50 records.</td></tr>
<tr><td>Test output:</td><td><input type="checkbox" name="import_test" value="yes"></td></tr>
<tr><td colspan=2>If you choose "send notification email" the users you are adding will be sent the request for confirmation of subscription to which they will have to reply. This is recommended, because it will identify invalid emails.</td></tr>
<tr><td>Send&nbsp;Notification&nbsp;email&nbsp;<input type="radio" name="notify" value="yes"></td><td>Make confirmed immediately&nbsp;<input type="radio" name="notify" value="no"></td></tr>
<?
include_once $GLOBALS["coderoot"] ."subscribelib2.php";
print ListAllAttributes();
?>

<tr><td><input type="submit" name="import" value="Import"></td><td>&nbsp;</td></tr>
</table>
<? } ?>

</p>

