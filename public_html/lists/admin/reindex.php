<?php

## make sure all the index exist in the database
@ob_end_flush();
include dirname(__FILE__).'/structure.php';

foreach ($DBstruct as $table => $columns) {
  print $table.'<br/>';
  foreach ($columns as $column => $definition) {
    if (strpos($column,'index') === 0) {
      print "Adding index: $definition[0] to $column, <br/>";
      flush();
      Sql_Query(sprintf('alter table %s add index %s',$table,$definition[0]),1);
    }
  }
}
