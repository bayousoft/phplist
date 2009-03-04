<?php
/*
function getPostVariables(&$params)

this function are setting globals variables using the POST variables
of the HTML form. This HTML variables are created using the "parameters"
array. This function not only set the HTML elements as PHP global variables,
it change the "default" value of each defined-element in the current structure
is used (array) with the POST value returned.
*/
function newMenu(){
}

function writeConfigFile($file){
   global $parameters;

   $fp = @fopen($file,"w");

   if (!$fp) return false;

   fwrite($fp, "<?php");

   foreach($parameters as $group){
      fwrite($fp, $group["file_description"]);

      $group_p = $group["parameters"];

      foreach($group_p as $variable){
         $variable["default"] = (isset($_SESSION[$variable["name"]]))?$_SESSION[$variable["name"]]:$variable["default"];

         fwrite($fp, $variable["file_description"]); // escribo descripcion del parametro

         if ($variable["commented"]){
            $linevar = "#"; // agrego "#" al principio si es que debe estar comentada por default
         }
         else $linevar = "";

         switch($variable["type"]){
            case "variable":
               $linevar.= 
               "$".$variable["name"].
               " = ".
               ((is_numeric($variable["default"]))?$variable["default"]:"\"".$variable["default"]."\"").
               ";";
               break;
   
            case "constant":
               $linevar.=
               "define(".
               "'".$variable["name"]."'".
               ", ".
               ((is_numeric($variable["default"]))?$variable["default"]:"\"".$variable["default"]."\"").
               ");";
               break;

            case "included":
               $linevar.=
               "include('".$variable["name"]."');";
               break;
 
            case "required":
               $linevar.=
               "require('".$variable["name"]."');";
               break;
 
            case "call":
               $linevar.=
               "$".$variable["name"]." = ".$variable["default"].";";
               break;
 
            case "array":
               $arrval = explode("|",$variable["default"]);

               for($i=0; $i<sizeof($arrval); $i++){
                  $arrval[$i] = (is_numeric($arrval[$i]))?$arrval[$i]:"'".$arrval[$i]."'";
               }

               $linevar.=
               "$".$variable["name"]." = array(".implode(",",$arrval).");";
               break;
         }


         if (!isset($variable["file_description_after"]))
              $linevar.= "\n";
         else $linevar.= "";


         fwrite($fp, $linevar); // escribo declaracion del parametro
         fwrite($fp, $variable["file_description_after"]); // escribo descripcion posterior del parametro
      }

      fwrite($fp, "\n\n");
   }

   fwrite($fp, "?>");
   fclose($fp);

   return true;
}

function getSessionData(&$params){
   for ($i=0; $i<sizeof($params); $i++){
      $varname = $params[$i]["name"];

      if (isset($_SESSION[$varname])){
         global $$varname;

         $$varname = $_SESSION[$varname];

         $params[$i]["default"] = $_SESSION[$varname];
      }
   }
}

function setSessionData($params){
   for ($i=0; $i<sizeof($params); $i++){
      $varname = $params[$i]["name"];

      global $$varname;

      if (isset($$varname)){
         $_SESSION[$varname] = $$varname;
      }
   }
}

function getPostVariables(&$params){
   global $_POST;

   for($i=0; $i<sizeof($params); $i++){
      $varname = $params[$i]["name"];

      if (isset($_POST[$varname])){
         global $$varname;

         $$varname = $_POST[$varname];

         $params[$i]["default"] = $_POST[$varname];
      }
   }
}

function genPHPVariables($params){
   for($i=0; $i<sizeof($params); $i++){
      if ($params[$i]["visible"]){
         $varname = $params[$i]["name"];
         $varvalue= $params[$i]["default"];

         global $$varname;

         $$varname = $varvalue;
      }
   }
}

function getHTMLElements($params){
   for($i=0; $i<sizeof($params); $i++){
      if ($params[$i]["visible"]){
         $name = $params[$i]["name"];
         $desc = $GLOBALS["I18N"]->get($params[$i]["appl_description"]);
         $type = $params[$i]["type"];
         $value= $params[$i]["default"];

         $htmlList[] = genHTMLElement($name, $desc, $value, $type);
      }
   }

   return implode("\n", $htmlList);
}

