<p>Qu&igrave; puoi definire templates (Modelli) che possono essere usati per inviare le emails alle liste. 
Un template &egrave; una pagina HTML che contengono dei <i>SegnaPosto</i> <b>[CONTENT]</b>. Questo andr&agrave; 
posizionato dove il testo per l'email andr&agrave; inserito. </P>
<p>In pi&ugrave; a [CONTENT], puoi aggiungere [FOOTER] e [SIGNATURE] per inserire informazione a pi&egrave; di 
pagina e la firma del messaggio, ma questo sono opzionali.</p>
<p>Le immagini del vostro template saranno inserite nelle emails. Se aggiungi un'immagine al contenuto dei tuoi 
messaggi (quando la invii), &egrave; necessario che che riporti l'URL completo del'immagine, e non che venga 
aggiunta nell'email.</p>
<p><b>User Tracking - Tracciamento Utenti</b></p>
<p>per facilitare il tracciamento degli utenti, aggiungi [USERID] al tuo template che sar&agrave; sostituito 
dall'identificatore dell'utente. Questo funziona solamente quando invii le email in HTML. Devi impostare alcuni 
URL che ricevono l'ID. Alternativamente puoi usare l'incorporato tracciamento utenti di <?php echo NAME?>. 
Per usare questo aggiungi [USERTRACK] al tuo template e un collegamento invisibile verr&agrave; aggiunto alla 
tua email per tenere traccia della visione delle email.</p>
<p><b>Immagini</b></p>
<p>Ogni riferimento alle immagini che non iniziano con "http://" sono (e devono) essere be caricate per essere 
incluse nell'email. E' consigliato di usare solo poche immagini e di crearle veramente piccole. Se caricate il 
vostro template, devi essere capace di aggiungere le vostre immagini. Riferimenti alle immagini che andranno 
incluse devono essere nella stessa cartella , esempio &lt;img&nbsp;src=&quot;image.jpg&quot;&nbsp;......&nbsp;&gt; 
e non  &lt;img&nbsp;src=&quot;/some/directory/location/image.jpg&quot;&nbsp;..........&nbsp;&gt;</p>
