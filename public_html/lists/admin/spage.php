<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>

<?php
require_once "accesscheck.php";

if ($_POST["default"]) {
	saveConfig("defaultsubscribepage",$_POST["default"]);
}

$default = getConfig("defaultsubscribepage");

if ($GLOBALS["require_login"] && !isSuperUser()) {
  $access = accessLevel("list");
  switch ($access) {
    case "owner":
      $subselect = " where owner = ".$_SESSION["logindetails"]["id"];break;
    case "all":
      $subselect = "";break;
    case "none":
    default:
      $subselect = " where id = 0";break;
  }
}

if ($delete) {
	Sql_Query(sprintf('delete from %s where id = %d',
  	$tables["subscribepage"],$delete));
	Sql_Query(sprintf('delete from %s where id = %d',
  	$tables["subscribepage_data"],$delete));
 	Info("deleted $delete");
}
print formStart('name="pagelist"');
$ls = new WebblerListing("Subscribe Pages");

$req = Sql_Query(sprintf('select * from %s %s order by title',$tables["subscribepage"],$subselect));
while ($p = Sql_Fetch_Array($req)) {
	$ls->addElement($p["id"]);
  $ls->addColumn($p["id"],"title",$p["title"]);
  $ls->addColumn($p["id"],"edit",sprintf('<a href="%s&id=%d">edit</a>',PageURL2("spageedit",""),$p["id"]));
  $ls->addColumn($p["id"],"del",sprintf('<a href="javascript:deleteRec(\'%s\');">del</a>',PageURL2("spage","","delete=".$p["id"])));
  $ls->addColumn($p["id"],"view",sprintf('<a href="%s&id=%d">view</a>',getConfig("subscribeurl"),$p["id"]));
  $ls->addColumn($p["id"],"status",$p["active"]? "Active":"Not Active");
	if (($require_login && isSuperUser()) || !$require_login) {
    $ls->addColumn($p["id"],"owner",adminName($p["owner"]));
    if ($p["id"] == $default) {
      $checked = "checked";
    } else {
      $checked = "";
  	}
    $ls->addColumn($p["id"],"default",sprintf('<input type="radio" name="default" value="%d" %s onChange="document.pagelist.submit()">',$p["id"],$checked));
  } else {
  	$adminname = "";
    $isdefault = "";
  }
}
print $ls->display();
print '<p>'.PageLink2("spageedit","Add a new one").'</p>';
?>
</form>
