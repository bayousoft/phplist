
# script to replace short php tags <? with long one <?php

for i in `find public_html -type f`; do echo $i;
sed "s/<?/<?php/" $i > /tmp/$$
mv -f /tmp/$$ $i
done
# remove accidentally doubled ones
for i in `find . -type f`; do echo $i;
sed "s/<?php/<?php/" $i > /tmp/$$
mv -f /tmp/$$ $i
done
# change the echo ones
for i in `find . -type f`; do echo $i;
sed "s/<?php echo /<?php echo /" $i > /tmp/$$
mv -f /tmp/$$ $i
done

