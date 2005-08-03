<?php
require_once dirname(__FILE__).'/accesscheck.php';

if (TEST)
  print $GLOBALS['I18N']->get('default login is')." <b>admin</b>, ".$GLOBALS['I18N']->get('with password')." <b>phplist</b>";

if (isset($_GET['page']) && $_GET["page"]) {
  $page = $_GET["page"];
  if (!is_file($page.".php") || $page == "logout") {
    $page = "home";
  }
} else {
  $page = "home";
}
if (!isset($GLOBALS['msg'])) $GLOBALS['msg'] = '';
?>
<font class="error"><?php echo $GLOBALS['msg']?></font>
<p>
<form method=post>
<input type=hidden name="page" value="<?php echo $page?>">
<table width=100% border=0 cellpadding=2 cellspacing=0>

<tr><td><span class="general"><?php echo $GLOBALS['I18N']->get('name');?>:</span></td></tr>
<tr><td><input type=text name="login" value="" size=30></td></tr>

<tr><td><span class="general"><?php echo $GLOBALS['I18N']->get('password');?>:</span></td></tr>
<tr><td><input type=password name="password" value="" size=30></td></tr>

<tr><td><input type=submit name="process" value="<?php echo $GLOBALS['I18N']->get('enter');?>"></td></tr></table>

<br/>
<p align="center"><hr width=50% size=3></p>
<b><?php echo $GLOBALS['I18N']->get('forgot password');?>:</b><br/>
<?php echo $GLOBALS['I18N']->get('enter your email');?>: <input type=text name="forgotpassword" value="" size=30><br/><br/>
<input type=submit name="process" value="<?php echo $GLOBALS['I18N']->get('send password');?>">

</form>