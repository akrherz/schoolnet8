<?php
/* Great an rss for current observations */

include("../config/settings.inc.php");
include('../include/locs.inc.php');
include('../include/currentdb.inc.php');
$app = "28"; include("../include/dblog.inc.php");

$xmlfile = "/tmp/currentwx.xml";

header("Content-type: text/xml");

/* Check to see if the file is less than 5 minutes old, if so, send it */
if ( file_exists($xmlfile) && (filemtime($xmlfile) + 300) > time() )
{
  readfile($xmlfile);
  exit();
}

/* Well, if not, we need to generate the XML file! */
$s =  "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>";
$s .= "<rss version=\"2.0\">";
$s .= "<channel>";
$s .= "<title>SchoolNet8 Current Obs</title>";
$s .= "<link>http://www.schoolnet8.com</link>";
$s .= "<description>SchoolNet8 Current Obs RSS</description>";
$s .= "<lastBuildDate>". date("c") ."</lastBuildDate>";

function aSortBySecondIndex($multiArray, $secondIndex, $sortdir) {
        while (list($firstIndex, ) = each($multiArray))
                $indexMap[$firstIndex] = $multiArray[$firstIndex][$secondIndex];    if ($sortdir == "down") asort($indexMap);
    else arsort($indexMap);

        while (list($firstIndex, ) = each($indexMap))
                if (is_numeric($firstIndex))
                        $sortedArray[] = $multiArray[$firstIndex];
                else $sortedArray[$firstIndex] = $multiArray[$firstIndex];
        return $sortedArray;
}


$obs = new currentdb();
while (list ($key, $val) = each ($obs->db))  {
  $obs->db[$key]['sname'] =  $Scities[$key]['short'];
}
reset ($obs->db);
$finalA = Array();
$finalA = aSortBySecondIndex($obs->db, "sname", "down");


while (list ($key, $val) = each ($finalA))  {
  $s .= "<item>";
  if ( ($val["ts"] + 300) < time() ) 
  {
    $s .= sprintf("<title>%s; Currently Not Reporting</title>", $Scities[$key]['short']  );
  } else {
    $s .= sprintf("<title>%s; Temp: %s F, Rainfall: %s</title>", $Scities[$key]['short'], $val["tmpf"], $val["pday"] );
  }
  $s .= "<author>KCCI</author>";
  $s .= "<link>http://www.schoolnet8.com/site.phtml?station=". $key ."</link>";
  $s .= "</item>";
}

$s .= "</channel>\n";
$s .= "</rss>\n";

$fp = fopen($xmlfile, 'w');
fwrite($fp, $s);
fclose($fp);

readfile($xmlfile);
?>
