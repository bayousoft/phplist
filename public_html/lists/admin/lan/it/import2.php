<?php

$lan = array(

'The temporary directory for uploading (%s) is not writable, so import will fail' => 'La cartella temporanea di caricamento (%s) non &egrave; scrivibile, l\'importazione &egrave; fallita',
'Invalid Email' => 'Email non valida',
'Import cleared' => 'Importazione approvata',
'Continue' => 'Continua',
'Reset Import session' => 'Azzera la sessione di importazione',
'File is either too large or does not exist.' => 'L\'archivoi &grave; troppo grande o non esiste.',
'No file was specified. Maybe the file is too big? ' => 'Nessun archivio specificato. Pu&ograve; darsi che l\'archivio &egrave; troppo grande? ',
'File too big, please split it up into smaller ones' => 'Archivio troppo grande, Dividilo in pi&ugrave; archivi pi&ugrave; piccoli',
'Use of wrong characters in filename: ' => 'Utilizzati caratteri errati nel nome dell\'archivio: ',
'Please choose whether to sign up immediately or to send a notification' => 'Si prega scegliere la conferma immediata o l\'invio di una notifica',
'Cannot read %s. file is not readable !' => 'Non si riesce a leggere %s. archivio non leggibile !',
'Something went wrong while uploading the file. Empty file received. Maybe the file is too big, or you have no permissions to read it.' => 'Qualcosa &egrave; andato male durante il caricamento dell\'archivio. Ricevuto archivio vuoto. Pu&ograve; essere che l\'archivio sia troppo grande, o non hai il permesso di lettura.',
'Reading emails from file ... ' => 'Lettura di emails dall\'archivio ... ',
'Error was around here &quot;%s&quot;' => 'Errori trovato qu&igrave; &quot;%s&quot;',
'Illegal character was %s' => 'Caratteri non validi in %s',
'A character has been found in the import which is not the delimiter indicated, but is likely to be confused for one. Please clean up your import file and try again' => 'Un carattere &egrave; stato trovato nell\'importazione che non &egrave; l\'indicatore di delimitazione, ma pu&ograve; essere confuso con uno di questi. Si prega ripulire l\'archivio di importazione e riprovare',
'ok, %d lines' => 'ok, %d linee',
'Cannot find column with email, please make sure the column is called &quot;email&quot; and not eg e-mail' => 'non trovata una colonna con email, si prega controllare che la colonna sia intitolata &quot;email&quot; e non per esempio e-mail',
'Create new one' => 'Crea un nuovo',
'Skip Column' => 'Salta la colonna',
'Import Attributes' => 'Attributi di Import',
'Continue' => 'Continua',
'Please identify the target of the following unknown columns' => 'Si prega identificare l\'obbiettivo per queste colonne sconosciute',
'Summary' => 'Sommario',
'maps to' => 'Pianifica',
'Create new Attribute' => 'Crea nuovo attributo',
'maps to' => 'Pianifica',
'Skip Column' => 'Salta la colonna',
'maps to' => 'Pianifica',
'%d lines will be imported' => '%d linee sono state importate',
'Confirm Import' => 'Conferma importazione',
'Test Output' => 'Verifica Risultato',
'Record has no email' => 'I Record non hanno email',
'Invalid Email' => 'Email non valide',
'clear value' => 'pulisce valori',
'New Attribute' => 'Nuovo Attributo',
'Skip value' => 'Salta Valore',
'duplicate' => 'duplicato',
'Duplicate Email' => 'Email Duplicate',
' user imported as ' => ' Utente importato come ',
'duplicate' => 'duplicato',
'duplicate' => 'duplicato',
'Duplicate Email' => 'Email Duplicato',
'All the emails already exist in the database and are member of the lists' => 'Tutte le emails esistono gi&agrave; nel database e sono membri delle liste',
'%s emails succesfully imported to the database and added to %d lists.' => '%s emails importate con successo nel database e aggiunte a %d liste.',
'%d emails subscribed to the lists' => '%d emails sottoscritte alle liste',
'%s emails already existed in the database' => '%s emails esistono gi&agrave; nel database',
'%d Invalid Emails found.' => '%d Trovato Emails non valide.',
'These records were added, but the email has been made up from ' => 'Questi records sono stati aggiunti, ma le email sono state preparate da ',
'These records were deleted. Check your source and reimport the data. Duplicates will be identified.' => 'Questi records erano cancellate. Controllate il vostro sorgente e reimporta i dati. I duplicati sono identificati.',
'User data was updated for %d users' => 'Dati utenti sono stati aggiornati per %d utenti',
'%d users were matched by foreign key, %d by email' => '%d utenti erano combinati tramite chiave straniera, %d tramite email',
'phplist Import Results' => 'Risultati import phplist',
'Test output<br/>If the output looks ok, click %s to submit for real' => 'Verifica risultato<br/>Se i risultati sono validi, click %s su sottometti per renderli reali',
'Confirm Import' => 'Conferma Importazione',
'Import some more emails' => 'Importa altre emails',
'Adding users to list' => 'Aggiungi utenti alla lista',
'Select the lists to add the emails to' => 'Seleziona le liste a cui aggiungere le emails',
'No lists available' => 'Nessuna lista disponibile',
'Add a list' => 'Aggiungi una lista',
'Select the groups to add the users to' => 'Seleziona il gruppo a cui aggiungere utenti',
'automatically added' => 'automaticamente aggiunto',
 'importintro' => '<p>Il file che hai caricato deve avere gli attributi dei records    nella prima linea.
    Controllate che la colonna email sia chiamata col nome "email" e non con nomi simili a "e-mail" o
    "Email Address".
    Maiuscolo non &egrave; importante.
    </p>
    Se hai una colonna chiamata "Foreign Key", Questa sar&agrave; usata per sincronizzarla tra un 
    database esterno e il database PHPlist. La chiave "foreignkey" prender&agrave; la precedenza quando 
    verificher&agrave; un utente esistente. Ciò rallenter&agrave; il processo dell\'importazione. Se usate 
    questo, &egrave; permesso avere records senza email, ma tuttavia “email non valido„ sar&agrave; generato. 
    Potete allora fare una ricerca “email non valido„ per trovare questi records. La massima grandezza per "Foreign Key" &egrave; 100.
    <br/><br/>
    <b>Attenzione</b>: L\'archivio deve essere di tipo testo semplice. Non caricare archivi binari come un Documento Word.
    <br/>',
'uploadlimits' => 'I seguenti limiti sono definiti dal vostro server:<br/>
Massima grandezza dei dati totali inviati al server: <b>%s</b><br/>
Massima grandezza di ogni archivio individuale: <b>%s</b>
<br/>PHPlist non elabora archivi pi&ugrave; grandi di 1Mb',
'testoutput_blurb' => 'Se scegli "Verifica risultato", otterai una lista a video delle emails analizzate, e il database non sar&agrave; riempito con nessuna informazione. Questo &egrave utile per trovare se il formato del vostro archivio &egrave; corretto. Verranno visualizzati solo i primi 50 records.',
'warnings_blurb' => 'Se scegli "Visualizza avvertenze", verrai avvisato se records non validi. Le avvertenze verranno solo visualizzate se scelto anche "Verifica risultato". Verranno ignorate quando si esegue l\'importazione. ',
'omitinvalid_blurb' => 'Se scegli "Ometti non validi", i records non validi non verranno aggiunti. I records non validi sono i records senza email. Quansiasi altra attributo Any other attributes verr&agrave; aggiunto automaticamente, esempio se la nazione non &egrave; stata trovata nel record, verr&agrave; aggiunta dalla lista delle nazioni.',
'assigninvalid_blurb' => 'Assegna non validi viene usato per creare una email per gli utenti con un indirizzo email non valido.
Puoi usare i valori fra [ e ] percomporre l\'email. Per esempio se il vostro archivio import contiene una colonna "First Name" e una colonna chiamata "Last Name", puoi usare 
"[first name] [last name]" per costruire un nuovo valore per l\'email per questo utente il suo nome e cognome.
Il valore [numero] puo essere usato il numero di sequenza per l\'importazione.',
'overwriteexisting_blurb' => 'Se scegli "Sovrascrivi Esistenti", le informazioni relative all\'utente nel database saranno sostituire dalle informazioni importate. Gli utenti sono riferiti tramite email o chiave straniera (foreign key).',
'retainold_blurb' => 'Se scegli "conserva vecchia email utente", vi &egrave; un conflitto tra le due emails essendo conservata la vecchia e aggiunto "duplicato" la nuova. Se non lo selezionate, il vecchio verr&agrave; "duplicate" e il nuovo prender_&agrave; la precedenza.',
'sendnotification_blurb' => 'Se scegli "invia email di notifica" agli utenti che state aggiungendo sara inviata la richiesta per la conferma della sottoscrizione a cui dovranno rispondere. Ci&ograve; &egrave; raccomandato, perch&eacuta; identificher&agrave; le email non valide.',
'phplist Import  Results' => 'phplist Import  Risultati',
'File containing emails' => 'Archivio contenente emails',
'Field Delimiter' => 'Delimitatore di Campo',
'(default is TAB)' => '(default &egrave; TAB)',
'Record Delimiter' => 'Delimitatore di Record',
'(default is line break)' => '(default &egrave; interruzione di linea)',
'Test output' => 'Verifica risultato',
'Show Warnings' => 'Visualizza Avvertenze',
'Omit Invalid' => 'Omette Non Validi',
'Assign Invalid' => 'Assegna Non Validi',
'Overwrite Existing' => 'Sovrascrive Esistenti',
'Retain Old User Email' => 'Conserva Email Utente Vecchia',
'Send&nbsp;Notification&nbsp;email' => 'Invia&nbsp;&nbsp;email&nbsp;di&nbsp;notifica',
'Make confirmed immediately' => 'Conferma immediatamente',
'Import' => 'Importa',


);
?>