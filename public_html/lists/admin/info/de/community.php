<h1>Aktuelle Version</h1>

<p>Bitte stellen Sie sicher, dass Sie die neuste Version von PHPlist verwenden, bevor Sie einen Fehler an die Entwickler melden.</p>

<?php
ini_set("user_agent",NAME. " (PHPlist Version ".VERSION.")");
ini_set("default_socket_timeout",5);
if ($fp = @fopen ("http://www.phplist.com/files/LATESTVERSION","r")) {
  $latestversion = fgets ($fp);
  $latestversion = preg_replace("/[^\.\d]/","",$latestversion);
  $v = VERSION;
  $v = str_replace("-dev","",$v);
  if ($v >= $latestversion) {
    print "<p><font color=green>Sie haben die aktuelle Version von PHPlist installiert.</font></p>";
  } else {
    print "<p><font color=red>Sie haben nicht die aktuelle Version von PHPlist installiert.</font>";
    print "<br/>Ihre Version: <b>".$v."</b>";
    print "<br/>Aktuelle Version: <b>".$latestversion."</b>  ";
    print '<a href="http://www.phplist.com/files/changelog">Neuerungen anzeigen</a>&nbsp;&nbsp;';
    print '<a href="http://www.phplist.com/files/phplist-'.$latestversion.'.tgz">Aktuelle Version herunterladen</a></p>';
  }
} else {
  print "<p>Die aktuelle Version von PHPlist <a href=http://www.phplist.com/files>finden Sie hier</a>.</p>";
}
?>

<hr>

<h1>Die PHPlist Community</h1>

<p>PHPlist wurde anfangs 2000 als kleine Applikation f&uuml;r das
<a href="http://www.nationaltheatre.org.uk" target="_blank">National Theatre</a> in London entwickelt.
Mit der Zeit wuchs es zu einem einigermassen umfassenden Customer Relationship Management System heran,
und die Anzahl der Websites, welche PHPlist einsetzen, wuchs rasch.
Obwohl der Programmcode prim&auml;r von einer einzigen Person gepflegt wird, ist das System inzwischen sehr komplex geworden.
Um die Qualit&auml;t der Applikation zu gew&auml;hrleisten ist die Unterst&uuml;tzung von vielen anderen Personen n&ouml;tig.</p>

<p>Um zu verhindern, dass die Entwickler mit Anfragen &uuml;berschwemmt werden, bitten wir Sie,
sich nicht direkt an <a href="http://tincan.co.uk" target="_blank">Tincan</a> zu wenden.
Benutzen Sie stattdessen eine der nachfolgenen M&ouml;glichkeiten, um Probleme zu l&ouml;sen und Fehler zu melden.
So bleibt den Entwicklern nicht nur mehr Zeit f&uuml;r Ihre eigentliche Arbeit,
sondern es entsteht auch eine Fragensammlung, welche wiederum anderen Benutzern helfen kann, sich mit PHPlist vertraut zu machen.</p>

<p>Der PHPlist Community stehen folgende Plattformen zur Verf&uuml;gung:
<ul>
	<li><a href="http://www.phplist.com/forums/" target="_blank">PHPlist Forum</a></li>
	<li><a href="http://mantis.tincan.co.uk/">PHPlist Bug Tracker</a></li>
</ul>
</p>

<hr>

<h1>Wie Sie PHPlist unterst&uuml;tzen k&ouml;nnen</h1>

<p>Wenn Sie ein <b>regelm&auml;ssiger Benutzer von PHPlist</b> sind und der Meinung sind, 
dass Sie die meisten Schwierigkeiten gemeistert haben, dann k&ouml;nnen Sie uns helfen, indem Sie die Fragen anderer Benutzer beantworten.</p>

<p>Wenn Sie ein <b>neuer Benutzer von PHPlist</b> sind und auf Probleme bei der Installation von PHPlist auf Ihrem Webserver stossen,
dann k&ouml;nnen Sie uns helfen, indem Sie die L&ouml;sung zuerst an den oben beschriebenen Orten suchen,
bevor Sie eine "Es funktioniert nicht"-Meldung absetzen.
H&auml;ufig h&auml;ngen Probleme mit der jeweiligen Umgebung zusammen, in der PHPlist installiert wird.
Dass PHPlist nur einen einzelnen Entwickler hat bringt den Nachteil mit sich,
dass dieser das System nicht auf jeder denkbaren Plattform und mit jeder PHP-Version gr&uuml;ndlich testen kann.</p>

<hr>

<h1>Weitere M&ouml;glichkeiten, um PHPlist zu unterst&uuml;tzen</h1>
<ul>
<li><p>Wenn PHPlist f&uuml;r Sie von Nutzen ist, dann teilen Sie dies auch anderen mit.
Sie haben wahrscheinlich einen ziemlichen Aufwand betrieben, um diese Software zu finden und mit anderen Programmen zu vergleichen.
Lass Sie doch andere von Ihren Erfahrungen profitieren.</p>

<p>Sie k&ouml;nnen dies tun, indem Sie <?php echo PageLink2("vote","f&uuml;r PHPlist stimmen")?>,
oder indem Sie einen Erfahrungsbericht schreiben auf Websites, welche Software bereitstellen.
Sie k&ouml;nnen aber auch einfach Ihren Freunden und Bekannten von PHPlist erz&auml;hlen.</p></li>

