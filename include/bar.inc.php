<?php include("$nwnpath/include/webring.html"); ?>
<div id="wrapper">
<div id="imgbar"><?php if (isset($station) && $station != "") {
	include_once("$nwnpath/include/locs.inc.php");
	include_once("$nwnpath/include/sponsors.inc.php");
	echo '<a href="'. $baseurl .'tool/clicktru.php?station='.$station.'" target="_new"><img src="'. $baseurl .'pics/kcci_'. $station .'.gif" alt="Banner" border="0"/></a>';
	echo "<br /><b><a href=\"$baseurl/site.phtml?station=$station\">". $Scities[$station]["city"] ."</a></b> SchoolNet8 Site is sponsored by <a href=\"". $baseurl ."tool/clicktru.php?station=$station\">". $sponsors[$station]["sponsor"] ."</a>";
       } else {
        echo '<a href="'. $baseurl .'tool/clicktru.php?station=CIPCO" target="_new"><img src="'. $baseurl .'pics/banner.gif" alt="Banner" border="0"/></a>';
       } ?></div>

<div id="mainNavOuter">
<div id="mainNav">
<div id="mainNavInner">

<ul>
 <li id="mainFirst<?php if ($THISPAGE == "homepage") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>">Home</a></li>
 <li id="main<?php if ($THISPAGE == "mysite") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>site.phtml">My Site</a></li>
 <li id="main<?php if ($THISPAGE == "current") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>current.phtml">Currents</a></li>
 <li id="main<?php if ($THISPAGE == "download") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>dl/index.phtml">Download</a></li>
 <li id="main<?php if ($THISPAGE == "info") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>info/index.phtml">Info</a></li>
 <li id="main<?php if ($THISPAGE == "guide") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>guide/index.phtml">Site Guide</a></li>
 <li id="main<?php if ($THISPAGE == "siteindex") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>guide/siteindex.phtml">Site Index</a></li>
 <li id="main<?php if ($THISPAGE == "101") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>schoolnet101/index.phtml">SNET 101</a></li>
 <li id="mainLast<?php if ($THISPAGE == "webcam") echo "-active"; ?>"><a href="<?php echo $baseurl; ?>camera/index.phtml">WebCams</a></li>
</ul>

</div>
</div>
</div>
