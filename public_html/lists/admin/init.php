<?php

# initialisation stuff
# record the start time(usec) of script
$now =  gettimeofday();
$GLOBALS["pagestats"] = array();
$GLOBALS["pagestats"]["time_start"] = $now["sec"] * 1000000 + $now["usec"];
$GLOBALS["pagestats"]["number_of_queries"] = 0;

$IsCommandlinePlugin = '';
$zlib_compression = ini_get('zlib.output_compression');
# hmm older versions of PHP don't have this, but then again, upgrade php instead?
if (function_exists('ob_list_handlers')) {
  $handlers = ob_list_handlers();
} else {
  $handlers = array();
}
$gzhandler = 0;
foreach ($handlers as $handler) {
  $gzhandler = $gzhandler || $handler == 'ob_gzhandler';
}
# @@@ needs more work
$GLOBALS['compression_used'] = $zlib_compression || $gzhandler;

# make sure these are set correctly, so they cannot be injected due to the PHP Globals Problem,
# http://www.hardened-php.net/globals-problem
$GLOBALS['language_module'] = $language_module;
$GLOBALS['database_module'] = $database_module;
if (isset($GLOBALS['design'])) {
#@todo 
#  $GLOBALS['design'] = basename($GLOBALS['design']);
}
if (!isset($GLOBALS['design']) || !is_dir(dirname(__FILE__).'/'.$GLOBALS['design'])) {
  $GLOBALS['design'] = '';
}
$GLOBALS['adodb_inc_file'] = $adodb_inc_file;
$GLOBALS['show_dev_errors'] = $show_dev_errors;
$magic_quotes = ini_get('magic_quotes_gpc');
if ($magic_quotes == 'off' || empty($magic_quotes)) {
  define('NO_MAGIC_QUOTES',true);
} else {
  define('NO_MAGIC_QUOTES',false);
}

if (empty($GLOBALS['language_module'])) {
  $GLOBALS['language_module'] = 'english.inc';
}
if (empty($GLOBALS['database_module'])) {
  $GLOBALS['database_module'] = 'mysql.inc';
}

## @@ would be nice to move this to the config file at some point
$GLOBALS['scheme'] = 'http';

## spelling mistake in earlier version, make sure to set it correctly
if (!isset($bounce_unsubscribe_threshold) && isset($bounce_unsubscribe_treshold)) {
  $bounce_unsubscribe_threshold = $bounce_unsubscribe_treshold;
}
# set some defaults if they are not specified
if (!defined("REGISTER")) define("REGISTER",1);
if (!defined("USE_PDF")) define("USE_PDF",0);
if (!defined("VERBOSE")) define("VERBOSE",0);
if (!defined("TEST")) define("TEST",1);
if (!defined("DEVSITE")) define("DEVSITE",0);

