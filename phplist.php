<?
# class to become plugin for the web httblerp://demo.tincan.co.uk
# this file is not used in the standalone version

class phplist extends DefaultPlugin {
  var $DBstructure = array();
  var $tables = array();
  var $table_prefix = "";
  var $VERSION = "dev";

  function phplist() {
    global $tables,$config;
    $table_prefix = "phplist_";
    $this->table_prefix = $table_prefix;
    $usertable_prefix = "";
    include $config["code_root"]."/uploader/plugins/phplist/".$this->coderoot()."/structure.php";
    $this->DBstructure = $DBstruct;

    $this->tables = array(
      "user" => $usertable_prefix . "user",
  		"user_history" => $usertable_prefix . "user_history",
      "list" => $table_prefix . "list",
      "listuser" => $table_prefix . "listuser",
      "message" => $table_prefix . "message",
      "listmessage" => $table_prefix . "listmessage",
      "usermessage" => $table_prefix . "usermessage",
      "attribute" => $usertable_prefix . "attribute",
      "user_attribute" => $usertable_prefix . "user_attribute",
      "sendprocess" => $table_prefix . "sendprocess",
      "template" => $table_prefix . "template",
      "templateimage" => $table_prefix . "templateimage",
      "bounce" => $table_prefix ."bounce",
      "user_message_bounce" => $table_prefix . "user_message_bounce",
      "config" => $table_prefix . "config",
      "admin" => $table_prefix . "admin",
      "adminattribute" => $table_prefix . "adminattribute",
      "admin_attribute" => $table_prefix . "admin_attribute",
      "admin_task" => $table_prefix . "admin_task",
      "task" => $table_prefix . "task",
      "subscribepage" => $table_prefix."subscribepage",
      "subscribepage_data" => $table_prefix . "subscribepage_data",
      "eventlog" => $table_prefix . "eventlog",
      "attachment" => $table_prefix."attachment",
      "message_attachment" => $table_prefix . "message_attachment",
      "rssitem" => $table_prefix . "rssitem",
      "rssitem_data" => $table_prefix . "rssitem_data",
      "user_rss" => $table_prefix . "user_rss",
      "rssitem_user" => $table_prefix . "rssitem_user"
    );
    if (!$this->isInitialised()) {
      $this->initialise();
    }
    $this->addDataType("phplist_1","Mailinglist Pages");
  }

  function name() {
    return "Advanced Mailinglists";
  }

  function home() {
    return "public_html/lists/admin/home.php";
  }

  function coderoot() {
    return "public_html/lists/admin/";
  }

  function codelib() {
    return array(
    	"public_html/lists/admin/lib.php",
    	"public_html/lists/admin/defaultconfig.inc",
      "public_html/lists/texts/english.inc");
  }

  function frontendlib() {
  	return array(
    	"public_html/lists/admin/lib.php",
    	"public_html/lists/admin/frontendlib.php",
    	"public_html/lists/admin/defaultconfig.inc",
      "public_html/lists/texts/english.inc"
		);
  }

  function parseText($data,$leaf,$branch) {
    return $data;
  }
  
  function getListsAsArray() {
  	$list = array();
    $list[0] = "-- None";
    $req = Sql_Query(sprintf('select distinct id, name from %s order by listorder',$this->tables["list"]));
    while ($row = Sql_Fetch_Array($req)){
    	$list[$row["id"]] = $row["name"];
    }
    return $list;
	}
  
  function addUserToList($userid,$listid) {
  	Sql_Query(sprintf('replace into %s (userid,listid,entered) values(%d,%d,now())',
    	$this->tables["listuser"],$userid,$listid));
  }

  function confirmUser($userid) {
  	Sql_Query(sprintf('update %s set confirmed = 1 where id = %d',$this->tables["user"],$userid));
  }
  
  function sendConfirmationRequest($userid) {
  	Sql_Query(sprintf('update %s set confirmed = 1 where id = %d',$this->tables["user"],$userid));
  }

