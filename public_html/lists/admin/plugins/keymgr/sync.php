<?php

include dirname(__FILE__).'/header.php';
if (!class_exists('gnupg')) return;

if (!Sql_Table_exists('keymanager_keys')) {
  print PageLink2('initialise',$GLOBALS['I18N']->get('Initialise Key Manager'));
  return;
}

$pl = $GLOBALS['plugins']['keymanager'];
print $pl->menu();

print '<h1>'.$GLOBALS['I18N']->get('Synchronising keys').'</h1>';
$keyattribute = $pl->getConfig('keyattribute');
if ($keyattribute) {
  if ($pl->sync_keys()) {
    print '<br/><b>'.$GLOBALS['I18N']->get('Ok').'</b><br/>';
  } else {
    print '<br/><b>'.$GLOBALS['I18N']->get('Failed').'</b><br/>';
  }
} else {
  print '<br/><b>'.$GLOBALS['I18N']->get('Not OK').'</b>:<br/>';
  print '<br/>'.$GLOBALS['I18N']->get('Please choose the attribute that holds the Public Key for users in the configure page').'<br/>';
}

$stats = $pl->key_stats();
printf($GLOBALS['I18N']->get('<b>%d keys</b> in the database<br/>%d unique emails'),$stats['numkeys'],$stats['emails']);

