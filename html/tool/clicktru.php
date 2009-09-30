<?php
 /* This is our basic application that counts the number of clicks of 
  * sponsor logos and links 
  */
include("../../config/settings.inc.php");
include("$nwnpath/include/sponsors.inc.php");
include("$nwnpath/include/cameras.inc.php");
$REMOTE_ADDR = getenv('REMOTE_ADDR');
$station = isset($_GET["station"]) ? substr($_GET['station'],0,11): die();
$stype = isset($_GET["stype"]) ? intval($_GET['stype']) : 0;
/* STYPES
 *  0 - Standard
 *  1 - IService
 *  2 - Hosted By
 */

$c = pg_connect($dbhost);
pg_exec($c, "INSERT into clicktru(station, ip, stype) VALUES
             ('$station', '$REMOTE_ADDR', $stype)");

if (! array_key_exists($station, $sponsors)){
 if ($stype == 1){ $sponsorurl = $cameras[$station]["iserviceurl"]; }
 else if ($stype == 2){ $sponsorurl = $cameras[$station]["hostedurl"]; }
 else{ $sponsorurl = $cameras[$station]["sponsorurl"]; }
} else {
 $sponsorurl = $sponsors[$station]["url"];
}

Header("Location: $sponsorurl")
?>