// obsolete by rssmanager plugin
// if (!defined("ENABLE_RSS")) define("ENABLE_RSS",0);
if (!defined("ALLOW_ATTACHMENTS")) define("ALLOW_ATTACHMENTS",0);
if (!defined("EMAILTEXTCREDITS")) define("EMAILTEXTCREDITS",0);
if (!defined("PAGETEXTCREDITS")) define("PAGETEXTCREDITS",0);
if (!defined("USEFCK")) define("USEFCK",1);
if (!defined("ASKFORPASSWORD")) define("ASKFORPASSWORD",0);
if (!defined("UNSUBSCRIBE_REQUIRES_PASSWORD")) define("UNSUBSCRIBE_REQUIRES_PASSWORD",0);
if (!defined("UNSUBSCRIBE_JUMPOFF")) define("UNSUBSCRIBE_JUMPOFF",0);
if (!defined("ENCRYPTPASSWORD")) define("ENCRYPTPASSWORD",0);
if (!defined("PHPMAILER")) define("PHPMAILER",0);
if (!defined('PHPMAILERHOST')) define("PHPMAILERHOST",'');
if (!defined("MANUALLY_PROCESS_QUEUE")) define("MANUALLY_PROCESS_QUEUE",1);
if (!defined("CHECK_SESSIONIP")) define("CHECK_SESSIONIP",1);
if (!defined("FILESYSTEM_ATTACHMENTS")) define("FILESYSTEM_ATTACHMENTS",0);
if (!defined("MIMETYPES_FILE")) define("MIMETYPES_FILE","/etc/mime.types");
if (!defined("DEFAULT_MIMETYPE")) define("DEFAULT_MIMETYPE","application/octet-stream");
if (!defined("USE_REPETITION")) define("USE_REPETITION",0);
if (!defined("USE_EDITMESSAGE")) define("USE_EDITMESSAGE",0);
if (!defined("FCKIMAGES_DIR")) define("FCKIMAGES_DIR","uploadimages");
if (!defined('UPLOADIMAGES_DIR')) define('UPLOADIMAGES_DIR','images');
if (!defined("USE_MANUAL_TEXT_PART")) define("USE_MANUAL_TEXT_PART",0);
if (!defined("ALLOW_NON_LIST_SUBSCRIBE")) define("ALLOW_NON_LIST_SUBSCRIBE",0);
if (!defined("MAILQUEUE_BATCH_SIZE")) define("MAILQUEUE_BATCH_SIZE",150); //Changed for test. (old value = 0)
if (!defined("MAILQUEUE_BATCH_PERIOD")) define("MAILQUEUE_BATCH_PERIOD",1800); //Changed for test. (old value = 3600)
if (!defined('MAILQUEUE_THROTTLE')) define('MAILQUEUE_THROTTLE',20); //Changed for test. (old value = 0)
if (!defined('MAILQUEUE_AUTOTHROTTLE')) define('MAILQUEUE_AUTOTHROTTLE',1); //Changed for test. (old value = 0)
if (!defined("NAME")) define("NAME",'phplist');
if (!defined("USE_OUTLOOK_OPTIMIZED_HTML")) define("USE_OUTLOOK_OPTIMIZED_HTML",0);
if (!defined("EXPORT_EXCEL")) define("EXPORT_EXCEL",0);
if (!defined("USE_PREPARE")) define("USE_PREPARE",0);
if (!defined("HTMLEMAIL_ENCODING")) define("HTMLEMAIL_ENCODING","quoted-printable");
if (!defined('TEXTEMAIL_ENCODING')) define('TEXTEMAIL_ENCODING','7bit');
if (!defined("USE_LIST_EXCLUDE")) define("USE_LIST_EXCLUDE",0);
if (!defined("WARN_SAVECHANGES")) define("WARN_SAVECHANGES",1);
if (!defined("STACKED_ATTRIBUTE_SELECTION")) define("STACKED_ATTRIBUTE_SELECTION",0);
if (!defined("REMOTE_URL_REFETCH_TIMEOUT")) define('REMOTE_URL_REFETCH_TIMEOUT',3600);
if (!defined('CLICKTRACK')) define('CLICKTRACK',0);
if (!defined('CLICKTRACK_SHOWDETAIL')) define('CLICKTRACK_SHOWDETAIL',0);
if (!defined('USETINYMCEMESG')) define('USETINYMCEMESG',0);
if (!defined('USETINYMCETEMPL')) define('USETINYMCETEMPL',0);
if (!defined('TINYMCEPATH')) define('TINYMCEPATH','');
if (!defined('STATS_INTERVAL')) define('STATS_INTERVAL','weekly');
if (!defined('USE_DOMAIN_THROTTLE')) define('USE_DOMAIN_THROTTLE',0);
if (!defined('DOMAIN_BATCH_SIZE')) define('DOMAIN_BATCH_SIZE',1);
if (!defined('DOMAIN_BATCH_PERIOD')) define('DOMAIN_BATCH_PERIOD',120);
if (!defined('DOMAIN_AUTO_THROTTLE')) define('DOMAIN_AUTO_THROTTLE',0);
if (!defined('LANGUAGE_SWITCH')) define('LANGUAGE_SWITCH',1);
if (!defined('USE_ADVANCED_BOUNCEHANDLING')) define('USE_ADVANCED_BOUNCEHANDLING',0);
if (!defined('DATE_START_YEAR')) define('DATE_START_YEAR',1900);
if (!defined('DATE_END_YEAR')) define('DATE_END_YEAR',0);
if (!defined('ALLOW_IMPORT')) define('ALLOW_IMPORT',1);
if (!defined('EMPTY_VALUE_PREFIX')) define('EMPTY_VALUE_PREFIX','--');
if (!defined('USE_ADMIN_DETAILS_FOR_MESSAGES')) define('USE_ADMIN_DETAILS_FOR_MESSAGES',1);
if (!defined('SEND_ONE_TESTMAIL')) define('SEND_ONE_TESTMAIL',0);
if (!defined('USE_SPAM_BLOCK')) define('USE_SPAM_BLOCK',1);
if (!defined('NOTIFY_SPAM')) define('NOTIFY_SPAM',1);
if (!defined('CLICKTRACK_LINKMAP')) define('CLICKTRACK_LINKMAP',0);
if (!defined('ALWAYS_ADD_USERTRACK')) define('ALWAYS_ADD_USERTRACK',0);
if (!defined('MERGE_DUPLICATES_DELETE_DUPLICATE')) define('MERGE_DUPLICATES_DELETE_DUPLICATE',1);
if (!defined('USE_PERSONALISED_REMOTEURLS')) define('USE_PERSONALISED_REMOTEURLS',1);
if (!defined('USE_LOCAL_SPOOL')) define('USE_LOCAL_SPOOL',0);
if (!defined('SEND_LISTADMIN_COPY')) define('SEND_LISTADMIN_COPY',0);
if (!defined('EMAIL_ADDRESS_VALIDATION_LEVEL')) define('EMAIL_ADDRESS_VALIDATION_LEVEL',1);
if (!defined('BLACKLIST_EMAIL_ON_BOUNCE')) define('BLACKLIST_EMAIL_ON_BOUNCE',1);
if (!defined('MANUALLY_PROCESS_BOUNCES')) define('MANUALLY_PROCESS_BOUNCES',1);
if (!defined('ENCRYPT_ADMIN_PASSWORDS')) define('ENCRYPT_ADMIN_PASSWORDS',0);
if (!defined('PASSWORD_CHANGE_TIMEFRAME')) define('PASSWORD_CHANGE_TIMEFRAME','1 day');

