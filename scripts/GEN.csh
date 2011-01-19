#!/bin/csh
# 31 Jan 2004	Lets do it!
setenv TZ UTC

cd /mesonet/www/apps/nwnwebsite/scripts

#diff time.txt /home/ldm/data/kcci/time.txt >& /dev/null
#diff /mesonet/data/gis/images/26915/DMX/meta/DMX_N0R_0.dbf DMX_N0R_0.dbf >& /dev/null
php -q check.php
if ($status == 10) then
  exit
endif

#rm images/*.png >& /dev/null
php -q genstills.php
php -q genLSD.php

