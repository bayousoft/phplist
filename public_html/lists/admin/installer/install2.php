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

if ($_SESSION["session_ok"] != 1){
   header("Location:?");
}



/***************************************************
  This script use only the structure BOUNCE_DEF
***************************************************/

include("lib/parameters.inc");

$inTheSame = 1;
$msg       = "";
$errno     = "";

if ($submited){
   /* The code above take the mission to write in config.php file
      the configuration parameters that the user charge, and another data
      witch are written by default (at least in the BASIC installation)
      It's the final Step (2)
   */

   // Database writing
   $path = "../config/";


   if (is_file($path."config.php")){
      /*
      CODE TO TRY TO REWRITE config.php IF EXISTS (can't by done cause permission problem)
      if (!is_readable($path."config.php")){
         $errno = 1;
         $msg   = $GLOBALS["I18N"]->get($GLOBALS["strConfigIsNotWritable"]);
      }
      else{
         copy($path."config.php", $path."config.php.ori");
         $stat  = writeConfigFile();

         if (!$stat){
            $errno = 1;
            $msg   = $GLOBALS["I18N"]->get($GLOBALS["strConfigRewriteError"]);
         }
         else{
            $errno = 0;
            $msg   = $GLOBALS["I18N"]->get($GLOBALS["strConfigWasRewrited"]);
         }
      }
      */
      $errno = 1;
      $msg   = $GLOBALS["I18N"]->get($GLOBALS["strConfigExists"]);
   }
   else{
      $stat  = writeConfigFile($path."config.php");

      if (!$stat){
         $errno = 1;
         $texto = str_replace("{path}","'$path'",$GLOBALS["strConfigDirNoWritable"]);
         $msg   = $GLOBALS["I18N"]->get($texto);
      }
      else{
         $errno = 0;
         $msg   = $GLOBALS["I18N"]->get($GLOBALS["strConfigWrited"]);
         header("Location:?");
      }
   }
}

include("installer/lib/js_nextPage.inc");
?>

<script type="text/javascript">
/* Is needed to declare this function (even in this "dummy" way)
   because is referenced in the js_nextPage.inc file
*/

function validation(){
   return true;
}
</script>
<br>
<br>
<div class="wrong"><?echo $msg?></div>
<style type="text/css">
table tr td input { float:right; }
</style>

<?
if ($errno || !$submited){
?>
<table width=500>
  <tr>
    <td>
    <div class="explain"><?echo $GLOBALS["I18N"]->get($GLOBALS['strReadyToInstall'])?></div>
    </td>
  </tr>
</table>

<form method="post" name="pageForm">
  <input type="hidden" name="page" value="<?echo $nextPage?>"/>
  <input type="hidden" name="submited" value="<?echo $inTheSame?>"/>

</form>
<?php
include("installer/lib/nextStep.inc");
?>
<?}?>
