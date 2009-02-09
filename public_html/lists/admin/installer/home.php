<?php
/* this script is called by install.php
   that parent-script have serveral included files
   witch contain some functions used in this one.
   i.e.:
   steps-lib.php
   languages.php
   mysql.inc
   install-texts.inc
*/

$_SESSION["session_ok"] = 1;

include("installer/lib/js_nextPage.inc");
?>
<div id="language_change">
  <script language="JavaScript" type="text/javascript">
  function langChange(){
     var lang_change=this.window.document.lang_change;

     if(lang_change.language_module.selectedIndex==0)return false;

     lang_change.submit();
     return true;
  }
  </script>
  <p>
  <form name="lang_change" action="" method=POST>
  <?echo languagePack("","langChange();")?>
  </form>
  <?'/*.$GLOBALS["I18N"]->get($GLOBALS["strLanguageOpt"])*/.'?>
  </p>
</div>

<div id="phplist_logo_header">
  <span class="phplist_logo_span"><img src="images/phplist-logo.png" title="phplist"></span>
  <span class="title_installer"><?php print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strInstallerTitle"]));?></span>
</div>
<div id="maincontent_install">
  <div class="intro_install"><?php print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strIntroInstaller"]));?></div>
</div>

<script type="text/javascript">
function validation(){
   var frm = document.pageForm;
   
   return true;
}
</script>

<form method="post" name="pageForm">
  <input type="hidden" name="page" value="<?echo $nextPage?>"/>
  <input type="hidden" name="submited" value="<?echo $inTheSame?>"/>
</form>

<?php
include("installer/lib/nextStep.inc");
?>
