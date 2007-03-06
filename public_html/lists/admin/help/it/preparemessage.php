<p>In questa pagina puoi preparare un messaggio da spedire in una data successiva.
 Puoi specificare tutte le informazioni necessarie per il messaggio, eccetto per l'effettiva
lista(e) che andrà specificata successivamente. Allora, al momento dell'invio (di un messaggio preparato) potete 
identificare la(le) lista(e) ed il messaggio preparato sarà trasmesso.</p>
<p>
 Il vostro messaggio preparato è stazionario, in modo da non sparirà dopo che è 
stato trasmesso, ma può essere selezionato molte altre volte. Fate attenzione con questo, 
perché questo può avere l'effetto di trasmettete lo stesso messaggio ai vostri utenti parecchie volte.
</p>
<p>
Questa funzionalità è progettata specialmente con la funzionalità di “amministratori multipli„ in mente. 
Se un amministratore principale prepara i messaggi, gli amministratori secondari possono inviarli alle loro proprie liste. 
In questo caso potete aggiungere i contenuti supplementari al vostro messaggio: gli attributi dei amministratori.
</p>
<p>Per esempio se avete un attributo <b>Nome</b> per gli amministratori potete aggiungere [LISTOWNER.NAME] come segnaposto, 
che sarà sostituito dal <b>Nome</b> del proprietario della lista, il messaggio sar&agrave; trasmesso a. Ciò è 
incurante di chi trasmette il messaggio. Così se l'amministratore principale trasmette il messaggio ad una lista 
che è di proprietà di qualcun'altro, i segnaposti [LISTOWNER] saranno sostituiti con i valori del proprietario 
della lista,  non i valori dell'amministratore principale .
</P>
<p>Riferimenti:
<br/>
Il formato dei segnaposti  [LISTOWNER] &egrave; <b>[LISTOWNER.ATTRIBUTE]</b><br/>
<p>Attualmente i seguenti attributi di amministratore sono definire:
<table border=1><tr><td><b>Attributi</b></td><td><b>Segnaposto</b></td></tr>
<?php
$req = Sql_query("select name from {$tables["adminattribute"]} order by listorder");
if (!Sql_Affected_Rows())
  print '<tr><td colspan=2>None</td></tr>';

while ($row = Sql_Fetch_Row($req))
  if (strlen($row[0]) < 20)
    printf ('<tr><td>%s</td><td>[LISTOWNER.%s]</td></tr>',$row[0],strtoupper($row[0]));

?>
