<?php

if (!empty($_GET['email'])) {
  Sql_Query(sprintf('insert into %s (email) values("%s")',$GLOBALS['tables']['user'],sql_escape($_GET['email'])),1);
  addUserHistory($_GET['email'],'Added by '.adminName(),'');
}
$status = $GLOBALS['I18N']->get('Email added');