  function display($subtype,$name,$value,$docid = 0) {
    global $config;
    $data = parseDelimitedData($value);
    $html = sprintf('<input type=hidden name="%s"
       value="%d">',$name,$subtype,$subtype);
    switch ($subtype) {
      case "1":
        $html .= '<p>Select the form to use for users to subscribe</p>';
        $req = Sql_Query(sprintf('select * from %s where active',$this->tables["subscribepage"]));
        $html .= sprintf('<select name="%s_spage">',$name);
        $html .= sprintf('<option value="0"> -- select one</option>');
        while ($row = Sql_Fetch_Array($req)) {
        	$selected = $data["spage"] == $row["id"] ? "selected":"";
          $html .= sprintf('<option value="%s" %s> %s</option>',$row["id"],$selected,$row["title"]);
        }
        return $html;
    }
    return "Invalid subtype: $subtype";
  }

  function adminTasks() {
    $tasks = array(
      "home" => "Administer Mailinglists",
      "configure" => "Configure Mailinglists",
      "list" => "Lists",
      "messages" => "Messages",
      "reconcileusers" => "Reconcile",
      "export" => "Export Emails",
      "attributes" => "Attributes",
      "spage" => "Configure Subscribe Pages",
    );
    if ($this->tables["attribute"] && Sql_Table_Exists($this->tables["attribute"])) {
      $res = Sql_Query("select * from {$this->tables['attribute']}",1);
      while ($row = Sql_Fetch_array($res)) {
        if ($row["type"] != "checkbox" && $row["type"] != "textarea" && $row["type"] != "textline" && $row["type"] != "hidden")
          $tasks["editattributes&"."id=".$row["id"]] = '=&gt; '.strip_tags($row["name"]);
      }
    }
    $tasks["templates"] = "Templates";
    $tasks["send"] = "Send a message";
    $tasks["bounces"] = "View Bounces";
    $tasks["eventlog"] = "Eventlog";
    return $tasks;
  }
  
  function adminPages() {
    $tasks = array(
      "home" => "Administer Mailinglists",
      "list" => "Lists",
      "messages" => "Messages",
      "reconcileusers" => "Reconcile",
      "export" => "Export Emails",
      "attributes" => "Attributes",
      "editattributes" => "Edit Attributes",
      "templates" => "Templates",
      "send" => "Send a message",
      "bounces" => "View Bounces",
      "eventlog" => "Eventlog",
      "spage" => "Configure Subscribe Pages",
      "spageedit" => "Edit a Subscribe Page",
    );
    return $tasks;
  }

  function selectPage($id) {
  	if (!$id) return '<!-- no subscribe page defined -->';
    $html = '';
#    if (preg_match("/(\w+)/",$_GET["p"],$regs)) {
    switch ($_GET["p"]) {
      case "preferences":
        if (!$_GET["id"]) $_GET["id"] = $id;
        require $this->coderoot()."/subscribelib2.php";
        $html = PreferencesPage($id,$userid);
        break;
      case "confirm":
        $html = ConfirmPage($id);
        break;
      case "unsubscribe":
        $html = UnsubscribePage($id);
        break;
      default:
      case "subscribe":
        require $this->coderoot() ."/subscribelib2.php";
        $html = SubscribePage($id);
        break;
    }
    return $html;
	}

  function show($dbdata,$leaf,$branch,$fielddata) {
    global $config;
    $data = parseDelimitedData($dbdata);
    switch ($data["subtype"]) {
      case "1":
        return $this->selectPage($data["spage"]);break;
      default: return "";
    }
  }

  function initialise() {
    dbg("initialising phplist");
    global $config;
    foreach($this->DBstructure as $table => $val){
      if (!Sql_Table_exists($table)) {
    #    print "creating $table <br>\n";
        Sql_Create_Table($this->tables[$table],$this->DBstructure[$table]);
      }
    }
  }

  function isInitialised() {
    global $config;
    return Sql_Table_Exists($this->tables["list"]);
  }

  function upgrade() {
    global $config,$tables;
    $doit = "yes";
    $tables = $this->tables;
    include $config["code_root"].'/'.$config["uploader_dir"].'/plugins/phplist/'.$this->coderoot()."/upgrade.php";
  }
  
  function version() {
    return $this->VERSION;
  }

  function duplicateLeaf($from,$to,$table) {
  }

  function deleteLeaf($leaf,$fielddata,$table) {
  }

  function store($itemid,$fielddata,$value,$table) {
    global $config;
    $data["name"] = $fielddata["name"];
    $data["subtype"] = $_POST[$value];
    if ($data["subtype"] == 1) {
    	# save link info
      $data["spage"] = $_POST[$fielddata["name"]."_spage"];
      $data["subtype"] = 1;
    }

    Sql_query(sprintf('replace into %s values("%s",%d,"%s")',$table,$fielddata["name"],$itemid,delimited($data)));
  }

}
?>
