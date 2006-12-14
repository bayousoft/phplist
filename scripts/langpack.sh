#!/bin/bash

# create a language pack for translation
DIR=`pwd`
[ -d "$DIR/public_html/lists/admin" ] || { echo "Run from code root as ./scripts/langpack.sh"; exit; }
. VERSION
cd $DIR/public_html/lists/admin
rm -f `find . -name ".#*"`
tar zcf $DIR/public_html/langpack/phplist-langpack-$DEVVERSION.tgz --exclude CVS lan/en info/en help/en
cd $DIR
