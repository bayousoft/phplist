<?php

$id = 0;
if (!empty($_GET['id'])) {
  $id = sprintf('%d',$_GET['id']);
} 
if (!$id) {
  return;
}

## remember any data entered
foreach ($_REQUEST as $key => $val) {
#  print $key;
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
if (!empty($_REQUEST["criteria_attribute"])) {
  include dirname(__FILE__).'/addcriterion.php';
}
if ((isset($_REQUEST["embargo"]['year']) && is_array($_REQUEST["embargo"]))) {
  $messagedata["embargo"] = $embargo->getDate() ." ".$embargo->getTime().':00';
}

if ((isset($_REQUEST["repeatuntil"]['year']) && is_array($_REQUEST["repeatuntil"]))) {
  $messagedata["repeatuntil"] = $repeatuntil->getDate() ." ".$repeatuntil->getTime().':00';
}

$status = 'OK';
