<?
require_once "accesscheck.php";

class date {
  var $type = "date";
  var $name = "";
  var $description = "Date";
  var $days = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
  var $months = array(
     "01" => "January",
     "02" => "February",
     "03" => "March",
     "04" => "April",
     "05" => "May",
     "06" => "June",
     "07" => "July",
     "08" => "August",
     "09" => "September",
     "10" => "October",
     "11" => "November",
     "12" => "December"
  );
  var $useTime = false;

  function date($name = "") {
  	$this->name = $name;
  }

  function getDate($value = "") {
  	if (!$value)
    	$value = $this->name;
    if ($_REQUEST["year"] && $_REQUEST["month"] && $_REQUEST["day"])
      return sprintf("%04d-%02d-%02d",$_REQUEST["year"][$value],$_REQUEST["month"][$value],$_REQUEST["day"][$value]);
  }

  function getTime($value = "") {
  	if (!$value)
    	$value = $this->name;
    if ($_REQUEST["hour"] && $_REQUEST["minute"])
      return sprintf("%02d:%02d",$_REQUEST["hour"][$value],$_REQUEST["minute"][$value]);
	}

  function showInput($name,$fielddata,$value,$document_id = 0) {
  	if (!$name)
    	$name = $this->name;
#    dbg("$name $fielddata $value $document_id");
    $year = substr($value,0,4);
    $month = substr($value,5,2);
    $day = substr($value,8,2);

    if (!$day && !$month && !$year) {
      $now = getdate(time());
      $day = $now["mday"];
      $month = $now["mon"];
      $year = $now["year"];
    }
    $html = "";

    $html .= "<!-- $day / $month / $year -->".'<select name="day['.$name.']">';
    for ($i=1;$i<32;$i++) {
      $sel = "";
      if ($i == $day)
        $sel = "selected";
      $html .= sprintf('<option value="%d" %s>%s',$i,$sel,$i);
    }
    $html .= '</select><select name="month['.$name.']">';
		reset($this->months);
    while (list($key,$val) = each ($this->months)) {
      $sel = "";
      if ($key == $month)
        $sel = "selected";
      $html .= sprintf('<option value="%s" %s>%s',$key,$sel,$val);
    }

    $html .= '</select><select name="year['.$name.']">';
    for ($i=$year - 3;$i<$year + 10;$i++) {
      $html .= "<option ";
      if ($i == $year)
        $html .= "selected";
      $html .= ">$i";
    }
    $html .= "</select>";
    if ($this->useTime) {
    	$html .= '<select name="hour['.$name.']">';
      for ($i=0;$i<23;$i++) {
        $sel = "";
        if ($i == $hour)
          $sel = "selected";
        $html .= sprintf('<option value="%d" %s>%02d',$i,$sel,$i);
      }
      $html .= '</select>';
    	$html .= '<select name="minute['.$name.']">';
      for ($i=0;$i<59;$i+=15) {
        $sel = "";
        if ($i == $minute)
          $sel = "selected";
        $html .= sprintf('<option value="%d" %s>%02d',$i,$sel,$i);
      }
      $html .= '</select>';
    }
    return $html;
  }

  function display($parent,$data,$leaf,$branch) {
    global $config;
    return formatDate($data);
  }

  function store($itemid,$fielddata,$value,$table) {
    Sql_query(sprintf('replace into %s values("%s",%d,"%s")',$table,$fielddata["name"],$itemid,$this->getDate($value)));
  }
}

?>
