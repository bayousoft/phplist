
<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr>

<?php
require_once "accesscheck.php";

if (!is_object("date")) {
	include $GLOBALS["coderoot"] . "date.php";
}
ob_end_flush();
$from = new date("from");
$to = new date("to");

$findtables = '';
$findbyselect = sprintf(' email like "%%%s%%"',$find);;
$findfield = $tables["user"].".email as display, ".$tables["user"].".bouncecount";
$findfieldname = "Email";
$find_url = '&find='.urlencode($find);

function resendConfirm($id) {
	global $tables,$envelope,$prepend;
	$userdata = Sql_Fetch_Array_Query("select * from {$tables["user"]} where id = $id");
  $lists_req = Sql_Query(sprintf('select %s.name from %s,%s where 
  	%s.listid = %s.id and %s.userid = %d',
  	$tables["list"],$tables["list"],
    $tables["listuser"],$tables["listuser"],$tables["list"],$tables["listuser"],$id));
  while ($row = Sql_Fetch_Row($lists_req)) {
  	$lists .= '  * '.$row[0]."\n";
  }

	if ($userdata["subscribepage"]) {
	  $subscribemessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("subscribemessage:".$userdata["subscribepage"],$id));
    $subject = getConfig("subscribesubject:".$userdata["subscribepage"]);
 	} else {
	  $subscribemessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("subscribemessage",$id));
    $subject = getConfig("subscribesubject");
	}

  logEvent("Resending confirmation request to ".$userdata["email"]);
  if (!TEST)
  	return sendMail($userdata["email"],$subject, $prepend.$subscribemessage,system_messageheaders($userdata["email"]),$envelope);
}

function fixEmail($email) {
  if (preg_match("#(.*)@.*hotmail.*#i",$email,$regs)) {
  	$email = $regs[1].'@hotmail.com';
  }
  if (preg_match("#(.*)@.*aol.*#i",$email,$regs)) {
  	$email = $regs[1].'@aol.com';
  }
  if (preg_match("#(.*)@.*yahoo.*#i",$email,$regs)) {
  	$email = $regs[1].'@yahoo.com';
  }
	$email = str_replace(" ","",$email);
  $email = preg_replace("#,#",".",$email);
	$email = str_replace("\.\.","\.",$email);
  $email = preg_replace("#[^\w]$#","",$email);
#  $email = preg_replace("#\.$#","",$email);
  $email = preg_replace("#\.cpm$#i","\.com",$email);
  $email = preg_replace("#\.couk$#i","\.co\.uk",$email);
  return $email;
}

function moveUser($userid) {
	global $tables;
	$newlist = $_GET["list"];
  Sql_Query(sprintf('delete from %s where userid = %d',$tables["listuser"],$userid));
  Sql_Query(sprintf('insert into %s (userid,listid) values(%d,%d)',$tables["listuser"],$userid,$newlist));
}

