<?php

function editVariable($keyAr,$value,$type,$section) {
  $res = '';
  foreach ($keyAr as $key => $val) {
    if ($val["section"] == $section) {
      if ($type == 'text' && !isset($_SESSION[$val[$value]])) { // lets define the value, if default or request one
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
            $res .= '<div class="description"><span>Info:</span> '.$GLOBALS["str".$val['name']."_desc"];
            $res .= '</div><br />';
          }
        }
        switch ($val['type']) {
          case "scalar":
            if ($_SESSION['dbCreatedSuccesfully'] == 1) {
              if ($val[$value] == 'database_name' || $val[$value] == 'database_user' || $val[$value] == 'database_password' || $val[$value] == 'database_host') {
                $res .= '<input type="hidden" value="'.$realValue;
                $res .= '" name="'.$val[$value].'">';
                break 1;
              }
            }
  
            if ($val[$value] == 'message_envelope') {
              $realValue = ($_SESSION[$val[$value]]?$_SESSION[$val[$value]]:'bounces@'.$_SERVER["SERVER_NAME"]);
              $res .= '<input type="'.$type.'" value="'.$realValue;
              $res .= '" name="'.$val[$value].'"> ';
            }
            elseif ($val[$value] == 'language_module' && $type == 'text') {
              $res .= languagePack($val[$value],"");
            } elseif ($val[$value] == 'pageroot' || $val[$value] == 'adminpages') {
              $res .= pageGetOpt($val[$value],$type);
            }/* elseif ($val[$value] == 'adminpages') {
              $res .= pageGetOpt($val[$value]);
            }*/
            else {
              $res .= '<input type="'.$type.'" value="'.$realValue;
              $res .= '" name="'.$val[$value].'"> ';
            }
            break;
            case "scalar_int":
            if (isset($val["type_value"]) && $val["type_value"] == "bool") {
              if (($val["values"] && !isset($_SESSION[$val[$value]])) || $_SESSION[$val[$value]] == 1) { $options[$val[$value]] = "checked"; } else { $options[$val[$value]] = ""; }
              $newtype[$val[$value]] = "checkbox";
              $realValue = '';
            }
            $res .= '<input type="'.($newtype[$val[$value]]?$newtype[$val[$value]]:$type).'" '.$options[$val[$value]].' value="'.$realValue;
            $res .= '" name="'.$val[$value].'" > '.($newtype[$val[$value]] ? "Activate or deactive <strong>".$val[$value]."</strong>" : "");
            $addtocheck .= $val[$value].',';
  /*          $res .= ' <script type="text/javascript" language="JavaScript">';
            $res .= sprintf("addFieldToCheck(\"%s\"); ", $val[$value]);
            $res .= '  </script>';*/
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
            if (isset($val["type_value"]) && $val["type_value"] == "bool") {
              if (($val["values"] && !isset($_SESSION[$val[$value]])) || $_SESSION[$val[$value]] == 1) { $options[$val[$value]] = "checked"; } else { $options[$val[$value]] = ""; }
              $newtype[$val[$value]] = "checkbox";
              $realValue = '';
            }
            $res .= '<input type="'.($newtype[$val[$value]]?$newtype[$val[$value]]:$type).'" '.$options[$val[$value]].' value="'.$realValue.'" name="'.$val[$value].'"> '.($newtype[$val[$value]] ? "Activate or deactive <strong>".$val[$value]."</strong>" : "");
            $addtocheck .= $val[$value].',';
  /*          $res .= ' <script type="text/javascript" language="JavaScript">';
            $res .= sprintf("addFieldToCheck(\"%s\"); ", $val[$value]);
            $res .= '  </script>';*/
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
        $_SESSION["check"] = $addtocheck;

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
  }
  return $res;
}
function writeToConfig($key, $requiredVars2) {
  if (!empty($key)) {
#  $configDir = $_SERVER['DOCUMENT_ROOT'].'/lists/config';
  if (isset($_SERVER["ConfigFile"]) && is_file($_SERVER["ConfigFile"])) {
    $nameConfigFile = $_SERVER['ConfigFile'];
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
      if (empty($val)) $val=0;
      if ($requiredVars2[$configInfo]) {
#      }
#      else {
      $configInfoToWrite .= "\n";
      $configInfoToWrite .= '# ';
      $configInfoToWrite .= $GLOBALS["str".$requiredVars2[$configInfo]["name"]."_desc"];
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

      if (!is_numeric($val) && !isset($requiredVars[$key]["type_value"])) {
        $msg = $GLOBALS["I18N"]->get('<p style="color:#000fff;"><b>'.$key.'</b>'.$GLOBALS['strErraticValue'].'<span style="color:#f00;">'.$val.'</span><br />');
        if (preg_match('/[0-9]+/', $val, $matches)) {
          $msg .= $GLOBALS["I18N"]->get(printf('%s%s</p>', $GLOBALS['strChangedForThis'], $matches[0]));
          $_SESSION[$key] = $matches[0];
        }
        else {
          $msg .= $GLOBALS["I18N"]->get(printf('%s%s</p>', $GLOBALS['strChangedForThis'], $requiredVars[$key]['values']));
          $_SESSION[$key] = $requiredVars[$key]['values'];
        }
      }
    }
  }
  return $msg;
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
  if (($finVal["type"] != "commented" && $finVal["type"] != "hidden_constant" && $finVal["type"] != "hidden_scalar_int" && $finVal["type"] != "hidden_scalar") && $finVal["name"] != "database_password" && $finVal["name"] != "userhistory_systeminfo" ) {
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
    elseif (isset($request[$finVal[$value2]]) && empty($request[$finVal[$value2]])) {
    $result .= '<td width="50%">';
    $result .= '0';
    $result .= "</td></tr>";
    } else {
    $result .= '<td width="50%">';
    $result .= $request[$finVal[$value2]];
    $result .= "</td></tr>";
    }

  }
}
$result .= '</table>';
return $result;
}

function getNextPageForm ($actualPage, $pages) {

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
  if (!$_SESSION['dbCreatedSuccesfully']) {
  $nextpage = 'install1';
  $actualPage = 'install01';
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
/*
    <script language="Javascript" type="text/javascript">
    var fieldnames = new Array();
    function addFieldToCheck(name) {
      fieldnames[fieldnames.length] = name;
    }
    </script>

      for (i=0;i<fieldnames.lenght;i++) { alert(fieldnames[i]); }

      for (i=0;i<fieldnames.lenght;i++) { alert(eval("document.subscribeform.fieldnames[i].checked"));
        if (document.subscribeform.fieldnames[i].checked) {
          document.subscribeform.fieldnames[i].value = fsedfdf;
        } else {
          document.subscribeform.fieldnames[i].value = 44444;
        }
      }

*/
  print $GLOBALS["I18N"]->get('
       <form action="./?page='.$nextpage.'" method="post" name="subscribeform">
  <input type="hidden" name="page" value="'.$nextpage.'">');
  include("install/$pages.php");
  print $GLOBALS["I18N"]->get('<div id="maincontent_install"><div class="install_start"><a href="javascript:installsubscribeform();" class="formsubmit">' . $textSubmit . '</a></span><br /><br /></div></div>');
  print $GLOBALS["I18N"]->get('    <script language="Javascript" type="text/javascript">
    var submitted = false;
    function installsubscribeform() {
      if (!submitted) {
        submitted = true;
        document.subscribeform.submit();
      }
    }
    </script>
  <noscript><input type="submit" name="submit" value="'.$textSubmit.'"></noscript>');
  print $GLOBALS["I18N"]->get('</form>');
#  }
  return;
}

function willNotContinue() {
  print $GLOBALS["I18N"]->get(sprintf('<div class="wrong"><br /><br />%s</div>',$GLOBALS['strReload']));
  include("install/footer.inc");
  exit;
}

function checkSessionCheckboxes() {

  foreach ($GLOBALS["requiredVars"] as $key => $val) {
     if (isset($val["type_value"]) && $val["type_value"] == "bool" && in_array($key,explode(",",$_SESSION["check"]))) {
      if ($val["values"] == 1) {
        if (!isset($_POST[$key]))
          $_SESSION[$key] = 0;
        else
          $_SESSION[$key] = 1;
      } else {
        if (isset($_POST[$key]))
          $_SESSION[$key] = 1;
        else
          $_SESSION[$key] = 0;
      }
    }
  }

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
$gestor = opendir('../texts/');
  if ($gestor ) { /*&& $language = readdir($gestor)  <== this takes out the english file... */
    $res .= '<select name="'.$name.'" '.$valueChange.'>';
    while (FALSE !== ($lang = readdir($gestor))) {
      if (strlen($lang) > 3 && !preg_match("/~/",str_replace(".inc","",$lang))) {
        $res .= '<option value="'.$lang.'"';
        if ($lang == $_SESSION[$name]) {
          $res .= ' selected="selected"';
        }
        $res .= '>';
        $res .= str_replace(".inc","",$lang);
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

function pageGetOpt($value,$type) {

switch($value) {
  case "pageroot": # adminpages
    preg_match("/([\/]?[\w]+)?/", $_SERVER["REQUEST_URI"], $page);
  break;
  case "adminpages":
  default:
    preg_match("/([\w\/]+)/", $_SERVER["REQUEST_URI"], $page);
  break;
}

return '<input type="'.$type.'" value="'.$page[0].'" name="'.$value.'"> ';

}


function Sql_Connect_Install($host,$user,$password) {
  if ($host && $user) {
    $db = mysql_connect($host, $user ,$password);
    $errno = mysql_errno();
    if ($db) {
      return $db;
    }
    if ($errno) {
      return FALSE;
    }
  }
}


function Sql_Create_Db ($databaseToCreate) {
  return sql_query('CREATE DATABASE `'.$databaseToCreate.'`',$GLOBALS["database_connection"]);
}

function Sql_Close ($connection) {
  return mysql_close($connection);
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
