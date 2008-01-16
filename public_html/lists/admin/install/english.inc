<?php

# language dependent text used in installer

$strPhplistDbCreation = 'Now let\'s try to create the db. Fill in the form bellow to do it.';
$strPhplistDbWrongOk = '<p class="allwrong">Default database and/or user/pass are wrong, that\'s ok, let\'s continue.</p>';
$strPhplistDefaultDbOk = '<p class="allok">Hmm, the default settings for database connection worked ok. You dont want to use phplist with these values, is not safe, you better change them and create a user for the database before continue.</p>';
$strDbname = 'Database name : ';
$strDbhost = 'Database host, usually \'localhost\' : ';
$strDbschema = 'Database schema, usually \'phplist\' : ';
$strDbuser = 'Database user, previously created : ';
$strDbpass = 'Database pass of your user : ';
$strDbCreatedOk = 'Database created succesfully. The next step will create a config file for your system here.';
$strDbCreatedKo = 'Could not create database, check the permissions of your user. Or ask your host provider to create one for you and then go to the next step.<p>Fill in the form again, installer will not continue if there isn\'t a databases.</p>';
$myConfigFileOpen = '<b>Your next step is to chmod your /lists/config/config.php file to 644. Right now is 666, and this is not safe, your installation can be hacked if you don\'t do this</b>';
$popAccountOk = '<strong>Your bounces POP3 account has responded ok.</strong>';
$popAccountKo = 'Warning: Your bounces email account details are wrong, I couldn\'t create POP3 connection to ';
$strFinal = '<p>These are the values that we are going to write to your config, is everything ok? If not go back and change it.</p>';
$dbAlreadyCreated = 'Great, the database is working ok, go to the next step.';
$noConfigAndChmod = 'Could not open config file to write, and could not chmod to be writable. The whole thing will fail... Have fun.';
$creatingConfig = 'Creating config file...';
$cantWriteToConfig = 'I couldnt right to the config file, HGRR!! Check the permissions again';
$configCreatedOk = 'Config file created OK.';
$configDoesntExist = 'Config file doesnt exists, something went wrong...';
//$noConfigFileThere = 'Your config file doesnot exists, phplist will try to create one, but it might fail. Please check the permissions on the lists/config directory and start the installation';
//$yesConfigFileNotWritable = 'Your config file exists, but is not writable. Change permissions to writable, ie 666 or 777 and continue with the installation';
//$goodConfigFile = 'Your config file is ready for writing (mmm, maybe we have to check if it\'s empty...).';
$strConfigIsNotAndDirNotWri = 'Warning: Your config file doesnt exists, and phplist cannot write into the lists/config directory. Please either create a config.php file with write permissions, or change permissions to the lists/config directory to be executable (ie 755) before continue.';
$strConfigIsNotWritable = 'Warning: Your config file is not writable, please change the permissions of the lists/config/config.php file to be writable (ie 666, 646, 777, etc).';
$strConfigPerms = 'Notice: Your config permissions are now';
$strChangeChmod = ', phplist could not change it, if you can, please change permissions to 0644 to your lists/config/config.php file. It won\' affect your installation or security, but would be good to keep it 644.';
$strIntroInstaller = '<p>Phplist installer will help you to create your database and config file for the system in several steps. Please keep in mind that if permissions are not well set up phplist won\'t be able to create either your db or your config file. The best thing to do first is to check if you have the right permissions so the installer will work well. Installer will need write permission on the lists/config/config.php file. You can probably do this through ftp, or if you have console access do chmod 646 lists/config/config.php.</p>
<p>You can always make the manual install following the instructions <a href="http://docs.phplist.com/PhplistInstallation">here</a>.</p>
<p>In several steps, as said, installer will check for all the configuration needed to start using the system. Some advances features are not availables for edit for now, if you still want to use them you will have to edit those manually after the creation of the config file.</p>

