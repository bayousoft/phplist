<?
require_once "accesscheck.php";

$GLOBALS["plugins"] = array();
if (is_dir("plugins")) {
  include_once "defaultplugin.php";
  $files = array();
  $dh=opendir("plugins");
  while (false!==($file = readdir($dh))) { 
    if ($file != "." && $file != ".." && 
      !preg_match("/~$/",$file) && 
      is_file("plugins/".$file) && 
      preg_match("/\.php$/",$file) ){
      array_push($files,$file);
    }
  }
  closedir($dh);
  asort($files);
  reset($files);
  foreach ($files as $file) {
    list($name,$ext) = explode(".",$file);
    if (preg_match("/[\w]+/",$name)) {
      include_once "plugins/" . $file;
      eval("\$class = new ". $name ."();");
#      print "$name = $class<br/>";
      $GLOBALS["plugins"][$name] = $class;
    }
  }
} 
?>
