<?php
include("../../config/settings.inc.php");
// worker.php
// Does the work of processing the download request...
// Daryl Herzmann 31 Dec 2002
// 18 Feb 2003	Lets also print out the station ID
// 28 Feb 2003	Use GigE connection
// 22 Apr 2003	Get around using globals

/** Get variables */
$station = $_GET["station"];
$s_hour = $_GET["s_hour"];
$e_hour = $_GET["e_hour"];
$s_day = $_GET["s_day"];
$e_day = $_GET["e_day"];
$year = $_GET["year"];
$month = $_GET["month"];
$vars = $_GET["vars"];
$sample = $_GET["sample"];
$delim = $_GET["delim"];
$dl_option = $_GET["dl_option"];

include("../../include/locs.inc.php");
include("../../include/mlib.php");

$ts1 = mktime($s_hour, 0, 0, $month, $s_day, $year) or 
  die("Invalid Date Format");
$ts2 = mktime($e_hour, 0, 0, $month, $e_day, $year) or
  die("Invalid Date Format");

if ($ts1 >= $ts2){
  die("Error:  Your 'End Date' is before your 'Start Date'!");
}

$now = time();
if ($ts1 > $now){
  die("Error: Your 'Start Date' is in the Future!");
}
if ($ts2 > $now){
  die("Error: Your 'End Date' is in the Future!");
}


$num_vars = count($vars);
if ( $num_vars == 0 )  die("You did not specify data");

 $connection = pg_connect($iemdbhost);


$sqlStr = "SELECT ";
for ($i=0; $i< $num_vars;$i++){
  if ($vars[$i] == "drct"){ $sqlStr .= "drct, ";  }
  $sqlStr .= $vars[$i] ." as var".$i.", ";
}

$sqlTS1 = strftime("%Y-%m-%d %H:%M", $ts1);
$sqlTS2 = strftime("%Y-%m-%d %H:%M", $ts2);
$table = strftime("t%Y_%m", $ts1);
$nicedate = strftime("%Y-%m-%d", $ts1);

$sampleStr = Array("1min" => "1",
  "5min" => "5",
  "10min" => "10",
  "20min" => "20",
  "1hour" => "60");

$d = Array("comma" => ",",
  "space" => " ",
  "tab" => "\t");

$sqlStr .= "to_char(valid, 'YYYY-MM-DD HH24:MI') as dvalid from ".$table ;
$sqlStr .= " WHERE valid >= '".$sqlTS1."' and valid <= '".$sqlTS2 ."' ";
$sqlStr .= " and station = '".$station."' and ";
$sqlStr .= " extract(minute from valid)::int % ".$sampleStr[$sample] ." = 0 ";
$sqlStr .= " ORDER by valid ASC";

 pg_exec($connection, "set enable_seqscan=off");
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

printf("%s%s%s%s%s", "STID", $d[$delim], "Station Name",  $d[$delim], "DATETIME");
for ($j=0; $j< $num_vars;$j++){
    if ($vars[$j] == "drct") printf("%s%6s", $d[$delim], "drctTxt") ;
    printf("%s%6s", $d[$delim], $vars[$j]) ;
}
echo "\n";
if ($dl_option == "download"){

 for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) 
 {
  printf("%s%s%s%s%s", $station , $d[$delim],  $Scities[$station]["city"], 
     $d[$delim], $row["dvalid"]);
  for ($j=0; $j< $num_vars;$j++){
    if ($vars[$j] == "drct") 
       printf("%s%6s", $d[$delim], drct2txt( $row["drct"] ) ) ;
    printf("%s%6s", $d[$delim], $row["var".$j]) ;
  }
  echo "\n";
  }
} else {

 for( $i=0; $row = @pg_fetch_array($rs,$i); $i++) 
 {
  printf("%s%s%s%s%s", $station , $d[$delim], $Scities[$station]["city"],
     $d[$delim], $row["dvalid"]);
  for ($j=0; $j< $num_vars;$j++){
    if ($vars[$j] == "drct")
       printf("%s%6s", $d[$delim], drct2txt( $row["drct"] ) ) ;
     printf("%s%6s", $d[$delim], $row["var".$j]) ;
  }
  echo "\n";
 }
}
?>
