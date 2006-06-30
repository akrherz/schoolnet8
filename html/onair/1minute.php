<?php
  // 1minute.php
  //  Unification of 1 minute SNET plots!
  // 

  include ("../../include/locs.inc.php");
  include ("../../include/mlib.php");
$station = 'SBDI4';
$year = 2006;
$month = 3;
$day = 19;

if (strlen($station) > 3){
    $station = $Scities[$station]["nwn_id"];
}
$station = intval($station);

if (strlen($year) == 4 && strlen($month) > 0 && strlen($day) > 0 ){
  $myTime = strtotime($year."-".$month."-".$day);
} else {
  $myTime = strtotime(date("Y-m-d"));
}
$dirRef = strftime("%Y_%m/%d", $myTime);
$titleDate = strftime("%b %d, %Y", $myTime);
$href = strftime("/tmp/".$station."_%Y_%m_%d", $myTime);

$wA = mktime(0,0,0, 8, 4, 2002);
$wLabel = "1min avg Wind Speed";
if ($wA > $myTime){
 $wLabel = "Instant Wind Speed";
}


$fcontents = @file('http://mesonet.agron.iastate.edu/archive/raw/snet/'.$dirRef.'/'.$station.'.dat');



// BUILD Arrays to hold minute-by-minute data
$tmpf = Array();
$dwpf = Array();
$sr = Array();
$mph = array();
$drct = array();
$gust = array();
$prec = array();
$alti = array();


$dirTrans = array(
  'N' => '360',
 'NNE' => '25',
 'NE' => '45',
 'ENE' => '70',
 'E' => '90',
 'ESE' => '115',
 'SE' => '135',
 'SSE' => '155',
 'S' => '180',
 'SSW' => '205',
 'SW' => '225',
 'WSW' => '250',
 'W' => '270',
 'WNW' => '295',
 'NW' => '305',
 'NNW' => '335');


$xlabel = Array();

$start = intval( $myTime );
$i = 0;

$dups = 0;
$missing = 0;
$min_yaxis = 100;
$max_yaxis = 0;
$hasgust = 0;
$peakGust = 0;
$peaksped = 0;

while (list ($line_num, $line) = each ($fcontents)) {
  $parts = split (",", $line);
  $thisTime = $parts[0];
  $thisDate = $parts[1];
  $dateTokens = split("/", $thisDate);
  $strDate = "20". $dateTokens[2] ."-". $dateTokens[0] ."-". $dateTokens[1];
  $timestamp = strtotime($strDate ." ". $thisTime );
#  echo $thisTime ."||";

  if (substr($parts[6], 0, 2) == "0-"){
    $thisTmpf = intval( substr($parts[6], 1, 2) ) ;
  } else {
    $thisTmpf = intval( substr($parts[6], 0, 3) ) ;
  }
  $thisRelH = intval( substr($parts[7],0,3) );
  $thisSR = intval( substr($parts[4],0,3) ) * 10;
  $thisMPH = intval( substr($parts[3],0,-3) );
  if ($thisMPH > $peaksped) $peaksped = $thisMPH;
  $thisDRCT = $dirTrans[$parts[2]];
  $thisGust = $parts[12];
  if ($thisGust < $peakgust)  $thisGust = $peakGust;
  else $peakgust = $thisGust;
  if (sizeof($parts) > 13) $hasgust = 1;
  $thisALTI = substr($parts[8],0,-1);
  $thisPREC = substr($parts[9],0,-2);


  if ($thisRelH > 0){
    $thisDwpf = dwpf($thisTmpf, $thisRelH);
  } else {
    $thisDwpf = "";
  }
  if ($thisTmpf < -50 || $thisTmpf > 150 ){
    $thisTmpf = "";
  } else {
    if ($max_yaxis < $thisTmpf){
      $max_yaxis = $thisTmpf;
    }
  }
  if ($thisDwpf < -50 || $thisDwpf > 150 ){
    $thisDwpf = "";
  }  else {
    if ($min_yaxis > $thisDwpf){
      $min_yaxis = $thisDwpf;
    }
  }

  $shouldbe = intval( $start ) + 60 * $i;


  // We are good, write data, increment i
  if ( $shouldbe == $timestamp ){
#    echo " EQUALS <br>";
    $tmpf[$i] = $thisTmpf;
    $dwpf[$i] = $thisDwpf;
    $sr[$i] = $thisSR;
    $xlabel[$i] = $thisTime;
    if ($i % 10 == 0){
      $drct[$i] = $thisDRCT;
    }else{
      $drct[$i] = "-199";
    }
    $mph[$i] = $thisMPH;
    $gust[$i] = $thisGust;
    $prec[$i] = $thisPREC;
    $alti[$i] = $thisALTI * 33.8639;
    if ($alti[$i] < 900)   $alti[$i] = " ";
    $i++;
    continue;

  // Missed an ob, leave blank numbers, inc i
  } else if (($timestamp - $shouldbe) > 0) {
#    echo " TROUBLE <br>";
    $tester = $shouldbe + 60;
    while ($tester <= $timestamp ){
      $tester = $tester + 60 ;
      $tmpf[$i] = "";
      $dwpf[$i] = "";
      $sr[$i] = "";
      $xlabel[$i] ="";
      $drct[$i] = "-199";
      $mph[$i] = "";
      $gust[$i] = "";
      $prec[$i] = "";
      $alti[$i] = "";
      $i++;
      $missing++;
    }
    $tmpf[$i] = $thisTmpf;
    $dwpf[$i] = $thisDwpf;
    $sr[$i] = $thisSR;
    $xlabel[$i] = $thisTime;
    if ($i % 10 == 0){
      $drct[$i] = $thisDRCT;
    } else {
      $drct[$i] = "-199";
    }
    $mph[$i] = $thisMPH;
    $gust[$i] = $thisGust;
    $prec[$i] = $thisPREC;
    $alti[$i] = $thisALTI * 33.8639;
    if ($alti[$i] < 900)   $alti[$i] = " ";

    $i++;
    continue;

    $line_num--;
  } else if (($timestamp - $shouldbe) < 0) {
#    echo "DUP <br>";
     $dups++;

  }

} // End of while

