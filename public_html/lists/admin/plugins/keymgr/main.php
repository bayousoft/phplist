<?php

include dirname(__FILE__).'/header.php';
if (!class_exists('gnupg')) return;

if (!Sql_Table_exists('keymanager_keys')) {
  print PageLink2('initialise',$GLOBALS['I18N']->get('Initialise Key Manager'));
  return;
}

$pl = $GLOBALS['plugins']['keymanager'];
print $pl->menu();

$pl->sync_keys();

$keys = $pl->getAllKeys();
print $GLOBALS['I18N']->get('Keys').": $keys<br/>";

$ls = new WebblerListing($GLOBALS['I18N']->get('Keys'));
if ($keys) {
  while ($key = $pl->getNextKey()) {
    $ls->addElement($key['keyid']);
    $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get('del'),'',PageUrl2('delkey&amp;id='.$key['id']));
    foreach ($pl->uiditems as $item) {
      if (isset($key[$item])) {
        $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get($item),$key[$item]);
      }
    }
    foreach ($pl->keyitems as $item) {
      if (isset($key[$item])) {
        $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get($item),$key[$item]);
      }
    }
    $expires = $pl->getKeyDetails($key['id'],'expires');
    if ($expires) {
      if ($expires < time()) {
        $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get('expires'),$GLOBALS['I18N']->get('Expired'));
      } else {
        $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get('expires'),date('j M Y',$expires));
      }
    } else {
      $ls->addColumn($key['keyid'],$GLOBALS['I18N']->get('expires'),$GLOBALS['I18N']->get('Never'));
    }
  } 
  print $ls->display();
}

