<?php

include dirname(__FILE__).'/header.php';

$pl = $GLOBALS['plugins']['keymanager'];

if (isset($_POST['text'])) {
  print "<hr/>".stripslashes($_POST['text']).'<br/>';
  print $GLOBALS['I18N']->get('Encrypting with key').' '.$pl->get_encrypt_key($email);
  $enc = $pl->encrypt_text(stripslashes($_POST['text']),stripslashes($_POST['email']));
  print '<hr/>'.$GLOBALS['I18N']->get('Encrypted text').'<br/>';
  if ($enc) {
    print '<pre>'.$enc.'</pre>';
  } else {
    print $GLOBALS['I18N']->get('Encryption failed');
  }
  print '<hr/>';
}

print $pl->menu();

print '<form method="post" action="">
<textarea name="text" rows="10" cols="50">';
print $GLOBALS['I18N']->get('Type some text to encrypt').'</textarea><br/>
'.$GLOBALS['I18N']->get('Select email to encrypt for').':
<select name="email">';

$emails = $pl->get_encrypting_emails();
foreach ($emails as $email) {
  print '<option>'.$email.'</option>';
}

print '</select><br/>
<input type="submit" name="encrypt" value="'.$GLOBALS['I18N']->get('Encrypt Text').'">
</form>
';