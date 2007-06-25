<?php

/*

Arrays to create the database 

The most important key is the type. In the type will be defined if you can modify the variable, and how it will be writen in the config file.

Some variables like 'bounce_mailbox_purge_unprocessed' and 'error_level' need the value without the "" (quotes) in the config file, so they need to be diff.

types:

scalar: a simple variable
contant: a contant, ie define("WHATEVER", "PHPLIST-INSTALLER");
scalar_int: a diff variable, without "" quotes in the value, ie $error_level = error_reporting(63);
commented: These ones you cannot see and will be hide in the config by default. They are advanced features, so if you want it just comment it out after the creation

Then we have the variables we dont want people to see, to we set up them with a hidden_type, ie hidden_constant, hidden_scalar, etc

*/

$generalVars = array(
/*  "developer_email"=> array(
          "name"=> "developer_email",
          "type"=> "scalar",
          "description"=> "Only for Dev versions. Set the developer email here",
          "values"=> "developer@localhost"),*/
	"database_host"=> array(
		"name"=> "database_host",
		"type"=> "scalar",
		"description"=> "Database hostname. Usually localhost.",
		"values"=> "localhost"),
	"database_name"=> array(
		"name"=> "database_name",
		"type"=> "scalar",
		"description"=> "Database name. Previously created, if not do it now, before continue.",
		"values"=> "phplistdb"),
	"database_user"=> array(
		"name"=> "database_user",
		"type"=> "scalar",
		"description"=> "Database user. Previously created, phplist cannot create one. Ask your host provider if you dont have one.",
		"values"=> "admin"),
	"database_password"=> array(
		"name"=> "database_password",
		"type"=> "scalar",
		"description"=> "Database password. Previously created with the user.",
		"values"=> "phplist"),
	"installation_name"=> array(
		"name"=> "installation_name",
		"type"=> "scalar",
		"description"=> "If you use multiple installations of phplist you can set this to something to identify this one. It will be prepended to email report subjects",
		"values"=> "phplist"),
  "language_module"=> array(
    "name"=> "language_module",
    "type"=> "scalar",
    "description"=> "Language module to use.",
    "values"=> "english.inc"),
	"table_prefix"=> array(
		"name"=> "table_prefix",
		"type"=> "scalar",
		"description"=> "If you want a prefix to all your tables, specify it here.",
		"values"=> "phplist_"),
	"usertable_prefix"=> array(
		"name"=> "usertable_prefix",
		"type"=> "scalar",
		"description"=> "If you want to use a different prefix to user tables, specify it here. Read README.usertables for more information.",
		"values"=> "phplist_user_"),
	"pageroot"=> array(
		"name"=> "pageroot",
		"type"=> "scalar",
		"description"=> "If you change the path to the phplist system, make the change here as well. Path should be relative to the root directory of your webserver (document root).",
		"values"=> "/lists"),
	"adminpages"=> array(
		"name"=> "adminpages",
		"type"=> "scalar",
		"description"=> "You cannot actually change the admin, but you can change the /lists",
		"values"=> "/lists/admin"));

$bouncesVars = array(
	"message_envelope"=> array(
		"name"=> "message_envelope",
		"type"=> "scalar",
		"description"=> "Message envelope.",
		"values"=> "listbounces@phplist.com"),
	"bounce_protocol"=> array(
		"name"=> "bounce_protocol",
		"type"=> "hidden_scalar",
		"description"=> "Handling bounces. Check README.bounces for more info. This can be pop or mbox",
		"values"=> "pop"),
	"MANUALLY_PROCESS_BOUNCES"=> array(
		"name"=> "MANUALLY_PROCESS_BOUNCES",
		"type"=> "constant",
		"description"=> "Set this to 0, if you set up a cron to download bounces regularly by using the commandline option. If this is 0, users cannot run the page from the web frontend. Read README.commandline to find out how to set it up on the commandline.",
		"values"=> 1),
	"bounce_mailbox_host"=> array(
		"name"=> "bounce_mailbox_host",
		"type"=> "scalar",
		"description"=> "Host of your bounces account. If protocol is pop.",
		"values"=> "localhost"),
	"bounce_mailbox_user"=> array(
		"name"=> "bounce_mailbox_user",
		"type"=> "scalar",
		"description"=> "User name of your bounce account. If protocol is pop.",
		"values"=> "username"),
	"bounce_mailbox_password"=> array(
		"name"=> "bounce_mailbox_password",
		"type"=> "scalar",
		"description"=> "Password of your bounces account. If protocol is pop.",
		"values"=> "password"),
	"bounce_mailbox_port"=> array(
		"name"=> "bounce_mailbox_port",
		"type"=> "scalar",
		"description"=> "The port is the remote port of the connection to retrieve the emails. The default should be fine but if it doesn't work, you can try 110/pop3",
		"values"=> "110/pop3/notls"),
	"bounce_mailbox"=> array(
		"name"=> "bounce_mailbox",
		"type"=> "scalar",
		"description"=> "When the protocol is mbox specify this one. It needs to be a local file in mbox format, accessible to your webserver user",
		"values"=> "/var/spool/mail/listbounces"),
	"bounce_mailbox_purge"=> array(
		"name"=> "bounce_mailbox_purge",
		"type"=> "scalar_int",
		"description"=> "Set this to 0 if you want to keep your messages in the mailbox. This is potentially a problem, because bounces will be counted multiple times, so only do this if you are testing things.",
		"values"=> 1),
	"bounce_mailbox_purge_unprocessed"=> array(
		"name"=> "bounce_mailbox_purge_unprocessed",
		"type"=> "scalar_int",
		"description"=> "Set this to 0 if you want to keep unprocessed messages in the mailbox. Unprocessed messages are messages that could not be matched with a user in the system. Messages are still downloaded into phplist, so it is safe to delete them from the mailbox and view them in phplist.",
		"values"=> 1),
	"bounce_unsubscribe_threshold"=> array(
		"name"=> "bounce_unsubscribe_threshold",
		"type"=> "scalar_int",
		"description"=> "How many bounces in a row need to have occurred for a user to be marked unconfirmed.",
		"values"=> 5));

