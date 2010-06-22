<?php
$pl = $GLOBALS["plugins"]['keymanager'];
if (!is_object($pl)) {
  print $GLOBALS['I18N']->get('Error initialising key manager');
  return;
}
print $pl->menu();

if (isset($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
  $pl->del_key($id);
}

Redirect('main&pi=keymanager');
exit;
?>