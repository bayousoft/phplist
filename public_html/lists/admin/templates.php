<?
require_once "accesscheck.php";

if ($delete) {
  # delete the index in delete
  print "Deleting $delete ..\n";
  $result = Sql_query("delete from ".$tables["template"]." where id = $delete");
  $result = Sql_query("delete from ".$tables["templateimage"]." where template = $delete");
  print "..Done<br /><hr /><br />\n";
}
?>

<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>


<?

$req = Sql_Query("select * from {$tables["template"]} order by listorder");
if (!Sql_Affected_Rows())
  print '<p class="error">No template have been defined</p>';
else
  print "<p><b>Existing templates</b></p><ul>";
while ($row = Sql_fetch_Array($req))
  printf('<li>
      [ <a href="javascript:deleteRec(\'%s\');">Delete</a> | %s | %s ] %s</li>',
      PageURL2("templates","","delete=".$row["id"]),
      PageLink2("viewtemplate","View","id=".$row["id"]),
      PageLink2("template","Edit","id=".$row["id"]),
      $row["title"]);
print "</ul>";
print "<p>".PageLink2("template","Add new Template")."</p>";
?>
