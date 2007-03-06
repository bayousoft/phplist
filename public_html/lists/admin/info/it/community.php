
<h1>La comunit&agrave; PHPlist</h1>
<p><b>Ultima Versione</b><br/>
Verifica di usare l'ultima versione prima di sottomettete il report di un bug.<br/>
<?php
ini_set("user_agent",NAME. " (PHPlist versione ".VERSION.")");
ini_set("default_socket_timeout",5);
if ($fp = @fopen ("http://www.phplist.com/files/LATESTVERSION","r")) {
  $latestversion = fgets ($fp);
  $thisversion = VERSION;
  $thisversion = str_replace("-dev","",$thisversion);
  if (versionCompare($thisversion,$latestversion)) {
    print "<font color=green size=2>Congratulazione, stai usando l'ultima versione</font>";
  } else {
    print "<font color=green size=2>Non stai usando l'ultima versione</font>";
    print "<br/>Tua versione: <b>".$thisversion."</b>";
    print "<br/>Ultima versione: <b>".$latestversion."</b>  ";
    print '<a href="http://www.phplist.com/files/changelog">Visualizza cosa &egrave; cambiato</a>&nbsp;&nbsp;';
    print '<a href="http://www.phplist.com/files/phplist-'.$latestversion.'.tgz">Download</a>';
  }
} else {
  print "<br/>Controlla se ultima versione: <a href=http://www.phplist.com/files>Qui</a>";
}
?>
<p>PHPlist &egrave; iniziato prima dell'anno 2000 come una piccola applicazione per il 
<a href="http://www.nationaltheatre.org.uk" target="_blank">The Royal National Theatre di Londra</a>. Col tempo si &egrave;
sviluppato come un sistema abbastanza completo per l'invio di newsletter e il 
numero di siti che lo utilizzano &egrave; cresciuto rapidamente. Sebbene il codice base sia primariamente
mantenuto da una persona, sta divenendo molto complesso, e per assicurare la
qualit&agrave; richieder&agrave; sempre pi&ugrave; la partecipazione di pi&ugrave; persone.</p>
<p>Per evitare di intasare la caselle postali degli sviluppatori, siete gentilmente
pregati di non spedire domande direttamente a <a href="http://tincan.co.uk" target="_blank">Tincan</a>, ma
di utilizzare gli altri metodi di comunicazione disponibile. Non solo questo permette di lasciare
pi&ugrave; tempo allo sviluppo, ma permette di creare uno storico delle domande, che possono essere
usate da un nuovo utente per essere informato del sistema</a>.</p>
<p>Per facilitare la comunit&agrave; PHPlist sono disponibili pi&ugrave; opzioni:
<ul>
<li>La <a href="http://docs.phplist.com" target="_blank">Documentazione Wiki</a>. Il sito della documentazione &egrave; il riferimento principale, e nessuna domanda dovrebbe essere inviata ad esso.<br/><br/></li>
<li>I <a href="http://www.phplist.com/forums/" target="_blank">Forums</a>. I forums sono i posti dove potete inviare le vostre domande ed affinchè altri vi rispondano.<br/><br/></li>
<li><a href="#bugtrack">Mantis</a>. Mantis &egrave; un indicatore di traccia. Ci&ograve; pu&ograve; essere usata per inviare le richieste delle nuove caratteristica e per segnalare i Bugs. Non pu&ograve; essere usata per le domande dell'helpdesk.<br/><br/></li>
</ul>
</p><hr>
<h1>Cosa potete fare per aiutarci</h1>
<p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="donate@phplist.com">
<input type="hidden" name="item_name" value="phplist version <?php echo VERSION?> for <?php echo $_SERVER['HTTP_HOST']?>">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="tax" value="0">
<input type="hidden" name="bn" value="PP-DonationsBF">
<input type="image" src="images/paypal.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
</form></p>
<p>Se sei un <b>utente regolare di PHPlist</b> e pensi di essere riuscito a risolvere la maggior parte delle questioni
puoi aiutare tramite <a href="http://www.phplist.com/forums/" target="_blank">rispondendo alle domande di altri utenti</a>. o scrivendo pagine nel <a href="#docscontrib">sito della documentazione</a>.</p>
<p>Se sei <b>nuovo in PHPlist</b> e hai un problema su come configurarlo per eseguirlo sul 
tuo sito, prima di inviare un messaggio "non funziona", puo esserti di aiuto provare a ricercare una 
soluzione nel primo sito riportato in alto. Spesso i problemi che potete avere sono relativi all'ambiente 
su cui la vostra installazione di PHPlist sta funzionando. Avere solamente uno sviluppatore per PHPlist presenta 
lo svantaggio che il sistema non può essere esaminato completamente su ogni piattaforma e su ogni versione di PHP.</p>
<h1>Altre cose che potete fare per aiutarci</h1>
<ul>
<li><p>Se pensi che PHPlist sia di grande aiuto per te, perch&egrave; non ci aiuti mettendo a conoscenza altre persone
della sua esistenza. Probabilmente avete fatto un grosso sforzo per trovarlo e nel decidere di utilizzarlo dopo averlo 
confrontato con altre applicazioni simili, potete quindi essere di beneficio ad altre persone con la vostra esperienza.</p>

