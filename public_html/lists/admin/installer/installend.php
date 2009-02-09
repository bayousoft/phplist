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

$msg = $GLOBALS["I18N"]->get($GLOBALS["strConfigWrited"]);

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
<style type="text/css">
table tr td input { float:right; }
</style>

<table width=500 align=center>
  <tr>
    <td align=center>
    <br>
    <br>
    <div class="explain"><?echo $msg?></div>
    <br>
    <br>
    </td>
  </tr>
</table>
