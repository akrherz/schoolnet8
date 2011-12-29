<?php
/* sync the climate data from the IEM to our database tables please
 */
set_time_limit(580);
$iemdb = pg_connect("user=nobody dbname=coop host=mesonet.agron.iastate.edu");
$localdb = pg_connect("dbname=kcci");
pg_query($localdb, "DELETE from climate51");

$rs = pg_query($iemdb, "SELECT * from climate51 
 WHERE station ~* 'IA' ORDER by station, valid ASC");

$q = 0;
$cur = "";
$db = Array();
for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
{
  $id = $row["station"];
  $v = $row["valid"];
  $k = sprintf("%s-%s", substr($v,0,7), $id);
  $p = floatval($row["precip"]);
  if ($k == $cur){ $q += $p; }
  else {$q = $p;}
  $cur = $k;

  $row["mtd"] = round($q,2);
  $sql = sprintf("INSERT into climate51(station, valid, high, low, precip,
         snow, max_high, max_low, min_high, min_low, max_precip, years, 
         gdd50, sdd86, max_high_yr, max_low_yr, min_high_yr, min_low_yr,
         max_precip_yr, max_range, min_range, precip_mtd) VALUES ('%s','%s',
         %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s,
         %s, %s, %s)", $id, $v, $row["high"], $row["low"], $row["precip"],
         $row["snow"]? $row["snow"] : 0, $row["max_high"], $row["max_low"], 
         $row["min_high"], $row["min_low"], $row["max_precip"], $row["years"] ? $row["years"] : 0, 
         $row["gdd50"], $row["sdd86"]? $row["sdd86"]: 0, $row["max_high_yr"], 
         $row["max_low_yr"], $row["min_high_yr"], $row["min_low_yr"],
         $row["max_precip_yr"], $row["max_range"] ? $row["max_range"]: 0, $row["min_range"] ? $row["min_range"]:0, $q);
  if (! pg_query($localdb, $sql) ){
    echo $sql;
  }
}
?>
