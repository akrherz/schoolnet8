<?php
/* Everything is in GMT, yeah! */

/* Current time */
$now = time();
//echo "Current time is ". date("Y/m/d H:i", $now) ."\n";

/* Filenames of files */
$fn_lastrun = 'lastrun.ts';
$fn_kccitime = '/home/ldm/data/gis/images/26915/KCCI/KCCI_N0R_tm_0.txt';
$fn_dmxtime = '/home/ldm/data/gis/images/4326/DMX.ts';

/* For what time did we previously run for */
$fc = file($fn_lastrun);
$lastrun = strtotime($fc[0]);
//echo "Last time we ran was ". date("Y/m/d H:i", $lastrun) ."\n";

/* What time is the KCCI RADAR valid */
$fc = file($fn_kccitime);
$kccits = strtotime(date("Y")."/".$fc[0]);
$kccits = strtotime( date("Y/m/d H:i", $kccits) );
//echo "KCCI is valid at ". date("Y/m/d H:i", $kccits) ."\n";

/* What time is DMX valid? */
$fc = file('/home/ldm/data/gis/images/4326/DMX.ts');
$s = substr($fc[0], 4,12) ;
$s = substr($s,0,4) ."-". substr($s,4,2) ."-". substr($s,6,2) ." ". substr($s,8,2) .":". substr($s,10,2);
$dmxts = strtotime($s);
//echo "DMX valid at ". date("Y/m/d H:i", $dmxts) ."\n";

$runts = 0;
/* Is KCCI Recent?  8 minutes */
if ( ($now - $kccits) < (8*60) )
{
  /* Is the KCCI data new? */
  if ($lastrun != $kccits)
  {
    $runts = $kccits;
  }
}
else 
{
  /* Is the DMX data newer? */
  if ($lastrun != $dmxts)
  {
    $runts = $dmxts;
  }
}

if ($runts == 0)
{
  exit(10);
}
else 
{
  $fp = fopen('lastrun.ts', 'w');
  fwrite($fp, date("Y/m/d H:i", $runts) );
  exit(0);
}
?>
