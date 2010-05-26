<?php
require_once dirname(__FILE__).'/accesscheck.php';

#initialisation###############

// Verify that FCKeditor is available
if (USEFCK && file_exists("./FCKeditor/fckeditor.php")) {
  include("./FCKeditor/fckeditor.php") ;

  // Create the editor object here so we can check to see if *it* wants us to use it (this
  // does a browser check, etc.
  $oFCKeditor = new FCKeditor('message') ;
  $usefck = $oFCKeditor->IsCompatible();
  unset($oFCKeditor); // This object is *very* short-lived.  Thankfully, it's also light-weight
} else {
  $usefck = 0;
}

// Verify that TinyMCE is available
$useTinyMCE = 0;
if (USETINYMCEMESG && file_exists(TINYMCEPATH)) {
  $useTinyMCE = 1;
}

include_once dirname(__FILE__). "/date.php";

$errormsg = '';
//$rss_content = '';          //Obsolete by rssmanager plugin
$done = 0;
$messageid = 0;
$forwardsubject = $forwardmessage = $forwardfooter = '';
$duplicate_atribute = 0; # not actually used it seems @@@ check
$embargo = new date("embargo");
$embargo->useTime = true;
$repeatuntil = new date("repeatuntil");
$repeatuntil->useTime = true;

