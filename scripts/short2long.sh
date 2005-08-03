
# script to replace short php tags <? with long one <?php

for i in `find public_html -type f|grep -e "\.php\|\.inc"`; do 
  echo $i;
  sed -i "s/<?/<?php/" $i 
done
# remove accidentally doubled ones
for i in `find public_html -type f|grep -e "\.php\|\.inc"`; do 
  echo $i;
  sed -i "s/<?phpphp/<?php/" $i 
done
# change the echo ones
for i in `find public_html -type f|grep -e "\.php\|\.inc"`; do 
  echo $i;
  sed -i "s/<?php=/<?php echo /" $i 
done

