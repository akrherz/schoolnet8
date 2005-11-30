<?php
 include("../../config/settings.inc.php");
 $c = pg_connect($dbhost);

if ( strlen($station) == 0 ) {
	$station = "CIPCO";
}
$station = $_GET['station'];
$query2 = "select count(station), extract(day from valid) as day 
    from site_stats WHERE valid > '2002-12-01 00:00' and
    station = '".$station."' GROUP by day";

$result = pg_exec($c, $query2);

$ydata = array();
$xlabel= array();

for($i=1;$i<32;$i++){
  $ydata[$i - 1] = 0;
  $xlabel[$i - 1] = $i;
}

$j = 0;
for( $i=0; $row = @pg_fetch_array($result,$i); $i++) 
{ 
  $ydata[intval($row['day']) - 1]  = $row['count'];
}


pg_close($c);

$max_hits = max($ydata);

include ("../plotting/jpgraph/jpgraph.php");
include ("../plotting/jpgraph/jpgraph_bar.php");


// Create the graph. These two calls are always required
$graph = new Graph(400,200,"example1");
$graph->SetScale("textlin");
$graph->img->SetMargin(40,10,40,40);

$graph->legend->SetLayout(LEGEND_HOR);
$graph->legend->Pos(0.05, 0.1, "right", "top");

$graph->title->Set($station ." hits per day");
$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);

$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);

$graph->xaxis->SetTitle("Day of the Month");
$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL, 10);
$graph->xaxis->SetTickLabels($xlabel);
$graph->xaxis->SetLabelAngle(90);


// Create the linear plot
$lineplot=new BarPlot($ydata);
$lineplot->SetColor("red");

// Add the plot to the graph
$graph->Add($lineplot);

// Display the graph
$graph->Stroke();
?>

