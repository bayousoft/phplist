<?
$pl = $GLOBALS["plugins"]['keymanager'];
if (!is_object($pl)) {
  print "Error initialising key manager";
  return;
}
print $pl->menu();

if (isset($_POST['newkey'])) {
  $result = $pl->add_key($_POST['newkey']);
#  print_r($result);
  if (isset($result['fingerprint'])) {
    print $GLOBALS['I18N']->get('Key fingerprint').': '.$result['fingerprint'].'<br/>';
    if ($result['imported'] || $result['unchanged']) {
      print $GLOBALS['I18N']->get('key successfully imported').'<br/>';
    }
    if ($result['secretimported'] || $result['secretunchanged']) {
      print $GLOBALS['I18N']->get('secret key successfully imported').'<br/>';
    }
    if ($result['newuserids']) {
      print $GLOBALS['I18N']->get('key is a new user').'<br/>';
    }
  } else {
    print $GLOBALS['I18N']->get('Error adding key').'<br/>';
  }
}

print '<h3>'.$GLOBALS['I18N']->get('Add a key').'</h3>';
print '<p>'.$GLOBALS['I18N']->get('If you want to add a key for signing, you can paste both Public and Private keys in the box in one go').'</p>';

print '<form method="post" action="">
<input type="submit" name="import" value="'.$GLOBALS['I18N']->get('Add new key').'">
<br/>
<textarea name="newkey" rows="30" cols="60"></textarea>
</form>
';
