<?php
/* Serialize the climate data to files we can update! */
$pg = pg_connect("dbname=coop host=10.10.10.20");

$rs = pg_query($pg, "SELECT * from climate51");

$db = Array();
for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
{
  $id = $row["station"];
  if (! array_key_exists($id, $db)) $db[$id] = Array();
  $db[$id][ $row["valid"] ] = $row;
}

reset($db);
while( list($id,$d) = each($db))
{
  $s = serialize($d);
  $fp =fopen($id.".txt", 'w');
  fwrite($fp,$s);
  fclose($fp); 
}

?>
