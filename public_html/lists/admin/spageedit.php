


<form method=post>
<table>
<?php
require_once "accesscheck.php";

# configure subscribe page

$access = accessLevel("spage");
switch ($access) {
  case "owner":
    $subselect = " where owner = ".$_SESSION["logindetails"]["id"];break;
  case "all":
    $subselect = "";break;
  case "none":
  default:
    $subselect = " where id = 0";break;
}

if ($_POST["save"] || $_POST["activate"] || $_POST["deactivate"]) {
	$owner = $_POST["owner"];
  if (!$owner)
  	$owner = $_SESSION["logindetails"]["id"];
	if ($id) {
  	Sql_Query(sprintf('update %s set title = "%s",owner = %d where id = %d',
    	$tables["subscribepage"],$title,$owner,$id));
 	} else {
  	Sql_Query(sprintf('insert into %s (title,owner) values("%s",%d)',
    	$tables["subscribepage"],$title,$owner));
   	$id = Sql_Insert_id();
  }
  Sql_Query(sprintf('delete from %s where id = %d',$tables["subscribepage_data"],$id));
  foreach (array("title","intro","header","footer","thankyoupage","button","htmlchoice","emaildoubleentry") as $item) {
  	Sql_Query(sprintf('insert into %s (name,id,data) values("%s",%d,"%s")',
    	$tables["subscribepage_data"],$item,$id,$_POST[$item]));
  }

  foreach (array("subscribesubject","subscribemessage","confirmationsubject","confirmationmessage") as $item) {
    SaveConfig("$item:$id",stripslashes($_POST[$item]),0);
	}

  Sql_Query(sprintf('delete from %s where id = %d and name like "attribute___"',
  	$tables["subscribepage_data"],$id));

  if (is_array($attr_use)) {
  	$cnt=0;
    $attributes = "";
	  while (list($att,$val) = each ($attr_use)) {
    	$default = $attr_default[$att];
      $order = $attr_listorder[$att];
      $required = $attr_required[$att];

      Sql_Query(sprintf('insert into %s (id,name,data) values(%d,"attribute%03d","%s")',
      	$tables["subscribepage_data"],$id,$att,
        $att.'###'.$default.'###'.$order.'###'.$required));
      $cnt++;
      $attributes .= $att.'+';
    }
  }
  Sql_Query(sprintf('replace into %s (id,name,data) values(%d,"attributes","%s")',
   	$tables["subscribepage_data"],$id,$attributes));
  if (is_array($list)) {
  	Sql_Query(sprintf('replace into %s (id,name,data) values(%d,"lists","%s")',
	   	$tables["subscribepage_data"],$id,join(',',$list)));
  }
  if (ENABLE_RSS) {
  	Sql_Query(sprintf('replace into %s (id,name,data) values(%d,"rssintro","%s")',
	   	$tables["subscribepage_data"],$id,$rssintro));
  	Sql_Query(sprintf('replace into %s (id,name,data) values(%d,"rss","%s")',
	   	$tables["subscribepage_data"],$id,join(',',$rss)));
  	Sql_Query(sprintf('replace into %s (id,name,data) values(%d,"rssdefault","%s")',
	   	$tables["subscribepage_data"],$id,$rssdefault));
	}
  if ($activate) {
  	Sql_Query(sprintf('update %s set active = 1 where id = %d',
    	$tables["subscribepage"],$id));
   	Redirect("spage");
    exit;
  } elseif ($deactivate) {
  	Sql_Query(sprintf('update %s set active = 0 where id = %d',
    	$tables["subscribepage"],$id));
   	Redirect("spage");
    exit;
  }
  
}
ob_end_flush();
if ($id) {
	$req = Sql_Query(sprintf('select * from %s where id = %d',$tables["subscribepage_data"],$id));
  while ($row = Sql_Fetch_Array($req))
  	$data[$row["name"]] = $row["data"];
  $attributes = explode('+',$data["attributes"]);
  $rss = explode(",",$data["rss"]);
  foreach ($attributes as $attribute) {
  	if ($data[sprintf('attribute%03d',$attribute)]) {
      	list($attributedata[$attribute]["id"],
      	$attributedata[$attribute]["default_value"],
      	$attributedata[$attribute]["listorder"],
      	$attributedata[$attribute]["required"]) = explode('###',$data[sprintf('attribute%03d',$attribute)]);
   	}
  }
  $selected_lists = explode(',',$data["lists"]);
  printf('<input type=hidden name="id" value="%d">',$id);
  $data["subscribemessage"] = getConfig("subscribemessage:$id");
  $data["subscribesubject"] = getConfig("subscribesubject:$id");
  $data["confirmationmessage"] = getConfig("confirmationmessage:$id");
  $data["confirmationsubject"] = getConfig("confirmationsubject:$id");
} else {
	$data["title"] = 'Title of this set of lists';
  $data["button"] = $strSubmit;
  $data["intro"] = $strSubscribeInfo;
  $data["header"] = $defaultheader;
  $data["footer"] = $defaultfooter;
  $data["thankyoupage"] = '<h3>'.$GLOBALS["strThanks"].'</h3>'."\n". $GLOBALS["strEmailConfirmation"];
  $data["subscribemessage"] = getConfig("subscribemessage");
  $data["subscribesubject"] = getConfig("subscribesubject");
  $data["confirmationmessage"] = getConfig("confirmationmessage");
  $data["confirmationsubject"] = getConfig("confirmationsubject");
  $data["htmlchoice"] = "checkforhtml";
  $data["emaildoubleentry"] = "yes";
  $data["rssdefault"] = "daily";
  $data["rssintro"] = 'Please indicate how often you want to receive messages';
  $rss = array_keys($rssfrequencies);
  $selected_lists = array();
}

