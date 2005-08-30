<?php
require_once dirname(__FILE__).'/accesscheck.php';
/*

Languages, countries, and the charsets typically used for them
http://www.w3.org/International/O-charset-lang.html

*/

$LANGUAGES = array(
#"af" => array("Afrikaans","iso-8859-1, windows-1252"),
#"sq" => array("Albanian","iso-8859-1, windows-1252"),
#"ar" => array("Arabic","iso-8859-6"),
#"eu" => array("Basque","iso-8859-1, windows-1252"),
#"bg" => array("Bulgarian","iso-8859-5"),
#"be" => array("Byelorussian","iso-8859-5"),
#"ca" => array("Catalan","iso-8859-1, windows-1252"),
#"hr" => array("Croatian"," iso-8859-2, windows-1250 "),
#"cs" => array("Czech "," iso-8859-2 "),
#"da" => array("Danish "," iso-8859-1, windows-1252 "),
#"nl"=> array("Dutch "," iso-8859-1, windows-1252 "),
#"eo" => array("Esperanto "," iso-8859-3* "),
#"et" => array("Estonian ","iso-8859-15 "),
#"fo" => array("Faroese "," iso-8859-1, windows-1252 "),
#"fi"=> array("Finnish "," iso-8859-1, windows-1252 "),
"de" => array("Deutsch "," iso-8859-1, windows-1252 "),
"en" => array("English "," iso-8859-1, windows-1252 "),
"es"=>array("espa&ntilde;ol"," iso-8859-1, windows-1252"),
"fr"=>array("fran&ccedil;ais ","iso-8859-1, windows-1252 "),
#"gl"=>array("Galician "," iso-8859-1, windows-1252 "),
#"el"=> array("Greek "," iso-8859-7 "),
#"iw"=> array("Hebrew "," iso-8859-8 "),
#"hu"=>array("Hungarian "," iso-8859-2 "),
#"is"=>array("Icelandic "," iso-8859-1, windows-1252 "),
#"ga"=>array("Irish "," iso-8859-1, windows-1252 "),
#"it"=>array("Italian "," iso-8859-1, windows-1252 "),
#"ja"=>array("Japanese "," shift_jis, iso-2022-jp, euc-jp"),
#"lv"=> array("Latvian ","iso-8859-13, windows-1257"),
#"lt"=> array("Lithuanian "," iso-8859-13, windows-1257"),
#"mk"=> array("Macedonian ","iso-8859-5, windows-1251"),
#"mt"=> array("Maltese ","iso-8859-3"),
#"no"=>array("Norwegian ","iso-8859-1, windows-1252"),
#"pl"=>array("Polish ","iso-8859-2"),
#"pt"=>array("Portuguese "," iso-8859-1, windows-1252"),
"pt-br"=>array("portugu&ecirc;s ","iso-8859-1, windows-1252"),
#"ro"=>array("Romanian "," iso-8859-2"),
#"ru"=>array("Russian "," koi8-r, iso-8859-5"),
#"gd"=>array("Scottish "," iso-8859-1, windows-1252"),
#"srcyrillic"=>array("Serbian "," windows-1251, iso-8859-5"),
#"srlatin"=>array("Serbian "," iso-8859-2, windows-1250"),
#"sk"=>array( "Slovak "," iso-8859-2"),
#"sl"=>array( "Slovenian "," iso-8859-2, windows-1250"),
#"sv"=>array("Swedish "," iso-8859-1, windows-1252"),
#"tr"=> array("Turkish "," iso-8859-9, windows-1254"),
#"uk"=>array("Ukrainian "," iso-8859-5"),
"zh-tw" => array("Traditional Chinese","utf-8"),
);

if (!empty($GLOBALS["SessionTableName"])) {
  require_once dirname(__FILE__).'/sessionlib.php';
}
@session_start();

if (isset($_POST['setlanguage']) && $_POST['setlanguage'] && is_array($LANGUAGES[$_POST['setlanguage']])) {
  $_SESSION['adminlanguage'] = array(
    "info" => $_POST['setlanguage'],
    "iso" => $_POST['setlanguage'],
    "charset" => $LANGUAGES[$_POST['setlanguage']][1],
  );
}

