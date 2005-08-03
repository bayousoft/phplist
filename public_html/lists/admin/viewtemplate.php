<?php
# view template
require_once dirname(__FILE__).'/accesscheck.php';
if ($_GET["pi"] && defined("IN_WEBBLER")) {
  $more = '&pi='.$_GET["pi"];
}

if (!$_GET["embed"]) {
  print '<iframe src="?page=viewtemplate&embed=yes&omitall=yes&id='.$_GET["id"].$more.'"
    scrolling="auto" width=100% height=450 margin=0 frameborder=0>
  </iframe>';
  print '<p>'.PageLink2("template&id=".$_GET["id"],$GLOBALS['I18N']->get('BackEditTemp')).'</p>';
} else {
  ob_end_clean();
  print previewTemplate($id,$_SESSION["logindetails"]["id"],nl2br($GLOBALS['I18N']->get('TempSample')));
}

?>
