<?
require_once "accesscheck.php";

class phplistPlugin {
  var $name = "Default Plugin";

  var $coderoot = "./plugins/defaultplugin/"; # coderoot relative to the phplist admin directory

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

  function adminmenu() {
    return array (
      # page, description
      "main" => "Main Page",
      "helloworld" => "Hello World page"
    );
  }
}

?>
