<?php
require('configvars.php');
require('requiredvars.php');

print showFinalValues($generalVars,'name', $_SESSION);

print showFinalValues($bouncesVars,'name', $_SESSION);

print showFinalValues($securityVars,'name', $_SESSION);

print showFinalValues($debbugingVars,'name', $_SESSION);

print showFinalValues($feedbackVars,'name', $_SESSION);

print showFinalValues($miscellaneousVars,'name', $_SESSION);

print showFinalValues($experimentalVars,'name', $_SESSION);

print showFinalValues($advanceVars,'name', $_SESSION);

?>
