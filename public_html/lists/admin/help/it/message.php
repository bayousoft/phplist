Nel campo del messaggio puoi usare delle variabili che saranno sostituite dai rispettivi valori di un utente:

<br/>Le variabili devono essere nella forma <b>[NAME]</b> dove NAME pu&ograve; essere sostituito con il nome di uno dei tuoi attributi.
</p>
<p>Al momento sono presenti i seguenti attributi:
<table border=1><tr><td><b>Attributo</b></td><td><b>Segnaposto</b></td></tr>
<?php
$req = Sql_query("select name from {$tables["attribute"]} order by listorder");
while ($row = Sql_Fetch_Row($req))
  if (strlen($row[0]) < 20)
    printf ('<tr><td>%s</td><td>[%s]</td></tr>',$row[0],strtoupper($row[0]));
print '</table>';
if (phplistPlugin::isEnabled('rssmanager')) {
?>
  <p>Puoi impostare dei template per i messaggi che vengono spediti con elementi RSS. Per fare questo clicca "Programma" e indica
  la frequenza per il messaggio. Il messaggio sar&agrave; poi usato per spedire la lista degli elementi agli utenti sulle liste che
  hanno questa frequenza impostata. Devi usare il segnaposto [RSS] nel tuo messaggio per identificare dove deve andare la lista.</p>
<?php }
?>
<p>Per spedire il contenuto di una pagina web, aggiungi i seguenti codici al messaggio:<br/>
<b>[URL:</b>http://www.esempio.org/nome_file.html<b>]</b></p>
<p>In questo URL puoi includere informazioni basilari sull'utente, ma non informazioni sugli attributi:<br/>
<b>[URL:</b>http://www.esempio.org/userprofile.php?email=<b>[</b>email<b>]]</b><br/>
</p>
