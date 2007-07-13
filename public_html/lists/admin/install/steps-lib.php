<?php

function editVariable($keyAr,$value,$type) {
  $res = '';
  foreach ($keyAr as $key => $val) {
    if ($type == 'text') { // lets define the value, if default or request one
      $realValue = $val["values"];
    }
    else {
      $realValue = $_SESSION[$val[$value]];
    }
      if ($key[$value]) {
        if ($type == 'text' && $val[$value] !== 'database_name' && $val[$value] !== 'database_user' && $val[$value] !== 'database_password' && $val[$value] !== 'database_host') {
          if ($val['type'] == 'scalar' || $val['type'] == 'scalar_int' || $val['type'] ==  'constant') {
            $res .= '<img src="images/break-el.gif" height="1" width="100%"><div class="value_name">';
            $res .= $key.'</div>';
            $res .= '<div class="description"><span>Info:</span> '.$val['description'];
            $res .= '</div><br />';
          }
        }
        switch ($val['type']) {
          case "scalar":
          if ($_SESSION['dbCreatedSuccesfully'] == TRUE) {
            if ($val[$value] == 'database_name' || $val[$value] == 'database_user' || $val[$value] == 'database_password' || $val[$value] == 'database_host') {
              $res .= '<input type="hidden" value="'.$_SESSION[$val[$value]];
              $res .= '" name="'.$val[$value].'">';
              break 1;
            }
          }
          if ($val[$value] == 'database_name' || $val[$value] == 'database_user' || $val[$value] == 'database_password' || $val[$value] == 'database_host') {
            if ($_SESSION['dbCreatedSuccesfully'] == FALSE) {
              $realValue = $_SESSION[$val[$value]];
            }
            $res .= '<input type="'.$type.'" value="'.$realValue;
            $res .= '" name="'.$val[$value].'"> ';
          }
          elseif ($val[$value] == 'message_envelope') {
            if ($type == 'text') {
              $realValue = 'bounces@'.$_SERVER["SERVER_NAME"];
            }
            $res .= '<input type="'.$type.'" value="'.$realValue;
            $res .= '" name="'.$val[$value].'"> ';
          }
          elseif ($val[$value] == 'language_module' && $type == 'text') {
            $res .= languagePack($val[$value],"");
          }
           else {
             $res .= '<input type="'.$type.'" value="'.$realValue;
             $res .= '" name="'.$val[$value].'"> ';
           }
           break;
           case "scalar_int":
           $res .= '<input type="'.$type.'" value="'.$realValue;
           $res .= '" name="'.$val[$value].'" maxlenght="4"> ';
           break;
           case "hidden_scalar":
           $res .= '<input type="hidden" value="'.$val["values"];
           $res .= '" name="'.$val[$value].'">';
           break;
           case "hidden_scalar_int":
           $res .= '<input type="hidden" value="'.$val["values"];
           $res .= '" name="'.$val[$value].'">';
           break;
           case "constant":
           $res .= '<input type="'.$type.'" maxlength="4" value="'.$realValue.'" name="'.$val[$value].'"> ';
           break;
           case "hidden_constant":
           $res .= '<input type="hidden" value="'.$val["values"].'" name="'.$val[$value].'">';
           break;
           case "hidden_array":
           $res .= '<input type="hidden" value="'.$val["values"].'" name="'.$val[$value].'">';
           break;
           case "commented":
           $res .= '<input type="hidden" value="'.$val["values"].'" name="'.$val[$value].'">';
           break;
         }
         if ($type == 'text') {
           if ($val['type'] == 'scalar' || $val['type'] == 'scalar_int' || $val['type'] ==  'constant') {
             if ($type == 'text' && $val[$value] !== 'database_name' && $val[$value] !== 'database_user' && $val[$value] !== 'database_password' && $val[$value] !== 'database_host') {
               $res .= '<br /><br />';
             }
           }
         }
       }
       else {
         $res .= $GLOBALS['$strValueNotFound'];
       }
     }
   return $res;
}
function writeToConfig($key, $requiredVars2) {
  if (!empty($key)) {
#  $configDir = $_SERVER['DOCUMENT_ROOT'].'/lists/config';
  if (isset($_SERVER["ConfigFile"]) && is_file($_SERVER["ConfigFile"])) {
    $nameConfigFile = $_SERVER['ConfigFile'];
  } elseif (isset($cline["c"]) && is_file($cline["c"])) {
    $nameConfigFile = $cline["c"];
  } elseif (isset($_ENV["CONFIG"]) && is_file($_ENV["CONFIG"])) {
    $nameConfigFile =  $_ENV["CONFIG"];
  } elseif (is_file("../config/config.php")) {
    $nameConfigFile = "../config/config.php";
  }


#  $nameConfigFile = $_SERVER['DOCUMENT_ROOT'].'/lists/config/config.php';
  $myConfigFile = fopen($nameConfigFile, 'a');
  if (!$myConfigFile || $myConfigFile == FALSE) {
    $chmod = chmod($nameConfigFile, 0666); //Try to chmod if is not writeable
    $myConfigFile = fopen($nameConfigFile, 'a'); // Try to open again
    if (!$myConfigFile || $myConfigFile == FALSE) {
      $chmod = chmod($nameConfigFile, 0646); //Try to chmod if is not writeable
      $myConfigFile = fopen($nameConfigFile, 'w'); // Try to open
      if (!$myConfigFile) {
        print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s (%s)</div>',$GLOBALS['noConfigAndChmod'], $nameConfigFile));
      }
      elseif (!$chmod || $chmod == FALSE) {
      return FALSE;
      }
    }
  }
  if ($myConfigFile) {
    print $GLOBALS["I18N"]->get(sprintf('<p>%s</p>',$GLOBALS['creatingConfig']));
    $configInfoToWrite = '';
    $configInfoToWrite .= '<?php';
    foreach ($key as $configInfo => $val) {
      if ($requiredVars2[$configInfo]) {
#      }
#      else {
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '# ';
      $configInfoToWrite .= $requiredVars2[$configInfo]["description"];
      $configInfoToWrite .= "\n";
      switch ($requiredVars2[$configInfo]["type"]) {
      case 'constant' :
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= 'define("';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= '",';
      $configInfoToWrite .= $val;
      $configInfoToWrite .= ');';
      $configInfoToWrite .= "\n";
      break;
      case 'hidden_constant' :
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= 'define("';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= '",';
      $configInfoToWrite .= $requiredVars2[$configInfo]['values'];
      $configInfoToWrite .= ');';
      $configInfoToWrite .= "\n";
      break;
      case 'hidden_array' :
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '$';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= ' = array(';
      if (is_array($requiredVars2[$configInfo]['values'])) {
      for ($i=0;$i < count($requiredVars2[$configInfo]['values']);$i++) {
      $configInfoToWrite .= '"';
      $configInfoToWrite .= $requiredVars2[$configInfo]['values'][$i];
      $configInfoToWrite .= '"';
      if ($i == count($requiredVars2[$configInfo]['values'])-1) {
      break 1;
      }
      $configInfoToWrite .= ',';
      }
      $configInfoToWrite .= ')';
      $configInfoToWrite .= ";\n";
      break 1;
      }
      $configInfoToWrite .= '"';
      $configInfoToWrite .= $val;
      $configInfoToWrite .= '")';
      $configInfoToWrite .= ";\n";
      break;
      case 'commented' :
      $configInfoToWrite .= "\n";
      break;
      case 'scalar_int' :
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '$';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= ' = ';
      $configInfoToWrite .= $val;
      $configInfoToWrite .= ";\n";
      break;
      case 'hidden_scalar_int' :
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '$';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= ' = ';
      $configInfoToWrite .= $val;
      $configInfoToWrite .= ";\n";
      if ($configInfo == 'error_level') {
        break 2;
      }
      else {
        break;
      }
      default:
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '$';
      $configInfoToWrite .= $configInfo;
      $configInfoToWrite .= ' = ';
      $configInfoToWrite .= '"';
      $configInfoToWrite .= $val;
      $configInfoToWrite .= '"';
      $configInfoToWrite .= ";\n";
      }
      }
    }
    $configInfoToWrite .= '?>';
    $configInfoToWrite .= "\n";
    $configText = sprintf('%s',$configInfoToWrite);
    substr($configText,1);
    $myConfigFileOpen = fwrite($myConfigFile, $configText);
    $configCreatedValue = 0;
    if (!$myConfigFileOpen) {
      print $GLOBALS["I18N"]->get(sprintf('%s', $GLOBALS['cantWriteToConfig']));
    }
    else {
      if (file_exists($nameConfigFile)) {
        print $GLOBALS["I18N"]->get(sprintf("<br /><b>%s</b>", $GLOBALS['configCreatedOk']));
        $configCreatedValue = 1;
      }
      else {
        print $GLOBALS["I18N"]->get(printf("%s", $GLOBALS['configDoesntExist']));
      }
    }
    /* LETS TRY TO HACK IT A LITTLE BIT :-) if this works we are Maradona... */

/*    $ftpConnection = ftp_connect($_SESSION['database_host']);
    $ftpLogin = ftp_login($ftpConnection, $_SESSION['database_user'], $_SESSION['database_password']);
    $ftpChmod = ftp_chmod($ftpConnection, 0644, $nameConfigFile);
    if ($ftpConnection && $ftpLogin && $ftpChmod) {
      print '<!-- Chmoded with mysql details through ftp, incredible! -->';
    }
*/
    $chmodBack = chmod($nameConfigFile, 0644);
    if (!$chmodBack) {
      print $GLOBALS["I18N"]->get(sprintf($strChModBack));
      $configPerms = substr(sprintf('%o', fileperms($nameConfigFile)), -4);
      if ($configPerms !== '0644') {
        print $GLOBALS["I18N"]->get(printf('<p class="wrong">%s %s%s</p>', $GLOBALS['strConfigPerms'], $configPerms, $GLOBALS['strChangeChmod']));
      }
    }
  $close = fclose($myConfigFile);
  return TRUE;
  }
  }
  else {  // We dont want an empty config file
    return FALSE; # write something instead an empty config // This is ok, cant be an empty config, fixed
  }
}

