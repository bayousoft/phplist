<?

# select the language module to use
# Look for <country>.inc files in the texts directory
# to find your language
#
$language_module = "english.inc";

# what is your Mysql database server
$database_host = "localhost";

# what is the name of the database we are using
$database_name = "phplistdb";

# who do we log in as?
$database_user = "phplist";

# and what password do we use
$database_password = 'phplist';

# if you use multiple installations of PHPlist you can set this to
# something to identify this one. it will be prepended to email report 
# subjects
$installation_name = 'PHPlist'; 

# Message envelope. This is the email that system messages come from
# it is useful to make this one where you can process the bounces on
# you will probably get a X-Authentication-Warning in your message
# when using this with sendmail
# NOTE: this is *very* different from the From: line in a message
# to use this feature, uncomment the following line, and change the email address
# to some existing account on your system
# requires PHP version > "4.0.5" and "4.3.1+" without safe_mode
# $message_envelope = 'listbounces@yourdomain';

# Handling bounces. Check README.bounces for more info
# This can be 'pop' or 'mbox'
$bounce_protocol = 'pop';

# set this to 0, if you set up a cron to download bounces regularly
define ("MANUALLY_PROCESS_BOUNCES",1);

# when the protocol is pop, specify these three
$bounce_mailbox_host = 'localhost';
$bounce_mailbox_user = 'popuser';
$bounce_mailbox_password = 'password';

# when the protocol is mbox specify this one
# it needs to be a local file in mbox format, accessible to your webserver user
$bounce_mailbox = '/var/spool/mail/listbounces';

# set this to 0 if you want to keep your messages in the mailbox. this is potentially
# a problem, because bounces will be counted multiple times, so only do this if you are
# testing things.
$bounce_mailbox_purge = 1;

# set this to 0 if you want to keep unprocessed messages in the mailbox. Unprocessed
# messages are messages that could not be matched with a user in the system
# messages are still downloaded into PHPlist, so it is safe to delete them from
# the mailbox and view them in PHPlist
$bounce_mailbox_purge_unprocessed = 1;

# how many bounces in a row need to have occurred for a user to be marked unconfirmed
$bounce_unsubscribe_treshold = 5;

# set this to 1 if you want PHPlist to deal with login for the administrative 
# section of the system
# you will be able to add administrators who control their own lists
# default login is "admin" with password "phplist"
#
$require_login = 1;

# as of version 2.4.1, you can have your users define a password for themselves as well
# this will cause some public pages to ask for an email and a password when the password is
# set for the user. If you want to activate this functionality, set the following
# to 1. See README.passwords for more information
define("ASKFORPASSWORD",0);

# to increase security the session of a user is checked for the IP address
# this needs to be the same for every request. This may not work with 
# network situations where you connect via multiple proxies, so you can
# switch off the checking by setting this to 0
define("CHECK_SESSIONIP",1);

# if you use passwords, you can store them encrypted or in plain text
# if you want to encrypt them, set this one to 1
# if you use encrypted passwords, users can only request you as an administrator to
# reset the password. They will not be able to request the password from
# the system
define("ENCRYPTPASSWORD",0);

# if you use login, how many lists can be created per administrator
define("MAXLIST",1);

# Check for host of email entered for subscription
# Do not use it if your server is not 24hr online
# make the 0 a 1, if you want to use it
$check_for_host = 0;

# if test is true (not 0) it will not actually send ANY messages,
# but display what it would have sent
define ("TEST",1);

# if you set verbose to 1, it will show the messages that will be sent. Do not do this
# if you have a lot of users, because it is likely to crash your browser
# (it does mine, Mozilla 0.9.2, well 1.4 now, but I would still keep it off :-)
define ("VERBOSE",0);

# some warnings may show up about your PHP settings. If you want to get rid of them
# set this value to 0
define ("WARN_ABOUT_PHP_SETTINGS",1);

# If you set up your system to send the message automatically, you can set this value
# to 0, so "Process Queue" will disappear from the site
define ("MANUALLY_PROCESS_QUEUE",1);

