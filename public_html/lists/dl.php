<?
ob_start();
$er = error_reporting(0); # some ppl have warnings on
if ($_SERVER["ConfigFile"] && is_file($_SERVER["ConfigFile"])) {
	print '<!-- using '.$_SERVER["ConfigFile"].'-->'."\n";
  include $_SERVER["ConfigFile"];
} elseif ($_ENV["CONFIG"] && is_file($_ENV["CONFIG"])) {
	print '<!-- using '.$_ENV["CONFIG"].'-->'."\n";
  include $_ENV["CONFIG"];
} elseif (is_file("config/config.php")) {
	print '<!-- using config/config.php -->'."\n";
  include "config/config.php";
} else {
	print "Error, cannot find config file\n";
  exit;
}
error_reporting($er);

$id = sprintf('%d',$_GET["id"]);
$data = Sql_Fetch_Row_Query("select filename,mimetype,remotefile,description,size from {$tables["attachment"]} where id = $id");

if ($data[0] && is_file($attachment_repository. "/".$data[0])) {
  ob_end_clean();
  if ($data[1]) {
    header("Content-type: $data[1]");
  } else {
    header("Content-type: application/octetstream");
  }

  list($fname,$ext) = explode(".",basename($data[2]));
  header ('Content-Disposition: attachment; filename="'.basename($data[2]).'"');
  if ($data[4])
  	$size = $data[4];
  else
  	$size = filesize($attachment_repository."/".$data[0]);

  if ($size) {
	  header ("Content-Length: " . $size);
    $fsize = $size;
  }
  else
  	$fsize = 4096;
  $fp = fopen($attachment_repository. "/".$data[0],"r");
  while ($buffer = fread($fp,$fsize)) {
    print $buffer;
  flush();
  }
  fclose ($fp);
	exit;
} else {
  FileNotFound();
}

