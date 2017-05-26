<?php
include('../config/settings.inc.php');
include('../include/locs.inc.php');
$locs = new Locations();

/** Time to loop over each Site! */
foreach($locs->table as $key => $value)
{
  $sid = $key;
  $lat = $value['lat'];
  $lon = $value['lon'];
  system("python gen_nwsfx.py $sid $lat $lon");
}

?>
