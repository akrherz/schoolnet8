<?php
include("../../config/settings.inc.php");

// sum_worker.php
// Does the work of processing the download request...
// Daryl Herzmann 30 Sep 2003

/** Get variables */
$station = $_GET["station"];
$s_year = $_GET["s_year"];
$e_year = $_GET["e_year"];
$s_day = $_GET["s_day"];
$e_day = $_GET["e_day"];
$s_month = $_GET["s_month"];
$e_month = $_GET["e_month"];
$vars = $_GET["vars"];
$delim = $_GET["delim"];
$dl_option = $_GET["dl_option"];

include("$nwnpath/include/locs.inc.php");

$ts1 = mktime(0, 0, 0, $s_month, $s_day, $s_year) or 
  die("Invalid Date Format");
$ts2 = mktime(0, 0, 0, $e_month, $e_day, $e_year) or
  die("Invalid Date Format");

if ($ts1 >= $ts2){
  die("Error:  Your 'End Date' is before your 'Start Date'!");
}

$now = time();
if ($ts1 > $now){
  die("Error: Your 'Start Date' is in the Future!");
}


$num_vars = count($vars);
if ( $num_vars == 0 )  die("You did not specify data");

 $connection = pg_connect($iemaccess);



$sqlStr = "SELECT ";
for ($i=0; $i< $num_vars;$i++){
  if ($vars[$i] == "max_gust"){
    $sqlStr .= "round((". $vars[$i] ." * 1.15)::numeric,0) as var".$i.", ";
  } else {
    $sqlStr .= $vars[$i] ." as var".$i.", ";
  }
}

$sqlTS1 = strftime("%Y-%m-%d %H:%M", $ts1);
$sqlTS2 = strftime("%Y-%m-%d %H:%M", $ts2);
$nicedate = strftime("%Y-%m-%d", $ts1);

$d = Array("comma" => ",",
  "space" => " ",
  "tab" => "\t");

$sqlStr .= "day as dvalid, max_tmpf_qc || min_tmpf_qc as qcflags from summary";
$sqlStr .= " WHERE day >= '".$sqlTS1."' and day <= '".$sqlTS2 ."' ";
$sqlStr .= " and station = '".$station."' ";
$sqlStr .= " ORDER by day ASC";
 $rs =  pg_exec($connection, $sqlStr);

if ( pg_numrows($rs) == 0){
  die("Did not find any data for this query!");
} else if ($dl_option == "download"){
 header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=snetData.dat");
} else {
 header("Content-type: text/plain");
}

 pg_close($connection);

echo "#QCFLAGS: 0 - All data bad, T - Temperatures bad, H - High Temp bad, L - Low Temp bad\n";
printf("%s%s%s%s%s", "STID", $d[$delim], "Station Name",  $d[$delim], "DATETIME");
for ($j=0; $j< $num_vars;$j++){
    printf("%s%6s", $d[$delim], $vars[$j]) ;
}
echo ",qcflags\n";
if ($dl_option == "download"){

 for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) 
 {
  printf("%s%s%s%s%s", $station , $d[$delim],  $Scities[$station]["city"], 
     $d[$delim], $row["dvalid"]);
  for ($j=0; $j< $num_vars;$j++){
    printf("%s%6s", $d[$delim], $row["var".$j]) ;
  }
  printf("%s%6s", $d[$delim], $row["qcflags"]) ;
  echo "\n";
  }
} else {

 for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) 
 {
  printf("%s%s%s%s%s", $station , $d[$delim], $Scities[$station]["city"],
     $d[$delim], $row["dvalid"]);
  for ($j=0; $j< $num_vars;$j++){
     printf("%s%6s", $d[$delim], $row["var".$j]) ;
  }
  printf("%s%6s", $d[$delim], $row["qcflags"]) ;
  echo "\n";
 }
}
?>
