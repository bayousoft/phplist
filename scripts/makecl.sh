#!/bin/bash
DATE=`date +"%Y-%m-%d %H:%M"`;
CVSROOT=:ext:basovink@phplist.cvs.sourceforge.net:/cvsroot/phplist

#cvs2cl.pl --prune -r -t --hide-filenames
if [ -f BRANCH ]; then
  . BRANCH
  cvs -d$CVSROOT log -r$BRANCH >> cvslog
else 
  cvs -d$CVSROOT log >> cvslog
fi
#cvs2cl.pl -F phplist-version-2-10-x --prune -t --hide-filenames --stdin < cvslog
./scripts/cvs2cl.pl --prune -b -r -t --hide-filenames --stdin < cvslog
echo "Changelog for phplist $BRANCH $DATE" > cl
echo >> cl
cat ChangeLog >> cl
mv -f cl ChangeLog
#rm -f cvslog
# make one for languages as well

exit;
  --version                    Show version and exit
  -r, --revisions              Show revision numbers in output
  -b, --branches               Show branch names in revisions when possible
  -t, --tags                   Show tags (symbolic names) in output
  --stdin                      Read from stdin, don't run cvs log
  --stdout                     Output to stdout not to ChangeLog
  -d, --distributed            Put ChangeLogs in subdirs
  -f FILE, --file FILE         Write to FILE instead of "ChangeLog"
  --fsf                        Use this if log data is in FSF ChangeLog style
  -W SECS, --window SECS       Window of time within which log entries unify
  -U UFILE, --usermap UFILE    Expand usernames to email addresses from UFILE
  -R REGEXP, --regexp REGEXP   Include only entries that match REGEXP
  -I REGEXP, --ignore REGEXP   Ignore files whose names match REGEXP
  -C, --case-insensitive       Any regexp matching is done case-insensitively
  -F BRANCH, --follow BRANCH   Show only revisions on or ancestral to BRANCH
  -S, --separate-header        Blank line between each header and log message
  --no-wrap                    Don't auto-wrap log message (recommend -S also)
  --gmt, --utc                 Show times in GMT/UTC instead of local time
  --accum                      Add to an existing ChangeLog (incompat w/ --xml)
  -w, --day-of-week            Show day of week
  --header FILE                Get ChangeLog header from FILE ("-" means stdin)
  --xml                        Output XML instead of ChangeLog format
  --hide-filenames             Don't show filenames (ignored for XML output)
  -P, --prune                  Don't show empty log messages
  -g OPTS, --global-opts OPTS  Invoke like this "cvs OPTS log ..."
  -l OPTS, --log-opts OPTS     Invoke like this "cvs ... log OPTS"
  FILE1 [FILE2 ...]            Show only log information for the named FILE(s)