function checkScalarInt($sessionValues, $requiredVars) {

  foreach ($sessionValues as $key => $val) {
    if (is_numeric($requiredVars[$key]['values'])) {
//      $val = intval($val);

      if (!is_numeric($val)) {
        print $GLOBALS["I18N"]->get('<p style="color:#000fff;"><b>'.$key.'</b>'.$GLOBALS['strErraticValue'].'<span style="color:#f00;">'.$val.'</span><br />');
        if (preg_match('/[0-9]+/', $val, $matches)) {
          print $GLOBALS["I18N"]->get(printf('%s%s</p>', $GLOBALS['strChangedForThis'], $matches[0]));
          $_SESSION[$key] = $matches[0];
        }
        else {
          print $GLOBALS["I18N"]->get(printf('%s%s</p>', $GLOBALS['strChangedForThis'], $requiredVars[$key]['values']));
          $_SESSION[$key] = $requiredVars[$key]['values'];
        }
      }
    }
  }
}


function processPopTest ($server,$user,$password) {
$port =  $_REQUEST["bounce_mailbox_port"];
if (!$port) {
  $port = '110/pop3/notls';
}
set_time_limit(6000);
$link=imap_open("{".$server.":".$port."}INBOX",$user,$password,CL_EXPUNGE);
if (!$link) {
  $link=imap_open("{".$server.":".$port."}INBOX",$user,$password);
}
if (!$link) {
  return FALSE;
}
return TRUE;
}