if (!isset($_SESSION['adminlanguage']) || !is_array($_SESSION['adminlanguage'])) {
  if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $accept_lan = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
  } else {
    $accept_lan = array('en'); # @@@ maybe make configurable?
  }
  $detectlan = '';
  foreach ($accept_lan as $lan) {
    if (!$detectlan) {
      if (preg_match('/^([\w-]+)/',$lan,$regs)) {
        $code = $regs[1];
        if (isset($LANGUAGES[$code])) {
          $detectlan = $code;
        } elseif (ereg('-',$code)) {
          list($language,$country) = explode('-',$code);
          if (isset($LANGUAGES[$language])) {
            $detectlan = $language;
          }
        }
      }
    }
  }
  if (!$detectlan) {
    $detectlan = 'en';
  }

  $_SESSION['adminlanguage'] = array(
    "info" => $detectlan,
    "iso" => $detectlan,
    "charset" => $LANGUAGES[$detectlan][1],
  );
}
$GLOBALS['strCharSet'] = $_SESSION['adminlanguage']['charset'];

# internationalisation (I18N)
class phplist_I18N {
  var $defaultlanguage = 'en';
  var $language = 'en';
  var $basedir = '';

  function phplist_I18N() {
    $this->basedir = dirname(__FILE__).'/lan/';
    if (isset($_SESSION['adminlanguage']) && is_dir($this->basedir.$_SESSION['adminlanguage']['iso'])) {
      $this->language = $_SESSION['adminlanguage']['iso'];
    } else {
      print "Not set or found: ".$_SESSION['adminlanguage'];
      exit;
    }
  }

  function formatText($text) {
    if (isset($GLOBALS["developer_email"])) {
      return '<font color=#A704FF>'.str_replace("\n","",$text).'</font>';
#       return 'TE'.$text.'XT';
    }
    return str_replace("\n","",$text);
  }

  function missingText($text) {
    if (isset($GLOBALS["developer_email"])) {
      if (isset($_GET['page'])) {
        $page = $_GET["page"];
      } else {
        $page = 'main page';
      }

      $msg = '

      Undefined text reference in page '.$page.'

      '.$text;

      #sendMail($GLOBALS["developer_email"],"phplist dev, missing text",$msg);
      $line = "'".$text."' => '".$text."',";
      $this->appendText('/tmp/'.$page.'.php',$line);

      return '<font color=#FF1717>'.$text.'</font>';#MISSING TEXT
    }
    return $text;
  }

  function appendText($file,$text) {
    if (is_file($file)) {
      $fp = @fopen ($file,"a");
    } else {
      $fp = @fopen($file,"w");
    }

    if ($fp) {
      fwrite($fp,$text."\n");
      fclose($fp);
    }
  }

  function get($text) {
    if (isset($_GET["page"]))
      $page = $_GET["page"];
    else
      $page = "home";
    if (trim($text) == "") return "";
    if (strip_tags($text) == "") return $text;
    if (is_file($this->basedir.$this->language.'/'.$page.'.php')) {
      @include $this->basedir.$this->language.'/'.$page.'.php';
    } elseif (!isset($GLOBALS['developer_email'])) {
      @include $this->basedir.$this->defaultlanguage.'/'.$page.'.php';
    }
    if (isset($lan) && is_array($lan) && isset($lan[$text])) {
      return $this->formatText($lan[$text]);
    }
    if (isset($lan) && is_array($lan) && isset($lan[strtolower($text)])) {
      return $this->formatText($lan[strtolower($text)]);
    }
    if (isset($lan) && is_array($lan) && isset($lan[strtoupper($text)])) {
      return $this->formatText($lan[strtoupper($text)]);
    }
    if (is_file($this->basedir.$this->language.'/common.php')) {
      @include $this->basedir.$this->language.'/common.php';
    } elseif (!isset($GLOBALS['developer_email'])) {
      @include $this->basedir.$this->defaultlanguage.'/common.php';
    }
    if (is_array($lan) && isset($lan[$text])) {
      return $this->formatText($lan[$text]);
    }
    if (is_array($lan) && isset($lan[strtolower($text)])) {
      return $this->formatText($lan[strtolower($text)]);
    }
    if (is_array($lan) && isset($lan[strtoupper($text)])) {
      return $this->formatText($lan[strtoupper($text)]);
    }

    if (is_file($this->basedir.$this->language.'/frontend.php')) {
      @include $this->basedir.$this->language.'/frontend.php';
    } elseif (!isset($GLOBALS['developer_email'])) {
      @include $this->basedir.$this->defaultlanguage.'/frontend.php';
    }
    if (is_array($lan) && isset($lan[$text])) {
      return $this->formatText($lan[$text]);
    }
    if (is_array($lan) && isset($lan[strtolower($text)])) {
      return $this->formatText($lan[strtolower($text)]);
    }
    if (is_array($lan) && isset($lan[strtoupper($text)])) {
      return $this->formatText($lan[strtoupper($text)]);
    }
    return $this->missingText($text);
  }
}

$I18N = new phplist_I18N();



