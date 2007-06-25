<?php
require_once dirname(__FILE__).'/accesscheck.php';

require( dirname(__FILE__) . '/phpmailer/class.phpmailer.php');

class PHPlistMailer extends PHPMailer {
    var $isText = false;
    var $WordWrap = 75;
    var $encoding = 'base64';
    var $messageid = 0;
    var $destionationemail = '';
    var $estimatedsize = 0;
    var $image_types = array(
                  'gif'  => 'image/gif',
                  'jpg'  => 'image/jpeg',
                  'jpeg'  => 'image/jpeg',
                  'jpe'  => 'image/jpeg',
                  'bmp'  => 'image/bmp',
                  'png'  => 'image/png',
                  'tif'  => 'image/tiff',
                  'tiff'  => 'image/tiff',
                  'swf'  => 'application/x-shockwave-flash'
                  );

    function PHPlistMailer($messageid,$email) {
    #  parent::PHPMailer();
      parent::SetLanguage('en','phpmailer/language/');
      $this->addCustomHeader("X-Mailer: phplist v".VERSION);
      $this->addCustomHeader("X-MessageID: $messageid");
      $this->addCustomHeader("X-ListMember: $email");
      $this->addCustomHeader("Precedence: bulk");
      $this->Host = PHPMAILERHOST;
      $this->Helo = getConfig("website");
      $newwrap = getConfig("wordwrap");
      if ($newwrap) {
        $this->WordWrap = $newwrap;
      }
      $this->destinationemail = $email;

      $this->CharSet = getConfig("html_charset");
      if (isset($GLOBALS['phpmailer_smtpuser']) && $GLOBALS['phpmailer_smtpuser'] != '') {
        $this->SMTPAuth = true;
        $this->Username = $GLOBALS['phpmailer_smtpuser'];
        $this->Password = $GLOBALS['phpmailer_smtppassword'];
#        logEvent('Sending authenticated email via '.PHPMAILERHOST);
      }
      $ip = gethostbyname($this->Host);
      if ($GLOBALS["message_envelope"]) {
        $this->Sender = $GLOBALS["message_envelope"];
        $this->addCustomHeader("Errors-To: ".$GLOBALS["message_envelope"]);

## one to work on at a later stage
#        $this->addCustomHeader("Return-Receipt-To: ".$GLOBALS["message_envelope"]);
      }
      if (!$this->Host || $ip == $this->Host) {
        $this->Mailer = "mail";
#        logEvent('Sending via mail');
      } else {
        $this->Mailer = "smtp";
#        logEvent('Sending via smtp');
      }
      $this->messageid = $messageid;
    }

    function add_html($html,$text = '',$templateid = 0) {
      $this->Body = $html;
      $this->IsHTML(true);
      if ($text) {
        $this->add_text($text);
      }
      $this->find_html_images($templateid);
    }

    function add_text($text) {
      if (!$this->Body) {
        $this->IsHTML(false);
        $this->Body = html_entity_decode($text ,ENT_QUOTES, 'UTF-8' ); #$text;
#        $this->Body = $text;
       } else {
        $this->AltBody = html_entity_decode($text ,ENT_QUOTES, 'UTF-8' );#$text;
      }
    }

    function append_text($text) {
      if ($this->AltBody) {
        $this->AltBody .= html_entity_decode($text ,ENT_QUOTES, 'UTF-8' );#$text;
      } else {
        $this->Body .= html_entity_decode($text ,ENT_QUOTES, 'UTF-8' );#$text;
      }
    }

    function build_message() {
    }

    function CreateHeader() {
      return parent::CreateHeader();
    }

    function CreateBody() {
      $body = parent::CreateBody();
      if ($this->message_type != 'plain') {
        foreach ($GLOBALS['plugins'] as $plugin) {
          $plreturn =  $plugin->mimeWrap($this->messageid,$body,$this->header,$this->ContentTypeHeader,$this->destinationemail);
          if (is_array($plreturn) && sizeof($plreturn) == 3) {
            $this->header = $plreturn[0];
            $body = $plreturn[1];
            $this->ContentTypeHeader = $plreturn[2];
          }
        }
      }
      return $body;
    }

