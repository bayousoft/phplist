<?php
require_once "accesscheck.php";


require( $GLOBALS["coderoot"] . "phpmailer/class.phpmailer.php");

class PHPlistMailer extends PHPMailer {
		var $isText = false;
    var $WordWrap = 75;
    var $encoding = 'base64';
		var $image_types = array(
									'gif'	=> 'image/gif',
									'jpg'	=> 'image/jpeg',
									'jpeg'	=> 'image/jpeg',
									'jpe'	=> 'image/jpeg',
									'bmp'	=> 'image/bmp',
									'png'	=> 'image/png',
									'tif'	=> 'image/tiff',
									'tiff'	=> 'image/tiff',
									'swf'	=> 'application/x-shockwave-flash'
								  );

    function PHPlistMailer($messageid,$email) {
    #	parent::PHPMailer();
      $this->addCustomHeader("X-Mailer: PHPlist v".VERSION);
    	$this->addCustomHeader("X-MessageID: $messageid");
      $this->addCustomHeader("X-ListMember: $email");
    	$this->addCustomHeader("Precedence: bulk");
      $this->Host = SMTPHOST;
      $this->Helo = getConfig("website");
      $this->CharSet = getConfig("html_charset");
      $ip = gethostbyname($this->Host);
      if ($GLOBALS["message_envelope"]) {
      	$this->Sender = $GLOBALS["message_envelope"];
        $this->addCustomHeader("Errors-To: ".$GLOBALS["message_envelope"]);
      }
      if (!$this->Host || $ip == $this->Host) {
       	$this->Mailer = "mail";
      } else {
      	$this->Mailer = "smtp";
      }
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
        $this->Body = $text;
     	} else {
        $this->AltBody = $text;
      }
    }
    
    function append_text($text) {
    	if ($this->AltBody) {
	    	$this->AltBody .= $text;
      } else {
      	$this->Body .= $text;
      }
    }

    function build_message() {
    }

    function send($to_name = "", $to_addr, $from_name, $from_addr, $subject = '', $headers = '',$envelope = '') {
    	$this->From = $from_addr;
			$this->FromName = $from_name;
      if (ereg("dev",VERSION)) {
      	# make sure we are not sending out emails to real users
        # when developing
      	$this->AddAddress('michiel@tincan.co.uk');
      } else {
      	$this->AddAddress($to_addr);
      }
      $this->Subject = $subject;
      if(!parent::Send()) {
        #echo "Message was not sent <p>";
        logEvent("Mailer Error: " . $this->ErrorInfo);
        return 0;
      }#
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
    	if (!$templateid) return;
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
      $this->attachment[$cur][1] = $filename;
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
}
