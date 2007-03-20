<?php
if (is_file('../../../VERSION')) {
  $fd = fopen ('../../../VERSION', "r");
  while ($line = fscanf ($fd, "%[a-zA-Z0-9,. ]=%[a-zA-Z0-9,. ]")) {
    list ($key, $val) = $line;
    if ($key == "VERSION")
      $version = $val . "-";
  }
  fclose($fd);
} else {
  $version = "dev";
}

define("VERSION",$version.'dev');

define("NAME",'phplist');

?>