# check whether Pear HTTP/Request is available
@include_once "HTTP/Request.php";
$GLOBALS['has_pear_http_request'] = class_exists('HTTP_Request');

## fairly crude way to determine php version, but mostly needed for the stripos
if (function_exists('stripos')) {
  define('PHP5',1);
} else {
  define('PHP5',0);
}
if (!isset($pageroot)) {
  $pageroot = '/lists/admin/';
}

if (!defined('FORWARD_ALTERNATIVE_CONTENT')) define('FORWARD_ALTERNATIVE_CONTENT',0);
if (!defined('KEEPFORWARDERATTRIBUTES')) define('KEEPFORWARDERATTRIBUTES',0);
if (!defined('FORWARD_EMAIL_COUNT') ) define('FORWARD_EMAIL_COUNT',1);
if (FORWARD_EMAIL_COUNT < 1) {
  print 'Config Error: FORWARD_EMAIL_COUNT must be > (int) 0';
  exit;
}
# allows FORWARD_EMAIL_COUNT forwards per user per period in mysql interval terms default one day
if (!defined('FORWARD_EMAIL_PERIOD') ) define('FORWARD_EMAIL_PERIOD', '1 day');
if (!defined('FORWARD_PERSONAL_NOTE_SIZE')) define('FORWARD_PERSONAL_NOTE_SIZE',0);
if (!defined('EMBEDUPLOADIMAGES')) define('EMBEDUPLOADIMAGES',0);
if (!defined('IMPORT_FILESIZE')) define('IMPORT_FILESIZE',1);