print '<tr><td colspan=2><h1>General Information</h1></td></tr>';

printf('<tr><td valign=top>Title</td><td><input type=text name=title value="%s" size=60></td></tr>',
	htmlspecialchars(stripslashes($data["title"])));
printf('<tr><td valign=top>Intro</td><td><textarea name=intro cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["intro"])));
printf('<tr><td valign=top>Header</td><td><textarea name=header cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["header"])));
printf('<tr><td valign=top>Footer</td><td><textarea name=footer cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["footer"])));
printf('<tr><td valign=top>Thank you page</td><td><textarea name=thankyoupage cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["thankyoupage"])));
printf('<tr><td valign=top>Text for Button</td><td><input type=text name=button value="%s" size=60></td></tr>',
	htmlspecialchars($data["button"]));
printf('<tr><td valign=top>HTML Email choice</td><td>');
printf ('<input type=radio name="htmlchoice" value="textonly" %s> Don\'t offer choice, default to <b>text</b> <br/>',$data["htmlchoice"] == "textonly"?"checked":"");#'
printf ('<input type=radio name="htmlchoice" value="htmlonly" %s> Don\'t offer choice, default to <b>HTML</b> <br/>',$data["htmlchoice"] == "htmlonly"?"checked":"");#'
printf ('<input type=radio name="htmlchoice" value="checkfortext" %s> Offer checkbox for text <br/>',$data["htmlchoice"] == "checkfortext"?"checked":"");
printf ('<input type=radio name="htmlchoice" value="checkforhtml" %s> Offer checkbox for HTML <br/>',$data["htmlchoice"] == "checkforhtml"?"checked":"");
printf ('<input type=radio name="htmlchoice" value="radiotext" %s> Radio buttons, default to text <br/>',$data["htmlchoice"] == "radiotext"?"checked":"");
printf ('<input type=radio name="htmlchoice" value="radiohtml" %s> Radio buttons, default to HTML <br/>',$data["htmlchoice"] == "radiohtml"?"checked":"");
print "</td></tr>";

printf('<tr><td valign=top>Display Email confirmation</td><td>');
printf ('<input type=radio name="emaildoubleentry" value="yes" %s>Display email confirmation<br/>',$data["emaildoubleentry"]=="yes"?"checked":"");#'
printf ('<input type=radio name="emaildoubleentry" value="no" %s>Don\'t display email confirmation<br/>',$data["emaildoubleentry"]=="no"?"checked":"");#'


print "<tr><td colspan=2><h1>Message they receive when they subscribe</h1></td></tr>";
printf('<tr><td valign=top>Subject</td><td><input type=text name=subscribesubject value="%s" size=60></td></tr>',
	htmlspecialchars(stripslashes($data["subscribesubject"])));
printf('<tr><td valign=top>Message</td><td><textarea name=subscribemessage cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["subscribemessage"])));
print "<tr><td colspan=2><h1>Message they receive when they confirm their subscription</h1></td></tr>";
printf('<tr><td valign=top>Subject</td><td><input type=text name=confirmationsubject value="%s" size=60></td></tr>',
	htmlspecialchars(stripslashes($data["confirmationsubject"])));
printf('<tr><td valign=top>Message</td><td><textarea name=confirmationmessage cols=60 rows=10 wrap=virtual>%s</textarea></td></tr>',
	htmlspecialchars(stripslashes($data["confirmationmessage"])));
print '<tr><td colspan=2><h1>Select the attributes to use</h1></td></tr><tr><td colspan=2>';
  $req = Sql_Query(sprintf('select * from %s order by listorder',
  	$tables["attribute"]));
  while ($row = Sql_Fetch_Array($req)) {
  	if (is_array($attributedata[$row["id"]])) {
    	$checked[$row["id"]] = "checked";
      $bgcol = '#F7E7C2';
			$value = $attributedata[$row["id"]];
   	} else {
    	$value = $row;
      $bgcol = '#ffffff';
    }
  ?>
  <table border=1 width=100% bgcolor="<?php echo $bgcol?>">
  <tr><td colspan=2 width=150>Attribute:<?php echo $row["id"] ?></td><td colspan=2>Check this box to use this attribute in the page <input type="checkbox" name="attr_use[<? echo $row["id"] ?>]" value="1" <?=$checked[$row["id"]]?>></td></tr>
  <tr><td colspan=2>Name: </td><td colspan=2><h2><?php echo htmlspecialchars(stripslashes($row["name"])) ?></h2></td></tr>
  <tr><td colspan=2>Type: </td><td colspan=2><h2><?php echo $row["type"]?></h2></td></tr>
  <tr><td colspan=2>Default Value: </td><td colspan=2><input type=text name="attr_default[<?php echo $row["id"]?>]" value="<? echo htmlspecialchars(stripslashes($value["default_value"])) ?>" size=40></td></tr>
  <tr><td>Order of Listing: </td><td><input type=text name="attr_listorder[<?php echo $row["id"]?>]" value="<? echo $value["listorder"] ?>" size=5></td>
  <td>Is this attribute required?: </td><td><input type=checkbox name="attr_required[<?php echo $row["id"]?>]" value="1" <? echo $value["required"] ? "checked": "" ?>></td></tr>
  </table><hr>
<?php
	}

print '</td></tr>';

if (ENABLE_RSS) {
	print '<tr><td colspan=2><h1>RSS settings</h1></td></tr>';
  printf('<tr><td valign=top>Intro Text</td><td><textarea name=rssintro rows=3 cols=60>%s</textarea></td></tr>',
    htmlspecialchars(stripslashes($data["rssintro"])));
  foreach ($rssfrequencies as $key => $val) {
  	printf('<tr><td colspan=2><input type=checkbox name="rss[]" value="%s" %s> Offer option to receive %s
    (default <input type=radio name="rssdefault" value="%s" %s>)
    </td></tr>',$key,in_array($key,$rss)?"checked":"",$val,
    $key,$data["rssdefault"] == $key ? "checked":""
    );
  }
  print "<tr><td colspan=2><hr></td></tr>";
}

print '<tr><td colspan=2><h1>Select the lists to offer</h1></td></tr>';

$req = Sql_query("SELECT * FROM {$tables["list"]} $subselect order by listorder");
if (!Sql_Affected_Rows())
	print "<tr><td colspan=2>No lists available, please create one first</td></tr>";
while ($row = Sql_Fetch_Array($req)) {
	printf ('<tr><td valign=top width=150><input type=checkbox name="list[%d]" value="%d" %s> %s</td><td>%s</td></tr>',
  	$row["id"],$row["id"],in_array($row["id"],$selected_lists)?"checked":"",stripslashes($row["name"]),stripslashes($row["description"]));
}

print '</table>';
if ($GLOBALS["require_login"] && (isSuperUser() || accessLevel("spageedit") == "all")) {
  print '<br/>Owner: <select name="owner">';
  $req = Sql_Query("select id,loginname from {$tables["admin"]} order by loginname");
  while ($row = Sql_Fetch_Array($req))
    printf ('<option value="%d" %s>%s</option>',$row["id"],$row["id"] == $data["owner"]? "selected":"",$row["loginname"]);
  print '</select>';
}

print '
<br/><input type="submit" name="save" value="Save Changes">&nbsp;
<input type="submit" name="activate" value="Save and Activate">
<input type="submit" name="deactivate" value="Save and Deactivate">
</form>';

?>

