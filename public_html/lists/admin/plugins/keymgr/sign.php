<?php

include dirname(__FILE__).'/header.php';

$pl = $GLOBALS['plugins']['keymanager'];

if (isset($_POST['tosign']) && isset($_POST['passphrase'])) {
  print "<hr/>".stripslashes($_POST['tosign']).'<br/>';
  print '<br/>'.$GLOBALS['I18N']->get('Signing with key').' '.$pl->get_sign_key($email);
  $signed = $pl->sign_text(stripslashes($_POST['tosign']),stripslashes($_POST['email']),$_POST['passphrase']);
  print '<hr/>'.$GLOBALS['I18N']->get('Signed text').'<br/>';
  if ($signed) {
    print '<pre>'.$signed.'</pre>';
  } else {
    print $GLOBALS['I18N']->get('Signing failed, make sure you uploaded your secret key, and typed the correct passphrase');
  }
  print '<hr/>';
}

print $pl->menu();

print '
<form method="post" action="">
<textarea name="tosign" rows="10" cols="50">'.$GLOBALS['I18N']->get('Type some text to sign').'</textarea><br/>';
print $GLOBALS['I18N']->get('Select email to sign with').':';
print '<select name="email">';
$signing_emails = $pl->get_signing_emails();
foreach ($signing_emails as $email) {
  print '<option>'.$email.'</option>';
}
print '</select><br/>';
print $GLOBALS['I18N']->get('Enter pass phrase').': <input type="password" name="passphrase" value=""><br/>
<input type="submit" name="sign" value="'.$GLOBALS['I18N']->get('Sign Text').'">
</form>';