if (!isset($GLOBALS["export_mimetype"])) $GLOBALS["export_mimetype"] = 'application/csv';
if (!isset($GLOBALS["admin_auth_module"])) $GLOBALS["admin_auth_module"] = 'phplist_auth.inc';
if (!isset($GLOBALS["require_login"])) $GLOBALS["require_login"] = 1;

if (!defined("WORKAROUND_OUTLOOK_BUG") && defined("USE_CARRIAGE_RETURNS")) {
  define("WORKAROUND_OUTLOOK_BUG",USE_CARRIAGE_RETURNS);
}
if (!isset($GLOBALS["blacklist_gracetime"])) $GLOBALS["blacklist_gracetime"] = 5;
if (!isset($GLOBALS["message_envelope"])) $GLOBALS["message_envelope"] = '';

# list of pages and categorisation in the system
$system_pages = array (
	"system" => array (
		"adminattributes" => "none",
		"attributes" => "none",
		"upgrade" => "none",
		"configure" => "none",
		"spage" => "owner",
		"spageedit" => "owner",
		"defaultconfig" => "none",
		"defaults" => "none",
		"initialise" => "none",
		"bounces" => "none",
		"bounce" => "none",
		"processbounces" => "none",
		"eventlog" => "none",
		"reconcileusers" => "none",
		"getrss" => "owner",
		"viewrss" => "owner",
		"purgerss" => "none",
		"setup" => "none",
		"dbcheck" => "none",
		
	),
	"list" => array (
		"list" => "owner",
		"editlist" => "owner",
		"members" => "owner"
	),
	"user" => array (
		"user" => "none",
		"users" => "none",
		"dlusers" => "none",
		"editattributes" => "none",
		"usercheck" => "none",
		"import1" => "none",
		"import2" => "none",
		"import3" => "none",
		"import4" => "none",
		"import" => "none",
		"export" => "none",
		"massunconfirm" => "none",
		
	),
	"message" => array (
		"message" => "owner",
		"messages" => "owner",
		"processqueue" => "none",
		"send" => "owner",
		"preparesend" => "none",
		"sendprepared" => "all",
		"template" => "none",
		"templates" => "none"
	),
	"clickstats" => array (
		'statsmgt' => 'owner',
		'mclicks' => 'owner',
		'uclicks' => 'owner',
		'userclicks' => 'owner',
		'mviews' => 'owner',
		'statsoverview' => 'owner',
		
	),
	"admin" => array (
		"admins" => "none",
		"admin" => "owner"
	)
);

$GLOBALS['pagecategories'] = array(
  ## category title => main page to link to
  'subscribers' => 'usermgt',
  'campaigns' => 'campaignmgt',
  'statistics' => 'statsoverview',
  'hide' => '',
  'system' => 'system',
  'develop' => 'develop',
  'config' => 'setup',
  'info' => '',
);

