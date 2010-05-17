
<div id="configurecontent"></div>

<?php
require_once dirname(__FILE__).'/accesscheck.php';

/*
if ($_GET["firstinstall"] || $_SESSION["firstinstall"]) {
  $_SESSION["firstinstall"] = 1;
  print "<p class="x">" . $GLOBALS['I18N']->get('checklist for installation') . "</p>";
  require "setup.php";
}
*/
if (empty($_REQUEST['id'])) {
  $id = '';
} else {
  $id = $_REQUEST['id'];
  if (!isset($default_config[$id])) {
    print $GLOBALS['I18N']->get('invalid request');
    return;
  }
}

# configure options
reset($default_config);
if (!empty($_REQUEST['save']) && $id) {
  $info = $default_config[$id];
  if (is_array($_POST)) {
    if ($id == "website" || $id == "domain") {
      $_POST["values"][$id] = str_replace("[DOMAIN]","",$_POST["values"][$id]);
      $_POST["values"][$id] = str_replace("[WEBSITE]","",$_POST["values"][$id]);
    }
    if ($_POST["values"][$id] == "" && !$info[3])
      Error("$info[1] " . $GLOBALS['I18N']->get('cannot be empty'));
    else {
      SaveConfig($id,$_POST["values"][$id],0);
      Redirect("configure");
      exit;
    }
  }
}

if (!$id) {
  while (list($key,$val) = each($default_config)) {
    if (is_array($val)) {
      $dbval = getConfig($key);
      if (isset($dbval))
        $value = $dbval;
      else
        $value = $val[0];
      printf('<div class="configEdit"><a href="%s" class="ajaxable">%s</a> <b>%s</b></div>',PageURL2("configure","","id=$key"),$GLOBALS['I18N']->get('edit'),$key,$GLOBALS['I18N']->get($val[1]));
      printf('<div id="edit_%s" class="configcontent">'.nl2br(htmlspecialchars(stripslashes($value))) . '</div></div>',$key);
    }
  }
} else {
  include dirname(__FILE__).'/actions/configure.php';
}
?>
