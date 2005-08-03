<?php
require_once dirname(__FILE__).'/accesscheck.php';

# $Id: export.php,v 1.5 2005-08-03 02:36:58 mdethmers Exp $

# export users from PHPlist

include dirname(__FILE__) .'/date.php';

$from = new date("from");
$to = new date("to");
if (isset($_GET['list'])) {
  $list = sprintf('%d',$_GET['list']);
} else {
  $list = 0;
}

include dirname(__FILE__). '/structure.php';
if ($process == $GLOBALS['I18N']->get('Export')) {
  $fromval= $from->getDate("from");
  $toval =  $to->getDate("to");
  if ($list)
    $filename = sprintf($GLOBALS['I18N']->get('ExportOnList'),ListName($list),$fromval,$toval,date("Y-M-d"));
  else
    $filename = sprintf($GLOBALS['I18N']->get('ExportFromval'),$fromval,$toval,date("Y-M-d"));
  ob_end_clean();
  $filename = trim(strip_tags($filename));

  header("Content-type: ".$GLOBALS["export_mimetype"]);
  header("Content-disposition:  attachment; filename=\"$filename\"");
  $col_delim = "\t";
  if (EXPORT_EXCEL) {
    $col_delim = ",";
  }
  $row_delim = "\n";

  if (is_array($cols)) {
    while (list ($key,$val) = each ($DBstruct["user"])) {
      if (in_array($key,$cols)) {
        if (!ereg("sys",$val[1])) {
          print $val[1].$col_delim;
        } elseif (ereg("sysexp:(.*)",$val[1],$regs)) {
          print $regs[1].$col_delim;
        }
      }
    }
   }
  $attributes = array();
  if (is_array($attrs)) {
    $res = Sql_Query("select id,name,type from {$tables['attribute']}");
    while ($row = Sql_fetch_array($res)) {
      if (in_array($row["id"],$attrs)) {
        print trim(stripslashes($row["name"])) .$col_delim;
        array_push($attributes,array("id"=>$row["id"],"type"=>$row["type"]));
      }
    }
  }
  print $GLOBALS['I18N']->get('ListMembership').$row_delim;
  if ($list)
    $result = Sql_query(sprintf('SELECT %s.* FROM
      %s,%s where %s.id = %s.userid and %s.listid = %s and %s.%s >= "%s 00:00:00" and %s.%s  <= "%s 23:59:59"
      ',$tables['user'],$tables['user'],$tables['listuser'],
        $tables['user'],$tables['listuser'],$tables['listuser'],$list,$tables['user'],$column,$fromval,$tables['user'],$column,$toval)
      );
  else
    $result = Sql_query(sprintf('
      SELECT * FROM %s where %s >= "%s 00:00:00" and %s  <= "%s 23:59:59"',
      $tables['user'],$column,$fromval,$column,$toval));

# print Sql_Affected_Rows()." users apply<br/>";
  while ($user = Sql_fetch_array($result)) {
    set_time_limit(500);
    reset($cols);
    while (list ($key,$val) = each ($cols))
      print strtr($user[$val],$col_delim,",").$col_delim;
    reset($attributes);
    while (list($key,$val) = each ($attributes)) {
      $value = UserAttributeValue($user["id"],$val["id"]);
      $enclose = 0;
      if (ereg('"',$value)) {
        $value = ereg_replace('"','""',$value);
        $enclose = 1;
      }
      if (ereg($col_delim,$value)) {
        $enclose = 1;
      }
      if (ereg($row_delim,$value)) {
        $enclose = 1;
      }
      if ($enclose) {
        $value = '"'.$value .'"';
      }
      print $value.$col_delim;
    }
    $lists = Sql_query("SELECT listid,name FROM
      {$tables['listuser']},{$tables['list']} where userid = ".$user["id"]." and
      {$tables['listuser']}.listid = {$tables['list']}.id");
    if (!Sql_Affected_rows($lists))
      print "No Lists";
    while ($list = Sql_fetch_array($lists)) {
      print stripslashes($list["name"])." ";
    }
    print $row_delim;
  }
  exit;
}

if ($list)
  print sprintf($GLOBALS['I18N']->get('ExportOn'),ListName($list));


?>
<form method=post>

<br/><br/>
<table>

<tr><td><?php echo $GLOBALS['I18N']->get('DateFrom');?></td><td><?php echo $from->showInput("","",$fromval);?></td></tr>
<tr><td><?php echo $GLOBALS['I18N']->get('DateTo');?> </td><td><?php echo $to->showInput("","",$toval);?></td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('DateToUsed');?></td></tr>
<tr><td><input type=radio name="column" value="entered" checked></td><td><?php echo $GLOBALS['I18N']->get('WhenSignedUp');?></td></tr>
<tr><td><input type=radio name="column" value="modified"></td><td><?php echo $GLOBALS['I18N']->get('WhenRecordChanged');?></td></tr>
</td></tr>
<tr><td colspan=2><?php echo $GLOBALS['I18N']->get('SelectColToIn');?></td></tr>

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
<input type=submit name="process" value="<?php echo $GLOBALS['I18N']->get('Export'); ?>"></form>