$securityVars = array(
	"require_login"=> array(
		"name"=> "require_login",
		"type"=> "hidden_scalar_int",
		"description"=> "set this to 1 if you want phplist to deal with login for the administrative section of the system you will be able to add administrators who control their own lists default login is admin with password phplist",
		"values"=> 1),
	"MAXLIST"=> array(
		"name"=> "MAXLIST",
		"type"=> "constant",
		"description"=> "If you use login, how many lists can be created per administrator.",
		"values"=> 1),
	"commandline_users"=> array(
		"name"=> "commandline_users",
		"type"=> "hidden_array",
		"description"=> "If you use commandline, you will need to identify the users who are allowed to run the script. See README.commandline for more info.",
		"values"=> "admin"),
	"ASKFORPASSWORD"=> array(
		"name"=> "ASKFORPASSWORD",
		"type"=> "constant",
		"description"=> "You can have your users define a password for themselves as well this will cause some public pages to ask for an email and a password when the password is set for the user. If you want to activate this functionality, set the following to 1. See README.passwords for more information.",
		"values"=> 0),
  "ENCRYPTPASSWORD"=> array(
    "name"=> "ENCRYPTPASSWORD",
    "type"=> "hidden_constant",
    "description"=> "If you use passwords, you can store them encrypted or in plain text if you want to encrypt them, set this one to 1 if you use encrypted passwords, users can only request you as an administrator to reset the password. They will not be able to request the password from the system.",
    "values"=> 0),
	"UNSUBSCRIBE_REQUIRES_PASSWORD"=> array(
		"name"=> "UNSUBSCRIBE_REQUIRES_PASSWORD",
		"type"=> "constant",
		"description"=> "If you also want to force people who unsubscribe to provide a password before processing their unsubscription, set this to 1. You need to have the above one set to 1 for this to have an effect.",
		"values"=> 0),
	"UNSUBSCRIBE_JUMPOFF"=> array(
		"name"=> "UNSUBSCRIBE_JUMPOFF",
		"type"=> "hidden_constant",
		"description"=> "If a user should immediately be unsubscribed, when using their personal URL, instead of the default way, which will ask them for a reason, set this to 1.",
		"values"=> 0),
	"blacklist_gracetime"=> array(
		"name"=> "blacklist_gracetime",
		"type"=> "hidden_scalar_int",
		"description"=> "When a user unsubscribes they are sent one final email informing them of their unsubscription. In order for that email to actually go out, a gracetime needs to be set otherwise it will never go out. The default of 5 minutes should be fine, but you can increase it if you experience problems.",
		"values"=> 5),
	"CHECK_SESSIONIP"=> array(
		"name"=> "CHECK_SESSIONIP",
		"type"=> "hidden_constant",
		"description"=> "To increase security the session of a user is checked for the IP address this needs to be the same for every request. This may not work with network situations where you connect via multiple proxies, so you can switch off the checking by setting this to 0.",
		"values"=> 1),
	"check_for_host"=> array(
		"name"=> "check_for_host",
		"type"=> "scalar_int",
		"description"=> "Check for host of email entered for subscription. Do not use it if your server is not 24hr online. Make the 0 a 1, if you want to use it.",
		"values"=> 0));

