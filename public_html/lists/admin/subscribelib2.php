<?

mt_srand((double)microtime()*1000000);
$randval = mt_rand();

if (!$id) {
	$id = sprintf('%d',$_GET["id"]);
}

if (!$id && $_GET["page"] != "import1") {
	Fatal_Error("Invalid call");
  exit;
}

$req = Sql_Query(sprintf('select * from %s where id = %d',$GLOBALS["tables"]["subscribepage_data"],$id));
while ($row = Sql_Fetch_Array($req)) {
  $subscribepagedata[$row["name"]] = $row["data"];
}
$attributes = explode('+',$subscribepagedata["attributes"]);
$required = array();
if (is_array($attributes)) {
  foreach ($attributes as $attribute) {
    if ($subscribepagedata[sprintf('attribute%03d',$attribute)]) {
      list($dummy,$dummy2,$dummy3,$req) = explode('###',$subscribepagedata[sprintf('attribute%03d',$attribute)]);
      if ($req) {
        array_push($required,$attribute);
      }
    }
  }
  $reqs = join(',',$required);
  # check if all required attributes have been entered;
  if ($reqs) {
    $res = Sql_Query("select * from {$GLOBALS["tables"]["attribute"]} where id in ($reqs)");
    $allthere = 1;
    while ($row = Sql_Fetch_Array($res)) {
      $fieldname = "attribute" .$row["id"];
      if ($row["type"] != "hidden") {
        $allthere = $allthere && $_POST[$fieldname];
        if (!$allthere && !$_POST[$fieldname])
          $missing .= $row["name"] .", ";
      }
    }
    $missing = substr($missing,0,-2);
  } else {
  	$allthere = 1;
  }
} else {
  $allthere = 1;
}

if ($allthere && ASKFORPASSWORD && ($_POST["passwordreq"] || $_POST["password"])) {
	if (!$_POST["password"] || $_POST["password"] != $_POST["password_check"]) {
  	$allthere = 0;
    $missing = $GLOBALS["strPasswordsNoMatch"];
  }
  if ($_POST["email"]) {
    $curpwd = Sql_Fetch_Row_Query(sprintf('select password from %s where email = "%s"',
    	$GLOBALS["tables"]["user"],$_POST["email"]));

    if ($curpwd[0] && $_POST["password"] != $curpwd[0]) {
    	$missing = $GLOBALS["strInvalidPassword"];
    }
	}
}

if (isset($_POST["email"]) && $check_for_host) {
  list($username,$domaincheck) = split('@',$_POST["email"]);
  $mxhosts = array();
  $validhost = getmxrr ($domaincheck,$mxhosts);
} else {
  $validhost = 1;
}

$listsok = ((!ALLOW_NON_LIST_SUBSCRIBE && is_array($_POST["list"])) || ALLOW_NON_LIST_SUBSCRIBE);

