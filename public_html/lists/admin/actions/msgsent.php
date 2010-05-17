<?php

@ob_end_clean();
$id = sprintf('%d',$_GET['id']);
if (!$id) {
  return '';
}
$message = Sql_Fetch_Assoc_Query(sprintf('select * from %s where id = %d',$GLOBALS['tables']['message'],$id));
if ($message['id'] != $id) return '';
$messagedata = loadMessageData($id);

$totalsent = $message['astext'] + 
  $message['ashtml'] + 
  $message['astextandhtml'] + 
  $message['aspdf'] + 
  $message['astextandpdf'];

print $totalsent;
exit;
?>
