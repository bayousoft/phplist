<? 
foreach ($_SESSION as $key) {
  unset($key);
}
?>
<div id="phplist_logo_header">
  <span class="phplist_logo_span"><img src="images/phplist-logo.png" title="phplist"></span>
  <span class="title_installer"><?php print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strInstallerTitle"]));?></span>
</div>
<div id="maincontent_install">
  <div class="intro_install"><?php print $GLOBALS["I18N"]->get(sprintf('%s',$GLOBALS["strIntroInstaller"]));?></div>
</div>
