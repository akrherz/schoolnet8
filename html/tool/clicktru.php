<?php

 include("../../config/settings.inc.php");
 include("../../include/sponsors.inc.php");
  $REMOTE_ADDR = getenv('REMOTE_ADDR');
  $station = $_GET['station'];

  $c = pg_connect($dbhost);

  pg_exec($c, "INSERT into clicktru(station, ip) VALUES
    ('". $station ."', '". $REMOTE_ADDR ."')");

  pg_close($c);

Header("Location: ".$sponsors[$station]["url"] )
?>