    function send($to_name = "", $to_addr, $from_name, $from_addr, $subject = '', $headers = '',$envelope = '') {
      $this->From = $from_addr;
      $this->FromName = $from_name;
      if (strstr(VERSION, "dev")) {
        # make sure we are not sending out emails to real users
        # when developing
        $this->AddAddress($GLOBALS["developer_email"]);
        if ($GLOBALS["developer_email"] != $to_addr) {
          $this->Body = 'Originally to: '.$to_addr."\n\n".$this->Body;
        }
      } else {
        $this->AddAddress($to_addr);
      }
      $this->Subject = $subject;
      if ($this->Body) {
        if(!parent::Send()) {
          #echo "Message was not sent <p>";
          logEvent("Error sending email to ".$to_addr);
          return 0;
        }#
      } else {
        logEvent('Error sending email to '.$to_addr);
        return 0;
      }
      return 1;
    }

    function add_attachment($contents,$filename,$mimetype) {
      // Append to $attachment array
      $cur = count($this->attachment);
      $this->attachment[$cur][0] = chunk_split(base64_encode($contents), 76, $this->LE);
      $this->attachment[$cur][1] = $filename;
      $this->attachment[$cur][2] = $filename;
      $this->attachment[$cur][3] = $this->encoding;
      $this->attachment[$cur][4] = $mimetype;
      $this->attachment[$cur][5] = false; // isStringAttachment
      $this->attachment[$cur][6] = "attachment";
      $this->attachment[$cur][7] = 0;
    }

     function find_html_images($templateid) {
      #if (!$templateid) return;
      // Build the list of image extensions
      while(list($key,) = each($this->image_types))
        $extensions[] = $key;

      preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', $this->Body, $images);

      for($i=0; $i<count($images[1]); $i++){
        if($this->image_exists($templateid,$images[1][$i])){
          $html_images[] = $images[1][$i];
          $this->Body = str_replace($images[1][$i], basename($images[1][$i]), $this->Body);
        }
      }

      if(!empty($html_images)){
        // If duplicate images are embedded, they may show up as attachments, so remove them.
        $html_images = array_unique($html_images);
        sort($html_images);
        for($i=0; $i<count($html_images); $i++){
          if($image = $this->get_template_image($templateid,$html_images[$i])){
            $content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
            $cid = $this->add_html_image($image, basename($html_images[$i]), $content_type);
            $this->Body = str_replace(basename($html_images[$i]), "cid:$cid", $this->Body);#@@@
          }
        }
      }
    }

    function add_html_image($contents, $name = '', $content_type='application/octet-stream') {
      // Append to $attachment array
      $cid = md5(uniqid(time()));
      $cur = count($this->attachment);
      $this->attachment[$cur][0] = $contents;
      $this->attachment[$cur][1] = '';#$filename;
      $this->attachment[$cur][2] = $name;
      $this->attachment[$cur][3] = $this->encoding;
      $this->attachment[$cur][4] = $content_type;
      $this->attachment[$cur][5] = false; // isStringAttachment
      $this->attachment[$cur][6] = "inline";
      $this->attachment[$cur][7] = $cid;

      return $cid;
    }

    function image_exists($templateid,$filename) {
      $req = Sql_Query(sprintf('select * from %s where template = %d and (filename = "%s" or filename = "%s")',
        $GLOBALS["tables"]["templateimage"],$templateid,$filename,basename($filename)));
      return Sql_Affected_Rows();
    }

     function get_template_image($templateid,$filename){
      $req = Sql_Fetch_Row_Query(sprintf('select data from %s where template = %d and (filename = "%s" or filename = "%s")',
        $GLOBALS["tables"]["templateimage"],$templateid,$filename,basename($filename)));
      return $req[0];
    }

    function EncodeFile ($path, $encoding = "base64") {
      # as we already encoded the contents in $path, return $path
      return chunk_split($path, 76, $this->LE);
    }

    function MailSend($header, $body) {
      ## we don't really use multiple to's so pass that on to phpmailer, if there are any
      if (sizeof($this->to) > 1 ||!USE_LOCAL_SPOOL) {
        return parent::MailSend($header,$body);
      } 
      if (!is_dir(USE_LOCAL_SPOOL) || !is_writable(USE_LOCAL_SPOOL)) return 0;
      if (!ereg("dev",VERSION)) {
        $header .= "To: ".$this->destinationemail.$this->LE;
      } else {
        $header .= 'Originally-To: '.$this->destinationemail.$this->LE;
        $header .= 'To: '.$GLOBALS['developer_email'].$this->LE;
      }
      $header .= "Subject: ".$this->EncodeHeader($this->Subject).$this->LE;

      $fname = tempnam(USE_LOCAL_SPOOL,'msg');
      file_put_contents($fname,$header."\n".$body);
      file_put_contents($fname.'.S',$this->Sender);
      return true;
    }
}