### variable initialisation and sanity checks
if (isset($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
} else {
  $id = 0;
}
## page actions
$send = isset($_POST["send"]);
$prepare = isset($_POST['prepare']);
$save = !empty($_POST["save"]) || !empty($_POST['followupto']);
$sendtest = !empty($_POST["sendtest"]);
$baseurl = PageURL2($_GET["page"].'&amp;id='.$id);

if (!isset($_GET['tab'])) $_GET['tab'] = '';
if (!empty($_GET['tab'])) {
  $baseurl .= '&tab='.$_GET['tab'];
}

### if we're not working on an existing message, create one and redirect to edit it
if (!$id) {
  $defaulttemplate = getConfig('defaultmessagetemplate');
  $query
  = " insert into %s"
  . "    (subject, status, entered, sendformat, embargo"
  . "    , repeatuntil, owner, template, tofield, replyto)"
  . " values"
  . "    ('(no subject)', 'draft', current_timestamp, 'HTML'"
  . "    , current_timestamp, current_timestamp, ?, ?, '', '')";
  $query = sprintf($query, $GLOBALS['tables']['message']);
  Sql_Query_Params($query, array($_SESSION['logindetails']['id'], $defaulttemplate));
  $id = Sql_Insert_Id($GLOBALS['tables']['message'], 'id');
  # 0008720: Using -p send from the commandline doesn't seem to work '
  if(!$GLOBALS["commandline"]) {
    Redirect($_GET["page"].'&id='.$id);
    exit;
  }
}

# load all message data
$messagedata = loadMessageData($id);
//var_dump($messagedata);exit;

if (isset($_GET['deleterule']) && $_GET["deleterule"]) {
  Sql_Query(sprintf('delete from %s where name = "criterion%d" and id = %d',$GLOBALS["tables"]["messagedata"],$_GET["deleterule"],$id));
  Redirect($_GET["page"].'&id='.$id.'&tab='.$_GET["tab"]);
}
ob_end_flush();

#load database data###########################

if ($id) {
  // Load message attributes / values
  print '<div id="addtoqueue"><input class="submit" type="submit" name="send" id="addtoqueuebutton" value="'.$GLOBALS['I18N']->get('sendmessage').'" /></div>';

  require dirname(__FILE__)."/structure.php";  // This gets the database structures into DBStruct

  $result = Sql_query("SELECT * FROM {$tables['message']} where id = $id $ownership");
  if (!Sql_Num_Rows($result)) {
    print $GLOBALS['I18N']->get("noaccess");
    $done = 1;
    return;
  }
  ## remember any data entered
  foreach ($_POST as $key => $val) {
  #  print $key;
    setMessageData($id,$key,$val);
    if (get_magic_quotes_gpc()) {
      if (is_string($val)) {
        $messagedata[$key] = stripslashes($val);
      } else {
        $messagedata[$key] = $val;
      }
    } else {
      $messagedata[$key] = $val;
    }
  }
  if ((isset($_POST["embargo"]['year']) && is_array($_POST["embargo"]))) {
    $messagedata["embargo"] = $embargo->getDate() ." ".$embargo->getTime().':00';
  }

  if ((isset($_POST["repeatuntil"]['year']) && is_array($_POST["repeatuntil"]))) {
    $messagedata["repeatuntil"] = $repeatuntil->getDate() ." ".$repeatuntil->getTime().':00';
  }
}

$htmlformatted = strip_tags($messagedata["message"]) != $messagedata["message"];

# sanitise the header fields, what else do we need to check on?
if (preg_match("/\n|\r/",$messagedata["from"])) {
  $messagedata["from"] = "";
} 
if (preg_match("/\n|\r/",$messagedata["forwardsubject"])) {
  $messagedata["forwardsubject"] = "";
} 
if (preg_match("/\n|\r/",$messagedata["forwardmessage"])) {
  $messagedata["forwardmessage"] = "";
} 
if (preg_match("/\n|\r/",$messagedata["forwardfooter"])) {
  $messagedata["forwardfooter"] = "";
} 

if (!empty($messagedata['sendurl'])) {
  if (!$GLOBALS["has_pear_http_request"]) {
    print Warn($GLOBALS['I18N']->get('warnnopearhttprequest'));
  } else {
    $messagedata["message"] = '[URL:'.$messagedata['sendurl'].']';
  }
} 

// If the variable isn't filled in, then the input fields don't default to the
// values selected.  Need to fill it in so a post will correctly display.

if (!isset($_SESSION["fckeditor_height"])) {
  $_SESSION["fckeditor_height"] = getConfig("fckeditor_height");
}

#actions and store in database#######################

if ($send || $sendtest || $prepare || $save) {

  if ($save || $sendtest) {
    // We're just saving, not sending.
    if (!isset($messagedata['status']) || $messagedata["status"] == "") {
      // No status - move to draft state
      $messagedata['status'] = "draft";
    } 
  } elseif ($send) {
    // We're sending - change state to "send-it" status!
    if (is_array($messagedata["targetlist"]) && sizeof($messagedata["targetlist"]) 
      && !empty($messagedata['subject']) && !empty($messagedata['from']) && 
      !empty($messagedata['message']) && empty($duplicate_attribute)) {
      $messagedata['status'] = "submitted";
    } else {
      if (USE_PREPARE) {
        $messagedata['status'] = "prepared";
      } else {
        $messagedata['status'] = "draft";
      }
    }
  }

//Obsolete by rssmanager plugin
//   if (ENABLE_RSS && $_POST["rsstemplate"]) {
//    # mark previous RSS templates with this frequency and owner as sent
//    # this should actually be much more complex than this:
//    # templates should be allowed by list and therefore a subset of lists, but
//    # for now we leave it like this
//    # the trouble is that this may duplicate RSS messages to users, because
//    # it can cause multiple template for lists. The user_rss should handle that, but it is
//    # not guaranteed which message will be used.
//#    Sql_Query(sprintf('update %s set status = "sent" where rsstemplate = "%s" and owner = %d',
//#      $tables["message"],$_POST["rsstemplate"],$_SESSION["logindetails"]["id"]));
//
//
//    # with RSS message we enforce repeat
//    switch ($_POST["rsstemplate"]) {
//      case "weekly": $_POST["repeatinterval"] = 10080; break;
//      case "monthly": $_POST["repeatinterval"] = 40320; break;
//      case "daily":
//      default: $_POST["repeatinterval"] = 1440; break;
//    }
//    $_POST["repeatuntil"] = date("Y-m-d H:i:00",mktime(0,0,0,date("m"),date("d"),date("Y")+1));
//  }

  ### allow plugins manipulate data or save it somewhere else
  $plugintabs = array();
  foreach ($GLOBALS['plugins'] as $plugin) {
  #  print "Saving ".$plugin->name;
    $resultMsg = $plugin->sendMessageTabSave($id,$messagedata);
  }

  if (!$htmlformatted  && strip_tags($messagedata["message"]) !=  $messagedata["message"])
    $errormsg = '<span  class="error">'.$GLOBALS['I18N']->get("htmlusedwarning").'</span>';
  $query = sprintf('update %s  set '
     . '  subject = ?'
     . ', fromfield = ?'
     . ', tofield = ?'
     . ', replyto = ?'
     . ', embargo = ?'
     . ', repeatinterval = ?'
     . ', repeatuntil = ?'
     . ', message = ?'
     . ', textmessage = ?'
     . ', footer = ?'
     . ', status = ?'
     . ', htmlformatted = ?'
     . ', sendformat  =  ?'
     . ', template  =  ?'
     . ', rsstemplate = ?'         //Leftover from the preplugin era
     . ' where id = ?', $tables["message"]);
  $result = Sql_Query_Params($query, array(
       $messagedata['subject']
     , $messagedata['from']
     , $messagedata["tofield"]
     , $messagedata["replyto"]
     , sprintf('%04d-%02d-%02d %02d:%02d',
        $messagedata["embargo"]['year'],$messagedata["embargo"]['month'],$messagedata["embargo"]['day'],
        $messagedata["embargo"]['hour'],$messagedata["embargo"]['minute'])
     , $messagedata["repeatinterval"]
     , sprintf('%04d-%02d-%02d %02d:%02d',
        $messagedata["repeatuntil"]['year'],$messagedata["repeatuntil"]['month'],$messagedata["repeatuntil"]['day'],
        $messagedata["repeatuntil"]['hour'],$messagedata["repeatuntil"]['minute'])
     , $messagedata["message"]
     , $messagedata["textmessage"]
     , $messagedata["footer"]
     , $messagedata['status']
     , $htmlformatted ? '1' : '0'
     , $messagedata["sendformat"]
     , $messagedata["template"]
     , $messagedata["rsstemplate"]          //Leftover from the preplugin era
     , $id));
#    print $query;
#    print "Message ID: $id";
    #    exit;
    if (!$GLOBALS["has_pear_http_request"] && preg_match("/\[URL:/i",$_POST["message"])) {
      print Warn($GLOBALS['I18N']->get('warnnopearhttprequest'));
    }

  // More  "Insert  only"  stuff  here (no need  to change  it on  an edit!)
  if (isset($messagedata["targetlist"]) && is_array($messagedata["targetlist"]))  {
    Sql_query("delete from {$tables["listmessage"]} where messageid = $id");
    if ( (isset($messagedata["targetlist"]["all"])) ||
      (isset($messagedata["targetlist"]["allactive"]))
      ) {
      $res = Sql_query("select * from  $tables[list] $subselect");
      while($row = Sql_fetch_array($res))  {
        $listid  =  $row["id"];
        if ($row["active"] || $messagedata["targetlist"]["all"] == "on")  {
          $result  =  Sql_query("insert ignore into $tables[listmessage]  (messageid,listid,entered) values($id,$listid,current_timestamp)");
        }
      }
    } else {
      foreach($messagedata["targetlist"] as $listid => $val) {
        $query
        = ' insert into ' . $tables['listmessage']
        . '    (messageid,listid,entered)'
        . ' values'
        . '    (?, ?, current_timestamp)';
        $result = Sql_Query_Params($query, array($id, $listid));
      }
    }
  } else {
    #  mark this  message  as listmessage for list  0
    $result  =  Sql_query("insert ignore into $tables[listmessage]  (messageid,listid,entered) values($id,0,current_timestamp)");
  }

# we want to create a join on tables as follows, in order to find users who have their attributes to the values chosen
# (independent of their list membership).
# select
#  table1.userid from user_attribute as table1
#  left join user_attribute as table2 on table1.userid = table2.userid
#  left join user_attribute as table3 on table1.userid = table3.userid
#  ...
# where
#  table1.attributeid = 2 and table1.value in (1,2,3,4)
#  and table2.attributeid = 1 and table2.value in (3,15)
#  and table3.attributeid = 3 and table3.value in (4,5,6)
#  ...

  # new criteria system, add one by one:
  if (!empty($_POST["criteria_attribute"])) {
    $operator = $messagedata["criteria_operator"];
    if (is_array($messagedata["criteria_values"])) {
      $values = join(", ",$messagedata["criteria_values"]);
      $values = cleanCommaList($values);
    } else {
      $values = $messagedata["criteria_values"];
    }
    foreach ($_POST["attribute_names"] as $key => $val) {
      $att_names[$key] = $val;
    }
    $newcriterion = array(
      "attribute" => sprintf('%d',$_POST["criteria_attribute"]),
      "attribute_name" => $att_names[$messagedata["criteria_attribute"]],
      "operator" => $operator,
      "values" => $values,
    );
    # find out what number we are
    $numarr = Sql_Fetch_Row_Query(sprintf('select data from %s where id = %d and name = "numcriteria"',
      $tables["messagedata"],$id));
    $num = sprintf('%d',$numarr[0]+1);
    # store this one
#    print $att_names[$_POST["criteria_attribute"]];
#    print $_POST["attribute_names[".$_POST["criteria_attribute"]."]"];
    print '<p class="information">'.$GLOBALS['I18N']->get("adding")." ".$newcriterion["attribute_name"]." ".$newcriterion["operator"]." ".$newcriterion["values"]."</p>";
    Sql_Query(sprintf('insert into %s (name,id,data) values("criterion%d",%d,"%s")',
      $tables["messagedata"],$num,$id,delimited($newcriterion)));
    # increase number
    Sql_Query(sprintf('replace into %s (name,id,data) values("numcriteria",%d,"%s")',
      $tables["messagedata"],$id,$num));
  }

  if (ALLOW_ATTACHMENTS && isset($_FILES) && is_array($_FILES) && sizeof($_FILES) > 0) {
    for ($att_cnt = 1;$att_cnt <= NUMATTACHMENTS;$att_cnt++) {
      $fieldname = "attachment".$att_cnt;
      if (isset($_FILES[$fieldname])) {
        $tmpfile = $_FILES[$fieldname]['tmp_name'];
        $remotename = $_FILES[$fieldname]["name"];
        $type = $_FILES[$fieldname]["type"];
        $newtmpfile = $remotename.time();
        move_uploaded_file($tmpfile, $GLOBALS['tmpdir'].'/'. $newtmpfile);
        if (is_file($GLOBALS['tmpdir'].'/'.$newtmpfile) && filesize($GLOBALS['tmpdir'].'/'.$newtmpfile)) {
          $tmpfile = $GLOBALS['tmpdir'].'/'.$newtmpfile;
        }
        if (strlen($_POST[$type]) > 255)
          print Warn($GLOBALS['I18N']->get("longmimetype"));
        $description = $_POST[$fieldname."_description"];
      } else {
        $tmpfile = '';
      }
      if ($tmpfile && filesize($tmpfile) && $tmpfile != "none") {
        list($name,$ext) = explode(".",basename($remotename));
        # create a temporary file to make sure to use a unique file name to store with
        $newfile = tempnam($GLOBALS["attachment_repository"],$name);
        $newfile .= ".".$ext;
        $newfile = basename($newfile);
        $file_size = filesize($tmpfile);
        $fd = fopen( $tmpfile, "r" );
        $contents = fread( $fd, filesize( $tmpfile ) );
        fclose( $fd );
        if ($file_size) {
          # this may seem odd, but it allows for a remote (ftp) repository
          # also, "copy" does not work across filesystems
          $fd = fopen($GLOBALS["attachment_repository"]."/".$newfile, "w" );
          fwrite( $fd, $contents );
          fclose( $fd );
          Sql_query(sprintf('insert into %s (filename,remotefile,mimetype,description,size) values("%s","%s","%s","%s",%d)',
          $tables["attachment"],
          basename($newfile),$remotename,$type,$description,$file_size)
          );
          $attachmentid = Sql_Insert_Id($tables['attachment'], 'id');
          Sql_query(sprintf('insert into %s (messageid,attachmentid) values(%d,%d)',
          $tables["message_attachment"],$id,$attachmentid));
          if (is_file($tmpfile)) {
            unlink($tmpfile);
          }

          # do a final check
          if (filesize($GLOBALS["attachment_repository"]."/".$newfile))
            print Info($GLOBALS['I18N']->get("addingattachment")." ".$att_cnt . " .. ok");
          else
            print Info($GLOBALS['I18N']->get("addingattachment")." ".$att_cnt." .. failed");
        } else {
          print Warn($GLOBALS['I18N']->get("uploadfailed"));
        }
      } elseif (!empty($_POST["localattachment".$att_cnt])) {
        $type = findMime(basename($_POST["localattachment".$att_cnt]));
        Sql_query(sprintf('insert into %s (remotefile,mimetype,description,size) values("%s","%s","%s",%d)',
          $tables["attachment"],
          $_POST["localattachment".$att_cnt],$type,$description,filesize($_POST["localattachment".$att_cnt]))
        );
        $attachmentid = Sql_Insert_Id($tables['attachment'], 'id');
        Sql_query(sprintf('insert into %s (messageid,attachmentid) values(%d,%d)',
        $tables["message_attachment"],$id,$attachmentid));
        print Info($GLOBALS['I18N']->get("addingattachment")." ".$att_cnt. " mime: $type");
      }
    }
  }
  
  ## when followupto is set, go there
  if (!empty($_POST['followupto']) && isValidRedirect($_POST['followupto'])) {
    Header('Location: '.$_POST['followupto']);
    exit;
  } 

  if (!empty($id)) {
    print "<h3>".$GLOBALS['I18N']->get("saved")."</H3><br/>";
  } else {
#    $id = $messageid; // New ID - need to set it for later use (test email).
    print "<h3>".$GLOBALS['I18N']->get("added")."</H3><br/>";
  }

  // If we're sending the message, just return now to the calling script
  # we only need to check that everything is there, once we actually want to send
  if ($send && $subject && $from && $message && !$duplicate_atribute && sizeof($_POST["targetlist"])) {
    if ($status == "submitted") {
      print "<h3>".$GLOBALS['I18N']->get("queued")."</h3>";
      print '<p class="button">'.PageLink2("processqueue",$GLOBALS['I18N']->get("processqueue")).'</p>';
    }
    $done = 1;
    return;
  } elseif ($send || $sendtest) {
    $errormessage = "";
    if ($messagedata['subject'] != stripslashes($_POST["subject"])) {
      $errormessage = $GLOBALS['I18N']->get("errorsubject");
    } elseif ($messagedata['from'] != $_POST["from"]) {
      $errormessage = $GLOBALS['I18N']->get("errorfrom");
    } elseif (empty($messagedata['from'])) {
      $errormessage = $GLOBALS['I18N']->get("enterfrom");
    } elseif (empty($messagedata['message'])) {
      $errormessage = $GLOBALS['I18N']->get("entermessage");
    } elseif (empty($messagedata['subject'])) {
      $errormessage = $GLOBALS['I18N']->get("entersubject");
    } elseif (!empty($duplicate_attribute)) {
      $errormessage = $GLOBALS['I18N']->get("duplicateattribute");
    } elseif ($send && !is_array($_POST["targetlist"])) {
      $errormessage = $GLOBALS['I18N']->get("selectlist");
    }
    echo "$errormessage<br/>";
  }

  // OK, the message has been saved, now check to see if we need to send a test message
  if ($sendtest) {

    echo "<HR>";
    // Let's send test messages to everyone that was specified in the
    if ($messagedata["testtarget"] == "") {
      print $GLOBALS['I18N']->get("notargetemail")."<br/>";
    }

    if (isset($cached[$id])) {
      unset($cached[$id]);
    }
    clearPageCache();
    include "sendemaillib.php";

    // OK, let's get to sending!
    $emailaddresses = split('[/,,/;]', $_POST["testtarget"]);

    foreach ($emailaddresses as $address) {
      $address = trim($address);
      $query
      = ' select id, email, uniqid, htmlemail, rssfrequency, confirmed'
      . ' from %s'
      . ' where email = ?';
      $query = sprintf($query, $tables['user']);
      $result = Sql_Query_Params($query, array($address));
                                                                                                          //Leftover from the preplugin era
      if ($user = Sql_fetch_array($result)) {
        if ( FORWARD_ALTERNATIVE_CONTENT && $_GET['tab'] == 'Forward') {
          if (SEND_ONE_TESTMAIL) {
            $success = sendEmail($id, $address, $user["uniqid"], $user['htmlemail'], array(), array($address) );
          } else {
            $success = sendEmail($id, $address, $user["uniqid"], 1,  array(), array($address) ) && sendEmail($id, $address, $user["uniqid"], 0,  array(), array($address));
          }
        } else {
          if (SEND_ONE_TESTMAIL) {
            $success = sendEmail($id, $address, $user["uniqid"], $user['htmlemail']);
          } else {
            $success = sendEmail($id, $address, $user["uniqid"], 1) && sendEmail($id, $address, $user["uniqid"], 0);
          }
        }
        print $GLOBALS['I18N']->get("sentemailto").": $address ";
        if (!$success) {
          print $GLOBALS['I18N']->get('failed');
        } else {
          print $GLOBALS['I18N']->get('success');
        }
        print '<br/>';
      } else {
        print $GLOBALS['I18N']->get("emailnotfound").": $address<br/>";
        printf('<div><a href="%s&action=addemail&email='.$address.'" class="ajaxable">%s</a></div>',$baseurl,$GLOBALS['I18N']->get("add"));
#        print addEmailLink($address);
      }
    }
    echo "<hr/>";
  }
} elseif (isset($_POST["deleteattachments"]) && is_array($_POST["deleteattachments"]) && $id) {
  if (ALLOW_ATTACHMENTS) {
    // Delete Attachment button hit...
    $deleteattachments = $_POST["deleteattachments"];
    foreach($deleteattachments as $attid)
    {
      $result = Sql_Query(sprintf("delete from %s where id = %d and messageid = %d",
        $tables["message_attachment"],
        $attid,
        $id));
      print Info($GLOBALS['I18N']->get("removedattachment")." ".$att_cnt);
      // NOTE THAT THIS DOESN'T ACTUALLY DELETE THE ATTACHMENT FROM THE DATABASE, OR
      // FROM THE FILE SYSTEM - IT ONLY REMOVES THE MESSAGE / ATTACHMENT LINK.  THIS
      // SHOULD PROBABLY BE CORRECTED, BUT I (Pete Ness) AM NOT SURE WHAT OTHER IMPACTS
      // THIS MAY HAVE.
      // (My thoughts on this are to check for any orphaned attachment records and if
      //  there are any, to remove it from the disk and then delete it from the database).
    }
  }
}

##############################
# Stacked attributes, processing and calculation
##############################

if (STACKED_ATTRIBUTE_SELECTION) {

# read criteria and parse it into a user query
$num = sprintf('%d',isset($messagedata['numcriteria']) ? $messagedata['numcriteria']: 0);
  #  print '<br/>'.$num . " criteria already defined";
$ls = new WebblerListing($GLOBALS['I18N']->get("existingcriteria"));
$used_attributes = array();
$tc = 0; # table counter
if (!isset($messagedata['criteria_overall_operator'])) {
  $messagedata['criteria_overall_operator'] = '';
}
$mainoperator = $messagedata['criteria_overall_operator'] == "all"? ' and ':' or ';

$subqueries = array();

for ($i = 1; $i<=$num;$i++) {
  if (isset($messagedata[sprintf('criterion%d',$i)])) {
    $crit_data = parseDelimitedData($messagedata[sprintf('criterion%d',$i)]);
  } else {
    $crit_data = array();
  }
  if (!empty($crit_data["attribute"])) {
    array_push($used_attributes,$crit_data["attribute"]);
  
    $ls->addElement('<!--'.$crit_data["attribute"].'-->'.$crit_data["attribute_name"]);
    $ls->addColumn('<!--'.$crit_data["attribute"].'-->'.$crit_data["attribute_name"],$GLOBALS['I18N']->get('operator'),$GLOBALS['I18N']->get($crit_data["operator"]));
    if (isset($messagedata["criteria"][$i])) {
      $attribute = $messagedata["criteria"][$i];
    } else {
      $attribute = '';
    }
    ## fix 6063
    $crit_data["values"] = cleanCommaList($crit_data["values"]);
    $crit_data['displayvalues'] = $crit_data['values'];

    # hmm, rather get is some other way, this is a bit unnecessary
    $type = Sql_Fetch_Row_Query("select type,tablename from {$tables["attribute"]} where id = ".$crit_data["attribute"]);
    $operator = $where_clause = $select_clause = "";
    switch($type[0]) {
      case "checkboxgroup":
        $or_clause = '';
        if ($tc) {
          $where_clause .= " $mainoperator ";
          $select_clause .= " left join $tables[user_attribute] as table$tc on table0.userid = table$tc.userid ";
        } else {
          $select_clause = "table$tc.userid from $tables[user_attribute] as table$tc ";
        }

        $where_clause .= " ( table$tc.attributeid = ".$crit_data["attribute"]." and (";
        if ($crit_data["operator"] == "is") {
          $operator = ' or ';
          $compare = ' > ';
        } else {
          $operator = ' and ';
          $compare = ' <  ';
        }
        foreach (explode(",",$crit_data["values"]) as $val) {
          if ($or_clause != '') {
            $or_clause .= " $operator ";
          }
          $or_clause .= "find_in_set('$val',table$tc.value) $compare 0";
        }
        $where_clause .= $or_clause . ") ) ";
        $subqueries[$i]['query'] = sprintf('select userid from %s as table%d where attributeid = %d
          and %s',$GLOBALS['tables']['user_attribute'],$tc,$crit_data['attribute'],$or_clause);

        $crit_data['displayvalues'] = '';
        $values_req = Sql_Query(sprintf('select * from %slistattr_%s where id in (%s) order by listorder,name',$GLOBALS['table_prefix'],$type[1],$crit_data['values']));
        while ($val = Sql_Fetch_Array($values_req)) {
          $crit_data['displayvalues'] .= $val['name'].', ';
        }
          
        break;
      case "checkbox":
        $value = $crit_data["values"][0];

        if ($tc) {
          $where_clause .= " $mainoperator ";
          $select_clause .= " left join $tables[user_attribute] as table$tc on table0.userid = table$tc.userid ";
        } else {
          $select_clause = "table$tc.userid from $tables[user_attribute] as table$tc";
        }

        $where_clause .= " ( table$tc.attributeid = ".$crit_data["attribute"]." and ";
        if ($crit_data["operator"] == "isnot") {
          $where_clause .= ' not ';
        }
        $valueselect = '';
        if ($value) {
          $valueselect = " length(table$tc.value) and table$tc.value != \"off\" and table$tc.value != \"0\" ";
        } else {
          $valueselect = " table$tc.value = \"\" or table$tc.value = \"0\" or table$tc.value = \"off\" ";
        }

        $where_clause .= '( '.$valueselect . ') ) ';
        $subqueries[$i]['query'] = sprintf('select userid from %s as table%d where attributeid = %d
          and %s',$GLOBALS['tables']['user_attribute'],$tc,$crit_data['attribute'],$valueselect);

        break;
      case "date":
        $date_value = parseDate($crit_data["values"]);
        if (!$date_value) {
          break;
        }
        if (isset($where_clause)) {
          $where_clause .= " $mainoperator ";
          $select_clause .= " left join $tables[user_attribute] as table$tc on table0.userid = table$tc.userid ";
        } else {
          $select_clause = " table$tc.userid from $tables[user_attribute] as table$tc ";
        }

        $where_clause .= ' ( table'.$tc.'.attributeid = '.$crit_data["attribute"].' and table'.$tc.'.value != "" and table'.$tc.'.value ';
        $dateoperator = '';
        switch ($crit_data["operator"]) {
          case "is":
            $where_clause .= ' = "'.$date_value . '" )';$dateoperator = '=';break;
          case "isnot":
            $where_clause .= ' != "'.$date_value . '" )';$dateoperator = '!=';break;
          case "isbefore":
            $where_clause .= ' <= "'.$date_value . '" )';$dateoperator = '<=';break;
          case "isafter":
            $where_clause .= ' >= "'.$date_value . '" )';$dateoperator = '>=';break;
        }
#        $where_clause .= " )";
        $subqueries[$i]['query'] = sprintf('select userid from %s where attributeid = %d and value != "" and value %s "%s" ',$GLOBALS['tables']['user_attribute'],
          $crit_data['attribute'],
          $dateoperator,
          $date_value);

        break;
      default:
        if (isset($where_clause)) {
          $where_clause .= " $mainoperator ";
          $select_clause .= " left join $tables[user_attribute] as table$tc on table0.userid = table$tc.userid ";
        } else {
          $select_clause = " table$tc.userid from $tables[user_attribute] as table$tc ";
        }

        $where_clause .= " ( table$tc.attributeid = ".$crit_data["attribute"]." and table$tc.value ";
        if ($crit_data["operator"] == "isnot") {
          $where_clause .= ' not in (';
        } else {
          $where_clause .= ' in (';
        }
        $where_clause .= cleanCommaList($crit_data["values"]) . ") )";
        $subqueries[$i]['query'] = sprintf('select userid from %s
        where attributeid = %d and
        value %s in (%s) ',$GLOBALS['tables']['user_attribute'],
          $crit_data['attribute'],
          $crit_data["operator"] == "isnot" ? 'not' :'',
          $crit_data["values"]);

        $crit_data['displayvalues'] = '';
        $values_req = Sql_Query(sprintf('select * from %slistattr_%s where id in (%s) order by listorder,name',$GLOBALS['table_prefix'],$type[1],$crit_data['values']));
        while ($val = Sql_Fetch_Array($values_req)) {
          $crit_data['displayvalues'] .= $val['name'].', ';
        }
    }
    $tc++;
    $ls->addColumn('<!--'.$crit_data["attribute"].'-->'.$crit_data["attribute_name"],$GLOBALS['I18N']->get('values'),$crit_data["displayvalues"]);
    $ls->addColumn('<!--'.$crit_data["attribute"].'-->'.$crit_data["attribute_name"],$GLOBALS['I18N']->get('remove'),PageLinkAjax($baseurl.'&amp;action=deleterule&deleterule='.$i,$GLOBALS['I18N']->get("remove")));
  }
}
$existing_criteria = '';
if (sizeof($subqueries)) {
#    $count_query = "select distinct $select_clause where $where_clause";
#    $count_query = addslashes($count_query);
  if (!empty($_GET["calculate"])) {
    ob_end_flush();
   # print "<h3>$count_query</h3>";
    print '<p class="information">'.$GLOBALS['I18N']->get("calculating")." ... </p>";
    flush();
  }
  foreach ($subqueries as $qid => $querydetails) {
    $req = Sql_Query($querydetails['query']);
    $subqueries[$qid]['results'] = array();
    while ($row = Sql_Fetch_Row($req)) {
      array_push($subqueries[$qid]['results'],$row[0]);
    }
  }
  $first = array_shift($subqueries);
  $userids = $first['results'];
  foreach ($subqueries as $subquery) {
    if ($messagedata['criteria_overall_operator'] == 'all') {
      $userids = array_intersect($userids,$subquery['results']);
    } else {
      $userids = array_merge($userids,$subquery['results']);
    }
  }
  $userids = array_unique($userids);
  $num_users = sizeof($userids);

  $count_query = sprintf('select * from %s where id in (%s)',$GLOBALS['tables']['user'],join(', ',$userids));

  if (!empty($_GET["calculate"])) {
    printf('.. '.$GLOBALS['I18N']->get('%d users apply'),$num_users).'</p>';
  }

  if ($id) {
    Sql_query(sprintf('update %s set userselection = "%s" where id = %d',
      $tables["message"],addslashes($count_query),$id));
  }

  if (!isset($_GET['calculate'])) {
    $ls->addButton($GLOBALS['I18N']->get("calculate"),$baseurl.'&amp;tab='.$_GET["tab"].'&amp;calculate=1');
  } else {
    $ls->addButton($GLOBALS['I18N']->get("reload"),$baseurl.'&amp;tab='.$_GET["tab"]);
  }
  $existing_criteria = $ls->display();
} else {
  if ($id) {
    Sql_query(sprintf('update %s set userselection = "" where id = %d',
      $tables["message"],$id));
  }
}


} // end of define STACKED_ATTRIBUTES

##############################
# Stacked attributes, end
##############################

echo $errormsg;
if (!$done) {
  if (ALLOW_ATTACHMENTS) {
    $enctype = 'enctype="multipart/form-data"';
  } else {
    $enctype = '';
  }

  #$baseurl = sprintf('./?page=%s&amp;id=%d',$_GET["page"],$id);
  if ($id) {
    $tabs = new WebblerTabs();
    $tabs->addTab($GLOBALS['I18N']->get("Content"),$baseurl.'&amp;tab=Content');
    if (FORWARD_ALTERNATIVE_CONTENT) {
      $tabs->addTab($GLOBALS['I18N']->get("Forward"),$baseurl.'&amp;tab=Forward');
    }
    $tabs->addTab($GLOBALS['I18N']->get("Format"),$baseurl.'&amp;tab=Format');
    if (ALLOW_ATTACHMENTS) {
      $tabs->addTab($GLOBALS['I18N']->get("Attach"),$baseurl.'&amp;tab=Attach');
    }
    $tabs->addTab($GLOBALS['I18N']->get("Scheduling"),$baseurl.'&amp;tab=Scheduling');
#    if (USE_RSS) {
  #      $tabs->addTab("RSS",$baseurl.'&amp;tab=RSS');
#    }
    $tabs->addTab($GLOBALS['I18N']->get("Criteria"),$baseurl.'&amp;tab=Criteria');
    $tabs->addTab($GLOBALS['I18N']->get("Lists"),$baseurl.'&amp;tab=Lists');
#    $tabs->addTab("Review and Send",$baseurl.'&amp;tab=Review');
    $tabs->addTab($GLOBALS['I18N']->get("Misc"),$baseurl.'&amp;tab=Misc');

    if ($_GET["tab"]) {
      $tabs->setCurrent($GLOBALS['I18N']->get($_GET["tab"]));
    } else {
      $tabs->setCurrent($GLOBALS['I18N']->get("Content"));
    }
    if (defined("WARN_SAVECHANGES")) {
      $tabs->addLinkCode(' class="savechanges" ');
    }
  #$baseurl = sprintf('./?page=%s&amp;id=%d',$_GET["page"],$id);

    ### allow plugins to add tabs
    $plugintabs = array();
    foreach ($GLOBALS['plugins'] as $plugin) {
   #   print $plugin->name;
      $plugintab = $plugin->sendMessageTab($id,$messagedata);
      if ($plugintab) {
        $plugintabname = substr(strip_tags($plugin->sendMessageTabTitle()),0,10);
        $plugintabs[$plugintabname] = $plugintab;
        $tabs->addTab($GLOBALS['I18N']->get($plugintabname),"$baseurl&amp;tab=".urlencode($plugintabname));
      }
    }
    print $tabs->display();
  }

  ?>

  <script language="Javascript" type="text/javascript">
  // some debugging stuff to see what happens
  function checkForm() {
    return true;
    for (var i=0;i < document.sendmessageform.elements.length;i++) {
      alert(document.sendmessageform.elements[i].name+" "+document.sendmessageform.elements[i].value);
    }
    return true;
  }

  // detection of unsaved changes,
  var browser = navigator.appName.substring ( 0, 9 );
  var changed = 0; function haschanged() {changed = 1; }
  function savechanges() { if (changed) { if (confirm("<?php echo str_replace('"','&quot',reverse_htmlentities($GLOBALS['I18N']->get("unsavedchanges")))?>")) return true; else return false; return false;}}
  //'
  var event_number = 0;if (browser=="Microsoft") {  document.onkeydown=haschanged;  document.onchange=haschanged;} else if (browser=="Netscape") {  document.captureEvents(Event.KEYDOWN);  document.captureEvents(Event.CHANGE); document.onkeydown=haschanged;document.onchange=haschanged;}
  function submitform() { document.sendmessageform.submit() }
  </script>
  <?php
  print formStart($enctype . ' name="sendmessageform" class="sendSend" ');
  #print '<form method="post" enctype="multipart/form-data" name="sendmessageform" onSubmit="return checkForm()">';
  print '<input type="hidden" name="workaround_fck_bug" value="1" />';
  print '<input type="hidden" name="followupto" value="" />';

  if ($_GET["page"] == "preparemessage")
    print Help("preparemessage",$GLOBALS['I18N']->get("whatisprepare"));

  if (!defined("IN_WEBBLER")) {
    if (empty($messagedata['from']) && is_object($GLOBALS["admin_auth"]) && $GLOBALS['require_login']) {
      $adminemail = $GLOBALS["admin_auth"]->adminEmail($_SESSION["logindetails"]["id"]);
      if ($adminemail && USE_ADMIN_DETAILS_FOR_MESSAGES) {
        $messagedata['from'] = $GLOBALS["admin_auth"]->adminName($_SESSION["logindetails"]["id"]).' '.$adminemail;
      } else {
        $messagedata['from'] = getConfig("message_from_name") . ' '.getConfig("message_from_address");
      }
    }
  }

  $formatting_content = '<div id="formatcontent">';

  #0013076: different content when forwarding 'to a friend'
  //  value="'.htmlentities($subject,ENT_QUOTES,'UTF-8').'" size="40"></td></tr> --> previous code in line 1032
  //  value="'.htmlentities($from,ENT_QUOTES,'UTF-8').'" size="40"></td></tr> --> previous code in line 1038

  $tmp = '<div id="maincontent">';
  $maincontent = $tmp;
  $forwardcontent = $tmp;

// custom code - start
  $utf8_subject = $messagedata['subject'];
  $utf8_from = $messagedata['from'];
  if (strcasecmp($GLOBALS['strCharSet'], 'utf-8') <> 0) {
     $utf8_subject = iconv($GLOBALS['strCharSet'],'UTF-8',$utf8_subject);
     $utf8_from = iconv($GLOBALS['strCharSet'],'UTF-8',$utf8_from);
  }

  $maincontent .= '
  <div><h3>'.Help("subject").' '.$GLOBALS['I18N']->get("Subject").':</h3></div>
    <div><input type="text" name="subject"
    value="'.htmlentities($utf8_subject,ENT_QUOTES,'UTF-8').'" size="60" /></div>
  <div><h3>'.Help("from").' '.$GLOBALS['I18N']->get("fromline").':</h3></div>
    <div><input type="text" name="from"
   value="'.htmlentities($utf8_from,ENT_QUOTES,'UTF-8').'" size="60" /></div>';
   
   if ($GLOBALS['has_pear_http_request']) {
      $maincontent .= sprintf('
      
      <div id="contentchoice">
      <h3>'.Help("sendmethod").' '.$GLOBALS['I18N']->get("Send your message from").':</h3>
      <input type="radio" name="sendmethod" value="remoteurl" %s />'.$GLOBALS['I18N']->get("a URL (website)").'
      <input type="radio" name="sendmethod" value="inputhere" %s />'.$GLOBALS['I18N']->get("Enter it here").'
      </div>',
        $messagedata['sendmethod'] == 'remoteurl' ? 'checked="checked"':'',
        $messagedata['sendmethod'] == 'inputhere' ? 'checked="checked"':''
      );
      
      $maincontent .= '
      <div id="remoteurl"><h3>'.Help("sendurl").' '.$GLOBALS['I18N']->get("URL").':</h3>
        <div><input type=text name="sendurl" id="remoteurlinput"
       value="'.$messagedata['sendurl'].'" size="60" /></div><span id="remoteurlstatus"></span></div>';
      if (isset($messagedata['sendmethod']) && $messagedata['sendmethod'] != 'remoteurl') {
        $maincontent .= '<script type="text/javascript">$("#remoteurl").hide();</script>';
      }
   }

// custom code - end
  #0013076: different content when forwarding 'to a friend'
  $forwardcontent .= $GLOBALS['I18N']->get("When a user forwards to a friend," .
  " the friend will receive this message instead of the one on the content tab.").
  '<div><h3>'.Help("subject").' '.$GLOBALS['I18N']->get("Subject").':</h3></div>
    <div><input type="text" name="forwardsubject" value="'.htmlentities($forwardsubject,ENT_QUOTES,'UTF-8').'" size="40" /></div>';

  $currentTime = Sql_Fetch_Row_Query('select now()');

  $scheduling_content = '<div id="schedulecontent">';
  $scheduling_content .= '
  <div><h3>'.$GLOBALS['I18N']->get("Time is Based on the Server Time").
    '</h3><br/>'.$GLOBALS['I18N']->get('Current Server Time is').' '.$currentTime[0].'</div>
  <div><h3>'.Help("embargo").' '.$GLOBALS['I18N']->get("embargoeduntil").':</h3></div>
    <div>'.$embargo->showInput("embargo","",$messagedata["embargo"]).'</div>';

  if (USE_REPETITION) {
    $repeatinterval = $messagedata["repeatinterval"];

    $scheduling_content .= '
    <div><h3>'.Help("repetition").' '.$GLOBALS['I18N']->get("repeatevery").':</h3></div>
        <div><select name="repeatinterval">
      <option value="0"';
      if ($repeatinterval == 0) { $scheduling_content .= ' selected="selected"'; }
      $scheduling_content .= '>-- '.$GLOBALS['I18N']->get("norepetition").'</option>
      <option value="60"';
      if ($repeatinterval == 60) { $scheduling_content .= ' selected="selected"'; }
      $scheduling_content .= '>'.$GLOBALS['I18N']->get("hour").'</option>
      <option value="1440"';
      if ($repeatinterval == 1440) { $scheduling_content .= ' selected="selected"'; }
      $scheduling_content .= '>'.$GLOBALS['I18N']->get("day").'</option>
      <option value="10080"';
      if ($repeatinterval == 10080) { $scheduling_content .= ' selected="selected"'; }
      $scheduling_content .= '>'.$GLOBALS['I18N']->get("week").'</option>
      </select>
        </div>
        <div>  <h3>'.$GLOBALS['I18N']->get("repeatuntil").':</h3></div>
        <div>'.$repeatuntil->showInput("repeatuntil","",$messagedata["repeatuntil"]);
      $scheduling_content .= '</div>';
  }
  $formatting_content .= '<div><input type="hidden" name="htmlformatted" value="auto" /></div>';

  $formatting_content .= '
    <div><h3>'.Help("sendformat").' '.$GLOBALS['I18N']->get("sendas").':</h3>
  '.$GLOBALS['I18N']->get("html").' <input type="radio" name="sendformat" value="HTML" ';
    $formatting_content .= $messagedata["sendformat"]=="HTML"?'checked="checked"':'';
    $formatting_content .= '/>
  '.$GLOBALS['I18N']->get("text").' <input type="radio" name="sendformat" value="text" ';
    $formatting_content .= $messagedata["sendformat"]=="text"?'checked="checked"':'';
    $formatting_content .= '/>
  ';

//  0009687: Confusing use of the word "Both", indicating one email with both text and html and not two emails
//  $formatting_content .= $GLOBALS['I18N']->get("textandhtml").' <input type="radio" name="sendformat" value="text and HTML" ';
//  $formatting_content .= $_POST["sendformat"]=="text and HTML" || !isset($_POST["sendformat"]) ?"checked":"";
//  $formatting_content .= '/>';

  foreach ($GLOBALS['plugins'] as $plugin) {
    $plugins_sendformats = $plugin->sendFormats();
    if (is_array($plugins_sendformats) && sizeof($plugins_sendformats)) {
      foreach ($plugins_sendformats as $val => $desc) {
        $val = preg_replace("/\W/",'',strtolower(trim($val)));
        $formatting_content .= sprintf('%s <input type="radio" name="sendformat" value="%s" %s />',
          $desc,$val, $messagedata["sendformat"]==$val?'checked="checked"':'');
      }
    }
  }
  $formatting_content .= '</div>';

  $req = Sql_Query("select id,title from {$tables["template"]} order by listorder");
  if (Sql_Num_Rows($req)) {
    $formatting_content .= '<div><h3>'.Help("usetemplate").' '.$GLOBALS['I18N']->get("usetemplate").':</h3> </div>
      <div><select name="template"><option value="0">-- '.$GLOBALS['I18N']->get("selectone").'</option>';
    $req = Sql_Query("select id,title from {$tables["template"]} order by listorder");
    while ($row = Sql_Fetch_Array($req)) {
      if ($row["title"]) {
        $formatting_content .= sprintf('<option value="%d" %s>%s</option>',$row["id"], $row["id"]==$messagedata["template"]?'selected="selected"':'',$row["title"]);
      }
    }
    $formatting_content .= '</select></div>';
  }
  $formatting_content .= '</div>';

//obsolete, moved to rssmanager plugin
//  if (ENABLE_RSS) {
//    $rss_content .= '<tr><td colspan="2">'.$GLOBALS['I18N']->get("rssintro").'
//    </td></tr>';
//    $rss_content .= '<tr><td colspan="2"><input type="radio" name="rsstemplate" value="none"/>'.$GLOBALS['I18N']->get("none").' ';
//    foreach ($rssfrequencies as $key => $val) {
//      $rss_content .= sprintf('<input type="radio" name="rsstemplate" value="%s" %s/>%s ',$key,$_POST["rsstemplate"] == $key ? "checked":"",$val);
//    }
//    $rss_content .= '</td></tr>';
//  }

  #0013076: different content when forwarding 'to a friend'
  $tmp = '<div id="messagecontent"><h3>'.Help("message").' '.$GLOBALS['I18N']->get("message").'.</h3> ';
  $maincontent .= $tmp;
  $forwardcontent .= $tmp;

  if ($usefck) {
    $oFCKeditor = new FCKeditor('message') ;
    $oFCKeditor->BasePath = './FCKeditor/';
    //$oFCKeditor->ToolbarSet = 'Accessibility' ;
    $oFCKeditor->ToolbarSet = 'Default' ;
    $oFCKeditor->Value = stripslashes($messagedata["message"]);
    $w = getConfig("fckeditor_width");
    $h = getConfig("fckeditor_height");
    if ($_SESSION["fckeditor_height"]) {
      $h = sprintf('%d',$_SESSION["fckeditor_height"]);
    }

    # version 1.4
#    $maincontent .= $oFCKeditor->ReturnFCKeditor( 'message', $w.'px', $h.'px' ) ;

    # for version 2.0
    if ($h < 400) {
      $h = 400;
    }
    $oFCKeditor->Height = $h;
    $oFCKeditor->Width = $w;
    
    $maincontent .= '<div>'.$oFCKeditor->CreateHtml() .'</div>';

  } elseif ($useTinyMCE) {

  $tinyMCE_path = TINYMCEPATH;
  $tinyMCE_lang = TINYMCELANG;
  $tinyMCE_theme = TINYMCETHEME;
  $tinyMCE_opts = TINYMCEOPTS;

  $maincontent .= "
  
      <script language='javascript' type='text/javascript' src='{$tinyMCE_path}'></script>\n"
        ."<script language='javascript' type='text/javascript'>\n"
        ."   tinyMCE.init({\n"
        ."      mode : 'exact',\n"
        ."    elements : 'message',\n"
        ."    language : '{$tinyMCE_lang}',\n"
        ."    theme : '{$tinyMCE_theme}'\n"
        ."    {$tinyMCE_opts}\n"
        ."   });\n"
        ."</script>\n"
        ."<textarea name='message' id='message' cols='65' rows='20'>{$messagedata['message']}</textarea>";

  } else {
    $maincontent    .= ' 
      <textarea name="message" cols="65" rows="20">'.htmlspecialchars($messagedata["message"]).'</textarea>';
  }

  #0013076: different content when forwarding 'to a friend'
  $forwardcontent .= '<textarea name="forwardmessage" cols="65" rows="20">'.htmlspecialchars($messagedata['forwardmessage']).'</textarea>';

  #0013076: different content when forwarding 'to a friend'
  $tmp = '
  </div>
  </div> <!-- end of message content -->
  ';
  
  if (isset($messagedata['sendmethod']) && $messagedata['sendmethod'] != 'inputhere') {
    $maincontent .= '<script type="text/javascript">$("#messagecontent").hide()</script>';
  }
  $maincontent .= $tmp;
  $forwardcontent .= $tmp;

  if (USE_MANUAL_TEXT_PART) {
  $maincontent .= '<div>
    <h3>'.Help("plaintextversion").' '.$GLOBALS['I18N']->get("plaintextversion").'</h3>
    <textarea name="textmessage" cols="65" rows="20">'.$messagedata["textmessage"].'</textarea>
  </div>';
  }

  #0013076: different content when forwarding 'to a friend'
  $maincontent .= '<div><h3>'.Help("footer").' '.$GLOBALS['I18N']->get("messagefooter").'.</h3> 
    <p class="information">'.
    $GLOBALS['I18N']->get("messagefooterexplanation1").'</p>
    <p class="information">'.
    $GLOBALS['I18N']->get("messagefooterexplanation2").'</p>
    <p class="information">'.
    $GLOBALS['I18N']->get("messagefooterexplanation3").'</p>
   </div>
  <div><textarea name="footer" cols="65" rows="5">'.$messagedata['footer'].'</textarea></div>';
  $forwardcontent .= '<div><h3>'.$GLOBALS['I18N']->get("forwardfooter").'.</h3> <br/>
    '.$GLOBALS['I18N']->get("messageforwardfooterexplanation").'<br/>'.
  '.</div>
  <div><textarea name="forwardfooter" cols="65" rows="5">'.$messagedata['forwardfooter'].'</textarea></div>';

  if (ALLOW_ATTACHMENTS) {
    // If we have a message id saved, we want to query the attachments that are associated with this
    // message and display that (and allow deletion of!)

    $att_content = '<div id="sendAttachment"><div>'.Help("attachments").' <h3>'.$GLOBALS['I18N']->get("addattachments").'</h3> </div>';
    $att_content .= '<div>
      '.$GLOBALS['I18N']->get("uploadlimits").':<br/>
      '.$GLOBALS['I18N']->get("maxtotaldata").': '.ini_get("post_max_size").'<br/>
      '.$GLOBALS['I18N']->get("maxfileupload").': '.ini_get("upload_max_filesize").'</div>';

    if ($id) {
      $result = Sql_Query(sprintf("Select Att.id, Att.filename, Att.remotefile, Att.mimetype, Att.description, Att.size, MsgAtt.id linkid".
                        " from %s Att, %s MsgAtt where Att.id = MsgAtt.attachmentid and MsgAtt.messageid = %d",
        $tables["attachment"],
        $tables["message_attachment"],
        $id));


      $ls = new WebblerListing($GLOBALS['I18N']->get('currentattachments'));

      while ($row = Sql_fetch_array($result)) {
        $ls->addElement($row["id"]);
        $ls->addColumn($row["id"],$GLOBALS['I18N']->get('filename'),$row["remotefile"]);
        $ls->addColumn($row["id"],$GLOBALS['I18N']->get('desc'),$row["description"]);
        $ls->addColumn($row["id"],$GLOBALS['I18N']->get('size'),$row["size"]);
        $phys_file = $GLOBALS["attachment_repository"]."/".$row["filename"];
        if (is_file($phys_file) && filesize($phys_file)) {
          $ls->addColumn($row["id"],$GLOBALS['I18N']->get('file'),$GLOBALS["img_tick"]);
        } else {
          $ls->addColumn($row["id"],$GLOBALS['I18N']->get('file'),$GLOBALS["img_cross"]);
        }
        $ls->addColumn($row["id"],$GLOBALS['I18N']->get('del'),sprintf('<input type="checkbox" name="deleteattachments[]" value="%s"/>',$row["linkid"]));
      }
      $ls->addButton($GLOBALS['I18N']->get('delchecked'),"javascript:document.sendmessageform.submit()");
      $att_content .= '<div>'.$ls->display().'</div>';
    }
    for ($att_cnt = 1;$att_cnt <= NUMATTACHMENTS;$att_cnt++) {
      $att_content .= sprintf  ('<div>%s</div><div><input type="file" name="attachment%d"/>&nbsp;&nbsp;<input class="submit" type="submit" name="save" value="%s"/></div>',$GLOBALS['I18N']->get('newattachment'),$att_cnt,$GLOBALS['I18N']->get('addandsave'));
      if (FILESYSTEM_ATTACHMENTS) {
        $att_content .= sprintf('<div><b>%s</b> %s:</div><div><input type="text" name="localattachment%d" size="50"/></div>',$GLOBALS['I18N']->get('or'),$GLOBALS['I18N']->get('pathtofile'),$att_cnt,$att_cnt);
      }
      $att_content .= sprintf ('<div>%s:</div>
        <div><textarea name="attachment%d_description" cols="65" rows="3" wrap="virtual"></textarea></div>',$GLOBALS['I18N']->get('attachmentdescription'),$att_cnt);
    }
    $att_content .= '</div>';
    # $shader = new WebblerShader("Attachments");
    # $shader->addContent($att_content);
    # $shader->initialstate = 'closed';
    # print $shader->display();
  }

  // Load the email address for the admin user so we can use that as the default value in the testtarget field
  # @@@ this only works with phplist authentication, needs to be abstracted
  if (!isset($messagedata["testtarget"])) {
    $res = Sql_Query(sprintf("Select email from %s where id = %d", $tables["admin"], $_SESSION["logindetails"]["id"]));
    $admin_details = Sql_Fetch_Array($res);

    $messagedata["testtarget"] = $admin_details["email"];
  }
  // if there isn't one, load the developer one, just being lazy here :-)
  if (empty($messagedata["testtarget"]) && isset($GLOBALS["developer_email"])) {
    $messagedata["testtarget"] = $GLOBALS["developer_email"];
  }

  // Display the HTML for the "Send Test" button, and the input field for the email addresses
  $sendtest_content = sprintf('<div class="sendTest"><div>
    <input class="submit" type="submit" name="sendtest" value="%s"/>%s: </div>
    <div><input type="text" name="testtarget" size="40" value="'.$messagedata["testtarget"].'"/><br />%s
    </div></div>',
    $GLOBALS['I18N']->get('sendtestmessage'),$GLOBALS['I18N']->get('toemailaddresses'),
    $GLOBALS['I18N']->get('sendtestexplain'));

  $criteria_content = $GLOBALS['I18N']->get('criteriaexplanation').'
  <div class="sendCriteria">';

  $any = 0;
  for ($i=1;$i<=NUMCRITERIAS;$i++) {
    $criteria_content .= sprintf('<div><h3>%s %d</h3></div>
    <div>%s <input type="checkbox" name="use[%d]"/></div>',$GLOBALS['I18N']->get('criterion'),$i,
    $GLOBALS['I18N']->get('usethisone'),$i);
    $attributes_request = Sql_Query("select * from $tables[attribute]");
    while ($attribute = Sql_Fetch_array($attributes_request)) {
      $criteria_content .= "\n\n";
      $criteria_cnt = sprintf('<input type="hidden" name="attrtype[%d]" value="%s"/>',
        $attribute["id"],$attribute["type"]);
/*       $criteria_content .= sprintf('<input type="hidden" name="attrtype[%d]" value="%s"/>', */
/*         $attribute["id"],$attribute["type"]); */
      switch ($attribute["type"]) {
        case "checkbox":
          $any = 1;
          $criteria_content .= sprintf ('<div>'.$criteria_cnt.'<input type="radio" name="criteria[%d]" value="%d"/>
             %s</div><div><b>%s</b></div><div><select name="attr%d%d[]">
                  <option value="0">Not checked</option>
                  <option value="1">Checked</option></select></div>',
                  $i,$attribute["id"],
                  $attribute["name"],$GLOBALS['I18N']->get('is'),$attribute["id"],$i);
          break;
        case "select":
        case "radio":
        case "checkboxgroup":
          $some = 0;
          $thisone = "";
          $values_request = Sql_Query("select * from $table_prefix"."listattr_".$attribute["tablename"]);
          $thisone .= sprintf ('<div>'.$criteria_cnt.'<input type="radio" name="criteria[%d]" value="%d"/> %s</div>
                  <div><b>%s</b></div><div><select name="attr%d%d[]" size="4" multiple="multiple">',
                  $i,$attribute["id"],strip_tags($attribute["name"]),$GLOBALS['I18N']->get('is'),$attribute["id"],$i);
          while ($value = Sql_Fetch_array($values_request)) {
            if($value["name"]) {
              $some = 1;
              $thisone .= sprintf ('<option value="%d">%s</option>',$value["id"],$value["name"]);
            }
          }
          $thisone .= "</select></div>";
          if ($some)
            $criteria_content .= $thisone;
          $any = $any || $some;
          break;
        default:
          $criteria_content .= "\n<!-- error: huh, unknown type ".$attribute["type"]." -->\n";
      }
    }
  }

  if (!$any) {
    $criteria_content = '<p class="information">'.$GLOBALS['I18N']->get('nocriteria').'</p>';
  } else {
    $criteria_content .= '</div>';
  #  $shader = new WebblerShader("Message Criteria");
  #  $shader->addContent($criteria_content.'</table>');
  #  $shader->hide();
  #  print $shader->display();
  }
						      
  ##############################
  # Stacked attributes, display
  ##############################

  if (STACKED_ATTRIBUTE_SELECTION) {

  /* new criteria content system */
  # list existing defined ones:
  # find out how many there are

  $att_js = '
  <script language="javascript" type="text/javascript">
    var values = Array();
    var operators = Array();
    var value_divs = Array();
    var value_default = Array();
  ';
  if (sizeof($used_attributes)) {
    $already_used = ' and id not in ('.join(',',$used_attributes).')';
  } else {
    $already_used = "";
  }
  $att_drop = '';
  $attreq = Sql_Query(sprintf('select * from %s where type in ("select","radio","date","checkboxgroup","checkbox") %s',$tables["attribute"],$already_used));
  while ($att = Sql_Fetch_array($attreq)) {
    if($att["name"]){
      $att_drop .= sprintf('<option value="%d" %s>%s</option>',
			   $att["id"],"",$att["name"]);
    }
    $num = Sql_Affected_Rows();
    switch ($att["type"]) {
      case "select":case "radio":case "checkboxgroup":
        $att_js .= sprintf('value_divs[%d] = "criteria_values_select";'."\n",$att["id"]);
        $att_js .= sprintf('value_default[%d] = "";'."\n",$att["id"]);
        $value_req = Sql_Query(sprintf('select * from %s order by listorder,name',
          $GLOBALS["table_prefix"]."listattr_".$att["tablename"]));
        $num = Sql_Num_Rows($value_req);
        $att_js .= sprintf('values[%d] = new Array(%d);'."\n",$att["id"],$num+1);
        #$att_js .= sprintf('values[%d][0] =  new Option("[choose]","0",false,true);'."\n",$att["id"]);
        $c = 0;
        while ($value = Sql_Fetch_Array($value_req)) {
          $att_js .= sprintf('values[%d][%d] =  new Option("%s","%d",false,false);'."\n",$att["id"],
            $c,$value["name"],$value["id"]);
          $c++;
        }
        $att_js .= sprintf('operators[%d] = new Array(2);'."\n",$att["id"]);
        $att_js .= sprintf('operators[%d][0] =  new Option("%s","is",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('is'));
        $att_js .= sprintf('operators[%d][1] =  new Option("%s","isnot",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('isnot'));
        break;
      case "checkbox":
        $att_js .= sprintf('value_divs[%d] = "criteria_values_select";'."\n",$att["id"]);
        $att_js .= sprintf('value_default[%d] = "";'."\n",$att["id"]);
        $att_js .= sprintf('values[%d] = new Array(%d);'."\n",$att["id"],2);
        $att_js .= sprintf('values[%d][0] =  new Option("%s",0,false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('unchecked'));
        $att_js .= sprintf('values[%d][1] =  new Option("%s",1,false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('checked'));
        $att_js .= sprintf('operators[%d] = new Array(2);'."\n",$att["id"]);
        $att_js .= sprintf('operators[%d][0] =  new Option("%s","is",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('is'));
        $att_js .= sprintf('operators[%d][1] =  new Option("%s","isnot",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('isnot'));
        break;
      case "date":
        $att_js .= sprintf('value_divs[%d] = "criteria_values_text";'."\n",$att["id"]);
        $att_js .= sprintf('value_default[%d] = "%s";'."\n",$att["id"],$GLOBALS['I18N']->get('dd-mm-yyyy'));
        $att_js .= sprintf('values[%d] = new Array(%d);'."\n",$att["id"],1);
        $att_js .= sprintf('values[%d][%d] =  new Option("%s","%d",false,false);'."\n",$att["id"],$c,
          "Date","dd-mm-yyyy"); # just to avoid javascript errors, not actually used
        $att_js .= sprintf('operators[%d] = new Array(4);'."\n",$att["id"]);
        $att_js .= sprintf('operators[%d][0] =  new Option("%s","is",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('is'));
        $att_js .= sprintf('operators[%d][1] =  new Option("%s","isnot",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('isnot'));
        $att_js .= sprintf('operators[%d][2] =  new Option("%s","isbefore",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('isbefore'));
        $att_js .= sprintf('operators[%d][3] =  new Option("%s","isafter",false,true);'."\n",$att["id"],$GLOBALS['I18N']->get('isafter'));
    }
  }
  $att_js .= '

  var browser = navigator.appName.substring ( 0, 9 );
  var warned = browser != "Microsoft";

  function findEl(name) {
    var div;
    if (document.getElementById){
      div = document.getElementById(name);
    } else if (document.all){
      div = document.all[name];
    }
    return div;
  }

  function changeDropDowns() {
    var choice = document.sendmessageform.criteria_attribute.options[document.sendmessageform.criteria_attribute.selectedIndex].value;
    if (choice == "")
      return;
    if (!warned) {
      alert("'.$GLOBALS['I18N']->get('buggywithie').'");
      warned = 1;
    }
    var value_el_select = findEl("criteria_values_select");
    var value_el_text = findEl("criteria_values_text");
    for (i=0;i<value_el_select.length;) {
      value_el_select.options[i] = null;
    }
    for (i=0;i<values[choice].length;i++) {
      value_el_select.options[i] = values[choice][i];
    }
    value_el_select.selectedIndex = 0;
    value_el_text.value = value_default[choice];

    for (i=0;i<document.sendmessageform.criteria_operator.length;) {
      document.sendmessageform.criteria_operator.options[i] = null;
    }
    for (i=0;i<operators[choice].length;i++) {
      document.sendmessageform.criteria_operator.options[i] = operators[choice][i];
    }
    document.sendmessageform.criteria_operator.selectedIndex = 0;
    var div1 = findEl("criteria_values_select");
    var div2 = findEl("criteria_values_text");
    var div3 = findEl(value_divs[choice]);
    div1.style.visibility = "hidden";
    div2.style.visibility = "hidden";
    div3.style.visibility = "visible";

  }
  </script>

  ';

  $att_drop = '<select name="criteria_attribute" onchange="changeDropDowns()" class="criteria_element" >';
  $att_drop .= '<option value="">['.$GLOBALS['I18N']->get('selectattribute').']</option>';
  $att_names = '';# to remember them later
  $attreq = Sql_Query(sprintf('select * from %s where type in ("select","radio","date","checkboxgroup","checkbox") %s',$tables["attribute"],$already_used));
  while ($att = Sql_Fetch_array($attreq)) {
    $att_drop .= sprintf('<option value="%d" %s>%s</option>',
      $att["id"],"",substr(stripslashes($att["name"]),0,30).' ('.$GLOBALS['I18N']->get($att["type"]).')');
    $att_names .= sprintf('<input type="hidden" name="attribute_names[%d]" value="%s"/>',$att["id"],stripslashes($att["name"]));
  }
  $att_drop .= '</select>'.$att_names;

  $operator_drop = '
    <select name="criteria_operator" class="criteria_element" >
    <option value="is">'.$GLOBALS['I18N']->get('is').'</option>
    <option value="isnot">'.$GLOBALS['I18N']->get('isnot').'</option>
    <option value="isbefore">'.$GLOBALS['I18N']->get('isbefore').'</option>
    <option value="isafter">'.$GLOBALS['I18N']->get('isafter').'</option>
  </select>
  ';

  $values_drop = '<span id="values_span" class="values_span">';
  $values_drop .= '<input class="criteria_element" name="criteria_values[]" id="criteria_values_text" size="15" type="text"/>';
#  $values_drop .= '</span>';
#  $values_drop .= '<span id="values_select">';
  $values_drop .= '<select class="criteria_element" name="criteria_values[]" id="criteria_values_select" multiple="multiple" size="10"></select>';
  $values_drop .= '</span>';

  $existing_overall_operator = $messagedata['criteria_overall_operator'] == "any" ? "any":"all";
  $criteria_overall_operator =
    sprintf('%s <input type="radio" name="criteria_overall_operator" value="all" %s/>
      %s <input type="radio" name="criteria_overall_operator" value="any" %s/>',
      $GLOBALS['I18N']->get('matchallrules'),
      $existing_overall_operator == "all"? 'checked="checked"':'',
      $GLOBALS['I18N']->get('matchanyrules'),
      $existing_overall_operator == "any"? 'checked="checked"':'');

  $criteria_content = $criteria_overall_operator.$existing_criteria.$att_js.
  '<div class="criteria_container">'.
  '<span class="criteria_element">'.$att_drop.'</span>'.
  '<span class="criteria_element">'.$operator_drop.'</span>'.
  '<span class="criteria_element">'.$values_drop.'</span>'.
  '<span class="criteria_element"><p class="submit"><input type="submit" name="save" value="'.$GLOBALS['I18N']->get('addcriterion').'"/></p></span>';
  '</div>';
  } // end of if (STACKED_ATTRIBUTE_SELECTION)

  ##############################
  # Stacked attributes, display end
  ##############################

  # notification of progress of message sending
  # defaulting to admin_details['email'] gives the wrong impression that this is the
  # value in the database, so it is better to leave that empty instead
  $notify_start = isset($messagedata['notify_start'])?$messagedata['notify_start']:'';#$admin_details['email'];
  $notify_end = isset($messagedata['notify_end'])?$messagedata['notify_end']:'';#$admin_details['email'];

  $misc_content = sprintf('
    <div class="sendNotify">
    <div>%s<br/>%s</div><div><input type="text" name="notify_start" value="%s" size="35"/></div>
    <div>%s<br/>%s</div><div><input type="text" name="notify_end" value="%s" size="35"/></div>
    </div>',
    $GLOBALS['I18N']->get('email to alert when sending of this message starts'),
    $GLOBALS['I18N']->get('separate multiple with a comma'),$notify_start,
    $GLOBALS['I18N']->get('email to alert when sending of this message has finished'),
    $GLOBALS['I18N']->get('separate multiple with a comma'),$notify_end);
  $show_lists = 0;

  if (!empty($messagedata['htmlsize'])) {
    $misc_content .= $GLOBALS['I18N']->get('Estimated size of HTML email').': '.formatBytes($messagedata['htmlsize']).'<br/>';
  }
  if (!empty($messagedata['textsize'])) {
    $misc_content .= $GLOBALS['I18N']->get('Estimated size of text email').': '.formatBytes($messagedata['textsize']).'<br/>';
  }

  if (!empty($messagedata['textsize']) || !empty($messagedata['htmlsize'])) {
    if (is_array($messagedata['targetlist']) && sizeof($messagedata['targetlist'])) {
      $lists = $messagedata['targetlist'];
      if (isset($messagedata['excludelist'])) {
        $excludelists = $messagedata['excludelist'];
      } else {
        $excludelists = array();
      }

      if (!empty($lists['all']) || !empty($lists['allactive'])) {
        $allactive = isset($lists['allactive']);
        $all = isset($lists['all']);
        $req = Sql_Query(sprintf('select id,active from %s %s',$GLOBALS['tables']['list'],$subselect));
        $lists = array();
        while ($row = Sql_Fetch_Row($req)) {
          if (($allactive && $row[1]) || $all) {
            $lists[$row[0]] = $row[0];
          }
        }
      }
      unset($lists['all']);
      unset($lists['allactive']);
      if (isset($messagedata['excludelist']) && is_array($messagedata['excludelist'])) {
        $exclude = sprintf(' and listuser.listid not in (%s)',join(',',$messagedata['excludelist']));
      } else {
        $exclude = '';
      }

      $htmlcnt = Sql_Fetch_Row_Query(sprintf('select count(distinct userid) from %s listuser,%s u where u.htmlemail and u.id = listuser.userid and listuser.listid in (%s) %s',
        $GLOBALS['tables']['listuser'],$GLOBALS['tables']['user'],join(',',array_keys($lists)),$exclude),1);
      $textcnt = Sql_Fetch_Row_Query(sprintf('select count(distinct userid) from %s listuser,%s user where !user.htmlemail and user.id = listuser.userid and listuser.listid in (%s) %s',
        $GLOBALS['tables']['listuser'],$GLOBALS['tables']['user'],join(',',array_keys($lists)),$exclude),1);
      if ($htmlcnt[0] || $textcnt[0]) {
        if (!isset($messagedata['textsize'])) $messagedata['textsize'] = 0;
        if (!isset($messagedata['htmlsize'])) $messagedata['htmlsize'] = 0;

        $misc_content .= $GLOBALS['I18N']->get('Estimated size of mailout').': '.formatBytes($htmlcnt[0] * $messagedata['htmlsize'] + $textcnt[0] * $messagedata['textsize']).'<br/>';
        ## remember this to see how well the estimate was
        Sql_Query(sprintf('replace into %s set name = "estimatedsize",id=%d,data = "%s"',$GLOBALS['tables']['messagedata'],$id,$htmlcnt[0] * $messagedata['htmlsize'] + $textcnt[0] * $messagedata['textsize']));
        $misc_content .= sprintf($GLOBALS['I18N']->get('About %d users to receive HTML and %s users to receive text version of email'),$htmlcnt[0],$textcnt[0]).'<br/>';
        Sql_Query(sprintf('replace into %s set name = "estimatedhtmlusers",id=%d,data = "%s"',$GLOBALS['tables']['messagedata'],$id,$htmlcnt[0]));
        Sql_Query(sprintf('replace into %s set name = "estimatedtextusers",id=%d,data = "%s"',$GLOBALS['tables']['messagedata'],$id,$textcnt[0]));
      }
    }
  }

//  print $tabs->display();
  switch ($_GET["tab"]) {
    case "Attach": print $att_content; break;
    case "Criteria": print $criteria_content; break;
    case "Format": print $formatting_content;break;
    case "Scheduling": print $scheduling_content;break;
//    case "RSS": print $rss_content;break;            //Obsolete by rssmanager plugin
    case "Lists": $show_lists = 1;break;
    case "Review": print $review_content; break;
    case "Misc": print $misc_content; break;
    case "Forward": print $forwardcontent; break;
    default:
      $isplugin = 0;
      foreach ($plugintabs as $tabname => $tabcontent) {
        if ($_GET['tab'] == $tabname) {
          print $tabcontent;
          $isplugin = 1;
        }
      }
      if (!$isplugin) {
        print $maincontent;
      }
      break;
  }
}
print $sendtest_content;

if (empty($messagedata["status"])) {
  $savecaption = $GLOBALS['I18N']->get('saveasdraft');
} else {
  $savecaption = $GLOBALS['I18N']->get('savechanges');#"Save &quot;".$_POST["status"]."&quot; message edits";

}

## if all is there, we can enable the send button
$allReady = true;
print '<script type="text/javascript">
$("#addtoqueue").html("");
</script>';

$testValue = trim($messagedata['subject']);
if (empty($testValue)) {
  $allReady = false;
  print '<script type="text/javascript">
  $("#addtoqueue").append("<div>subject missing</div>");
  </script>';
}
$testValue = trim($messagedata['message']);
if (empty($testValue)) {
  $allReady = false;
  print '<script type="text/javascript">
  $("#addtoqueue").append("<div>message content missing</div>");
  </script>';
} 
$testValue = trim($messagedata['from']);
if (empty($testValue)) {
  $allReady = false;
  print '<script type="text/javascript">
  $("#addtoqueue").append("<div>From missing</div>");
  </script>';
} 
if (empty($messagedata['targetlist'])) {
  $allReady = false;
  print '<script type="text/javascript">
  $("#addtoqueue").append("<div>target missing</div>");
  </script>';
} 
if ($allReady) {
  print '<script type="text/javascript">
  $("#addtoqueue").html(\'<input class="submit" type="submit" name="send" id="addtoqueuebutton" value="'.$GLOBALS['I18N']->get('sendmessage').'" />\');
  </script>';
}
    



print '<div class="sendSubmit"><div>
    <input class="submit" type="submit" name="save" value="'.$savecaption.'"/>
    </div></div>
    <input type="hidden" name="id" value="'.$id.'"/>
    <input type="hidden" name="status" value="'.$messagedata["status"].'"/>
';

?>