# use Register to "register" to PHPlist.com. Once you set TEST to 0, the system will then
# request the "Powered By" image from www.phplist.com, instead of locally. This will give me
# a little bit of an indication of how much it is used, which will encourage me to continue
# developing PHPlist. If you do not like this, set Register to 0.
define ("REGISTER",1);

# CREDITS
# We request you retain some form of credits on the public elements of
# PHPlist. These are the subscribe pages and the emails.
# This not only gives respect to the large amount of time given freely
# by the developers	but also helps build interest, traffic and use of
# PHPlist, which is beneficial to future developments.
# By default the webpages and the HTML emails will include an image and
# the text emails will include a powered by line

# If you want to remove the image from the HTML emails, set this constant
# to be 1, the HTML emails will then only add a line of text as signature
define("EMAILTEXTCREDITS",0);

# if you want to also remove the image from your public webpages
# set the next one to 1, and the pages will only include a line of text
define("PAGETEXTCREDITS",0);

# in order to get some feedback about performance, PHPlist can send statistics to a central
# email address. To de-activate this set the following value to 1
define ("NOSTATSCOLLECTION",0);

# this is the email it will be sent to. You can leave the default, or you can set it to send
# to your self. If you use the default you will give me some feedback about performance
# which is useful for me for future developments
# $stats_collection_address = 'phplist-stats@tincan.co.uk';

# the number of criterias you want to be able to select when sending a message.
# Useful is is to make it the same as the number of selectable attributes you enter in the
# system, but that is up to you (selectable = select, radio or checkbox)
define ("NUMCRITERIAS",2);

# if you want a prefix to all your tables, specify it here,
$table_prefix = "phplist_";

# if you want to use a different prefix to user tables, specify it here.
# read README.usertables for more information
$usertable_prefix = "phplist_user_";

# if you change the path to these pages, make the change here as well
# path should be relative to the root directory of your webserver
# you may have to change it at loads of other places as well

$pageroot = '/lists';
$adminpages = $pageroot . '/admin';

# PHPlist can send RSS feeds to users. Feeds can be sent daily, weekly or
# monthly. This feature is currently marked experimental.
# To use the feature you need XML support in your PHP installation, and you need
# to set this constant to 1
define("ENABLE_RSS",0);

# if you have set up a cron to download the RSS entries, you can set this to be 0
define("MANUALLY_PROCESS_RSS",1);

# the FCKeditor is now included in PHPlist, but the use of it is experimental
# if you want to try it, set this to 1
define("USEFCK",0);

# attachments is a new feature and is currently still experimental
# set this to 1 if you want to try it
# caution, message may become very large. it is generally more
# acceptable to send a URL for download to users
# if you try it, it will be appreciated to give feedback to the
# users mailinglist, so we can learn whether it is working ok
# using attachments requires PHP 4.1.0 and up
define("ALLOW_ATTACHMENTS",0);

# if you use the above, how many would you want to add per message (max)
define("NUMATTACHMENTS",1);

# when using attachments you can upload them to the server
# if you want to use attachments from the local filesystem (server) set this to 1
# filesystem attachments are attached at real send time of the message, not at 
# the time of creating the message
define("FILESYSTEM_ATTACHMENTS",0);

# if you add filesystem attachments, you will need to tell PHPlist where your
# mime.types file is.
define("MIMETYPES_FILE","/etc/mime.types");

# if a mimetype cannot be determined for a file, specify the default mimetype here:
define("DEFAULT_MIMETYPE","application/octet-stream");

# the attachment repository is the place where the files are stored (if you use
# ALLOW_ATTACHMENTS)
# this needs to be writable to your webserver user
# it also needs to be a full path, not a relative one
# for secutiry reasons it is best if this directory is not public (ie below
# your website document root)
$attachment_repository = '/tmp';

# if you want to be able to send your messages as PDF attachments, you need to install
# FPDF (http://www.fpdf.org) and set these variables accordingly

# define('FPDF_FONTPATH','/home/pdf/font/');
# require('fpdf.php');
# define("USE_PDF",1);
# $pdf_font = 'Times';
# $pdf_fontstyle = '';
# $pdf_fontsize = 14;

# if you want to use \r\n for formatting messages set the 0 to 1
# see also http://www.securityfocus.com/archive/1/255910
# this is likely to break things for other mailreaders, so you should
# only use it if all your users have Outlook (not Express)
define("WORKAROUND_OUTLOOK_BUG",1);

