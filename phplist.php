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
    $this->addDataType("phplist_1","Mailinglist Subscribe Page");
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
    return array("public_html/lists/admin/lib.php","public_html/lists/admin/defaultconfig.inc","public_html/lists/texts/english.inc");
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
    $html = sprintf('<input type=hidden name="%s"
       value="%d">',$name,$subtype,$subtype);
    switch ($subtype) {
      case "1":
        $html .= '<p>Select the form to use for users to subscribe</p>';
        $html .= '<p>Select the lists to offer for subscription:</p>';
        $req = Sql_Query(sprintf('select * from %s order by listorder',$this->tables["list"]));
        while ($row = Sql_Fetch_Array($req)) {
          $html .= sprintf('<input type=checkbox name="%s_lists[]" value="%s"> %s <br/>',$name,$row["id"],$row["name"]);
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

  function show($dbdata,$leaf,$branch,$fielddata) {
    global $config;
    $data = parseDelimitedData($dbdata);
    switch ($fielddata["type"]) {
      case "phplist_1":
        if (preg_match("/(\w+)/",$GET["p"],$regs)) {
          if (is_file($regs[1].".php")) {
            include $regs[1].".php";
          } else {
            print "Error: no such page: $p";
          }
        }
        else {
          $html .= sprintf('<p><a href="./?p=subscribe">%s</a></p>',$strSubscribeTitle);
          $html .= sprintf('<p><a href="./?p=unsubscribe">%s</a></p>',$strUnsubscribeTitle);
          $html .= $PoweredBy;
        }
        return $html;
      default: return "";
    }
    return $html;
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
  }

}
?>