function genHTMLElement($name, $des="", $default="", $type="variable"){
   $des = (!$des)?$name:$des;

   switch($type){
      case "variable":
      case "constant":
         $element = "    <input type='text' name='$name' value='$default'>\n";
         break;

      case "array":
         $arr = explode("|", $default);
         
         $element = "    <select name='$name'>\n";

         for ($i=0; $i<sizeof($arr); $i++){
            $element.= "      <option value='$arr[$i]'>$arr[$i]\n";
         }

         $element.= "    </select>\n";
         break;
   }

   $element = "<tr>\n".
              "  <td>$des</td>\n".
              "  <td>\n".
              $element.
              "  </td>\n".
              "</tr>";

   return $element;
}

function breadcrumb($page=0){
   $bread[] = "<a href='?page='>Home</a>";

   for ($i=0; $i<$page; $i++){
      $bread[] = "<a href='?page=$i'>".$GLOBALS["I18N"]->get($GLOBALS["strStep"])." $i</a>";
   }

   $bread[] = "Step $page";


   return implode("&nbsp;&raquo;&nbsp;", $bread);
}

/**
  editVariable function to get the editable inputs of the config variables
  @param $config_vars
    array of variables in requiredVars.php file, selected by section of $section
  @param $section
    string of the section we need the inputs, section we are editing.
  @returns
    html with all inputs
**/
function editVariable($config_vars,$section) {
  $res = '';
  foreach ($config_vars as $key => $val) {
    $variables++;
    if ($val["section"] == $section) {
      if (!isset($_SESSION[$val["name"]])) { // lets define the value, if default or edited one
        $realValue = $val["values"];
      }
      else {
        $realValue = $_SESSION[$val["name"]];
      }
      if ($key["name"]) {
        if ($val["name"] !== 'database_name'
//        && $val[$value] !== 'database_schema'
        && $val["name"] !== 'database_user'
        && $val["name"] !== 'database_password'
        && $val["name"] !== 'database_host') {
          if ($val['type'] == 'scalar' || $val['type'] == 'scalar_int' || $val['type'] ==  'constant') {
            $res .= '<img src="images/break-el.gif" height="1" width="100%" /><div class="value_name">';
            $res .= $key.'</div>';
            $res .= '<div class="description"><span>'.$GLOBALS["I18N"]->get($GLOBALS["strValInfo"]).'</span> '.$GLOBALS["str".$val['name']."_desc"];
            $res .= '</div><br />';
          }
        }
        $addtocheck = '';
        switch ($val['type']) {
          case "scalar":
            if ($_SESSION['dbCreatedSuccesfully'] == 1) {
              if ($val["name"] == 'database_name'
//              || $val[$value] == 'database_schema'
              || $val["name"] == 'database_user'
              || $val["name"] == 'database_password'
              || $val["name"] == 'database_host') {
                $res .= getInput($val["name"], $realValue, "hidden");
                break 1;
              }
            }

            if ($val["name"] == 'message_envelope') {
              $realValue = (isset($_SESSION[$val["name"]])?$_SESSION[$val["name"]]:'bounces@'.$_SERVER["SERVER_NAME"]);
              $res .= getInput($val["name"], $realValue, "text");
            }
            elseif ($val["name"] == 'language_module') {
              $res .= languagePack($val["name"],"");
            } elseif ($val["name"] == 'pageroot' || $val["name"] == 'adminpages') {
              $res .= pageGetOpt($val["name"]);
            }/* elseif ($val["name"] == 'adminpages') {
              $res .= pageGetOpt($val["name"]);
            }*/
            else {
              $res .= getInput($val["name"], $realValue, "text");
            }
            break;
            case "scalar_int":
            if (isset($val["type_value"]) && $val["type_value"] == "bool") {
              if (!isset($_SESSION[$val["name"]]) && $val["values"] == 1) $checked = 1;
              else $checked = ($_SESSION[$val["name"]] == 1) ? 1 : 0;

              $res .= $GLOBALS["I18N"]->get($GLOBALS["strActivateDeactivate"])."<strong>".$val["name"]."</strong><br />";
              $res .= getInput($val["name"], 1, "radio", (($checked == 1)?"checked":''));
              $res .= $GLOBALS["I18N"]->get($GLOBALS["strYes"]);
              $res .= getInput($val["name"], 0, "radio", (($checked == 0)?"checked":''));
              $res .= $GLOBALS["I18N"]->get($GLOBALS["strNo"]);
            } else {
              $res .= getInput($val["name"], $val["values"], "text");
            }
            break;
            case "hidden_scalar":
              $res .= getInput($val["name"], $val["values"], "hidden");
            break;
            case "hidden_scalar_int":
              $res .= getInput($val["name"], $val["values"], "hidden");
            break;
            case "constant":
            if (isset($val["type_value"]) && $val["type_value"] == "bool") {
              if (!isset($_SESSION[$val["name"]]) && $val["values"] == 1) $checked = 1;
              else $checked = ($_SESSION[$val["name"]] == 1) ? 1 : 0;

              $res .= $GLOBALS["I18N"]->get($GLOBALS["strActivateDeactivate"])."<strong>".$val["name"]."</strong><br />";
              $res .= getInput($val["name"], 1, "radio", (($checked == 1)?"checked":''));
              $res .= $GLOBALS["I18N"]->get($GLOBALS["strYes"]);
              $res .= getInput($val["name"], 0, "radio", (($checked == 0)?"checked":''));
              $res .= $GLOBALS["I18N"]->get($GLOBALS["strNo"]);
            } else {
              $res .= getInput($val["name"], $val["values"], "text");
            }
#            if (isset($_POST[$val["name"]]))
#              $addtocheck .= $val["name"].',';
            break;
            case "hidden_constant":
              $res .= getInput($val["name"], $val["values"], "hidden");
            break;
            case "hidden_array":
              $res .= getInput($val["name"], $val["values"], "hidden");
            break;
            case "commented":
              $res .= getInput($val["name"], $val["values"], "hidden");
            break;
        }
//        $_SESSION["check"] .= $addtocheck;

        if ($val['type'] == 'scalar' || $val['type'] == 'scalar_int' || $val['type'] ==  'constant') {
          if ($val["name"] !== 'database_name'
          && $val["name"] !== 'database_user'
          && $val["name"] !== 'database_password'
          && $val["name"] !== 'database_host') {
            $res .= '<br /><br />';
          }
        }
      }
      else {
        $res .= $GLOBALS['$strValueNotFound'];
      }
    } else {
      $novariables++;
    }
  }
  return $res;
}

