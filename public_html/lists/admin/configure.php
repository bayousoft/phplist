
<?php
require_once "accesscheck.php";

# configure options
reset($default_config);
if ($save && $id) {
  $info = $default_config[$id];
  if (is_array($_POST)) {
    if ($id == "website" || $id == "domain") {
      $_POST["values"][$id] = str_replace("[WEBSITE]","",$_POST["values"][$id]);
      $_POST["values"][$id] = str_replace("[DOMAIN]","",$_POST["values"][$id]);
    }
    if ($_POST["values"][$id] == "" && !$info[3])
      Error("$info[1] cannot be empty");
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
      printf('<p><a href="%s">edit</a> <b>%s</b><br/>',PageURL2("configure","","id=$key"),$val[1]);
      print nl2br(htmlspecialchars($value)) . "<br/><hr/>";
    }
  }
} else {
  $val = $default_config[$id];
  printf('%s<p>Editing <b>%s</b><br/>',formStart(),$val[1]);
  printf ('<input type=hidden name="id" value="%s">',$id);
  $dbval = getConfig($id);
#  print $dbval.'<br/>';
  if (isset($dbval))
    $value = $dbval;
  else
    $value = $val[0];
#  print $value . " ".$website . " ".$domain.'<br/>';
  if ($id != "website" && $id != "domain") {
    $value = preg_replace('/'.$website.'/i','[WEBSITE]', $value);
    $value = preg_replace('/'.$domain.'/i','[DOMAIN]', $value);
	}
#  print $value . '<br/>';
  if ($val[2] == "textarea")
    printf('<textarea name="values[%s]" rows=25 cols=55>%s</textarea><br/>',
      $id,htmlspecialchars($value));
  else if ($val[2] == "text")
    printf('<input type="text" name="values[%s]" size="70" value="%s"><br/>',
    $id,htmlspecialchars($value));
  else if ($val[2] == "boolean")
    printf('<input type="text" name="values[%s]" size="10" value="%s"><br/>',
    $id,htmlspecialchars($value));
  print '<br/><input type="hidden" name="save" value="1"><input type="submit" name="savebutton" value="Save Changes"></form>';
}
?>

