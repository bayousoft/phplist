<?
require_once "accesscheck.php";

# quick installation checklist

# initialise database
# setup config values
# configure attributes
# create lists
# create subscribe pages

print "<table>";
print '<tr><td>Initialise Database</td><td>'.PageLink2("initialise","Go there").'</td><td>';

if (Sql_Table_Exists($tables["config"])) {
	print $GLOBALS["img_tick"];
} else {
	print $GLOBALS["img_cross"];
}

print '</td></tr>';

if ($GLOBALS["require_login"]) {
  print '<tr><td>Change Admin Password </td><td>'.PageLink2("admin&id=1","Go there").'</td><td>';

  $curpwd = Sql_Fetch_Row_Query("select password from {$tables["admin"]} where loginname = \"admin\"");
  if ($curpwd[0] != "phplist") {
    print $GLOBALS["img_tick"];
  } else {
    print $GLOBALS["img_cross"];
  }

  print '</td></tr>';
}
print '<tr><td>Configure General Values</td><td>'.PageLink2("configure","Go there").'</td><td>';
$data = Sql_Fetch_Row_Query("select value from {$tables["config"]} where item = \"admin_address\"");
if ($data[0]) {
	print $GLOBALS["img_tick"];
} else {
	print $GLOBALS["img_cross"];
}

print '</td></tr>';

print '<tr><td>Configure Attributes</td><td>'.PageLink2("attributes","Go there").'</td><td>';
$req = Sql_Query("select * from {$tables["attribute"]}");
if (Sql_Affected_Rows()) {
	print $GLOBALS["img_tick"];
} else {
	print $GLOBALS["img_cross"];
}

print '</td></tr>';

print '<tr><td>Create Lists</td><td>'.PageLink2("list","Go there").'</td><td>';
$req = Sql_Query("select * from {$tables["list"]} where active");
if (Sql_Affected_Rows()) {
	print $GLOBALS["img_tick"];
} else {
	print $GLOBALS["img_cross"];
}

print '</td></tr>';

print '<tr><td>Create Subscribe Pages</td><td>'.PageLink2("spage","Go there").'</td><td>';
$req = Sql_Query("select * from {$tables["subscribepage"]}");
if (Sql_Affected_Rows()) {
	print $GLOBALS["img_tick"];
} else {
	print $GLOBALS["img_cross"];
}

print '</td></tr>';
print '</table>';
