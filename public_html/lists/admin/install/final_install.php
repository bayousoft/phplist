<?php
require('configvars.php');
require('requiredvars.php');

print $GLOBALS["I18N"]->get($_SESSION["printeable"]);

checkSessionCheckboxes();

print $GLOBALS["I18N"]->get(sprintf('<div class="explain">%s</div>',$GLOBALS["strFinalValuesText"]));

print $GLOBALS["I18N"]->get(showFinalValues($generalVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($bouncesVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($securityVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($debbugingVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($feedbackVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($miscellaneousVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($experimentalVars,'name', $_SESSION));

print $GLOBALS["I18N"]->get(showFinalValues($advanceVars,'name', $_SESSION));

?>
