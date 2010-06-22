<?php

$id = 0;
if (!empty($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
} 
if (!$id) {
  return;
}

if (isset($_REQUEST['sendmethod']) && $_REQUEST['sendmethod'] == 'inputhere') {
  $_REQUEST['sendurl'] = '';
}

if (!empty($_REQUEST['sendurl'])) {
  if (!$GLOBALS["has_pear_http_request"]) {
    print Warn($GLOBALS['I18N']->get('warnnopearhttprequest'));
  } else {
    $_REQUEST["message"] = '[URL:'.$_REQUEST['sendurl'].']';
  }
} 

## remember any data entered
foreach ($_REQUEST as $key => $val) {
/*
  print $key .' '.$val;
*/
  setMessageData($id,$key,$val);
  if (get_magic_quotes_gpc()) {
    if (is_string($val)) {
      $messagedata[$key] = stripslashes($val);
    } else {
      $messagedata[$key] = $val;
    }
  } else {
    $messagedata[$key] = $val;
  }
}
unset($GLOBALS['MD']);

$messagedata = loadMessageData($id);

/*
if (!empty($_REQUEST["criteria_attribute"])) {
  include dirname(__FILE__).'/addcriterion.php';
}
*/

/*
print '<hr/>';
var_dump($messagedata);
#exit;
*/

$status = 'OK';