if (isset($_POST["subscribe"]) && is_email($_POST["email"]) && $listsok
   && $allthere && $validhost) {
  # make sure to save the correct data
  if ($subscribepagedata["htmlchoice"] == "checkfortext" && !$textemail) {
  	$htmlemail = 1;
  } else {
    $htmlemail = $_POST["htmlemail"];
  }

  # now check whether this user already exists.
  $email = $_POST["email"];
  $result = Sql_query("select * from {$GLOBALS["tables"]["user"]} where email = \"$email\"");#"
  if (!Sql_affected_rows()) {
    # they do not exist, so add them
    $query = sprintf('insert into %s (email,entered,uniqid,confirmed,
    	htmlemail,subscribepage,rssfrequency) values("%s",now(),"%s",0,%d,%d,"%s")',
		$GLOBALS["tables"]["user"],addslashes($email),getUniqid(),$htmlemail,$id,
    $_POST["rssfrequency"]);
	  $result = Sql_query($query);
  } else {
    # they do exist, so update the existing record
	  # read the current values to compare changes
    $old_data = Sql_fetch_array($result);
    if (ASKFORPASSWORD && $old_data["password"]) {
      if (ENCRYPTPASSWORD) {
        $canlogin = md5($_POST["password"]) == $old_data["password"];
      } else {
        $canlogin = $_POST["password"] == $old_data["password"];
      }
      if (!$canlogin) {
        $msg = $GLOBALS["strUserExists"];
        $msg .= '<p>'.$GLOBALS["strUserExistsExplanationStart"].
          sprintf('<a href="%s&email=%s">%s</a>',getConfig("preferencesurl"),$email,
          $GLOBALS["strUserExistsExplanationLink"]).
          $GLOBALS["strUserExistsExplanationEnd"];
        return;
      }
    }


    $userid = $old_data["id"];
	  $old_data = array_merge($old_data,getUserAttributeValues('',$userid));
  	$history_entry = 'http://'.getConfig("website").$GLOBALS["adminpages"].'/?page=user&id='.$userid."\n\n";
    $query = sprintf('update %s set email = "%s",htmlemail = %d,subscribepage = %d,rssfrequency = "%s" where id = %d',$GLOBALS["tables"]["user"],addslashes($email),$htmlemail,$id,$_POST["rssfrequency"],$userid);
	  $result = Sql_query($query);
  }

  # if we do not know the userid, retrieve it
  if (!$userid)
    $userid = Sql_insert_id();

  if (ASKFORPASSWORD && $_POST["password"]) {
  	if (ENCRYPTPASSWORD) {
    	$newpassword = sprintf('%s',md5($_POST["password"]));
   	} else {
    	$newpassword = sprintf('%s',$_POST["password"]);
    }
   	# see whether is has changed
    $curpwd = Sql_Fetch_Row_Query("select password from {$GLOBALS["tables"]["user"]} where id = $userid");
    if ($_POST["password"] != $curpwd[0]) {
    	$storepassword = 'password = "'.$newpassword.'"';
      Sql_query("update {$GLOBALS["tables"]["user"]} set passwordchanged = now(),$storepassword where id = $userid");
    } else {
    	$storepassword = "";
    }
  } else {
  	$storepassword = "";
  }

  # subscribe to the lists
  if (is_array($_POST["list"])) {
    while(list($key,$val)= each($_POST["list"])) {
      if ($val == "signup") {
        $result = Sql_query("replace into {$GLOBALS["tables"]["listuser"]} (userid,listid,entered) values($userid,$key,now())");
        $lists .= "\n  * ".listname($key);
      }
    }
  }

  # remember the users attributes
  # make sure to only remember the ones from this subscribe page
  $history_entry .= 'Subscribe page: '.$id;
  $attids = join(',',$attributes);
  $attids = substr($attids,0,-1);
  if ($attids && $attids != "") {
    $res = Sql_Query("select * from ".$GLOBALS["tables"]["attribute"]." where id in ($attids)");
    while ($row = Sql_Fetch_Array($res)) {
      $fieldname = "attribute" .$row["id"];
      $value = $_POST[$fieldname];
  #    if ($value != "") {
        if (is_array($value)) {
          $newval = array();
          foreach ($value as $val) {
            array_push($newval,sprintf('%0'.$checkboxgroup_storesize.'d',$val));
          }
          $value = join(",",$newval);
        }
        Sql_Query(sprintf('replace into %s (attributeid,userid,value) values("%s","%s","%s")',
          $GLOBALS["tables"]["user_attribute"],$row["id"],$userid,$value));
        $history_entry .= "\n".$row["name"] . ' = '.UserAttributeValue($userid,$row["id"]);
  #    }
    }
  }
	if (is_array($old_data)) {
		$history_subject = 'Re-Subscription';
		# when they submit a new subscribe
	  $current_data = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',$GLOBALS["tables"]["user"],$userid));
	  $current_data = array_merge($current_data,getUserAttributeValues('',$userid));
	  foreach ($current_data as $key => $val) {
	  	if (!is_numeric($key))
	  	if ($old_data[$key] != $val && $key != "password" && $key != "modified") {
	    	$information_changed = 1;
	    	$history_entry .= "\n$key = $val\n*changed* from $old_data[$key]";
	   	}
	  }
	  if (!$information_changed) {
	  	$history_entry .= "\nNo user details changed";
	  }
	} else {
		$history_subject = 'Subscription';
	}

  $history_entry .= "\n\nList Membership: \n$lists\n";

  $subscribemessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("subscribemessage:$id",$userid));

  print '<title>'.$GLOBALS["strSubscribeTitle"].'</title>';
  print $subscribepagedata["header"];
  if ($_SESSION["adminloggedin"]) {
  	print '<p><b>You are logged in as '.$_SESSION["logindetails"]["adminname"].'</b></p>';
    print '<p><a href="'.$adminpages.'">Back to the main admin page</a></p>';
    if ($_POST["makeconfirmed"]) {
    	$sendrequest = 0;
      Sql_Query(sprintf('update %s set confirmed = 1 where email = "%s"',$GLOBALS["tables"]["user"],$email));
      addUserHistory($email,$history_subject." by ".$_SESSION["logindetails"]["adminname"],$history_entry);
    } else {
    	$sendrequest = 1;
		}
  } else {
  	$sendrequest = 1;
  }

  # personalise the thank you page
  if ($subscribepagedata["thankyoupage"]) {
  	$thankyoupage = $subscribepagedata["thankyoupage"];
  } else {
  	$thankyoupage = '<h3>'.$strThanks.'</h3>'. $strEmailConfirmation;
	}

  if (eregi("\[email\]",$thankyoupage,$regs))
    $thankyoupage = eregi_replace("\[email\]",$email,$thankyoupage);
  $user_att = getUserAttributeValues($email);
  while (list($att_name,$att_value) = each ($user_att)) {
    if (eregi("\[".$att_name."\]",$thankyoupage,$regs))
      $thankyoupage = eregi_replace("\[".$att_name."\]",$att_value,$thankyoupage);
  }

  if (is_array($GLOBALS["plugins"])) {
    reset($GLOBALS["plugins"]);
    foreach ($GLOBALS["plugins"] as $name => $plugin) {
      $thankyoupage = $plugin->parseThankyou($id,$userid,$thankyoupage);
    }
  }

  if ($sendrequest && is_array($_POST["list"])) {
  	if (sendMail($email, getConfig("subscribesubject:$id"), $subscribemessage,system_messageheaders($email),$envelope)) {
    	sendAdminCopy("Lists subscription","\n".$email . " has subscribed\n\n$history_entry");
      addUserHistory($email,$history_subject,$history_entry);
      print $thankyoupage;
   	} else {
      print '<h3>'.$strEmailFailed.'</h3>';
		}
  } else {
    print $thankyoupage;
    if ($_SESSION["adminloggedin"]) {
	    print "<p>User has been added and confirmed</p>";
    }
  }

  print "<P>".$PoweredBy.'</p>';
  print $subscribepagedata["footer"];
  exit;
} elseif ($_POST["update"] && is_email($_POST["email"]) && $allthere) {
  $email = trim($_POST["email"]);
  if ($_GET["uid"]) {
    $req = Sql_Fetch_Row_Query(sprintf('select id from %s where uniqid = "%s"',
      $GLOBALS["tables"]["user"],$_GET["uid"]));
    $userid = $req[0];
  } else {
    $req = Sql_Fetch_Row_query("select id from {$GLOBALS["tables"]["user"]} where email = \"".$_REQUEST["email"]."\"");
    $userid = $req[0];
  }
  if (!$userid)
    Fatal_Error("Error, no such user");
  # update the existing record, check whether the email has changed
  $req = Sql_Query("select * from {$GLOBALS["tables"]["user"]} where id = $userid");
  $data = Sql_fetch_array($req);
  # check whether they are changing to an email that already exists, should not be possible
	$req = Sql_Query("select uniqid from {$GLOBALS["tables"]["user"]} where email = \"$email\"");
  if (Sql_Affected_Rows()) {
  	$row = Sql_Fetch_Row($req);
    if ($row[0] != $_GET["uid"]) {
	  	Fatal_Error("Cannot change to that email address.
      <br/>This email already exists.
      <br/>Please use the preferences URL for this email to make updates.
      <br/>Click <a href=\"".getConfig("preferencesurl")."&email=$email\">here</a> to request your personal location");
	    exit;
    }
  }
  # read the current values to compare changes
  $old_data = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',$GLOBALS["tables"]["user"],$userid));
  $old_data = array_merge($old_data,getUserAttributeValues('',$userid));
  $history_entry = 'http://'.getConfig("website").$GLOBALS["adminpages"].'/?page=user&id='.$userid."\n\n";

  if (ASKFORPASSWORD && $_POST["password"]) {
  	if (ENCRYPTPASSWORD) {
    	$newpassword = sprintf('%s',md5($_POST["password"]));
   	} else {
    	$newpassword = sprintf('%s',$_POST["password"]);
    }
   	# see whether is has changed
    $curpwd = Sql_Fetch_Row_Query("select password from {$GLOBALS["tables"]["user"]} where id = $userid");
    if ($_POST["password"] != $curpwd[0]) {
    	$storepassword = 'password = "'.$newpassword.'",';
      Sql_query("update {$GLOBALS["tables"]["user"]} set passwordchanged = now() where id = $userid");
      $history_entry .= "\nUser has changed their password\n";
    } else {
    	$storepassword = "";
    }
  } else {
  	$storepassword = "";
  }

  $query = sprintf('update %s set email = "%s", %s htmlemail = %d where id = %d',
    $GLOBALS["tables"]["user"],addslashes($_POST["email"]),$storepassword,$_POST["htmlemail"],$userid);
 #print $query;
  $result = Sql_query($query);
  if ($data["email"] != $email) {
    $emailchanged = 1;
    Sql_Query(sprintf('update %s set confirmed = 0 where id = %d',$GLOBALS["tables"]["user"],$userid));
  }

  # subscribe to the lists
  # first take them off the ones, then re-subscribe
	if ($subscribepagedata["lists"]) {
    $subscribepagedata["lists"] = preg_replace("/^\,/","",$subscribepagedata["lists"]);
		Sql_query(sprintf('delete from %s where userid = %d and listid in (%s)',$GLOBALS["tables"]["listuser"],$userid,$subscribepagedata["lists"]));
	} else {
		Sql_query(sprintf('delete from %s where userid = %d',$GLOBALS["tables"]["listuser"],$userid));
	}

  $lists = "";
  if (is_array($_POST["list"])) {
    while(list($key,$val)= each($_POST["list"])) {
      if ($val == "signup") {
        $result = Sql_query("replace into {$GLOBALS["tables"]["listuser"]} (userid,listid,entered) values($userid,$key,now())");
        $lists .= "  * ".$_POST["listname"][$key]."\n";
      }
    }
  }
  if ($lists == "")
  	$lists = "No Lists";

  $datachange .= "$strEmail = ".$email . "\n";
  if ($subscribepagedata["htmlchoice"] != "textonly"
    && $subscribepagedata["htmlchoice"] != "htmlonly") {
	  $datachange .= "$strSendHTML = ";
	  $datachange .= $_POST["htmlemail"] ? "$strYes\n":"$strNo\n";
  }
  if ($_POST["rssfrequency"]) {
	  $datachange .= "$strFrequency = ".$_POST["rssfrequency"]."\n";
  }

  # remember the users attributes
  $attids = join(',',$attributes);
  $attids = substr($attids,0,-1);
  if ($attids && $attids != "") {
    $res = Sql_Query("select * from ".$GLOBALS["tables"]["attribute"] ." where id in ($attids)");
    while ($attribute = Sql_Fetch_Array($res)) {
      $fieldname = "attribute" .$attribute["id"];
      $value = $_POST[$fieldname];
      $replace = 1;#isset($_POST[$fieldname]);
      if (is_array($value)) {
        $values = array();
        foreach ($value as $val) {
          array_push($values,sprintf('%0'.$checkboxgroup_storesize.'d',$val));
        }
        $value = join(",",$values);
      }
      if ($replace) {
        Sql_query(sprintf('replace into %s (attributeid,userid,value) values("%s","%s","%s")',
          $GLOBALS["tables"]["user_attribute"],$attribute["id"],$userid,$value));
        if ($attribute["type"] != "hidden") {
          $datachange .= strip_tags($attribute["name"]) . " = ";
          if ($attribute["type"] == "checkbox")
            $datachange .= $value?$strYes:$strNo;
          elseif ($attribute["type"] != "textline" && $attribute["type"] != "textarea")
            $datachange .= AttributeValue($attribute["tablename"],$value);
          else
            $datachange .= stripslashes($value);
          $datachange .= "\n";
        }
      }
    }
  }
  $current_data = Sql_Fetch_Array_Query(sprintf('select * from %s where id = %d',$GLOBALS["tables"]["user"],$userid));
  $current_data = array_merge($current_data,getUserAttributeValues('',$userid));
  foreach ($current_data as $key => $val) {
  	if (!is_numeric($key))
  	if ($old_data[$key] != $val && $key != "password" && $key != "modified") {
    	$information_changed = 1;
    	$history_entry .= "$key = $val\n*changed* from $old_data[$key]\n";
   	}
  }
  if (!$information_changed) {
  	$history_entry .= "\nNo user details changed";
  }
  $history_entry .= "\n\nList Membership: \n$lists\n";

  $message = ereg_replace('\[LISTS\]', $lists, getUserConfig("updatemessage",$userid));
  $message = ereg_replace('\[USERDATA\]', $datachange, $message);
  if ($emailchanged) {
    $newaddressmessage = ereg_replace('\[CONFIRMATIONINFO\]', getUserConfig("emailchanged_text",$userid), $message);
    $oldaddressmessage = ereg_replace('\[CONFIRMATIONINFO\]', getUserConfig("emailchanged_text_oldaddress",$userid), $message);
  } else {
    $message = ereg_replace('\[CONFIRMATIONINFO\]', "", $message);
  }

	print '<title>'.$GLOBALS["strPreferencesTitle"].'</title>';
  print $subscribepagedata["header"];
  if (!TEST) {
    if ($emailchanged) {
      if (sendMail($data["email"],getConfig("updatesubject"),$oldaddressmessage, system_messageheaders($email),$envelope) &&
	      sendMail($email,getConfig("updatesubject"),$newaddressmessage, system_messageheaders($email),$envelope)) {
				$ok = 1;
    		sendAdminCopy("Lists information changed","\n".$data["email"] . " has changed their information.\n\nThe email has changed to $email.\n\n$history_entry");
      	addUserHistory($email,"Change",$history_entry);
      } else {
      	$ok = 0;
      }
    } else {
      if (sendMail($email, getConfig("updatesubject"), $message, system_messageheaders($email),$envelope)) {
      	$ok = 1;
    		sendAdminCopy("Lists information changed","\n".$data["email"] . " has changed their information\n\n$history_entry");
      	addUserHistory($email,"Change",$history_entry);
      } else {
      	$ok = 0;
      }
    }
  } else {
  	$ok = 1;
  }
  if ($ok) {
  	print '<h3>'.$GLOBALS["strPreferencesUpdated"].'</h3>';
    if ($emailchanged)
      echo $strPreferencesEmailChanged;
    print "<br/>";
    echo $strPreferencesNotificationSent;
  } else {
    print '<h3>'.$strEmailFailed.'</h3>';
  }
  print "<P>".$PoweredBy.'</p>';
  print $subscribepagedata["footer"];
  exit;
} elseif (($_POST["subscribe"] || $_POST["update"]) && !is_email($_POST["email"])) {
  $msg = '<div class="missing">'.$strEnterEmail.'</div><br/>';
} elseif (($_POST["subscribe"] || $_POST["update"]) && !$validhost) {
  $msg = '<div class="missing">'.$strInvalidHostInEmail.'</div><br/>';
} elseif (($_POST["subscribe"] || $_POST["update"]) && !$_POST["list"]) {
  $msg = '<div class="missing">'.$strEnterList.'</div><br/>';
} elseif (($_POST["subscribe"] || $_POST["update"]) && $missing) {
  $msg = '<div class="missing">'."$strValuesMissing: $missing".'</div><br/>';
}