<li><p>Sie k&ouml;nnen PHPlist <b>in Ihre Sprache &uuml;bersetzen</b> und diese &Uuml;bersetzung anderen zur Verf&uuml;gung stellen.
Die Internationalisierung von PHPlist soll in zuk&uuml;nftigen Versionen noch verbessert werden.
F&uuml;r den Moment reicht es aber aus, die Datei <b>english.inc</b> zu &uuml;bersetzen.<br />
(Weitere <a href="http://docs.phplist.com/PhplistTranslation">Informationen f&uuml;r &Uuml;bersetzer</a> in englischer Sprache)</p>
</li>

<li>
<p>Sie k&ouml;nnen <b>alle Features von PHPlist testen</b> um herauszufinden, ob sie sich bei Ihnen bew&auml;hren.
Bitte ver&ouml;ffentlichen Sie Ihre Erkenntnisse im <a href="http://www.phplist.com/forums/" target="_blank">PHPlist Forum</a>.</p></li>

<li>
<p>Sie k&ouml;nnen <b>PHPlist bei Ihren kommerziellen Kundenprojekten einsetzen</b> (wenn Sie beispielsweise in einer Web-Agentur arbeiten)
und Ihre Kunden von den Vorz&uuml;gen dieses Systems &uuml;berzeugen.
Falls diese dann &Auml;nderungen oder Erweiterungen w&uuml;nschen, k&ouml;nnen Sie <b>neue Features in Auftrag geben</b> und von Ihren Kunden finanzieren lassen.
Falls Sie wissen m&ouml;chten, wie teuer die Entwicklung bestimmter Features w&auml;re, dann <a href="mailto:phplist@tincan.co.uk?subject=request for quote to change PHPlist">nehmen Sie mit uns Kontakt auf</a> (in englischer Sprache).
Die meisten neuen Features von PHPlist wurden im Auftrag von zahlenden Kunden hinzugef&uuml;gt.
Dies bringt Vorteile f&uuml;r alle Beteiligten: Sie selbst zahlen nur einen bescheidenen Preis, um Ihre Ziele zu erreichen, die Community kommt in den Genuss von neuen Features,
und die Entwickler erhalten eine Entsch&auml;digung f&uuml;r die Arbeit, die sie in PHPlist investiert haben.</p></li>

<li><p>Wenn Sie PHPlist regelm&auml;ssig einsetzen und <b>eine gr&ouml;ssere Zahl von Abonnenten haben</b> (1000 oder mehr),
dann sind wir sehr an Ihrer System-Spezifikation und Ihrer Versandstatistik interessiert.</p>
<p>PHPlist &uuml;bermittelt standardm&auml;ssig Statistiken an <a href="mailto:phplist-stats@tincan.co.uk">phplist-stats@tincan.co.uk</a>,
allerdings enthalten diese keine Systemdetails.
Wenn Sie uns helfen m&ouml;chten, PHPlist zu verbessern, dann w&auml;re es sehr hilfreich, wenn Sie uns Ihre Systemspezifikation mitteilen
k&ouml;nnen und die standardm&auml;ssige &Uuml;bermittlung von Statistiken an obige Adresse belassen.
(Diese Mails werden nicht von Menschen gelesen, sondern automatisch ausgewertet, um die Performance von PHPlist beurteilen zu k&ouml;nnen.)</p></li>
</ul>

<hr>

<h1><a name="lists"></a>Die PHPlist Mailinglist</h1>
<p>Die fr&uuml;here PHPlist Mailinglist wurde eingestellt. An ihre Stelle ist neu das <a href="http://www.phplist.com/forums/">PHPlist Forum</a> getreten.
(Auch das bis vor kurzem noch zug&auml;ngliche <a href="http://lists.cupboard.org/archive/tincan.co.uk" target="_blank">Mailinglist-Archiv</a> ist nun geschlossen.)</p>

<hr>

<h1><a name="bugtrack"></a>Der PHPlist Bug Tracker</h1>
<p>Um den Entwicklern einen Fehler zu melden besuchen Sie den <a href="http://mantis.tincan.co.uk/" target="_blank">PHPlist Bug Tracker</a> und legen ein pers&ouml;nliches Konto an.
Das Passwort wird Ihnen per E-Mail zugeschickt.</p>

<p>Anschliessend k&ouml;nnen Sie in den Bug Tracker (Mantis) einloggen und eine <b>Fehlermeldung</b> absetzen.
Sie k&ouml;nnen dieses System auch benutzen, um <b>neue Features</b> vorzuschlagen.</p>

<p>Bitte beachten Sie, dass alle Anfragen, welche nicht &uuml;ber den Bug Tracker oder das Forum eingehen, ignoriert werden.</p>

<hr>

<h1>Ihre Systemdetails</h1>

<ul>
	<li>PHPlist-Version: <?php echo VERSION?></li>
	<li>PHP-Version: <?php echo phpversion()?></li>
	<li>Webserver: <?php echo getenv("SERVER_SOFTWARE")?></li>
	<li>Website: <a href="http://<?php echo getConfig("website")."$pageroot"?>"><?=getConfig("website")."$pageroot"?></a></li>
	<li>MySQL-Version: <?php echo mysql_get_server_info();?></li>
	<li>PHP-Module:<br/>
		<ul>
		<?php
		$le = get_loaded_extensions();
		foreach($le as $module) {
		    print "<LI>$module\n";
		}
		?>
		</ul>
	</li>
</ul>