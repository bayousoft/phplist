<?php
ob_start();
$er = error_reporting(63); # some ppl have warnings on
if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
  include $_ENV["CONFIG"];
} elseif (is_file("config/config.php")) {
  include "config/config.php";
}
error_reporting($er);

if ($_GET["u"] && $_GET["m"]) {
  $userid = Sql_Fetch_Row_Query(sprintf('select id from %s where uniqid = "%s"',
    $GLOBALS["tables"]["user"],$_GET["u"]));
  if ($userid[0]) {
    Sql_Query(sprintf('update %s set viewed = now() where messageid = %d and userid = %d',
      $GLOBALS["tables"]["usermessage"],$_GET["m"],$userid[0]));
    Sql_Query(sprintf('update %s set viewed = viewed + 1 where id = %d',
      $GLOBALS["tables"]["message"],$_GET["m"]));
  }
}
header("Content-Type: image/png");
print base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABGdBTUEAALGPC/xhBQAAAAZQTFRF////AAAAVcLTfgAAAAF0Uk5TAEDm2GYAAAABYktHRACIBR1IAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH0gQCEx05cqKA8gAAAApJREFUeJxjYAAAAAIAAUivpHEAAAAASUVORK5CYII=');