$debbugingVars = array(
	"TEST"=> array(
		"name"=> "TEST",
		"type"=> "constant",
		"description"=> "With test mode ON no message will be delivered.",
		"values"=> "0"),
	"VERBOSE"=> array(
		"name"=> "VERBOSE",
		"type"=> "hidden_constant",
		"description"=> "If you set verbose to 1, it will show the messages that will be sent. Do not do this if you have a lot of users, because it is likely to crash your browser (it does mine, Mozilla 0.9.2, well 1.6 now, but I would still keep it off :-).",
		"values"=> "0"),
	"WARN_ABOUT_PHP_SETTINGS"=> array(
		"name"=> "WARN_ABOUT_PHP_SETTINGS",
		"type"=> "hidden_constant",
		"description"=> "Some warnings may show up about your PHP settings. If you want to get rid of them set this value to 0.",
		"values"=> "1"),
	"MANUALLY_PROCESS_QUEUE"=> array(
		"name"=> "MANUALLY_PROCESS_QUEUE",
		"type"=> "constant",
		"description"=> 'If you set up your system to send the message automatically, you can set this value to 0, so "Process Queue" will disappear from the site this will also stop users from loading the page on the web frontend, so you will have to make sure that you run the queue from the commandline, check README.commandline how to do this',
		"values"=> "1"),
	"WORKAROUND_OUTLOOK_BUG"=> array(
		"name"=> "WORKAROUND_OUTLOOK_BUG",
		"type"=> "hidden_constant",
		"description"=> 'if you want to use \r\n for formatting messages set the 0 to 1. See also http://www.securityfocus.com/archive/1/255910. This is likely to break things for other mailreaders, so you should only use it if all your users have Outlook (not Express)',
		"values"=> "0"),
	"userhistory_systeminfo"=> array(
		"name"=> "userhistory_systeminfo",
		"type"=> "hidden_array",
		"description"=> 'User history system info. When logging the history of a user, you can specify which system variables you want to log. These are the ones that are found in the $_SERVER and the $_ENV variables of PHP. check http://www.php.net/manual/en/language.variables.predefined.php. The values are different per system, but these ones are quite common.',
		"values"=> array("HTTP_USER_AGENT","HTTP_REFERER","REMOTE_ADDR")),
	"USE_SPAM_BLOCK"=> array(
		"name"=> "USE_SPAM_BLOCK",
		"type"=> "constant",
		"description"=> 'Add spamblock if you set this to 1, phplist will try to check if the subscribe attempt is a spambot trying to send nonsense. If you think this doesn\'t work, set this to 0, this is currently only implemented on the subscribe pages.',
		"values"=> 1),
	"NOTIFY_SPAM"=> array(
		"name"=> "NOTIFY_SPAM",
		"type"=> "constant",
		"description"=> 'notify spam when phplist detects a possible spam attack, it can send you a notification about it you can check for a while to see if the spam check was correct and if so, set this value to 0, if you think the check does it\'s job correctly. it will only send you emails if you have "Does the admin get copies of subscribe, update and unsubscribe messages" in the configuration set to true.',
		"values"=> 1));

$feedbackVars = array(
	"REGISTER"=> array(
		"name"=> "REGISTER",
		"type"=> "hidden_constant",
		"description"=> 'Use Register to "register" to phplist.com. Once you set TEST to 0, the system will then request the "Powered By" image from www.phplist.com, instead of locally. This will give me a little bit of an indication of how much it is used, which will encourage me to continue developing phplist. If you do not like this, set Register to 0.',
		"values"=> 0),
	"EMAILTEXTCREDITS"=> array(
		"name"=> "EMAILTEXTCREDITS",
		"type"=> "hidden_constant",
		"description"=> "CREDITS
# We request you retain some form of credits on the public elements of phplist. These are the subscribe pages and the emails.
# This not only gives respect to the large amount of time given freely by the developers but also helps build interest, traffic and use of phplist, which is beneficial to future developments.
# By default the webpages and the HTML emails will include an image and the text emails will include a powered by line

#If you want to remove the image from the HTML emails, set this constant to be 1, the HTML emails will then only add a line of text as signature",
		"values"=> 0),
	"PAGETEXTCREDITS"=> array(
		"name"=> "PAGETEXTCREDITS",
		"type"=> "hidden_constant",
		"description"=> "If you want to also remove the image from your public webpages set the next one to 1, and the pages will only include a line of text",
		"values"=> 0),
	"NOSTATSCOLLECTION"=> array(
		"name"=> "NOSTATSCOLLECTION",
		"type"=> "constant",
		"description"=> "In order to get some feedback about performance, phplist can send statistics to a central email address. To de-activate this set the following value to 1",
		"values"=> 0),
	"stats_collection_address"=> array(
		"name"=> "stats_collection_address",
		"type"=> "commented",
		"description"=> 'this is the email it will be sent to. You can leave the default, or you can set it to send to your self. If you use the default you will give me some feedback about performance which is useful for me for future developments.
# $stats_collection_address = "phplist-stats@tincan.co.uk";',
		"values"=> ""));

