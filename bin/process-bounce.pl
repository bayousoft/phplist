#!/usr/bin/perl

# this script is old, use at your own risk

# process bounces for PHPlist

# this script can be used to invoke bounces from the aliases file of sendmail
# however, it is for checking only. Use the normal PHP bounce pages to process
# your bounces

##################### Configurable variables ######################

########### database information ############

$dbhost = "localhost";
$dbname = "phplistdb";
$dbuser = "phplist";
$dbpasswd = 'phplist';

# who to send bounces to that we don't know what to do with
$moderator = 'you@yourdomain.com';

##################### end of configuration ############

# read the message
while (<STDIN>) {
  $user_line = $_ if (/X-ListMember: (.*)/);
  $user = $1;
  $messageno_line = $_ if (/X-MessageId: (.*)/);
  $messageno = $1;
  # only keep up to last required header line
  if (!$user && !$messageno) $msg .= $_;
}

if ($user && $messageno) {
  # ah, we found both the user and the message, nicely bounced
  $dbh = Mysql->Connect("$dbhost","$dbname","$dbuser","$dbpasswd" ) || &Error ("Can't connect to Database, please reload\n");
  $sth = $dbh->Query("insert into bounce (date,data) values(now(),\"$msg\"");
  $bounceid = $sth->insertid;
  $userid_req = $dbh->Query("select id from user where email = \"$user\"");
  @userid_req = $userid_req->fetchrow;
  $userid = $userid_req[0];
  $sth = $dbh->Query("insert into user_message_bounce (user,message,bounce) values($userid,$messageno,$bounceid)");
} else {
  open SM,"|/usr/sbin/sendmail -t";
  print SM "Subject: Unparsable bounce\n";
  print SM "From: PHPList-Daemon\n";
  print SM "To: $moderator\n";
  print SM "\n";
  print SM $msg;
  close(SM);
}
