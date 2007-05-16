<?php
require('configvars.php');

printf('<div class="explain">%s</div>', $GLOBALS['strExplainInstall1']);

print editVariable($generalVars,'name', 'text');

print editVariable($bouncesVars,'name', 'text');

?>
