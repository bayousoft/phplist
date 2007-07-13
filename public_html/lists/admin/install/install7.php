<?php
require('configvars.php');

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

$tmpDir = '/tmp';

if (!is_writable($tmpDir)) {
  print $GLOBALS["I18N"]->get(sprintf('<p class="wrong">%s</p>',$GLOBALS["strTmpNotWritable"]));
}
else {
  print $GLOBALS["I18N"]->get(sprintf('%s',$$GLOBALS["strTmpIsOk"]));
}

print $GLOBALS["I18N"]->get(editVariable($advanceVars,'name', 'text'));

?>