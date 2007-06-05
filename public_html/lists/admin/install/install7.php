<?php
require('configvars.php');

$tmpDir = '/tmp';

if (!is_writable($tmpDir)) {
  printf('<p class="wrong">%s</p>',$GLOBALS["strTmpNotWritable"]);
}
else {
  printf('%s',$$GLOBALS["strTmpIsOk"]);
}

print editVariable($advanceVars,'name', 'text');

?>