<?php
include('../include/locs.inc.php');

/** Time to loop over each Site! */
foreach($Scities as $key => $value)
{
  $sid = $key;
  $lat = $Scities[$sid]['lat'];
  $lon = $Scities[$sid]['lon'];

  system("./genFX.py $sid $lat $lon");
}

?>