$miscellaneousVars = array(
	"NUMCRITERIAS"=> array(
		"name"=> "NUMCRITERIAS",
		"type"=> "constant",
		"description"=> "The number of criterias you want to be able to select when sending a message. Useful is to make it the same as the number of selectable attributes you enter in the system, but that is up to you (selectable = select, radio or checkbox).",
		"values"=> 2),
	"ALLOW_NON_LIST_SUBSCRIBE"=> array(
		"name"=> "ALLOW_NON_LIST_SUBSCRIBE",
		"type"=> "hidden_constant",
		"description"=> "If you do not require users to actually sign up to lists, but only want to use the subscribe page as a kind of registration system, you can set this to 1 and users will not receive an error when they do not check a list to subscribe to.",
		"values"=> 0),
	"MAILQUEUE_BATCH_SIZE"=> array(
		"name"=> "MAILQUEUE_BATCH_SIZE",
		"type"=> "hidden_constant",
		"description"=> "define the amount of emails you want to send per period. If 0, batch processing is disabled and messages are sent out as fast as possible.",
		"values"=> 0),
	"MAILQUEUE_BATCH_PERIOD"=> array(
		"name"=> "MAILQUEUE_BATCH_PERIOD",
		"type"=> "hidden_constant",
		"description"=> "define the length of one batch processing period, in seconds (3600 is an hour).",
		"values"=> 3600),
	"MAILQUEUE_THROTTLE"=> array(
		"name"=> "MAILQUEUE_THROTTLE",
		"type"=> "hidden_constant",
		"description"=> "to avoid overloading the server that sends your email, you can add a little delay between messages that will spread the load of sending you will need to find a good value for your own server, value is in seconds (or you can play with the autothrottle below).",
		"values"=> 0),
	"DATE_START_YEAR"=> array(
		"name"=> "DATE_START_YEAR",
		"type"=> "hidden_constant",
		"description"=> "year ranges. If you use dates, by default the drop down for year will be from three years before until 10 years after this the current value for year. If there is no current value the current year will be used. If you want to use a bigger range you can set the start and end year here be aware that the drop down may become very large. If set to 0 they will use the default behaviour. So I'm afraid you can't start with year 0.",
		"values"=> 0),
	"DATE_END_YEAR"=> array(
		"name"=> "DATE_END_YEAR",
		"type"=> "hidden_constant",
		"description"=> "Date end year. Also be aware not to set the end year to something relatively soon in the future, or it will stop working when you reach that year.",
		"values"=> 0),
	"EMPTY_VALUE_PREFIX"=> array(
		"name"=> "EMPTY_VALUE_PREFIX",
		"type"=> "hidden_constant",
		"description"=> 'empty value prefix. This can be used to identify values in select attributes that are not allowed to be selected and cause an error "Please enter your ...". By using a top value that starts with this string, you can make sure that the selects do not have a default value, that may be accidentally selected, eg. "-- choose your country".',
		"values"=> '"--"'),
	"USE_ADMIN_DETAILS_FOR_MESSAGES"=> array(
		"name"=> "USE_ADMIN_DETAILS_FOR_MESSAGES",
		"type"=> "hidden_constant",
		"description"=> "admin details for messages. If this is enabled phplist will initialise the From in new messages to be the details of the logged in administrator who is sending the message, otherwise it will default to the values set in the configure page that identify the From for system messages.",
		"values"=> 1),
	"SEND_ONE_TESTMAIL"=> array(
		"name"=> "SEND_ONE_TESTMAIL",
		"type"=> "hidden_constant",
		"description"=> "test emails. If you send a test email, phplist will by default send you two emails, one in HTML format and the other in Text format. If you set this to 1, you can override this behaviour and only have a test email sent to you that matches the user record of the user that the test emails are sent to.",
		"values"=> 0));

