<?
require_once "accesscheck.php";

if (file_exists("FCKeditor/fckeditor.php") && USEFCK) {
	include("./FCKeditor/fckeditor.php") ;
  $usefck = 1;
} else {
	$usefck = 0;
}
include $GLOBALS["coderoot"] . "date.php";
$embargo = new date("embargo");
$embargo->useTime = true;
$repeatuntil = new date("repeatuntil");
$repeatuntil->useTime = true;

echo '<script language="Javascript" src="js/jslib.js" type="text/javascript"></script><hr><p>';

# check the criterias, one attribute can only exist once
if ($send) {
  $used_attributes = array();
  for ($i=1;$i<=NUMCRITERIAS;$i++) {
    if (isset($_POST["use"][$i])) {
      $attribute = $_POST["criteria"][$i];
      if (!in_array($attribute,$used_attributes))
        array_push($used_attributes,$attribute);
      else
        $duplicate_attribute = 1;
    }
  }
}

if ($_POST["htmlformatted"] == "auto")
	$htmlformatted = strip_tags($_POST["message"]) != $_POST["message"];
else 
	$htmlformatted = $_POST["htmlformatted"];
  
# sanitise the header fields, what else do we need to check on?
if (preg_match("/[\n|\r]/",$_POST["from"])) {
	$from = "";
} else {
	$from = $_POST["from"];
}
if (preg_match("/[\n|\r]/",$_POST["subject"])) {
	$subject = "";
} else {
	$subject = $_POST["subject"];
}

