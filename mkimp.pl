#!/usr/bin/perl

# generate test file for import2
open F,">testimport2.txt";
print F "email\tName\tAddr1\tAddr2\tTown\tPostcode\tforeign key\tsend this user HTML emails\tEntered\n";
$start = 1;
for ($i=$start;$i<$start+400;$i++) {
  print F "clashFK$i\@domain\tTestUser $i\tAddr line 1\tAddr line2\tTown\tPostcode $i\tABC $i\t1\n";
}
close(F);

# generate test file for import1
open F,">testimport1.txt";
$start = 0;
for ($i=$start;$i<$start+400;$i++) {
  print F "test$i\@localhost.localdomain\n";
}
close(F);


