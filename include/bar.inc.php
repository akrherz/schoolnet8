<div id="wrapper">
<ul id="iemwebring">
 <li><span style="color: #00b;">IEM Webring:</span></li>
 <li><a href="http://mesonet.agron.iastate.edu">Iowa Mesonet</a></li>
 <li><a href="http://iowa.cocorahs.org">Iowa CoCoRaHS</a></li>
 <li><a href="http://wepp.mesonet.agron.iastate.edu">Daily Erosion Project</a></li>
 <li><a href="http://mesonet.agron.iastate.edu/roads/">Iowa Road Conditions</a></li>
</ul>
<div id="imgbar"><?php 
if (! isset($THISPAGE)) $THISPAGE = "";
if (isset($station) && $station != "") {
	include_once("$nwnpath/include/locs.inc.php");
	include_once("$nwnpath/include/sponsors.inc.php");
	echo '<a href="'. $baseurl .'tool/clicktru.php?station='.$station.'" target="_new"><img src="'. $baseurl .'pics/kcci_'. $station .'.gif" alt="Banner" border="0"/></a>';
} else {
        echo '<a href="'. $baseurl .'tool/clicktru.php?station=CIPCO" target="_new"><img src="'. $baseurl .'pics/banner.gif" alt="Banner" border="0"/></a><a href="'. $baseurl .'tool/clicktru.php?station=TOUCH" target="_new"><img src="'. $baseurl .'pics/banner2.gif" alt="Banner" border="0"/></a>';
}
?></div>
<?php
if (!isset($station)) $lstation = "SAMI4";
else $lstation = $station;
$_pages = Array(
 "homepage" => Array(
    "base" => Array("title" => "Homepage", "url" => ""),
 ),
 "mysite" => Array(
    "base" => Array("title" => "My Site", "url" => "site.phtml"),
    "main" => Array("title" => "Mainpage", "url" => "site.phtml"),
    "hist" => Array("title" => "Data Calendar", "url" => "hist.phtml?station=$lstation"),
    "google" => Array("title" => "Google Maps", "url" => "GIS/gm.phtml?station=$lstation"),
    "ortho" => Array("title" => "Orthophotography", "url" => "ortho.phtml?station=$lstation"),
    "trace" => Array("title" => "1 Minute Timeseries", "url" => "plotting/1trace_fe.phtml?station=$lstation"),
 ),
 "current" => Array(
    "base" => Array("title" => "Currents", "url" => "current.phtml"),
    "main" => Array("title" => "Mainpage", "url" => "current.phtml"),
    "radar" => Array("title" => "Live RADAR", "url" => "display.phtml"),
    "sort" => Array("title" => "Sortable Currents", "url" => "cc.phtml"),
 ),
 "download" => Array(
    "base" => Array("title" => "Download", "url" => "dl/index.phtml"),
    "obs" => Array("title" => "Observations", "url" => "dl/index.phtml"),
    "sum" => Array("title" => "Daily Summary", "url" => "dl/index.phtml?page=sum"),
    "sview" => Array("title" => "SchoolNet8 Viewer", "url" => "sview/"),
 ),
 "training" => Array(
    "base" => Array("title" => "Learn", "url" => "training/"),
    "main" => Array("title" => "Mainpage", "url" => "training/"),
    "faq" => Array("title" => "FAQ", "url" => "info/faq.phtml"),
    "101" => Array("title" => "SchoolNet8 101", "url" => "schoolnet101/"),
    "guide" => Array("title" => "Site Guide", "url" => "guide/"),
 ),
 "siteindex" => Array(
    "base" => Array("title" => "Site Index", "url" => "guide/siteindex.phtml"),
 ),
 "webcam" => Array(
    "base" => Array("title" => "WebCams", "url" => "camera/"),
    "current" => Array("title" => "Current/Past Images", "url" => "camera/"),
    "lapse" => Array("title" => "Lapse Builder", "url" => "camera/bloop.phtml"),
    "live" => Array("title" => "Live Image", "url" => "camera/viewer.phtml"),
    "daily" => Array("title" => "Daily Movies", "url" => "camera/movies/"),
    "bestof" => Array("title" => "Best Of!", "url" => "camera/bestof.phtml"),
 ),
);
$THISPAGE = isset($THISPAGE) ? $THISPAGE : "networks-base";
$ar = split("-", $THISPAGE);
if (sizeof($ar) == 1) $ar[1] = "";
echo "<div id=\"iem_nav\"><ul>\n";
$b = "";
while( list($idx, $page) = each($_pages) )
{
  echo sprintf("<li%s><a href=\"%s\">%s</a></li>", 
      ($ar[0] == $idx) ? " class=\"selected\"" : " ",
      $baseurl . $page["base"]["url"], $page["base"]["title"]);
  if ($ar[0] == $idx)
  {
    if (sizeof($page) == 1) { $b = ""; continue; }
    $b .= "<div id=\"iem_subnav\"><ul>\n";
    while( list($idx2, $page2) = each($page) )
    {
       if ($idx2 == "base") continue;
       $b .= sprintf("<li%s><a href=\"%s\">%s</a></li>", 
         ($ar[1] == $idx2) ? " class=\"selected\"" : " ",
          $baseurl . $page[$idx2]["url"], 
     ($ar[1] == $idx2) ? "[ ". $page[$idx2]["title"] ." ]": $page[$idx2]["title"
] );
    }
    $b .= "</ul></div>\n";
  }
}
echo "<ul></div> $b";
?>
<br clear="all" />
