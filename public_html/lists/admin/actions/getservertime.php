<?php

$currentTime = Sql_Fetch_Row_Query('select now()');
$status = $currentTime[0];

