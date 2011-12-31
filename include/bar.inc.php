<?php
/* Header bar providing for the sponsor logo and menu bar */
include_once("$nwnpath/include/locs.inc.php");
include_once("$nwnpath/include/sponsors.inc.php");

$hextra = "";
if (isset($station) && $station != "") { $lstation = $station; }
else if (isset($camid)){ $lstation = $camid; }
else if (! isset($station)){ 
	/* This is the special header bar for generic pages, need to have a 
	 * special clicktru option as well
	 */
	$lstation = "MAIN"; 
	$hextra = sprintf("<a href=\"%s/tool/clicktru.php?station=TOUCH\">
	<img src=\"%s/pics/touchstone.png\" border=\"0\" /></a>", BASEURL, BASEURL);
}
else { $lstation = $station; }

$clickapp = sprintf("%s/tool/clicktru.php?station=%s", BASEURL, $lstation);
$sponsorlogo = sprintf("%s/pics/%s.png", BASEURL, $lstation);
?>
<div id="wrapper">
<div id="imgbar" style="height:60px;">
<a href="<?php echo $clickapp; ?>" target="_new"><img src="<?php echo $sponsorlogo; ?>" alt="Banner" style="border:0px;" height="60"/></a>
<?php echo $hextra; ?></div>
<?php
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
    "favorites" => Array("title" => "Favorites", "url" => "ccfav.phtml"),
    "radar" => Array("title" => "Live RADAR", "url" => "display.phtml"),
    "sort" => Array("title" => "Sortable Currents", "url" => "cc.phtml"),
    "wap" => Array("title" => "WAP", "url" => "wap/"),
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
    "features" => Array("title" => "Features", "url" => "guide.phtml"),
    "101" => Array("title" => "SchoolNet8 101", "url" => "schoolnet101/"),
    "guide" => Array("title" => "Site Guide", "url" => "guide/"),
    "survey" => Array("title" => "Survey", "url" => "survey.phtml"),
    "web" => Array("title" => "Web Devel", "url" => "info/webdevel.phtml"),
 ),
 "siteindex" => Array(
    "base" => Array("title" => "Site Index", "url" => "guide/siteindex.phtml"),
 ),
 "webcam" => Array(
    "base" => Array("title" => "WebCams", "url" => "camera/"),
    "current" => Array("title" => "Current/Past Images", "url" => "camera/"),
    "lapse" => Array("title" => "Lapse Builder", "url" => "camera/bloop.phtml"),
    "learn" => Array("title" => "Learn", "url" => "camera/movies/learn.phtml"),
    "live" => Array("title" => "Live Image", "url" => "camera/viewer.phtml"),
    "daily" => Array("title" => "Daily Movies", "url" => "camera/movies/"),
    "bestof" => Array("title" => "Best Of!", "url" => "camera/bestof.phtml"),
 ),
);
$THISPAGE = isset($THISPAGE) ? $THISPAGE : "homepage-base";
$ar = preg_split('/-/', $THISPAGE);
if (sizeof($ar) == 1) $ar[1] = "";
echo "<div id=\"iem_nav\"><ul>\n";
$b = "";
while( list($idx, $page) = each($_pages) )
{
  echo sprintf("<li%s><a href=\"%s\">%s</a></li>", 
      ($ar[0] == $idx) ? " class=\"selected\"" : " ",
      BASEURL . $page["base"]["url"], $page["base"]["title"]);
  if ($ar[0] == $idx)
  {
    if (sizeof($page) == 1) { $b = ""; continue; }
    $b .= "<div id=\"iem_subnav\"><ul>\n";
    while( list($idx2, $page2) = each($page) )
    {
       if ($idx2 == "base") continue;
       $b .= sprintf("<li%s><a href=\"%s\">%s</a></li>", 
         ($ar[1] == $idx2) ? " class=\"selected\"" : " ",
          BASEURL . $page[$idx2]["url"], 
     ($ar[1] == $idx2) ? "[ ". $page[$idx2]["title"] ." ]": $page[$idx2]["title"
] );
    }
    $b .= "</ul></div>\n";
  }
}
echo "<ul></div> $b";
?>
<br clear="all" />
