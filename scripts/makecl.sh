#!/bin/bash
DATE=`date +"%Y-%m-%d %H:%M"`;

svn2cl --html --reparagraph --ignore-message-starting '*** empty' --title="phpList Changelog" --revision-link='http://phplist.svn.sourceforge.net/viewvc/phplist?view=revision&revision='
sed s/'<br\/>'/' '/g ChangeLog.html > cl
rm -f ChangeLog.html 
mv cl ChangeLog.html
svn2cl  --reparagraph --ignore-message-starting '*** empty' --title="phpList Changelog"
