<?php
require_once "accesscheck.php";


$attributes = array();
ob_end_flush();

function readentry($file) {
  $fp = fopen($file,"r");
  $found = "";
  while (!feof ($fp)) {
    $buffer = fgets($fp, 4096);
    if (!ereg("#",$buffer)) {
      $found = $buffer;
      fclose($fp);
      return $found;
    }
  }
  fclose ($fp);
  return "";
}
      
$dir = opendir("data");
while ($file = readdir($dir)) {
  if (is_file("data/$file")) {
    $entry = readentry("data/$file");
    $attributes[$entry] = $file;
  }
}
closedir($dir);

if (is_array($selected)) {
  while(list($key,$val) = each($selected)) {
    $entry = readentry("data/$val");
    list($name,$desc) = explode(":",$entry);
    print "<br/><br/>Loading $desc<br>\n";
    $lc_name = str_replace(" ","", strtolower(str_replace(".txt","",$val)));
    $lc_name = ereg_replace("[^[:alnum:]]","",$lc_name);

    if ($lc_name == "") Fatal_Error("Name cannot be empty: $lc_name");
    Sql_Query("select * from {$tables['attribute']} where tablename = \"$lc_name\"");
    if (Sql_Affected_Rows()) Fatal_Error("Name is not unique enough");

    $query = sprintf('insert into %s (name,type,required,tablename) values("%s","%s",%d,"%s")',
    $tables["attribute"],addslashes($name),"select",1,$lc_name);
    Sql_Query($query);
    $insertid = Sql_Insert_id();

    $query = "create table $table_prefix"."listattr_$lc_name (id integer not null primary key auto_increment, name varchar(255) unique,listorder integer default 0)";
    Sql_Query($query);
    $fp = fopen("data/$val","r");
    $header = "";
    while (!feof ($fp)) {
      $buffer = fgets($fp, 4096);
      if (!ereg("#",$buffer)) {
        if (!$header)
          $header = $buffer;
        else if (trim($buffer) != "")
          Sql_Query(sprintf('insert into %slistattr_%s (name) values("%s")',$table_prefix,$lc_name,trim($buffer)));
      }
    }
    fclose ($fp);
  }
  print "Done<br/><br/>";
  print '<p>'.PageLink2("attributes","Continue").'</p>';

} else {

?>


<?php echo formStart()?>
<?php
reset($attributes);
while (list($key,$attribute) = each ($attributes)) {
  list($name,$desc) = explode(":",$key);
  printf('<input type=checkbox name="selected[]" value="%s">%s<br>', $attribute,$desc);
}
print "<input type=submit value=Add></form>";

}
?>