<p>Per fare questo, puoi <?php echo PageLink2("vote","Votare")?> per PHPlist, o scrivere una recensione nei siti
che elencano applicazioni. Potete inoltre parlarne alle persone che conoscete.
</li>
<li><p>Potete <b>Tradurre</b> PHPlist nella vostra lingua e inviarci la traduzione.
Per aiutarci controlla le <a href="http://docs.phplist.com/PhplistTranslation">Pagine di traduzione</a> nel Wiki.
</p>
</li>
<li>
<p>Potete <b>Provare</b> tutte le varie caratteristiche di PHPlist e controllare che lavorino bene per voi.
Inviate i vostri risultati nei <a href="http://www.phplist.com/forums/" target="_blank">Forums</a>.</p></li>
<li>
<p>Potete usare PHPlist a pagamento per i vostri clienti (Se siete una agenzia-web per esempio) e convincerli 
che questo &egrave un grande strumento per ottenere i loro obbiettivi. E se desiderano alcuni cambiamenti potete 
<b>commissionare nuove caratteristiche</b> che saranno pagate dai vostri clienti. Se desiderate sapere quanto 
costerebbe aggiungere le caratteristiche a PHPlist, <a href="mailto:phplist2@tincan.co.uk?subject=request for quote to change PHPlist">Chiedetelo con un click</a>.
Molte delle nuove caratteristiche di PHPlist sono state aggiunte su richiesta di clienti paganti. Questo dar&agrave; il beneficio
di pagare un piccolo prezzo per realizzare i vostri obbiettivi, beneficer&agrave; la comunit&agrave; per ottenere nuove
caratteristiche, e beneficer&agrave; gli sviluppatori nell'essere pagati per il lavoro su PHPlist :-)</p></li>

<li><p>Se usate PHPlist regolarmente e avete un <b>numero discretamente grande di sottoscritti</b> (1000+), noi siamo
interessati alle specifiche del vostro sistema, e riceviamo statistiche. Per default PHPlist trasmetter&agrave;
statistiche a <a href="mailto:phplist-stats@tincan.co.uk">phplist-stats@tincan.co.uk</a>, ma non viene spedito
nessun dettaglio del sistema. Se desiderate aiutarci facendo si che lavori meglio, sarebbe ottimo se 
ci comunicaste le specifiche del vostro sistema, e lasciare di default l'invio della statistica al suddetto indirizzo.
L'indirizzo è giusto una goccia, non &egrave; letto da persone, ma viene analizzato per calcolare quali sono le performance
di PHPlist.</p></li>
</ul>

</p>
<p><b><a name="bugtrack"></a>Mantis</b><br/>
<a href="http://mantis.tincan.co.uk/" target="_blank">Mantis</a> &egrave; il sito dove potete trovare lo storico dei problemi di phplist. I vostri problemi possono essere qualsiasi cosa relativa a phplist, commenti e sugerimenti su come migliorarlo o il resoconto di un bug. Se inserite il resoconto di un bug, assicuratevi di includere ogni informazione possibile per facilitare gli sviluppatori nel risolvere il problema.</p>
<p>Le esigenze minime per riportare un bug sono i dettagli del vostro sistema:</p>

<?php if (!stristr($_SERVER['HTTP_USER_AGENT'],'firefox')) { ?>
<p>Se avete esperienza sul problemi, provate a usare Firefox per vedere se cos&igrave; il problema &egrave risolto.
<a href="http://www.spreadfirefox.com/?q=affiliates&amp;id=131358&amp;t=81"><img border="0" alt="Get Firefox!" title="Get Firefox!" src="images/getff.gif"/></a>
<?php } ?>

</p>
<p>I dettagli del vostro sistema sono:</p>

<ul>
<li>PHPlist version: <?php echo VERSION?></li>
<li>PHP version: <?php echo phpversion()?></li>
<li>Browser: <?php echo $_SERVER['HTTP_USER_AGENT']?></li>
<li>Webserver: <?php echo $_SERVER['SERVER_SOFTWARE']?></li>
<li>Website: <a href="http://<?php echo getConfig("website")."$pageroot"?>"><?=getConfig("website")."$pageroot"?></a></li>
<li>Mysql Info: <?php echo mysql_get_server_info();?></li>
<li>PHP Modules:<br/><ul>
<?php
$le = get_loaded_extensions();
foreach($le as $module) {
    print "<LI>$module\n";
}
?>
</ul></li>
</ul>
<p>Fate attenzione, le emails o i forum che non utilizzano questo sistema, saranno ignorati.</p>

<p><b><a name="docscontrib"></a>Contribuite alla documentazione</b><br/>
Se volete aiutare scrivendo la documentazione, iscrivetevi a <a href="http://tincan.co.uk/?lid=878">Mailinglist Sviluppatori</a>. In questo momento documentatori e sviluppatori condividono la mailinglist, perch&egrave; i loro interessi coincidono ed &egrave; utile ripartire le informazioni. <br/>
Prima di fare qualsiasi cosa, discutete il problema nella mailinglist e quando l'idea &egrave; stabilita potete realizzarla.

<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