/**
  getInput function to get the editable inputs of the config variables
  @param $name
    name att of the input
  @param $value
    value of the input
  @param $type
    type of the input
  @param $extra
    extra attributes of the type checked="checked", onChange="doSomething" for the input
  @returns
    input of type radio, text or hidden
**/
function getInput($name, $value, $type, $extra = "") {
  return sprintf('<input type="%s" name="%s" value="%s" %s />', $type ,$name, $value, $extra);
}

/**
  getVarForConfig function to get the kind of variable to write in the config file, of the type $type
  @param $type
    type of the variable
  @param $name
    name of the constant or variable
  @param $val
    value of the constant or variable
  @param $extra
    extra attributes (not in use right now)
  @returns
    the correct syntax to write php format of constants and variables in the config file
**/
function getVarForConfig($type, $name, $val, $extra = "") {
  $res = "";
  switch ($type) {
    case "constant":
    case "hidden_constant":
      $res .= sprintf("define(\"%s\", %s);", $name, $val);
    break;
    case "hidden_array":
      $res .= sprintf('$%s = array(', $name);
      if (is_array($val)) {
        for ($i=0;$i < count($val);$i++) {
          $res .= sprintf('"%s"', $val[$i]);
          if ($i == count($val)-1) {
            break 1;
          }
          $res .= ',';
        }
        $res .= ");\n";
        break 1;
      }
      $res .= sprintf('"%s");', $val);
    break;
    case 'commented':
    break;
    case 'scalar_int':
    case "hidden_scalar_int":
    default:
      $res .= sprintf('$%s = "%s";', $name, $val);
    break;
  }
  return $res;
}

