<?php

include dirname(__FILE__).'/header.php';

$pl = $GLOBALS['plugins']['keymanager'];

if (!empty($_POST) && is_array($_POST)) {
  foreach ($pl->configvars as $var => $desc) {
    if (isset($_POST[$var])) {
      $pl->writeConfig($var,$_POST[$var]);
    }
  }
  print '<div class="info">'.$GLOBALS['I18N']->get('Changes Saved').'</div>';
}  
print $pl->menu();

print '<form method="post" action="">';
print '<table>';
foreach ($pl->configvars as $var => $desc) {
  print '<tr>';
  print '<td>'.$GLOBALS['I18N']->get($desc[1]).'</td>';
  print '<td>'.$pl->displayConfig($var).'</td>';
  print '</tr>';
}

print '<tr><td colspan=2><input type="submit" name="save" value="'.htmlspecialchars($GLOBALS['I18N']->get('Save Changes')).'"></td></tr>';

print '</table>';
print '</form>';
