<?php

/*

=========================================================================

General settings for language and database

=========================================================================

*/


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

# if you want a prefix to all your tables, specify it here,
$table_prefix = "phplist_";

# if you want to use a different prefix to user tables, specify it here.
# read README.usertables for more information
$usertable_prefix = "phplist_user_";

# if you change the path to the PHPlist system, make the change here as well
# path should be relative to the root directory of your webserver (document root)
$pageroot = '/lists';
$adminpages = '/lists/admin';

/*

=========================================================================

Settings for handling bounces

=========================================================================

*/

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

# set this to 0, if you set up a cron to download bounces regularly by using the
# commandline option. If this is 0, users cannot run the page from the web
# frontend. Read README.commandline to find out how to set it up on the
# commandline
define ("MANUALLY_PROCESS_BOUNCES",1);

# when the protocol is pop, specify these three
$bounce_mailbox_host = 'localhost';
$bounce_mailbox_user = 'popuser';
$bounce_mailbox_password = 'password';

# the "port" is the remote port of the connection to retrieve the emails
# the default should be fine but if it doesn't work, you can try the second
# one. To do that, add a # before the first line and take off the one before the
# second line

$bounce_mailbox_port = "110/pop3/notls";
#$bounce_mailbox_port = "110/pop3";

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


/*

=========================================================================

Security related settings

=========================================================================

*/

# set this to 1 if you want PHPlist to deal with login for the administrative
# section of the system
# you will be able to add administrators who control their own lists
# default login is "admin" with password "phplist"
#
$require_login = 1;

# if you use login, how many lists can be created per administrator
define("MAXLIST",1);

# if you use commandline, you will need to identify the users who are allowed to run
# the script. See README.commandline for more info
$commandline_users = array("admin");

# as of version 2.4.1, you can have your users define a password for themselves as well
# this will cause some public pages to ask for an email and a password when the password is
# set for the user. If you want to activate this functionality, set the following
# to 1. See README.passwords for more information
define("ASKFORPASSWORD",0);

# if you also want to force people who unsubscribe to provide a password before
# processing their unsubscription, set this to 1. You need to have the above one set
# to 1 for this to have an effect
define("UNSUBSCRIBE_REQUIRES_PASSWORD",0);

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

# Check for host of email entered for subscription
# Do not use it if your server is not 24hr online
# make the 0 a 1, if you want to use it
$check_for_host = 0;

/*

=========================================================================

Debugging and informational

=========================================================================

*/


# if test is true (not 0) it will not actually send ANY messages,
# but display what it would have sent
define ("TEST",1);

# if you set verbose to 1, it will show the messages that will be sent. Do not do this
# if you have a lot of users, because it is likely to crash your browser
# (it does mine, Mozilla 0.9.2, well 1.6 now, but I would still keep it off :-)
define ("VERBOSE",0);

# some warnings may show up about your PHP settings. If you want to get rid of them
# set this value to 0
define ("WARN_ABOUT_PHP_SETTINGS",1);

# If you set up your system to send the message automatically, you can set this value
# to 0, so "Process Queue" will disappear from the site
# this will also stop users from loading the page on the web frontend, so you will
# have to make sure that you run the queue from the commandline
# check README.commandline how to do this
define ("MANUALLY_PROCESS_QUEUE",1);

# if you want to use \r\n for formatting messages set the 0 to 1
# see also http://www.securityfocus.com/archive/1/255910
# this is likely to break things for other mailreaders, so you should
# only use it if all your users have Outlook (not Express)
define("WORKAROUND_OUTLOOK_BUG",0);

# user history system info.
# when logging the history of a user, you can specify which system variables you
# want to log. These are the ones that are found in the $_SERVER and the $_ENV
# variables of PHP. check http://www.php.net/manual/en/language.variables.predefined.php
# the values are different per system, but these ones are quite common.
$userhistory_systeminfo = array(
	'HTTP_USER_AGENT',
	'HTTP_REFERER',
	'REMOTE_ADDR'
);

/*

=========================================================================

Feedback to developers

=========================================================================

*/

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

/*

=========================================================================

Miscellaneous

=========================================================================

*/


# the number of criterias you want to be able to select when sending a message.
# Useful is is to make it the same as the number of selectable attributes you enter in the
# system, but that is up to you (selectable = select, radio or checkbox)
define ("NUMCRITERIAS",2);

# if you do not require users to actually sign up to lists, but only want to
# use the subscribe page as a kind of registration system, you can set this to 1 and
# users will not receive an error when they do not check a list to subscribe to
define("ALLOW_NON_LIST_SUBSCRIBE",0);

# batch processing
# if you are on a shared host, it will probably be appreciated if you don't send
# out loads of emails in one go. To do this, you can configure batch processing.
# Please note, the following two values can be overridden by your ISP by using
# a server wide configuration. So if you notice these values to be different
# in reality, that may be the case

# define the amount of emails you want to send per period. If 0, batch processing
# is disabled
define("MAILQUEUE_BATCH_SIZE",0);

