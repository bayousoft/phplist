<?php
require_once dirname(__FILE__).'/accesscheck.php';

## update to phpmailer v2 is not finished yet
#require( dirname(__FILE__) . '/phpmailer2/class.phpmailer.php');

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
      parent::SetLanguage('en', dirname(__FILE__) . '/phpmailer/language/');
      $this->addCustomHeader("X-Mailer: phplist v".VERSION);
      $this->addCustomHeader("X-MessageID: $messageid");
      $this->addCustomHeader("X-ListMember: $email");
      $this->addCustomHeader("Precedence: bulk");
      $newwrap = getConfig("wordwrap");
      if ($newwrap) {
        $this->WordWrap = $newwrap;
      }
      $this->destinationemail = $email;

      $this->CharSet = getConfig("html_charset");

      if (defined('PHPMAILERHOST') && PHPMAILERHOST != '' && isset($GLOBALS['phpmailer_smtpuser']) && $GLOBALS['phpmailer_smtpuser'] != '') {
         $this->SMTPAuth = true;
         $this->Helo = getConfig("website");
         $this->Host = PHPMAILERHOST;

         $this->Username = $GLOBALS['phpmailer_smtpuser'];
         $this->Password = $GLOBALS['phpmailer_smtppassword'];
         #  logEvent('Sending authenticated email via '.PHPMAILERHOST);

         #  logEvent('Sending via smtp');
         $this->Mailer = "smtp";
      }
      else{
         #  logEvent('Sending via mail');
         $this->Mailer = "mail";
      }

      //$ip = gethostbyname($this->Host);

      if ($GLOBALS["message_envelope"]) {
        $this->Sender = $GLOBALS["message_envelope"];
        $this->addCustomHeader("Errors-To: ".$GLOBALS["message_envelope"]);

## one to work on at a later stage
#        $this->addCustomHeader("Return-Receipt-To: ".$GLOBALS["message_envelope"]);
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

    function add_timestamp()
    {
      #0013076: Yellow Moon Baker Ross - New phpList Development
      # Add a line like Received: from [10.1.2.3] by website.example.com with HTTP; 01 Jan 2003 12:34:56 -0000
      # more info: http://www.spamcop.net/fom-serve/cache/369.html
      $ip_address = $_SERVER['REMOTE_ADDR'];
      if ( $_SERVER['REMOTE_HOST'] ) {
        $ip_domain = $_SERVER['REMOTE_HOST'];        
      } else {
        $ip_domain = gethostbyaddr($ip_address);
      }
      $hostname = $_SERVER["HTTP_HOST"];
      $request_time = date('r',$_SERVER['REQUEST_TIME']);
      $sTimeStamp = "from $ip_domain [$ip_address] by $hostname with HTTP; $request_time";
      $this->addTimeStamp($sTimeStamp);      
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
          #echo "Message was not sent <p class="">";
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
      ## phpmailer 2.x
      if (method_exists($this,'AddStringAttachment')) {
        $this->AddStringAttachment($contents,$filename,'base64', $mimetype);
      } else {
        ## old phpmailer
        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = base64_encode($contents);
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $filename;
        $this->attachment[$cur][3] = $this->encoding;
        $this->attachment[$cur][4] = $mimetype;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;
      }
    }

     function find_html_images($templateid) {
      #if (!$templateid) return;
      // Build the list of image extensions
      while(list($key,) = each($this->image_types))
        $extensions[] = $key;

      preg_match_all('/"([^"]+\.('.implode('|', $extensions).'))"/Ui', $this->Body, $images);

      for($i=0; $i<count($images[1]); $i++) {
        if($this->image_exists($templateid,$images[1][$i])){
          $html_images[] = $images[1][$i];
          $this->Body = str_replace($images[1][$i], basename($images[1][$i]), $this->Body);
        }
          ## addition for filesystem images
        if (EMBEDUPLOADIMAGES) {
         if($this->filesystem_image_exists($images[1][$i])){
            $filesystem_images[] = $images[1][$i];
            $this->Body = str_replace($images[1][$i], basename($images[1][$i]), $this->Body);
          }
        }
        ## end addition
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
        ## addition for filesystem images
      if(!empty($filesystem_images)){
        // If duplicate images are embedded, they may show up as attachments, so remove them.
        $filesystem_images = array_unique($filesystem_images);
        sort($filesystem_images);
        for($i=0; $i<count($filesystem_images); $i++){
          if($image = $this->get_filesystem_image($filesystem_images[$i])){
            $content_type = $this->image_types[substr($filesystem_images[$i], strrpos($filesystem_images[$i], '.') + 1)];
            $cid = $this->add_html_image($image, basename($filesystem_images[$i]), $content_type);
            $this->Body = str_replace(basename($filesystem_images[$i]), "cid:$cid", $this->Body);#@@@
          }
        }
      }
        ## end addition
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

        ## addition for filesystem images
    function filesystem_image_exists($filename) {
      ##  find the image referenced and see if it's on the server
      $localfile = $filename;
      if (defined('UPLOADIMAGES_DIR')) {
        print $_SERVER['DOCUMENT_ROOT'].$localfile;
        return is_file($_SERVER['DOCUMENT_ROOT'].$localfile);
      } else {
        $elements = parse_url($filename);
        $localfile = basename($elements['path']);
        return is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile);
      }
    }

    function get_filesystem_image($filename) {
      ## get the image contents
      $localfile = $filename;
      if (defined('UPLOADIMAGES_DIR')) {
        if (is_file($_SERVER['DOCUMENT_ROOT'].$localfile)) {
          return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].$localfile));
        }
      } elseif (is_file($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile)) {
        $elements = parse_url($filename);
        $localfile = basename($elements['path']);
        return base64_encode( file_get_contents($_SERVER['DOCUMENT_ROOT'].$GLOBALS['pageroot'].'/'.FCKIMAGES_DIR.'/'.$localfile));
      } 
      return '';
    }
    ## end addition

    function image_exists($templateid,$filename) {
      $query
      = ' select *'
      . ' from ' . $GLOBALS['tables']['templateimage']
      . ' where template = ?'
      . '   and (filename = ? or filename = ?)';
      $rs = Sql_Query_Params($query, array($templateid, $filename, basename($filename)));
      return Sql_Num_Rows($rs);
    }

     function get_template_image($templateid,$filename){
      $query
      = ' select data'
      . ' from ' . $GLOBALS['tables']['templateimage']
      . ' where template = ?'
      . '   and (filename = ? or filename= ?)';
      $rs = Sql_Query_Params($query, array($templateid, $filename, basename($filename)));
      $req = Sql_Fetch_Row($rs);
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
