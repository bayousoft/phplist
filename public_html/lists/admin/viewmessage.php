<?php
# view prepared message
require_once "accesscheck.php";

ob_end_clean();

$message = Sql_Fetch_Array_Query("select * from {$tables["message"]} where status = 'prepared' and id = ".$_GET["id"]);
if ($message["htmlformatted"])
  $content = stripslashes($message["message"]);
else
  $content = nl2br(stripslashes($message["message"]));
if ($message["template"]) {
	print previewTemplate($message["template"],$_SESSION["logindetails"]["id"],$content,$message["footer"]);
} else {
	print nl2br($content."\n\n".$message["footer"]);
}
exit;
?>
