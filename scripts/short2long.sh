
# script to replace short php tags <? with long one <?php

echo "replacing <? with <?php"
for i in `fgrep -rilI -e "<?" public_html*`; do
   echo $i;
   sed -i "s/<?/<?php/" $i;
done
# remove accidentally doubled ones
echo "replacing <?phpphp with <?php"
for i in `fgrep -rilI -e "<?phpphp " public_html*`; do
   echo $i;
   sed -i "s/<?phpphp/<?php/" $i;
done
echo "replacing <?php= with <?php echo "
for i in `fgrep -rilI -e "<?php= " public_html*`; do
   echo $i;
   sed -i "s/<?php=/<?php echo /" $i;
done


