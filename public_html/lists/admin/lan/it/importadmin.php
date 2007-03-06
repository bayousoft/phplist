<?php
$lan = array(
  'File is either to large or does not exist.' => 'L\'archivio &egrave; troppo largo o non esiste.',
  'No file was specified.' => 'Nessun archivio specificato.',
  'Some characters that are not valid have been found. These might be delimiters. Please check the file and select the right delimiter. Character found:' => 'Sono stati trovati alcuni caratteri non validi. questi possono essere delimitati. Controllo l\'archivio e seleziona i seguenti delimitatori. Caratteri trovati:',
  'Name cannot be empty' => 'Il nome non pu&ograve; essere vuoto',
  'Name is not unique enough' => 'Il nome non &egrave sufficientemente univoco',
  'Cannot find the email in the header' => 'Non trovata email nell\'intestazione',
  'Cannot find the password in the header' => 'Non trovata la password nell\'intestazione',
  'Cannot find the loginname in the header' => 'non trovato il nome login nell\'intestazione',
  'Record has no email' => 'Questi Record non hanno email',
  'Invalid Email' => 'Email non valida',
  'Record has more values than header indicated, this may cause trouble' => 'I Record hanno pi&ugrave; valori di quelli indicati sulla testata, questo causa confusione',
  'password' => 'password',
  'loginname' => 'nome login',
  'Empty loginname, using email:' => 'Nome login vuoto, usa email:',
  'Value' => 'Valore',
  'added to attribute' => 'aggiunto agli attributi',
  'new email was' => 'la nuova email era',
  'new emails were' => 'le nuove emails sono',
  'email was' => 'email era',
  'emails were' => 'emails sono',
  'All the emails already exist in the database' => 'Tutte le emails esistono gi&agrave; nel database',
  'succesfully imported to the database and added to the system.' => 'importazione completata con successo nel database e aggiunta al sistema.',
  'Import some more emails' => 'Importa altre emails',
  'No default permissions have been defined, please create default permissions first, by creating one dummy admin and assigning the default permissions to this admin' => 'Nessun permesso di default &egrave; stato definito, creare prima i permessi di default, creando un amministratore di comodo e asegnando i permessi di default a questo amministratore',
  
  # do not translate email, loginname and password
  'importadmininfo' => '
  L\'archivio da caricare deve contenere gli amministratori
verr&agrave; aggiunto al sistema. Le colonne dovranno avere le seguenti intestazioni: <b>email</b>, <b>loginname</b>, <b>password</b>. Ogni altra colonna verr&agrave; aggiunta come attributo degli amministratori.
 <b>Attenzione</b>: l\'archivio deve essere di testo semplice. Non caricare archivi binari come documenti Word.
  ',
  'File containing emails' => 'Arcihvio contenente emails',
  'Field Delimiter' => 'Delimitatore di campo',
  'Record Delimiter' => 'Delimitatore di record',
  'importadmintestinfo' => 'Se spuntato "Verifica formato archivio", otterrete a video la lista delle email controllate, e le informazioni non saranno scritte sul database. Questo &egrave; utile per verificare se il formato dell\'archivio &egrave; corretto. Verranno visualizzati solo i primi 50 records.',
  # this should be the same as the term between quotes in the previous one
  'Test output' => 'Verifica formato archivio',
  'Check this box to create a list for each administrator, named after their loginname' => 'Spunta questo box per create una lista per ogni amministratore, nominata dopo il loro loginname',
  'Do Import' => 'Da Importare',
  'default is TAB' => 'default &egrave; TAB',
  'default is line break' => 'default &egrave; line break (a capo)',
  'testoutputinfo' => 'Verifica formato archivio:<br>Deve esserci solo UNA email per linea.<br>Se &egrave; corretto, vai a <a href="javascript:history.go(-1)">Back</a> per risottomettere per reale<br><br>',
  'List for' => 'Lista per',
'login' => 'login',
  
  
);
?>
