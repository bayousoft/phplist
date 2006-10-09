
<!-- all info is in the info file -->
<?php
if (!ALLOW_IMPORT) {
  print $GLOBALS['I18N']->get('import is not available');
  return;
}

if ($GLOBALS['commandline']) {
  $file = $cline['f'];
  if (!is_file($file)) {
    print ClineError('Cannot find file to import (hint: use -f)');
  }
  if (!$cline['l']) {
    print ClineError('Specify lists to import users to');
  }
  print clineSignature();

  ob_start();
  $_FILES["import_file"] = array(
    'tmp_name' => $file,
    'name' => $file,
    'size' => filesize($file),
  );
  $_POST['lists'] = explode(',',$cline['l']);
  $_POST['groups'] = explode(',',$cline['g']);

  
  $_POST['import'] = 1;
  $_POST['overwrite'] = 'yes';
  $_POST['notify'] = 'no';
  $_POST['omit_invalid'] = 'yes';
  $_POST["import_field_delimiter"] = "\t";
  $_POST["import_field_delimiter"] = ',';
  $_POST["import_record_delimiter"] = "\n";
  require dirname(__FILE__).'/import2.php';
  ob_end_clean();
  print "\nAll done\n";
  exit;
}
?>