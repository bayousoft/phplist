<h3>Formato del messaggio</h3>
Se usi "auto detect" il messaggio verr&acute; classificato come HTML non appena un tag HTML (&lt; ... &gt;) viene trovato nel testo.
</p><p><b>Nel caso sei indeciso lascia "Auto detect"</b></p><p>
Se non sei sicuro che "auto detect" funzioni e il messaggio che incolli &egrave; formattato in HTML scegli "HTML".
I riferimenti ad oggeti esterni (es. immagini) devono avere l'URL completa, ovvero che inizi con http:// (a differenza delle immagini usate per i template).
Per tutto il resto sei responsabile per la formattazione del testo.
<p>Se vuoi forzare un messaggio in formato testo, seleziona "Testo".
</p><p>
Questa informazione &egrave; usata per creare una versione testuale del testo formattato in HTML o viceversa (testo HTML di un messaggio testuale). 
La formattazione funziona in questo modo:<br/>
Testo originale in HTML -&gt; testo<br/>
<ul>
<li><b>Grassetti</b> il testo sar&aacute; all'interno di due <b>caratteri *</b>, <b>corsivo</b> tra due <b>caratteri /</b></li>
<li>Link presenti nel testo saranno rimpiazzati con il testo, seguito dall'URL tra due virgolette</li>
<li>Grandi blocchi di testo saranno mandati a capo ogni 70 caratteri</li>
</ul>
Testo originale in formato testuale -&gt; HTML<br/>
<ul>
<li>Due a capo saranno rimpiazzati da un &lt;p&gt; (paragrafo)</li>
<li>Un singolo a capo sar&aacute; rimpiazzato da un &lt;br /&gt; (a capo)</li>
<li>Indirizzi email saranno saranno resi cliccabili</li>
<li>URL saranno resi cliccabili. URL sono riconosciuti se sono in una di queste forme:<br/>
<ul><li>http://some.website.url/some/path/somefile.html
<li>www.websiteurl.com
</ul>
I link creati avranno il campo classe (per modificarne l'aspetto tramite fogli di stile) "url" e target "_blank".
</ul>
<b>Attenzione</b>: indicando che il tuo messaggio &egrave; testuale, ma incollando del testo in HTML, questo verr&aacute; inviato come tale (con tutti i tag visibili e non interpretati) anche agli utenti che hanno indicato di voler ricevere messaggi in formato testo.