$experimentalVars = array(
	"USE_LIST_EXCLUDE"=> array(
		"name"=> "USE_LIST_EXCLUDE",
		"type"=> "hidden_constant",
		"description"=> 'List exclude will add the option to send a message to users who are on a list except when they are on another list. This is currently marked experimental',
		"values"=> 0),
	"admin_auth_module"=> array(
		"name"=> "admin_auth_module",
		"type"=> "commented",
		"description"=> ' Admin authentication module.
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
#$admin_auth_module = "/usr/local/etc/auth.inc";
',
		"values"=> ""),
	"STACKED_ATTRIBUTE_SELECTION"=> array(
		"name"=> "STACKED_ATTRIBUTE_SELECTION",
		"type"=> "hidden_constant",
		"description"=> "Stacked attribute selection. This is a new method of making a selection of attributes to send your messages to to start with, it doesn\'t seem to work very well in Internet Explorer, but it works fine using Mozilla, Firefox, Opera (haven't tried any other browsers), so if you use IE, you may not want to try this.

# stacked attribute selection allows you to continuously add a selection of attributes to your message. This is quite a bit more powerful than the old method, but it can also cause very complex queries to be constructed that may take too long to calculate. If you want to try this, set the value to 1, and give us feedback on how it's going.

# if you want to use dates for attribute selections, you need to use this one",
		"values"=> 0),
	"REMOTE_URL_REFETCH_TIMEOUT"=> array(
		"name"=> "REMOTE_URL_REFETCH_TIMEOUT",
		"type"=> "hidden_constant",
		"description"=> 'Send a webpage. You can send the contents of a webpage, by adding [URL:http://website/file.html] as the content of a message. This can also be personalised for users by using eg [URL:http://website/file.html?email=[email]] the timeout for refetching a URL can be defined here. When the last time a URL has been fetched exceeds this time, the URL will be refetched. This is in seconds, 3600 is an hour. This only affects sending within the same "process queue". If a new process queue is started the URL will be fetched the first time anyway. Therefore this is only useful is processing your queue takes longer than the time identified here.',
		"values"=> 3600),
	"CLICKTRACK"=> array(
		"name"=> "CLICKTRACK",
		"type"=> "constant",
		"description"=> 'Click tracking. If you set this to 1, all links in your emails will be converted to links that go via phplist. This will make sure that clicks are tracked. This is experimental and all your findings when using this feature should be reported to mantis for now its off by default until we think it works correctly',
		"values"=> 0),
	"CLICKTRACK_SHOWDETAIL"=> array(
		"name"=> "CLICKTRACK_SHOWDETAIL",
		"type"=> "constant",
		"description"=> 'Click track, list detail. If you enable this, you will get some extra statistics about unique users who have clicked the links in your messages, and the breakdown between clicks from text or html messages. However, this will slow down the process to view the statistics, so it is recommended to leave it off, but if you are very curious, you can enable it',
		"values"=> 0),
	"CLICKTRACK_LINKMAP"=> array(
		"name"=> "CLICKTRACK_LINKMAP",
		"type"=> "commented",
		"description"=> "# Click track link map
# if you want the links in your emails to look a bit more professional, you can set the click track
# link map. If you do this, you will need to add a RewriteRule in your Apache config, which maps this
# back to the original lt.php
# it's quite useful to keep links short in the emails, particularly text emails
# basically the effect is that /lists/lt.php?id=XYX is changed to /lt/XYZ
# if for example your rewrite rule is:
# RewriteRule   ^/lt/(.*)$ /lists/lt.php?id=$1 [PT]
# more info at http://www.google.com/search?q=mod_rewrite (phplist docs to follow at some point)
#define('CLICKTRACK_LINKMAP','/lt/');",
		"values"=> ""),
	"ALWAYS_ADD_USERTRACK"=> array(
		"name"=> "ALWAYS_ADD_USERTRACK",
		"type"=> "constant",
		"description"=> '# Add Usertrack
# tracking opens now seems fairly common, however flawed it still is. Set this option to 1
# to always add [USERTRACK] to any message being sent out, even if someone forgot to add it to
# the template, footer or message body',
		"values"=> 0),
	"USE_DOMAIN_THROTTLE"=> array(
		"name"=> "USE_DOMAIN_THROTTLE",
		"type"=> "hidden_constant",
		"description"=> 'Domain Throttling. You can activate domain throttling, by setting USE_DOMAIN_THROTTLE to 1, define the maximum amount of emails you want to allow sending to any domain and the number of seconds for that amount. This will make sure you don not send too many emails to one domain which may cause blacklisting. Particularly the big ones are tricky about this. It may cause a dramatic increase in the amount of time to send a message, depending on how many users you have that have the same domain (eg hotmail.com) if too many failures for throttling occur, the send process will automatically add an extra delay to try to improve that. The example sends 1 message every 2 minutes.',
		"values"=> 0),
	"DOMAIN_BATCH_SIZE"=> array(
		"name"=> "DOMAIN_BATCH_SIZE",
		"type"=> "hidden_constant",
		"description"=> 'How many messages in a row',
		"values"=> 1),
	"DOMAIN_BATCH_PERIOD"=> array(
		"name"=> "DOMAIN_BATCH_PERIOD",
		"type"=> "hidden_constant",
		"description"=> 'The value is in seconds',
		"values"=> 120),
	"DOMAIN_AUTO_THROTTLE"=> array(
		"name"=> "DOMAIN_AUTO_THROTTLE",
		"type"=> "hidden_constant",
		"description"=> 'If you have very large numbers of users on the same domains, this may result in the need to run processqueue many times, when you use domain throttling. You can also tell phplist to simply delay a bit between messages to increase the number of messages sent per queue run. If you want to use that set this to 1, otherwise simply run the queue many times. A cron process every 10 or 15 minutes is recommended.',
		"values"=> 0),
	"LANGUAGE_SWITCH"=> array(
		"name"=> "LANGUAGE_SWITCH",
		"type"=> "constant",
		"description"=> 'Admin language. If you want to disable the language switch for the admin interface (and run all in english) set this one to 0',
		"values"=> 1),
	"USE_ADVANCED_BOUNCEHANDLING"=> array(
		"name"=> "USE_ADVANCED_BOUNCEHANDLING",
		"type"=> "hidden_constant",
		"description"=> 'Advanced bounce processing. With advanced bounce handling you are able to define regular expressions that match bounces and the action that needs to be taken when an expression matches. This will improve getting rid of bad emails in your system, which will be a good thing for making sure you are not being blacklisted by other mail systems. If you use this, you will need to teach your system regularly about patterns in new bounces',
		"values"=> 0));

