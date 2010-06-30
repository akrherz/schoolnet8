<?php
include('../include/locs.inc.php');
$locs = new Locations();

/** Time to loop over each Site! */
foreach($locs->table as $key => $value)
{
  $sid = $key;
  $lat = $value['lat'];
  $lon = $value['lon'];
  system("./genFX.py $sid $lat $lon");
  system("/home/ldm/bin/pqinsert -p 'data c 000000000000 kcci/fx/$sid.html blah blah' ../data/fx/$sid.html");
}

?>
