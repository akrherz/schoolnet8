<?php 
/*
 DROP table site_stats_report;
 DROP table apphits;
 create table site_stats_report as SELECT station, count(valid) as hits, count(distinct(ip)) as hosts from site_stats GROUP by station;
 update site_stats_report SET station = 'CIPCO' WHERE station = 'NONE';
 delete from site_stats_report WHERE station = 'S03I4';
 create table apphits as SELECT count(valid) as hits, app from site_stats GROUP by app;
 delete from apphits WHERE app = -1;
 GRANT select on site_stats_report to apache;
 GRANT select on apphits to apache;
*/

  set_time_limit(1000);
  define('FPDF_FONTPATH','pdf/font/');
  require('pdf/fpdf.php');
  include('../../config/settings.inc.php');

class PDF extends FPDF
{
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
function Header()
{
    global $station;
    global $Scities;
    //Logo
    $this->Image('../images/banner.png',40,8, 120);
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    $this->Ln(2);
    //Title
    $mo = date('M Y');
    //$this->Cell(180,30,'KCCI SchoolNet8 WebSite Stats for Jan 2005' ,0,0,'C');
    $this->Cell(180,30,'KCCI SchoolNet8 WebSite Stats for '. $mo ,0,0,'C');
    //Line break
    $this->Ln(20);
}// End Header

//Load data
function LoadData()
{
  global $sponsors, $station,  $byS, $Scities, $dbhost;
  $c = pg_connect($dbhost);
  pg_exec($c, "set enable_seqscan=off");
  $q0 = "SELECT station, hits, 
    hosts from site_stats_report ORDER by station ASC";
  $r0 = pg_exec($c, $q0);

 $data=array();
 $j = 0;
 for( $i=0; $row = @pg_fetch_array($r0,$i); $i++){
	$station = $row["station"];
	if (array_key_exists($station, $sponsors) || $station == "CIPCO") {
		$data[$j]=$row;
		$q1 = "SELECT count(valid) as c_count from clicktru WHERE 
			station = '". $row["station"] ."' ";
		$r1 = pg_exec($c, $q1);
		$data[$j] += pg_fetch_array($r1, 0);
		$data[$j]['short'] = $Scities[$data[$j]['station']]['short'];
		$data[$j]['sponsor'] = $sponsors[$data[$j]['station']]['sponsor'];
		$byS[$sponsors[$row['station']]['sponsor']]['sponsor'] = $sponsors[$data[$j]['station']]['sponsor'];
		@$byS[$sponsors[$row['station']]['sponsor']]['hits'] += $row['hits'];
		@$byS[$sponsors[$row['station']]['sponsor']]['c_count'] += $data[$j]['c_count'];
		$j += 1;
   }
 
 
 }
 return $data;
} // End of LoadData()

function WriteHTML($html)
{
    //HTML parser
    $html=str_replace("\n",' ',$html);
    $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
    foreach($a as $i=>$e)
    {
        if($i%2==0)
        {
            //Text
            if (isset($this->HREF))
                $this->PutLink($this->HREF,$e);
            else
                $this->Write(5,$e);
        }
        else
        {
            //Tag
            if($e{0}=='/')
                $this->CloseTag(strtoupper(substr($e,1)));
            else
            {
                //Extract properties
                $a2=split(' ',$e);
                $tag=strtoupper(array_shift($a2));
                $prop=array();
                foreach($a2 as $v)
                    if(ereg('^([^=]*)=["\']?([^"\']*)["\']?$',$v,$a3))
                        $prop[strtoupper($a3[1])]=$a3[2];
                $this->OpenTag($tag,$prop);
            }
        }
    }
}

//Colored table
function FancyTable($header,$data, $pTotals)
{
        global $tHits, $tClicks, $tHosts;
        //Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        //Header
        $w=array(40,80,25,25,25);
        for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
        $this->Ln();
        //Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        //Data
        $fill=0;
        foreach($data as $row)
        {
           if ($row['station'] == "CIPCO") $tHosts = $row['hosts'];
           $tHits += $row['hits'];
           $tClicks += $row['c_count'];

                $this->Cell($w[0],5,$row['short'],'LR',0,'L',$fill);
                $this->Cell($w[1],5,$row['sponsor'],'LR',0,'L',$fill);
                $this->Cell($w[2],5,number_format($row['hits']),'LR',0,'R',$fill);
                $this->Cell($w[3],5,number_format($row['hosts']),'LR',0,'R',$fill);
                $this->Cell($w[4],5,number_format($row['c_count']),'LR',0,'R',$fill);
                $this->Ln();
                $fill=!$fill;
        }
  if ($pTotals){
    $this->Cell($w[0],5,"TOTAL:",'LR',0,'L',$fill);
    $this->Cell($w[1],5," ",'LR',0,'L',$fill);
    $this->Cell($w[2],5,number_format($tHits),'LR',0,'R',$fill);
    $this->Cell($w[3],5,number_format($tHosts),'LR',0,'R',$fill);
    $this->Cell($w[4],5,number_format($tClicks),'LR',0,'R',$fill);
    $this->Ln();
  }

        $this->Cell(array_sum($w),0,'','T');
}

function FancyTable2($header,$data)
{
        //Colors, line width and bold font
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        //Header
        $w=array(80,25,25);
        for($i=0;$i<count($header);$i++)
                $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
        $this->Ln();
        //Color and font restoration
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        //Data
        $fill=0;$tHits=0;$tClick=0;
        foreach($data as $row)
        {
           if (@$row['station'] == "CIPCO") $tHosts = $row['hosts'];
           $tHits += $row['hits'];
           $tClick += $row['c_count'];

                $this->Cell($w[0],6,$row['sponsor'],'LR',0,'L',$fill);
                $this->Cell($w[1],6,number_format($row['hits']),'LR',0,'R',$fill);
                $this->Cell($w[2],6,number_format($row['c_count']),'LR',0,'R',$fill);
                $this->Ln();
                $fill=!$fill;
        }
  $this->Cell($w[0],6,"TOTAL:",'LR',0,'L',$fill);
  $this->Cell($w[1],6,number_format($tHits),'LR',0,'R',$fill);
  $this->Cell($w[2],6,number_format($tClick),'LR',0,'R',$fill);
  $this->Ln();

        $this->Cell(array_sum($w),0,'','T');
}

function FancyTable3($header,$rs)
{
  //Colors, line width and bold font
  $this->SetFillColor(255,0,0);
  $this->SetTextColor(255);
  $this->SetDrawColor(128,0,0);
  $this->SetLineWidth(.3);
  $this->SetFont('','B');
  //Header
  $w=array(100,25);
  for($i=0;$i<count($header);$i++)
  {
    $this->Cell($w[$i],7,$header[$i],1,0,'C',1);
  }
  $this->Ln();
  //Color and font restoration
  $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        //Data
        $fill=0;
        $tHits = 0;
  $appDict = Array(
    "" => "Unknown",
    "  " => "Un-registered application",
    "01" => "Graphical Current Plot",
    "02" => "Textual Current Page",
    "03" => "1 Minute Data Trace",
    "04" => "Sortable Currents Table",
    "05" => "SchoolNet8 Homepage",
    "06" => "Current's Homepage",
    "07" => "Charts/Plots Homepage",
    "08" => "SchoolNet8 Download Page",
    "09" => "Information Homepage",
    "10" => "WAP Mainpage",
    "11" => "WAP Current Ob View",
    "12" => "Dynamic Plotter",
    "13" => "Weather Data Calendar",
    "14" => "SchoolNet8 Viewer Page",
    "15" => "State Fair Display",
    "16" => "Web Site Guide",
        "17" => "Ortho Images",
    "18" => "Site Guide",
    "19" => "Site Index",
    "20" => "Website Guide",
    "21" => "GoogleMap Test Interface",
    "22" => "Live Doppler static RADAR Viewer",
    "23" => "Web Camera Mainpage",
    "24" => "Web Camera Dynamic Loops",
    "25" => "Web Camera 640x480 page",
    "26" => "Web Camera Live Shots",
    "27" => "Web Camera Lapses",
    "28" => "Current Data RSS",
    "29" => "Monthly climate chart",
    "30" => "Web Camera Movies",
    "31" => "Web Camera Best Of",

);

   for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
        {
           $tHits += $row['hits'];

          $this->Cell($w[0],6,$appDict[$row['app']],'LR',0,'L',$fill);
          $this->Cell($w[1],6,number_format($row['hits']),'LR',0,'R',$fill);
          $this->Ln();
          $fill=!$fill;
        }
  $this->Cell($w[0],6,"TOTAL:",'LR',0,'L',$fill);
  $this->Cell($w[1],6,number_format($tHits),'LR',0,'R',$fill);
  $this->Ln();

  $this->Cell(array_sum($w),0,'','T');
}

function OpenTag($tag,$prop)
{
    //Opening tag
    if($tag=='B' or $tag=='I' or $tag=='U')
        $this->SetStyle($tag,true);
    if($tag=='A')
        $this->HREF=$prop['HREF'];
    if($tag=='BR')
        $this->Ln(5);
}

function CloseTag($tag)
{
    //Closing tag
    if($tag=='B' or $tag=='I' or $tag=='U')
        $this->SetStyle($tag,false);
    if($tag=='A')
        $this->HREF='';
}

function SetStyle($tag,$enable)
{
    //Modify style and select corresponding font
    if (! isset($this->$tag)) $this->$tag = 0;
    $this->$tag+=($enable ? 1 : -1);
    $style='';
    foreach(array('B','I','U') as $s)
        if(isset($this->$s))
            $style.=$s;
    $this->SetFont('',$style);
}

function PutLink($URL,$txt)
{
    //Put a hyperlink
    $this->SetTextColor(0,0,255);
    $this->SetStyle('U',true);
    $this->Write(5,$txt,$URL);
    $this->SetStyle('U',false);
    $this->SetTextColor(0);
}

} // End of FPDF


