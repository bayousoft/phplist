<script language="Javascript" src="js/jslib.js" type="text/javascript"></script>
<hr>
<H2>Database administration</H2>
<H1>WARNING: this page is intended to aid development. Return to the <a href="?page=home">home page</a> now if you're not sure what this is.</H1>
<?php
require_once dirname(__FILE__) . '/accesscheck.php';

if (!is_object("date")) {
  include $GLOBALS["coderoot"] . "date.php";
}

ob_end_flush();
$from = new date("from");
$to = new date("to");
$fromval = $toval = $todo = "";

$findtables = '';
$findbyselect = sprintf(' email like "%%%s%%"', $find);
;
$findfield = $tables["user"] . ".email as display, " . $tables["user"] . ".bouncecount";
$findfieldname = "Email";
$find_url = '&find=' . urlencode($find);

function resendConfirm($id) {
  global $tables, $envelope;
  $userdata = Sql_Fetch_Array_Query("select * from {$tables["user"]} where id = $id");
  $lists_req = Sql_Query(sprintf('select %s.name from %s,%s where
    %s.listid = %s.id and %s.userid = %d', $tables["list"], $tables["list"], $tables["listuser"], $tables["listuser"], $tables["list"], $tables["listuser"], $id));
  while ($row = Sql_Fetch_Row($lists_req)) {
    $lists .= '  * ' . $row[0] . "\n";
  }

  if ($userdata["subscribepage"]) {
    $subscribemessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("subscribemessage:" . $userdata["subscribepage"], $id));
    $subject = getConfig("subscribesubject:" . $userdata["subscribepage"]);
  } else {
    $subscribemessage = ereg_replace('\[LISTS\]', $lists, getUserConfig("subscribemessage", $id));
    $subject = getConfig("subscribesubject");
  }

  logEvent($GLOBALS['I18N']->get('Resending confirmation request to') . " " . $userdata["email"]);
  if (!TEST)
    return sendMail($userdata["email"], $subject, $_REQUEST["prepend"] . $subscribemessage, system_messageheaders($userdata["email"]), $envelope);
}

function fixEmail($email) {
  if (preg_match("#(.*)@.*hotmail.*#i", $email, $regs)) {
    $email = $regs[1] . '@hotmail.com';
  }
  if (preg_match("#(.*)@.*aol.*#i", $email, $regs)) {
    $email = $regs[1] . '@aol.com';
  }
  if (preg_match("#(.*)@.*yahoo.*#i", $email, $regs)) {
    $email = $regs[1] . '@yahoo.com';
  }
  # $email = str_replace(" ","",$email);
  $email = preg_replace("#,#", ".", $email);
  $email = str_replace("\.\.", "\.", $email);
  #  $email = preg_replace("#[^\w]$#","",$email);
  #  $email = preg_replace("#\.$#","",$email);
  $email = preg_replace("#\.cpm$#i", "\.com", $email);
  $email = preg_replace("#\.couk$#i", "\.co\.uk", $email);
  return $email;
}

function mergeUser($userid) {
  $duplicate = Sql_Fetch_Array_Query("select * from {$GLOBALS["tables"]["user"]} where id = $userid");
  printf('<br/>%s', $duplicate["email"]);
  if (preg_match("/^duplicate[^ ]* (.*)/", $duplicate["email"], $regs)) {
    print "-> " . $regs[1];
    $email = $regs[1];
  }
  elseif (preg_match("/^([^ ]+@[^ ]+) \(\d+\)/", $duplicate["email"], $regs)) {
    print "-> " . $regs[1];
    $email = $regs[1];
  } else {
    $email = "";
  }
  if ($email) {
    $orig = Sql_Fetch_Row_Query(sprintf('select id from %s where email = "%s"', $GLOBALS["tables"]["user"], $email));
    if ($orig[0]) {
      print " " . $GLOBALS['I18N']->get("user found");
      $umreq = Sql_Query("select * from {$GLOBALS["tables"]["usermessage"]} where userid = " . $duplicate["id"]);
      while ($um = Sql_Fetch_Array($umreq)) {
        Sql_Query(sprintf('update %s set userid = %d, entered = "%s" where userid = %d and entered = "%s"', $GLOBALS["tables"]["usermessage"], $orig[0], $um["entered"], $duplicate["id"], $um["entered"]));
      }
      $bncreq = Sql_Query("select * from {$GLOBALS["tables"]["user_message_bounce"]} where user = " . $duplicate["id"]);
      while ($bnc = Sql_Fetch_Array($bncreq)) {
        Sql_Query(sprintf('update %s set user = %d, time = "%s" where user = %d and time = "%s"', $GLOBALS["tables"]["user_message_bounce"], $orig[0], $bnc["time"], $duplicate["id"], $bnc["time"]));

      }
      Sql_Query("delete from {$GLOBALS["tables"]["listuser"]} where userid = " . $duplicate["id"]);
    } else {
      print " " . $GLOBALS['I18N']->get("no user found");
    }
    flush();
  } else {
    print "-> " . $GLOBALS['I18N']->get("unable to find original email");
  }
}

function moveUser($userid) {
  global $tables;
  $newlist = $_GET["list"];
  Sql_Query(sprintf('delete from %s where userid = %d', $tables["listuser"], $userid));
  Sql_Query(sprintf('insert into %s (userid,listid,entered) values(%d,%d,current_timestamp)', $tables["listuser"], $userid, $newlist));
}

function addUniqID($userid) {
  Sql_query(sprintf('update %s set uniqid = "%s" where id = %d', $GLOBALS["tables"]["user"], getUniqID(), $userid));
}

function deleteMsgHistory($msgid) {
  global $tables;
  Sql_Query(sprintf('delete from %s where messageid = %d', $tables["usermessage"], $msgid));
  Sql_Query(sprintf('delete from %s where message = %d', $tables["user_message_bounce"], $msgid));
  Sql_Query(sprintf('delete from %s where message = %d', $tables["user_message_forward"], $msgid));
  Sql_Query(sprintf('delete from %s where messageid = %d', $tables["linktrack"], $msgid));
  Sql_Query(sprintf('delete from %s where messageid = %d', $tables["linktrack_userclick"], $msgid));
}

function deleteRssHistory($userid) {
  global $tables;
  Sql_Query(sprintf('delete from %s where userid = %d', $tables["rssitem_user"], $userid));
  Sql_Query(sprintf('delete from %s where userid = %d', $tables["usermessage"], $userid));
  Sql_Query(sprintf('delete from %s where userid = %d', $tables["user_rss"], $userid));
}

if (($require_login && !isSuperUser()) || !$require_login || isSuperUser()) {
  $access = accessLevel("dbadmin");
  switch ($access) {
    case "all" :
      if ($_GET["option"]) {
        set_time_limit(600);
        switch ($_GET["option"]) {

          case "nolists" :
            info($GLOBALS['I18N']->get("Deleting users who are not on any list"));
            $req = Sql_Query(sprintf('SELECT %s.id ' .
            'FROM %s  LEFT JOIN %s ON %s.id = %s.userid ' .
            'WHERE userid IS null', $tables["user"], $tables["user"], $tables["listuser"], $tables["user"], $tables["listuser"]));
            $total = Sql_Affected_Rows();
            print "$total " . $GLOBALS['I18N']->get('users apply') . "<br/>";
            $todo = "deleteUser";
            break;

          case "delusers" :
            flush();
            $req = Sql_Query("select id,email from {$tables["user"]}");
            $c = 0;
            print '<form method=post>';
            while ($row = Sql_Fetch_Array($req)) {
              set_time_limit(60);
              $c++;
              if (is_array($tagged) && in_array($row["id"], array_keys($tagged))) {
                deleteUser($row["id"]);
                $deleted++;
              } else {
                $list .= sprintf('<input type=checkbox name="tagged[%d]" value="1">&nbsp;  ', $row["id"]) . PageLink2("user&id=" . $row["id"] . "&returnpage=dbadmin&returnoption=delusers", "User " . $row["id"]) . "    [" . $row["email"] . ']<br/>';
              }
            }
            if ($deleted)
              print $deleted . " " . $GLOBALS['I18N']->get('users deleted') . "<br/>";
            print $c . " " . $GLOBALS['I18N']->get('users apply') . "<br/>$list\n";
            if ($c)
              print '<input type=submit name="deletetagged" value="' . $GLOBALS['I18N']->get('Delete Tagged Users') . '"></form>';
            break;

          case "delmsghistory" :
            flush();
            $req = Sql_Query("select id,subject from {$tables["message"]} where status is not null");
            $c = 0;
            print '<form method=post>';
            while ($row = Sql_Fetch_Array($req)) {
              set_time_limit(60);
              $c++;
              if (is_array($tagged) && in_array($row["id"], array_keys($tagged))) {
                deleteMsgHistory($row["id"]);
                $deleted++;
              }
              $list .= sprintf('<input type=checkbox name="tagged[%d]" value="1">&nbsp;  ', $row["id"]) . PageLink2("message&id=" . $row["id"] . "&returnpage=dbadmin&returnoption=delmsghistory", "Message " . $row["id"]) . "    [" . $row["subject"] . ']<br/>';
            }
            if ($deleted)
              print $GLOBALS['I18N']->get('History of ') . $deleted . " " . $GLOBALS['I18N']->get('messages deleted') . "<br/>";
            print $c . " " . $GLOBALS['I18N']->get('messages apply') . "<br/>$list\n";
            if ($c)
              print '<input type=submit name="deletetagged" value="' . $GLOBALS['I18N']->get('Delete History of Tagged Messages') . '"></form>';
            break;

          case "delrsshistory" :
            flush();
            $req = Sql_Query("SELECT DISTINCT userid, count(itemid) AS itemcount, entered FROM {$tables["rssitem_user"]} GROUP BY userid, entered");
            $c = 0;
            print '<form method=post>';
            while ($row = Sql_Fetch_Array($req)) {
              set_time_limit(60);
              $c++;
              if (is_array($tagged) && in_array($row["userid"], array_keys($tagged))) {
                deleteRssHistory($row["userid"]);
                $deleted++;
              }
              $list .= sprintf('<input type=checkbox name="tagged[%d]" value="1">&nbsp;  ', $row["userid"]) . PageLink2("user&id=" . $row["userid"] . "&returnpage=dbadmin&returnoption=delrsshistory", "User " . $row["userid"]) . "    [" . $row["itemcount"] . " items send on " . $row["entered"] . ']<br/>';
            }
            if ($deleted)
              print $GLOBALS['I18N']->get('RSS history of ') . $deleted . " " . $GLOBALS['I18N']->get('users deleted') . "<br/>";
            print $c . " " . $GLOBALS['I18N']->get('users apply') . "<br/>$list\n";
            if ($c)
              print '<input type=submit name="deletetagged" value="' . $GLOBALS['I18N']->get('Delete RSS History of Tagged Users') . '"></form>';
            break;

          case "markinvalidunconfirmed" :
          case "removestaleentries" :
            break;

          default :
            Info("Sorry, I don't know how to " . $_GET["option"]);
            return;
        }
        $c = 1;
        ob_end_flush();
      }

      $table_list = $tables["user"] . $findtables;
      if ($find) {
        $listquery = "select {$tables["user"]}.id,$findfield,{$tables["user"]}.confirmed from " . $table_list . " where $findbyselect";
        $count = Sql_query("SELECT count(*) FROM " . $table_list . " where $findbyselect");
        $unconfirmedcount = Sql_query("SELECT count(*) FROM " . $table_list . " where !confirmed && $findbyselect");
        if ($_GET["unconfirmed"])
          $listquery .= ' and !confirmed';
      } else {
        $listquery = "select {$tables["user"]}.id,$findfield,{$tables["user"]}.confirmed from " . $table_list;
        $count = Sql_query("SELECT count(*) FROM " . $table_list);
        $unconfirmedcount = Sql_query("SELECT count(*) FROM " . $table_list . " where !confirmed");
      }
      $delete_message = ("<br />" . $GLOBALS['I18N']->get("Delete will delete user and all listmemberships") . "<br />");
      break;

    case "none" :
    default :
      $table_list = $tables["user"];
      $subselect = " where id = 0";
      break;
  }
}

if (isset ($_GET["delete"])) {
  $delete = sprintf('%d', $_GET["delete"]);
  # delete the index in delete
  print "deleting $delete ..\n";
  deleteUser($delete);
  print "... " . $GLOBALS['I18N']->get('Done') . "<br><hr><br>\n";
  Redirect("users&start=$start");
}

$totalres = Sql_fetch_Row($unconfirmedcount);
$totalunconfirmed = $totalres[0];
$totalres = Sql_fetch_Row($count);
$total = $totalres[0];

print "<p><b>" . $total . " " . $GLOBALS['I18N']->get('Users') . "</b>";
print $find ? " " . $GLOBALS['I18N']->get("found") : " " . $GLOBALS['I18N']->get("in the database");
print "</p>";
?>

<p><?php echo PageLink2("dbadmin&option=delusers",$GLOBALS['I18N']->get("Delete users..."))?>
<p><?php echo PageLink2("dbadmin&option=delmsghistory",$GLOBALS['I18N']->get("Delete message history..."))?>
<p><?php echo PageLink2("dbadmin&option=delrsshistory",$GLOBALS['I18N']->get("Delete RSS history..."))?>
<p><?php echo PageLink2("dbadmin",$GLOBALS['I18N']->get("Return to database manager"))?>
<hr>
