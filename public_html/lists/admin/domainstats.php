<?php

# domain stats

$totalreq = Sql_Fetch_Row_Query(sprintf('select count(*) from %s',$GLOBALS['tables']['user']));
$total = $totalreq[0];

$confirmed = array();
$req = Sql_Query(sprintf('select lcase(substring_index(email,"@",-1)) as domain,count(email) as num from %s where confirmed group by domain order by num desc limit 50',$GLOBALS['tables']['user']));
$ls = new WebblerListing($GLOBALS['I18N']->get('Top 50 domains with more than 5 emails'));
while ($row = Sql_Fetch_Array($req)) {
  if ($row['num'] > 5) {
    $ls->addElement($row['domain']);
    $confirmed[$row['domain']] = $row['num'];
    $ls->addColumn($row['domain'],$GLOBALS['I18N']->get('confirmed'),$row['num']);
    $perc = sprintf('%0.2f',($row['num'] / $total * 100));
    $ls->addColumn($row['domain'],'<!-conf-->'.$GLOBALS['I18N']->get('perc'),$perc);

  }
}
$req = Sql_Query(sprintf('select lcase(substring_index(email,"@",-1)) as domain,count(email) as num from %s where !confirmed group by domain order by num desc limit 50',$GLOBALS['tables']['user']));
while ($row = Sql_Fetch_Array($req)) {
/*  if (!in_array($confirmed,$row['domain'])) {
    $ls->addElement($row['domain']);
  }*/
  if (in_array($row['domain'],array_keys($confirmed))) {
    if ($row['num'] > 5) {
      $ls->addColumn($row['domain'],$GLOBALS['I18N']->get('unconfirmed'),$row['num']);
      $perc = sprintf('%0.2f',($row['num'] / $total * 100));
      $ls->addColumn($row['domain'],'<!--unc-->'.$GLOBALS['I18N']->get('perc'),$perc);
    }
    $ls->addColumn($row['domain'],$GLOBALS['I18N']->get('num'),$row['num'] + $confirmed[$row['domain']]);
    $perc = sprintf('%0.2f',(($row['num'] + $confirmed[$row['domain']]) / $total * 100));
    $ls->addColumn($row['domain'],$GLOBALS['I18N']->get('perc'),$perc);
  }

}
print $ls->display();

?>