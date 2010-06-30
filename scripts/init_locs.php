<?php
/* Initialize the database entries */
include("../include/locs.inc.php");
$dbconn = pg_connect("dbname=kcci");

while(list($key,$db) = each($Scities)){
  $sql = sprintf("INSERT into stations(id, sname, city, online, nwn_id,
    climate_site, geom) values ('%s', '%s', '%s', '%s', %s, '%s', 
    'SRID=4326;POINT(%s %s)')", $key, $db["short"], $db["city"], 
     $db["online"] ? 't': 'f', $db["nwn_id"], $db["climate_site"], $db["lon"],
     $db["lat"]);
  pg_query($dbconn, $sql);
}

?>
