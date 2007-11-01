<div id="wrapper">
<ul id="iemwebring">
 <li><span style="color: #00b;">IEM Webring:</span></li>
 <li><a href="http://mesonet.agron.iastate.edu">Iowa Mesonet</a></li>
 <li><a href="http://iowa.cocorahs.org">Iowa CoCoRaHS</a></li>
 <li><a href="http://wepp.mesonet.agron.iastate.edu">Daily Erosion Project</a></l
i>
 <li><a href="http://mesonet.agron.iastate.edu/roads/">Iowa Road Conditions</a></
li>
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

<ul id="linkbar">
 <li class="<?php if ($THISPAGE == "homepage") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>">Home</a></li>
 <li class="<?php if ($THISPAGE == "mysite") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>site.phtml">My Site</a></li>
 <li class="<?php if ($THISPAGE == "current") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>current.phtml">Currents</a></li>
 <li class="<?php if ($THISPAGE == "download") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>dl/index.phtml">Download</a></li>
 <li class="<?php if ($THISPAGE == "siteindex") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>guide/siteindex.phtml">Site Index</a></li>
 <li class="<?php if ($THISPAGE == "training") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>training/">Training</a></li>
 <li class="<?php if ($THISPAGE == "webcam") echo "selected"; ?>"><a href="<?php echo $baseurl; ?>camera/index.phtml">WebCams</a></li>
</ul>