<p>For more help undertanding phplist install you can take a look at <a href="http://docs.phplist.com/">phplist docs</a>, <a href="http://mantis.phplist.com/">mantis</a> for report a bug or ask for a future feature and the <a href="http://forums.phplist.com/">forums</a> for tips, answers and extra help.</p>
<p>The phplist installer requires cookies to be enabled.</p>';
$strInstallerTitle = 'installer';
$strConfigHasContent = 'Warning: Your config file already has content, which means that it was already initialised. If you want to use the installer please clean the file.';
$strTmpNotWritable = 'Warning: Your actual tmp directory is not writable, please right a complete path in the inputs below.';
$strStillNoConnection = 'Warning: Your database details are still wrong, I couldn\'t connect to it. Please, come back and define the details again. Be sure to have Caps Lock disable in your keyboard, and check your spelling.';
$strTmpIsOk = '<p><strong>Your tmp/ folder works ok right now, change them below only if you are sure.</strong></p>';
$strNoDbCannotContinue = 'Fatal error: Still no connection to the database, installer will not continue. Please check it or create one to continue.';
$strStartAgain = 'Click below to start the installer again, your database details were wrong.';
$strReload = 'Please do what is described above and refresh to start the install.';
$strGoToInitialiseDb = 'Click in the link and go to your installation to initialise the db ';
$addFeature = 'Or maybe you want to add a new feature to the config file? If yes click this';
$strChangedForThis = 'I have changed it for this => ';
$strErraticValue = ' will cause a syntax error. The erratic value is = ';
$strDbExplain = 'Here you will try to create/check your phplist database. You wont be able to continue if the Installer can\'t make a connection to the database.
Try not to use special characters like "\'-/*%", keep it simple using letter, numbers and "_".';
$strExplainInstall1 = 'Please read one and each of the following descriptions.
Keep in mind that using wrong values in the field can cause a parse error in the Config file and will make the whole system stop working';
$strFinalValuesText = 'These values (plus some no-editable-now values) will be written into your config file. You can always change these values later editing the config file.<br/>Values in red are the ones you haven\'t changed, or are the same as the default value.';
$strDbroot = 'Aditionally you can give the root user and pass. The Installer then will create a user for your database, the database if it doesn\'t exist and will give permissions to that user only for that database. Root user and pass will be destroyed after used, and won\'t be written in the config file or any other place.
<p>Something to be aware of is the actual mysql host used. When creating a mysql user, the host for the database should be something like \'localhost\', and phplist will used that as default. If you are not sure about this, create the user yourself and don\'t use the options below.</p>';
$strDbrootuser = 'The root user to create everything at once.';
$strDbrootpass = 'Root password';
$strUserCreatedOk = 'User for the database was created ok, root access was destroy.';
$strLanguageOpt = 'Please choose your language. Be aware that not all languages have been translated.';

