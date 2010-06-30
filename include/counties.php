<?php

$sql = "SELECT * from xref ORDER by sid";
$rs = pg_exec($sql);
pg_close($c);

$cdb = Array();
$sdb = Array();

for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) { 
  $fips = $row['fips'];
  $cname = $row['cname'];
  $sid = $row['sid'];

  if (array_key_exists($fips, $sdb)){
    $sdb[$fips][] = $sid;
  } else {
    $sdb[$fips] = Array();
    $sdb[$fips][] = $sid;
  }
  $cdb[$fips] = $cname;

} // End 

// Put in Alphabetical order by County
asort($cdb);

echo "<table><tr><td valign=\"TOP\">";
$i =1;
// Walk the counties
foreach(array_keys($cdb) as $key){
  echo "<table width='100%'>
  <tr><th colspan='2' align='left' bgcolor='#e6e6fa'>". $cdb[$key] ." County</th></tr>";
  foreach($sdb[$key] as $sid){
    echo "<tr><td width=15><input type=\"checkbox\" name=\"st[]\" value=\"". $sid ."\" ";
    if (in_array($sid, $st)) echo "CHECKED";
    echo "></td>
      <td align=\"left\">". $locs->table[$sid]['city'] ."</td></tr>";
  }
  echo "</table>";

  if ($i == 11 or $i == 22)  echo "</td><td valign=\"TOP\">";

  $i++;
}

echo "</td></tr></table>";


?>
