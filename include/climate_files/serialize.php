<?php
/* Serialize the climate data to files we can update! */
set_time_limit(580);
$pg = pg_connect("user=nobody dbname=coop host=mesonet.agron.iastate.edu");

$rs = pg_query($pg, "SELECT * from climate51 ORDER by station, valid ASC");

$q = 0;
$cur = "";
$db = Array();
for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
{
  $id = $row["station"];
  $v = $row["valid"];
  $p = floatval($row["precip"]);
  $k = sprintf("%s-%s", substr($v,0,7), $id);
  //echo "$k - $cur - $v - $q \n";
  if ($k == $cur){ $q += $p; }
  else {$q = $p;}
  $cur = $k;
  if (! array_key_exists($v, $db)) $db[$v] = Array();
  $row["mtd"] = $q;
  $db[ $row["valid"] ][$id] = $row;
}
reset($db);
while( list($id,$d) = each($db))
{
 echo $id;
  $s = serialize($d);
  $fp =fopen($id.".txt", 'w');
  fwrite($fp,$s);
  fclose($fp); 
}

?>