$advanceVars = array(
	"HTMLEMAIL_ENCODING"=> array(
		"name"=> "HTMLEMAIL_ENCODING",
		"type"=> "hidden_constant",
		"description"=> "You can specify the encoding for HTML and plaintext messages here. This only works if you do not use the phpmailer (see below) .The default should be fine. Valid options are 7bit, quoted-printable and base64",
		"values"=> '"quoted-printable"'),
	"TEXTEMAIL_ENCODING"=> array(
		"name"=> "TEXTEMAIL_ENCODING",
		"type"=> "hidden_constant",
		"description"=> "Encoding for text emails",
		"values"=> '"7bit"'),
	"ENABLE_RSS"=> array(
		"name"=> "ENABLE_RSS",
		"type"=> "constant",
		"description"=> "phplist can send RSS feeds to users. Feeds can be sent daily, weekly or monthly. To use the feature you need XML support in your PHP installation, and you need to set this constant to 1",
		"values"=> 0),
	"MANUALLY_PROCESS_RSS"=> array(
		"name"=> "MANUALLY_PROCESS_RSS",
		"type"=> "hidden_constant",
		"description"=> "If you have set up a cron to download the RSS entries, you can set this to be 0",
		"values"=> 1),
        "USEFCK"=> array(
                "name"=> "USEFCK",
                "type"=> "hidden_constant",
                "description"=> "The FCKeditor is now included in phplist, but the use of it is experimental if it\'s not working for you, set this to 0
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa.",
                "values"=> 1),
        "FCKIMAGES_DIR"=> array(
                "name"=> "FCKIMAGES_DIR",
                "type"=> "hidden_constant",
		"description"=> "If you want to upload images to the FCKeditor, you need to specify the location of the directory where the images go. This needs to be writable by the webserver, and it needs to be in your public document (website) area. The directory is relative to the root of phplist as set above
# This is a potential security risk, so read README.security for more information",
                "values"=> '"uploadimages"'),
        "UPLOADIMAGES_DIR"=> array(
                "name"=> "UPLOADIMAGES_DIR",
                "type"=> "commented",
		"description"=> "# alternatively, you can set UPLOADIMAGES_DIR, which will take precedence over the FCKIMAGES_DIR
# and it's location will need to be in the document root of your website, instead of in the 
# phplist root. To use this, comment out the following line, and set it to a directory in your
# website document root, that is writable by your webserver user
#define(\"UPLOADIMAGES_DIR\",\"uploadimages\");",
                "values"=> ''),
	"USETINYMCEMESG"=> array(
		"name"=> "USETINYMCEMESG",
		"type"=> "hidden_constant",
		"description"=> "TinyMCE Support (http://tinymce.moxiecode.com/) It is suggested to copy the tinymce/jscripts/tiny_mce directory from the standard TinyMCE distribution into the public_html/lists/admin/plugins directory in order to keep the install clean.
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa. Set this to 1 to turn on TinyMCE for writing messages.",
		"values"=> 0),
	"USETINYMCETEMPL"=> array(
		"name"=> "USETINYMCETEMPL",
		"type"=> "hidden_constant",
		"description"=> "Set this to 1 to turn on TinyMCE for editing templates",
		"values"=> 0),
	"TINYMCEPATH"=> array(
		"name"=> "TINYMCEPATH",
		"type"=> "hidden_constant",
		"description"=> "Set this to path of the TinyMCE script, relative to the admin directory",
		"values"=> '"plugins/tiny_mce/tiny_mce.js"'),
	"TINYMCELANG"=> array(
		"name"=> "TINYMCELANG",
		"type"=> "hidden_constant",
		"description"=> "Set this to the language you wish to use for TinyMCE",
		"values"=> '"en"'),
	"TINYMCETHEME"=> array(
		"name"=> "TINYMCETHEME",
		"type"=> "hidden_constant",
		"description"=> "Set this to the theme you wish to use.  Default options are: simple, default and advanced.",
		"values"=> '"advanced"'),
	"TINYMCEOPTS"=> array(
		"name"=> "TINYMCEOPTS",
		"type"=> "hidden_constant",
		"description"=> "Set this to any additional options you wish.  Please be careful with this as you can inadvertantly break TinyMCE. Rever to the TinyMCE documentation for full details.",
		"values"=> '""'),
	"USE_MANUAL_TEXT_PART"=> array(
		"name"=> "USE_MANUAL_TEXT_PART",
		"type"=> "constant",
		"description"=> "Manual text part, will give you an input box for the text version of the message instead of trying to create it by parsing the HTML version into plain text.",
		"values"=> 0),
	"ALLOW_ATTACHMENTS"=> array(
		"name"=> "ALLOW_ATTACHMENTS",
		"type"=> "hidden_constant",
		"description"=> "Attachments is a new feature and is currently still experimental. Set this to 1 if you want to try it Caution, message may become very large. it is generally more acceptable to send a URL for download to users If you try it, it will be appreciated to give feedback to the users mailinglist, so we can learn whether it is working ok. Using attachments requires PHP 4.1.0 and up.",
		"values"=> 0),
	"NUMATTACHMENTS"=> array(
		"name"=> "NUMATTACHMENTS",
		"type"=> "hidden_constant",
		"description"=> "If you use attachments, how many would you want to add per message (max). You can leave this 1, even if you want to attach more files, because you will be able to add them sequentially.",
		"values"=> 1),
	"FILESYSTEM_ATTACHMENTS"=> array(
		"name"=> "FILESYSTEM_ATTACHMENTS",
		"type"=> "hidden_constant",
		"description"=> "When using attachments you can upload them to the server. If you want to use attachments from the local filesystem (server) set this to 1. Filesystem attachments are attached at real send time of the message, not at the time of creating the message",
		"values"=> 0),
	"MIMETYPES_FILE"=> array(
		"name"=> "MIMETYPES_FILE",
		"type"=> "hidden_constant",
		"description"=> "if you add filesystem attachments, you will need to tell phplist where your mime.types file is.",
		"values"=> '"/etc/mime.types"'),
	"DEFAULT_MIMETYPE"=> array(
		"name"=> "DEFAULT_MIMETYPE",
		"type"=> "hidden_constant",
		"description"=> "If a mimetype cannot be determined for a file, specify the default mimetype here:",
		"values"=> '"application/octet-stream"'),
	"PLUGIN_ROOTDIR"=> array(
		"name"=> "PLUGIN_ROOTDIR",
		"type"=> "hidden_constant",
		"description"=> "you can create your own pages to slot into phplist and do certain things that are more specific to your situation (plugins). If you do this, you can specify the directory where your plugins are. It is useful to keep this outside the phplist system, so they are retained after upgrading. There are some example plugins in the plugins directory inside the admin directory. This directory needs to be absolute, or relative to the admin directory. If you want to see the example plugins take off the path and add the word plugins as the value.",
		"values"=> '"/home/me/phplistplugins"'),
	"attachment_repository"=> array(
		"name"=> "attachment_repository",
		"type"=> "scalar",
		"description"=> "The attachment repository is the place where the files are stored (if you use ALLOW_ATTACHMENTS). This needs to be writable to your webserver user. It also needs to be a full path, not a relative one. For secutiry reasons it is best if this directory is not public (ie below your website document root).",
		"values"=> "/tmp"),
	"PDF"=> array(
		"name"=> "PDF",
		"type"=> "commented",
		"description"=> ' if you want to be able to send your messages as PDF attachments, you need to install
# FPDF (http://www.fpdf.org) and set these variables accordingly

# define("FPDF_FONTPATH","/home/pdf/font/");
# require("fpdf.php");
# define("USE_PDF",1);
# $pdf_font = "Times";
# $pdf_fontstyle = "";
# $pdf_fontsize = 14;
',
		"values"=> ""),
	"export_mimetype"=> array(
		"name"=> "export_mimetype",
		"type"=> "hidden_scalar",
		"description"=> 'the mime type for the export files. You can try changing this to application/vnd.ms-excel to make it open automatically in excel',
		"values"=> "application/csv"),
	"EXPORT_EXCEL"=> array(
		"name"=> "EXPORT_EXCEL",
		"type"=> "hidden_constant",
		"description"=> 'if you want to use export format optimized for Excel, set this one to 1',
		"values"=> 0),
	"USE_REPETITION"=> array(
		"name"=> "USE_REPETITION",
		"type"=> "hidden_constant",
		"description"=> 'Repetition. This adds the option to repeat the same message in the future. After the message has been sent, this option will cause the system to automatically create a new message with the same content. Be careful with it, because you may send the same message to your users. The embargo of the message will be increased with the repetition interval you choose. Also read the README.repetition for more info',
		"values"=> 0),
	"USE_PREPARE"=> array(
		"name"=> "USE_PREPARE",
		"type"=> "hidden_constant",
		"description"=> 'Prepare a message. This system allows you to create messages as a super admin that can then be reviewed and selected by sub admins to send to their own lists. It is old functionality that is quite confusing, and therefore by default it is now off. If you used to use it, you can switch it on here. If you did not use it, or are a new user, it is better to leave it off. It has nothing to do with being able to edit messages.',
		"values"=> 0),
	"PHPMAILER"=> array(
		"name"=> "PHPMAILER",
		"type"=> "hidden_constant",
		"description"=> 'If you want to use the PHPMailer class from phpmailer.sourceforge.net, set the following to 1. If you tend to send out html emails, it is recommended to do so.',
		"values"=> 1),
	"PHPMAILERHOST"=> array(
		"name"=> "PHPMAILERHOST",
		"type"=> "hidden_constant",
		"description"=> 'To use a SMTP please give your server hostname here, leave it blank to use the standard PHP mail() command.',
		"values"=> '""'),
	"phpmailer_smtpuser"=> array(
		"name"=> "phpmailer_smtpuser",
		"type"=> "commented",
		"description"=> ' if you want to use smtp authentication when sending the email uncomment the following
# two lines and set the username and password to be the correct ones
# $phpmailer_smtpuser = "smtpusername";
# $phpmailer_smtppassword = "smtppassword";',
		"values"=> ""),
	"tmpdir"=> array(
		"name"=> "tmpdir",
		"type"=> "scalar",
		"description"=> "tmpdir. A location where phplist can write some temporary files if necessary. Make sure it is writable by your webserver user, and also check that you have open_basedir set to allow access to this directory. Linux users can leave it as it is. This directory is used for all kinds of things, mostly uploading of files (like in import), creating PDFs and more.",
		"values"=> "/tmp"),
	"form_action"=> array(
		"name"=> "form_action",
		"type"=> "commented",
		"description"=> ' if you are on Windoze, and/or you are not using apache, in effect when you are getting
# "Method not allowed" errors you will want to uncomment this
# ie take off the #-character in the next line
# using this is not guaranteed to work, sorry. Easier to use Apache instead :-)
# $form_action = "index.php";',
		"values"=> ""),
	"database_module"=> array(
		"name"=> "database_module",
		"type"=> "scalar",
		"description"=> "Select the database module to use. Anyone wanting to submit other database modules is very welcome!",
		"values"=> "mysql.inc"),
	"SessionTableName"=> array(
		"name"=> "SessionTableName",
		"type"=> "commented",
		"description"=> ' you can store sessions in the database instead of the default place by assigning
# a tablename to this value. The table will be created and will not use any prefixes
# this only works when using mysql and only for administrator sessions
# $SessionTableName = "phplistsessions";',
		"values"=> ""),
	"ADOdb"=> array(
		"name"=> "ADOdb",
		"type"=> "commented",
		"description"=> ' there is now support for the use of ADOdb http://php.weblogs.com/ADODB
# this is still experimental, and any findings should be reported in the
# bugtracker
# in order to use it, define the following settings:
#$database_module = "adodb.inc";
#$adodb_inc_file = "/path/to/adodb_inc.php";
#$adodb_driver = "mysql";',
		"values"=> ""),
	"error_level"=> array(
		"name"=> "error_level",
		"type"=> "hidden_scalar_int",
		"description"=> "If you want more trouble, make this 63 (very unlikely you will like the result).",
		"values"=> "error_reporting(0)")
	);

?>