function showFinalValues($keyVar,$value2, $request) { // this will also make no-empty values

$result = '';
$result .= '<table width=100% align="right" border="1">';
foreach ($keyVar as $fin => $finVal) {
  if (($finVal["type"] != "commented" && $finVal["type"] != "hidden_constant" && $finVal["type"] != "hidden_scalar_int" && $finVal["type"] != "hidden_scalar") && $finVal["name"] != "database_password") {
    $result .= '<tr><td width="50%">';
    $result .= $finVal["name"];
    $result .= ' => </td>';
    if (!isset($request[$finVal[$value2]]) || $request[$finVal[$value2]] == $finVal["values"]) {
    $result .= '<td width="50%"><span style="color:red;">';
    $result .= $finVal["values"];
    $result .= "</span></td></tr>";
    }
/*  elseif ($finVal["type"] == "commented") {
  $result .= '<td width="50%">';
  $result .= 'Do not worry about this one, ;)';
  $result .= '</td></tr>';
  } */ // hidden_constant, Hmm maybe - Yes, it's now hidden
    else {
    $result .= '<td width="50%">';
    $result .= $request[$finVal[$value2]];
    $result .= "</td></tr>";
    }
  }
}
$result .= '</table>';
return $result;
}

function getNextPageForm ($actualPage) {

  switch ($actualPage) {
  case 'home':
  $nextpage = 'install0';
  $prevpage = 'home';
  $actualPage = 'home';
  $textSubmit = 'Start the installer';
  break;
  case 'install0':
  $nextpage = 'install01';
  $prevpage = 'home';
  $textSubmit = 'Next step';
  break;
  case 'install01':
  $nextpage = 'install1';
  $prevpage = 'install0';
  $textSubmit = 'Next step';
  break;
  case 'install1':
  if ($_SESSION['dbCreatedSuccesfully'] == FALSE) {
  $nextpage = 'install01';
  $actualPage = 'install0';
  }
  else {
  $nextpage = 'install2';
  $prevpage = 'install01';
  }
  $textSubmit = 'Next step';
  break;
  case 'install2':
  $nextpage = 'install3';
  $prevpage = 'install1';
  $textSubmit = 'Next step';
  break;
  case 'install3':
  $nextpage = 'install4';
  $prevpage = 'install2';
  $textSubmit = 'Next step';
  break;
  case 'install4':
  $nextpage = 'install5';
  $prevpage = 'install3';
  $textSubmit = 'Next step';
  break;
  case 'install5':
  $nextpage = 'install6';
  $prevpage = 'install4';
  $textSubmit = 'Next step';
  break;
  case 'install6':
  $nextpage = 'install7';
  $prevpage = 'install5';
  $textSubmit = 'Next step';
  break;
  case 'install7':
  $nextpage = 'final_install';
  $prevpage = 'install6';
  $textSubmit = 'Next step';
  break;
  case 'final_install':
  $nextpage = 'write_install';
  $prevpage = 'install7';
  $textSubmit = 'Write to config now!';
  break;
  case 'write_install':
  $prevpage = 'final_install';
  break;
  default:
  $nextpage = 'install0';
  $prevpage = 'home';
  $textSubmit = 'Start the installer';
  break;
  }

  if ($actualPage == 'home') {
    print $GLOBALS["I18N"]->get('
<div id="language_change">
  <SCRIPT language="JavaScript" type="text/javascript">
    function langChange(){
    var lang_change=this.window.document.lang_change;
    if(lang_change.language_module.selectedIndex==0)return false;
    lang_change.submit();return true;
    }
  </SCRIPT>
  <p>
  <form name="lang_change" action="" method=POST>
  '.languagePack("","langChange();").'
  </form>
  '/*.$GLOBALS["I18N"]->get($GLOBALS["strLanguageOpt"])*/.'
  </p>
</div>
  ');
  }
  print $GLOBALS["I18N"]->get('<form action="" method="post" name="subscribeform">');
  include("install/$actualPage.php");
  print $GLOBALS["I18N"]->get('<div id="maincontent_install"><div class="install_start"><input type="hidden" name="page" value="'.$nextpage.'"><a href="./?page='.$nextpage.'" onClick="javascript:installsubscribeform();"  class="formsubmit">'.$textSubmit.'</a></span><noscript><input type="submit" value="'.$textSubmit.'"></noscript><br /><br /></div></div>');
  print $GLOBALS["I18N"]->get('    <script language="Javascript" type="text/javascript">
    var submitted = false;
    function installsubscribeform() {
      if (!submitted) {
        submitted = true;
        document.subscribeform.submit();
      }
    }
    </script>');
  print $GLOBALS["I18N"]->get('</form>');
#  }
  return;
}

function willNotContinue() {
  print $GLOBALS["I18N"]->get(sprintf('<div class="wrong"><br /><br />%s</div>',$GLOBALS['strReload']));
  include("install/footer.inc");
  exit;
}
function languagePack($value = "", $onChange = "") {
$res = '';
if (!empty($onChange)) {
  $valueChange = 'onChange="'.$onChange.'"';
}
if (empty($value)) {
  $name = 'language_module';
} else {
  $name = $value;
}
$_SESSION[$name] = $_SESSION[$name]?$_SESSION[$name]:'english.inc';
$gestor = opendir('../texts');
  if ($gestor && $language = readdir($gestor)) {
    $res .= '<select name="'.$name.'" '.$valueChange.'>';
    while (FALSE !== ($lang = readdir($gestor))) {
      if (strlen($lang) > 3 ) {
        $res .= '<option value="'.$lang.'"';
        if ($lang == $_SESSION[$name]) {
          $res .= ' selected="selected"';
        }
        $res .= '>';
        $res .= $lang;
        $res .= '</option>';
      }
    }
    $res .= '</select>';
  }
  else {
    $res .= '<select name="'.$name.'" type=text>';
    $res .= '<option value="english.inc">english.inc</option>';
    $res .= '</select>';
  }
return $res;
}


function Sql_Connect_Install($host,$user,$password) {
  if ($host && $user) {
  $db = mysql_connect($host, $user ,$password);
  $errno = mysql_errno();
    if (!$errno) {
      return TRUE;
    }
  if ($errno) {
        return FALSE;
    }
  }
}


function Sql_Create_Db ($databaseToCreate) {
  $creatingDb = mysql_query(sprintf('CREATE DATABASE %s;',$databaseToCreate));
  if ($creatingDb) {
  $result = TRUE;
  }
  else {
  $result = FALSE;
  }
return $result;
}

function Sql_Close ($connection) {
  mysql_close($connection);
}

function cleanSession() {

/*  if (is_array($vars)) {
    cleanSession($vars);
  }
  else {*/
    session_unset();
    session_destroy();
#  }
  return;
}

?>
