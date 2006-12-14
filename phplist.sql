-- MySQL dump 10.10
--
-- Host: localhost    Database: phplistcvsdb
-- ------------------------------------------------------
-- Server version	5.0.22-standard

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `eventlog`
--

DROP TABLE IF EXISTS `eventlog`;
CREATE TABLE `eventlog` (
  `id` int(11) NOT NULL auto_increment,
  `entered` datetime default NULL,
  `page` varchar(100) default NULL,
  `entry` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `eventlog`
--


/*!40000 ALTER TABLE `eventlog` DISABLE KEYS */;
LOCK TABLES `eventlog` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `eventlog` ENABLE KEYS */;

--
-- Table structure for table `keymanager_keydata`
--

DROP TABLE IF EXISTS `keymanager_keydata`;
CREATE TABLE `keymanager_keydata` (
  `name` varchar(255) NOT NULL default '',
  `id` int(11) NOT NULL,
  `data` text,
  PRIMARY KEY  (`name`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keymanager_keydata`
--


/*!40000 ALTER TABLE `keymanager_keydata` DISABLE KEYS */;
LOCK TABLES `keymanager_keydata` WRITE;
INSERT INTO `keymanager_keydata` VALUES ('keyid',1,'95594BCA409A5757'),('uid',1,'a:6:{s:4:\"name\";s:8:\"Test Key\";s:7:\"comment\";s:12:\"Testing Only\";s:5:\"email\";s:16:\"test@phplist.com\";s:3:\"uid\";s:42:\"Test Key (Testing Only) <test@phplist.com>\";s:7:\"revoked\";b:0;s:7:\"invalid\";b:0;}'),('timestamp',1,'1166055217'),('disabled',1,''),('expired',1,''),('expires',1,'1166660017'),('revoked',1,''),('invalid',1,''),('is_secret',1,''),('can_sign',1,'1'),('can_encrypt',1,''),('fingerprint',1,'BFA5D8D49E8306503EB7339395594BCA409A5757'),('keyid',2,'7D14A33E7C51CC14'),('uid',2,'a:6:{s:4:\"name\";s:8:\"Test Key\";s:7:\"comment\";s:12:\"Testing Only\";s:5:\"email\";s:16:\"test@phplist.com\";s:3:\"uid\";s:42:\"Test Key (Testing Only) <test@phplist.com>\";s:7:\"revoked\";b:0;s:7:\"invalid\";b:0;}'),('timestamp',2,'1166055228'),('disabled',2,''),('expired',2,''),('expires',2,'1166660028'),('revoked',2,''),('invalid',2,''),('is_secret',2,''),('can_sign',2,''),('can_encrypt',2,'1'),('fingerprint',2,'439C5C4328646F641221CD1A7D14A33E7C51CC14'),('keyid',3,'E93323A40F693332'),('uid',3,'a:6:{s:4:\"name\";s:9:\"Test User\";s:7:\"comment\";s:23:\"Keys to test the system\";s:5:\"email\";s:20:\"testuser@phplist.com\";s:3:\"uid\";s:58:\"Test User (Keys to test the system) <testuser@phplist.com>\";s:7:\"revoked\";b:0;s:7:\"invalid\";b:0;}'),('timestamp',3,'1162947175'),('disabled',3,''),('expired',3,''),('expires',3,'1199070001'),('revoked',3,''),('invalid',3,''),('is_secret',3,''),('can_sign',3,'1'),('can_encrypt',3,''),('fingerprint',3,'03ADF7C0CA373E2B5EE122EAE93323A40F693332'),('keyid',4,'E4C78A4C23880ADD'),('uid',4,'a:6:{s:4:\"name\";s:9:\"Test User\";s:7:\"comment\";s:23:\"Keys to test the system\";s:5:\"email\";s:20:\"testuser@phplist.com\";s:3:\"uid\";s:58:\"Test User (Keys to test the system) <testuser@phplist.com>\";s:7:\"revoked\";b:0;s:7:\"invalid\";b:0;}'),('timestamp',4,'1162947184'),('disabled',4,''),('expired',4,''),('expires',4,'1199070010'),('revoked',4,''),('invalid',4,''),('is_secret',4,''),('can_sign',4,''),('can_encrypt',4,'1'),('fingerprint',4,'F3AF547AAE11DA61F796D09BE4C78A4C23880ADD');
UNLOCK TABLES;
/*!40000 ALTER TABLE `keymanager_keydata` ENABLE KEYS */;

--
-- Table structure for table `keymanager_keys`
--

DROP TABLE IF EXISTS `keymanager_keys`;
CREATE TABLE `keymanager_keys` (
  `id` int(11) NOT NULL auto_increment,
  `keyid` varchar(255) NOT NULL,
  `email` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `fingerprint` varchar(255) default NULL,
  `can_encrypt` tinyint(4) default NULL,
  `can_sign` tinyint(4) default NULL,
  `deleted` tinyint(4) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `keymanager_keys`
--


/*!40000 ALTER TABLE `keymanager_keys` DISABLE KEYS */;
LOCK TABLES `keymanager_keys` WRITE;
INSERT INTO `keymanager_keys` VALUES (1,'95594BCA409A5757','test@phplist.com','Test Key','BFA5D8D49E8306503EB7339395594BCA409A5757',0,1,0),(2,'7D14A33E7C51CC14','test@phplist.com','Test Key','439C5C4328646F641221CD1A7D14A33E7C51CC14',1,0,0),(3,'E93323A40F693332','testuser@phplist.com','Test User','03ADF7C0CA373E2B5EE122EAE93323A40F693332',0,1,0),(4,'E4C78A4C23880ADD','testuser@phplist.com','Test User','F3AF547AAE11DA61F796D09BE4C78A4C23880ADD',1,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `keymanager_keys` ENABLE KEYS */;

--
-- Table structure for table `phplist_admin`
--

DROP TABLE IF EXISTS `phplist_admin`;
CREATE TABLE `phplist_admin` (
  `id` int(11) NOT NULL auto_increment,
  `loginname` varchar(25) NOT NULL default '',
  `namelc` varchar(255) default NULL,
  `email` varchar(255) NOT NULL default '',
  `created` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `modifiedby` varchar(25) default NULL,
  `password` varchar(255) default NULL,
  `passwordchanged` date default NULL,
  `superuser` tinyint(4) default '0',
  `disabled` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `loginname` (`loginname`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_admin`
--


/*!40000 ALTER TABLE `phplist_admin` DISABLE KEYS */;
LOCK TABLES `phplist_admin` WRITE;
INSERT INTO `phplist_admin` VALUES (1,'admin','admin','','2002-05-24 16:06:33','2002-05-24 15:06:33','','phplist','2002-05-24',1,0),(2,'listadmin','listadmin','listadmin@phplist.com','2002-05-31 10:37:15','2002-05-31 10:17:27','listadmin','password','0000-00-00',0,0),(3,'listadmin2','listadmin2','lsitadmin2@phplist.com','2002-05-31 10:40:12','2002-05-31 09:40:12','admin','password','0000-00-00',0,0),(4,'listadmin3','listadmin3','','2002-05-31 11:05:22','2002-05-31 10:05:22','admin','password','0000-00-00',0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_admin` ENABLE KEYS */;

--
-- Table structure for table `phplist_admin_attribute`
--

DROP TABLE IF EXISTS `phplist_admin_attribute`;
CREATE TABLE `phplist_admin_attribute` (
  `adminattributeid` int(11) NOT NULL default '0',
  `adminid` int(11) NOT NULL default '0',
  `value` varchar(255) default NULL,
  PRIMARY KEY  (`adminattributeid`,`adminid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_admin_attribute`
--


/*!40000 ALTER TABLE `phplist_admin_attribute` DISABLE KEYS */;
LOCK TABLES `phplist_admin_attribute` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_admin_attribute` ENABLE KEYS */;

--
-- Table structure for table `phplist_admin_task`
--

DROP TABLE IF EXISTS `phplist_admin_task`;
CREATE TABLE `phplist_admin_task` (
  `adminid` int(11) NOT NULL default '0',
  `taskid` int(11) NOT NULL default '0',
  `level` int(11) default NULL,
  PRIMARY KEY  (`adminid`,`taskid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_admin_task`
--


/*!40000 ALTER TABLE `phplist_admin_task` DISABLE KEYS */;
LOCK TABLES `phplist_admin_task` WRITE;
INSERT INTO `phplist_admin_task` VALUES (2,17,4),(2,16,4),(2,15,4),(2,14,4),(2,13,4),(2,12,4),(2,11,4),(2,7,4),(2,6,4),(2,5,4),(2,4,4),(2,3,4),(2,2,4),(2,1,4),(2,25,4),(2,24,4),(2,23,4),(2,22,4),(2,21,4),(2,20,4),(2,19,4),(2,18,4),(2,10,4),(2,9,4),(2,8,4),(2,27,4),(2,26,4),(0,26,0),(0,27,0),(0,8,4),(0,9,4),(0,10,4),(0,18,4),(0,19,4),(0,20,0),(0,21,0),(0,22,0),(0,23,1),(0,24,0),(0,25,0),(0,1,0),(0,2,0),(0,3,0),(0,4,0),(0,5,0),(0,6,0),(0,7,0),(0,11,0),(0,12,0),(0,13,0),(0,14,0),(0,15,4),(0,16,4),(0,17,4),(3,26,0),(3,27,0),(3,8,4),(3,9,4),(3,10,4),(3,18,4),(3,19,4),(3,20,0),(3,21,0),(3,22,0),(3,23,1),(3,24,0),(3,25,0),(3,1,0),(3,2,0),(3,3,0),(3,4,0),(3,5,0),(3,6,0),(3,7,0),(3,11,0),(3,12,0),(3,13,0),(3,14,0),(3,15,4),(3,16,4),(3,17,4),(4,26,0),(4,27,0),(4,8,4),(4,9,4),(4,10,4),(4,18,4),(4,19,4),(4,20,0),(4,21,0),(4,22,0),(4,23,1),(4,24,0),(4,25,0),(4,1,0),(4,2,0),(4,3,0),(4,4,0),(4,5,0),(4,6,0),(4,7,0),(4,11,0),(4,12,0),(4,13,0),(4,14,0),(4,15,4),(4,16,4),(4,17,4),(0,246,0),(0,247,0),(0,248,0),(0,249,0),(0,250,4),(0,251,4),(0,252,4),(0,253,4),(0,254,4),(0,255,4);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_admin_task` ENABLE KEYS */;

--
-- Table structure for table `phplist_adminattribute`
--

DROP TABLE IF EXISTS `phplist_adminattribute`;
CREATE TABLE `phplist_adminattribute` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `type` varchar(30) default NULL,
  `listorder` int(11) default NULL,
  `default_value` varchar(255) default NULL,
  `required` tinyint(4) default NULL,
  `tablename` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_adminattribute`
--


/*!40000 ALTER TABLE `phplist_adminattribute` DISABLE KEYS */;
LOCK TABLES `phplist_adminattribute` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_adminattribute` ENABLE KEYS */;

--
-- Table structure for table `phplist_attachment`
--

DROP TABLE IF EXISTS `phplist_attachment`;
CREATE TABLE `phplist_attachment` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) default NULL,
  `remotefile` varchar(255) default NULL,
  `mimetype` varchar(255) default NULL,
  `description` text,
  `size` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_attachment`
--


/*!40000 ALTER TABLE `phplist_attachment` DISABLE KEYS */;
LOCK TABLES `phplist_attachment` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_attachment` ENABLE KEYS */;

--
-- Table structure for table `phplist_bounce`
--

DROP TABLE IF EXISTS `phplist_bounce`;
CREATE TABLE `phplist_bounce` (
  `id` int(11) NOT NULL auto_increment,
  `date` datetime default NULL,
  `header` text,
  `data` blob,
  `status` varchar(255) default NULL,
  `comment` text,
  PRIMARY KEY  (`id`),
  KEY `dateindex` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_bounce`
--


/*!40000 ALTER TABLE `phplist_bounce` DISABLE KEYS */;
LOCK TABLES `phplist_bounce` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_bounce` ENABLE KEYS */;

--
-- Table structure for table `phplist_bounceregex`
--

DROP TABLE IF EXISTS `phplist_bounceregex`;
CREATE TABLE `phplist_bounceregex` (
  `id` int(11) NOT NULL auto_increment,
  `regex` varchar(255) default NULL,
  `action` varchar(255) default NULL,
  `listorder` int(11) default '0',
  `admin` int(11) default NULL,
  `comment` text,
  `status` varchar(255) default NULL,
  `count` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `regex` (`regex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_bounceregex`
--


/*!40000 ALTER TABLE `phplist_bounceregex` DISABLE KEYS */;
LOCK TABLES `phplist_bounceregex` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_bounceregex` ENABLE KEYS */;

--
-- Table structure for table `phplist_bounceregex_bounce`
--

DROP TABLE IF EXISTS `phplist_bounceregex_bounce`;
CREATE TABLE `phplist_bounceregex_bounce` (
  `regex` int(11) NOT NULL default '0',
  `bounce` int(11) NOT NULL default '0',
  PRIMARY KEY  (`regex`,`bounce`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_bounceregex_bounce`
--


/*!40000 ALTER TABLE `phplist_bounceregex_bounce` DISABLE KEYS */;
LOCK TABLES `phplist_bounceregex_bounce` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_bounceregex_bounce` ENABLE KEYS */;

--
-- Table structure for table `phplist_config`
--

DROP TABLE IF EXISTS `phplist_config`;
CREATE TABLE `phplist_config` (
  `item` varchar(35) NOT NULL default '',
  `value` longtext,
  `editable` tinyint(4) default '1',
  `type` varchar(25) default NULL,
  PRIMARY KEY  (`item`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_config`
--


/*!40000 ALTER TABLE `phplist_config` DISABLE KEYS */;
LOCK TABLES `phplist_config` WRITE;
INSERT INTO `phplist_config` VALUES ('version','2.11.1-dev',0,NULL),('subscribesubject:3','Request for confirmation',0,NULL),('subscribemessage:3','  Almost welcome to our mailinglist(s) ...\r\n\r\n  Someone, hopefully you, has subscribed your email address to the following mailinglists:\r\n\r\n[LISTS]\r\n\r\n  If this is correct, please click this URL to confirm your subscription:\r\n\r\n[CONFIRMATIONURL]\r\n\r\n  If this is not correct, you do not need to do anything, simply delete this message.\r\n\r\n  Thank you\r\n\r\n  ',0,NULL),('confirmationsubject:3','Welcome to our Mailinglist',0,NULL),('confirmationmessage:3','  Welcome to our Mailinglists\r\n\r\n  Please keep this email for later reference.\r\n\r\n  Your email address has been added to the following mailinglists:\r\n[LISTS]\r\n\r\n  To unsubscribe please go to [UNSUBSCRIBEURL] and follow the steps.\r\n  To update your details and preferences please go to [PREFERENCESURL].\r\n\r\n  Thank you\r\n\r\n',0,NULL),('subscribeurl','http://[WEBSITE]/lists/?p=subscribe',1,NULL),('unsubscribeurl','http://[WEBSITE]/lists/?p=unsubscribe',1,NULL),('preferencesurl','http://[WEBSITE]/lists/?p=preferences',1,NULL),('defaultsubscribepage','1',1,NULL),('textline_width','40',1,NULL),('textarea_dimensions','10,40',1,NULL),('hide_single_list','1',1,NULL),('messagefooter','--\nTo unsubscribe from this list visit [UNSUBSCRIBE]\n\nTo update your preferences visit [PREFERENCES]\n',1,NULL),('subscribemessage:2','  Almost welcome to our mailinglist(s) ...\r\n\r\n  Someone, hopefully you, has subscribed your email address to the following mailinglists:\r\n\r\n[LISTS]\r\n\r\n  If this is correct, please click this URL to confirm your subscription:\r\n\r\n[CONFIRMATIONURL]\r\n\r\n  If this is not correct, you do not need to do anything, simply delete this message.\r\n\r\n  Thank you\r\n\r\n  ',0,NULL),('subscribemessage','\n\n  Almost welcome to our mailinglist(s) ...\n\n  Someone, hopefully you, has subscribed your email address to the following mailinglists:\n\n[LISTS]\n\n  If this is correct, please click this URL to confirm your subscription:\n\n[CONFIRMATIONURL]\n\n  If this is not correct, you do not need to do anything, simply delete this message.\n\n  Thank you\n\n  ',1,NULL),('subscribesubject:2','Request for confirmation',0,NULL),('subscribesubject','Request for confirmation',1,NULL),('confirmationmessage:2','  Welcome to our Mailinglists\r\n\r\n  Please keep this email for later reference.\r\n\r\n  Your email address has been added to the following mailinglists:\r\n[LISTS]\r\n\r\n  To unsubscribe please go to [UNSUBSCRIBEURL] and follow the steps.\r\n  To update your details and preferences please go to [PREFERENCESURL].\r\n\r\n  Thank you\r\n\r\n',0,NULL),('confirmationmessage','\n\n  Welcome to our Mailinglists\n\n  Please keep this email for later reference.\n\n  Your email address has been added to the following mailinglists:\n[LISTS]\n\n  To unsubscribe please go to [UNSUBSCRIBEURL] and follow the steps.\n  To update your details and preferences please go to [PREFERENCESURL].\n\n  Thank you\n\n',1,NULL),('confirmationsubject:2','Welcome to our Mailinglist',0,NULL),('confirmationsubject','Welcome to our Mailinglist',1,NULL),('confirmationurl','http://[WEBSITE]/lists/?p=confirm',1,NULL),('message_from_name','Webmaster',1,NULL),('message_from_address','noreply@[DOMAIN]',1,NULL),('report_address','listreports@[DOMAIN]',1,NULL),('check_new_version','7',1,NULL),('updatelastcheck','2006-12-13 01:24:15',0,NULL),('fckeditor_height','400',1,NULL),('rssthreshold','',1,NULL),('unsubscribesubject','Goodbye from our Newsletter',1,NULL),('message_replyto_address','noreply@[DOMAIN]',1,NULL),('send_admin_copies','0',1,NULL),('defaultmessagetemplate','0',1,NULL),('fckeditor_width','600',1,NULL),('domain','phplist.com',0,NULL),('website','cvs.phplist.com',0,NULL),('xormask','4585d6fb8798ff2830062756e03eb6b2',0,NULL),('admin_address','webmaster@[DOMAIN]',1,NULL),('admin_addresses','',1,NULL),('forwardurl','http://[WEBSITE]/lists/?p=forward',1,NULL),('unsubscribemessage','\n\n  Goodbye from our Newsletter, sorry to see you go.\n\n  You have been unsubscribed from our newsletters.\n\n  This is the last email you will receive from us. We have added you to our\n  \"blacklist\", which means that our newsletter system will refuse to send\n  you any other email, without manual intervention by our administrator.\n\n  If there is an error in this information, you can re-subscribe:\n  please go to [SUBSCRIBEURL] and follow the steps.\n\n  Thank you\n\n',1,NULL),('updatesubject','[notify] Change of List-Membership details',1,NULL),('updatemessage','\n\n  This message is to inform you of a change of your details on our newsletter database\n\n  You are currently member of the following newsletters:\n\n[LISTS]\n\n[CONFIRMATIONINFO]\n\n  The information on our system for you is as follows:\n\n[USERDATA]\n\n  If this is not correct, please update your information at the following location:\n\n[PREFERENCESURL]\n\n  Thank you\n\n  ',1,NULL),('emailchanged_text','\n  When updating your details, your email address has changed.\n  Please confirm your new email address by visiting this webpage:\n\n[CONFIRMATIONURL]\n\n',1,NULL),('emailchanged_text_oldaddress','\n  Please Note: when updating your details, your email address has changed.\n\n  A message has been sent to your new email address with a URL\n  to confirm this change. Please visit this website to activate\n  your membership.\n',1,NULL),('personallocation_subject','Your personal location',1,NULL),('personallocation_message','\n\nYou have requested your personal location to update your details from our website.\nThe location is below. Please make sure that you use the full line as mentioned below.\nSometimes email programme can wrap the line into multiple lines.\n\nYour personal location is:\n[PREFERENCESURL]\n\nThank you.\n',1,NULL),('forwardfooter','--\nThis message has been forwarded to you by [FORWARDEDBY].\n  You have not been automatically subscribed to this newsletter.\n  To subscribe to this newsletter go to\n\n [SUBSCRIBE]\n',1,NULL),('pageheader','<link href=\"styles/phplist.css\" type=\"text/css\" rel=\"stylesheet\">\n</head>\n<body bgcolor=\"#ffffff\" background=\"images/bg.png\">\n<a name=\"top\"></a>\n<div align=center>\n<table cellspacing=0 cellpadding=0 width=710 border=0>\n<tr>\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n</tr>\n\n<tr valign=\"top\" align=\"left\">\n<td>\n<!--TOP TABLE starts-->\n<TABLE cellSpacing=0 cellPadding=0 width=708 bgColor=#ffffff border=0>\n  <TR vAlign=top>\n    <TD colSpan=2 rowspan=\"2\" height=\"63\" background=\"images/topstrip.png\"><a href=\"http://www.phplist.com\" target=\"_blank\"><img src=\"images/masthead.png\" border=0 width=577 height=75></a></TD>\n    <TD align=left\n      background=\"images/topstrip.png\" bgcolor=\"#F0D1A3\"><FONT\n      size=-2>&nbsp;<I>powered by: </I><BR>&nbsp;<B>[<A class=powered\n      href=\"http://www.php.net/\" target=_new><I>PHP</I></A>]</B> + <B>[<A\n      class=powered href=\"http://www.mysql.com/\"\n      target=_new>mySQL</A>]</B></FONT></TD></TR>\n  <TR vAlign=bottom>\n    <TD vAlign=bottom width=132\n    background=\"images/topright.png\" bgcolor=\"#F0D1A3\"><SPAN\n      class=webblermenu>PHPlist</SPAN></TD></TR>\n  <TR>\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\n      src=\"images/transparent.png\" width=20\n      border=0></TD>\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\n      src=\"images/transparent.png\" width=576\n      border=0></TD>\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\n      src=\"images/transparent.png\" width=132\n      border=0></TD></TR>\n  <TR vAlign=top>\n    <TD>&nbsp;</TD>\n<td><div align=left>\n<br />\n',1,NULL),('pagefooter','</div>\n</td>\n<td>\n<div class=\"menutableright\">\n\n</div>\n</td>\n</tr>\n\n\n\n\n<tr><td colspan=\"4\">&nbsp;</td></tr>\n\n\n\n<tr><td colspan=\"4\">&nbsp;</td></tr>\n</table>\n<!--TOP TABLE ends-->\n\n</td></tr>\n\n\n<tr>\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n</tr>\n\n<tr>\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?php echo VERSION?></td>\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n</tr>\n\n<tr>\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n</tr>\n\n<tr>\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\n</tr>\n\n<tr>\n<td colspan=3>\n&nbsp;\n</td>\n</tr>\n</tbody>\n</table>\n\n</div>\n</body></html>\n',1,NULL),('html_charset','iso-8859-1',1,NULL),('text_charset','iso-8859-1',1,NULL),('wordwrap','75',1,NULL),('html_email_style','\n<style type=\"text/css\">\nbody { font-size : 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }\na { font-size: 11px; color: #ff6600; font-style: normal; font-family: verdana, sans-serif; text-decoration: none; }\na:visited { color: #666666; }\na:hover {  text-decoration: underline; }\np { font-weight: normal; font-size: 11px; color: #666666; font-style: normal; font-family: verdana, sans-serif; text-decoration: none; }\nh1 {font-weight: bold; font-size: 14px; color: #666666; font-style: normal; font-family: verdana, sans-serif; text-decoration: none;}\nh2 {font-weight: bold; font-size: 13px; color: #666666; font-style: normal; font-family: verdana, sans-serif; text-decoration: none;}\nh3 {font-weight: bold; font-size: 12px; color: #666666; font-style: normal; font-family: verdana, sans-serif; text-decoration: none; margin:0px; padding:0px;}\nh4 {font-weight: bold; font-size: 11px; color: #666666; font-style: normal; font-family: verdana, sans-serif; text-decoration: none; margin:0px; padding:0px;}\nhr {width : 100%; height : 1px; color: #ff9900; size:1px;}\n.forwardform {margin: 0 0 0 0; padding: 0 0 0 0;}\n.forwardinput {margin: 0 0 0 0; padding: 0 0 0 0;}\n.forwardsubmit {margin: 0 0 0 0; padding: 0 0 0 0;}\ndiv.emailfooter { font-size : 11px; font-family: Verdana, Arial, Helvetica, sans-serif; }\ndiv.emailfooter a { font-size: 11px; color: #ff6600; font-style: normal; font-family: verdana, sans-serif; text-decoration: none; }\n</style>\n',1,NULL),('alwayssendtextto','mail.com\nemail.com',1,NULL),('subscribesubject:4','Request for confirmation',0,NULL),('subscribemessage:4','  Almost welcome to our mailinglist(s) ...\r\n\r\n  Someone, hopefully you, has subscribed your email address to the following mailinglists:\r\n\r\n[LISTS]\r\n\r\n  If this is correct, please click this URL to confirm your subscription:\r\n\r\n[CONFIRMATIONURL]\r\n\r\n  If this is not correct, you do not need to do anything, simply delete this message.\r\n\r\n  Thank you\r\n\r\n  ',0,NULL),('confirmationsubject:4','Welcome to our Mailinglist',0,NULL),('confirmationmessage:4','  Welcome to our Mailinglists\r\n\r\n  Please keep this email for later reference.\r\n\r\n  Your email address has been added to the following mailinglists:\r\n[LISTS]\r\n\r\n  To unsubscribe please go to [UNSUBSCRIBEURL] and follow the steps.\r\n  To update your details and preferences please go to [PREFERENCESURL].\r\n\r\n  Thank you\r\n\r\n',0,NULL),('Key Manager-keyattribute','14',0,NULL),('Key Manager-keyringlocation','',0,NULL),('Email 2 Fax-provider','someemailtofaxprovider.com',0,NULL),('Email 2 Fax-faxattribute','15',0,NULL),('Email 2 Fax-prefix','Fax=',0,NULL),('Email 2 Fax-toformat','[prefix][faxnumber]@[providerdomain]',0,NULL),('Email 2 Fax-htmldocbin','/usr/bin/htmldoc',0,NULL),('Email 2 Fax-htmldocoptions','--no-toc --footer ./D --header lt. --browserwidth 800 --no-strict \r\n--size A4 --pagemode fullscreen --webpage  \r\n--bodycolor ffffff --bodyfont helvetica --textcolor 000000 \r\n--compression=9 --textfont helvetica --fontsize 12 \r\n--fontspacing 1 --color --firstpage p1 \r\n--headfootfont Courier --headfootsize 8 --linkstyle underline ',0,NULL),('Email 2 Fax-sendas','pdf',0,NULL),('subscribesubject:5','Request for confirmation',0,NULL),('subscribemessage:5','\r\n  Almost welcome to our mailinglist(s) ...\r\n\r\n  Someone, hopefully you, has subscribed your email address to the following mailinglists:\r\n\r\n[LISTS]\r\n\r\n  If this is correct, please click this URL to confirm your subscription:\r\n\r\n[CONFIRMATIONURL]\r\n\r\n  If this is not correct, you do not need to do anything, simply delete this message.\r\n\r\n  Thank you\r\n\r\n  ',0,NULL),('confirmationsubject:5','Welcome to our Mailinglist',0,NULL),('confirmationmessage:5','\r\n  Welcome to our Mailinglists\r\n\r\n  Please keep this email for later reference.\r\n\r\n  Your email address has been added to the following mailinglists:\r\n[LISTS]\r\n\r\n  To unsubscribe please go to [UNSUBSCRIBEURL] and follow the steps.\r\n  To update your details and preferences please go to [PREFERENCESURL].\r\n\r\n  Thank you\r\n\r\n',0,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_config` ENABLE KEYS */;

--
-- Table structure for table `phplist_eventlog`
--

DROP TABLE IF EXISTS `phplist_eventlog`;
CREATE TABLE `phplist_eventlog` (
  `id` int(11) NOT NULL auto_increment,
  `entered` datetime default NULL,
  `page` varchar(100) default NULL,
  `entry` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_eventlog`
--


/*!40000 ALTER TABLE `phplist_eventlog` DISABLE KEYS */;
LOCK TABLES `phplist_eventlog` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_eventlog` ENABLE KEYS */;

--
-- Table structure for table `phplist_linktrack`
--

DROP TABLE IF EXISTS `phplist_linktrack`;
CREATE TABLE `phplist_linktrack` (
  `linkid` int(11) NOT NULL auto_increment,
  `messageid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `url` varchar(255) default NULL,
  `forward` text,
  `firstclick` datetime default NULL,
  `latestclick` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `clicked` int(11) default '0',
  PRIMARY KEY  (`linkid`),
  UNIQUE KEY `messageid` (`messageid`,`userid`,`url`),
  KEY `miduidurlindex` (`messageid`,`userid`,`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_linktrack`
--


/*!40000 ALTER TABLE `phplist_linktrack` DISABLE KEYS */;
LOCK TABLES `phplist_linktrack` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_linktrack` ENABLE KEYS */;

--
-- Table structure for table `phplist_linktrack_forward`
--

DROP TABLE IF EXISTS `phplist_linktrack_forward`;
CREATE TABLE `phplist_linktrack_forward` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) default NULL,
  `personalise` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `urlunique` (`url`),
  KEY `urlindex` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_linktrack_forward`
--


/*!40000 ALTER TABLE `phplist_linktrack_forward` DISABLE KEYS */;
LOCK TABLES `phplist_linktrack_forward` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_linktrack_forward` ENABLE KEYS */;

--
-- Table structure for table `phplist_linktrack_ml`
--

DROP TABLE IF EXISTS `phplist_linktrack_ml`;
CREATE TABLE `phplist_linktrack_ml` (
  `messageid` int(11) NOT NULL,
  `forwardid` int(11) NOT NULL,
  `firstclick` datetime default NULL,
  `latestclick` datetime default NULL,
  `total` int(11) default '0',
  `clicked` int(11) default '0',
  `htmlclicked` int(11) default '0',
  `textclicked` int(11) default '0',
  PRIMARY KEY  (`messageid`,`forwardid`),
  KEY `midindex` (`messageid`),
  KEY `fwdindex` (`forwardid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_linktrack_ml`
--


/*!40000 ALTER TABLE `phplist_linktrack_ml` DISABLE KEYS */;
LOCK TABLES `phplist_linktrack_ml` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_linktrack_ml` ENABLE KEYS */;

--
-- Table structure for table `phplist_linktrack_uml_click`
--

DROP TABLE IF EXISTS `phplist_linktrack_uml_click`;
CREATE TABLE `phplist_linktrack_uml_click` (
  `id` int(11) NOT NULL auto_increment,
  `messageid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `forwardid` int(11) default NULL,
  `firstclick` datetime default NULL,
  `latestclick` datetime default NULL,
  `clicked` int(11) default '0',
  `htmlclicked` int(11) default '0',
  `textclicked` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `miduidfwdid` (`messageid`,`userid`,`forwardid`),
  KEY `midindex` (`messageid`),
  KEY `uidindex` (`userid`),
  KEY `miduidindex` (`messageid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_linktrack_uml_click`
--


/*!40000 ALTER TABLE `phplist_linktrack_uml_click` DISABLE KEYS */;
LOCK TABLES `phplist_linktrack_uml_click` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_linktrack_uml_click` ENABLE KEYS */;

--
-- Table structure for table `phplist_linktrack_userclick`
--

DROP TABLE IF EXISTS `phplist_linktrack_userclick`;
CREATE TABLE `phplist_linktrack_userclick` (
  `linkid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `messageid` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `data` text,
  `date` datetime default NULL,
  KEY `linkusermessageindex` (`linkid`,`userid`,`messageid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_linktrack_userclick`
--


/*!40000 ALTER TABLE `phplist_linktrack_userclick` DISABLE KEYS */;
LOCK TABLES `phplist_linktrack_userclick` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_linktrack_userclick` ENABLE KEYS */;

--
-- Table structure for table `phplist_list`
--

DROP TABLE IF EXISTS `phplist_list`;
CREATE TABLE `phplist_list` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` text,
  `entered` datetime default NULL,
  `listorder` int(11) default NULL,
  `prefix` varchar(10) default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `active` tinyint(4) default NULL,
  `owner` int(11) default NULL,
  `rssfeed` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_list`
--


/*!40000 ALTER TABLE `phplist_list` DISABLE KEYS */;
LOCK TABLES `phplist_list` WRITE;
INSERT INTO `phplist_list` VALUES (1,'test','List for testing. I you don\'t make this list live and add yourself as a member, you can use this list to test a message. If the message comes out ok, you can resend it to other lists.','2002-04-18 22:27:28',0,'','2002-04-18 21:27:28',0,0,NULL),(2,'list 2','','2002-05-31 10:38:47',0,'','2003-08-22 20:20:51',1,1,'http://www.phplist.com/test.rss'),(3,'list 3','','2002-05-31 10:39:31',0,'','2003-03-21 11:01:33',1,0,NULL),(4,'list 4','','2002-05-31 10:40:29',0,'','2003-03-21 11:01:41',1,0,NULL),(5,'list 5','','2002-05-31 10:59:05',0,'','2003-03-21 11:01:47',1,0,NULL),(6,'list 6','','2002-05-31 11:05:39',0,'','2003-03-21 11:01:55',1,0,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_list` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_bpleaseche`
--

DROP TABLE IF EXISTS `phplist_listattr_bpleaseche`;
CREATE TABLE `phplist_listattr_bpleaseche` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_bpleaseche`
--


/*!40000 ALTER TABLE `phplist_listattr_bpleaseche` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_bpleaseche` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_bpleaseche` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_bwheredoyo`
--

DROP TABLE IF EXISTS `phplist_listattr_bwheredoyo`;
CREATE TABLE `phplist_listattr_bwheredoyo` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_bwheredoyo`
--


/*!40000 ALTER TABLE `phplist_listattr_bwheredoyo` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_bwheredoyo` WRITE;
INSERT INTO `phplist_listattr_bwheredoyo` VALUES (1,'At home',0),(2,'At work',0),(3,'At school',0),(4,'At the local library',0),(5,'At a friend&rsquo;s home',0),(6,'At the local postoffice',0),(7,'At a cybercafe',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_bwheredoyo` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_cbgroup`
--

DROP TABLE IF EXISTS `phplist_listattr_cbgroup`;
CREATE TABLE `phplist_listattr_cbgroup` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_cbgroup`
--


/*!40000 ALTER TABLE `phplist_listattr_cbgroup` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_cbgroup` WRITE;
INSERT INTO `phplist_listattr_cbgroup` VALUES (1,'option 1',0),(2,'option 2',0),(3,'option 3',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_cbgroup` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_comments`
--

DROP TABLE IF EXISTS `phplist_listattr_comments`;
CREATE TABLE `phplist_listattr_comments` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_comments`
--


/*!40000 ALTER TABLE `phplist_listattr_comments` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_comments` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_countries`
--

DROP TABLE IF EXISTS `phplist_listattr_countries`;
CREATE TABLE `phplist_listattr_countries` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_countries`
--


/*!40000 ALTER TABLE `phplist_listattr_countries` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_countries` WRITE;
INSERT INTO `phplist_listattr_countries` VALUES (1,'Andorra',0),(2,'United Arab Emirates',0),(3,'Afghanistan',0),(4,'Antigua and Barbuda',0),(5,'Anguilla',0),(6,'Albania',0),(7,'Armenia',0),(8,'Netherlands Antilles',0),(9,'Angola',0),(10,'Antarctica',0),(11,'Argentina',0),(12,'American Samoa',0),(13,'Austria',0),(14,'Australia',0),(15,'Aruba',0),(16,'Azerbaidjan',0),(17,'Bosnia-Herzegovina',0),(18,'Barbados',0),(19,'Bangladesh',0),(20,'Belgium',0),(21,'Burkina Faso',0),(22,'Bulgaria',0),(23,'Bahrain',0),(24,'Burundi',0),(25,'Benin',0),(26,'Bermuda',0),(27,'Brunei Darussalam',0),(28,'Bolivia',0),(29,'Brazil',0),(30,'Bahamas',0),(31,'Bhutan',0),(32,'Bouvet Island',0),(33,'Botswana',0),(34,'Belarus',0),(35,'Belize',0),(36,'Canada',0),(37,'Cocos (Keeling) Islands',0),(38,'Central African Republic',0),(39,'Congo',0),(40,'Switzerland',0),(41,'Ivory Coast (Cote D\'Ivoire)',0),(42,'Cook Islands',0),(43,'Chile',0),(44,'Cameroon',0),(45,'China',0),(46,'Colombia',0),(47,'Costa Rica',0),(48,'Former Czechoslovakia',0),(49,'Cuba',0),(50,'Cape Verde',0),(51,'Christmas Island',0),(52,'Cyprus',0),(53,'Czech Republic',0),(54,'Germany',0),(55,'Djibouti',0),(56,'Denmark',0),(57,'Dominica',0),(58,'Dominican Republic',0),(59,'Algeria',0),(60,'Ecuador',0),(61,'Estonia',0),(62,'Egypt',0),(63,'Western Sahara',0),(64,'Spain',0),(65,'Ethiopia',0),(66,'Finland',0),(67,'Fiji',0),(68,'Falkland Islands',0),(69,'Micronesia',0),(70,'Faroe Islands',0),(71,'France',0),(72,'France (European Territory)',0),(73,'Gabon',0),(74,'Great Britain',0),(75,'Grenada',0),(76,'Georgia',0),(77,'French Guyana',0),(78,'Ghana',0),(79,'Gibraltar',0),(80,'Greenland',0),(81,'Gambia',0),(82,'Guinea',0),(83,'Guadeloupe (French)',0),(84,'Equatorial Guinea',0),(85,'Greece',0),(86,'S. Georgia & S. Sandwich Isls.',0),(87,'Guatemala',0),(88,'Guam (USA)',0),(89,'Guinea Bissau',0),(90,'Guyana',0),(91,'Hong Kong',0),(92,'Heard and McDonald Islands',0),(93,'Honduras',0),(94,'Croatia',0),(95,'Haiti',0),(96,'Hungary',0),(97,'Indonesia',0),(98,'Ireland',0),(99,'Israel',0),(100,'India',0),(101,'British Indian Ocean Territory',0),(102,'Iraq',0),(103,'Iran',0),(104,'Iceland',0),(105,'Italy',0),(106,'Jamaica',0),(107,'Jordan',0),(108,'Japan',0),(109,'Kenya',0),(110,'Kyrgyzstan',0),(111,'Cambodia',0),(112,'Kiribati',0),(113,'Comoros',0),(114,'Saint Kitts & Nevis Anguilla',0),(115,'North Korea',0),(116,'South Korea',0),(117,'Kuwait',0),(118,'Cayman Islands',0),(119,'Kazakhstan',0),(120,'Laos',0),(121,'Lebanon',0),(122,'Saint Lucia',0),(123,'Liechtenstein',0),(124,'Sri Lanka',0),(125,'Liberia',0),(126,'Lesotho',0),(127,'Lithuania',0),(128,'Luxembourg',0),(129,'Latvia',0),(130,'Libya',0),(131,'Morocco',0),(132,'Monaco',0),(133,'Moldavia',0),(134,'Madagascar',0),(135,'Marshall Islands',0),(136,'Macedonia',0),(137,'Mali',0),(138,'Myanmar',0),(139,'Mongolia',0),(140,'Macau',0),(141,'Northern Mariana Islands',0),(142,'Martinique (French)',0),(143,'Mauritania',0),(144,'Montserrat',0),(145,'Malta',0),(146,'Mauritius',0),(147,'Maldives',0),(148,'Malawi',0),(149,'Mexico',0),(150,'Malaysia',0),(151,'Mozambique',0),(152,'Namibia',0),(153,'New Caledonia (French)',0),(154,'Niger',0),(155,'Norfolk Island',0),(156,'Nigeria',0),(157,'Nicaragua',0),(158,'Netherlands',0),(159,'Norway',0),(160,'Nepal',0),(161,'Nauru',0),(162,'Neutral Zone',0),(163,'Niue',0),(164,'New Zealand',0),(165,'Oman',0),(166,'Panama',0),(167,'Peru',0),(168,'Polynesia (French)',0),(169,'Papua New Guinea',0),(170,'Philippines',0),(171,'Pakistan',0),(172,'Poland',0),(173,'Saint Pierre and Miquelon',0),(174,'Pitcairn Island',0),(175,'Puerto Rico',0),(176,'Portugal',0),(177,'Palau',0),(178,'Paraguay',0),(179,'Qatar',0),(180,'Reunion (French)',0),(181,'Romania',0),(182,'Russian Federation',0),(183,'Rwanda',0),(184,'Saudi Arabia',0),(185,'Solomon Islands',0),(186,'Seychelles',0),(187,'Sudan',0),(188,'Sweden',0),(189,'Singapore',0),(190,'Saint Helena',0),(191,'Slovenia',0),(192,'Svalbard and Jan Mayen Islands',0),(193,'Slovak Republic',0),(194,'Sierra Leone',0),(195,'San Marino',0),(196,'Senegal',0),(197,'Somalia',0),(198,'Suriname',0),(199,'Saint Tome and Principe',0),(200,'Former USSR',0),(201,'El Salvador',0),(202,'Syria',0),(203,'Swaziland',0),(204,'Turks and Caicos Islands',0),(205,'Chad',0),(206,'French Southern Territories',0),(207,'Togo',0),(208,'Thailand',0),(209,'Tadjikistan',0),(210,'Tokelau',0),(211,'Turkmenistan',0),(212,'Tunisia',0),(213,'Tonga',0),(214,'East Timor',0),(215,'Turkey',0),(216,'Trinidad and Tobago',0),(217,'Tuvalu',0),(218,'Taiwan',0),(219,'Tanzania',0),(220,'Ukraine',0),(221,'Uganda',0),(222,'United Kingdom',0),(223,'United States',0),(224,'Uruguay',0),(225,'Uzbekistan',0),(226,'Vatican City State',0),(227,'Saint Vincent & Grenadines',0),(228,'Venezuela',0),(229,'Virgin Islands (British)',0),(230,'Virgin Islands (USA)',0),(231,'Vietnam',0),(232,'Vanuatu',0),(233,'Wallis and Futuna Islands',0),(234,'Samoa',0),(235,'Yemen',0),(236,'Mayotte',0),(237,'Yugoslavia',0),(238,'South Africa',0),(239,'Zambia',0),(240,'Zaire',0),(241,'Zimbabwe',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_countries` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_hiddenfiel`
--

DROP TABLE IF EXISTS `phplist_listattr_hiddenfiel`;
CREATE TABLE `phplist_listattr_hiddenfiel` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_hiddenfiel`
--


/*!40000 ALTER TABLE `phplist_listattr_hiddenfiel` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_hiddenfiel` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_hiddenfiel` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_iagreewith`
--

DROP TABLE IF EXISTS `phplist_listattr_iagreewith`;
CREATE TABLE `phplist_listattr_iagreewith` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_iagreewith`
--


/*!40000 ALTER TABLE `phplist_listattr_iagreewith` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_iagreewith` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_iagreewith` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_most`
--

DROP TABLE IF EXISTS `phplist_listattr_most`;
CREATE TABLE `phplist_listattr_most` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_most`
--


/*!40000 ALTER TABLE `phplist_listattr_most` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_most` WRITE;
INSERT INTO `phplist_listattr_most` VALUES (1,'At home',0),(2,'At work',0),(3,'At school',0),(4,'At the local library',0),(5,'At a friend&rsquo;s home',0),(6,'At the local postoffice',0),(7,'At a cybercafe',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_most` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_othercomme`
--

DROP TABLE IF EXISTS `phplist_listattr_othercomme`;
CREATE TABLE `phplist_listattr_othercomme` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_othercomme`
--


/*!40000 ALTER TABLE `phplist_listattr_othercomme` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_othercomme` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_othercomme` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_publickey`
--

DROP TABLE IF EXISTS `phplist_listattr_publickey`;
CREATE TABLE `phplist_listattr_publickey` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_publickey`
--


/*!40000 ALTER TABLE `phplist_listattr_publickey` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_publickey` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_publickey` ENABLE KEYS */;

--
-- Table structure for table `phplist_listattr_somemoreco`
--

DROP TABLE IF EXISTS `phplist_listattr_somemoreco`;
CREATE TABLE `phplist_listattr_somemoreco` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `listorder` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listattr_somemoreco`
--


/*!40000 ALTER TABLE `phplist_listattr_somemoreco` DISABLE KEYS */;
LOCK TABLES `phplist_listattr_somemoreco` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listattr_somemoreco` ENABLE KEYS */;

--
-- Table structure for table `phplist_listmessage`
--

DROP TABLE IF EXISTS `phplist_listmessage`;
CREATE TABLE `phplist_listmessage` (
  `id` int(11) NOT NULL auto_increment,
  `messageid` int(11) NOT NULL default '0',
  `listid` int(11) NOT NULL default '0',
  `entered` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `messageid` (`messageid`,`listid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listmessage`
--


/*!40000 ALTER TABLE `phplist_listmessage` DISABLE KEYS */;
LOCK TABLES `phplist_listmessage` WRITE;
INSERT INTO `phplist_listmessage` VALUES (3,2,1,'2002-05-31 09:57:18','2002-05-31 08:57:18'),(4,3,0,'2002-05-31 11:57:44','2002-05-31 10:57:44'),(24,11,6,'2002-05-31 12:26:44','2002-05-31 11:26:44'),(25,0,2,'2002-05-31 12:30:39','2002-05-31 11:30:39'),(26,0,3,'2002-05-31 12:30:39','2002-05-31 11:30:39'),(27,26,4,'2002-05-31 12:30:39','2002-05-31 11:30:39'),(28,26,5,'2002-05-31 12:30:39','2002-05-31 11:30:39'),(29,28,6,'2002-05-31 12:30:39','2002-05-31 11:30:39'),(42,14,1,'2003-03-21 12:45:01','2003-03-21 12:45:01'),(43,15,2,'2003-10-20 01:11:55','2003-10-20 00:11:55'),(44,15,3,'2003-10-20 01:11:55','2003-10-20 00:11:55'),(45,15,4,'2003-10-20 01:11:55','2003-10-20 00:11:55'),(46,15,5,'2003-10-20 01:11:55','2003-10-20 00:11:55'),(47,15,6,'2003-10-20 01:11:55','2003-10-20 00:11:55'),(48,18,3,'2005-09-08 17:00:54','2005-09-08 16:00:54'),(49,18,4,'2005-09-08 17:00:54','2005-09-08 16:00:54'),(50,18,5,'2005-09-08 17:00:54','2005-09-08 16:00:54');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listmessage` ENABLE KEYS */;

--
-- Table structure for table `phplist_listrss`
--

DROP TABLE IF EXISTS `phplist_listrss`;
CREATE TABLE `phplist_listrss` (
  `listid` int(11) NOT NULL default '0',
  `type` varchar(255) default NULL,
  `entered` datetime default NULL,
  `info` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listrss`
--


/*!40000 ALTER TABLE `phplist_listrss` DISABLE KEYS */;
LOCK TABLES `phplist_listrss` WRITE;
INSERT INTO `phplist_listrss` VALUES (2,'retrieval','2003-08-22 21:19:19','Parsing http://www.phplist.com/forums/rdf.php ok\n15 items, 15 new items\n'),(2,'retrieval','2003-08-22 21:21:11','Parsing http://www.phplist.com/test.rss ok\n15 items, 15 new items\n'),(2,'retrieval','2003-08-22 21:22:05','Parsing http://www.phplist.com/test.rss ok\n15 items, 15 new items\n'),(2,'retrieval','2003-08-22 21:22:56','Parsing http://www.phplist.com/test.rss ok\n7 items, 7 new items\n');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listrss` ENABLE KEYS */;

--
-- Table structure for table `phplist_listuser`
--

DROP TABLE IF EXISTS `phplist_listuser`;
CREATE TABLE `phplist_listuser` (
  `userid` int(11) NOT NULL default '0',
  `listid` int(11) NOT NULL default '0',
  `entered` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`userid`,`listid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_listuser`
--


/*!40000 ALTER TABLE `phplist_listuser` DISABLE KEYS */;
LOCK TABLES `phplist_listuser` WRITE;
INSERT INTO `phplist_listuser` VALUES (1,1,NULL,'2005-09-08 15:37:06'),(3,1,NULL,'2006-12-13 01:47:59'),(4,1,NULL,'2005-09-08 15:38:14'),(1,6,NULL,'2005-09-08 15:37:06'),(1,5,NULL,'2005-09-08 15:37:06'),(5,1,NULL,'2005-09-08 15:39:11'),(3,6,NULL,'2006-12-13 01:47:59'),(5,6,NULL,'2005-09-08 15:39:11'),(5,2,NULL,'2005-09-08 15:39:11'),(1,4,NULL,'2005-09-08 15:37:06'),(1,2,NULL,'2005-09-08 15:37:06'),(8,6,NULL,'2005-09-08 15:43:47'),(8,5,NULL,'2005-09-08 15:43:47');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_listuser` ENABLE KEYS */;

--
-- Table structure for table `phplist_message`
--

DROP TABLE IF EXISTS `phplist_message`;
CREATE TABLE `phplist_message` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) NOT NULL default '',
  `fromfield` varchar(255) NOT NULL default '',
  `tofield` varchar(255) NOT NULL default '',
  `replyto` varchar(255) NOT NULL default '',
  `message` text,
  `footer` text,
  `entered` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` varchar(255) default NULL,
  `processed` mediumint(8) unsigned default '0',
  `userselection` text,
  `sent` datetime default NULL,
  `htmlformatted` tinyint(4) default '0',
  `sendformat` varchar(20) default NULL,
  `template` int(11) default NULL,
  `astext` int(11) default '0',
  `ashtml` int(11) default '0',
  `astextandhtml` int(11) default '0',
  `viewed` int(11) default '0',
  `bouncecount` int(11) default '0',
  `sendstart` datetime default NULL,
  `aspdf` int(11) default '0',
  `astextandpdf` int(11) default '0',
  `rsstemplate` varchar(100) default NULL,
  `owner` int(11) default NULL,
  `embargo` datetime default NULL,
  `repeatinterval` int(11) default '0',
  `repeatuntil` datetime default NULL,
  `textmessage` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_message`
--


/*!40000 ALTER TABLE `phplist_message` DISABLE KEYS */;
LOCK TABLES `phplist_message` WRITE;
INSERT INTO `phplist_message` VALUES (2,'Demo','Demo','','','\r\n<b>Hello [NAME]</b><br/><br/>\r\nThis is a test message</br>\r\n\r\n','--\r\nTo unsubscribe from this list visit [UNSUBSCRIBE]\r\n\r\nTo update your preferences visit [PREFERENCES]\r\n','2002-05-31 09:57:18','2002-05-31 08:57:23','sent',4,'select distinct userid from user_attribute','2002-05-31 09:57:23',1,'HTML',1,1,3,0,0,0,NULL,0,0,NULL,NULL,NULL,0,NULL,NULL),(3,'Prepared Message','Prepared Message Demo','','','\r\n<b>Hello [NAME]</b><br/>\r\n<br/>\r\n\r\nThis is a Demo message',' ','2002-05-31 11:57:44','2002-05-31 10:57:44','prepared',0,'select distinct userid from phplist_user_user_attribute',NULL,1,'both',1,0,0,0,0,0,NULL,0,0,NULL,NULL,NULL,0,NULL,NULL),(11,'message 2','me','','','hi\n##LISTOWNER=4','--\r\nTo unsubscribe from this list visit [UNSUBSCRIBE]\r\n\r\nTo update your preferences visit [PREFERENCES]\r\n','2002-05-31 12:26:44','2002-08-09 16:38:45','sent',1,NULL,'2002-08-09 17:38:45',0,'both',1,0,0,1,0,0,NULL,0,0,NULL,NULL,NULL,0,NULL,NULL),(14,'Demo Message','Demo User','','','\r\nHi [NAME]\r\n\r\nIt is now possible to add configuration variables to the messages. Something like this:\r\n\r\nthe reports go to [report_address]\r\n\r\nour website is at [website]\r\n\r\nour domain is [domain]\r\n\r\n','--\r\nTo unsubscribe from this list visit [UNSUBSCRIBE]\r\n\r\nTo update your preferences visit [PREFERENCES]\r\n','2003-03-21 12:45:01','2003-03-21 12:45:40','sent',3,'select distinct userid from phplist_user_user_attribute','2003-03-21 12:45:06',0,'both',2,0,0,3,5,0,'2003-03-21 12:45:04',0,0,NULL,NULL,NULL,0,NULL,NULL),(15,'Your daily email','Webmaster noreply@phplist.michiel','','','\r\nThese are the latest entries:\r\n[RSS]','--\r\nTo unsubscribe from this list visit [UNSUBSCRIBE]\r\n\r\nTo update your preferences visit [PREFERENCES]\r\n','2003-10-20 01:11:55','2005-09-08 14:16:11','sent',3,'select distinct userid from phplist_user_user_attribute','2005-09-08 15:16:11',0,'text and HTML',2,0,0,0,0,0,'2005-09-08 15:16:04',0,0,'daily',1,'2004-03-04 19:03:22',0,NULL,NULL),(18,'Test Message','Webmaster noreply@mydomain.com','','','<br />Hello [NAME]<br /><br />This is a test message. We are using <a href=\"http://www.phplist.com\">phplist.</a><br />Thanks, cheers','--\r\nTo unsubscribe from this list visit [UNSUBSCRIBE]\r\n\r\nTo update your preferences visit [PREFERENCES]\r\n','2005-09-08 16:47:44','2005-09-08 16:00:54','submitted',0,NULL,NULL,1,'text and HTML',1,0,0,0,0,0,NULL,0,0,'',1,'2005-09-08 16:47:00',0,'2005-09-08 16:47:44',''),(19,'(no subject)','','','',NULL,NULL,'2006-12-14 00:49:27','2006-12-14 00:49:27','draft',0,NULL,NULL,0,'text and HTML',0,0,0,0,0,0,NULL,0,0,NULL,1,'2006-12-14 00:49:27',0,'2006-12-14 00:49:27',NULL),(20,'(no subject)','','','',NULL,NULL,'2006-12-14 01:01:29','2006-12-14 01:01:29','draft',0,NULL,NULL,0,'text and HTML',0,0,0,0,0,0,NULL,0,0,NULL,1,'2006-12-14 01:01:29',0,'2006-12-14 01:01:29',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_message` ENABLE KEYS */;

--
-- Table structure for table `phplist_message_attachment`
--

DROP TABLE IF EXISTS `phplist_message_attachment`;
CREATE TABLE `phplist_message_attachment` (
  `id` int(11) NOT NULL auto_increment,
  `messageid` int(11) NOT NULL default '0',
  `attachmentid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_message_attachment`
--


/*!40000 ALTER TABLE `phplist_message_attachment` DISABLE KEYS */;
LOCK TABLES `phplist_message_attachment` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_message_attachment` ENABLE KEYS */;

--
-- Table structure for table `phplist_messagedata`
--

DROP TABLE IF EXISTS `phplist_messagedata`;
CREATE TABLE `phplist_messagedata` (
  `name` varchar(100) NOT NULL default '',
  `id` int(11) NOT NULL default '0',
  `data` text,
  PRIMARY KEY  (`name`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_messagedata`
--


/*!40000 ALTER TABLE `phplist_messagedata` DISABLE KEYS */;
LOCK TABLES `phplist_messagedata` WRITE;
INSERT INTO `phplist_messagedata` VALUES ('to process',15,'0'),('ETA',15,'Thu 8 Sep 15:16'),('msg/hr',15,'0');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_messagedata` ENABLE KEYS */;

--
-- Table structure for table `phplist_rssitem`
--

DROP TABLE IF EXISTS `phplist_rssitem`;
CREATE TABLE `phplist_rssitem` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `link` varchar(100) NOT NULL default '',
  `source` varchar(255) default NULL,
  `list` int(11) default NULL,
  `added` datetime default NULL,
  `processed` mediumint(8) unsigned default '0',
  `astext` int(11) default '0',
  `ashtml` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `title` (`title`,`link`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_rssitem`
--


/*!40000 ALTER TABLE `phplist_rssitem` DISABLE KEYS */;
LOCK TABLES `phplist_rssitem` WRITE;
INSERT INTO `phplist_rssitem` VALUES (1,'What is PHPlist','http://tincan.co.uk/phplist','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(2,'PHPlist features','http://tincan.co.uk/?lid=453','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(3,'How to install PHPlist','http://tincan.co.uk/?lid=295','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(4,'How to configure PHPlist','http://tincan.co.uk/?lid=652','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(5,'Download','http://tincan.co.uk/?lid=301','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(6,'PHPlist support','http://tincan.co.uk/?lid=306','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0),(7,'PHPlist demo','http://tincan.co.uk/?lid=293','http://www.phplist.com/test.rss',2,'2003-08-22 21:22:56',0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_rssitem` ENABLE KEYS */;

--
-- Table structure for table `phplist_rssitem_data`
--

DROP TABLE IF EXISTS `phplist_rssitem_data`;
CREATE TABLE `phplist_rssitem_data` (
  `itemid` int(11) NOT NULL default '0',
  `tag` varchar(100) NOT NULL default '',
  `data` text,
  PRIMARY KEY  (`itemid`,`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_rssitem_data`
--


/*!40000 ALTER TABLE `phplist_rssitem_data` DISABLE KEYS */;
LOCK TABLES `phplist_rssitem_data` WRITE;
INSERT INTO `phplist_rssitem_data` VALUES (1,'title','What is PHPlist'),(1,'link','http://tincan.co.uk/phplist'),(1,'description','      What is PHPlist, overview.'),(2,'title','PHPlist features'),(2,'link','http://tincan.co.uk/?lid=453'),(2,'description','    A full list of features of PHPlist.'),(3,'title','How to install PHPlist'),(3,'link','http://tincan.co.uk/?lid=295'),(3,'description','		Installation instructions of PHPlist.'),(4,'title','How to configure PHPlist'),(4,'link','http://tincan.co.uk/?lid=652'),(4,'description','		Details on how to adapt PHPlist to fit into your website.'),(5,'title','Download'),(5,'link','http://tincan.co.uk/?lid=301'),(5,'description','		Download PHPlist to your own computer.'),(6,'title','PHPlist support'),(6,'link','http://tincan.co.uk/?lid=306'),(6,'description','    Explains how to get some help with working with PHPlist.'),(7,'title','PHPlist demo'),(7,'link','http://tincan.co.uk/?lid=293'),(7,'description','		Try PHPlist for yourself');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_rssitem_data` ENABLE KEYS */;

--
-- Table structure for table `phplist_rssitem_user`
--

DROP TABLE IF EXISTS `phplist_rssitem_user`;
CREATE TABLE `phplist_rssitem_user` (
  `itemid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `entered` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`itemid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_rssitem_user`
--


/*!40000 ALTER TABLE `phplist_rssitem_user` DISABLE KEYS */;
LOCK TABLES `phplist_rssitem_user` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_rssitem_user` ENABLE KEYS */;

--
-- Table structure for table `phplist_sendprocess`
--

DROP TABLE IF EXISTS `phplist_sendprocess`;
CREATE TABLE `phplist_sendprocess` (
  `id` int(11) NOT NULL auto_increment,
  `started` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `alive` int(11) default '1',
  `ipaddress` varchar(50) default NULL,
  `page` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_sendprocess`
--


/*!40000 ALTER TABLE `phplist_sendprocess` DISABLE KEYS */;
LOCK TABLES `phplist_sendprocess` WRITE;
INSERT INTO `phplist_sendprocess` VALUES (1,'2002-05-31 09:53:52','2002-05-31 08:53:57',0,'127.0.0.1',NULL),(2,'2002-05-31 09:57:19','2002-05-31 08:57:23',0,'127.0.0.1',NULL),(3,'2002-05-31 11:58:23','2002-05-31 10:58:25',0,'127.0.0.1',NULL),(4,'2002-05-31 11:58:51','2002-05-31 10:58:55',0,'127.0.0.1',NULL),(5,'2002-05-31 11:59:19','2002-05-31 10:59:19',0,'127.0.0.1',NULL),(6,'2002-05-31 12:24:04','2002-05-31 11:24:04',0,'127.0.0.1',NULL),(7,'2002-05-31 12:26:02','2002-05-31 11:26:02',0,'127.0.0.1',NULL),(8,'2002-05-31 12:31:08','2002-05-31 11:31:10',0,'127.0.0.1',NULL),(9,'2002-05-31 12:32:26','2002-05-31 11:32:26',0,'127.0.0.1',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_sendprocess` ENABLE KEYS */;

--
-- Table structure for table `phplist_subscribepage`
--

DROP TABLE IF EXISTS `phplist_subscribepage`;
CREATE TABLE `phplist_subscribepage` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `active` tinyint(4) default '0',
  `owner` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_subscribepage`
--


/*!40000 ALTER TABLE `phplist_subscribepage` DISABLE KEYS */;
LOCK TABLES `phplist_subscribepage` WRITE;
INSERT INTO `phplist_subscribepage` VALUES (1,'Name Only',1,NULL),(2,'Everything',1,1),(3,'Email Only',1,1),(4,'Encryption',1,1),(5,'French',1,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_subscribepage` ENABLE KEYS */;

--
-- Table structure for table `phplist_subscribepage_data`
--

DROP TABLE IF EXISTS `phplist_subscribepage_data`;
CREATE TABLE `phplist_subscribepage_data` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `data` text,
  PRIMARY KEY  (`id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_subscribepage_data`
--


/*!40000 ALTER TABLE `phplist_subscribepage_data` DISABLE KEYS */;
LOCK TABLES `phplist_subscribepage_data` WRITE;
INSERT INTO `phplist_subscribepage_data` VALUES (1,'title','Name Only'),(1,'intro','Subscribe to one or more of our mailing lists using the form below'),(1,'header','<link href=\"styles/phplist.css\" type=text/css rel=stylesheet>\r\n</head>\r\n<body bgcolor=\"#ffffff\" background=\"images/bg.png\">\r\n<a name=\"top\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<table cellspacing=0 cellpadding=0 width=708 border=\"0\" bgcolor=\"#ffffff\">\r\n\r\n<tr valign=\"top\">\r\n<td height=\"41\" background=\"images/top01.png\" colspan=\"2\">\r\n<span class=\"phphead\"><a href=\"http://www.phplist.com\" class=\"phphead\"><b>PHP</b>list</a></span></td>\r\n<td background=\"images/top02.png\" align=\"left\">\r\n<font size=\"-2\">&nbsp;<i>powered by:\r\n</i><br />&nbsp;<b>[<a class=\"powered\" href=\"http://www.php.net/\" target=\"_new\"><i>PHP</i></a>]</b> + <b>[<a class=\"powered\" href=\"http://www.mysql.com/\" target=\"_new\">mySQL</a>]</b></font></td>\r\n</tr>\r\n\r\n<tr valign=\"bottom\">\r\n<td><img src=\"images/top03a.png\" width=20 height=34 alt=\"\" border=\"0\"></td>\r\n<td background=\"images/top03b.png\" height=\"34\"><!--hello <b>ben</b>:&nbsp;<a class=\"urhere\" href=\"\">you are here &gt; main admin</a>-->\r\n<td width=\"132\" valign=\"bottom\" background=\"images/top04.png\"><span class=\"webblermenu\">PHPlist</span></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=20 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=576 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=132 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>&nbsp;</td>\r\n<td>\r\n<br />\r\n'),(1,'footer','</td>\r\n<td>\r\n&nbsp;</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n<!-- main page content-->\r\n\r\n<!-- end of main page content--><!-- bottom black line-->\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?=VERSION?></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n</body></html>\r\n'),(1,'button','Subscribe to the Selected Mailinglists'),(1,'htmlchoice','radiohtml'),(1,'attribute001','1######1###1'),(1,'attributes','1+'),(1,'lists','3,4,5,6'),(3,'htmlchoice','htmlonly'),(3,'emaildoubleentry','yes'),(2,'lists','2,3,4,5,6'),(2,'attributes','1+2+3+4+5+6+10+9+11+12+'),(2,'attribute012','12######10###1'),(2,'attribute011','11###Please use this area to describe how you think that the persons who make open source software actually make a living, if you didn\'t pay them any money?###9###1'),(2,'attribute009','9######8###1'),(2,'attribute010','10######7###'),(2,'attribute006','6###Checked###6###'),(2,'attribute005','5###Checked###5###'),(2,'attribute004','4###Checked###4###'),(3,'title','Email Only'),(3,'intro','Subscribe to one or more of our mailing lists using the form below'),(3,'header','<link href=\"styles/phplist.css\" type=text/css rel=stylesheet>\r\n</head>\r\n<body bgcolor=\"#ffffff\">\r\n<a name=\"top\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<table cellspacing=0 cellpadding=0 width=708 border=\"0\" bgcolor=\"#ffffff\">\r\n\r\n<tr valign=\"top\">\r\n<td height=\"41\" background=\"images/top01.png\" colspan=\"2\">\r\n<span class=\"phphead\"><a href=\"http://www.phplist.com\" class=\"phphead\"><b>PHP</b>list</a></span></td>\r\n<td background=\"images/top02.png\" align=\"left\">\r\n<font size=\"-2\">&nbsp;<i>powered by:\r\n</i><br />&nbsp;<b>[<a class=\"powered\" href=\"http://www.php.net/\" target=\"_new\"><i>PHP</i></a>]</b> + <b>[<a class=\"powered\" href=\"http://www.mysql.com/\" target=\"_new\">mySQL</a>]</b></font></td>\r\n</tr>\r\n\r\n<tr valign=\"bottom\">\r\n<td><img src=\"images/top03a.png\" width=20 height=34 alt=\"\" border=\"0\"></td>\r\n<td background=\"images/top03b.png\" height=\"34\"><!--hello <b>ben</b>:&nbsp;<a class=\"urhere\" href=\"\">you are here &gt; main admin</a>-->\r\n<td width=\"132\" valign=\"bottom\" background=\"images/top04.png\"><span class=\"webblermenu\">PHPlist</span></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=20 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=576 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=132 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>&nbsp;</td>\r\n<td>\r\n<br />\r\n'),(3,'footer','</td>\r\n<td>\r\n&nbsp;</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n<!-- main page content-->\r\n\r\n<!-- end of main page content--><!-- bottom black line-->\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?=VERSION?></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n</body></html>\r\n'),(3,'thankyoupage',''),(3,'button','Subscribe to the Selected Mailinglists'),(2,'footer','</td>\r\n<td>\r\n&nbsp;</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n<!-- main page content-->\r\n\r\n<!-- end of main page content--><!-- bottom black line-->\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?=VERSION?></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n</body></html>\r\n'),(2,'thankyoupage',''),(2,'button','Subscribe to the Selected Mailinglists'),(2,'htmlchoice','checkforhtml'),(2,'emaildoubleentry',''),(2,'attribute001','1######1###1'),(2,'attribute002','2###Netherlands###2###1'),(2,'attribute003','3######3###'),(2,'header','<link href=\"styles/phplist.css\" type=text/css rel=stylesheet>\r\n</head>\r\n<body bgcolor=\"#ffffff\" background=\"images/bg.png\">\r\n<a name=\"top\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<table cellspacing=0 cellpadding=0 width=708 border=\"0\" bgcolor=\"#ffffff\">\r\n\r\n<tr valign=\"top\">\r\n<td height=\"41\" colspan=\"2\">\r\n<span class=\"phphead\"><a href=\"http://www.phplist.com\" class=\"phphead\"><b>PHP</b>list</a></span></td>\r\n<td align=\"left\">\r\n<font size=\"-2\">&nbsp;<i>powered by:\r\n</i><br />&nbsp;<b>[<a class=\"powered\" href=\"http://www.php.net/\" target=\"_new\"><i>PHP</i></a>]</b> + <b>[<a class=\"powered\" href=\"http://www.mysql.com/\" target=\"_new\">mySQL</a>]</b></font></td>\r\n</tr>\r\n\r\n<tr valign=\"bottom\">\r\n<td></td>\r\n<td height=\"34\"><td width=\"132\" valign=\"bottom\" background=\"images/top04.png\"><span class=\"webblermenu\">PHPlist</span></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=20 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=576 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=132 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\">\r\n<td>&nbsp;</td>\r\n<td>\r\n<br />\r\n'),(2,'intro','Subscribe to one or more of our mailing lists using the form below'),(2,'title','Everything'),(3,'attributes',''),(3,'lists','2,3,4,5,6'),(4,'title','Encryption'),(4,'language_file','english-usa.inc'),(4,'intro','Subscribe to one or more of our newsletters using the form below\r\n\r\nPlease make sure to upload your public key to receive the emails encrypted.'),(4,'header','<link href=\"styles/phplist.css\" type=\"text/css\" rel=\"stylesheet\">\r\n</head>\r\n<body bgcolor=\"#ffffff\" background=\"images/bg.png\">\r\n<a name=\"top\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n<tr>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\" align=\"left\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<TABLE cellSpacing=0 cellPadding=0 width=708 bgColor=#ffffff border=0>\r\n  <TR vAlign=top>\r\n    <TD colSpan=2 rowspan=\"2\" height=\"63\" background=\"images/topstrip.png\"><a href=\"http://www.phplist.com\" target=\"_blank\"><img src=\"images/masthead.png\" border=0 width=577 height=75></a></TD>\r\n    <TD align=left\r\n      background=\"images/topstrip.png\" bgcolor=\"#F0D1A3\"><FONT\r\n      size=-2>&nbsp;<I>powered by: </I><BR>&nbsp;<B>[<A class=powered\r\n      href=\"http://www.php.net/\" target=_new><I>PHP</I></A>]</B> + <B>[<A\r\n      class=powered href=\"http://www.mysql.com/\"\r\n      target=_new>mySQL</A>]</B></FONT></TD></TR>\r\n  <TR vAlign=bottom>\r\n    <TD vAlign=bottom width=132\r\n    background=\"images/topright.png\" bgcolor=\"#F0D1A3\"><SPAN\r\n      class=webblermenu>PHPlist</SPAN></TD></TR>\r\n  <TR>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=20\r\n      border=0></TD>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=576\r\n      border=0></TD>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=132\r\n      border=0></TD></TR>\r\n  <TR vAlign=top>\r\n    <TD>&nbsp;</TD>\r\n<td><div align=left>\r\n<br />\r\n'),(4,'footer','</div>\r\n</td>\r\n<td>\r\n<div class=\"menutableright\">\r\n\r\n</div>\r\n</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?php echo VERSION?></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n</body></html>\r\n'),(4,'thankyoupage','<h3>Thank you for subscribing to our newsletters.</h3>\r\nYour email has been added to our system. You will be e-mailed shortly with a request to confirm your membership. Please make sure to click the link in that message to confirm your subscription.'),(4,'button','Subscribe to the Selected Newsletters'),(4,'htmlchoice','htmlonly'),(4,'emaildoubleentry','yes'),(4,'attribute014','14######4###1'),(4,'attribute001','1######1###1'),(4,'attribute002','2###Netherlands###3###1'),(4,'attribute013','13######2###1'),(4,'attributes','14+1+2+13+'),(4,'lists','3,4'),(5,'title','French'),(5,'language_file','french.inc'),(5,'intro','Subscribe to one or more of our newsletters using the form below'),(5,'header','<link href=\"styles/phplist.css\" type=\"text/css\" rel=\"stylesheet\">\r\n</head>\r\n<body bgcolor=\"#ffffff\" background=\"images/bg.png\">\r\n<a name=\"top\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n<tr>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=708 border=0></td>\r\n<td bgcolor=\"#000000\" rowspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\"top\" align=\"left\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<TABLE cellSpacing=0 cellPadding=0 width=708 bgColor=#ffffff border=0>\r\n  <TR vAlign=top>\r\n    <TD colSpan=2 rowspan=\"2\" height=\"63\" background=\"images/topstrip.png\"><a href=\"http://www.phplist.com\" target=\"_blank\"><img src=\"images/masthead.png\" border=0 width=577 height=75></a></TD>\r\n    <TD align=left\r\n      background=\"images/topstrip.png\" bgcolor=\"#F0D1A3\"><FONT\r\n      size=-2>&nbsp;<I>powered by: </I><BR>&nbsp;<B>[<A class=powered\r\n      href=\"http://www.php.net/\" target=_new><I>PHP</I></A>]</B> + <B>[<A\r\n      class=powered href=\"http://www.mysql.com/\"\r\n      target=_new>mySQL</A>]</B></FONT></TD></TR>\r\n  <TR vAlign=bottom>\r\n    <TD vAlign=bottom width=132\r\n    background=\"images/topright.png\" bgcolor=\"#F0D1A3\"><SPAN\r\n      class=webblermenu>PHPlist</SPAN></TD></TR>\r\n  <TR>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=20\r\n      border=0></TD>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=576\r\n      border=0></TD>\r\n    <TD bgColor=#000000><IMG height=1 alt=\"\"\r\n      src=\"images/transparent.png\" width=132\r\n      border=0></TD></TR>\r\n  <TR vAlign=top>\r\n    <TD>&nbsp;</TD>\r\n<td><div align=left>\r\n<br />\r\n'),(5,'footer','</div>\r\n</td>\r\n<td>\r\n<div class=\"menutableright\">\r\n\r\n</div>\r\n</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\"4\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n<td bgcolor=\"#ff9900\" class=\"bottom\">&copy; <a href=\"http://tincan.co.uk\" target=\"_tincan\" class=\"urhere\">tincan limited</a> | <a class=\"urhere\" href=\"http://www.phplist.com\" target=\"_blank\">phplist</a> - version <?php echo VERSION?></td>\r\n<td bgcolor=\"#000000\"><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\"#000000\" colspan=3><img height=1 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3><img height=3 alt=\"\" src=\"images/transparent.png\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n</body></html>\r\n'),(5,'thankyoupage','<h3>Thank you for subscribing to our newsletters.</h3>\r\nYour email has been added to our system. You will be e-mailed shortly with a request to confirm your membership. Please make sure to click the link in that message to confirm your subscription.'),(5,'button','Subscribe to the Selected Newsletters'),(5,'htmlchoice','checkforhtml'),(5,'emaildoubleentry','yes'),(5,'attribute001','1######1###1'),(5,'attribute013','13######2###1'),(5,'attributes','1+13+'),(5,'lists','3,4');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_subscribepage_data` ENABLE KEYS */;

--
-- Table structure for table `phplist_task`
--

DROP TABLE IF EXISTS `phplist_task`;
CREATE TABLE `phplist_task` (
  `id` int(11) NOT NULL auto_increment,
  `page` varchar(25) default NULL,
  `type` varchar(25) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `page` (`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_task`
--


/*!40000 ALTER TABLE `phplist_task` DISABLE KEYS */;
LOCK TABLES `phplist_task` WRITE;
INSERT INTO `phplist_task` VALUES (1,'adminattributes','system'),(2,'attributes','system'),(3,'upgrade','system'),(4,'configure','system'),(5,'defaultconfig','system'),(6,'defaults','system'),(7,'initialise','system'),(8,'list','list'),(9,'editlist','list'),(10,'members','list'),(11,'user','user'),(12,'users','user'),(13,'dlusers','user'),(14,'editattributes','user'),(15,'import1','user'),(16,'import2','user'),(17,'import','user'),(18,'message','message'),(19,'messages','message'),(20,'processqueue','message'),(21,'send','message'),(22,'preparesend','message'),(23,'sendprepared','message'),(24,'template','message'),(25,'templates','message'),(26,'admins','admin'),(27,'admin','admin'),(28,'export','user'),(30,'bounce','system'),(31,'bounces','system'),(32,'processbounces','system'),(33,'spage','system'),(34,'spageedit','system'),(35,'import3','user'),(36,'import4','user'),(49,'eventlog','system'),(50,'reconcileusers','system'),(51,'getrss','system'),(52,'viewrss','system'),(53,'setup','system'),(245,'owner','admin'),(246,'purgerss','system'),(247,'dbcheck','system'),(248,'usercheck','user'),(249,'massunconfirm','user'),(250,'statsmgt','clickstats'),(251,'mclicks','clickstats'),(252,'uclicks','clickstats'),(253,'userclicks','clickstats'),(254,'mviews','clickstats'),(255,'statsoverview','clickstats');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_task` ENABLE KEYS */;

--
-- Table structure for table `phplist_template`
--

DROP TABLE IF EXISTS `phplist_template`;
CREATE TABLE `phplist_template` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `template` longblob,
  `listorder` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_template`
--


/*!40000 ALTER TABLE `phplist_template` DISABLE KEYS */;
LOCK TABLES `phplist_template` WRITE;
INSERT INTO `phplist_template` VALUES (1,'Old Phplist design','<!DOCTYPE HTML PUBLIC \\\"-//W3C//DTD HTML 4.0 Transitional//EN\\\">\r\n\r\n<html>\r\n<head>\r\n<meta http-equiv=\\\"Content-Type\\\" content=\\\"text/html; charset=ISO-8859-1\\\">\r\n<STYLE TYPE=\\\"text/css\\\">\r\n<!--\r\n	H1 {font-family: Arial, Helvetica; font-size: 14pt}\r\n	BODY {font-family: Arial, Helvetica, sans-serif; font-style: normal; font-size: 12pt; margin-left: 30px}\r\n	A  {font-weight : bold;color : black;	text-decoration : none;}\r\n	A:Visited  {font-weight : bold;	color : black;	text-decoration : none;}\r\n	A:Active  {font-weight : bold;color : black;	text-decoration : none;}\r\n	A:Hover  {font-weight : bold; color : orange;	text-decoration : none;}\r\n	.url  {font-weight : bold;}\r\n	.botbar  {font-size : 11px;font-weight : bold;color : white;}\r\n	// -->\r\n</STYLE>\r\n\r\n\r\n</head>\r\n<body bgcolor=#FFFFFF>\r\n<div align=\\\"center\\\">\r\n<!--TOP TABLE starts-->\r\n<table width=627 border=0 cellpadding=0 cellspacing=0>\r\n<tr>\r\n<td><img src=\\\"spacer.png\\\" width=57 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=96 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=135 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=45 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=212 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=13 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=34 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=8 height=1></td>\r\n<td><img src=\\\"spacer.png\\\" width=27 height=1></td>\r\n</tr>\r\n<tr>\r\n<td><img src=\\\"01.png\\\" width=57 height=29></td>\r\n<td><img src=\\\"02.png\\\" width=96 height=29></td>\r\n<td><img src=\\\"03.png\\\" width=135 height=29></td>\r\n<!--PHP link-->\r\n<td><a href=\\\"http://www.php.net\\\" target=\\\"_blank\\\"><img src=\\\"04.png\\\" width=45 height=29 border=\\\"0\\\" alt=\\\"php3\\\"></a></td>\r\n<td><img src=\\\"05.png\\\" width=212 height=29></td>\r\n<!--mySQL link-->\r\n<td colspan=3><a href=\\\"http://www.mysql.com\\\" target=\\\"_blank\\\"><img src=\\\"06.png\\\" width=55 height=29 border=\\\"0\\\" alt=\\\"mySQL\\\"></a></td>\r\n<td><img src=\\\"07.png\\\" width=27 height=29></td>\r\n</tr>\r\n<tr>\r\n<td><img src=\\\"08.png\\\" width=57 height=24></td>\r\n<td><img src=\\\"09.png\\\" width=96 height=24></td>\r\n<td><a href=\\\"http://www.phplist.com\\\"><img src=\\\"10.png\\\" width=135 height=24 border=0></a></td>\r\n<td><a href=\\\"http://www.phplist.com\\\"><img src=\\\"11.png\\\" width=45 height=24 border=0></a></td>\r\n<td colspan=2><img src=\\\"12.png\\\" width=225 height=24></td>\r\n<!--HOME button here-->\r\n<td><a href=\\\"/\\\"><img src=\\\"13.png\\\" width=34 height=24 border=\\\"0\\\" alt=\\\"home\\\"></a></td>\r\n<td colspan=2><img src=\\\"14.png\\\" width=35 height=24></td>\r\n</tr>\r\n<tr>\r\n<td><img src=\\\"15.png\\\" width=57 height=29></td>\r\n<td><img src=\\\"16.png\\\" width=96 height=29></td>\r\n<td><img src=\\\"17.png\\\" width=135 height=29></td>\r\n<td><img src=\\\"18.png\\\" width=45 height=29></td>\r\n<td><img src=\\\"19.png\\\" width=212 height=29></td>\r\n<td colspan=3><img src=\\\"20.png\\\" width=55 height=29></td>\r\n<td><img src=\\\"21.png\\\" width=27 height=29></td>\r\n</tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n<!--MAIN TABLE starts-->\r\n<table width=627 border=0 cellpadding=0 cellspacing=0>\r\n<tr valign=\\\"top\\\">\r\n<td background=\\\"leftside.png\\\"><img src=\\\"bigcan.png\\\" width=57 height=102 hspace=0 vspace=0 border=0 alt=\\\"\\\"></td>\r\n<td>\r\n<!--INNER TABLE holding nav and main body starts-->\r\n<table width=543 border=0 cellpadding=0 cellspacing=0>\r\n<tr valign=\\\"top\\\">\r\n<td bgcolor=\\\"#FFCC66\\\" class=\\\"menuitem\\\"><img src=\\\"spacer.png\\\" width=96 height=1></td>\r\n<td bgcolor=\\\"#ffffff\\\"><img src=\\\"spacer.png\\\" width=10 height=1></td>\r\n<td bgcolor=\\\"#ffffff\\\"><img src=\\\"spacer.png\\\" width=437 height=1></td>\r\n</tr>\r\n<tr valign=\\\"top\\\">\r\n<!--MENU HEAD and LIST goes in here-->\r\n<td bgcolor=\\\"#FFCC66\\\" align=\\\"right\\\" class=\\\"menuitem\\\"><span class=\\\"menuhead\\\"><!--Menu header-->&nbsp;</span><br>\r\n<span class=\\\"menuitem\\\">\r\n<br></span></td>\r\n<td bgcolor=\\\"#ffffff\\\"><img src=\\\"spacer.png\\\" width=10 height=102></td>\r\n<td><p>\r\n[CONTENT]\r\n<!-- footer starts -->\r\n </td>\r\n</tr>\r\n</table>\r\n<!--INNER TABLE holding nav and main body ends-->\r\n</td>\r\n<td background=\\\"rightside.png\\\"><img src=\\\"rightside.png\\\" width=27\r\n height=1 hspace=0 vspace=0 border=0 alt=\\\"\\\"></td>\r\n</tr>\r\n</table>\r\n<!--MAIN TABLE ends-->\r\n<!--BOTTOM TABLE starts here-->\r\n<table width=626 border=0 cellpadding=0 cellspacing=0>\r\n<tr> <td><img src=\\\"bot_01.png\\\" width=136 height=20 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n<td><img src=\\\"orangebit.png\\\" width=16 height=20 hspace=0 vspace=0\r\n border=0 align=\\\"left\\\" alt=\\\"\\\"></td>\r\n<td colspan=\\\"2\\\"><img src=\\\"spacer.png\\\" width=372 height=1 hspace=0\r\n vspace=0 border=0 alt=\\\"\\\"></td>\r\n<td><img src=\\\"bot_02.png\\\" width=36 height=20 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n</tr>\r\n<tr>\r\n<td colspan=\\\"5\\\"><img src=\\\"bot_03.png\\\" width=626 height=9 hspace=0\r\n vspace=0 border=0 alt=\\\"\\\"></td>\r\n</tr>\r\n<tr>\r\n<td><img src=\\\"bot_04.png\\\" width=136 height=25 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n<td><img src=\\\"bot_05.png\\\" width=82 height=25 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n<!--EDITABLE TEXT PART of bottom bar goes in here-->\r\n<td bgcolor=\\\"#339933\\\" align=\\\"center\\\" class=\\\"botbartd\\\"><img\r\n src=\\\"spacer.png\\\" width=213 height=1 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"><br><span class=\\\"botbar\\\">&copy; <a href=\\\"http://www.tincan.co.uk\\\"\r\n target=\\\"_tincan\\\" class=\\\"url\\\">tincan limited</a> | <a href=\\\"http://www.phplist.com\\\"\r\n target=\\\"_phplist\\\" class=\\\"url\\\">php list\r\n </a></span></td>\r\n<td><img src=\\\"bot_07.png\\\" width=159 height=25 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n<td><img src=\\\"bot_08.png\\\" width=36 height=25 hspace=0 vspace=0 border=0\r\n alt=\\\"\\\"></td>\r\n</tr>\r\n<tr>\r\n<td colspan=\\\"5\\\"><img src=\\\"bot_09.png\\\" width=626 height=11 hspace=0\r\n vspace=0 border=0 alt=\\\"\\\"></td>\r\n</tr>\r\n</table><!--BOTTOM TABLE ends here-->\r\n\r\n<p>[FOOTER]<br />\r\n[SIGNATURE]</p>\r\n\r\n</div>\r\n[USERTRACK]\r\n</body>\r\n</html>\r\n\r\n\r\n\r\n',0),(2,'PHPlist','<!doctype html public \\\"-//w3c//dtd html 4.0 transitional//en\\\">\r\n\r\n<html><head><title>tincan limited - phplist</title>\r\n<link href=\\\"styles/phplist.css\\\" type=text/css rel=stylesheet>\r\n\r\n\r\n</head>\r\n<body bgcolor=\\\"#ffffff\\\" background=\\\"images/bg.png\\\">\r\n<a name=\\\"top\\\"></a>\r\n<div align=center>\r\n<table cellspacing=0 cellpadding=0 width=710 border=0>\r\n\r\n<tr>\r\n<td bgcolor=\\\"#000000\\\" rowspan=3><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=708 border=0></td>\r\n<td bgcolor=\\\"#000000\\\" rowspan=3><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\\\"top\\\">\r\n<td>\r\n<!--TOP TABLE starts-->\r\n<table cellspacing=0 cellpadding=0 width=708 border=\\\"0\\\" bgcolor=\\\"#ffffff\\\">\r\n\r\n<tr valign=\\\"top\\\">\r\n<td height=\\\"41\\\" background=\\\"images/top01.png\\\" colspan=\\\"2\\\">\r\n<span class=\\\"phphead\\\"><b>PHP</b>list</span></td>\r\n<td background=\\\"images/top02.png\\\" align=\\\"left\\\">\r\n<font size=\\\"-2\\\">&nbsp;<i>powered by:\r\n</i><br />&nbsp;<b>[<a class=\\\"powered\\\" href=\\\"http://www.php.net/\\\" target=\\\"_new\\\"><i>PHP</i></a>]</b> + <b>[<a class=\\\"powered\\\" href=\\\"http://www.mysql.com/\\\" target=\\\"_new\\\">mySQL</a>]</b></font></td>\r\n</tr>\r\n\r\n<tr valign=\\\"bottom\\\">\r\n<td><img src=\\\"images/top03a.png\\\" width=20 height=34 alt=\\\"\\\" border=\\\"0\\\"></td>\r\n<td background=\\\"images/top03b.png\\\" height=\\\"34\\\"></td>\r\n<td width=\\\"132\\\" valign=\\\"bottom\\\" background=\\\"images/top04.png\\\"><span class=\\\"webblermenu\\\">PHPlist</span></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=20 border=0></td>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=576 border=0></td>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=132 border=0></td>\r\n</tr>\r\n\r\n<tr valign=\\\"top\\\">\r\n<td>&nbsp;</td>\r\n<td>\r\n[CONTENT]\r\n</td>\r\n<td>\r\n<div class=\\\"menutableright\\\">\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">admin home</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">lists</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">messages</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">import emails</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">subscribe</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">unsubscribe</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">all messages</a></span>\r\n<span class=\\\"menulinkleft\\\"><a href=\\\"\\\">send a message</a></span>\r\n</div>\r\n</td>\r\n</tr>\r\n\r\n\r\n\r\n\r\n<tr><td colspan=\\\"4\\\">&nbsp;</td></tr>\r\n\r\n\r\n\r\n<tr><td colspan=\\\"4\\\">&nbsp;</td></tr>\r\n</table>\r\n<!--TOP TABLE ends-->\r\n\r\n</td></tr>\r\n\r\n<!-- main page content-->\r\n<!-- end of main page content--><!-- bottom black line-->\r\n<tr>\r\n<td bgcolor=\\\"#000000\\\" colspan=3><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n<td bgcolor=\\\"#ff9900\\\" class=\\\"bottom\\\">phplist - version 1.6.3</td>\r\n<td bgcolor=\\\"#000000\\\"><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td bgcolor=\\\"#000000\\\" colspan=3><img height=1 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n</tr>\r\n  \r\n<tr>\r\n<td colspan=3><img height=3 alt=\\\"\\\" src=\\\"images/transparent.png\\\" width=1 border=0></td>\r\n</tr>\r\n\r\n<tr>\r\n<td colspan=3>\r\n&nbsp;\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n\r\n</div>\r\n[USERTRACK]\r\n</body></html>\r\n',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_template` ENABLE KEYS */;

--
-- Table structure for table `phplist_templateimage`
--

DROP TABLE IF EXISTS `phplist_templateimage`;
CREATE TABLE `phplist_templateimage` (
  `id` int(11) NOT NULL auto_increment,
  `template` int(11) default NULL,
  `mimetype` varchar(100) default NULL,
  `filename` varchar(100) default NULL,
  `data` longblob,
  `width` int(11) default NULL,
  `height` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_templateimage`
--


/*!40000 ALTER TABLE `phplist_templateimage` DISABLE KEYS */;
LOCK TABLES `phplist_templateimage` WRITE;
INSERT INTO `phplist_templateimage` VALUES (1,1,'image/png','powerphplist.png','iVBORw0KGgoAAAANSUhEUgAAAEYAAAAeCAMAAACmLZgsAAADAFBMVEXYx6fmfGXfnmCchGd3VDPipmrouYIHBwe3qpNlVkTmcWHdmFrfRTeojW3IpXn25L7mo3TaGhe6mXLCmm+7lGnntn7sx5Sxh1usk3akdEfBiFPtyJfgo2bjqW7krnTjqnDproK1pInvODRRTEKFemnuzaAtIRXenF7KqIHfn2KHcVjtyZjnqHrnknLhpGjnt4HeMyzlnnHr1rLkmW3WAADllGuUfmPcKSMcFxLnuICUd1f037kqJiDqv47sxZLYAQHLtJLfOTI7KhrInnHqwY7hTUHz2rGDbVTz27Xkr3XJvKPng3HuypzouoPrwo/hXk3x1qzqwIvizavrwpDu0atqYVTqnoBdTz7QlFvqtYbgST14cWPar33hYkrw0qZKQjjdml12XkPSv52NhHPovIjjrHLZDQz03bbsxZHcq3fgQjsUEg92YUmUinjgpGbvz6PZtYjcp3Tr2bWEaUzz3LXx1KhFOi7pvojy2K314rzjvYzjf2EwLCbw0qRvUzb25MBoSi3gomXdmFvlsXhBOzIiHxrw06i8oHzx1qrqwIvmjWt4aVaFXjnopHzuy5724r/supM5Myzeml3qv4rx1Kbou4bmuYTosoHhyaTipWngoWTmtHvms3rjrXLmsn2yf07OkFf137zsx5bw1KvmsXjoq33uzqTsxpTouojdl1vlZlvswpDy16rDtZrkbFq3jmHhUUXhpmrbHxriX0/lsnrirnf14r/ty6BZPiXouYflsnjmsXvimmZaQSjiqGvipmnhpmn2473msnjovIbtx5nem13w0aRKNCDipWrrw5TsvY7qvokODArhWUnqwI/ip2vemVzlpnTrw5Hjq3Dy17Dihl/xSUPvbl3Nu53gUEPfQDPhpWnlh2nwi3ToiXDouYXt27n03LO1nX3bFBHjlmbaCAnroHXYCAfBs5fWqXXsxZbnwIzjYFPrw5Ddwp3pvYyUaD7On27RpnjXpXDswJTWpG/gsn3lwJHy4Lv037jiaFbdmVzcl1kDAgEEAwIAAACJJzCsAAAAAWJLR0QAiAUdSAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9MKFQolCwe/95QAAAXuSURBVHicrZF5XJJ3HMdVHodmZhcmCqbzRFNRSbGpCHk2tF46y6yQyiup7LDDpSlgpoVmHjNAXi3TWs0Oj8qt0qxJxyhn1LZga1u2tVou290In31/D7j197YPz+/7+x6/75vv83ssjP9B4xMyWhhf/msxgtSg0sbrswEjMRgkBomdBIzBYGdnkIDszLvElJWgwPBSAsljEELCDtYxxQfq0lKBQPBRDmAg+4lBKBQaTDLtQskrvrlEEImakChJAAMQdSWBGRTW1/NwvFco0+Dlg2znMfxdWS8kcCqs3noMLAaG7TxYXw++TOg9Vu89NjhYL6S9pxaoS9WCJ+ilfEA8qjPurDmYwZP1ysp5Y+UyHhWyuI8z7oNhPoPIYL0+VpCRXfU5yMauoqZB/bPKRoGgcct1OmCsQPDn5VSelRWGjZXzqJh3BprGCs1hhaahYpgVKpsyVpgmAzUxZl/fglT5rNNoMc4A8agMBprGW5bB4zF43kSCgTOuYgwMAw8MdpHIOOMMBpWHehi0Hq8tjYBRB+nHLcYVCrGYR1UoFOhuxApvTMwrV5juRpGhOThxN97OcA78iwoxlScWQ0DPrkTDVPGlNMDQaOvXw6LRaIGwiIDY//aJKvLEYhSKaaYTnT38RR1VVR1VUVqE0ev1crn+kvwa2uR6faD8kt5ajrL6TnD1+v5+eScq6C/p+/X6a4HyQDjZL3eNquyo6ujYfoTSh17Kum9oaMh6CJk+a2LvG0LORDRR7YODKI3Ow6P6qnA70qI06dAQYOiguVwOh8XisOIe0ukPdRwiYN6l980jizZDuY9OnyUa37mRPmMr3A5OJv06DzYjWmyvoBw6HTBarbaGy8qNO/m0ixUXqtVe0HFyM/9cGM7q+k4bRtYkaAnNEuE7Z/+0BI9cuzIL9/t5VuTW/WScXVHhESWFKmBcVapuTteO4ODQyazTD1WqC5M53Jrh0Ls61mdrSGRRgkqVo1KpTrHHN6tI5P0znj+fbz//zPLdMe6RRtuYGF+Ka46rK2CSkpK6WN3DsOlYmcFJScM6TkEzRDtYr28kaUR+SYQAM+/MXtyWCFqya+PjD5QY98bXJktRAjA9UimTdTNYer69m3lyTtv5dpjGra1t6grWp2sQRnpZ2vZhG5pGGkYuCZv5/HHErSPx8dtXleDp57KVUunly1LAtLQovxh5tHBPwP1JTyfd3xMQEMcpCJi6Z8Ujzpc98FJ+SqWyRak8xTau7PHNwvEs2wSnA0XfxMcjzDMKdCtbWgBDoVCab+bC1+HkjnwLhjuZU5A5DRzdUgrCUAjNBMxvlOklIg18oNUheXlFgLENMhUpgIkANVsyR6Z1MbnMrpHwe5mcgnvhuUzL8xERYSKRXwQhhHkc9NoGXyfPrHGNTV5eHsJQgkxVwCQjBbWHBs+1PP7m3KnDoXGcuIA5oXMokCYBBpVfSwbM2uXZsfy3QkJSPfBlIS+KYiJhGlMxGTBXmsxyOz3teHBTUztMU9fUlIxSJBGbZCpOFxnX/n4uNeSNFy+KbPH0TYlHfOGDv0PUrjQB5uNtZjXrWKdrtm0DDLcOQpQniTTpTvb29k5TprPHw0IWpC+zWXViNVtjk+h1ewpM02RuBUw1oYbqajcuK7Omurpdx2HWNVQTvzANrimJ3LWrxG+3CF/99Toc3+9RgZM9U2tvV0/ZhS/JJjobGgATa1JK7NLu8JNuKbFucSxuXYop6VQRCRDAeH6eVbJu04JlWRB7eP7ofzv2lm9WZMIPRGNsLGBGzUqLag9wi0obvbE43PKX0bTR0ZSU0Q0PnB48cHd3t7HY9L27xR/FxaknFthYeLnkp6Slvb3b3tfUmfI+YKKj8/OjzYawTxbfAHvU0cW/trDyTuKhfQ4DDsUDoOJiB4fiRAG/NRrq+eY24gGMI6GjaCE5tjq2+vvzvQoFiwgEaMBhYADtDmVnEyu9+HCGOPhPYytgXMzyh2Z+ba1Xobry8J3EvENny8rKHF5V2b7Ew4V8l1fkb+5zAcz/or8Ag3ozZFZX3G0AAAAASUVORK5CYII=',70,30),(2,1,'image/png','spacer.png','iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABGdBTUEAALGPC/xhBQAAAAZQTFRF////AAAAVcLTfgAAAAF0Uk5TAEDm2GYAAAABYktHRACIBR1IAAAACXBIWXMAAAsSAAALEgHS3X78AAAAB3RJTUUH0gQDDRIxVeX9EAAAAApJREFUeJxjYAAAAAIAAUivpHEAAAAASUVORK5CYII=',1,1),(3,1,'image/png','01.png','iVBORw0KGgoAAAANSUhEUgAAADkAAAAdCAMAAAAn+zSXAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFuLi4x8fH3Nzcmpqazs7OZWVl19fXtLS0srKyo6Oj0dHRyMjIfn5+sLCwqqqqh4eH4+Pj2tral5eXampqy8vLsNiwgYGBXl5eb29vjo6OeXl5cnJycHBw////6enp/v7+6Ojo7OzsM5kz+/v75+fn6urq/f398vLyxMTE9/f37+/v5ubmwMDApdKl8/PzjseO9fX1WaxZ/Pz8+vr67u7u6+vrtra27e3t5eXl+fn5+Pj4vLy88PDw9vb2w8PDwsLC2dnZt7e3vb29rq6uurq639/f5OTkr6+vwcHB9PT0vr6+NJk0OJs4XK1cxcXF9Pn44eHh0+jj8fHxv7+/u7u7cbhxtdq2P58/n5+fnJyclcqV1dXVi4uLq9WzY7Fjudu/e3t7kciRgsGCqKiozc3NfHx8lZWVoM+gabRpUahRiMOIicSJS6VLRkZGp9OnyOLbsde87/b1+fv7+fz8t9rHudvEvdzOo9Gj2evnd3d3mcyZrKyspNGk0tLSk5OTQaBB++LODwAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDCTseSYkAAAB6ElEQVR4nJ3S93eaQAAHcMCBUaqpicbEetyVo6CQNJWAuPeIbVYzuvfee69/vWdplk8F8v2Re5/3/T7uqNTJ4p+iTqBgMzo/53MvQeTVo36r33Urg57EXTYQoCjDndQ9cz+Kp8Jsr2ZCNzJ/k/P0WZaabUOsmC5keyapGnXV34GKomAXnVcjTSqw41cAVhQIUM6pFL4lZZaiNwHEEIi5jLTkUBqJqaKhMgqEpG6lJC2n885kN9E0jPVpgDFxl1fLBSHLO5ILXIXdCXYA2UlcWhPO8amUA2n+jqr17dPmYGhmKZ0XeH7w2V5KM2G1RU8DCMUVabnwr8+RxJGw2vAqwCrUsvz+gZ00442KGsSA3ETpSKG99MZZWfYTqJOl+cNCW7nJzcp0DYjiYOnRQjvpjddlmoG6PrzUTnojIQIRQoOlAj90OkGuLlTXQwxAKCOVtewwnCAZLiDTbYRyxy/DXmbPdKtyTUQj/s1kWYg+qPZuwPFwrOTmK9u/oH4cfni39fbW60UrY+S1x3LjISbXKB3Cja8f92Jfvt87b2W09PoqxZApkodT1v7DjTd3YovX7+/eXrtgZaTESbUld0QxdwD5rU+xJy92115euXTxrJVRUvN5ij0GAFTaf6rvP+/9fP702Z8DRvIXKzra2Frx8qQAAAAASUVORK5CYII=',57,29),(4,1,'image/png','02.png','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',96,29),(5,1,'image/png','03.png','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',135,29),(6,1,'image/png','04.png','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',45,29),(7,1,'image/png','05.png','iVBORw0KGgoAAAANSUhEUgAAANQAAAAdCAMAAADl57JWAAAABGdBTUEAALGPC/xhBQAAAORQTFRF/////wAAM5kzpdKlWaxZjseO/x0d/xAQ/xkZ/zQ0/w0N/zs7/wMD/ycn/yws/yIi/z8//xYW/xIS/0lJ/2Ji/xQU/1hY/zEx/0RE/yQk/wEB/wYG/wcH/w4O/y4u/1NT/zc3/ykp/wsL/wkJ/x8f/xsb/11d/wQE/05O/2dn/3Fx/3Z2/2xs/4CA/5eX/4mJ/4GB/39/1tfl/3t7/4yM/5OT8wwPzzZD/v7+4+Xt/25u/3d3/4qK/7e3/4SE/5GR/3Jy8BEU/4WF/4iI/3x8/6Ki/3p6/2ho/21t/42N/6Wl/35+v1gDAQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDy2+iM1DAAAAsElEQVR4nO3R1RUCQRBE0cYdFndbXGYFd3fyz4cU+mdOs3P6ZlD1AJhDuJyiFO2VKy09XA2ksslgx99OFGPNeC0fakS0Qrpfz3Uzg/FwNLUPJqyoX5VhQV0AC1nqZl/esKZ+VYYldQEsZKmvMI8woX5VhjN1ASxkKWHNDOpP5aAOgIYp9To9rOv8Tv2pHNQB0BClnput2BmfPfWnjDHGGGP/yqMg8CkIvAoCt4KUHPUD6b0vP47UFzcAAAAASUVORK5CYII=',212,29),(8,1,'image/png','06.png','iVBORw0KGgoAAAANSUhEUgAAADcAAAAdCAMAAAA5MgQkAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFvBISNDEy09PT2rS01MfH8qSkqKWmM5kzBgUFzc3Nu7u6lpaYtbW1jseOytPTzLm55qenTbeSxsbGeXl9hoaI7Ozs5LKyylhYxklJcbRp9JeX13p66Nran5GRbbaEwCgo6urq48rKycnJVVVV7Lm55ubmyoSEmcqTcldXzcDAwjk5mIOD3cHAWaxZypWV4eHh25iYyGlp4rq68ePj6Zub5vLy06ys0Y2Nsq6uSUhJ3t7e2trazaGh3NLSpdKlzt/fyHh48La2xsvLaGlq89nZM6Z28a+vi8SbQz09Nq6KZK5eVqVK5sjIWEdHwsLC6uPj9oyMdXV7u7S08fPzRZ87c3Nzos6bdmhpgH6B54+PzsnJ1nBwyc7Ogrx9Jxsb3Nvb19fXv7++////lMmQxsLE5+fn7O7u4uTj6ejn4+TkUlBR6cDA29vbHh0d29zcfXZ26+vr5eTkmsyj8O/v8c7Ox8fHW1paX19gb25wt7e3vre3ysvLyMjI/PT0z8/Pnp6e9uNTVgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDwmCiymSAAADR0lEQVR4nL3UW1vaSACA4WSo2QkgtiGQIAoBWxSwcrBylK1Ft40ItFhtU6CQrEAVmgpLsWjY5K93Bvr41Fb3wot9L0IO82UycwHx9/0Q9+3eIIvI3LWXMy+wxzf1er1i0YdkiOGw40CmB6zvEAT8gwmCcHrabj9BBoPB2VlkKpPJnFrQfB+eYQ8x59Tj64PTaULicdO1BSyYqhCdDn7BDDprv5a3vppynzp6itJkPStHBmWLJTWRZTkLAGCsDO2f6IqdeLP4bDpRPL6Ovnxp70N0z10r7oXXsHDRt74mrpVEMbzuWyYbjUbp8EEwWD2sEA5Hpvn5SzaSAe0lU24rEYs7uejQckFN2IuuOSKnRfYsJYqklM1IqnpR1iejEW2uEItzD9Fcxb1k8rWb80Vq7qKbiydLpfdBXzic23xPildLuXOSFKlN3DF0i2lVrypEX8iebSaWonwgwQccCc6UDLl3NlZEkhxTExBRVVKRQUpVVXvbDuFKmR4xDJ5v7qXza42L8nyN43s5juc4nufXWbyWRokKNtQGBfQWqcJvg3MIu2WamXVCO1vk3bFYCBXJWCDGJzli5wuQRBKqMM1AqO426XIXwt3BMe40zT/tXvR80VCNC3hjG15v4PlCbpvwVt+Ol5XlUgM2gg0IxVy1hTpp2pk/K8q067ebGyF+mwsltvkdW922FSMCfRbCivZvGqabYzShMZRJSB4KaH3dT5Eu68edQwALPGfbCdhaV/U6Y3vg9QZtIkTTnKsqpVtJdHbJQpVt6isQksYKZDXcdQRZ307Y6vW8Zsszo3w5tpEfWXfRRkDSAlq0+RgtVO0aQDZItKvoNRYad8O+LD+3TTRtMmLQFjP5fH7EAP3QYC0KQNdVCQ1V7X66nDIoyjCksdU/67JA1/yTaYShjGn5daADvOWjFJnGX01epMAPs/1868hkZaDr9B38VlphUbnrB9f3gIK6oa6VFfOl9Q6XillRjG/HVvNP91J2wjGhDAvLSpL9P0jSL8/H9/5/+b+7P+/lH2L+Dicnq+9mVldPfn9MPL1VweX6OP9qZv6jy1Uo/DKA+OM2RwcFj8fz6AePx3VwdHRzxG3dtHq0v//XzP4+KgsHN8PvjdlZguuBg/sAAAAASUVORK5CYII=',55,29),(9,1,'image/png','07.png','iVBORw0KGgoAAAANSUhEUgAAABsAAAAdCAMAAABsU+d6AAAABGdBTUEAALGPC/xhBQAAAIRQTFRF/////v7+dbp1OZw5+Pj4/f39+/v7gcCB/Pz8M5kzbrduWaxZWq1br9LKg8GDg7yr3unniL2w4OblNZo18vLyQJ9AebqWYbBh4O3qV6tX8/PzXa5dS6VL5O7sZrNm+vr69/f3j8WftdfPcbd9YK12QqFC8fb1abRpTqdOXq9eUKN8arVq25vYiAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNESb9GysUAAAATklEQVR4nGNgGAWjYBADRSUJNVZGRqxyfOzsYryy8hzYpDXluJiYlPkFWNhYMWSZRWUkxdW1VQRFFNjQ9XJyCjOranBL82gJSbFwoEgCAAWTA+aYZlabAAAAAElFTkSuQmCC',27,29),(10,1,'image/png','08.png','iVBORw0KGgoAAAANSUhEUgAAADkAAAAYCAMAAAB3NqUkAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFxsbG5OTkOp061dXVTqdOwsLCwMDAk8mTn5+fmsyaZGRkycnJ5+fn4ODgmZmZpaWl4uLiYrFi2trazeTftra2hcKFcnJy2NjYRaJFy8vLPZ49xMTEWq1aubm5tLS0sbGxenp6rtevcrlybbZtk5OTVapVfn5+d3d3M5kz/////v7+6enp7Ozs/Pz86urq7+/v+/v7+Pj46Ojo8vLy/f39+fn5+vr69fX1NJk0SKNIX69fNZo19vb2vb298fHxvr6+7u7u9/f39PT0v7+/urq6pdKlvLy88PDwzs7Ou7u76+vr7e3ts7OzTk5Onc6ddLl08/Pz0tLSd7t3fb59UqlShYWFOJs4j8ePg7+ZUVFRqqqqrNS4jcaNXa5dSaRJNps2ttq5jMWOotCsqNK+otGiQaBBj4+PioqKebx5iMOIi8WLudvBrdWwd7qBZ7Jxstizlcih8vb1XFxcwt7Xx+HakcWylpaWgcCBQJ9A3t7eYbBkZbJl3+7raLNoabRpr6+v75QmdAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNETbgrDtwAAAB50lEQVR4nI3SaVfaQBQG4ESNgiwqgsXWVtyZO5OJhIC2TQgaQHCtezdttZvavdrW7n+9CZCcQBLN/TjnPGfeee8wyDEQCCiVTRETYS7Hg3Hy++1Yz/jO5bO0fRinfLzLVIZZvCwsZuoQBp+XEwPJvfn0NTK8G9JCLKbFbGa2Di8+rp9MXd5Pp6+RZGaUY1iMSVZuwMGx4HhXx3w7dEgYinZzMUpJYS4vGVJ6F9xwgw65Oskp1TAlS8124MnL7R032C5jj0q3GZYSq50X5dORm26wTcrT/0pcxNYOX97/9cpRjlOCEuWUTtFqB0nHwY1Dd9gqO3tVNWRsstkOnK8nRjrcYYuE13e0WxGRFh80Hon4T/sTSY8r7RL6hppZFxpZ4XPP9tSeB7TL2JdVvVesLyQv1Q/4r2cTSddeWyV8i5fUGKbCYvOfQ/7o+4HXK+0ylOqubYbxipkVwcLTrS6vV9pkcW20xrDisp61ARH/MPE36QktCX0BVbkhYqtXBLn3W54rsUl5minpqyQFMysC+cOfK8KaEmbeaFy/SAWzVz1s9ue9u97QlEKKqVSNesysCM0KAz4kRAOa/tNJQTazIpQnfmRkLV6risYqJUvmqA8JvZNx9Ydoq0efDPYhCyktrhdrXIl8y//UkOay7Wv1cAAAAABJRU5ErkJggg==',57,24),(11,1,'image/png','09.png','iVBORw0KGgoAAAANSUhEUgAAAGAAAAAYCAMAAAAyNwimAAAABGdBTUEAALGPC/xhBQAAAWJQTFRFM5kzM5gzMpcyL4wvMpUyLoouMZQxMI8wMJEwLosuL44vMZIxMZMxL40vLokuKHgoMpYyMJAwJXAlK4IrLYgtJ3UnJW4lL4svKX0pJF8kKHYoJW0lLYUtImciI2sjI2gjKHcoLIMsI2kjK4MrKnUqJGwkJnImJnMmLIUsLoguJFwkJnAmJnEmSJdIKXwpLVktHVMdKHEoLYktKG8oYKRgGTwZKn4qSYBJJ3QnGEgYFjsWMJIwV6pXKncqI1EjXa5dIFggLowuJFokKVwpKnIqLGksM5czUaRRIVwhLYctLYYtY69jJ3MnK4ErH1MfKnQqJ1knImQiID8gIT8hK2srGUcZKnwqJmsmMpQyLFUsNXc1WJZYK3QrJF4kLmMuKHkoYK9gLIYsH0wfJXElImYiJW8lG0UbIWIhMZExLIQsFD4UKXspKWopImUiKGEoI2ojKXopW61bIloiWqxaKmIqIGIgpgmOaAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEhFui93YAAABj0lEQVR4nO2QxXLDMABEY8uyJEuG2A23TZmZmZmZmZn7/5VpCpOk7d3v4vHM7tNKoZCNgbW57oud+sm+1+fBo9m23c7G9puhmre19HJBNFKqEQgJUBBmkigIoZ9MXGkdzaNLIy81D+OF9wVR/RoSSIiKMJUNN2L0QutpYPWkaWo/kUgsrPesbB/cmWaqpCQaryZcDwGPM9nI5A8JMpgvqqutjHSZhY/R+DRx94SpzPd4kS01/7C2fLN1bLGsbK8/cpq+fD9LVSTj+dDGWU+z6HlbZMS61ZO6XrRx7BfCWP5yXUGUhy29wqyL6JxkpZkqP2+YiQECNc3XZ3wdry1RpAIH3nAK9Ptr8ghDsZaqUssqLq6qjqkAanl5Gp9CgMrHsBx6dx9FineEXbD1ovA9wkcowPEpCnD99g9yx+fS87Z9Ag4jF++2ws+IxDDicq7kH9VZgjCmv4z/rMuMOmQpOBGKffiasCP/bfxnXTQkGyNbwY5IkuzBmC//g93r++SKiF/5u/xf5NwQEBAQEJCZD8wLKPNZhu5VAAAAAElFTkSuQmCC',96,24),(12,1,'image/png','10.png','iVBORw0KGgoAAAANSUhEUgAAAIcAAAAYCAMAAADnCR6uAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFmcuZQXBBhaGFe717hpiGyOHISaJJxt3GdqN14uzipLekjbeNUZhRrcGtkcORKn4qMJEw1enVUkpI1OPUTJBMJG0k7/bvKFYoGUkZweDBzN3ML40vYKtguty6Qp9CLYktMpcyIWIhiauJz+fPH1sfLYUtOpw6HFMcbKxsksiSJ3UnKHkoJnImWqBaK4EraWdmxtrGb5NvarRqdpd2ZKNk5fHlYZphI2gjNHo08vfye3h5dbN1hcKFTW9NVqpWf7J/wN3AxNTEN5U3M5kz/wAA/////4iIM5gz/0RE/yIiMpUy/+7u/7u7/xER/93d/6qq/2Zm/3d3/zMzMI8w/5mZ/8zMLIMsMZMx/1VV4fDhptOm/f79stSyYrFiXK1cUqdSvsm+wcrB4ebhFT8VydTJ8vnyyePJzeDNUnRS0drRtNm0YJZgrteuosqit8+3tMW0RIhEosaincWdNpM2I2ojQTo6mLCVlL6UkbWRqcmph4uFn72fNGo0wNvAXVlZcYlxesBtFAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEBlSZjdoAAADuElEQVR4nJWW+WPSSBTHUaGtVhpaTUVTS6IgbaBYz+pqvco3CYRwFtJ676r12PVa3fvwX3eSyUyOEqDvpyHk++aTN9/3koSsbwXjwi60VsOEE/MPn3hX5S/XUKvSaOB6ISMHFDN/L/y2sPD/xR9+vxCnHx+JMMbWZTTc3YxOy0l2/gjlsB+j43FomB305KBmauYioUiuLMfqJ+CI/F6FVmXR1ZpeJr1+Cy3v6g4+VKR6GH/l3JW7UyP0B+d4h3Y1EG2S6TTZQV6+CcvLjhNCztaHZovVH5jD9wHLZGL3jq7b97DjXQFK8Rwx+oNyLMOsRsJqYHFKr8/xgvdxMmTUifQH5GA2c/xosCdrYV6Wl9D3fpKGiRh1Av04kAiHbzOLuL2puamsPhJ1G2AbYJYYdXi6WP04kAjHWe4CAz/+BJhtut79Yp+HwTj+/CN1zWnKxJ1oulj9gyDI7dXV1cs6CbbYx+HbTEPyxWYa9PkaWMm8ZxPEAI9HD7cm1ds+iL5HpHN2XZbZQtfDHFO+zRp4k80WL8FN3MF7iRvVaLbo2RvbwFl9Uj0fOTrdXurZdspb1CMcR/i0qpo4VVQUJe3uTmZGbsW3II+uSUD0yfQZVhC97mw/O8hJPbaw5TBHgtusi7dFRVTVn+nuQGWNN0wYJGjBUXpeEN0tw6wqFHJrV69e/1QScr16mGOPebHaxrG8WKoM7mKb5hEKvGGCQcyy5IOM0vPZJ2ccjnNfCUihoopiuVSQ7DDHKza8q9u4oqgDqee93wB1MJSD+PGeb8FRej77PA6FYP46vb4+I6qVCMczNFn6Jg59JY/w7BFtAKBc2Tey3bBMLLGKj9Tz2Sf3pgnH4fyGKkzTwlSkuRCHb7MaueHMdGpvkZaV5BGFFCt6zdA0reM36H/MgiP1fPbJkstRVDwOUo9bCHH4NrM6DTohWhZNe9TnsOg/ZpcX5LgtT6Dfx1HmHPNhDj4yqQVJ1Jjr0oF69JFMJtMwa8wKcz16MqP1cRwbZQFhjuFOdE/7UoBjG99eZLNpdggGXudoQUbr4ziIYW+GOJ4OmxBOaHiZFysp/wVzuFjM/7uILttlzS3IGH0MBzFs+a8Qx23PVJGwNOBNXh1wjh18zG+I4j/sdg2fJWc4jNHzfuEcJcqhiDdOBDl+CX/T0ah1yKfdZlEpFTiHgfuk54ST7GVCprY7HMboM/s51ulCLN8IcsyzQlcbTmMabU1r9cmdzzezeXK8ewGOspCTCq/ZBXIwztQeo+fzdAiH+B0pcSBPpMWYHgAAAABJRU5ErkJggg==',135,24),(13,1,'image/png','11.png','iVBORw0KGgoAAAANSUhEUgAAAC0AAAAYCAMAAABZc4S2AAAABGdBTUEAALGPC/xhBQAAAYBQTFRFM5kz////M5gzMpcyMpUyLIUsMI8wMZMxKn0qKn4qIWIhKHcoJ3YnIF8gMZQx8PXwK4IrMpYyIGEgL4wvL44vMJEwF0QXxtzG5PHkLIQsL40vwc3BFDsUJnMmQaBBi7KLwMrAFT4VKXspHFQcHFUcImYiMZIx8PPwKn8qKHgoI2gjJGskwMvALokuGEkYksiSyOPIKXopHFMcJW4ljLaMbqBui7OLg5qDgLWA4/DjSXVJjbiNh6aHeaB5Hloeg5yDP5k/HlkexdjFHVYdd7p3wtDC1OXUkcWRj76PLYctxNfEX5ZfLYgtLooum8KbxNXEhaGFx9/HGk8ahcGFIWMh0dzRkseSZqtmjLWMhaKFxdnFOmw6yeTJ4erh4ObgiKqIK4EryOHIrdatO2071unWTaVNkcSRYZlhksaSwc7BVnhWeZ95fq9+SXdJK4Arx+DHhMCEJXAlWYRZJ3QnKXwpGEcYxdrFts+2JG0kLosui7SLG1IbI2oju9y71urWHVgdlWSEcwAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEDAQ1K8EAAABMklEQVR4nGNgGAUDDuRIUasfwwQExCl2kWA0YGERZMaqHE2QSZ+RkTGRjU2NBatydNVhQNWBrD7CoiyEVTMxuwNVixtysEqxM2NRnZpUB5SXqC1gZgZ6LdjAEsjjd4ry82TDZjgjDKQ4AL2WAeOV5VWyY3E5XDVjvogoexqM48UtKcKHqZyR0S0n3bQcpMJCVzjcQxzIiLMPyDbm0MXiUUZGb60QIV5/oGsjOCVZNaqAqnUUS51VeVhF+LCojrXJVVaJTrYTqinm0FCSB6pWkBGr5pIVEMYMFrAr1V0jrZQduYw1OQyhqo2AqqXYsZgNBaFFvNo8JUpmYJfo1Wtxs7JhuiReHa7eVpuHk9MEyJAWC+It5PRVE8RQLWSUZS4NUc1foZkgYA1kZOqpqAKNxkwqAC/qJux66BeEAAAAAElFTkSuQmCC',45,24),(14,1,'image/png','12.png','iVBORw0KGgoAAAANSUhEUgAAAOEAAAAYAQMAAAA24AjoAAAABGdBTUEAALGPC/xhBQAAAANQTFRFM5kzngQ2VgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDS4Vt/57AAAAD0lEQVR4nGNgGAWjYPgAAALQAAFbrPJzAAAAAElFTkSuQmCC',225,24),(15,1,'image/png','13.png','iVBORw0KGgoAAAANSUhEUgAAACIAAAAYCAMAAACoeN87AAAABGdBTUEAALGPC/xhBQAAAYBQTFRF+fz62u3ezOfS6vXtk8yic7eEU6RFTqJCWKpWYqtaM51Np9SqRLaTPJs2bbWFabBncrV6Z65jM59QM5xE3O7h9fr2ud7CWbN64PHlUreS/P78SadVQ5058Pjy1OrZc7qDM5s9NKVxNK2JndCpyeXNe8KUM6RrveDH0OnVY7Z7XLeOYLaLweHKdL2KNpkzbLNxa7qEObGNgsSVM6FdM6mBQ6pztty+NKNjqNWy0unYN6FTud2+7ffwSreTstq7SqA+S695WbeRM59VXbWEZbWGR6hhi8ib8/n0M6h9M6FZTKliV6VKzefVa7uK9vv45fPpM6d5weHHM51INp5Kq9i7hseaNZ9WN6FYNp9RM6yFXLFuPKFMicih1+zax+TLQLWSrNe2M5kz/////v7+/v/+R588/f79OJs1M6qFQJ04M5k3N59O+/37UriT/v//SqpoRatwnM+gQaVgPaNXhsidSatxR615V7WKVa5nxOTOUq9yxOPMxePJw+LL/P38t9y7RrmhFAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDjW0/2RUAAACjUlEQVR4nF3T/1/hYBwA8MeErCzGirFliNFZ2EKR8pTkaLHrmzrJMLl0Xdd9v3P3r98zvF7d6z6/7v3a8/kKOiiEocVqWm8f7Z+6gaZ1R8bIcT0ZIxxm3wB9BbqwIVEMbEaAKrt+phsGRtNqK00PsWQeClMy2EWiuXKiGp9ekyQJ4ceUW6XWjnYIh8Um6ETwVU2xwIHczd7juC5EUYSHgP4keUzW/gARwWbeOEjuMdohrkcp+yPDceFKVAbxwLq9ahMQ6VuJHY7tzZHkcwQ7rAH3VynQ5MTEKL/ZJhx9RGxme0xK9XiURQmo3VqUNxabgbswzKqnUtFuHoDO0Eq0P7hGVxCSl0BtefFn1rhflzjxkWG+LBJWG+j0N2JSkGZRmuSqxl8rOLldY47DXAYW3r9NHjh8oGMx7YQNahCRqLvgRILElchJIpOpxOlXdzH7LuiYibZY1uIkjFOjXyFMj5sGkNdE8VF2HXlMFtCpEskKAFckTJTzNxjG8xgWuqGYM1G8YNz/EcjnFBTXXq/32pC++IfoD1HaKiJsA0fE6TYa/YblC7FSomYP6em61CAO4dYYV1Wnv6eq/hb6C4zT42m6k6K1LVTJ1pjUiTYh25C81Yte8s1a5x59w3F2DFHBOnnXSt+TpXx5b9o6NICidNYLKQobQe2bkT+KElQLswHoY1zkDN2U0mqcn1++8XcRMeScV4DZm41xugwrMr2Ry2M1PuRC6crU03z591ogZq8OBX2l+vOmopQAv9PT7vsXFrLBpzI9V0crtTuYLKYNLaannmB6U+L0++dz2ijFvSwmOgCzg3i4+/yd1sqG5dsCm6e7bBSJl/WeGP1I1iN5mSpTMoMlMs0HYqk6OZK/+Dare54AVm0AAAAASUVORK5CYII=',34,24),(16,1,'image/png','14.png','iVBORw0KGgoAAAANSUhEUgAAACMAAAAYCAMAAABHurQFAAAABGdBTUEAALGPC/xhBQAAAV9QTFRFM5kz////NJk0/v7+MZQx/Pz87+/vLoku6enp+Pj4OZw5Kn8qM5gzMpUyMpcyNps2NZo1+fn5+vr68vLy9fX11NTU/f394+Pj+/v70dHRL4wvyMjI19fXMJEw5+fn3d3d7Ozs0NDQ6urq4eHhMZIx9/f3QJ9AR6NHtra28fHx9vb2j4+Pu7u78/Pz4uLiMZIyn6akVKlUdnZ2cnJySKNPMI8wzc3NKH41srKyqampTKNhKn4sUKdQK4s+tLS0xMTEp6enpKSkWKGOv7+/0tLSLoouKIE3L44v3t7eLosu2dnZ09PTvb29tbW1m5ubK41ISqVKbm5ub29v5ubmPZ49OJs4MZY13NzcZKiXhoaGjY2NKYhcK39hJH9IXpWJlpaWLYgvlZWV5eXls7OzOp067u7uwMDAkpKSTKVMMpYyQqFCOpw9mcK5bqebfHx8xsbGKYY/MJM3np6eU6lTxcXFdaLumgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDQ4u2d6zAAABGElEQVR4nH3PVXODQBQF4NtdsgWKhhICjTee1F1Td3d3d/v/U5h0KlN2z/M359wL8CtoM7vy3tb/QIphXPMT+Bsk3t0Mdu6kmvQQphlA6kYudz+2XhACmGYAtIusuPVokcw3+m/cPVF6TTYTIUQ37p6qLCetgo7pBjQR5P2JM+PrO1+DVE1pGY/w9dWTfA0gJHFDH/HL6kn+BhDcRo+6LSOIGUZToi9l012jG0BS2ml9niFeEc0AyNMHi3bMK6ISpHSdjFbiXhHdSOnZ07zJZxgGgHMO+xoajQDDINmZWlqYS+iYZSZ7Sr12rMgwwNUO7I54Y0xT9/R2tU3CbNN+XI4kgmzTcZ03UzrbrJWG53mBbVb3zm1e+ATyXCFUETuPEAAAAABJRU5ErkJggg==',35,24),(17,1,'image/png','15.png','iVBORw0KGgoAAAANSUhEUgAAADkAAAAdCAMAAAAn+zSXAAAABGdBTUEAALGPC/xhBQAAAYBQTFRF8fHxJV9O5+fn17NswptO1qtVh4eHYrFiVUUj9sx5KHcp3d3dKSko+Pj4uZRKX65fw8PDZWVkM5kzfWQ0o6OjOYU5/9mMs7Oz0tLS/s1shGo2WqRaSDwf6enpq6urMpUy2NjYLoouZ1Iq/f39np6e/tWEjXE758J8XFxc7+/v4rVaoYFBVFRU9vb2zs7O6rteK4IrRpxGLUw9JHk7NCwZ4ODgSKNIfX193LFZm4FL+/v7v7+/9PT0rYpFyKBQkpKSTExM4+PjlntD/cpmcXFx6+vr7L1e8MBgVZhVtJFKubm54r11+MZkkXU75rhcQ0NDu5tZmZmZ7MmB/9N87OzsfmxHaJ9p9cRicVotqopK+8lksI1Gz6ZT/teInX4/ycnJMJAwaJ58dl8yS4tXlnk8YE4oqYdEvphMQz4qIB4b/////v7+/stm88Ni779g9tKKSUlI9dGHIW1HyKRcIW85TH5vOJc48MNppIdOp4pOWFVLWlY/8MyFPD03c4B+Q4hD3NL5jAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEDeOsDqnAAADOklEQVR4nI3U+T8icRgH8K9RRE1qS0PXpEkmTTlWZEo1qZAObMhZImlG1rVrL7v/+j7fSS1hX31+nvfruWYGiaIohTZDoQBRQCQvie2Qtv6h+Q/vhKbLCJ4Jc1SoRNg8YZIRu5CgymNj0wiX1OtCDiKJfM9LviOBgZoeHBzEcpNK64TOkm9LcFjd3/bkQTJRXcj/quRbEtzw8J/TLywbi8WQKDnYki5gK3SUfC3przOWnZ2TBZyEXo+gZCKkg/10lHwl6fLM6s7Jbz3LcT9+bm8bQHJGSodP8rJkp6TL08Ons/V6XaOZm6vPObNIYmIU5cf7kf4nadjM7XX98XHOalU5K0WVGUlkDG7yulks++efw558/kJ2RYPb660ghow5RrDsaFYkk/9qQqsAry9UKqch611a00bWzIgnr0vCG2OKHn9bNuGFqlo0Z4+XtIpI7/odlpqYQHh8L8ZkSn1cos9imW/D2zwezw0ucqgMTmwtuhFfEPKcnWgtCAXUajg0t+CyU6stOTZ4/2jFjZ5rI8rgZO7h15bKi0jCn46xLMcmFhIs96jRaPLcpstoNGI5Qz9tR4ZnBwplcCq10ri5WayuIT45kja6XNE+SNQFxBWNRjeN9rR/xGKZwSXxkE0YCeZWdjOZgfEB594S7JbwU3ao4WrHaNc5AoIaS7rZq1VVNWQBpnb3ly9Hx4824lot3BMlBUcpTVF2itL5HQGIGhJoSSh5Ya1W3OcyvARoWq/CXZDE+zw2Qd2ZgCA4YLc0XiyULJq9CuWUDD+ZjrJrNUUQ3lugSUIQAk8RcAhbgVAP4XtCs7Mw5LFWmWs0oSnuVtQOc/CVMaQPeQo2GyHHBkkWPAh5hJa0Vosw5GQLfjdjOIX/CQxP+sLwqByEE/aRZJiQ36Hy9K3KCb0GU5llGS5CxcjkFJay5UnSh0Pi8DwjSc1vhcay6D44zDWacKPShFeo9cJJEgORIC++Mmi2B6asKVMZvNV2xVQDie+lJT9yTvM5TLmM4bcWXNntQrJys7ujRyaTwdCGmW5k5UyrTAFcj8drWhleNTL7XUi9ATb7cGTacu49g5fdSPP53cRnU695r4bv+AQv/wL6FGs/AO9qZQAAAABJRU5ErkJggg==',57,29),(18,1,'image/png','16.png','iVBORw0KGgoAAAANSUhEUgAAAGAAAAAdCAMAAABi+pkVAAAABGdBTUEAALGPC/xhBQAAAQhQTFRF/8xmMJEwJnQpK4Ar+MZj6bpdV1dX0adUjHA4SEhIZ1Ups49I/85t/stm+8lk779g9cRiR0dH/Mpl8sJh+cdk9MNi+shk9sVi7b5f7r5fvphM8MBgvJdL1qtV8sJgJnQoREREWk8n0alZfmY2o4JC57lds5FNK4Er2q5XZlUp0qhUzaRS47Zbv5lN67xe4LNayaFQ/Mpmso5H1apV6bxk6rteZ1ct/cplUlJSVFRUVlZWm30+98ZjYlIoim435Ldb88JhZVQpsY5H5bdc6LpdxZ5Px55QzKNSyqJQV1M4jHE8t5JJqohERUVF5rhcT09P+Mhq88Jigmk1z6ZThms206lUsI1G98Vj8DhWmQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNECF6ZI/2AAAAwklEQVR4nLXThRHCQBCF4QUuOISQBElwd3d3d+2/E27o4X0F7D87e0cGMMqasChqxKLYWZIkGw5de/2BGYj28fHcDpPvUPLxmlhxDjRMnT4NB0yGFuVa0YKTIG1bnaYFmBZt3oHIl8HcaNe8M6+LUJy0LsgjNzIQeooeHzJwzImqggxoy+CMIQNhIYjdoCSAb3Bp637oK1oFZOaFzeeBiu4BLsADoor8yDwgM+QCPIC88D+gdMGBOnQ+D2Dn8wAYPvADtvopoJ7QLGwAAAAASUVORK5CYII=',96,29),(19,1,'image/png','17.png','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',135,29),(20,1,'image/png','18.png','iVBORw0KGgoAAAANSUhEUgAAAC0AAAAdCAMAAAAJvhUFAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFKCgogoKC8/Pzia2KKXspIWIiZWVl3t7ebm5u2NjYsLCwXV1d7OzspaWlTU1NycnJaWlpnZ2d1dXVeHh4t7e3V1dXfHx89PT0JGslUlJS6urqdHR0hYWF29vb1NTUj4+PcnJywsLCGk0auLi4MJEwrq6ui4uLLosuwc3B4uLi4ODgOjo6mJiYGEoYbGxsW1tbtbW1REREqampQkJCoaGhFkMWlZWVHFMdWFhYFT4VMDAwWYJaNjY2////+Pj4SEhI6enpJnQp/v7+K4Ar0dHRs7OzjIyM+/v75ubm7u7u/f39YmJi9vb2+vr6d3d39/f3zs7Oz8/P7+/v8fHxf39/iIiI7/LvkJCQ/Pz8xMTE5+fnYGBgjY2Nv7+/cXFxurq6k5OTJlMoJXEnobCho6OjsrKyapZql5eXwcHBY4BkPz8/jLaMXY5dHlsgSkpK0drRy8vLK4Mr5OTkM1Y0N2A3JEomd5l30tLSH10fKHYoo7aj6OjorKys0NDQVnhWmpqaMlFzdgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDyWwU0VxAAABnklEQVR4nO3RSVuCUBSA4ZspCqKmGUpqapZlmkZFqQUZDeA8a2WlzfM8z3+9Sy4K2LRo2bu5nPN8wOKC/ftRmqbfnxnodFutPoHn8Oi05ulqaEABMMwtHYPEekZjt3eKNd3/aH9SLyjA+uIoFrvZgVFta/pMI9a1uy344va8AmC++UF8Nki0n03BCWpO4Uc9DuNjilR9DShlJHsUgMfablFnPB6kjD1RhyCOLlKPdIV1MgCJkJtEpWI8pyYoY7Rr0xHWIzkHSaZ1REfVvywFnLkwkk6nG/pIpBEm1g9C9YquEY0guWrI8IotSYHdKSeRc4i6O9yTWLOJTfoJj4fwm7ENU3FRCqxax81Tbve6u/VhGFt9QNHiIWZu1UMGqwkdERJSIIBeen172CBm3TBZAr3J5Jq24PWN+bxFbaKcZ6WALSlo0cVC4c3iCuAqluczJbjos7iucRvPMVKAZ20l/EUQ1nqTqjyf4jieVZXwRAIvZ7IpRl5z3ArP5jOZPJuFLdxwKT4r/jW7wsk/zbTvkhP92MpGWf1b//V//ff1JzUOJiSjCuOwAAAAAElFTkSuQmCC',45,29),(21,1,'image/png','19.png','iVBORw0KGgoAAAANSUhEUgAAANQAAAAdBAMAAAAgF19XAAAABGdBTUEAALGPC/xhBQAAACRQTFRF////MJEwSEhIK4Ar0dHRJnQpjIyMbm5u6enps7Oz+Pj4V1dXvnVGvAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEQLBGM/FAAAAPUlEQVR4nO3B0QAAIQAFwVVIIYUUUjiFFFJIIYUUksti7+fNUDQ0DZ+GquFqmBqGhq2ha1gajoaIiIj4yQNdPNCk9vSy9QAAAABJRU5ErkJggg==',212,29),(22,1,'image/png','20.png','iVBORw0KGgoAAAANSUhEUgAAADcAAAAdCAMAAAA5MgQkAAAABGdBTUEAALGPC/xhBQAAAYBQTFRFLXYrMKWDJnQpdXV13t7ebqZ9Z11cH2tLxMTEKmZWvb29fHx8M5kzMKB5ZKSAK4ArLIUsKXsraGhoSaWOMpYySbKSXV1dMJEw9vb29PT05+fn4+Pjzs7OysrKUlJSYWFhioqKL40vWFFOLoMtrq6uq6urNJIxN4QvpKSkbGxsMZIxT6WNXlZTNnovLYgtamFf////+Pj46enpjIyMV1dX0dHRs7Ozbm5uSEhI/v7++/v7Z5x+gICAZWVlTEpKVlZWUk5Mm5ubaZ1/1NTU8vLy2traQYA3Q4A5XqOEQYg1U6WM0NDQS0tLVZNKK4IslpaWJXo46+vrInM4bJ19XI9Ya5x6NquL5OTk19fXbq51UolIY1tYY6+FZp5lZpdrQJc2QUxKR0dHarCCW7GMXZBa8PDwtra2/Pz8TJtAkpKShYWFWKWJTodEsbGxMKJ/RYo4UZBDuLi4YZpcVVVVJnoz7u7uTk5Oal9eOKyMcrKCUZ1DZphuY6ZdPpU0XaNTLYArqygHCgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEiFIUu10AAABG0lEQVR4nGMQxwBqtRl1kUnJohWMjHm8QMAYJhpTWVMVr6alJSLCAwUM/JigXt2zoIiVz8NLWxgItLP5WGN989WV+f0E9BTFoXoZmLABBl23nJC4UCdra2un4OqUKHddiDpBfn6gXi2gTgYL7MDOQUknmq1cX58tWkfJwQ4imJjAzh4kyA/SyWBCArAvlpMr82Ev8dNTZDAnAWhqCtnKi9nLcQb4MRiTAhSybLiZNW3FTDgZzEgAuSqqGo6ZCjbMmvIMpiQAbxlZDq40FQ1/BW4GIxKAlJQ0i6uzDEehqiODIQlAQkLSpdQonMVZlovBgBRgaZluZSiRaiQdQZo+iF4rQ5dA0vWBtFoZkqUPaCd5+gwMRvWN6huO+gBUxSlhaWAmrQAAAABJRU5ErkJggg==',55,29),(23,1,'image/png','21.png','iVBORw0KGgoAAAANSUhEUgAAABsAAAAdCAMAAABsU+d6AAAABGdBTUEAALGPC/xhBQAAAYBQTFRF1dXVMZIxysrKLIUuj4+PRqNGL40vMJEwLosuNJk1M5kz////+Pj4jIyM0dHR6enpMZQxLokubm5uKn8qs7OzM5gz/v7+Mpcy8vLy+vr65ubmMpYy/f399fX1/Pz8PZ49+/v71tbWwcHBmpqaNZo1p6en8/PzxMTE7+/v4ODgMpUyP58/3d3dubm5O507KYlDpKSkvLy8wMDAd3d319fXNZZdPJtW5eXlx8fHM35obW1tP55FQqFCh4eHVpyKMJEzUWNgb29v3NzcYa9nPJ1Brq6uRaJFiYmJtra2YnVxYHhztLS07Ozs7e3tOZw6XK1czc3N5+fnWVlZ2dnZZ2dnYmJi2trai4uL7u7u09PTk5OTz8/PaG9u4eHhUJOCrKysNWhcfHx8MZY1N5s3KIVAgICAv7+/Koc8pqamnZ2dKH40X69f4+Pj0NDQSKNI6OjoqKio+fn59vb2IXVOKYtO6+vrJIFFXK1i8PDwU6lTLo0xT6ZWKYE0JoA/oaGhKpBJ20VMUAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEgFzPM28AAABHklEQVR4nJ3QV3OCUBAFYCwocgE1kSsgIHaNJb333nvvvffek7+evMTL4CYP2ddvzjkzS7El5/OXl/EBwctR/zXxD7MFrUrM764Qf7MTh8sSJTZz62BsIRG07vmtN8ZtRmL8dkvXO2OuJbYZG6ydezBvErPnEyv7a3suH2DrVCpw3D58SCaJSd7cVL6KH3kpthIztKWz5YOEs7n4A2JY5yQlEr1yVv4EiSGsysnLupqjxc6gNYcQwkZymqr+uAsBhrAsNQpDsR3WU2oIXz9l7P2f7C5gSOXGFqIbnnAcMKxrijDaN1AA7HsynUs10GHIkKop5/VZ2LA8PtsxkY1DhnQp09RKF0DDN8rq63MbaIi7jzxO0rAZ6Z7e0wuTfQFeq0OgPT4GhAAAAABJRU5ErkJggg==',27,29),(24,1,'image/png','bigcan.png','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',57,102),(25,1,'image/png','rightside.png','iVBORw0KGgoAAAANSUhEUgAAABsAAAABBAMAAADdt8ibAAAABGdBTUEAALGPC/xhBQAAACpQTFRF////M5kz+Pj46enp0dHRSKNIYa9ns7OzX69fMZQxbm5ujIyMLokuKn8qkFM2xwAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEQ+/qbN4AAAAFUlEQVR4nGNgAAEms1bBObe2OysAAA8ZA1/3yOr8AAAAAElFTkSuQmCC',27,1),(26,1,'image/png','bot_01.png','iVBORw0KGgoAAAANSUhEUgAAAIgAAAAUCAMAAABhwIVYAAAABGdBTUEAALGPC/xhBQAAAURQTFRF/8xm////M5kzYrFi6enpSKNIX69f+Pj4NJk0+MZj/stm0adUs49IMpcyMZQxjXE46bpdbm5uM5gz+8lk57lcLokuNZo19cRiNps2MpUy8sJhJXo8L4wvMpYy/cpl4bRaOp06/Mpl7L1e+cdkOJs45bdc9sViMJAw7b5fyKBQUJBlsI1GQJ9BI3tPL44vR3FOO5079MNi6LpdJn4+K4Y0JHpURYxiL5I2h2w206lU+shkL44xLopeQ3NoKY1PzKNSvZdMzqVS98ZjoIBAMZIx6rteQaBB2q5XJ4VFl3k8wptOL4wwJXs8p4ZDjHA4SJRry6JRg2k0upVKz6ZTMZQyqIZDKopHPZ49rYpFN5s4269YJ4JA2K1WtZlQj3I53bFY16xWpoxJ8MBgTIFaim432a5X88JhOZw5so5HLYoy4rVaMI8wO7DSKQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDxII7uB+AAAA80lEQVR4nGNgxAHYWZjZWJn4RKUF+Xm4BTgZaA6GjEN8BodDJGWMB4dDeLOjB4dDhFwybf14uI0G3CHAuDFN0Q4WcRpwh8jq6VpE2KuKcw20Q3jVTQJ9NeXT5AbaIUJ83vrOXvFJwgPtECZZdQ/PoAQl5YF2CIcQn3VcBreIGs0TCaEQYeLlCwvVlJdSHGiHcHDwhiQ7pGoo0zpICDpEjIPXPNFRVZzWQUIwajjEmHjtYt01OGmcgwk6hElMRUXIJiBLyoq2kUPYIRwSCulMZpYihsI0dQlhh4BcYhCpFeMqTlOXEOEQoEvC3XT8o5TUaOgSAFQ6ILJUFGniAAAAAElFTkSuQmCC',136,20),(27,1,'image/png','orangebit.png','iVBORw0KGgoAAAANSUhEUgAAABAAAAAUAQMAAAC+rC80AAAABGdBTUEAALGPC/xhBQAAAAZQTFRF/8xm/85tq+lszgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDitO8Fk3AAAADklEQVR4nGNgYGQgFwEAAooAFbycyRkAAAAASUVORK5CYII=',16,20),(28,1,'image/png','bot_02.png','iVBORw0KGgoAAAANSUhEUgAAACQAAAAUCAMAAADSpG8HAAAABGdBTUEAALGPC/xhBQAAARdQTFRF////M5kz+Pj46enpMZQxs7OzLokubm5ujIyMKn8q0dHR/v7+NZo1SaRJRaJFO507NJk0YbBhpdKlVKlUN5s3iMOIPZ49/f39qdSp9vb2hcKFc7lzbbZtdbp1hsOG+vr6n8+huNvBaLNofr2XqtSrnc6dcbd2QJ9AtdnBQaBBSqVK/Pz8ZrJrqtDGfr9+a7VrPp8+qtWqS6VLcLdwdLl05/Lv8Pf1gsCFptKy9fX1bLVsf79/Wq1ahcKGW61bksmSsti8Z7Nne7uMf7+Er9a2YK9gocm/Q6FDh8OHWKtYm82b+Pv7+/v7Nps2Xa5dicSJfb59brduk8mTk8mV7fXz8vLyXq9eU6lTvNvSmsqsjsSfuNu81unk0FbGEQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDwGMUKGgAAAAuElEQVR4nI3OxRLCQAAD0Aq+BUoNKO7u7u7u+v/fQQcGuJRdcn6TBMNkQpBxkx7HFSqtWqPUkYSckVCw8wfq2dFo2EgZUMjqSw4EBAKVroPlEMh8KOWyNALNE6uqdAmOiKvTJCAQsO7WJw6BYpfWxkDDEXC5+aYRhyNzeWrTU3AEzow4eRb9RqDg33pHFBzt+4F8dIFD0azN1FgjDUUgbBHr6dfYL1T0ZPhl6G1kEbjfLONj5GO+6AGxVxblhVC64AAAAABJRU5ErkJggg==',36,20),(29,1,'image/png','bot_03.png','iVBORw0KGgoAAAANSUhEUgAAAnIAAAAJCAMAAACBijGMAAAABGdBTUEAALGPC/xhBQAAAWtQTFRF////M5kzWaxZpdKnjseO/8xm+Pj46enpSKNINJk0X69fKn8qLokus7Ozbm5uMZQxYrFi0dHRjIyM/stm/85tPZ49NZo1Nps2OZw5fb59QaBBR6NHUahR9cRiQJ9AU6lTUKdQOJs4SqVK/v7+VapV+MZjj8ePa7VrodChSaRJ/Mpldbp1/cpl+8lkXa5ds9mzWKtYi8WLs9m0vN3NYK9gn76YgcCBlMmU67xeisWKZ7NnsdixaLCDgsGCcLdwutvCicSJh8OKtdq2bbZt8sJhQ6FDisGggL+AZrNmqdSrudy+mMuag6h7PJ08N5s3lsuWabRpuNrMxLVr7b5fXKp1XK1cc7lzuty/rtavRqNGZLFkfr9+iMOIvt7Rg8GD+shk+fv7Pp8+n8+fTqdOrteuVqtWrNWsVqpistfGjMSZoM+grdatsNe7d7t4zuXgk7qVbLVsisSMYbBho9GjZbFoe7yK1unl+cdkotCk8RkA1gAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDSGFCOPqAAABMElEQVR4nGNgwAHY2AW4OBhhgFNCSkk+3cdCVleYlS5ABJe7RsGQAMrGThrRTHJi8BTEyM/DzSfEK8jOhlMPapJj5FSUSmYqCglyUdWiS6IbTXJDGuSFp5jbKYgipTjSkxwjp3iikgFXiU2wbLwOHRLdaJIbwqDMXj/L1iRSgpORoiQHqlxdFfTUc0vzZcu1hGmd6kaT3FAFyrFe+sWW6jIoRRyZSY6RUcxXmilAW9Ix081UVZe2Zd1okhuKICEwI8coOynGWUXKj5ORGkkOWLuKcqhEhUm6mxV6VzCPglGADDwjrNM0/A0lPWSkFNETHNlJDtyPkNOUD7XSjjNkGQWjABmoqTlIFujJSIuKY0k3FCQ5SKsOmOwMmEbBKEABqQo4khvlSW4UjAKSATzJAQAIxkfEGVHCJAAAAABJRU5ErkJggg==',626,9),(30,1,'image/png','bot_04.png','iVBORw0KGgoAAAANSUhEUgAAAIgAAAAZCAMAAADdXpaGAAAABGdBTUEAALGPC/xhBQAAAe9QTFRFM5kz////M5gz6enp+Pj4/v7+MpYyMpcy+/v7NJk0SKNIX69fYrFiMZQxMJAw9/f3OZw57+/vLosuMJEw7Ozs/Pz8MpczwMDANZo1ra2t29vb+fn5+vr6MpUyMZMxfHx819fX/f39lJSU6+vrm5ub8/Pz8vLyL4wvL44vy8vLRaJFWGpnXq9eNm9h+fz58fjxW61bqqqqMZIx2+3bN5s3tbW1lpaWTKVMLoku09PTJH5I1OnUvLy8KItayOPILIMsqtWqJ3450dHRUqlSh8OHcrly2OvYxsbGKIdct7e3hoaGNps2P58/TqdOgICAJYFJ4+PjLoouo6OjMZQywsLC4ODg9Pn0Lo81IXJS4uLiPZ49ptOm6fTp7/fvysrKJoJRaLNovN28nc6dt9u35OTkYK9l9fX1PJ08LYgtVapVWY2BLpBmQqFCd7t3j4+PodCh0OfQrq6us9mzVqtWr9evRqNGgsGCksmS3NzczMzM7PXssbGxbbZt4vHijcaN5ubmu7u79vv2R6NHKoAu3d3dycnJzOXMMZY2+/37LIUt9PT0mMuYfb593+/fr6+v1tbW1NTU4eHh6urqyMjIe3t70tLSI3lQLYYtJoFC/f79ZJ6QVali2dnZZLFkxOHEJoFEwN/AQJ9A5fLlQ59S7e3tmrCc/gAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDTjhY0sqAAABVUlEQVR4nGNgxAFYmHm4uRjoCEYdMgQdkqrTNDgcMjtTa3A4ZHGpz+BwSDanwOBwiKUYJ+cgcIi08oR2Bu9B4BAZEYVZ7IMhRDgEK0LEmAaBQxRVA+Js2Zjo5xRcDmHlZ55qGozpEk4JCRMBgfSohTlaXOa+zsUG3HMTalxze8pc6sq7k/KjHUqKkhMXzLNrK7B2M+6tXRRTFasf1qjXMROXRVCA2yEyglLiHrzsdAsSnE4U7UqpLAynn0twOoSVRUTBJotPjl4uwR1rHGoTWw3jNYzY6OMS3A5h5Z/U36Diry5MH5fgcQiHWqSmZN58Ifq4BLdDGBX5ReaIB3nNEBKWpYNL8DiElUO13slTxc/KQoMOSRaPQxhZpQVDNavN5Kd1BvLJ0top+BzCKMq/pGVyn9IUXcfpQny8tE0qeB3CypGmLOUuqSSvHdFsn6EOcgutHAMA1mI82Up6aVEAAAAASUVORK5CYII=',136,25),(31,1,'image/png','bot_05.png','iVBORw0KGgoAAAANSUhEUgAAAFIAAAAZAQMAAACYfet3AAAABGdBTUEAALGPC/xhBQAAAAZQTFRFM5kz////Gb/48AAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDzWt5FUVAAAAFElEQVR4nGNgGMTgPxwcGGingAAAyM4Kt4MBRZAAAAAASUVORK5CYII=',82,25),(32,1,'image/png','bot_07.png','iVBORw0KGgoAAAANSUhEUgAAAJ8AAAAZCAMAAAAYLAwXAAAABGdBTUEAALGPC/xhBQAAAH5QTFRFM5kz////icSJRaJF6fTpPp8+5fLlv9+/UqlS/f79yeTJzufOtdq1O507crlymMuYr9ev8fjxxeLF9Pn02OvYkciRTaZNOZw5Xa5d+v36WKtYSaRJ7fbt9/v3ebx5pNGk0+nT3e7dns+eqdSp4fDhudy5gcCBa7VrZLFkQaBBoDndUQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEilGiWVGAAAASUlEQVR4nO3QtRHAMAAAMYeZmZn2XzB9+pyb1wgSAgAA4EORw2mGLOl1c1/y2XNT6/DjbVqjQj3H8Lrr1i477TGCSnYPAAB/eAHOAQPU1sUyqAAAAABJRU5ErkJggg==',159,25),(33,1,'image/png','bot_08.png','iVBORw0KGgoAAAANSUhEUgAAACQAAAAZCAMAAABuOnzZAAAABGdBTUEAALGPC/xhBQAAAfVQTFRFM5kz////M5gz+Pj46enp0dHRMZQxbm5ujIyMs7Oz/v7+MI8wLokuMpcy+/v7MpYyMpUyLIMsRUVFK4ArVlZWKn8q9/f3J3YnWVlZL4wv7Ozs7+/vLYctm5ubTExMK4IrfHx88/Pzj4+PlJSUra2t/Pz8aGhogICA29vb+vr6y8vLSkpKJ3Un8vLyt7e3NJk06+vrqqqqR0dHKHcoZmZmL44vu7u7HWlG3d3d19fX+fn5S0tLMZMxUFBQMZIxKHko/f39MJAw9PT01NTULlxSrq6uHWhJKXopl5eXoKCgXl5eUlJSr6+vNps2J1pNb29vTk5O9fX12dnZfn5+HWtEXFxcNZo1Kn4qKn0q5ubmKXwpJ3QoYmJihoaGysrKOJs4LosuvLy8KX4vHm0+H20+c3NzWFhYJXMrHmtCI3EyfX19IG47JXY14eHhlpaW1tbWJno04ODgRkZGY2NjUVFRcnJyLYYtsbGxJ3Uq09PTd3d3k5OTbGxsZWVle3t7OkpHxsbG5OTk6urqtbW1ZGRkdHR0cHBwK4Eri4uLwsLCIGpOJ3Yoa2tro6Oj4uLizMzMIG85LIUsjY2N7e3tWlpayMjIiIiILYgtLVdOkpKS0tLSycnJwMDA4+PjXV1dTU1Nn5+fJnQqKXspMJEwwcHBKHkptLS0j0tfGAAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNDxoGNWhMAAABWElEQVR4nGNgwA3YeETZOThZWZjxqKG/oiSCipjsZAoIKuLlls8hqEggIaKLfRJ+RUx2E5dKtCktq8SniNdUuGJK6Uw9ZwMr3AaxTXd38aryMLOU0sRtkKO8sW1x8IJOC2k+3AbxLAoR6ZlmGDdX0YEh3jdMXx+LQdzt3XKhrdXNs1jEuHAZZCNjb15Sq+65RENaE4ciJgHJ8HRtCX8O1/5GJ1VG7Gr4ueV1ZtjGKMgmWkoxc2FVBFQjKJ6hLcHeYTg7V5ePEZsisJpkI++WLFmz/CYxLmyKwGqyiwLmpSqpaPXp8mFTxCRgKig+QUhOoia2d06QlJgDI4YiJl4bSWGdFCFrETV1WT3WSEWQQYzoxnDLREWbA9WYKOSVaxVKW4HUMKKYwubII2yc6SckJ2JSp+wz1WKymCojsiImfrbFkmXC9gvd6o3mp6kpKKs0aBgEgi1jZAQAOAlDXbY72dEAAAAASUVORK5CYII=',36,25),(34,1,'image/png','bot_09.png','iVBORw0KGgoAAAANSUhEUgAAAnIAAAALCAMAAADMQpCHAAAABGdBTUEAALGPC/xhBQAAAadQTFRF6enp+Pj4K4ArMJEwSEhIV1dXjIyMJ3Qn0dHRs7Ozbm5u/////v7++/v79fX1/Pz87+/vqqqq9/f3tra26urq0tLS/f39a2trXFxc7OzsmZmZ+vr6l5eXvb29y8vL3t7e2tradHR0+fn5cXFx7u7u1NTUiYmJX19fKn8qSUlJVlZWSkpKJ3Qo9PT0oKCggICAx8fHt7e3kpKSysrK5+fnWVlZI3EyYmJixMTE19fXtLS0tbW1sbGxg4ODxcXF9vb2pqamLosu2dnZnp6ehoaGxsbG8vLyjY2NJHEw29vbkJCQf39/z8/P8/PzTU1NMJAwWFhYeHh46+vrlJSUZWVlKn0qeXl55OTk4+Pj3d3d39/f8fHxv7+/UVFRoaGh4ODgY2Nj4uLiL44vubm5lZWV1tbWUFBQfX19jo6ObW1tRUVFLYYtenp6b29vrq6uyMjILIMsQkdGOkpHMk5JIG86IG9MJnQqR1tXKXstR0dHJ3UnL1JLHWlIS0tLUlhXIHA7IG46Ml5UOExIKHkoTk5ORVVSKXspKXoqLFVMJ3YnOVZQIXQ8PmRcIOo1cgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNECgDuDdSAAABxklEQVR4nO3W1XbCUBRF0UuFCglECAkuwaHU3d3doEbdoe7u+tGF9qHULun7md+wxh4bKbAIUqm2NEfnO/nXvUP35lr9bG8qAHi9s/VrKfdP+6cnq75Fge+cjzZb1EqS+GgK4ZN7b659o0pXLZYeXL08DLtTAEjCPXxz7N99vF713HpLxWpd1Ub7Z3FJk4s3NxAysQH5Ou86v3u+KGnzZwCA4W8rudzZ3jo6W0x38evyAGsKDXwWlzy5eHPacJeRNjT1r0wL3skenycNACyPr2fSK0yv9DcZaGNXWJtQnITkYs0x1Gh5HltncC5Z+SGXUNyXDgBGX7HgGuKtS05DHZtXPkoxCcVJSU5BqGQtaIyryacbC5y5I1Yxkg0ARkS0juQ6Cxrp/BpuDLXIVAnFSUpOQWjIWqrI7CicqKSndIMdy61yAP7UutwxqJuiKycKHeYiqpbUJBYnLbn40NmUFJqxcKYy4xxbkQUARgU7ZywzcZYZRCltXyZOenLxpdMz3XYUNDcsOLhMADA4x0KDOYjs3YxeQ3wvSXJysegIlZ7JGbdr1QgALLXWPp7D6FXEj+D+ldx7dRqStMkASMJGxh7cL73FvAEYetPVM4Of5wAAAABJRU5ErkJggg==',626,11),(35,0,'image/png','powerphplist.png','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',70,30),(36,1,'image/png','leftside.png','iVBORw0KGgoAAAANSUhEUgAAADkAAAABCAMAAABT7/Z3AAAABGdBTUEAALGPC/xhBQAAADNQTFRF/////8xm+Pj46bpddaV14+Pjs49ISKNIJXo8M5kzjXE4+MZjbm5u0adULokuX69fMZQxecNRpQAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSBAMNEAg41heaAAAAHUlEQVR4nGNgwAWYWFn42TkF+Dh4uNh4mbkZ0QEADb8Amb5Nx94AAAAASUVORK5CYII=',57,1),(37,2,'image/png','powerphplist.png','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',70,30),(45,2,'image/png','images/bg.png','iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKAQMAAAC3/F3+AAAAA1BMVEXKysSWXhchAAAAAWJLR0QAiAUdSAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9MDFQwVK+oK/2wAAAALSURBVHicY2DABwAAHgAB9v3plgAAAABJRU5ErkJggg==',10,10),(39,2,'image/png','images/transparent.png','iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAABlBMVEX///////9VfPVsAAAAAXRSTlMAQObYZgAAAAFiS0dEAIgFHUgAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfSCgkOHRU8teVSAAAACklEQVR4nGNgAAAAAgABSK+kcQAAAABJRU5ErkJggg==',1,1),(40,2,'image/png','images/top01.png','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',576,41),(41,2,'image/png','images/top02.png','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',132,41),(42,2,'image/png','images/top03a.png','iVBORw0KGgoAAAANSUhEUgAAABQAAAAiCAMAAABodAmPAAABMlBMVEX35sL36MXw0aP358Prwo/qwY3sxJH36MTtyJbsxpPpvYj35sHy2bLqv4r35sPqwYz35cL248L358LsxZLz3Lbw0qXw06f14sD35cHw0aby163rxJHx1qvuzJzsxpLy2LLtx5Xpv4nsxZPtyJjw0KT14cDw0aXx1KnsyJbqwI3pv4r14sLrwY7syJXz27Tqvonz3LXrwY/sw5H36MjtyJf36Mftx5buypr36Mbqvoruy5z14L3y2bDtyJXqv4nw0qjqwIzsx5bqwY/rw5HsxJDtyZf358jqwo7x1qzuzJvrwo703rr25MT35sf25MPrxJDw06jz27Xy2LD14Lzsw5Dx1Krtyprpvorty5zuzJ/uy53037zuzZ/24sLvzqD03bjz2rL46Mr14cLx1q7uy5ry166+d3MYAAAAAWJLR0QAiAUdSAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9IKCQ4cLpSlPTcAAAGxSURBVHicbYsHc9pAEIUXH3c6IVSQgkHICIyKaaaaZgjuvdtJ7MRO7LT//xeyJ5HJTIZv996+fVrBeiqFLaTdFvMRHdTVyaQ+qafac1VdT83b9TmGqq7u6lRV1ZquC6vWqA6UUp1G1Ght4YCe0Ob+fvK8mzw/aTa7zS7tUkguARwnefjhoHfTc5hzcOj02I3TA8ZYhbmMWYy5+CJAli3LYrLsym6lYsmWiwPDTbkcSzly5b1N4NU9Xh0Oy7zK32MP8XHY8jyPb3HOj464J6bHOYxGxeI29qen0Xbx7NfZ5+LLF/jm+7a9Y/t26Ie+vbpza9u3sPHzPl2Kq1RKI3d3aehcTHONIMgFjUbwI/jayGSur0ExwDAgbyh5wAEoBixHgbyiAN4oIIaCi/hHiT79uzJwMYXTsCIDJFaiEdCIZprCmdgmZkTTyF9iBySBKkmJBJGwsXCBhEASQoj4gIsIpTjGkuKLxeV/LMLxeDwYDPr9/vfj46sreJ3mMpeXGUEOmT48PF/ArNV5F9PptFqtt/Rsdg8f1wqF3wXk9HRNsIFANrsakRWEYbiCwMoSloZ/AFuCOQKHqos8AAAAAElFTkSuQmCC',20,34),(43,2,'image/png','images/top03b.png','iVBORw0KGgoAAAANSUhEUgAAArAAAAAiCAMAAACQqxrlAAADAFBMVEXw0aPvz6DuzJzvzp/rwo/tyZjqwY3uy5rsxpPsxJHtyJbpvYjvzqDqv4rw0KPw0aLtyJjuzJvuy5zsxZPtyZfsxpLqwYzqwI3w0KL35sLvzZ/vzp7typruy5vuypnvzZ7tyJXuzp/syJXuzZ/tx5Xuzp7ty5nty5rsx5buy5nqwY/uyprsyJbsxJDtyJfqv4nqvorsxZLqwIzpv4rtx5bqvonpvorsw5HrwY7qwo725cDrxJHrxJDsw5Dpv4nrwY/rwo7rw5Hqwo/14rz25L735cL358P25MD14Lj36MX35sH14bv35cH25b/358L25L3w0ab36MT2477w0aXw06f14rvy2bL35sP037z14sDw0qXx1Kn25L/x1a303rrx1q7y2LL03Lj03bjz3Lbx1qvy16724sDw06j14L3247335sTz3LXw06r25cTz27Xw0KTz27bx1Kruy5325MT248HuzaDy2bDx1az03Lnw06vz27Ttypv36Mb36Mj14brx1qzy2LD358b14L7w0qj25cLy2LH14b3y17Hvz6TuzKDz2rX03rvy163037325MP248D25cX35sbuzaH137j248L04Lj14cD14r303br25cPvzqH358j36Mf35cTuzqH04Lzw0qnx06n03bvuzJ/24r/247/x1q/wz5/14Lzx1Kzw1Krsx5f35sjtu3fx1a/y2K/z27fuzqDty53z2bLx167wy5f25MXv0KT24sL248Pv0KPw0qfqwI7wzp3sxpbuvXvw1Kny2LPtyZv037n037j03rn148Hkl1Pqv43tunPtuXPvyJDwzZvw0anw0ajwypTx1a7uwYPuwYHx1anuwoTy17Dwz57wzp7vz6Xv0KD25sTvxYfuyp3wzZrvzaD46Mrvxory17Luv33vw4b148Lx1avz2rTy2rXw1Kf14Lv04L3wz6Puypzz2rLtunb35sf25MLtx5jtyZr36Mr24sHrw5Lwy5XvzaHw0KH03rzjpGrttm/vyI3vyZD35cfx163+FRNgAAAAAWJLR0QAiAUdSAAAAAlwSFlzAAALEgAACxIB0t1+/AAAAAd0SU1FB9IKCQ4dCrG96KcAAB0TSURBVHic7VYFeBtXth4xRCzZksWyLLTMkiFmB5w0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',688,34),(44,2,'image/png','images/top04.png','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',132,34);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_templateimage` ENABLE KEYS */;

--
-- Table structure for table `phplist_urlcache`
--

DROP TABLE IF EXISTS `phplist_urlcache`;
CREATE TABLE `phplist_urlcache` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `lastmodified` int(11) default NULL,
  `added` datetime default NULL,
  `content` mediumtext,
  PRIMARY KEY  (`id`),
  KEY `urlindex` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_urlcache`
--


/*!40000 ALTER TABLE `phplist_urlcache` DISABLE KEYS */;
LOCK TABLES `phplist_urlcache` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_urlcache` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_attribute`
--

DROP TABLE IF EXISTS `phplist_user_attribute`;
CREATE TABLE `phplist_user_attribute` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `type` varchar(30) default NULL,
  `listorder` int(11) default NULL,
  `default_value` varchar(255) default NULL,
  `required` tinyint(4) default NULL,
  `tablename` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_attribute`
--


/*!40000 ALTER TABLE `phplist_user_attribute` DISABLE KEYS */;
LOCK TABLES `phplist_user_attribute` WRITE;
INSERT INTO `phplist_user_attribute` VALUES (1,'First Name','textline',1,'',1,'name'),(2,'Country','select',2,'Netherlands',1,'countries'),(3,'<b>Please check all that apply</b>','radio',3,'',0,'bpleaseche'),(4,'I think PHPlist is brilliant','checkbox',4,'Checked',0,'ithinkphpl'),(5,'I think the price of software has no relationship to the quality of software','checkbox',5,'Checked',0,'ithinkthep'),(6,'I think it is obvious that in a few years\\\' time all software will be opensource','checkbox',6,'Checked',0,'ithinkitis'),(10,'<b>Where do you connect to the internet</b>','checkboxgroup',7,'',0,'bwheredoyo'),(9,'Where do you connect most often','radio',8,'',1,'most'),(11,'Some more comments','textarea',9,'Please use this area to describe how you think that the persons who make open source software actually make a living, if you didn\'t pay them any money?',1,'somemoreco'),(12,'I agree with the terms and conditions of use','checkbox',10,'',0,'iagreewith'),(13,'Last Name','textline',2,'',1,'lastname'),(14,'Public Key','textarea',6,'',1,'publickey'),(15,'Fax Number (numbers only)','textline',5,'',1,'faxnumbern');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_attribute` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_blacklist`
--

DROP TABLE IF EXISTS `phplist_user_blacklist`;
CREATE TABLE `phplist_user_blacklist` (
  `email` varchar(255) NOT NULL default '',
  `added` datetime default NULL,
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_blacklist`
--


/*!40000 ALTER TABLE `phplist_user_blacklist` DISABLE KEYS */;
LOCK TABLES `phplist_user_blacklist` WRITE;
INSERT INTO `phplist_user_blacklist` VALUES ('billgates@microsoft.com','2005-09-08 16:42:14');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_blacklist` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_blacklist_data`
--

DROP TABLE IF EXISTS `phplist_user_blacklist_data`;
CREATE TABLE `phplist_user_blacklist_data` (
  `email` varchar(255) NOT NULL default '',
  `name` varchar(100) default NULL,
  `data` text,
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_blacklist_data`
--


/*!40000 ALTER TABLE `phplist_user_blacklist_data` DISABLE KEYS */;
LOCK TABLES `phplist_user_blacklist_data` WRITE;
INSERT INTO `phplist_user_blacklist_data` VALUES ('billgates@microsoft.com','reason','I\\\'m not really that interested in your newsletter anymore. Sorry.');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_blacklist_data` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_message_bounce`
--

DROP TABLE IF EXISTS `phplist_user_message_bounce`;
CREATE TABLE `phplist_user_message_bounce` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `message` int(11) NOT NULL default '0',
  `bounce` int(11) NOT NULL default '0',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`,`message`,`bounce`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_message_bounce`
--


/*!40000 ALTER TABLE `phplist_user_message_bounce` DISABLE KEYS */;
LOCK TABLES `phplist_user_message_bounce` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_message_bounce` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_message_forward`
--

DROP TABLE IF EXISTS `phplist_user_message_forward`;
CREATE TABLE `phplist_user_message_forward` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL default '0',
  `message` int(11) NOT NULL default '0',
  `forward` varchar(255) default NULL,
  `status` varchar(255) default NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`,`message`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_message_forward`
--


/*!40000 ALTER TABLE `phplist_user_message_forward` DISABLE KEYS */;
LOCK TABLES `phplist_user_message_forward` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_message_forward` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_rss`
--

DROP TABLE IF EXISTS `phplist_user_rss`;
CREATE TABLE `phplist_user_rss` (
  `userid` int(11) NOT NULL default '0',
  `last` datetime default NULL,
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_rss`
--


/*!40000 ALTER TABLE `phplist_user_rss` DISABLE KEYS */;
LOCK TABLES `phplist_user_rss` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_rss` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_user`
--

DROP TABLE IF EXISTS `phplist_user_user`;
CREATE TABLE `phplist_user_user` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `confirmed` tinyint(4) default '0',
  `entered` datetime default NULL,
  `modified` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `uniqid` varchar(255) default NULL,
  `htmlemail` tinyint(4) default '0',
  `bouncecount` int(11) default '0',
  `subscribepage` int(11) default '0',
  `rssfrequency` varchar(100) default NULL,
  `password` varchar(255) default NULL,
  `passwordchanged` datetime default NULL,
  `disabled` tinyint(4) default '0',
  `extradata` text,
  `foreignkey` varchar(100) default NULL,
  `blacklisted` tinyint(4) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fkey` (`foreignkey`),
  KEY `index_uniqid` (`uniqid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_user`
--


/*!40000 ALTER TABLE `phplist_user_user` DISABLE KEYS */;
LOCK TABLES `phplist_user_user` WRITE;
INSERT INTO `phplist_user_user` VALUES (1,'html@testdomain.com',1,'2002-05-31 09:50:42','2005-09-08 15:37:06','fccc8f74d93746c87b283118a1782d4a',1,0,0,'','',NULL,0,'','',0),(3,'blahblah@localhost.localdomain',1,'2002-05-31 09:51:36','2006-12-13 01:47:59','ca17bbd22c9b6e4f46873da6b24fc886',1,0,0,'','',NULL,0,'','',0),(4,'anotheruser@anotherdomain.com',1,'2002-05-31 09:52:18','2005-09-08 15:38:14','a407ffbc6f4aa66a2fc5635af0342a33',1,0,0,'','',NULL,0,'','',0),(5,'user4@hotmail.com',0,'2002-05-31 12:15:59','2005-09-08 15:39:11','7304ea28bb8ab6733f923cf3ea7451ec',0,0,0,'','',NULL,0,'','',0),(8,'billgates@microsoft.com',1,'2003-10-20 01:06:40','2005-09-08 15:42:14','60de27fe99c566f73d03c29959ba7d3b',1,0,2,'daily','iwantyourmoney','2003-10-20 01:06:40',0,'','',1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_user` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_user_attribute`
--

DROP TABLE IF EXISTS `phplist_user_user_attribute`;
CREATE TABLE `phplist_user_user_attribute` (
  `attributeid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `value` text,
  PRIMARY KEY  (`attributeid`,`userid`),
  KEY `userindex` (`userid`),
  KEY `attindex` (`attributeid`),
  KEY `userattid` (`attributeid`,`userid`),
  KEY `attuserid` (`userid`,`attributeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_user_attribute`
--


/*!40000 ALTER TABLE `phplist_user_user_attribute` DISABLE KEYS */;
LOCK TABLES `phplist_user_user_attribute` WRITE;
INSERT INTO `phplist_user_user_attribute` VALUES (1,1,'HTML Test'),(1,3,'Blah'),(1,4,'Some other test user'),(2,1,'222'),(1,5,'Name'),(2,5,'158'),(5,5,'on'),(4,5,'on'),(6,5,''),(9,1,'1'),(4,1,''),(5,1,''),(6,1,''),(10,1,'5,1,3'),(2,3,'0'),(4,3,'on'),(5,3,'on'),(6,3,'on'),(9,3,'5'),(10,3,''),(2,4,'11'),(9,4,'1'),(4,4,'on'),(5,4,'on'),(6,4,'on'),(10,4,'1,3'),(11,1,''),(11,3,''),(11,4,''),(11,5,''),(12,1,''),(12,3,''),(12,4,'on'),(12,5,''),(1,8,'William'),(2,8,'223'),(3,8,''),(4,8,'on'),(5,8,'on'),(6,8,'on'),(10,8,'4'),(9,8,'1'),(11,8,'What a nice app. Cool!'),(12,8,'on'),(9,5,'0'),(10,5,''),(13,1,NULL),(13,3,''),(13,4,NULL),(13,5,NULL),(13,8,NULL),(14,1,NULL),(14,3,'-----BEGIN PGP PUBLIC KEY BLOCK-----\r\nVersion: GnuPG v1.4.5 (GNU/Linux)\r\n\r\nmQGiBEVRKmcRBAC1VrGyOt4W/tuAPvwULudPujp64I0sc5bSE0uf1x9HWbfSJGaR\r\n8B4VoUvP/mWtIHBEy7EsBFRk8MUn/nB4qyHgsUXXwgTV0HKTV2N1JA2wn4W0l3nZ\r\nBHmVkUbDdmjfA7FM/4CljLkQFiSgwP4ofgDg/sR7kGn5xEFtSkqRI4enJwCgtvQi\r\nhVljgIE983nNAhxusQxzamkD/iGYpYR0FCHJgRjFYZvb6fEhGQesjqLrct3MRm/1\r\n3yD5tq2DEvFK9hl+G4q5R3VZ9eFDPaA5ZeP+1j4Znv1u6wyCvgc3xun+yzNTHhNx\r\nRnr9VpcTdC29F9ex7JSQzJzZ1i30hF5fya0ZlTOTsq9yOS1+SnN/9MxFPtevDE5o\r\nZEkdA/0aSkGJeRo1QkennZrce1cXoqsCCFFJadLhxjlF7FUvalZLMB6MAPzEPHi5\r\nBILpXyGbfmv+L3X1PSsTGt2KPHnhfN4m0EHeQwT5+YlRwF1Wf97ceeGu0T2RWaqV\r\nULRN7oNF2tfmvl5zZRSzvwcjHc7uJEUcg7WYHq618Sp0MJlQX7Q6VGVzdCBVc2Vy\r\nIChLZXlzIHRvIHRlc3QgdGhlIHN5c3RlbSkgPHRlc3R1c2VyQHBocGxpc3QuY29t\r\nPohmBBMRAgAmBQJFUSpnAhsjBQkCJzDKBgsJCAcDAgQVAggDBBYCAwECHgECF4AA\r\nCgkQ6TMjpA9pMzJi2wCfXWaV9oQC5hWhC9cq3P5RoxQPObkAmwWcaUE3RnE9zof5\r\n4VC5ocR9OBL5uQINBEVRKnAQCADDBBj6iNXza2TvpZe3aFmTbIxkMryRgoIie+MA\r\nSxn+/OR57MDM9AJ4xhW9xl6neu0eygw0mbZGl+HTX4AjAGVIMSUGHno4cpv9VLA+\r\nSyElAJI6+pKTGPxzm+yX+ic7b8/O5s5MN9W9PMOT0gBwT+dBdZ03rgtCpOpKINbg\r\nqhseJlxl+b1nrDyjf+1pq+O3ZIlDMrCd6TlbadBJd9ntY7Wj9MgtqwOIbozSD9LT\r\nSZ7dyWgvebj9aJ7YU/E2tqr5GweXwRpLO3AEZyBhOUE7gj5Kl8DlYR6BDC2Lns5f\r\npFDf7XR/+q/r79emMGHNKQE9rNA/zwmcTWxw3ZzEsRJd4AKDAAMFCACLUhCcytbo\r\n2wPEre/eCjERWLQen2Qxs9uipzjr++sHlcfnWIYOCe2WwNRFycfpi8SOLDIEm9gn\r\nB3jBlIQNNbI2crxHiFHmtPwWlSnJPenVrbAW+bzuoko6TJvTcOCfgu+L452n+o2H\r\npbzt9cgOfL3/43dDtxkdEwOTNMOBnPFTWV5dtJ0Jp0eK1Zz/AHpmbY0993zpdMDC\r\nQfJyj8VEgUEHAQ21D7stWo437w4Jg2LJugtXSfHWT8A2dBXXhCJSS9swA7Qi+9l+\r\njOld8YZZRxAVpJSvHFrCwQXuKbaC/u1kMP/IguMjbI1PXS+GLVuZIGfhzX2O4TCf\r\nQTn5g58y8QqliE8EGBECAA8FAkVRKnACGwwFCQInMMoACgkQ6TMjpA9pMzJ/XQCf\r\nQdMvZgIGvg4Tb55e/5FHEODGWZwAn0JX78nLRgp77qKOj0yZWIeTqmGW\r\n=h+2D\r\n-----END PGP PUBLIC KEY BLOCK-----\r\n'),(14,4,NULL),(14,5,NULL),(14,8,NULL),(15,1,NULL),(15,3,''),(15,4,NULL),(15,5,NULL),(15,8,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_user_attribute` ENABLE KEYS */;

--
-- Table structure for table `phplist_user_user_history`
--

DROP TABLE IF EXISTS `phplist_user_user_history`;
CREATE TABLE `phplist_user_user_history` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `ip` varchar(255) default NULL,
  `date` datetime default NULL,
  `summary` varchar(255) default NULL,
  `detail` text,
  `systeminfo` text,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_user_user_history`
--


/*!40000 ALTER TABLE `phplist_user_user_history` DISABLE KEYS */;
LOCK TABLES `phplist_user_user_history` WRITE;
INSERT INTO `phplist_user_user_history` VALUES (1,8,'192.168.0.18','2004-06-02 11:31:06','Update by admin','I think it is obvious that in a few years\' time all software will be opensource = on\nchanged from \n\nList subscriptions:\nWas subscribed to: list 5\nWas subscribed to: list 6\nIs now subscribed to: list 5\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-GB; rv:1.6) Gecko/20040113\nHTTP_REFERER = http://phplist.michiel/lists/admin/?page=user&start=&id=8&find=\nREMOTE_ADDR = 192.168.0.18'),(2,1,'127.0.0.1','2005-09-08 16:37:06','Update by admin','email = html@testdomain.com\nchanged from html@domain1.com\nconfirmed = 1\nchanged from 0\nWhere do you connect most often = At home\nchanged from \n&lt;b&gt;Where do you connect to the internet&lt;/b&gt; = At home; At school; At a friend&amp;rsquo;s home\nchanged from \n\nList subscriptions:\nWas subscribed to: test\nWas subscribed to: list 2\nWas subscribed to: list 4\nWas subscribed to: list 5\nWas subscribed to: list 6\nIs now subscribed to: test\nIs now subscribed to: list 2\nIs now subscribed to: list 4\nIs now subscribed to: list 5\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/admin/?page=user&id=1\nREMOTE_ADDR = 127.0.0.1'),(3,4,'127.0.0.1','2005-09-08 16:38:14','Update by admin','email = anotheruser@anotherdomain.com\nchanged from anotheruser@another==domain.com\nCountry = Argentina\nchanged from \nI think PHPlist is brilliant = on\nchanged from \nI think the price of software has no relationship to the quality of software = on\nchanged from \nI think it is obvious that in a few years\' time all software will be opensource = on\nchanged from \nWhere do you connect most often = At home\nchanged from \n&lt;b&gt;Where do you connect to the internet&lt;/b&gt; = At home; At school\nchanged from \nI agree with the terms and conditions of use = on\nchanged from \n\nList subscriptions:\nWas subscribed to: test\nIs now subscribed to: test\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/admin/?page=user&start=0&id=4&find=&sortby=&sortorder=desc&unconfirmed=0&blacklisted=0\nREMOTE_ADDR = 127.0.0.1'),(4,5,'127.0.0.1','2005-09-08 16:39:11','Update by admin','email = user4@hotmail.com\nchanged from asdhsakjd@czxczxczxczxczxs.com\nName = Name\nchanged from gfhngfhgfhgfh\nI think PHPlist is brilliant = on\nchanged from \nI think the price of software has no relationship to the quality of software = on\nchanged from \n\nList subscriptions:\nWas subscribed to: test\nWas subscribed to: list 2\nWas subscribed to: list 6\nIs now subscribed to: test\nIs now subscribed to: list 2\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/admin/?page=user&start=0&id=5&find=&sortby=&sortorder=desc&unconfirmed=0&blacklisted=0\nREMOTE_ADDR = 127.0.0.1'),(5,8,'127.0.0.1','2005-09-08 16:40:17','Update by admin','confirmed = 1\nchanged from 0\n\nList subscriptions:\nWas subscribed to: list 5\nWas subscribed to: list 6\nIs now subscribed to: list 5\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/admin/?page=user&start=0&id=8&find=&sortby=&sortorder=desc&unconfirmed=0&blacklisted=0\nREMOTE_ADDR = 127.0.0.1'),(6,8,'127.0.0.1','2005-09-08 16:42:14','Unsubscription','Unsubscribed from   * All newsletters\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/?p=unsubscribe&uid=60de27fe99c566f73d03c29959ba7d3b\nREMOTE_ADDR = 127.0.0.1'),(7,8,'127.0.0.1','2005-09-08 16:43:47','Update by admin','\nNo userdata changed\nList subscriptions:\nIs now subscribed to: list 5\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.10) Gecko/20050719 Fedora/1.7.10-1.5.1\nHTTP_REFERER = http://phplist.laptop/lists/admin/?page=user&start=0&id=8&find=&sortby=&sortorder=desc&unconfirmed=0&blacklisted=0\nREMOTE_ADDR = 127.0.0.1'),(8,3,'201.235.113.141','2006-12-13 01:47:59','Update by admin','Public Key = -----BEGIN PGP PUBLIC KEY BLOCK-----\r\nVersion: GnuPG v1.4.5 (GNU/Linux)\r\n\r\nmQGiBEVRKmcRBAC1VrGyOt4W/tuAPvwULudPujp64I0sc5bSE0uf1x9HWbfSJGaR\r\n8B4VoUvP/mWtIHBEy7EsBFRk8MUn/nB4qyHgsUXXwgTV0HKTV2N1JA2wn4W0l3nZ\r\nBHmVkUbDdmjfA7FM/4CljLkQFiSgwP4ofgDg/sR7kGn5xEFtSkqRI4enJwCgtvQi\r\nhVljgIE983nNAhxusQxzamkD/iGYpYR0FCHJgRjFYZvb6fEhGQesjqLrct3MRm/1\r\n3yD5tq2DEvFK9hl+G4q5R3VZ9eFDPaA5ZeP+1j4Znv1u6wyCvgc3xun+yzNTHhNx\r\nRnr9VpcTdC29F9ex7JSQzJzZ1i30hF5fya0ZlTOTsq9yOS1+SnN/9MxFPtevDE5o\r\nZEkdA/0aSkGJeRo1QkennZrce1cXoqsCCFFJadLhxjlF7FUvalZLMB6MAPzEPHi5\r\nBILpXyGbfmv+L3X1PSsTGt2KPHnhfN4m0EHeQwT5+YlRwF1Wf97ceeGu0T2RWaqV\r\nULRN7oNF2tfmvl5zZRSzvwcjHc7uJEUcg7WYHq618Sp0MJlQX7Q6VGVzdCBVc2Vy\r\nIChLZXlzIHRvIHRlc3QgdGhlIHN5c3RlbSkgPHRlc3R1c2VyQHBocGxpc3QuY29t\r\nPohmBBMRAgAmBQJFUSpnAhsjBQkCJzDKBgsJCAcDAgQVAggDBBYCAwECHgECF4AA\r\nCgkQ6TMjpA9pMzJi2wCfXWaV9oQC5hWhC9cq3P5RoxQPObkAmwWcaUE3RnE9zof5\r\n4VC5ocR9OBL5uQINBEVRKnAQCADDBBj6iNXza2TvpZe3aFmTbIxkMryRgoIie+MA\r\nSxn+/OR57MDM9AJ4xhW9xl6neu0eygw0mbZGl+HTX4AjAGVIMSUGHno4cpv9VLA+\r\nSyElAJI6+pKTGPxzm+yX+ic7b8/O5s5MN9W9PMOT0gBwT+dBdZ03rgtCpOpKINbg\r\nqhseJlxl+b1nrDyjf+1pq+O3ZIlDMrCd6TlbadBJd9ntY7Wj9MgtqwOIbozSD9LT\r\nSZ7dyWgvebj9aJ7YU/E2tqr5GweXwRpLO3AEZyBhOUE7gj5Kl8DlYR6BDC2Lns5f\r\npFDf7XR/+q/r79emMGHNKQE9rNA/zwmcTWxw3ZzEsRJd4AKDAAMFCACLUhCcytbo\r\n2wPEre/eCjERWLQen2Qxs9uipzjr++sHlcfnWIYOCe2WwNRFycfpi8SOLDIEm9gn\r\nB3jBlIQNNbI2crxHiFHmtPwWlSnJPenVrbAW+bzuoko6TJvTcOCfgu+L452n+o2H\r\npbzt9cgOfL3/43dDtxkdEwOTNMOBnPFTWV5dtJ0Jp0eK1Zz/AHpmbY0993zpdMDC\r\nQfJyj8VEgUEHAQ21D7stWo437w4Jg2LJugtXSfHWT8A2dBXXhCJSS9swA7Qi+9l+\r\njOld8YZZRxAVpJSvHFrCwQXuKbaC/u1kMP/IguMjbI1PXS+GLVuZIGfhzX2O4TCf\r\nQTn5g58y8QqliE8EGBECAA8FAkVRKnACGwwFCQInMMoACgkQ6TMjpA9pMzJ/XQCf\r\nQdMvZgIGvg4Tb55e/5FHEODGWZwAn0JX78nLRgp77qKOj0yZWIeTqmGW\r\n=h+2D\r\n-----END PGP PUBLIC KEY BLOCK-----\r\n\nchanged from \n\nList subscriptions:\nWas subscribed to: test\nWas subscribed to: list 6\nIs now subscribed to: test\nIs now subscribed to: list 6\n','\nHTTP_USER_AGENT = Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.8) Gecko/20061107 Fedora/1.5.0.8-1.fc6 Firefox/1.5.0.8\nHTTP_REFERER = http://phplistcvs.cedar.tincan.co.uk/lists/admin/?page=user&start=0&id=3&find=&sortby=&sortorder=desc&unconfirmed=0&blacklisted=0\nREMOTE_ADDR = 201.235.113.141');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_user_user_history` ENABLE KEYS */;

--
-- Table structure for table `phplist_usermessage`
--

DROP TABLE IF EXISTS `phplist_usermessage`;
CREATE TABLE `phplist_usermessage` (
  `messageid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `entered` datetime default NULL,
  `viewed` datetime default NULL,
  `status` varchar(255) default NULL,
  PRIMARY KEY  (`userid`,`messageid`),
  KEY `userindex` (`userid`),
  KEY `messageindex` (`messageid`),
  KEY `enteredindex` (`entered`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_usermessage`
--


/*!40000 ALTER TABLE `phplist_usermessage` DISABLE KEYS */;
LOCK TABLES `phplist_usermessage` WRITE;
INSERT INTO `phplist_usermessage` VALUES (2,1,'2002-05-31 09:57:20','0000-00-00 00:00:00','sent'),(2,3,'2002-05-31 09:57:22','0000-00-00 00:00:00','sent'),(2,4,'2002-05-31 09:57:23','0000-00-00 00:00:00','sent'),(11,3,'2002-08-09 17:38:45',NULL,'sent'),(14,4,'2003-03-21 12:45:33','2003-03-21 12:45:33','sent'),(14,3,'2003-03-21 12:45:40','2003-03-21 12:45:40','sent'),(14,1,'2003-03-21 12:45:40','2003-03-21 12:45:40','sent'),(15,3,'2005-09-08 15:16:04',NULL,'invalid email');
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_usermessage` ENABLE KEYS */;

--
-- Table structure for table `phplist_userstats`
--

DROP TABLE IF EXISTS `phplist_userstats`;
CREATE TABLE `phplist_userstats` (
  `id` int(11) NOT NULL auto_increment,
  `unixdate` int(11) default NULL,
  `item` varchar(255) default NULL,
  `listid` int(11) default '0',
  `value` int(11) default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `entry` (`unixdate`,`item`,`listid`),
  KEY `listdateindex` (`listid`,`unixdate`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phplist_userstats`
--


/*!40000 ALTER TABLE `phplist_userstats` DISABLE KEYS */;
LOCK TABLES `phplist_userstats` WRITE;
INSERT INTO `phplist_userstats` VALUES (1,1125543600,'unsubscription',0,1);
UNLOCK TABLES;
/*!40000 ALTER TABLE `phplist_userstats` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

