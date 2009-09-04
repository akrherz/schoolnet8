<?php
include("../../config/settings.inc.php");
include("$nwnpath/include/sponsors.inc.php");
include("$nwnpath/include/cameras.inc.php");
$REMOTE_ADDR = getenv('REMOTE_ADDR');
$station = isset($_GET["station"]) ? substr($_GET['station'],0,11): die();

$c = pg_connect($dbhost);
pg_exec($c, "INSERT into clicktru(station, ip) VALUES
             ('". $station ."', '". $REMOTE_ADDR ."')");

if (! array_key_exists($station, $sponsors)){
 $sponsorurl = $cameras[$station]["sponsorurl"];
} else {
 $sponsorurl = $sponsors[$station]["url"];
}

Header("Location: $sponsorurl")
?>
