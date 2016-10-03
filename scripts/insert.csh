#!/bin/csh
# This runs on schoolnet8.com as lsd user, please edit upstream in git repo
# scripts/insert.csh

# Run further away from top of the minute as this is when file is xferring
sleep 10

# Convert to PNG
cp -f ~/incoming/radar_d_1.jpg /tmp/insert.jpg
convert -black-threshold 20% -depth 8 -colors 140 /tmp/insert.jpg max.png
diff max.png oldmax.png >& /dev/null

if ($status != 0) then 
  gdalwarp -s_srs EPSG:4326 -t_srs EPSG:26915 -te 176082.5 4449617.5 710482.5 4850417.5 -tr 835 -835  max.png max.tif >& /dev/null
  convert max.tif max2.png >& /dev/null
  set YYYYMMDDHHMM="`date -u +'%Y%m%d%H%M'`"
  /home/ldm/bin/pqinsert -p "lsd ac ${YYYYMMDDHHMM} gis/images/26915/KCCI/KCCI_N0R_0.png GIS/kcci/KCCI_${YYYYMMDDHHMM}.png png" max2.png

  echo "`date -u +'%m/%d %H:%M'`" > gtime.txt
  /home/ldm/bin/pqinsert -p "data c ${YYYYMMDDHHMM} gis/images/26915/KCCI/KCCI_N0R_tm_0.txt bogus txt" gtime.txt

  rm -f max2.png max.tif
endif

mv max.png oldmax.png
