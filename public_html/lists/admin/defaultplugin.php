<?php
require_once dirname(__FILE__).'/accesscheck.php';

class phplistPlugin {
  var $name = "Default Plugin";

  var $coderoot = "./PLUGIN_ROOTDIR/defaultplugin/"; # coderoot relative to the phplist admin directory

  function phplistplugin() {
    # initialisation code
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

  function adminmenu() {
    return array (
      # page, description
      "main" => "Main Page",
      "helloworld" => "Hello World page"
    );
  }
}

?>
