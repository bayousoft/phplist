<?php
require_once dirname(__FILE__).'/accesscheck.php';

# reset click track statistics

foreach (array('linktrack','linktrack_ml','linktrack_uml_click','linktrack_forward','linktrack_userclick') as $table) {
  Sql_Query(sprintf('delete from %s',$GLOBALS['tables'][$table]));
}

print $GLOBALS['I18N']->get('Statistics erased');

?>