if (($require_login && !isSuperUser()) || !$require_login || isSuperUser()) {
  $access = accessLevel("reconcileusers");
  switch ($access) {
    case "all":
      if ($option) {
      	set_time_limit(600);
        switch ($option) {
          case "markallconfirmed":
            info( "Marking all users confirmed");
            Sql_Query("update {$tables["user"]} set confirmed = 1");
            $total =Sql_Affected_Rows();
            print "$total users apply<br/>";
            break;
          case "markallhtml":
            info( "Marking all users to receive HTML");
            Sql_Query("update {$tables["user"]} set htmlemail = 1");
            $total =Sql_Affected_Rows();
            print "$total users apply<br/>";
            break;
          case "markalltext":
            info( "Marking all users to receive text");
            Sql_Query("update {$tables["user"]} set htmlemail = 0");
            $total =Sql_Affected_Rows();
            print "$total users apply<br/>";
            break;
          case "nolists":
            info( "Deleting users who are not on any list");
            $req = Sql_Query(sprintf('select %s.id from %s
              left join %s on %s.id = %s.userid
              where userid is NULL',
              $tables["user"],$tables["user"],$tables["listuser"],$tables["user"],$tables["listuser"]));
            $total =Sql_Affected_Rows();
            print "$total users apply<br/>";
            $todo = "deleteUser";
            break;
          case "nolistsnewlist":
            info( "Moving users who are not on any list to ".ListName($list));
            $req = Sql_Query(sprintf('select %s.id from %s
              left join %s on %s.id = %s.userid
              where userid is NULL',
              $tables["user"],$tables["user"],$tables["listuser"],$tables["user"],$tables["listuser"]));
            $total =Sql_Affected_Rows();
            print "$total users apply<br/>";
            $todo = "moveUser";
            break;
          case "bounces":
            info( "Deleting users with more than ".$_REQUEST["num"]." bounces");
            $req = Sql_Query(sprintf('select id from %s
              where bouncecount > %d',
              $tables["user"],$_REQUEST["num"]
            ));
            $total = Sql_Affected_Rows();
            print "$total users apply<br/>";
            $todo = "deleteUser";
            break;
          case "resendconfirm":
 						$fromval = $from->getDate("from");
 						$toval = $from->getDate("to");
            Info("Resending request for confirmation to users who signed up after $fromval and before $toval");
            $req = Sql_Query(sprintf('select id from %s
              where entered > "%s" and entered < "%s" and !confirmed',
              $tables["user"],$fromval,$toval
            ));
            $total = Sql_Affected_Rows();
            print "$total users apply<br/>";
            $todo = "resendConfirm";
            break;
          case "deleteunconfirmed":
 						$fromval = $from->getDate("from");
 						$toval = $from->getDate("to");
            Info("Deleting unconfirmed users who signed up after $fromval and before $toval");
            $req = Sql_Query(sprintf('select id from %s
              where entered > "%s" and entered < "%s" and !confirmed',
              $tables["user"],$fromval,$toval
            ));
            $total = Sql_Affected_Rows();
            print "$total users apply<br/>";
            $todo = "deleteuser";
            break;
        }
        $c = 1;
        ob_end_flush();
        if ($todo && $req)
        while ($user = Sql_Fetch_Array($req)) {
          if ($c % 10 == 0) {
            print "$c/$total<br/>\n";
            flush();
          }
					set_time_limit(60);
          $todo($user["id"]);
          $c++;
        }
        if ($total)
          print "$total/$total<br/>";
      }
  		if ($option == "invalidemail") {
      	Info("Listing users with an invalid email");
        flush();
       	$req = Sql_Query("select id,email from {$tables["user"]}");
       	$c=0;
        print '<form method=post>';
        while ($row = Sql_Fetch_Array($req)) {
					set_time_limit(60);
        	if (!is_email($row["email"])) {
          	$c++;
            if (is_array($tagged) && in_array($row["id"],array_keys($tagged))) {
            	deleteUser($row["id"]);
              $deleted++;
            } else {
	            $list .= sprintf('<input type=checkbox name="tagged[%d]" value="1">&nbsp;  ',$row["id"]).PageLink2("user&id=".$row["id"]."&returnpage=reconcileusers&returnoption=invalidemail","User ".$row["id"]). "    [".$row["email"].']<br/>';
            }
         	}
        }
        if ($deleted)
        	print "$deleted Users deleted<br/>";
        print "$c Users apply<br/>$list\n";
        if ($c)
	        print '<input type=submit name="deletetagged" value="Delete Tagged Users"></form>';
  		} elseif ($option == "fixinvalidemail") {
      	Info("Trying to fix users with an invalid email");
        flush();
       	$req = Sql_Query("select id,email from {$tables["user"]}");
      	$c=0;
        while ($row = Sql_Fetch_Array($req)) {
					set_time_limit(60);
        	if (!is_email($row["email"])) {
          	$c++;
            $fixemail = fixEmail($row["email"]);
            if (is_email($fixemail)) {
            	Sql_Query(sprintf('update %s set email = "%s" where id = %d',$tables["user"],$fixemail,$row["id"]),0);
              $list .= PageLink2("user&id=".$row["id"]."&returnpage=reconcileusers&returnoption=fixinvalidemail","User ".$row["id"]). "    [".$row["email"].'] => fixed to '. $fixemail.'<br/>';
							$fixed++;
       			} else {
            	$notfixed++;
	            $list .= PageLink2("user&id=".$row["id"]."&returnpage=reconcileusers&returnoption=fixinvalidemail","User ".$row["id"]). "    [".$row["email"].']<br/>';
          	}
         	}
        }
        print "$fixed Users fixed<br/>$notfixed Users could not be fixed<br/>$list\n";
  		} elseif ($option == "deleteinvalidemail") {
      	Info("Deleting users with an invalid email");
        flush();
       	$req = Sql_Query("select id,email from {$tables["user"]}");
      	$c=0;
        while ($row = Sql_Fetch_Array($req)) {
					set_time_limit(60);
        	if (!is_email($row["email"])) {
          	$c++;
            deleteUser($row["id"]);
         	}
        }
        print "$c Users deleted<br/>\n";
    	} elseif ($option == "removestaleentries") {
      	Info("Cleaning some user tables of invalid entries");
        # some cleaning up of data:
        $req = Sql_Verbose_Query("select {$tables["usermessage"]}.userid
          from {$tables["usermessage"]} left join {$tables["user"]} on {$tables["usermessage"]}.userid = {$tables["user"]}.id
          where {$tables["user"]}.id IS NULL group by {$tables["usermessage"]}.userid");
        print Sql_Affected_Rows() . " entries apply<br/>";
        while ($row = Sql_Fetch_Row($req)) {
          Sql_Query("delete from {$tables["usermessage"]} where userid = $row[0]");
        }
        $req = Sql_Verbose_Query("select {$tables["user_attribute"]}.userid
          from {$tables["user_attribute"]} left join {$tables["user"]} on {$tables["user_attribute"]}.userid = {$tables["user"]}.id
          where {$tables["user"]}.id IS NULL group by {$tables["user_attribute"]}.userid");
        print Sql_Affected_Rows() . " entries apply<br/>";
        while ($row = Sql_Fetch_Row($req)) {
          Sql_Query("delete from {$tables["user_attribute"]} where userid = $row[0]");
        }
        $req = Sql_Verbose_Query("select {$tables["listuser"]}.userid
          from {$tables["listuser"]} left join {$tables["user"]} on {$tables["listuser"]}.userid = {$tables["user"]}.id
          where {$tables["user"]}.id IS NULL group by {$tables["listuser"]}.userid");
        print Sql_Affected_Rows() . " entries apply<br/>";
        while ($row = Sql_Fetch_Row($req)) {
          Sql_Query("delete from {$tables["listuser"]} where userid = $row[0]");
        }
        $req = Sql_Verbose_Query("select {$tables["usermessage"]}.userid
          from {$tables["usermessage"]} left join {$tables["user"]} on {$tables["usermessage"]}.userid = {$tables["user"]}.id
          where {$tables["user"]}.id IS NULL group by {$tables["usermessage"]}.userid");
        print Sql_Affected_Rows() . " entries apply<br/>";
        while ($row = Sql_Fetch_Row($req)) {
          Sql_Query("delete from {$tables["usermessage"]} where userid = $row[0]");
        }
        $req = Sql_Verbose_Query("select {$tables["user_message_bounce"]}.user
          from {$tables["user_message_bounce"]} left join {$tables["user"]} on {$tables["user_message_bounce"]}.user = {$tables["user"]}.id
          where {$tables["user"]}.id IS NULL group by {$tables["user_message_bounce"]}.user");
        print Sql_Affected_Rows() . " entries apply<br/>";
        while ($row = Sql_Fetch_Row($req)) {
          Sql_Query("delete from {$tables["user_message_bounce"]} where user = $row[0]");
        }
			}

      $table_list = $tables["user"].$findtables;
      if ($find) {
        $listquery = "select {$tables["user"]}.id,$findfield,{$tables["user"]}.confirmed from ".$table_list." where $findbyselect";
        $count = Sql_query("SELECT count(*) FROM ".$table_list ." where $findbyselect");
        $unconfirmedcount = Sql_query("SELECT count(*) FROM ".$table_list ." where !confirmed && $findbyselect");
        if ($_GET["unconfirmed"])
          $listquery .= ' and !confirmed';
      } else {
        $listquery = "select {$tables["user"]}.id,$findfield,{$tables["user"]}.confirmed from ".$table_list;
        $count = Sql_query("SELECT count(*) FROM ".$table_list);
        $unconfirmedcount = Sql_query("SELECT count(*) FROM ".$table_list." where !confirmed");
      }
      $delete_message = '<br />Delete will delete user and all listmemberships<br />';
      break;
    case "none":
    default:
      $table_list = $tables["user"];
      $subselect = " where id = 0";break;
  }
}

if (isset($delete)) {
  # delete the index in delete
  print "deleting $delete ..\n";
  Sql_query("delete from ".$tables["listuser"]." where userid = $delete");
  Sql_query("delete from ".$tables["user"]." where id = $delete");
  Sql_query("delete from ".$tables["user_attribute"]." where userid = $delete");
  Sql_query("delete from ".$tables["user_message_bounce"]." where user = $delete");
  Sql_query("delete from ".$tables["usermessage"]." where userid = $delete");

  print "..Done<br><hr><br>\n";
  Redirect("users&start=$start");
}

$totalres = Sql_fetch_Row($unconfirmedcount);
$totalunconfirmed = $totalres[0];
$totalres = Sql_fetch_Row($count);
$total = $totalres[0];

print "<p><b>$total Users</b>";
print $find ? " found": " in the database";
print "</p>";
?>


<p>To delete all users who are not subscribed to any list, <?=PageLink2("reconcileusers&option=nolists","Click here")?>
<p>To find users who have an invalid email, <?=PageLink2("reconcileusers&option=invalidemail","Click here")?>
<p>To delete users who have an invalid email, <?=PageLink2("reconcileusers&option=deleteinvalidemail","Click here")?>
<p>To mark all users to receive HTML, <?=PageLink2("reconcileusers&option=markallhtml","Click here")?>
<p>To mark all users to receive text, <?=PageLink2("reconcileusers&option=markalltext","Click here")?>
<p>To mark all users confirmed, <?=PageLink2("reconcileusers&option=markallconfirmed","Click here")?>
<p>To try to (automatically) fix emails for users who have an invalid email, <?=PageLink2("reconcileusers&option=fixinvalidemail","Click here")?>
<p>To remove stale entries from tables, <?=PageLink2("reconcileusers&option=removestaleentries","Click here")?>
<hr>
<form method=get>
<input type=hidden name="page" value="reconcileusers">
<input type=hidden name="option" value="nolistsnewlist">
<p>To move all users who are not subscribed to any list to
<select name="list">
<?
$req = Sql_Query(sprintf('select id,name from %s order by listorder',$tables["list"]));
while ($row = Sql_Fetch_Row($req)) {
	printf ('<option value="%d">%s</option>',$row[0],$row[1]);
}
?>
</select><input type=submit value="Click here"></form>
<hr>
<form method=get>
<input type=hidden name="page" value="reconcileusers">
<input type=hidden name="option" value="bounces">
<p>To delete all users with more than
<select name="num">
<option>5</option>
<option>10</option>
<option selected>15</option>
<option>20</option>
<option>50</option>
</select> bounces <input type=submit value="Click here"></form>
<p>Note: this will use the total count of bounces on a user, not consecutive bounces</p>
<hr>
<form method=get>
<table><tr><td colspan=2>
To resend the request for confirmation to users who signed up and have not confirmed their subscription</td></tr>
<tr><td>Date they signed up after:</td><td><?=$from->showInput("","",$fromval);?></td></tr>
<tr><td>Date they signed up before:</td><td><?=$to->showInput("","",$toval);?></td></tr>
<tr><td colspan=2>Text to prepend to email:</td></tr>
<tr><td colspan=2><textarea name="prepend" rows="10" cols="60">
Sorry, to bother you. We are cleaning up our database and
it appears you have previously signed up to our mailinglists
and not confirmed your subscription.

We would like to give you the opportunity to re-confirm your
subscription. The details of how to confirm are below.

</textarea>
</td></tr>
</table>
<input type=hidden name="page" value="reconcileusers">
<input type=hidden name="option" value="resendconfirm">
<input type=submit value="Click here"></form>

<hr>
<form method=get>
<table><tr><td colspan=2>
To delete users who signed up and have not confirmed their subscription</td></tr>
<tr><td>Date they signed up after:</td><td><?=$from->showInput("","",$fromval);?></td></tr>
<tr><td>Date they signed up before:</td><td><?=$to->showInput("","",$toval);?></td></tr>
</table>
<input type=hidden name="page" value="reconcileusers">
<input type=hidden name="option" value="deleteunconfirmed">
<input type=submit value="Click here"></form>