if ($_POST["send"] && $subject && $_POST["message"] && $from && !$duplicate_attribute) {
	if (is_array($_POST["list"])) {
  	$status = "submitted";
  } else {
  	$status = "prepared";
  }

	if (ENABLE_RSS && $_POST["rsstemplate"]) {
  	# mark previous RSS templates with this frequency and owner as sent
    # this should actually be much more complex than this:
    # templates should be allowed by list and therefore a subset of lists, but
    # for now we leave it like this
    # the trouble is that this may duplicate RSS messages to users, because
    # it can cause multiple template for lists. The user_rss should handle that, but it is
    # not guaranteed which message will be used.
  	Sql_Query(sprintf('update %s set status = "sent" where rsstemplate = "%s" and owner = %d',
			$tables["message"],$_POST["rsstemplate"],$_SESSION["logindetails"]["id"]));
	}

  $query = sprintf('insert into %s (subject,fromfield,tofield,
  		replyto,embargo,repeat,repeatuntil,message,footer,status,entered,
      htmlformatted,sendformat,template,rsstemplate,owner) values(
      "%s","%s","%s","%s","%s",%d,"%s","%s","%s","%s",%s,%d,"%s",%d,"%s",%d)',
      $tables["message"],
      addslashes($subject),
      addslashes($from),
      addslashes($_POST["tofield"]),
      addslashes($_POST["replyto"]),
			$embargo->getDate()." ".$embargo->getTime().":00",
      $_POST["repeatinterval"],
			$repeatuntil->getDate()." ".$repeatuntil->getTime().":00",
      addslashes($_POST["message"]),
      addslashes($_POST["footer"]),
      $status,"now()",
      $htmlformatted,
      $_POST["sendformat"],
      $_POST["template"],$_POST["rsstemplate"],$_SESSION["logindetails"]['id']
);
	if (!$htmlformatted && strip_tags($_POST["message"]) != $_POST["message"])
  	$msg = '<span class="error">Warning: You indicated the content was not HTML, but there were some HTML tags in it. This may cause errors</span>';

  $result = Sql_query($query);
  $messageid = Sql_insert_id();

  if (is_array($_POST["list"])) {
    if ($_POST["list"]["all"]) {
      $res = Sql_query("select * from $tables[list] $subselect");
      while($row = Sql_fetch_array($res)) {
        $listid = $row["id"];
        if ($row["active"]) {
          $result = Sql_query("insert into $tables[listmessage] (messageid,listid,entered) values($messageid,$listid,now())");
        }
      }
    } else {
      while(list($key,$val)= each($_POST["list"])) {
        if ($val == "signup")
          $result = Sql_query("insert into $tables[listmessage] (messageid,listid,entered) values($messageid,$key,now())");
      }
    }
  } else {
    # mark this message as listmessage for list 0
    $result = Sql_query("insert into $tables[listmessage] (messageid,listid,entered) values($messageid,0,now())");
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

  # check the criterias, create the selection query
  $used_tables = array();
  for ($i=1;$i<=NUMCRITERIAS;$i++) {
    if (isset($_POST["use"][$i])) {
      $attribute = $_POST["criteria"][$i];
      $type = $_POST["attrtype"][$attribute];
      switch($type) {
      	case "checkboxgroup":
          $values = "attr$attribute$i";
          if (isset($where_clause)) {
            $where_clause .= " and ";
            $select_clause .= " left join $tables[user_attribute] as table$i on table$first.userid = table$i.userid ";
          } else {
            $select_clause = "table$i.userid from $tables[user_attribute] as table$i ";
            $first = $i;
          }

          $where_clause .= "table$i.attributeid = $attribute and (";
          if (is_array($_POST[$values])) {
            foreach ($_POST[$values] as $val) {
              if (isset($or_clause)) {
                $or_clause .= " or ";
              }
              $or_clause .= "find_in_set('$val',table$i.value) > 0";
            }
          }
          $where_clause .= $or_clause . ")";
          break;
        case "checkbox":
          $values = "attr$attribute$i";
          $value = $_POST[$values][0];

          if (isset($where_clause)) {
            $where_clause .= " and ";
            $select_clause .= " left join $tables[user_attribute] as table$i on table$first.userid = table$i.userid ";
          } else {
            $select_clause = "table$i.userid from $tables[user_attribute] as table$i ";
            $first = $i;
          }

          $where_clause .= "table$i.attributeid = $attribute and ";
          if ($value) {
          	$where_clause .= "( length(table$i.value) and table$i.value != \"off\" and table$i.value != \"0\") ";
          } else {
          	$where_clause .= "( table$i.value = \"\" or table$i.value = \"0\" or table$i.value = \"off\") ";
          }
          break;
       	default:
          $values = "attr$attribute$i";
          if (isset($where_clause)) {
            $where_clause .= " and ";
            $select_clause .= " left join $tables[user_attribute] as table$i on table$first.userid = table$i.userid ";
          } else {
            $select_clause = "table$i.userid from $tables[user_attribute] as table$i ";
            $first = $i;
          }

          $where_clause .= "table$i.attributeid = $attribute and table$i.value in (";
          $list = array();
          if (is_array($_POST[$values])) {
            while (list($key,$val) = each ($_POST[$values]))
              array_push($list,$val);
          }
          $where_clause .= join(", ",$list) . ")";
      }
    }
  }

  # if no selection was made, use all
  if (!isset($where_clause)) {
    $count_query = addslashes("select distinct userid from $tables[user_attribute]");
  } else {
    $count_query = addslashes("select $select_clause where $where_clause");
  }

  Sql_query("update $tables[message] set userselection = \"$count_query\" where id = $messageid");
 # commented, because this could take too long
 # Sql_Query($count_query);
 # $num = Sql_Affected_rows();

  if (ALLOW_ATTACHMENTS) {
    for ($att_cnt = 1;$att_cnt <= NUMATTACHMENTS;$att_cnt++) {
    	$fieldname = "attachment".$att_cnt;
      $tmpfile = $_FILES[$fieldname]['tmp_name'];
      $remotename = $_FILES[$fieldname]["name"];
      $type = $_FILES[$fieldname]["type"];
      if (strlen($_POST[$type]) > 255)
      	print Warn("Mime Type is longer than 255 characters, this is trouble");
      $description = $_POST[$fieldname."_description"];
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
          $fd = fopen( $attachment_repository."/".$newfile, "w" );
          fwrite( $fd, $contents );
          fclose( $fd );
          Sql_query(sprintf('insert into %s (filename,remotefile,mimetype,description,size) values("%s","%s","%s","%s",%d)',
          $tables["attachment"],
          basename($newfile),$remotename,$type,$description,$file_size)
          );
          $attachmentid = Sql_Insert_id();
          Sql_query(sprintf('insert into %s (messageid,attachmentid) values(%d,%d)',
          $tables["message_attachment"],$messageid,$attachmentid));
          print Info("Added attachment ".$att_cnt);
        } else {
        	print Warn("Uploaded file $att_cnt not properly received, empty file");
        }
      } elseif ($_POST["localattachment".$att_cnt]) {
      	$type = findMime(basename($_POST["localattachment".$att_cnt]));
        Sql_query(sprintf('insert into %s (remotefile,mimetype,description,size) values("%s","%s","%s",%d)',
          $tables["attachment"],
          $_POST["localattachment".$att_cnt],$type,$description,filesize($_POST["localattachment".$att_cnt]))
        );
        $attachmentid = Sql_Insert_id();
        Sql_query(sprintf('insert into %s (messageid,attachmentid) values(%d,%d)',
        $tables["message_attachment"],$messageid,$attachmentid));
        print Info("Added attachment ".$att_cnt. " mime: $type");
      }
    }
  }



  $done = 1;

  ?>
  <h3>Message added </h3>
  <? #echo $num ?> <!--users apply (at the moment, independent of list membership)<p>-->
