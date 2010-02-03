<?php

print '<h3>'.$GLOBALS['I18N']->get('PGP key manager for phplist').'</h3>';

if (version_compare(phpversion(),"5.0.0","<")) {
  print $GLOBALS['I18N']->get('This plugin requires PHP 5');
  return;
}

if (!class_exists('gnupg')) {
  print '<h3>'.$GLOBALS['I18N']->get('This plugin requires the <a href="http://pecl.php.net/package/gnupg">pecl::gnupg</a> extension').'</h3>'.
    $GLOBALS['I18N']->get('Hint').': <b>pecl install gnupg</b>
  ';
  return;
}

# $home = getenv('GNUPGHOME');
# print "Keyring Home: ".$home.'<br/>';
# error_reporting(E_ALL);
# ini_set('display_errors',1);

?>
