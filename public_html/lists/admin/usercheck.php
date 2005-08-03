<?php

if (isset($_POST["usercheck"])) {
  $lsexist = new WebblerListing($GLOBALS["I18N"]->get("existingusers"));
  $lsnonexist = new WebblerListing($GLOBALS["I18N"]->get("nonexistingusers"));
  $users = explode("\n",$_POST["usercheck"]);
  foreach ($users as $user) {
    $user = trim($user);
    if ($_POST["check"] == "foreignkey") {
      $exists = Sql_Query(sprintf('select id,foreignkey,email,password from %s where foreignkey = "%s"',$tables["user"],$user));
    } else {
      $exists = Sql_Query(sprintf('select id,foreignkey,email,password from %s where email = "%s"',$tables["user"],$user));
    }
    if (Sql_Num_Rows($exists)) {
      $id = Sql_Fetch_Array($exists);
      $lsexist->addElement($user,PageUrl2("user&id=".$id["id"]));
      $lsexist->addColumn($user,$GLOBALS["I18N"]->get('email'),$id['email']);
      $lsexist->addColumn($user,$GLOBALS["I18N"]->get('key'),$id['foreignkey']);
      $lsexist->addColumn($user,$GLOBALS["I18N"]->get('passwd'),$id['password']);
    } else {
      $lsnonexist->addElement($user);
    }
  }
  print $lsexist->display();
  print $lsnonexist->display();
}

$GLOBALS["I18N"]->get("existcheckintro");

print '<form method=post>';
print '<table>';
print '<tr><td>'.$GLOBALS["I18N"]->get("whatistype").'</td></tr>';
print '<tr><td>'.$GLOBALS["I18N"]->get("foreignkey").' <input type=radio name="check" value="foreignkey"></td></tr>';
print '<tr><td>'.$GLOBALS["I18N"]->get("email").' <input type=radio name="check" value="email"></td></tr>';
print '<tr><td>'.$GLOBALS["I18N"]->get("pastevalues").'</td></tr>';
print '<tr><td><input type=submit name="continue" value="'.$GLOBALS["I18N"]->get("continue").'"></td></tr>';
print '<tr><td><textarea name="usercheck" rows=30 cols=65>'.$_POST['usercheck'].'</textarea></td></tr>';
print '<tr><td><input type=submit name="continue" value="'.$GLOBALS["I18N"]->get("continue").'"></td></tr>';
print '</table></form>';
