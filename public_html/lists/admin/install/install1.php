<?php
require('configvars.php');

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

print $GLOBALS["I18N"]->get(sprintf('<div class="explain">%s</div>', $GLOBALS['strExplainInstall1']));

print $GLOBALS["I18N"]->get(editVariable($generalVars,'name', 'text'));

print $GLOBALS["I18N"]->get(editVariable($bouncesVars,'name', 'text'));

?>