$xpre = array(0 => '12 AM', '1 AM', '2 AM', '3 AM', '4 AM', '5 AM',
        '6 AM', '7 AM', '8 AM', '9 AM', '10 AM', '11 AM', 'Noon',
        '1 PM', '2 PM', '3 PM', '4 PM', '5 PM', '6 PM', '7 PM',
        '8 PM', '9 PM', '10 PM', '11 PM', 'Midnight');

if ($peaksped > $peakGust) $peakGust = $peaksped;

for ($j=0; $j<24; $j++){
  $xlabel[$j*60] = $xpre[$j];
}


// Fix y[0] problems
if ($tmpf[0] == ""){
  $tmpf[0] = 0;
}
if ($dwpf[0] == ""){
  $dwpf[0] = 0;
}
if ($sr[0] == ""){
  $sr[0] = 0;
}


include ("../../include/jpgraph/jpgraph.php");
include ("../../include/jpgraph/jpgraph_line.php");
include ("../../include/jpgraph/jpgraph_scatter.php");

// Create the graph. These two calls are always required
$graph = new Graph(590,360,"example1");
if ($min_yaxis ==  ""){
  $graph->SetScale("textlin", $min_yaxis - 4, $max_yaxis +4);
} else {
  $graph->SetScale("textlin");
}
$graph->img->SetMargin(55,40,55,80);
//$graph->xaxis->SetFont(FONT1,FS_BOLD);
$graph->xaxis->SetTickLabels($xlabel);
//$graph->xaxis->SetTextLabelInterval(60);
$graph->xaxis->SetTextTickInterval(60);

$graph->xaxis->SetLabelAngle(90);
//$graph->yaxis->scale->ticks->SetPrecision(1);
$graph->yscale->SetGrace(10);
$graph->title->Set($Scities[$nwsli]['city'] ." Time Series");
$graph->subtitle->Set($titleDate );

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.075,0.0925);
//$graph->yaxis->scale->ticks->SetPrecision(0);

$graph->title->SetFont(FF_FONT1,FS_BOLD,14);
$graph->subtitle->SetFont(FF_FONT1,FS_BOLD,12);

$graph->yaxis->SetTitle("Temperature [F]");

$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,14);
$graph->yaxis->SetFont(FF_FONT1,FS_BOLD,14);
$graph->xaxis->SetTitle("Valid Local Time");
$graph->xaxis->SetTitleMargin(45);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,14);
$graph->yaxis->SetTitleMargin(35);
$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD,14);
$graph->xaxis->SetPos("min");

// Create the linear plot
$lineplot=new LinePlot($tmpf);
$lineplot->SetLegend("Temperature");
$lineplot->SetColor("red");
// Create the linear plot

$lineplot2=new LinePlot($dwpf);
$lineplot2->SetLegend("Dew Point");
$lineplot2->SetColor("blue");


$graph->Add($lineplot2);
$graph->Add($lineplot);

$graph->Stroke("/mesonet/www/html/".$href."_1.png");

echo '<img src="'.$href.'_1.png">';

//__________________________________________________________________________

// Create the graph. These two calls are always required
$graph = new Graph(590,360,"example1");
$graph->SetScale("textlin",0, 360);
$graph->SetY2Scale("lin", 0, $peakGust + 5);
$graph->img->SetMargin(55,40,55,80);
$graph->xaxis->SetTickLabels($xlabel);
$graph->xaxis->SetTextTickInterval(60);
$graph->xaxis->SetLabelAngle(90);
//$graph->yaxis->scale->ticks->SetPrecision(1);
$graph->title->Set($Scities[$nwsli]['city'] ." Time Series");
$graph->subtitle->Set($titleDate );

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.075,0.105);

