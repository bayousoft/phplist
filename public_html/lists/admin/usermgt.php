<?
require_once "accesscheck.php";

$spb ='<span class="menulinkleft">';
$spe = '</span>';

print $spb.PageLink2("users","Users").$spe;
print $spb.PageLink2("attributes","User Attributes").$spe;
if ($tables["attribute"] && Sql_Table_Exists($tables["attribute"])) {
	$attrmenu = array();
	$res = Sql_Query("select * from {$tables['attribute']}",1);
	while ($row = Sql_Fetch_array($res)) {
		if ($row["type"] != "checkbox" && $row["type"] != "textarea" && $row["type"] != "textline" && $row["type"] != "hidden")
			$attrmenu["editattributes&id=".$row["id"]] = strip_tags($row["name"]);
	}
}
foreach ($attrmenu as $page => $desc) {
	$link = PageLink2($page,$desc);
	if ($link) {
		$html .= $spb.$link.$spe;
	}
}
print $spb.'Control values for '.$spe.$html.$spb.'&nbsp;<br/>'.$spe;

print $spb.PageLink2("reconcileusers","Reconcile Users").$spe;
print $spb.PageLink2("import","Import Emails").$spe;
print $spb.PageLink2("export","Export Emails").$spe;
?>
