<?php
ob_end_flush();
include dirname(__FILE__).'/header.php';
if (!class_exists('gnupg')) return;

if (!Sql_Table_exists('keymanager_keys')) {
  print PageLink2('initialise','Initialise Key Manager');
  return;
}

$pl = $GLOBALS['plugins']['keymanager'];
print $pl->menu();

if (isset($_POST['content']) && isset($_POST['passphrase']) && isset($_POST['signemail']) && isset($_POST['encemail'])) {
  print '<br/>Creating message';
  $query = sprintf('insert into %s set '.
    'fromfield = "%s", '.
    'status = "testmessage" ',
    $GLOBALS['tables']["message"],
    'PGP test '.$_POST['signemail']
  );
  $result  =  Sql_query($query);
  $messageid = Sql_Insert_id();

#  foreach (array(1,0) as $htmlformatted) {
  $htmlformatted = 1;
#    foreach (array('text','html','text and HTML') as $sendformat) {
    $sendformat = 'text and HTML';
 #     foreach (array(0,1) as $htmlandtextinput) {
        $htmlandtextinput = 0;
        foreach (array(0,1) as $userhtmlpreference) {
          foreach (array(0,1) as $signed) {
            foreach (array(0,1) as $encrypted) {
 #             foreach (array(0,1) as $sendanyway) {
              $sendanyway = 1;
                $message = '<h1>HTML message</h1>';
                $textmessage = '***message***';
                print '<br/>Sending: ';
                $subj = '';
                if ($htmlformatted) $subj .= 'HTML formatted, ';
                $subj .= $sendformat.',';
                if ($htmlandtextinput) {
                  $subj .= 'both,';
                } else {
                  $subj .= 'only html,';
                  $textmessage = '';
                }
                if ($userhtmlpreference) {
                  $subj .= 'UP: html,';
                } else {
                  $subj .= 'UP: text,';
                }
                if ($signed) {
                  $subj .= 'signed,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="true",name="keymanager_signmessage_actualstate"',$GLOBALS['tables']['messagedata'],$messageid));
                  Sql_Query(sprintf('replace into %s set id=%d,data="%s",name="keymanager_passphrase"',$GLOBALS['tables']['messagedata'],$messageid,$_POST['passphrase']));
                  Sql_Query(sprintf('replace into %s set id=%d,data="%s",name="keymanager_emailtosign"',$GLOBALS['tables']['messagedata'],$messageid,$_POST['signemail']));
                } else {
                  $subj .= 'not signed,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="false",name="keymanager_signmessage_actualstate"',$GLOBALS['tables']['messagedata'],$messageid));
                  Sql_Query(sprintf('replace into %s set id=%d,data="",name="keymanager_emailtosign"',$GLOBALS['tables']['messagedata'],$messageid));
                  Sql_Query(sprintf('replace into %s set id=%d,data="",name="keymanager_passphrase"',$GLOBALS['tables']['messagedata'],$messageid));
                }
                if ($encrypted) {
                  $subj .= 'encr,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="true",name="keymanager_encryptmessage_actualstate"',$GLOBALS['tables']['messagedata'],$messageid));
                } else {
                  $subj .= 'not encr,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="false",name="keymanager_encryptmessage_actualstate"',$GLOBALS['tables']['messagedata'],$messageid));
                }
                if ($sendanyway) {
                  $subj .= 'anyway,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="true",name="keymanager_encryptmessage_sendanyway"',$GLOBALS['tables']['messagedata'],$messageid));
                } else {
                  $subj .= 'not anyway,';
                  Sql_Query(sprintf('replace into %s set id=%d,data="false",name="keymanager_encryptmessage_sendanyway"',$GLOBALS['tables']['messagedata'],$messageid));
                }
                Sql_Query(sprintf('update %s set htmlformatted = %d,sendformat = "%s",message = "%s",textmessage = "%s" where id = %d',$GLOBALS['tables']['message'],$htmlformatted,$sendformat,$message,$textmessage,$messageid));
                Sql_Query(sprintf('update %s set subject = "%s" where id = %d',$GLOBALS['tables']['message'],$subj,$messageid));
                print "Sending $subj\n\n\n\n\n";flush();

                if (isset($GLOBALS['cached'][$messageid])) {
                  unset($GLOBALS['cached'][$messageid]);
                }
                require_once '/home/michiel/cvs/phplist/mainbranch/phplist/public_html/lists/admin/sendemaillib.php';
                sendEmail ($messageid,$_POST['encemail'],'',$userhtmlpreference);
                flush();
              }
            }
          }
#        }
#      }
#    }
#  }

  if ($messageid) {
    Sql_Query(sprintf('delete from %s where id = %d',$GLOBALS['tables']['message'],$messageid));
    Sql_Query(sprintf('delete from %s where id = %d',$GLOBALS['tables']['messagedata'],$messageid));
  }

  print '<hr/>';
}


?>

<form method="post" action="">
<textarea name="content" rows="10" cols="50">Type some text to send</textarea>
<br/>Select email to sign with:
<select name="signemail">
<?php 
$emails = $pl->get_signing_emails();
foreach ($emails as $email) {
  print '<option>'.$email.'</option>';
}
?>
</select><br/>
Enter pass phrase: <input type="password" name="passphrase" value=""><br/>
<br/>Select email to encrypt for:
<select name="encemail">
<?php 
$emails = $pl->get_encrypting_emails();
foreach ($emails as $email) {
  print '<option>'.$email.'</option>';
}
?>
</select><br/>
<input type="submit" name="sign" value="Run Tests">
</form>