$strdeveloper_email_desc = 'Only for Dev versions. Set the developer email here';
$strlanguage_module_desc = 'Language module to use. Defualt english.';
$strdatabase_host_desc = 'Database hostname. Usually localhost.';
$strdatabase_name_desc = 'Database name. Previous created, if not do it now, before continue.';
$strdatabase_user_desc = 'Database user. Previous created, phplist cannot create one. Ask your host provider if you dont have one.';
$strdatabase_password_desc = 'Database password. Previous created with the user.';
$strinstallation_name_desc = 'If you use multiple installations of PHPlist you can set this to something to identify this one. It will be prepended to email report subjects';
$strtable_prefix_desc = 'If you want a prefix to all your tables, specify it here.';
$strusertable_prefix_desc = 'If you want to use a different prefix to user tables, specify it here. Read README.usertables for more information.';
$strpageroot_desc = 'If you change the path to the PHPlist system, make the change here as well. Path should be relative to the root directory of your webserver (document root).';
$stradminpages_desc = 'You cannot actually change the admin, but you can change the /lists';
$strmessage_envelope_desc = 'Message envelope.';
$strbounce_protocol_desc = 'Handling bounces. Check README.bounces for more info. This can be pop or mbox';
$strMANUALLY_PROCESS_BOUNCES_desc = 'Set this to 0, if you set up a cron to download bounces regularly by using the commandline option. If this is 0, users cannot run the page from the web frontend. Read README.commandline to find out how to set it up on the commandline.';
$strbounce_mailbox_host_desc = 'Host of your bounces account. If protocol is pop.';
$strbounce_mailbox_user_desc = 'User name of your bounce account. If protocol is pop.';
$strbounce_mailbox_password_desc = 'Password of your bounces account. If protocol is pop.';
$strbounce_mailbox_port_desc = 'The port is the remote port of the connection to retrieve the emails. The default should be fine but if it doesn\'t work, you can try 110/pop3';
$strbounce_mailbox_desc = 'When the protocol is mbox specify this one. It needs to be a local file in mbox format, accessible to your webserver user';
$strbounce_mailbox_purge_desc = 'Set this to 0 if you want to keep your messages in the mailbox. This is potentially a problem, because bounces will be counted multiple times, so only do this if you are testing things.';
$strbounce_mailbox_purge_unprocessed_desc = 'Set this to 0 if you want to keep unprocessed messages in the mailbox. Unprocessed messages are messages that could not be matched with a user in the system. Messages are still downloaded into PHPlist, so it is safe to delete them from the mailbox and view them in PHPlist.';
$strbounce_unsubscribe_threshold_desc = 'How many bounces in a row need to have occurred for a user to be marked unconfirmed.';
$strrequire_login_desc = 'set this to 1 if you want PHPlist to deal with login for the administrative section of the system you will be able to add administrators who control their own lists default login is admin with password phplist';
$strMAXLIST_desc = 'If you use login, how many lists can be created per administrator.';
$strcommandline_users_desc = 'If you use commandline, you will need to identify the users who are allowed to run the script. See README.commandline for more info.';
$strASKFORPASSWORD_desc = 'As of version 2.4.1, you can have your users define a password for themselves as well this will cause some public pages to ask for an email and a password when the password is set for the user. If you want to activate this functionality, set the following to 1. See README.passwords for more information.';
$strUNSUBSCRIBE_REQUIRES_PASSWORD_desc = 'If you also want to force people who unsubscribe to provide a password before processing their unsubscription, set this to 1. You need to have the above one set to 1 for this to have an effect.';
$strUNSUBSCRIBE_JUMPOFF_desc = 'If a user should immediately be unsubscribed, when using their personal URL, instead of the default way, which will ask them for a reason, set this to 1.';
$strblacklist_gracetime_desc = 'When a user unsubscribes they are sent one final email informing them of their unsubscription. In order for that email to actually go out, a gracetime needs to be set otherwise it will never go out. The default of 5 minutes should be fine, but you can increase it if you experience problems.';
$strCHECK_SESSIONIP_desc = 'To increase security the session of a user is checked for the IP address this needs to be the same for every request. This may not work with network situations where you connect via multiple proxies, so you can switch off the checking by setting this to 0.';
$strENCRYPTPASSWORD_desc = 'If you use passwords, you can store them encrypted or in plain text if you want to encrypt them, set this one to 1 if you use encrypted passwords, users can only request you as an administrator to reset the password. They will not be able to request the password from the system.';
$strcheck_for_host_desc = 'Check for host of email entered for subscription. Do not use it if your server is not 24hr online. Make the 0 a 1, if you want to use it.';
$strTEST_desc = 'With test mode ON no message will be delivered.';
$strVERBOSE_desc = 'If you set verbose to 1, it will show the messages that will be sent. Do not do this if you have a lot of users, because it is likely to crash your browser (it does mine, Mozilla 0.9.2, well 1.6 now, but I would still keep it off :-).';
$strWARN_ABOUT_PHP_SETTINGS_desc = 'Some warnings may show up about your PHP settings. If you want to get rid of them set this value to 0.';
$strMANUALLY_PROCESS_QUEUE_desc = 'If you set up your system to send the message automatically, you can set this value to 0, so "Process Queue" will disappear from the site this will also stop users from loading the page on the web frontend, so you will have to make sure that you run the queue from the commandline, check README.commandline how to do this';
$strWORKAROUND_OUTLOOK_BUG_desc = 'if you want to use \r\n for formatting messages set the 0 to 1. See also http://www.securityfocus.com/archive/1/255910. This is likely to break things for other mailreaders, so you should only use it if all your users have Outlook (not Express)';
$struserhistory_systeminfo_desc = 'User history system info. When logging the history of a user, you can specify which system variables you want to log. These are the ones that are found in the $_SERVER and the $_ENV variables of PHP. check http://www.php.net/manual/en/language.variables.predefined.php. The values are different per system, but these ones are quite common.';
$strUSE_SPAM_BLOCK_desc = 'Add spamblock if you set this to 1, phplist will try to check if the subscribe attempt is a spambot trying to send nonsense. If you think this doesn\'t work, set this to 0, this is currently only implemented on the subscribe pages.';
$strNOTIFY_SPAM_desc = 'notify spam when phplist detects a possible spam attack, it can send you a notification about it you can check for a while to see if the spam check was correct and if so, set this value to 0, if you think the check does it\'s job correctly. it will only send you emails if you have "Does the admin get copies of subscribe, update and unsubscribe messages" in the configuration set to true.';
$strREGISTER_desc = 'Use Register to "register" to PHPlist.com. Once you set TEST to 0, the system will then request the "Powered By" image from www.phplist.com, instead of locally. This will give me a little bit of an indication of how much it is used, which will encourage me to continue developing PHPlist. If you do not like this, set Register to 0.';
$strEMAILTEXTCREDITS_desc = 'CREDITS
# We request you retain some form of credits on the public elements of PHPlist. These are the subscribe pages and the emails.
# This not only gives respect to the large amount of time given freely by the developers but also helps build interest, traffic and use of PHPlist, which is beneficial to future developments.
# By default the webpages and the HTML emails will include an image and the text emails will include a powered by line
#If you want to remove the image from the HTML emails, set this constant to be 1, the HTML emails will then only add a line of text as signature';
$strPAGETEXTCREDITS_desc = 'If you want to also remove the image from your public webpages set the next one to 1, and the pages will only include a line of text';
$strNOSTATSCOLLECTION_desc = 'In order to get some feedback about performance, PHPlist can send statistics to a central email address. To de-activate this set the following value to 1';
$strstats_collection_address_desc = 'this is the email it will be sent to. You can leave the default, or you can set it to send to your self. If you use the default you will give me some feedback about performance which is useful for me for future developments.
# $stats_collection_address = "phplist-stats@tincan.co.uk";';
$strNUMCRITERIAS_desc = 'The number of criterias you want to be able to select when sending a message. Useful is to make it the same as the number of selectable attributes you enter in the system, but that is up to you (selectable = select, radio or checkbox).';
$strALLOW_NON_LIST_SUBSCRIBE_desc = 'If you do not require users to actually sign up to lists, but only want to use the subscribe page as a kind of registration system, you can set this to 1 and users will not receive an error when they do not check a list to subscribe to.';
$strMAILQUEUE_BATCH_SIZE_desc = 'define the amount of emails you want to send per period. If 0, batch processing is disabled and messages are sent out as fast as possible.';
$strMAILQUEUE_BATCH_PERIOD_desc = 'define the length of one batch processing period, in seconds (3600 is an hour).';
$strMAILQUEUE_THROTTLE_desc = 'to avoid overloading the server that sends your email, you can add a little delay between messages that will spread the load of sending you will need to find a good value for your own server, value is in seconds (or you can play with the autothrottle below).';
$strDATE_START_YEAR_desc = 'year ranges. If you use dates, by default the drop down for year will be from three years before until 10 years after this the current value for year. If there is no current value the current year will be used. If you want to use a bigger range you can set the start and end year here be aware that the drop down may become very large. If set to 0 they will use the default behaviour. So I\'m afraid you can\'t start with year 0.';
$strDATE_END_YEAR_desc = 'Date end year. Also be aware not to set the end year to something relatively soon in the future, or it will stop working when you reach that year.';
$strEMPTY_VALUE_PREFIX_desc = 'empty value prefix. This can be used to identify values in select attributes that are not allowed to be selected and cause an error "Please enter your ...". By using a top value that starts with this string, you can make sure that the selects do not have a default value, that may be accidentally selected, eg. "-- choose your country".';
$strUSE_ADMIN_DETAILS_FOR_MESSAGES_desc = 'admin details for messages. If this is enabled phplist will initialise the From in new messages to be the details of the logged in administrator who is sending the message, otherwise it will default to the values set in the configure page that identify the From for system messages.';
$strSEND_ONE_TESTMAIL_desc = 'test emails. If you send a test email, phplist will by default send you two emails, one in HTML format and the other in Text format. If you set this to 1, you can override this behaviour and only have a test email sent to you that matches the user record of the user that the test emails are sent to.';
$strUSE_LIST_EXCLUDE_desc = 'List exclude will add the option to send a message to users who are on a list except when they are on another list. This is currently marked experimental';
$stradmin_auth_module_desc = ' Admin authentication module.
# to validate the login for an administrator, you can define your own authentication module
# this is not finished yet, so dont use it unless you are happy to play around with it
# if you have modules to contribute, send them to phplist2@tincan.co.uk
# the default module is phplist_auth.inc, which you can find in the "auth" subdirectory of the
# admin directory. It will tell you the functions that need to be defined for phplist to
# retrieve its information.
# phplist will look for a file in that directory, or you can enter the full path to the file
# eg
#$admin_auth_module = "phplist_auth.inc";
# or
#$admin_auth_module = "/usr/local/etc/auth.inc"; ';
$strSTACKED_ATTRIBUTE_SELECTION_desc = 'Stacked attribute selection. This is a new method of making a selection of attributes to send your messages to to start with, it doesn\'t seem to work very well in Internet Explorer, but it works fine using Mozilla, Firefox, Opera (haven\'t tried any other browsers), so if you use IE, you may not want to try this.
# stacked attribute selection allows you to continuously add a selection of attributes to your message. This is quite a bit more powerful than the old method, but it can also cause very complex queries to be constructed that may take too long to calculate. If you want to try this, set the value to 1, and give us feedback on how it\'s going.
# if you want to use dates for attribute selections, you need to use this one';
$strREMOTE_URL_REFETCH_TIMEOUT_desc = 'Send a webpage. You can send the contents of a webpage, by adding [URL:http://website/file.html] as the content of a message. This can also be personalised for users by using eg [URL:http://website/file.html?email=[email]] the timeout for refetching a URL can be defined here. When the last time a URL has been fetched exceeds this time, the URL will be refetched. This is in seconds, 3600 is an hour. This only affects sending within the same "process queue". If a new process queue is started the URL will be fetched the first time anyway. Therefore this is only useful is processing your queue takes longer than the time identified here.';
$strCLICKTRACK_desc = 'Click tracking. If you set this to 1, all links in your emails will be converted to links that go via phplist. This will make sure that clicks are tracked. This is experimental and all your findings when using this feature should be reported to mantis for now it\'s off by default until we think it works correctly';
$strCLICKTRACK_SHOWDETAIL_desc = 'Click track, list detail. If you enable this, you will get some extra statistics about unique users who have clicked the links in your messages, and the breakdown between clicks from text or html messages. However, this will slow down the process to view the statistics, so it is recommended to leave it off, but if you\'re very curious, you can enable it';
$strCLICKTRACK_LINKMAP_desc = '
# Click track link map
# if you want the links in your emails to look a bit more professional, you can set the click track
# link map. If you do this, you will need to add a RewriteRule in your Apache config, which maps this
# back to the original lt.php
# it\'s quite useful to keep links short in the emails, particularly text emails
# basically the effect is that /lists/lt.php?id=XYX is changed to /lt/XYZ
# if for example your rewrite rule is:
# RewriteRule ^/lt/(.*)$ /lists/lt.php?id=$1 [PT]
# more info at http://www.google.com/search?q=mod_rewrite (phplist docs to follow at some point)
#define(\'CLICKTRACK_LINKMAP\',\'/lt/\');';
$strALWAYS_ADD_USERTRACK_desc = '
# Add Usertrack
# tracking opens now seems fairly common, however flawed it still is. Set this option to 1
# to always add [USERTRACK] to any message being sent out, even if someone forgot to add it to
# the template, footer or message body';
$strUSE_DOMAIN_THROTTLE_desc = 'Domain Throttling. You can activate domain throttling, by setting USE_DOMAIN_THROTTLE to 1, define the maximum amount of emails you want to allow sending to any domain and the number of seconds for that amount. This will make sure you don\'t send too many emails to one domain which may cause blacklisting. Particularly the big ones are tricky about this. It may cause a dramatic increase in the amount of time to send a message, depending on how many users you have that have the same domain (eg hotmail.com) if too many failures for throttling occur, the send process will automatically add an extra delay to try to improve that. The example sends 1 message every 2 minutes.';
$strDOMAIN_BATCH_SIZE_desc = 'How many messages in a row';
$strDOMAIN_BATCH_PERIOD_desc = 'The value is in seconds';
$strDOMAIN_AUTO_THROTTLE_desc = 'If you have very large numbers of users on the same domains, this may result in the need to run processqueue many times, when you use domain throttling. You can also tell phplist to simply delay a bit between messages to increase the number of messages sent per queue run. If you want to use that set this to 1, otherwise simply run the queue many times. A cron process every 10 or 15 minutes is recommended.';
$strLANGUAGE_SWITCH_desc = 'Admin language. If you want to disable the language switch for the admin interface (and run all in english) set this one to 0';
$strUSE_ADVANCED_BOUNCEHANDLING_desc = 'Advanced bounce processing. With advanced bounce handling you are able to define regular expressions that match bounces and the action that needs to be taken when an expression matches. This will improve getting rid of bad emails in your system, which will be a good thing for making sure you are not being blacklisted by other mail systems. If you use this, you will need to teach your system regularly about patterns in new bounces';
$strHTMLEMAIL_ENCODING_desc = 'You can specify the encoding for HTML and plaintext messages here. This only works if you do not use the phpmailer (see below) .The default should be fine. Valid options are 7bit, quoted-printable and base64';
$strTEXTEMAIL_ENCODING_desc = 'Encoding for text emails';
$strENABLE_RSS_desc = 'PHPlist can send RSS feeds to users. Feeds can be sent daily, weekly or monthly. To use the feature you need XML support in your PHP installation, and you need to set this constant to 1';
$strMANUALLY_PROCESS_RSS_desc = 'If you have set up a cron to download the RSS entries, you can set this to be 0';
$strUSEFCK_desc = 'The FCKeditor is now included in PHPlist, but the use of it is experimental if it\'s not working for you, set this to 0
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa.';
$strFCKIMAGES_DIR_desc = 'If you want to upload images to the FCKeditor, you need to specify the location of the directory where the images go. This needs to be writable by the webserver, and it needs to be in your public document (website) area. The directory is relative to the root of PHPlist as set above
# This is a potential security risk, so read README.security for more information';
$strUPLOADIMAGES_DIR_desc = '
# alternatively, you can set UPLOADIMAGES_DIR, which will take precedence over the FCKIMAGES_DIR
# and it\'s location will need to be in the document root of your website, instead of in the
# phplist root. To use this, comment out the following line, and set it to a directory in your
# website document root, that is writable by your webserver user
#define("UPLOADIMAGES_DIR","uploadimages");';
$strUSETINYMCEMESG_desc = 'TinyMCE Support (http://tinymce.moxiecode.com/) It is suggested to copy the tinymce/jscripts/tiny_mce directory from the standard TinyMCE distribution into the public_html/lists/admin/plugins directory in order to keep the install clean.
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa. Set this to 1 to turn on TinyMCE for writing messages.';
$strUSETINYMCETEMPL_desc = 'Set this to 1 to turn on TinyMCE for editing templates';
$strTINYMCEPATH_desc = 'Set this to path of the TinyMCE script, relative to the admin directory';
$strTINYMCELANG_desc = 'Set this to the language you wish to use for TinyMCE';
$strTINYMCETHEME_desc = 'Set this to the theme you wish to use. Default options are: simple, default and advanced.';
$strTINYMCEOPTS_desc = 'Set this to any additional options you wish. Please be careful with this as you can inadvertantly break TinyMCE. Rever to the TinyMCE documentation for full details.';
$strUSE_MANUAL_TEXT_PART_desc = 'Manual text part, will give you an input box for the text version of the message instead of trying to create it by parsing the HTML version into plain text.';
$strALLOW_ATTACHMENTS_desc = 'Attachments is a new feature and is currently still experimental. Set this to 1 if you want to try it Caution, message may become very large. it is generally more acceptable to send a URL for download to users If you try it, it will be appreciated to give feedback to the users mailinglist, so we can learn whether it is working ok. Using attachments requires PHP 4.1.0 and up.';
$strNUMATTACHMENTS_desc = 'If you use attachments, how many would you want to add per message (max). You can leave this 1, even if you want to attach more files, because you will be able to add them sequentially.';
$strFILESYSTEM_ATTACHMENTS_desc = 'When using attachments you can upload them to the server. If you want to use attachments from the local filesystem (server) set this to 1. Filesystem attachments are attached at real send time of the message, not at the time of creating the message';
$strMIMETYPES_FILE_desc = 'if you add filesystem attachments, you will need to tell PHPlist where your mime.types file is.';
$strDEFAULT_MIMETYPE_desc = 'If a mimetype cannot be determined for a file, specify the default mimetype here:';
$strPLUGIN_ROOTDIR_desc = 'you can create your own pages to slot into PHPlist and do certain things that are more specific to your situation (plugins). If you do this, you can specify the directory where your plugins are. It is useful to keep this outside the PHPlist system, so they are retained after upgrading. There are some example plugins in the plugins directory inside the admin directory. This directory needs to be absolute, or relative to the admin directory. If you want to see the example plugins take off the path and add the word plugins as the value.';
$strattachment_repository_desc = 'The attachment repository is the place where the files are stored (if you use ALLOW_ATTACHMENTS). This needs to be writable to your webserver user. It also needs to be a full path, not a relative one. For secutiry reasons it is best if this directory is not public (ie below your website document root).';
$strPDF_desc = ' if you want to be able to send your messages as PDF attachments, you need to install
# FPDF (http://www.fpdf.org) and set these variables accordingly
# define("FPDF_FONTPATH","/home/pdf/font/");
# require("fpdf.php");
# define("USE_PDF",1);
# $pdf_font = "Times";
# $pdf_fontstyle = "";
# $pdf_fontsize = 14; ';
$strexport_mimetype_desc = 'the mime type for the export files. You can try changing this to application/vnd.ms-excel to make it open automatically in excel';
$strEXPORT_EXCEL_desc = 'if you want to use export format optimized for Excel, set this one to 1';
$strUSE_REPETITION_desc = 'Repetition. This adds the option to repeat the same message in the future. After the message has been sent, this option will cause the system to automatically create a new message with the same content. Be careful with it, because you may send the same message to your users. The embargo of the message will be increased with the repetition interval you choose. Also read the README.repetition for more info';
$strUSE_PREPARE_desc = 'Prepare a message. This system allows you to create messages as a super admin that can then be reviewed and selected by sub admins to send to their own lists. It is old functionality that is quite confusing, and therefore by default it is now off. If you used to use it, you can switch it on here. If you did not use it, or are a new user, it is better to leave it off. It has nothing to do with being able to edit messages.';
$strPHPMAILER_desc = 'If you want to use the PHPMailer class from phpmailer.sourceforge.net, set the following to 1. If you tend to send out html emails, it is recommended to do so.';
$strPHPMAILERHOST_desc = 'To use a SMTP please give your server hostname here, leave it blank to use the standard PHP mail() command.';
$strphpmailer_smtpuser_desc = ' if you want to use smtp authentication when sending the email uncomment the following
# two lines and set the username and password to be the correct ones
# $phpmailer_smtpuser = "smtpusername";
# $phpmailer_smtppassword = "smtppassword";';
$strtmpdir_desc = 'tmpdir. A location where phplist can write some temporary files if necessary. Make sure it is writable by your webserver user, and also check that you have open_basedir set to allow access to this directory. Linux users can leave it as it is. This directory is used for all kinds of things, mostly uploading of files (like in import), creating PDFs and more.';
$strform_action_desc = ' if you are on Windoze, and/or you are not using apache, in effect when you are getting
# "Method not allowed" errors you will want to uncomment this
# ie take off the
#-character in the next line
# using this is not guaranteed to work, sorry. Easier to use Apache instead :-)
# $form_action = "index.php";';
$strdatabase_module_desc = 'Select the database module to use. Anyone wanting to submit other database modules is very welcome!';
$strSessionTableName_desc = ' you can store sessions in the database instead of the default place by assigning
# a tablename to this value. The table will be created and will not use any prefixes
# this only works when using mysql and only for administrator sessions
# $SessionTableName = "phplistsessions";';
$strADOdb_desc = ' there is now support for the use of ADOdb http://php.weblogs.com/ADODB
# this is still experimental, and any findings should be reported in the
# bugtracker
# in order to use it, define the following settings:
#$database_module = "adodb.inc";
#$adodb_inc_file = "/path/to/adodb_inc.php";
#$adodb_driver = "mysql";';
$strerror_level_desc = 'If you want more trouble, make this 63 (very unlikely you will like the result).';
$strAccessDenied = 'Access denied, please check that the user has the right permissions or the password is ok.';
$strWrongHost = 'Wrong host, please check your host name.';
$strServerBusy = 'There\'s an error in your details. Seems to be that the database doesn\'t exists and the user can\'t create it or doesn\'t exists either.';
$strCuoldNotCreateDb = "Could not create database";
$strDoingDbCheck = '<p>Doing database checking for exiting tables.<br/>If database tables exists Installer won\'t create them again, but you can create them once the config system is operating.</p>';
$strEmptyField = 'You have left fields empty, please check your database details.';
?>