# the mime type for the export files. You can try changing this to
# application/vnd.ms-excel to make it open automatically in excel
$export_mimetype = 'application/csv';

# Repetition. This adds the option to repeat the same message in the future.
# After the message has been sent, this option will cause the system to automatically
# create a new message with the same content. Be careful with it, because you may
# send the same message to your users
# the embargo of the message will be increased with the repetition interval you choose
define("USE_REPETITION",0);

# If you want to use the PHPMailer class from phpmailer.sourceforge.net, set the following
# to 1, this code is not finished yet, and it is highly experimental. Do not use on
# live installations, only to play around with, and for testing.
# if you want to use this, put the phpmailer files in lists/admin/phpmailer
# I will include it in a future release, but for now, you will have to add it yourself
define("PHPMAILER",0);

# If you do the above, and you want to send with SMTP, give your SMTP server here:
# otherwise the normal mail() function will be used
define("SMTPHOST","mail");

# if you upgrade we need to be able to write temporary files somewhere
# indicate where this can be done. Make sure it is writable by your webserver
# user. Linux users can leave it as it is.
# If you send messages as PDF, you will need to use this as well
# also the RSS class will create cache files in this directory
$tmpdir = '/tmp';

# if you are on Windoze, and/or you are not using apache, in effect when you are getting
# "Method not allowed" errors you will want to uncomment this
# ie take off the #-character in the next line
# using this is not guaranteed to work, sorry. Easier to use Apache instead :-)
# $form_action = 'index.php';

# select the database module to use
# anyone wanting to submit other database modules is
# very welcome!
$database_module = "mysql.inc";

# there is now support for the use of ADOdb
# this is still experimental, and any findings should be reported in the
# bugtracker
# in order to use it, define the following settings:
#$database_module = 'adodb.inc';
#$adodb_inc_file = '/path/to/adodb_inc.php';
#$adodb_driver = 'mysql';

# if you want more trouble, make this 63 (very unlikely you will like the result)
$error_level = error_reporting(0);

################################################################################################
# you should not need to edit below, but if things go wrong you can try to play around with it #
################################################################################################

if (file_exists($database_module)) {
  include $database_module;
} elseif (file_exists("admin/$database_module")) {
  include "admin/$database_module";
} elseif (file_exists("../$database_module")) { # help is one level up
  include "../$database_module";
} else {
	print "Cannot load $database_module, exit";
	exit;
}
if (file_exists("../texts/english.inc")) { # first load english and then the translation
  include "../texts/english.inc";
} elseif(file_exists("texts/english.inc")) {
  include "texts/english.inc";
} elseif (file_exists("../../texts/english.inc")) {
  include "../../texts/english.inc";
} else {
	print "Cannot load english.inc, exit;";
	exit;
}
if (file_exists("../texts/$language_module")) {
  include "../texts/$language_module";
} elseif (file_exists("texts/$language_module")) {
  include "texts/$language_module";
} elseif (file_exists("../../texts/$language_module")) {
  include "../../texts/$language_module";
} else {
	print "Cannot load $language_module, exit";
	exit;
}
if (file_exists("defaultconfig.inc")) {
  include "defaultconfig.inc";
} elseif (file_exists("admin/defaultconfig.inc")) {
  include "admin/defaultconfig.inc";
} elseif (file_exists("../defaultconfig.inc")) {
  include "../defaultconfig.inc";
} else {
  print "cannot load defaultconfig, exit";
	exit;
}
if (file_exists("connect.php")) {
  include "connect.php";
} elseif (file_exists("admin/connect.php")) {
  include "admin/connect.php";
} elseif (file_exists("../connect.php")) {
  include "../connect.php";
} else {
	print "Cannot load libraries connect, exit";
	exit;
}
if (file_exists("languages.php")) {
  include "languages.php";
} elseif (file_exists("admin/languages.php")) {
  include "admin/languages.php";
} elseif (file_exists("../languages.php")) {
  include "../languages.php";
} else {
	print "Cannot load language libraries, exit";
	exit;
}#ob_start(); # useful to be able to redirect after outputting

?>