$GLOBALS['pageclassification'] = array(
  "send"  => array('category' => 'campaigns','mainmenu' => 1),
  "mviews"  => array('category' => 'statistics','mainmenu' => 1),
  "users"  => array('category' => 'subscribers','mainmenu' => 1),
  "usermgt"  => array('category' => 'subscribers','mainmenu' => 1),
  "import"  => array('category' => 'subscribers','mainmenu' => 1),
  "about"  => array('category' => 'info','mainmenu' => 1),
  "accesscheck"  => array('category' => 'hide'),
  "addprefix"  => array('category' => 'hide'),
  "adminattributes"  => array('category' => 'config'),
  "admin"  => array('category' => 'config','mainmenu' => 1),
  "admins"  => array('category' => 'config','mainmenu' => 1),
  "attributes"  => array('category' => 'config','mainmenu' => 1),
  "bounce"  => array('category' => 'system'),
  "bouncerule"  => array('category' => 'config'),
  "bouncerules"  => array('category' => 'config'),
  "bounces"  => array('category' => 'system'),
  "campaign_core"  => array('category' => 'hide'),
  "campaign"  => array('category' => 'campaigns'),
  "catlists"  => array('category' => 'config'),
  "checkbouncerules"  => array('category' => 'config'),
  "checki18n"  => array('category' => 'develop','mainmenu' => 1),
  "community"  => array('category' => 'info','mainmenu' => 1),
  "configure"  => array('category' => 'config','mainmenu' => 1),
  "convertstats"  => array('category' => 'system'),
  "dbadmin"  => array('category' => 'develop','mainmenu' => 1),
  "dbcheck"  => array('category' => 'system','mainmenu' => 1),
  "defaults"  => array('category' => 'config'),
  "dlusers"  => array('category' => 'subscribers'),
  "domainstats"  => array('category' => 'statistics','mainmenu' => 1),
  "editattributes"  => array('category' => 'config'),
  "editlist"  => array('category' => 'config','mainmenu' => 1),
  "eventlog"  => array('category' => 'system','mainmenu' => 1),
  "export"  => array('category' => 'subscribers','mainmenu' => 1),
  "generatebouncerules"  => array('category' => 'config'),
  "getrss"  => array('category' => 'system'),
  "home"  => array('category' => 'info'),
  "import1"  => array('category' => 'subscribers'),
  "import2"  => array('category' => 'subscribers'),
  "import3"  => array('category' => 'subscribers'),
  "import4"  => array('category' => 'subscribers'),
  "importadmin"  => array('category' => 'system','mainmenu' => 1),
  "info"  => array('category' => 'hide'),
  "initialise"  => array('category' => 'system'),
  "listbounces"  => array('category' => 'subscribers'),
  "list"  => array('category' => 'config'),
  "massremove"  => array('category' => 'subscribers','mainmenu' => 1),
  "massunconfirm"  => array('category' => 'subscribers','mainmenu' => 1),
  "mclicks"  => array('category' => 'statistics','mainmenu' => 1),
  "members"  => array('category' => 'subscribers','mainmenu' => 1),
  "message"  => array('category' => 'campaigns'),
  "messages"  => array('category' => 'campaigns','mainmenu' => 1),
  "templates"  => array('category' => 'campaigns','mainmenu' => 1),
  "bouncemgt"  => array('category' => 'campaigns','mainmenu' => 1),
  "msgbounces"  => array('category' => 'info'),
  "msgstatus"  => array('category' => 'hide'),
  "processbounces"  => array('category' => 'system'),
  "processqueue"  => array('category' => 'system'),
  "purgerss"  => array('category' => 'system'),
  "reconcileusers"  => array('category' => 'subscribers','mainmenu' => 1),
  "reindex"  => array('category' => 'system'),
  "resetstats"  => array('category' => 'system'),
  "sendprepared"  => array('category' => 'campaigns'),
  "setup"  => array('category' => 'config','mainmenu' => 1),
  "spageedit"  => array('category' => 'config','mainmenu' => 1),
  "spage"  => array('category' => 'config'),
  "statsmgt"  => array('category' => 'statistics','mainmenu' => 1),
  "statsoverview"  => array('category' => 'statistics','mainmenu' => 1),
  "stresstest"  => array('category' => 'develop'),
  "subscriberstats"  => array('category' => 'statistics','mainmenu' => 1),
  "template"  => array('category' => 'campaigns'),
  "tests"  => array('category' => 'develop'),
  "uclicks"  => array('category' => 'statistics'),
  "upgrade"  => array('category' => 'system'),
  "usercheck"  => array('category' => 'subscribers'),
  "userclicks"  => array('category' => 'statistics'),
  "userhistory"  => array('category' => 'subscribers'),
  "user"  => array('category' => 'subscribers'),
  "viewmessage"  => array('category' => 'campaigns'),
  "viewrss"  => array('category' => 'system'),
  "viewtemplate"  => array('category' => 'campaigns'),
  "vote"  => array('category' => 'info'),

);


