<?
require_once "accesscheck.php";


class phplistPlugin {
  var $name = "Default Plugin";

  var $coderoot = "./plugins/defaultplugin/"; # coderoot relative to the phplist admin directory
  
  function phplistplugin() {
    # initialisation code
  
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