<?php
	return;
} elseif ($send && $subject != $_POST["subject"]) {
  ?>
  <font color=red size=+2>Sorry, you used invalid characters in the Subject field.</font><br>
  <?
} elseif ($send && $from != $_POST["from"]) {
  ?>
  <font color=red size=+2>Sorry, you used invalid characters in the From field.</font><br>
  <?
} elseif ($send && !$from) {
  ?>
  <font color=red size=+2>Please enter a from line</font><br>
  <?
} elseif ($send && !$message) {
  ?>
  <font color=red size=+2>Please enter a message</font><br>
  <?
} elseif ($send && !$subject) {
  ?>
  <font color=red size=+2>Please enter a subject</font><br>
  <?
} elseif ($send && $duplicate_attribute) {
  ?>
  <font color=red size=+2>Error: you can use an attribute in one rule only</font><br>
  <?
}

if (!$footer)
  $footer = getConfig("messagefooter");

echo $msg;
if (!$done) {
if (ALLOW_ATTACHMENTS) {
	$enctype = 'enctype="multipart/form-data"';
} else {
	$enctype = '';
}
?>
<?=formStart($enctype);

if ($_GET["page"] == "preparemessage")
	print Help("preparemessage","What is prepare a message");

if (!$from) {
	$from = getConfig("message_from_name") . ' '.getConfig("message_from_address");
}

?>

<table>
<tr><td><?=Help("subject")?> Subject:</td><td><input type=text name=subject value="<?php echo $subject?>" size=40></td></tr>
<tr><td colspan=2>
</ul>
</td></tr>
<tr><td><?=Help("from")?> From line:</td><td><input type=text name=from value="<?php echo $from?>" size=40></td></tr>
<tr><td colspan=2>

</td></tr>
<tr><td><?=Help("embargo")?> Embargoed until</td><td><?=$embargo->showInput("embargo","",$_POST["embargo"])?></td></tr>
</td></tr>

<? if (USE_REPETITION) { ?>

<tr><td><?=Help("repetition")?> Repeat message every:</td><td>
<select name="repeatinterval">
<option value="0">-- no repetition</option>
<option value="60">Hour</option>
<option value="1440">Day</option>
<option value="10080">Week</option>
</select>

</td></tr>
</td></tr>
<tr><td>  Repeat until:</td><td><?=$repeatuntil->showInput("repeatuntil","",$_POST["repeatuntil"])?></td></tr>
</td></tr>

<? } ?>

<tr><td colspan=2><?=Help("format")?> Format: <b>Auto detect</b> <input type=radio name="htmlformatted" value="auto" <?=!isset($htmlformatted) || $htmlformatted == "auto"?"checked":""?>>
<b>HTML</b> <input type=radio name="htmlformatted" value="1" <?=$htmlformatted == "1" ?"checked":""?>>
<b>Text</b> <input type=radio name="htmlformatted" value="0" <?=$htmlformatted == "0" ?"checked":""?>>
</td></tr>
<tr><td colspan=2><?=Help("sendformat")?> Send as:
HTML <input type=radio name="sendformat" value="HTML" <?=$sendformat=="HTML"?"checked":""?>>
text <input type=radio name="sendformat" value="text" <?=$sendformat=="text"?"checked":""?>>
<? if (USE_PDF) { ?>
PDF <input type=radio name="sendformat" value="PDF" <?=$sendformat=="PDF"?"checked":""?>>
<? } ?>
text and HTML <input type=radio name="sendformat" value="text and HTML" <?=$sendformat=="text and HTML" || !isset($sendformat) ?"checked":""?>>
<? if (USE_PDF) { ?>
text and PDF <input type=radio name="sendformat" value="text and PDF" <?=$sendformat=="text and PDF" ?"checked":""?>>
<? } ?>
</td></tr>
<?
$req = Sql_Query("select id,title from {$tables["template"]} order by listorder");
if (Sql_affected_Rows()) {
?>
<tr><td><?=Help("usetemplate")?> Use template: </td><td><select name="template"><option value=0>-- select one</option>
<?
$req = Sql_Query("select id,title from {$tables["template"]} order by listorder");
while ($row = Sql_Fetch_Array($req)) {
  printf('<option value="%d" %s>%s</option>',$row["id"], $row["id"]==$template?'SELECTED':'',$row["title"]);
}
?>
</select></td></tr>
<? }

if (ENABLE_RSS) {
	print '<tr><td colspan=2>If you want to use this message as the template for sending RSS feeds
 	select the frequency it should be used for and use [RSS] in your message to indicate where the list of items needs to go.
  </td></tr>';
  print '<tr><td colspan=2><input type=radio name="rsstemplate" value="none">None ';
  foreach ($rssfrequencies as $key => $val) {
		printf('<input type=radio name="rsstemplate" value="%s">%s ',$key,$val);
  }
  print '</td></tr>';
}
?>

<tr><td colspan=2><?=Help("message")?> Message. </td></tr>

<tr><td colspan=2>

<? if (!$usefck) { ?>

	<textarea name=message cols=45 rows=20><?php echo $message?></textarea>

<? } else {
	$oFCKeditor = new FCKeditor ;
	$oFCKeditor->ToolbarSet = 'Accessibility' ;
	$oFCKeditor->Value = $message ;
	$oFCKeditor->CreateFCKeditor( 'message', '600px', '400px' ) ;
}

?>

</td></tr>
<tr><td colspan=2>Message Footer. <br/>Use <b>[UNSUBSCRIBE]</b> to insert the personal unsubscribe URL for each user. <br/>Use <b>[PREFERENCES]</b> to insert the personal URL for a user to update their details.</td></tr>
<tr><td colspan=2><textarea name=footer cols=45 rows=5><?php echo $footer ?></textarea></td></tr>

</table>
<?

if (ALLOW_ATTACHMENTS) {
	print "<table><tr><td colspan=2>".Help("attachments")." Add Attachments to your message</td></tr>";
	for ($att_cnt = 1;$att_cnt <= NUMATTACHMENTS;$att_cnt++) {
  	printf ('<tr><td>File %d:</td><td><input type=file name="attachment%d"></td></tr>',$att_cnt,$att_cnt);
    if (FILESYSTEM_ATTACHMENTS) {
	    printf('<tr><td><b>or</b> path to file on server:</td><td><input type=text name="localattachment%d" size="50"></td></tr>',$att_cnt,$att_cnt);
  	}
    printf ('<tr><td colspan=2>Description:</td></tr>
    	<tr><td colspan=2><textarea name="attachment%d_description" cols=45 rows=3 wrap="virtual"></textarea></td></tr>',$att_cnt);
 	}
  print '</table>';
}

$html = '
<p><b>Select the criteria for this message:</b>
<ol>
<li>to use a criteria, check the box next to it
<li>then check the radio button next to the attribute you want to use
<li>then choose the values of the attributes you want to send the message to
</ul>
<i>Note:</i> Messages will be sent to people who fit to <i>Criteria 1</i> <b>AND</b> <i>Criteria 2</i> etc
<table>
';

$any = 0;
for ($i=1;$i<=NUMCRITERIAS;$i++) {
  $html .= "<tr><td colspan=2><hr><h3>Criteria $i</h3></td><td>Use this one <input type=checkbox name=\"use[$i]\"></tr>";
  $attributes_request = Sql_Query("select * from $tables[attribute]");
  while ($attribute = Sql_Fetch_array($attributes_request)) {
  	$html .= "\n\n";
  	$html .= sprintf('<input type=hidden name="attrtype[%d]" value="%s">',$attribute["id"],$attribute["type"]);
    switch ($attribute["type"]) {
      case "checkbox":
      	$any = 1;
        $html .= sprintf ('<tr><td><input type="radio" name="criteria[%d]" value="%d"> %s</td><td><b>IS</b></td><td><select name="attr%d%d[]">
                <option value="0">Not checked
                <option value="1">Checked</select></td></tr>',$i,$attribute["id"],$attribute["name"],$attribute["id"],$i);
        break;
      case "select":
      case "radio":
      case "checkboxgroup":
			  $some = 0;
      	$thisone = "";
        $values_request = Sql_Query("select * from $table_prefix"."listattr_".$attribute["tablename"]);
        $thisone .= sprintf ('<tr><td valign=top><input type="radio" name="criteria[%d]" value="%d"> %s</td>
                <td valign=top><b>IS</b></td><td><select name="attr%d%d[]" size=4 multiple>',$i,$attribute["id"],strip_tags($attribute["name"]),$attribute["id"],$i);
        while ($value = Sql_Fetch_array($values_request)) {
 				  $some =1;
          $thisone .= sprintf ('<option value="%d">%s',$value["id"],$value["name"]);
        }
        $thisone .= "</select></td></tr>";
        if ($some)
        	$html .= $thisone;
        $any = $any || $some;
        break;
      default:
        $html .= "\n<!-- error: huh, unknown type ".$attribute["type"]." -->\n";
    }
  }
}

if ($any)
	print $html .'</table>';
else
	print "<p>There are currently no attributes available to use for sending a message. The message will go to any user on the lists selected</p>";

?>

</ul>

<? } ?>