function ListAvailableLists($userid = 0,$lists_to_show = "") {
  global $tables;
  $list = $_POST["list"];
	$subselect = "";$listset = array();

	$showlists = explode(",",$lists_to_show);
	foreach ($showlists as $listid)
		if (preg_match("/^\d+$/",$listid))
			array_push($listset,$listid);
	if (sizeof($listset) >= 1) {
		$subselect = "where id in (".join(",",$listset).") ";
	}

	$some = 0;
	$html = '<ul class="list">';
  $result = Sql_query("SELECT * FROM {$GLOBALS["tables"]["list"]} $subselect order by listorder");
  while ($row = Sql_fetch_array($result)) {
    if ($row["active"]) {
      $html .= '<li class="list"><input type="checkbox" name="list['.$row["id"] . ']" value=signup ';
      if ($list[$row["id"]] == "signup")
        $html .= "checked";
      if ($userid) {
        $req = Sql_Fetch_Row_Query(sprintf('select userid from %s where userid = %d and listid = %d',
          $GLOBALS["tables"]["listuser"],$userid,$row["id"]));
        if (Sql_Affected_Rows())
          $html .= "checked";
      }
      $html .= "/><b>".stripslashes($row["name"]).'</b><div class="listdescription">';
      $desc = nl2br(StripSlashes($row["description"]));
      $html .= '<input type=hidden name="listname['.$row["id"] . ']" value="'.htmlspecialchars(stripslashes($row["name"])).'"/>';
      $html .= $desc.'</div></li>';
			$some++;
			if ($some == 1) {
				$singlelisthtml = sprintf('<input type="hidden" name="list[%d]" value="signup">',$row["id"]);
      	$singlelisthtml .= '<input type="hidden" name="listname['.$row["id"] . ']" value="'.htmlspecialchars(stripslashes($row["name"])).'"/>';
			}
    }
  }
  $html .= '</ul>';
	$hidesinglelist = getConfig("hide_single_list");
  if (!$some) {
    global $strNotAvailable;
    return '<p>'.$strNotAvailable.'</p>';
  } elseif ($some == 1 && $hidesinglelist == "true") {
		return $singlelisthtml;
	} else {
		global $strPleaseSelect;
		return '<p>'.$strPleaseSelect .':</p>'.$html;
	}
}

