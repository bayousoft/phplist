<?php
require_once "accesscheck.php";

# $Id: export.php,v 1.2 2004-05-11 11:41:38 mdethmers Exp $

# export users from PHPlist

include "date.php";

$from = new date("from");
$to = new date("to");

include $GLOBALS["coderoot"] . "structure.php";
if ($process == "Export") {
	$fromval= $from->getDate("from");
  $toval =  $to->getDate("to");
  if ($list)
	  $filename = "PHPList Export on ".ListName($list)." from $fromval to $toval (".date("Y-M-d").").csv";
	else
	  $filename = "PHPList Export from $fromval to $toval (".date("Y-M-d").").csv";
	ob_end_clean();
	header("Content-type: ".$GLOBALS["export_mimetype"]);
	header("Content-disposition:  attachment; filename=\"$filename\"");

	if (is_array($cols)) {
    while (list ($key,$val) = each ($DBstruct["user"])) {
      if (in_array($key,$cols)) {
        if (!ereg("sys",$val[1])) {
          print $val[1]."\t";
        } elseif (ereg("sysexp:(.*)",$val[1],$regs)) {
          print $regs[1]."\t";
        }
      }
    }
 	}
  $attributes = array();
  if (is_array($attrs)) {
    $res = Sql_Query("select id,name,type from {$tables['attribute']}");
    while ($row = Sql_fetch_array($res)) {
      if (in_array($row["id"],$attrs)) {
        print trim(stripslashes($row["name"])) ."\t";
        array_push($attributes,array("id"=>$row["id"],"type"=>$row["type"]));
      }
    }
  }
  print 'List Membership'."\n";
  if ($list)
    $result = Sql_query(sprintf('SELECT %s.* FROM
      %s,%s where %s.id = %s.userid and %s.listid = %s and %s.%s >= "%s" and %s.%s  <= "%s"
      ',$tables['user'],$tables['user'],$tables['listuser'],
        $tables['user'],$tables['listuser'],$tables['listuser'],$list,$tables['user'],$column,$fromval,$tables['user'],$column,$toval)
      );
  else
    $result = Sql_query(sprintf('
      SELECT * FROM %s where %s >= "%s" and %s  <= "%s"',
      $tables['user'],$column,$fromval,$column,$toval));

# print Sql_Affected_Rows()." users apply<br/>";
  while ($user = Sql_fetch_array($result)) {
    set_time_limit(500);
    reset($cols);
    while (list ($key,$val) = each ($cols))
      print strtr($user[$val],"\t",",")."\t";
    reset($attributes);
    while (list($key,$val) = each ($attributes)) {
      print strtr(UserAttributeValue($user["id"],$val["id"]),"\t",",")."\t";
    }
    $lists = Sql_query("SELECT listid,name FROM
      {$tables['listuser']},{$tables['list']} where userid = ".$user["id"]." and
      {$tables['listuser']}.listid = {$tables['list']}.id");
    if (!Sql_Affected_rows($lists))
      print "No Lists";
    while ($list = Sql_fetch_array($lists)) {
      print $list["name"]." ";
    }
    print "\n";
  }
  exit;
}

if ($list)
	print "<h2>Export users on ".ListName($list)."</h2>";


?>
<form method=post>

<br/><br/>
<table>

<tr><td>Date From:</td><td><?php echo $from->showInput("","",$fromval);?></td></tr>
<tr><td>Date To: </td><td><?php echo $to->showInput("","",$toval);?></td></tr>
<tr><td colspan=2>What date needs to be used:</td></tr>
<tr><td><input type=radio name="column" value="entered" checked></td><td>When they signed up</td></tr>
<tr><td><input type=radio name="column" value="modified"></td><td>When the record was changed</td></tr>
</td></tr>
<tr><td colspan=2>Select the columns to include in the export</td></tr>

<?php
  $cols = array();
  while (list ($key,$val) = each ($DBstruct["user"])) {
    if (!ereg("sys",$val[1])) {
      printf ("\n".'<tr><td><input type=checkbox name="cols[]" value="%s" checked></td><td>%s</td></tr>',$key,$val[1]);
    } elseif (ereg("sysexp:(.*)",$val[1],$regs)) {
      printf ("\n".'<tr><td><input type=checkbox name="cols[]" value="%s" checked></td><td>%s</td></tr>',$key,$regs[1]);
    }
  }
  $res = Sql_Query("select id,name,tablename,type from {$tables['attribute']} order by listorder");
  $attributes = array();
  while ($row = Sql_fetch_array($res)) {
    printf ("\n".'<tr><td><input type=checkbox name="attrs[]" value="%s" checked></td><td>%s</td></tr>',$row["id"],stripslashes($row["name"]));
  }

?>
</table>
<input type=submit name="process" value="Export"></form>

