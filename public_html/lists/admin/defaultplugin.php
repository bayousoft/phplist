<?php
require_once dirname(__FILE__).'/accesscheck.php';

class phplistPlugin {
  var $name = "Default Plugin";

  var $coderoot = "./PLUGIN_ROOTDIR/defaultplugin/"; # coderoot relative to the phplist admin directory
  # optional configuration variables
  var $configvars = array(
    # config var    array( type, name [array values])
  );

  function phplistplugin() {
    # initialisation code
    ## try to prepend PLUGIN ROOTDIR, if necessary
    if (!is_dir($this->coderoot)) {
      $this->coderoot = PLUGIN_ROOTDIR.'/'.$this->coderoot;
    }
  }

  # writeConfig, write a value to the general config to be retrieved at a later stage
  # parameters: name -> name of the variable
  #             value -> value of the variable, can be a scalar, array or object
  # returns success or failure
  function writeConfig($name,$value) {
    $store = '';
    if (is_object($value) || is_array($value)) {
      $store = 'SER:'.serialize($value);
    } else {
      $store = $value;
    }
    Sql_Query(sprintf('replace into %s set item = "%s-%s",value="%s",editable=0',$GLOBALS['tables']['config'],
      $this->name,addslashes($name),addslashes($store)));
    return 1;
  }

  # getConfig, read a value from the general config to be retrieved at a later stage
  # parameters: name -> name of the variable
  # returns value
  function getConfig($name) {
    $req = Sql_Fetch_Array_Query(sprintf('select value from  %s where item = "%s-%s"',$GLOBALS['tables']['config'],
      $this->name,addslashes($name)));
    $result = stripslashes($req[0]);
    if (!empty($result) && strpos('SER:',$result) == 1) {
      $result = substr($result,4);
      return unserialize($result);
    }
    return $result;
  }

  ## displayConfig
  # purpose: display input for a config variable in the backend
  # parameters: 
  # name -> name of the config variable, as found in $this->configvars
  # return, HTML snippet of input to slot into a form
  function displayConfig($name) {
    $name = trim(strtolower($name));
    $name = preg_replace('/\W/','',$name);
    $type = $this->configvars[$name][0];
    $label = $this->configvars[$name][1];
    $currentvalue = $this->getConfig($name);
    $html = '';
    switch ($type) {
      case 'attributeselect':
        $html = sprintf('<select name="%s"><option value=""> --%s</option>',$name,$GLOBALS['I18N']->get('choose'));
        $req = Sql_Query(sprintf('select * from %s',$GLOBALS['tables']['attribute']));
        while ($row = Sql_Fetch_Array($req)) {
          $html .= sprintf('<option value="%d" %s>%s</option>',
            $row['id'],$row['id'] == $currentvalue ? 'selected="selected"':'',htmlspecialchars($row['name']));
        }
        $html .= '</select>';
        return $html;
      case 'radio':
        $values = $this->configvars[$name][2];
        foreach ($values as $key => $label) {
          $html .= sprintf('<input type="radio" name="%s" value="%s" %s> %s',
            $name,$key,$currentvalue == $key ? 'checked="checked"':'',$label);
        }
        return $html;
      case 'textarea':
        $html = sprintf('<textarea name="%s" rows="10" cols="40" wrap="virtual">%s </textarea>',$name,htmlspecialchars($currentvalue));
        return $html;
      case 'text':
      default:
        $html = sprintf('<input type="text" name="%s" value="%s" size="45">',$name,htmlspecialchars($currentvalue));
        return $html;
    }
  }

  # parse the text of the thankyou page
  # parameters:
  #  pageid -> id of the subscribe page
  #  userid -> id of the user
  #  text -> current text of the page
  # returns parsed text
  function parseThankyou($pageid = 0,$userid = 0,$text = "") {
    return $text;
  }

  ## add a tab to the "Send a Message page" for options to be set in the plugin
  # parameters: 
  #    messageid = ID of the message being displayed (should always be > 0)
  #    messagedata = associative array of all data entered for this message
  # returns: HTML code to slot into the form to submit to the database
  function sendMessageTab($messageid = 0,$messagedata = array()) {
    return '';
  }

  ## If adding a TAB to the Send a Message page, what is the TAB's name
  # parameters: none
  # returns: short title (less than about 10 characters)
  function sendMessageTabTitle($messageid = 0) {
    return '';
  }

  ## sendFormats();
  # parameters: none
  # returns array of "shorttag" => "description" of possible formats this plugin can provide
  # this will be listed in the "Send As" list of radio buttons, so that an editor can choose the format
  function sendFormats() {
    return array();
  }

  ## parseOutgoingTextMessage
  # parameters: 
  #  messageid: ID of the message
  #  text content: entire text content of a message going out
  #  destination: destination email
  #  userhtmlpref: 1/0 whether the user wants to receive HTML
  # returns: parsed content
  function parseOutgoingTextMessage($messageid,$content,$destination,$userhtmlpref = 0) {
    return $content;
  }

  ## parseOutgoingHTMLMessage
  # parameters: 
  #  messageid: ID of the message
  #  text content: entire HTML content of a message going out
  #  destination: destination email
  #  userhtmlpref: 1/0 whether the user wants to receive HTML
  # returns: parsed content
  function parseOutgoingHTMLMessage($messageid,$content,$destination,$userhtmlpref = 0) {
    return $content;
  }

  ###getMessageAttachment($messageid,$mail->Body);
  # parameters: $messageid,$messagecontent
  # returns array (
  #  'content' => Content of the attachment 
  #  'filename' => name of the attached file
  #  'mimetype' => mimetype of the attachment
  # );
  function getMessageAttachment($messageid,$content) {
    return array();
  }

  ### mimeWrap
  # purpose: wrap the actual contents of the message in another MIME layer
  # parameters:
  #   messageid: message being sent 
  #   body: current body of message
  #   header: current header of message, except for the Content-Type
  #   contenttype: Content-Type of message
  #   destination: email that this message is going out to
  # returns array(newheader,newbody,newcontenttype)
  function mimeWrap($messageid,$body,$header,$contenttype,$destination) {
     return array($header,$body,$contenttype);
  }

  ### setFinalDestinationEmail
  # purpose: change the actual recipient based on user Attribute values:
  # parameters: 
  #   messageid: message being sent 
  #   uservalues: array of "attributename" => "attributevalue" of all user attributes
  #   email: email that this message is current set to go out to
  # returns: email that it should go out to
  function setFinalDestinationEmail($messageid,$uservalues,$email) {
    return $email;
  }

  ## constructMessage
  # purpose: create the actual message, based on the text and html content as prepared by phplist
  # parameters:
  # sendformat: the send format chosen by the admin
  #    if this is not one of the sendFormats() set up for this plugin, return 0
  # htmlmessage: contents of HTML version of message
  # textmessage: contents of Text version of message
  # mail:  mail object that is going to be send
  ### you can alter the outgoing mail by calling the required methods of the mail object
  # returns 1 if the message has been dealt with successfully and 0 if not
  function constructMessage($sendformat,$htmlmessage,$textmessage,&$mail) {
    return 0;
  }

  function adminmenu() {
    return array (
      # page, description
      "main" => "Main Page",
      "helloworld" => "Hello World page"
    );
  }
}

?>