/**
  writeToConfig function to write the contents of the $_SESSION variable if it is inside the $requiredVars array
  @param $session_vars
    array $_SESSION containing all the configuration variables
  @param $req_vars
    array of all the configuration taken from the requiredVars.php file, with all the default content
  @returns
    TRUE if it could write the config file succesfully or else FALSE
**/
function writeToConfig($session_vars, $req_vars) {
  if (!empty($session_vars)) {
    if (isset($_SERVER["ConfigFile"]) && is_file($_SERVER["ConfigFile"])) {
      $nameConfigFile = $_SERVER['ConfigFile'];
    } elseif (is_file("../config/config.php")) {
      $nameConfigFile = "../config/config.php";
    }
    $myConfigFile = fopen($nameConfigFile, 'a');
    if (!isset($myConfigFile) || $myConfigFile == FALSE) {
      $myConfigFile = fopen($nameConfigFile, 'w'); // Try to open
      if (!isset($myConfigFile)) {
        print $GLOBALS["I18N"]->get(sprintf('<div class="wrong">%s (%s)</div>',$GLOBALS['noConfigAndChmod'], $nameConfigFile));
      }
    }
    if ($myConfigFile) {
      print $GLOBALS["I18N"]->get(sprintf('<p>%s</p>',$GLOBALS['creatingConfig']));
      $configInfoToWrite = '';
      $configInfoToWrite .= '<?php';
      foreach ($session_vars as $key_name => $val) {
        if (empty($val)) $val=0;
        if (isset($req_vars[$key_name])) {
          $configInfoToWrite .= "\n";
          $configInfoToWrite .= sprintf('# %s',$GLOBALS["str".$req_vars[$key_name]["name"]."_desc"]);
          $configInfoToWrite .= "\n";
          $configInfoToWrite .= "\n";
          switch ($req_vars[$key_name]["type"]) {
            case 'hidden_constant' :
            case 'hidden_array' :
            case 'commented' :
            $var_name = $key_name;
            $var_value = $req_vars[$key_name]['values'];
            break;
            default:
            case 'constant':
            case 'scalar_int' :
            case 'hidden_scalar_int' :
            $var_name = $key_name;
            $var_value = $val;
            break;
          }
          $configInfoToWrite .= getVarForConfig($req_vars[$key_name]["type"], $var_name, $var_value);
          $configInfoToWrite .= "\n";
          if ($key_name == 'error_level') {
            break 1;
          }
        }
      }
      $configInfoToWrite .= "\n\n?>\n";
      $configText = sprintf('%s',$configInfoToWrite);
      substr($configText,1);
      $myConfigFileOpen = fwrite($myConfigFile, $configText);
      $configCreatedValue = 0;
      if (!$myConfigFileOpen) {
        print $GLOBALS["I18N"]->get(sprintf('%s', $GLOBALS['cantWriteToConfig']));
      }
      else {
        if (file_exists($nameConfigFile) && filesize($nameConfigFile) > 2) {
          print $GLOBALS["I18N"]->get(sprintf("<br /><strong>%s</strong>", $GLOBALS['configCreatedOk']));
          $configCreatedValue = 1;
          $configPerms = substr(sprintf('%o', fileperms($nameConfigFile)), -4);
          if ($configPerms !== '0644') {
              print $GLOBALS["I18N"]->get(printf('<p class="wrong">%s %s%s</p>', $GLOBALS['strConfigPerms'], $configPerms, $GLOBALS['strChangeChmod']));
          }
        }
        else {
          print $GLOBALS["I18N"]->get(printf("%s", $GLOBALS['configDoesntExist']));
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

/**
  checkScalarInt function checks on every default value of the $requiredVars array taken from requiredVars.php whether it is a numeric value; if it is, check the edited value and tries to correct it in the $_SESSION array if the edited value is not numeric.
  @param $session_vars
    array $_SESSION containing all the configuration variables
  @param $req_vars
    array of all the configuration taken from the requiredVars.php file, with all the default content
  @returns
    a message if any correction was made
**/
function checkScalarInt($session_vars, $req_vars) {

  foreach ($session_vars as $key => $val) {
    $msg = '';
    if (!is_array($var)) {
      $_SESSION[$key] = cleanVar($_SESSION[$key]);
    }
    if (isset($req_vars[$key]['values']) && is_numeric($req_vars[$key]['values'])) {
      if (!is_numeric($val) && !isset($req_vars[$key]["type_value"])) {
        $msg = $GLOBALS["I18N"]->get('<p style="color:#000fff;"><strong>'.$key.'</strong>'.$GLOBALS['strErraticValue'].'<span style="color:#f00;">'.$val.'</span><br />');
        if (preg_match('/[0-9]+/', $val, $matches)) {
          $msg .= $GLOBALS["I18N"]->get(sprintf('%s <strong>%s</strong></p>', $GLOBALS['strChangedForThis'], $matches[0]));
          $_SESSION[$key] = $matches[0];
        }
        else {
          $msg .= $GLOBALS["I18N"]->get(sprintf('%s <strong>%s</strong></p>', $GLOBALS['strChangedForThis'], $req_vars[$key]['values']));
          $_SESSION[$key] = $req_vars[$key]['values'];
        }
      }
    }
  }
  return $msg;
}

/**
  cleanVar cleans the given variable or array, removing ",',\,?,[,]
  @param $var
    array or variable to clean
  @returns
    the given variable or array with the replacements made
**/
function cleanVar($var) {

  $strip = array('"',"'",'\\',"?","[","]");
  $var = str_replace($strip, "", $var);
  return $var;

}

/**
  processPopTest implementation of the processPop function of phplist for the installer
  @param $server
    server for the pop account
  @param $user
    user for the account
  @param $password
    password for the given user in $user
  @returns
    TRUE if the connection was successfull; else FALSE
**/
function processPopTest ($envelope,$server,$user,$password) {
   $port =  (isset($_REQUEST["bounce_mailbox_port"]))?$_REQUEST["bounce_mailbox_port"]:false;

   if (!$port) {
      $port = '110/pop3/notls';
   }
   set_time_limit(6000);

   $current = error_reporting(0);

   $link = @imap_open("{".$server.":".$port."}INBOX",$user,$password,CL_EXPUNGE+OP_SILENT);

   if (!$link){
      $link = @imap_open("{".$server.":".$port."}INBOX",$user,$password);
   }

   error_reporting($current);

   if (!$link){
      return FALSE;
   }

   return TRUE;
}

/**
  showFinalValues function to show a table based html with the values which will be written to the config file
  @param $session_vars
    array $_SESSION containing all the configuration variables
  @param $req_vars
    array of all the configuration taken from the requiredVars.php file, with all the default content
  @returns
    html of final values to write to the config file
**/
function showFinalValues($req_vars, $session_vars) { # somebody please work on this to use css based html"
   $result = '';
   $result .= '<table width=100% align="right" border="1">';
   
   foreach ($req_vars as $key => $val){
      if (($val["type"] != "commented" && 
           $val["type"] != "hidden_constant" && 
           $val["type"] != "hidden_scalar_int" && 
           $val["type"] != "hidden_scalar") && 
           $val["name"] != "database_password" && 
           $val["name"] != "userhistory_systeminfo" ) {
         $result .= '<tr><td width="50%">';
         $result .= $val["name"];
         $result .= ' => </td>';
   
         if (!isset($session_vars[$val["name"]]) || $session_vars[$val["name"]] == $val["values"]) {
            $result .= '<td width="50%"><span style="color:red;">';
            $result .= $val["values"];
            $result .= "</span></td></tr>";
         }
         elseif (isset($session_vars[$val["name"]]) && empty($session_vars[$val["name"]])) {
            $result .= '<td width="50%">';
            $result .= '0';
            $result .= "</td></tr>";
         }
         else{
            $result .= '<td width="50%">';
            $result .= $session_vars[$val["name"]];
            $result .= "</td></tr>";
         }
   
      }
   }
   
   $result .= '</table>';
   return $result;
}

/**
  getNextPageForm function to show a form to the next page
  @param $actualPage
    string of the page to show; pages are now inside the pages.php file
  @returns
    prints html of the form for the next page with all the editable inputs for this page
**/

function getNextPageForm ($actualPage) { # function getNextPageForm ($actualPage, $pages)

  preg_match("/[0-9]+$/", $actualPage, $res);
  switch ($actualPage) {
  case 'home':
  $nextpage = 'install0';
  $prevpage = 'home';
  $actualPage = 'home';
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strStartInst"]);
  break;
  case 'install0':
  $nextpage = 'install'.($res[0]+1);
  $prevpage = 'home';
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
  break;
  case 'install01':
  $nextpage = 'install1';
  $prevpage = 'install0';
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
  break;
  case 'install1':
  if (!$_SESSION['dbCreatedSuccesfully']) {
  $nextpage = 'install1';
  $actualPage = 'install01';
  }
  else {
  $nextpage = 'install'.($res[0]+1);
  $prevpage = 'install01';
  }
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
  break;
  case 'install2':
  case 'install3':
  case 'install4':
//  case 'install5':
//  case 'install6':
  $nextpage = 'install'.($res[0]+1);
  $prevpage = 'install'.($res[0]-1);
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
  break;
  case 'install5':
  $nextpage = 'final_install';
  $prevpage = 'install'.($res[0]-1);
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
  break;
  case 'final_install':
  $nextpage = 'write_install';
  $prevpage = 'install5';
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strWriteToConfig"]);
  break;
  case 'write_install':
  $prevpage = 'final_install';
  break;
  default:
  $nextpage = 'install0';
  $prevpage = 'home';
  $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strStartInst"]);
  break;
  }

  if ($actualPage == 'home') {
    if (isset($GLOBALS["I18N"]) && is_object($GLOBALS["I18N"])) {
      print $GLOBALS["I18N"]->get('
  <div id="language_change">
    <script language="JavaScript" type="text/javascript">
      function langChange(){
      var lang_change=this.window.document.lang_change;
      if(lang_change.language_module.selectedIndex==0)return false;
      lang_change.submit();return true;
      }
    </script>
    <p>
    <form name="lang_change" action="" method=POST>
    '.languagePack("","langChange();").'
    </form>
    '/*.$GLOBALS["I18N"]->get($GLOBALS["strLanguageOpt"])*/.'
    </p>
  </div>
    ');
    }
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
  if (isset($GLOBALS["I18N"]) && is_object($GLOBALS["I18N"])) {
    print $GLOBALS["I18N"]->get('
        <form action="./?page='.(isset($nextpage)?$nextpage:'').'" method="post" name="subscribeform">
    <input type="hidden" name="page" value="'.(isset($nextpage)?$nextpage:'').'"/>');
  }
  include("install/pages.php");
  if (isset($GLOBALS["I18N"]) && is_object($GLOBALS["I18N"])) {
    print $GLOBALS["I18N"]->get('<div id="maincontent_install"><div class="install_start"><a href="javascript:installsubscribeform();" class="formsubmit">' . $textSubmit . '</a><br /><br /></div></div>');
    print $GLOBALS["I18N"]->get('    <script language="Javascript" type="text/javascript">
      var submitted = false;
      function installsubscribeform() {
        if (!submitted) {
          submitted = true;
          document.subscribeform.submit();
        }
      }
      </script>
    <noscript><input type="submit" name="submit" value="'.$textSubmit.'"/></noscript>');
    print $GLOBALS["I18N"]->get('</form>');
  }
#  }
  return;
}

/**
  willNotContinue function will print an error message and won't continue with installation unless you correct what the message says
  @returns
    void
**/

function willNotContinue() {
  print $GLOBALS["I18N"]->get(sprintf('<div class="wrong"><br /><br />%s</div>',$GLOBALS['strReload']));
  include("install/define.php");
  include("install/footer.inc");
  exit;
}

/**
  languagePack function to show select html input with all the available languages in the "texts" directory
  @param $value
    the name of the select attribute
  @param $onChange
    extra attributes for the select input, of the type onClick="doSomething"
  @returns
    html of the language files user can use; else the english.inc default one
**/
function languagePack($value = "", $onChange = "") {
   $res = '';
   $valueChange = '';
   if (!empty($onChange)) {
     $valueChange = 'onChange="'.$onChange.'"';
   }
   if (empty($value)) {
     $name = 'language_module';
   } else {
     $name = $value;
   }
   if (!isset($_SESSION[$name])) $_SESSION[$name] = 'english.inc';
   
   $gestor = opendir('../texts/');
   
   if ($gestor ){ /*&& $language = readdir($gestor)  <== this takes out the english file... */
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

/**
  pageGetOpt function to load the message_envelope input with a default value of the type listbounces@servername
  @param $value
    the name of input attribute
  @returns
    getInput with the corrected value
**/
function pageGetOpt($value) {

switch($value) {
  case "pageroot": # adminpages
    preg_match("/([\/]?[\w]+)?/", $_SERVER["REQUEST_URI"], $page);
  break;
  case "adminpages":
  default:
    preg_match("/([\w\/]+)/", $_SERVER["REQUEST_URI"], $page);
    $page[0] = substr($page[0], 0, -1);
  break;
}

return getInput($value, $page[0], "text");
#'<input type="text" value="'.$page[0].'" name="'.$value.'"/> ';

}

function check_connection($host, $user, $pass, $dbname){
   $errno  = 0;
   $msg    = "";
   $created= "";

   if ($host && $user && $pass){
      $conn = @mysql_connect($host, $user ,$pass);

      if (!$conn){
         $errno = mysql_errno();
      }
      else{
         if ($dbname){
            $res = @mysql_select_db($dbname, $conn);

            if (!$res){
               $creating = 1;
               $errno = mysql_errno($conn);

               $res = mysql_query("CREATE DATABASE $dbname", $conn);

               if (!$res){
                  $errno = mysql_errno($conn);
                  $created = 0;
               }
               else{
                  $errno = 0;
                  $created = 1;
               }
            }

         }
      }
   }
   else{
      if (!$host)
          $errno = 6661;
      else if (!$user)
              $errno = 6662;
           else if (!$pass)
                   $errno = 6663;
   }

   switch ($errno){
      case 2003: 
      case 2004: 
      case 2005: $msg = $GLOBALS["I18N"]->get("problem to connect to this server");    break;
      case 1040: $msg = $GLOBALS["I18N"]->get("server busy"); break;
      case 1044: 
      case 1045: $msg = $GLOBALS["I18N"]->get("User or password are wrong, check it"); break;
      case 1049: $msg = $GLOBALS["I18N"]->get("database '$dbname' not exists".(($creating)?" and can't be created, check permission for the user":"")); break;
      case 1006: $msg = $GLOBALS["I18N"]->get("You can't create database '$dbname', contact your sysadmin"); break;
      case 1007: $msg = $GLOBALS["I18N"]->get("The database '$dbname' exists, you can't create it again"); break;
      case 6661: $msg = $GLOBALS["I18N"]->get("The database host must be supplied"); break;
      case 6662: $msg = $GLOBALS["I18N"]->get("The database user must be supplied"); break;
      case 6663: $msg = $GLOBALS["I18N"]->get("The database password must be supplied"); break;
      case 0   : $msg = $GLOBALS["I18N"]->get("Database connection OK".(($creating)?" - Database $dbname was created":"")); break;
   }

   return "$errno|$msg|$created";
}

/**
  Sql_Connect_Install implementation of the Sql_function for phplist, but to connect to a server without a database
  @param $host
    the server database's host
  @param $user
    username with access to the server database host
  @param $password
    password for the given user in $user
  @returns
    TRUE if it could connect to the server; else FALSE
**/
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

/**
  Sql_Create_Db function to create the database
  @param $databaseToCreate
    database name to create
  @returns
    resource of the query. TRUE if created; else FALSE
**/
function Sql_Create_Db ($databaseToCreate) {
  global $database_connection;
  return sql_query('CREATE DATABASE `'.$databaseToCreate.'`',$database_connection);
}
/**
  Sql_Close function to close the server database connection
  @param $connection
    connection link resource
  @returns
    resource of the query. TRUE if closed; else FALSE
**/
function Sql_Close ($connection) {
  return mysql_close($connection);
}

/**
  cleanSession function to unset and destroy all the session cookies
  @returns
    void
**/
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

# deprecated function // using radio buttons

function checkSessionCheckboxes() {
  foreach ($GLOBALS["requiredVars"] as $key => $val) {
     if (isset($_SESSION["check"]) && isset($val["type_value"]) && $val["type_value"] == "bool" && in_array($_POST[$key],explode(",",$_SESSION["check"]))) {
      if ($val["values"] == 1) {
        if (!isset($_POST[$key])) {
          $_SESSION[$key] = 0; }
        else {
          $_SESSION[$key] = 1; }
      } else {
        if (empty($_POST[$key])) {
          $_SESSION[$key] = 1; }
        else {
          $_SESSION[$key] = 0; }
      }
    }
  }
}

?>
