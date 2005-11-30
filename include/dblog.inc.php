<?php
 $REMOTE_ADDR = getenv("REMOTE_ADDR");
 $dbapp = isset($app) ? $app : "-1";
 $dbstation = isset($station) ? $station : "NONE";


 $c = pg_connect($dbhost);
 pg_exec($c, "INSERT into site_stats(station, ip, app) VALUES
    ('". strtoupper($dbstation) ."', '". $REMOTE_ADDR ."', '". $dbapp ."')"); 
 pg_close($c); 
?>
