<?php
/* This file receive tha variable $textSubmit from the caller script.
   This variable have the step number description for page related.
*/
if ($_SESSION["installType"] == "BASIC")
     $maxPage = 3;
else $maxPage = 5;

if ($inTheSame)
     $nextPage = (isset($page) && $page != "")?$page:0;
else $nextPage = (isset($page) && $page != "")?$page+1:0;

if ($page+1 < $maxPage)
     $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strNextStep"]);
else $textSubmit = $GLOBALS["I18N"]->get($GLOBALS["strWriteToConfig"]);


?>
<script language="Javascript" type="text/javascript">
var submitted = false;

function goToPage() {
   if (validation()){
      if (!submitted) {
         submitted = true;

         document.pageForm.submit();
      }
   }
}
</script>
<noscript><input type="submit" name="submit" value="<?echo $textSubmit?>"/></noscript>
