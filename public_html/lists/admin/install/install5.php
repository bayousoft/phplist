<?php
require('configvars.php');

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

print $GLOBALS["I18N"]->get(editVariable($miscellaneousVars,'name', 'text'));

?>