function ListAttributes($attributes,$attributedata,$htmlchoice = 0,$userid = 0) {
  global $strPreferHTMLEmail,$strPreferTextEmail,
  	$strEmail,$tables,$table_prefix,$strPreferredFormat,$strText,$strHTML;
/*	if (!sizeof($attributes)) {
  	return "No attributes have been defined for this page";
 	}
*/
  if ($userid) {
    $data = array();
    $current = Sql_Fetch_array_Query("select * from {$GLOBALS["tables"]["user"]} where id = $userid");
  	$datareq = Sql_Query("select * from {$GLOBALS["tables"]["user_attribute"]} where userid = $userid");
    while ($row = Sql_Fetch_Array($datareq)) {
      $data[$row["attributeid"]] = $row["value"];
    }

    $email = $current["email"];
    $htmlemail = $current["htmlemail"];
    # override with posted info
    foreach ($current as $key => $val) {
      if ($_POST[$key] && $key != "password") {
        $current[$key] = $val;
      }
    }
  } else {
    $email = stripslashes($_REQUEST["email"]);
    $htmlemail = $_POST["htmlemail"];
  }

  $textlinewidth = sprintf('%d',getConfig("textline_width"));
  if (!$textlinewidth) $textlinewidth = 40;
  list($textarearows,$textareacols) = explode(",",getConfig("textarea_dimensions"));
  if (!$textarearows) $textarearows = 10;
  if (!$textareacols) $textareacols = 40;

  $html = "";
  if ($_GET["page"] != "import1")
  $html .= sprintf('
  <tr><td><div class="required">%s</div></td>
  <td class="attributeinput"><input type=text name=email value="%s" size="%d">
  <script language="Javascript" type="text/javascript">addFieldToCheck("email","%s");</script></td></tr>',
  $GLOBALS["strEmail"],htmlspecialchars($email),$textlinewidth,$GLOBALS["strEmail"]);

  if ($_GET["page"] != "import1")
  if (ASKFORPASSWORD) {
  	# we only require a password if there isnt one, so they can set it
    # otherwise they can keep the existing, if they do not enter anything
  	if (!$current["password"]) {
    	$pwdclass = "required";
      $js = sprintf('<script language="Javascript" type="text/javascript">addFieldToCheck("password","%s");</script>',$GLOBALS["strPassword"]);
      $js2 = sprintf('<script language="Javascript" type="text/javascript">addFieldToCheck("password_check","%s");</script>',$GLOBALS["strPassword2"]);
      $html .= '<input type=hidden name="passwordreq" value="1">';
    } else {
    	$pwdclass = 'attributename';
      $html .= '<input type=hidden name="passwordreq" value="0">';
    }

  	$html .= sprintf('
  <tr><td><div class="%s">%s</div></td>
  <td class="attributeinput"><input type=password name=password value="" size="%d">%s</td></tr>',
  $pwdclass,$GLOBALS["strPassword"],$textlinewidth,$js);
  	$html .= sprintf('
  <tr><td><div class="%s">%s</div></td>
  <td class="attributeinput"><input type=password name="password_check" value="" size="%d">%s</td></tr>',
  $pwdclass,$GLOBALS["strPassword2"],$textlinewidth,$js2);
 	}

  switch($htmlchoice) {
  	case "textonly":
      $html .= sprintf('<input type=hidden name="htmlemail" value="0">');
      break;
  	case "htmlonly":
      $html .= sprintf('<input type=hidden name="htmlemail" value="1">');
      break;
  	case "checkfortext":
      $html .= sprintf('<tr><td colspan=2>
      <span class="attributeinput">
      <input type=checkbox name="textemail" value="1" %s></span>
      <span class="attributename">%s</span>
      </td></tr>',!$htmlemail,$strPreferTextEmail);
      break;
  	case "radiotext":
    	if (!isset($htmlemail))
      	$htmlemail = 0;
      $html .= sprintf('<tr><td colspan=2>
      	<span class="attributename">%s<br/>
      	<span class="attributeinput"><input type=radio name="htmlemail" value="0" %s></span>
        <span class="attributename">%s</span>
        <span class="attributeinput"><input type=radio name="htmlemail" value="1" %s></span>
        <span class="attributename">%s</span></td></tr>',
        $strPreferredFormat,
        !$htmlemail ? "checked":"",$strText,
        $htmlemail ? "checked":"",$strHTML);
      break;
  	case "radiohtml":
    	if (!isset($htmlemail))
      	$htmlemail = 1;
      $html .= sprintf('<tr><td colspan=2>
      	<span class="attributename">%s</span><br/>
      	<span class="attributeinput"><input type=radio name="htmlemail" value="0" %s></span>
        <span class="attributename">%s</span>
        <span class="attributeinput"><input type=radio name="htmlemail" value="1" %s></span>
        <span class="attributename">%s</span></td></tr>',
        $strPreferredFormat,
        !$htmlemail ? "checked":"",$strText,
        $htmlemail ? "checked":"",$strHTML);
      break;
  	case "checkforhtml":
    default:
      $html .= sprintf('<tr><td colspan=2>
      	<span class="attributeinput"><input type=checkbox name="htmlemail" value="1" %s></span>
        <span class="attributename">%s</span></td></tr>',$htmlemail ? "checked":"",$strPreferHTMLEmail);
      break;
	}
  $html .= "\n";

  $attids = join(',',array_keys($attributes));
  if ($attids) {
    $res = Sql_Query("select * from {$GLOBALS["tables"]["attribute"]} where id in ($attids)");
    while ($attr = Sql_Fetch_Array($res)) {
      $attr["required"] = $attributedata[$attr["id"]]["required"];
      $attr["default_value"] = $attributedata[$attr["id"]]["default_value"];
      $fieldname = "attribute" .$attr["id"];
  #  print "<tr><td>".$attr["id"]."</td></tr>";
      if ($userid && !isset($_POST[$fieldname])) {
        # post values take precedence
        $val = Sql_Fetch_Row_Query(sprintf('select value from %s where
          attributeid = %d and userid = %d',$GLOBALS["tables"]["user_attribute"],$attr["id"],$userid));
        $_POST[$postvalue] = $val[0];
      }
      switch ($attr["type"]) {
        case "checkbox":
          $output[$attr["id"]] = '<tr><td colspan=2>';
          # what they post takes precedence over the database information
          if ($_POST[$fieldname])
            $checked = $_POST[$fieldname] ? "checked":"";
          else
            $checked = $data[$attr["id"]] ? "checked":"";
          $output[$attr["id"]] .= sprintf("\n".'<input type="checkbox" name="%s" value="on" %s class="attributeinput">',$fieldname,$checked);
          $output[$attr["id"]] .= sprintf("\n".'<span class="%s">%s</span>',$attr["required"] ? 'required' : 'attributename',stripslashes($attr["name"]));
          break;
        case "radio":
          $output[$attr["id"]] .= sprintf("\n".'<tr><td colspan=2><div class="%s">%s</div>',$attr["required"] ? 'required' : 'attributename',stripslashes($attr["name"]));
          $values_request = Sql_Query("select * from $table_prefix"."listattr_".$attr["tablename"]." order by listorder,name");
          while ($value = Sql_Fetch_array($values_request)) {
            if (isset($_POST[$fieldname]))
              $checked = $_POST[$fieldname] == $value["id"] ? "checked":"";
            else if (isset($data[$attr["id"]]))
              $checked = $data[$attr["id"]] == $value["id"] ? "checked":"";
            else
              $checked = $attr["default_value"] == $value["name"] ? "checked":"";
            $output[$attr["id"]] .= sprintf('&nbsp;%s&nbsp;<input type=radio  class="attributeinput" name="%s" value="%s" %s>',
              $value["name"],$fieldname,$value["id"],$checked);
          }
          break;
        case "select":
          $output[$attr["id"]] .= sprintf("\n".'<tr><td><div class="%s">%s</div>',$attr["required"] ? 'required' : 'attributename',stripslashes($attr["name"]));
          $values_request = Sql_Query("select * from $table_prefix"."listattr_".$attr["tablename"]." order by listorder,name");
          $output[$attr["id"]] .= sprintf('</td><td class="attributeinput"><!--%d--><select name="%s" class="attributeinput">',$data[$attr["id"]],$fieldname);
          while ($value = Sql_Fetch_array($values_request)) {
            if (isset($_POST[$fieldname]))
              $selected = $_POST[$fieldname] == $value["id"] ? "selected" : "";
            else if (isset($data[$attr["id"]]))
              $selected = $data[$attr["id"]] == $value["id"] ? "selected":"";
            else
              $selected = $attr["default_value"] == $value["name"] ? "selected":"";
            $output[$attr["id"]] .= sprintf('<option value="%s" %s>%s',$value["id"],$selected,stripslashes($value["name"]));
          }
          $output[$attr["id"]] .= "</select>";
          break;
        case "checkboxgroup":
          $output[$attr["id"]] .= sprintf("\n".'<tr><td colspan=2><div class="%s">%s</div>',$attr["required"] ? 'required' : 'attributename',stripslashes($attr["name"]));
          $values_request = Sql_Query("select * from $table_prefix"."listattr_".$attr["tablename"]." order by listorder,name");
          $output[$attr["id"]] .= sprintf('</td></tr>');
          while ($value = Sql_Fetch_array($values_request)) {
            if (isset($_POST[$fieldname]))
              $selected = in_array($value["id"],$_POST[$fieldname]) ? "checked" : "";
            else if (isset($data[$attr["id"]])) {
              $selection = explode(",",$data[$attr["id"]]);
              $selected = in_array($value["id"],$selection) ? "checked":"";
            }
            $output[$attr["id"]] .= sprintf('<tr><td colspan=2 class="attributeinput"><input type=checkbox name="%s[]"  class="attributeinput" value="%s" %s> %s</td></tr>',$fieldname,$value["id"],$selected,stripslashes($value["name"]));
          }
          break;
        case "textline":
          $output[$attr["id"]] .= sprintf("\n".'<tr><td><div class="%s">%s</div>',$attr["required"] ? 'required' : 'attributename',$attr["name"]);
          $output[$attr["id"]] .= sprintf ('</td><td class="attributeinput">
            <input type=text name="%s"  class="attributeinput" size="%d" value="%s">',$fieldname,
            $textlinewidth,
            $_POST[$fieldname] ? htmlspecialchars(stripslashes($_POST[$fieldname])) : ($data[$attr["id"]] ? $data[$attr["id"]] : $attr["default_value"]));
          if ($attr["required"])
            $output[$attr["id"]] .= sprintf('<script language="Javascript" type="text/javascript">addFieldToCheck("%s","%s");</script>',$fieldname,$attr["name"]);
          break;
        case "textarea":
          $output[$attr["id"]] .= sprintf("\n".'<tr><td colspan=2>
            <div class="%s">%s</div></td></tr>',$attr["required"] ? 'required' : 'attributename',
            $attr["name"]);
          $output[$attr["id"]] .= sprintf ('<tr><td class="attributeinput" colspan=2>
            <textarea name="%s" rows="%d"  class="attributeinput" cols="%d" wrap="virtual">%s</textarea>',
            $fieldname,$textarearows,$textareacols,
            $_POST[$fieldname] ? htmlspecialchars(stripslashes($_POST[$fieldname])) : ($data[$attr["id"]] ? $data[$attr["id"]] : $attr["default_value"]));
          if ($attr["required"])
            $output[$attr["id"]] .= sprintf('<script language="Javascript" type="text/javascript">addFieldToCheck("%s","%s");</script>',$fieldname,$attr["name"]);
          break;
        case "hidden":
          $output[$attr["id"]] .= sprintf('<input type=hidden name="%s" size=40 value="%s">',$fieldname,$data[$attr["id"]] ? $data[$attr["id"]] : $attr["default_value"]);
          break;
        default:
          print "<!-- error: huh, invalid attribute type -->";
      }
      $output[$attr["id"]] .= "</td></tr>\n";
    }
  }

  # make sure the order is correct
	foreach ($attributes as $attribute => $listorder) {
  	$html .= $output[$attribute];
 	}
  return $html;
}

function ListAllAttributes() {
	global $tables;
 	$attributes = array();
  $attributedata = array();
  $res = Sql_Query("select * from {$GLOBALS["tables"]["attribute"]} order by listorder");
  while ($row = Sql_Fetch_Array($res)) {
 # 	print $row["id"]. " ".$row["name"];
  	$attributes[$row["id"]] = $row["listorder"];
		$attributedata[$row["id"]]["id"] = $row["id"];
		$attributedata[$row["id"]]["default_value"] = $row["default_value"];
		$attributedata[$row["id"]]["listorder"] = $row["listorder"];
		$attributedata[$row["id"]]["required"] = $row["required"];
		$attributedata[$row["id"]]["default_value"] = $row["default_value"];
  }
	return ListAttributes($attributes,$attributedata,"checkforhtml");
}

function RSSOptions($data,$userid = 0) {
	global $rssfrequencies,$tables;
  if ($userid) {
  	$current = Sql_Fetch_Row_Query("select rssfrequency from {$GLOBALS["tables"]["user"]} where id = $userid");
    $default = $current[0];
  }
  if (!$default || !in_array($default,array_keys($rssfrequencies))) {
  	$default = $data["rssdefault"];
  }

  $html = "\n<table>";
  $html .= '<tr><td>'.$data["rssintro"].'</td></tr>';
  $html .= '<tr><td>';
  $options = explode(",",$data["rss"]);
  if (!in_array($data["rssdefault"],$options)) {
    array_push($options,$data["rssdefault"]);
  }
  foreach ($options as $freq) {
    $html .= sprintf('<input type=radio name="rssfrequency" value="%s" %s>&nbsp;%s&nbsp;',
      $freq,$freq == $default ? "checked":"",$rssfrequencies[$freq]);
  }
  $html .= '</td></tr></table>';
  if ($data["rssintro"])
		return $html;
}

?>
