åեǡ"ѿ"ѤǤޤѿϡ桼ˤȤäŬڤִͤޤ
<br />ѿ<b>[NAME]</b>ϤΤ褦ʷ|ǻѤɬפꡢNAMEϡ°-ΰĤ̾pִޤ 
<br />㤨С⤷°-"First Name"äƤʤ顢åǡ??Ԥ"First Name"Τͤ??ɬפ֤[FIRST NAME]ˤȵҤƤ$
</p><p>߼°-dǤޤ
<table border=1><tr><td><b>°-</b></td><td><b>ץ졼ۥ!</b></td></tr>
<?php
$req = Sql_query("select name from {$tables["attribute"]} order by listorder");
while ($row = Sql_Fetch_Row($req))
  if (strlen($row[0]) < 20)
    printf ('<tr><td>%s</td><td>[%s]</td></tr>',$row[0],strtoupper($row[0]));
print '</table>';
if (phplistPlugin::isEnabled('rssmanager')) {
?>
  <p>RSSƥۿ뤿˥åΥƥץ졼Ȥꤹ뤳ȤǤޤRSSƥۿ뤿ˤϡ"塼"륿֤򥯥åơå٤ꤷƤ$ åϡΤȤ٤ꤷƤꥹȾΥ桼ˡƥΥꥹȤ??뤿˻Ѥޤ꤯ˤϡå˥ץ졼ۥ![RSS]ޤɬפޤ</p>
<?php }
?>

<p>֥ڡΥƥĤ??뤿ˤϡåΥƥĤ˲ɲäƤ$<br/>
<b>[URL:</b>http://www.example.org/path/to/file.html<b>]</b></p>
<p>URLΤ˴Ūʥ桼ޤ뤳ȤǤޤ°-ϴޤ뤳ȤϤǤޤ?</br>
<b>[URL:</b>http://www.example.org/userprofile.php?email=<b>[</b>email<b>]]</b><br/>
</p>