include("../../include/locs.inc.php"); 
include("../../include/sponsors.inc.php"); 
$Scities["CIPCO"] = Array("short" => "CIPCO Logo", "city" => "CIPCO", "id" =>  "CIPCP");
$sponsors["CIPCO"] = Array("name"=> "CIPCO Logo", "sponsor" => "CIPCO");
$byS = Array();


$html = '
  <BR>A hit is one page load showing that sponsors logo.
  <BR>The Hosts column is the number of unique IPs, or to some extent, a measure of the number of people visiting the site.
  <BR>Click Thrus are the number of times a visitor followed the link included with the banner.
';

$html2 = '<b>Website:</b> <A HREF="http://www.theiowachannel.com/schoolnet8">KCCI SchoolNet8</A>';


$pdf=new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',12);

$data = $pdf->LoadData();

$pdf->AddPage();
$pdf->Cell(40,10,'Stats by Station');
$pdf->Ln(10);
$header = array('Station', 'Sponsor', 'Hits', 'Hosts', 'Click Thrus');
$pdf->FancyTable($header,array_slice($data, 0, 33), false );
$pdf->Ln(10);
//$pdf->Cell(40,10,'Continued on next page...');

$pdf->AddPage();
$pdf->FancyTable($header,array_slice($data, 33), true);
$pdf->Ln(10);
//$pdf->Cell(40,10,'Continued on next page...');

$pdf->AddPage();
$header = array('Sponsor', 'Hits', 'Click Thrus');
$pdf->Cell(40,10,'Stats by Sponsor');
$pdf->Ln(10);
$pdf->FancyTable2($header,$byS);

$pgconn = pg_connect($dbhost);
  pg_exec($pgconn, "set enable_seqscan=off");
$rs = pg_exec($pgconn, "SELECT  hits,
      app from apphits ORDER by app ASC");
pg_close($pgconn);

$pdf->AddPage();
$header = array('Application', 'Hits');
$pdf->Cell(40,10,'Hits by Application');
$pdf->Ln(10);
$pdf->FancyTable3($header,$rs);

$pdf->Ln(10);
$pdf->Cell(40,10,'Notes:');
$pdf->Ln(10);
$pdf->WriteHTML($html);
$pdf->Ln(10);
$pdf->WriteHTML($html2);
$pdf->Output();
?>
