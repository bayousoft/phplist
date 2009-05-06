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
$GLOBALS['adodb_inc_file'] = $adodb_inc_file;
$GLOBALS['show_dev_errors'] = $show_dev_errors;

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
if (!defined('MERGE_DUPLICATES_DELETE_DUPLICATE')) define('MERGE_DUPLICATES_DELETE_DUPLICATE',0);
if (!defined('USE_PERSONALISED_REMOTEURLS')) define('USE_PERSONALISED_REMOTEURLS',1);
if (!defined('USE_LOCAL_SPOOL')) define('USE_LOCAL_SPOOL',0);
if (!defined('SEND_LISTADMIN_COPY')) define('SEND_LISTADMIN_COPY',0);
if (!defined('EMAIL_ADDRESS_VALIDATION_LEVEL')) define('EMAIL_ADDRESS_VALIDATION_LEVEL',2);
if (!defined('BLACKLIST_EMAIL_ON_BOUNCE')) define('BLACKLIST_EMAIL_ON_BOUNCE',1);
if (!defined('MANUALLY_PROCESS_BOUNCES')) define('MANUALLY_PROCESS_BOUNCES',1);
if (!defined('ENCRYPT_ADMIN_PASSWORDS')) define('ENCRYPT_ADMIN_PASSWORDS',0);
if (!defined('PASSWORD_CHANGE_TIMEFRAME')) define('PASSWORD_CHANGE_TIMEFRAME','1 day');

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
?>