# define the length of one batch processing period, in seconds (3600 is an hour)
define("MAILQUEUE_BATCH_PERIOD",3600);

/*

=========================================================================

Advanced Features, HTML editor, RSS, Attachments, Plugins. PDF creation

=========================================================================

*/

# you can specify the encoding for HTML messages here. This only works if you do
# not use the phpmailer (see below)
# the default should be fine. Valid options are 7bit, quoted-printable and base64
define("HTMLEMAIL_ENCODING","quoted-printable");


# PHPlist can send RSS feeds to users. Feeds can be sent daily, weekly or
# monthly. This feature is currently marked experimental.
# To use the feature you need XML support in your PHP installation, and you need
# to set this constant to 1
define("ENABLE_RSS",0);

# if you have set up a cron to download the RSS entries, you can set this to be 0
define("MANUALLY_PROCESS_RSS",1);

# the FCKeditor is now included in PHPlist, but the use of it is experimental
# if it's not working for you, set this to 0
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa.
define("USEFCK",1);

# If you want to upload images to the FCKeditor, you need to specify the location
# of the directory where the images go. This needs to be writable by the webserver,
# and it needs to be in your public document (website) area
# the directory is relative to the root of PHPlist as set above
# This is a potential security risk, so read README.security for more information
define("FCKIMAGES_DIR","uploadimages");

# TinyMCE Support (http://tinymce.moxiecode.com/)
# It is suggested to copy the tinymce/jscripts/tiny_mce directory from the
# standard TinyMCE distribution into the public_html/lists/admin/plugins
# directory in order to keep the install clean.
# NOTE: If you enable TinyMCE please disable FCKeditor and vice-versa.
# Set this to 1 to turn on TinyMCE:
define("USETINYMCE", 0);
# Set this to path of the TinyMCE script, relative to the admin directory:
define("TINYMCEPATH", "plugins/tiny_mce/tiny_mce.js");
# Set this to the language you wish to use for TinyMCE:
define("TINYMCELANG", "en");
# Set this to the theme you wish to use.  Default options are: simple, default and advanced.
define("TINYMCETHEME", "advanced");
# Set this to any additional options you wish.  Please be careful with this as you can
# inadvertantly break TinyMCE.  Rever to the TinyMCE documentation for full details.
# Should be in the format: ',option1:"value",option2:"value"'   <--- note comma at beginning
define("TINYMCEOPTS", "");

# Manual text part, will give you an input box for the text version of the message
# instead of trying to create it by parsing the HTML version into plain text
define("USE_MANUAL_TEXT_PART",0);

# attachments is a new feature and is currently still experimental
# set this to 1 if you want to try it
# caution, message may become very large. it is generally more
# acceptable to send a URL for download to users
# if you try it, it will be appreciated to give feedback to the
# users mailinglist, so we can learn whether it is working ok
# using attachments requires PHP 4.1.0 and up
define("ALLOW_ATTACHMENTS",0);

# if you use the above, how many would you want to add per message (max)
# You can leave this 1, even if you want to attach more files, because
# you will be able to add them sequentially
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

# you can create your own pages to slot into PHPlist and do certain things
# that are more specific to your situation (plugins)
# if you do this, you can specify the directory where your plugins are. It is
# useful to keep this outside the PHPlist system, so they are retained after
# upgrading
# there are some example plugins in the "plugins" directory inside the
# admin directory
# this directory needs to be absolute, or relative to the admin directory

define("PLUGIN_ROOTDIR","/home/me/phplistplugins");

# uncomment this one to see the examples in the system (and then comment the 
# one above)
#define("PLUGIN_ROOTDIR","plugins");


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

# the mime type for the export files. You can try changing this to
# application/vnd.ms-excel to make it open automatically in excel
$export_mimetype = 'application/csv';

# if you want to use export format optimized for Excel, set this one to 1
define("EXPORT_EXCEL",0);

# Repetition. This adds the option to repeat the same message in the future.
# After the message has been sent, this option will cause the system to automatically
# create a new message with the same content. Be careful with it, because you may
# send the same message to your users
# the embargo of the message will be increased with the repetition interval you choose
# also read the README.repetition for more info
define("USE_REPETITION",0);

# Prepare a message. This system allows you to create messages as a super admin
# that can then be reviewed and selected by sub admins to send to their own lists
# it is old functionality that is quite confusing, and therefore by default it
# is now off. If you used to use it, you can switch it on here. If you did not
# use it, or are a new user, it is better to leave it off. It has nothing to
# do with being able to edit messages.
define("USE_PREPARE",0);

# James Storm has contributed a new version of the HTML email class that creates the
# HTML emails. He claims that his class fixes all kinds of things for MS Outlook.
# if you want to use his class instead of the standard one, set this to 1
# if you do so, we'd appreciate feedback whether it works ok, so we can integrate it
# properly into the system. We need some more test information before this can be done

# this does currently not work, so do not use it!
define("USE_OUTLOOK_OPTIMIZED_HTML",0);

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

# there is now support for the use of ADOdb http://php.weblogs.com/ADODB
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
