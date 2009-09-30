<?php
 /* Application databases website hits on a per app basis
  */
 $REMOTE_ADDR = getenv("REMOTE_ADDR");
 $dbapp = isset($app) ? $app : die("Variable app is not set, BUG!");
 $dbstation = isset($station) ? strtoupper($station) : "CIPCO";
 if (isset($camid)){ $dbstation = strtoupper($camid); }

 $c = pg_connect($dbhost);
 pg_exec($c, "INSERT into site_stats(station, ip, app) VALUES
    ('$dbstation', '$REMOTE_ADDR', '$dbapp')"); 
 pg_close($c); 
?>