$graph->yaxis->scale->ticks->Set(90,15);
//$graph->yaxis->scale->ticks->SetPrecision(0);
$graph->y2axis->scale->ticks->Set(5,1);
//$graph->y2axis->scale->ticks->SetPrecision(0);

$graph->yaxis->SetColor("blue");
$graph->y2axis->SetColor("red");

$graph->title->SetFont(FF_FONT1,FS_BOLD,14);
$graph->subtitle->SetFont(FF_FONT1,FS_BOLD,12);

$graph->yaxis->SetTitle("Wind Direction");
$graph->y2axis->SetTitle("Wind Speed [MPH]");

$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->y2axis->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->yaxis->SetFont(FF_FONT1,FS_BOLD,12);
$graph->y2axis->SetFont(FF_FONT1,FS_BOLD,12);
$graph->xaxis->SetTitle("Valid Local Time");
$graph->xaxis->SetTitleMargin(45);
$graph->yaxis->SetTitleMargin(35);
$graph->y2axis->SetTitleMargin(28);
$graph->xaxis->title->SetFont(FF_FONT1,FS_NORMAL,14);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,14);
$graph->xaxis->SetPos("min");

// Create the linear plot
$lineplot=new LinePlot($mph);
$lineplot->SetLegend($wLabel);
$lineplot->SetColor("red");

if ($hasgust == 1){
  // Create the linear plot
  $lp1=new LinePlot($gust);
  $lp1->SetLegend("Peak Wind Gust");
  $lp1->SetColor("black");
}

// Create the linear plot
$sp1=new ScatterPlot($drct);
$sp1->mark->SetType(MARK_FILLEDCIRCLE);
$sp1->mark->SetFillColor("blue");
$sp1->mark->SetWidth(3);

$graph->Add($sp1);
$graph->AddY2($lineplot);
if ($hasgust == 1){
  $graph->AddY2($lp1);
}

$graph->Stroke("/mesonet/www/html/".$href."_2.png");
echo '<br><br><img src="'.$href.'_2.png">';

//__________________________________________________________________________

$graph = new Graph(590,360,"example1");
$graph->SetScale("textlin");
$graph->SetY2Scale("lin", 0, 4.00);
$graph->img->SetMargin(55,40,55,80);
$graph->xaxis->SetTickLabels($xlabel);
$graph->xaxis->SetTextTickInterval(60);
$graph->xaxis->SetLabelAngle(90);
$graph->title->Set($Scities[$nwsli]['city'] ." Time Series");
$graph->subtitle->Set($titleDate );

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.075,0.125);

$graph->y2axis->scale->ticks->Set(1,0.25);
//$graph->y2axis->scale->ticks->SetPrecision(1);
$graph->yaxis->scale->ticks->Set(2,0.1);
//$graph->yaxis->scale->ticks->SetPrecision(0);

$graph->yaxis->SetColor("black");
$graph->yscale->SetGrace(20);
$graph->y2axis->SetColor("blue");

$graph->title->SetFont(FF_FONT1,FS_BOLD,14);
$graph->subtitle->SetFont(FF_FONT1,FS_BOLD,12);

$graph->yaxis->SetTitle("Pressure [millibars]");
$graph->yaxis->SetFont(FF_FONT1,FS_NORMAL,12);
$graph->yaxis->title->SetFont(FF_FONT1,FS_NORMAL,12);
//$graph->y2axis->SetTitle("Accumulated Precipitation [inches]");
$graph->y2axis->SetFont(FF_FONT1,FS_NORMAL,14);

//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD,12);
$graph->xaxis->SetTitle("Valid Local Time");
$graph->xaxis->SetTitleMargin(45);
$graph->yaxis->SetTitleMargin(40);
//$graph->y2axis->SetTitleMargin(28);
$graph->xaxis->title->SetFont(FF_FONT1,FS_NORMAL,14);
$graph->xaxis->SetFont(FF_FONT1,FS_NORMAL,14);
$graph->xaxis->SetPos("min");

// Create the linear plot
$lineplot=new LinePlot($alti);
$lineplot->SetLegend("Pressure");
$lineplot->SetColor("black");

// Create the linear plot
$lineplot2=new LinePlot($prec);
$lineplot2->SetLegend("Precipitation");
$lineplot2->SetColor("blue");
$lineplot2->SetWeight(2);

$graph->AddY2($lineplot2);
$graph->Add($lineplot);

$graph->Stroke("/mesonet/www/html/".$href."_3.png");
echo '<br><br><img src="'.$href.'_3.png">';

?>
