<?php

require_once dirname(__FILE__).'/accesscheck.php';
if (!ALLOW_IMPORT) {
  print '<p class="">'.$GLOBALS['I18N']->get('import is not available').'</p>';
  return;
}

require dirname(__FILE__).'/commonlib/pages/importcsv.php';

?>
