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


         if (!isset($variable["file_description_after"])){
            $linevar.= "\n";
            $txtafter= "";
         }
         else{
            $linevar.= "";
            $txtafter= $variable["file_description_after"];
         }


         fwrite($fp, $linevar);  // escribo declaracion del parametro
         fwrite($fp, $txtafter); // escribo descripcion posterior del parametro
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
   $port = (isset($_REQUEST["bounce_mailbox_port"]))?$_REQUEST["bounce_mailbox_port"]:false;

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

function check_connection($host, $user, $pass, $dbname){
   $errno  = 0;
   $msg    = "";
   $created= "";

   if ($host && $user && $pass){
      $conn = @mysql_connect($host, $user ,$pass);

      if (!$conn){
         $errno = mysql_errno();
         //echo "$errno - ACA<br>";
      }
      else{
         if ($dbname){
            $res = @mysql_select_db($dbname, $conn);

            if (!$res){
               $creating = 1;
               $errno = mysql_errno($conn);
               echo "axa - $errno<br>";

               $res = mysql_query("CREATE DATABASE $dbname", $conn);

               if (!$res){
                  $errno = mysql_errno($conn);
                  //echo "axa2 - $errno".mysql_error()."<br>";
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

   $txtDbOK = $GLOBALS['strDbConnOK'];
   $txtDbConnRef = $GLOBALS['strDbConnRef'];
   $txtDbServerBusy = $GLOBALS['strDbServerBusy'];
   $txtDbWrongData = $GLOBALS['strDbWrongUserData'];
   $txtDbDeniedC = $GLOBALS['strDbDeniedCr'];
   $txtDbHostNeeded = $GLOBALS['strDbHostNeeded'];
   $txtDbUserNeeded = $GLOBALS['strDbUserNeeded'];
   $txtDbPassNeeded = $GLOBALS['strDbPassNeeded'];
   $txtDbNotExist = $GLOBALS['strDbNotExist'];
   $txtDbNotCreate = $GLOBALS['strDbNotCreate'];
   $txtDbExists = $GLOBALS['strDbExists'];
   $txtDbCreated = $GLOBALS['strDbCreated'];

   //echo "ERRNO: $errno";

   switch ($errno){
      case 2003: 
      case 2004: 
      case 2005: $msg = $GLOBALS["I18N"]->get($txtDbConnRef);    break;
      case 1040: $msg = $GLOBALS["I18N"]->get($txtDbServerBusy); break;
      case 1044: $msg = $GLOBALS["I18N"]->get(sprintf($txtDbDeniedC, $user, $dbname)); break;
      case 1045: $msg = $GLOBALS["I18N"]->get($txtDbWrongData); break;
      case 1049: $msg = $GLOBALS["I18N"]->get(sprintf($txtDbNotExist, $dbname).(($creating)?" and can't be created, check permission for the user":"")); break;
      case 1006: $msg = $GLOBALS["I18N"]->get(sprintf($txtDbNotCreate, $dbname)); break;
      case 1007: $msg = $GLOBALS["I18N"]->get(sprintf($txtDbExists, $dbname)); break;
      case 6661: $msg = $GLOBALS["I18N"]->get($txtDbHostNeeded); break;
      case 6662: $msg = $GLOBALS["I18N"]->get($txtDbUserNeeded); break;
      case 6663: $msg = $GLOBALS["I18N"]->get($txtDbPassNeeded); break;
      case 0   : $msg = $GLOBALS["I18N"]->get($txtDbOK.(($creating)?" - ".sprintf($txtDbCreated, $dbname):"")); break;
   }

   return "$errno|$msg|$created";
}
?>
