#!/bin/sh

# this script is not supported, use at your own risk

# you can use this script to invoke regular sending of your emails automatically

# set the path to be the location of your "admin" directory
path=/home/michiel/projects/phplist/public_html/lists/admin

# set php to be the location of your php binary
php=/usr/bin/php

# if you use the PHPlist authentication, set the username and password here
username=admin
password=phplist

# now this one will process your queue
$php $path/index.php page=processbounces login=$username password=$password > /dev/null 2>&1



