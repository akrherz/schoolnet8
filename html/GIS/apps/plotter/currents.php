<?php
$expiry = 60*60*24*100; // 100 days
session_start();

include("../../../../config/settings.inc.php"); 
dl($mapscript);
$app = "12"; include("$nwnpath/include/dblog.inc.php"); 
include("$nwnpath/include/forms.inc.php");

$ERROR = "";
$layers = isset($_GET["layers"]) ? $_GET["layers"] : Array("nws_warnings");
$radar = isset($_GET["radar"]) ? strtoupper($_GET["radar"]) : "KCCI";
$refresh = isset($_GET["refresh"]) ? intval($_GET["refresh"]): 60;
$mode = isset($_GET["mode"]) ? $_GET["mode"] : "realtime";
$year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
$month = isset($_GET["month"]) ? $_GET["month"] : date("m");
$day = isset($_GET["day"]) ? $_GET["day"] : date("d");
$hour = isset($_GET["hour"]) ? $_GET["hour"] : date("H");
$minute = isset($_GET["minute"]) ? $_GET["minute"] : date("i");
$extents = isset($_GET['extents']) ? $_GET['extents'] : "200000,4400000,710000,4900000";
$zoom     = isset($_GET['zoom']) ? $_GET['zoom'] : 0;
$street = isset($_GET["street"]) && strlen($_GET["street"]) > 0 ? $_GET["street"] : "";
$city = isset($_GET["city"]) && strlen($_GET["city"]) > 0 ? $_GET["city"] : "";
//$img_x = $_GET["img_x"];
//$img_y = $_GET["img_y"];
$showRoadCond = isset($_GET["roadcond"]);
//$showRoadCond = 0;
$showSiteLabel = isset($_GET["sitelabel"]);

if ($mode == "archive")
{
  $radar = "KCCI";
  $showRoadCond = false;
  $archivestart = mktime(12,0,0,3,1,2004); // 1 March 2004
  $archivets = mktime($_GET["hour"], $_GET["minute"], 0, $_GET["month"], $_GET["day"], $_GET["year"]);
  if ($archivestart > $archivets || time() < $archivets ) 
  { 
    $ERROR .= "Your date choice is not contained in the archive.  Switching to realtime mode.<br />"; 
    $mode = "realtime";
  }
}

function click2geo($oextents, $click_x, $click_y, $imgsz_x, $imgsz_y, $zoom) {                                                                              
  $arExtents = explode(",", $oextents);
  $ll_x = $arExtents[0];
  $ll_y = $arExtents[1];
  $ur_x = $arExtents[2];
  $ur_y = $arExtents[3];
//  print_r($arExtents);
                                                                              
  $dy = ($ur_y - $ll_y) / floatval($imgsz_y);
  $dx = ($ur_x - $ll_x) / floatval($imgsz_x);
                                                                              
  $centerX = ($click_x * $dx) + $ll_x ;
  $centerY = $ur_y - ($click_y * $dy) ;
                                                                              
  if (intval($zoom) < 0)
    $zoom = -1 / intval($zoom) ;
                                                                              
  $n_ll_x = $centerX - (($dx * $zoom) * ($imgsz_x / 2.00));
  $n_ur_x = $centerX + (($dx * $zoom) * ($imgsz_x / 2.00));
  $n_ll_y = $centerY - (($dy * $zoom) * ($imgsz_y / 2.00));
  $n_ur_y = $centerY + (($dy * $zoom) * ($imgsz_y / 2.00));
                                                                              
  return $n_ll_x .",". $n_ll_y .",". $n_ur_x .",". $n_ur_y ;
}

$imgwidth = 640;
$imgheight = 480;
$oextents = isset($_GET['extents']) ? $_GET['extents'] : "200000,4400000,710000,4900000";
if ( $zoom == 0 ){ // Full Extents
  unset($_GET["img_x"]);
  $oextents = "200000,4400000,710000,4900000";
}
if ( isset($_GET['img_x']) && strlen($_GET['img_x']) > 0 ){
	$extents = click2geo($oextents, $_GET['img_x'], $_GET['img_y'],
	$imgwidth, $imgheight, $zoom);
} elseif ( isset($_GET["street"]) && isset($_GET["city"]) && strlen($_GET["city"]) > 0 )  {
	$s = strlen($_GET["street"]) > 0 ? $_GET["street"] : "100 Main Street";
	$u = sprintf("%s/geocoder.py?street=%s&city=%s", $basecgi, 
		$s, $_GET["city"]);
	$res = trim( file_get_contents(str_replace(" ", "%20", $u)) );
	//echo "GEOCODER RESPONSE:::$res::: $u :::";
	if (substr($res,0,5) == "ERROR")
	{
		$ERROR .= "Geocoder did not find your address, $s, ". $_GET["city"] .", sorry";
		$extents = $oextents;
	} else {
		$tokens = explode(",", $res);
		$lat = $tokens[0];
		$lon = $tokens[1];
		$projInObj = ms_newprojectionobj("init=epsg:4326");
		$projOutObj = ms_newprojectionobj("init=epsg:26915");
		$point = ms_newpointobj();
		$point->setXY($lon, $lat);
		$point->project($projInObj, $projOutObj);
		$a = Array( $point->x - 20000, $point->y - 15000,
                $point->x + 20000, $point->y + 15000);
		$extents = implode(",", $a);
	}
} else {
   $extents = $oextents;
}


/**
 * Function to find a radar image valid near this time 
 */
function findradarts($t)
{
  $format = "/mesonet/ARCHIVE/data/%Y/%m/%d/GIS/kcci/KCCI_%Y%m%d%H%M.png";
  $fp = "/mesonet/ARCHIVE/data/". gmdate("Y/m/d", $t) ."/GIS/kcci/KCCI_"
     . gmdate("YmdHi", $t) . ".png";
  if (is_file($fp)) { return $t; }

  for($i= $t - 240; $i < $t + 240; $i = $i + 60)
  {
    $fp = "/mesonet/ARCHIVE/data/". gmdate("Y/m/d", $i) ."/GIS/kcci/KCCI_"
     . gmdate("YmdHi", $i) . ".png";
    if (is_file($fp))
    {
	  copy($fp, "/tmp/KCCI_". gmdate("YmdHi", $i) .".png");
	  copy("/home/ldm/data/kcci/max.wld", "/tmp/KCCI_". gmdate("YmdHi", $i) .".wld" );
      return $i;
    } 
  }
  return 0;
}

//$st = $_GET['st'];
$st = isset($_GET['st']) ? $_GET['st'] : Array();
if (isset($_SESSION['lsd_st']) && ! isset($_GET['st']) )
{
  $st = $_SESSION['lsd_st'];
}
$_SESSION['lsd_st'] = $st; 

$var = isset($_GET['var']) ? $_GET["var"]: "tmpf";

include("$nwnpath/include/locs.inc.php");
include("$nwnpath/include/radar.php");

/* Lets check to see if we specified a timestamp! */
if ( $mode == "archive" )
{
	$radarts = findradarts($archivets);
    if ($radarts == 0) /* Did not find radar! */
    {
      $ERROR .= "Could not find archived Super Doppler for this time.<br />";
      $radarts = $archivets;
      $MISSINGRADAR = true;
    }
}

$myStations = Array();
$formStr = "";
$cgiStr = "";
$str = isset($_GET['str']) ? $_GET['str'] : Array();

if ( isset($st) ) {
  foreach ($st as $key => $value) {
    if (strlen($value) > 0 && $value != "ahack") {
      if (! in_array($value, $str) ){
        $myStations[$value] = "hi";
      }
    }
  }
} else {
  $myStations["SKCI4"] = 'hi';
}

foreach ($myStations as $key => $value) {
  if (strlen($value) > 0 && $value != "ahack") {
    $formStr .= "<input type='hidden' name='st[]' value='".$key."'>\n";
    $cgiStr .= "st[]=". $key ."&";
  }
}

if (sizeof($myStations) == 0) {
  $myStations["SKCI4"] = 'hi';
}


$varDef = Array("tmpf" => "Current Temperatures",
  "dwpf" => "Current Dew Points",
  "alti" => "Pressure",
  "sped" => "Current Wind Speed [MPH]",
  "sknt" => "Current Wind Speed [knts]",
  "barb" => "Current Wind Barbs [knts]",
  "gbarb" => "Today Wind Gust Barbs [knts]",
  "max_sped" => "Today's Wind Gust [MPH]",
  "max_sknt" => "Today's Wind Gust [knts]",
  "feel" => "Currently Feels Like",
  "tmpf_max" => "Today's High Temperature",
  "tmpf_min" => "Today's Low Temperature",
  "pmonth" => "This Month's Precip",
  "pday" => "Today's Precip");


function sel($svar, $var, $txt){
  echo "<option value='".$var."' ";
  if ($var == $svar) echo "SELECTED";
  echo ">". $txt ."</option>\n";
}
if ($showRoadCond)
{
  $cgiStr .= "roadcond=show&";
}
if ($showSiteLabel)
{
  $cgiStr .= "sitelabel=show&";
}

$lstr = implode("&layers[]=", $layers);
$mapurl =  "${baseurl}GIS/map.php?layers[]=$lstr&zoom=1&mode=$mode&year=$year&month=$month&day=$day&hour=$hour&minute=$minute&extents=$extents&radar=$radar&var=$var&$cgiStr";


?>

<html>
<head>
  <title>Live Super Doppler8 App</title>
<script Language="JavaScript">
<!--
 function resetButtons(){
   document.panButton.src = '../../../images/button_pan_off.png';
   document.zoominButton.src = '../../../images/button_zoomin_off.png';
   document.zoomoutButton.src = '../../../images/button_zoomout_off.png';
   document.zoomfullButton.src = '../../../images/button_zoomfull_off.png';
   document.saveButton.src = '../../../images/button_save_off.png';
 }

var sURL = unescape(window.location.pathname);

function doLoad()
{
    // the timeout value should be the same as in the "refresh" meta-tag
<?php if ($refresh > 0) { ?>
    setTimeout( "refresh()", <?php echo $refresh; ?> * 1000 );
<?php } ?>
}

function refresh()
{
    //  This version of the refresh function will cause a new
    //  entry in the visitor's history.  It is provided for
    //  those browsers that only support JavaScript 1.0.
    //
    //document.getElementById("hider1").style.display='none'; //simply hides status and loader bar
<?php if (! isset($radarts) && $refresh > 0) { ?>
  ts = new Date();
  i = new Image();
  i.src = "<?php echo $mapurl; ?>&"+ ts.getTime();
  document.images["mymap"].src = i.src;
  setTimeout( "refresh()", <?php echo $refresh; ?> * 1000 );
<?php } ?>
}

//-->
</script>
  <style type="text/css" media="screen">@import "../../../css/main.css";</style>
</head>
<body onload="doLoad()">

<table style="float: left; align: left;">
 <tr>
   <td bgcolor="#EEEEEE" valign="top" rowspan="3">
<form method="GET" action="currents.php" name="myform">

<input type="hidden" name="extents" value="<?php echo $extents; ?>">
<input type="hidden" name="zoom" value="1">
<?php echo $formStr; ?>

<b>- Map Controls:</b><br />
<img src="../../../images/button_zoomin_off.png" name="zoominButton" alt="Zoom In"
  onClick="javascript: resetButtons(); document.zoominButton.src = '../../../images/button_zoomin_on.png'; document.myform.zoom.value = -2;">
<img src="../../../images/button_pan_on.png" name="panButton" alt="Pan"
  onClick="javascript: resetButtons(); document.panButton.src = '../../../images/button_pan_on.png'; document.myform.zoom.value = 1;">
<img src="../../../images/button_zoomout_off.png" name="zoomoutButton" alt="Zoom Out"
  onClick="javascript: resetButtons(); document.zoomoutButton.src = '../../../images/button_zoomout_on.png'; document.myform.zoom.value = 2;">
<img src="../../../images/button_zoomfull_off.png" name="zoomfullButton" alt="Zoom Full"
  onClick="document.myform.zoom.value = 0; document.myform.submit(); ">
<a href="<?php echo $mapurl; ?>&download=yes"><img src="../../../images/button_save_off.png" name="saveButton" alt="Save Image" onClick="javascript: resetButtons();" border="0"></a>

<br /><b>- Time Mode:</b> <span style="color: #0000fa"><?php if ($mode == "realtime"){ echo "Live"; } else { echo "Archived"; } ?></span>
<br /><input type="submit" value="Live/Archive Options" onclick="javascript: setLayerDisplay('timewindow', 'block'); return false;">

<div id="timewindow" style="z-index: 3; position: absolute; top: 150px; left: 250px; width: 400px; display: none; background-color: #eee; padding: 5px; border: 1px dashed #000;">
<strong>Time Options</strong> (<a href="javascript: setLayerDisplay('timewindow', 'none');">Close</a>)
<br /><i>This application can run in realtime mode or archive mode.</i>
<br /><input type="radio" name="mode" value="realtime" <?php if ($mode == "realtime") echo "checked='checked'"; ?>><b>Realtime mode</b>
<div style="margin-left: 20px;">Radar & SchoolNet8 information is displayed for the most recent time possible.
<br /><b>Auto Refresh Interval:</b><select name="refresh">
      <?php
         sel($refresh, "0", "Never");
         sel($refresh, "60", "Every Minute");
         sel($refresh, "300", "Every 5 minutes");
         sel($refresh, "1200", "Every 20 minutes");
       ?>
      </select>
</div>
<hr>
<input type="radio" name="mode" value="archive" <?php if ($mode == "archive") echo "checked='checked'"; ?>><b>Archive mode</b>
<div style="margin-left: 20px;">Radar & SchoolNet8 information is displayed for a time of your choice. The archive begins on 2 March 2004.

<table>
<tr><th>Month:</th><th>Day:</th><th>Year:</th><th>Hour:</th><th>Minute:</th></tr>
<tr>
<td><?php echo monthSelect($month); ?></td>
<td><?php echo daySelect($day); ?></td>
<td><?php echo yearSelect(2004, $year, "year"); ?></td>
<td><?php echo localHourSelect($hour, "hour"); ?></td>
<td><?php echo minuteSelect($minute, "minute"); ?></td>
</tr>
</table></div>
<br /><input type="submit" value="Make Changes">
</div>
<!-- End of That box -->

<script LANGUAGE="JavaScript1.2" type="text/javascript">
//<!--
function setLayerDisplay( layerName, d ) {
  if ( document.getElementById ) {
 
    var w = document.getElementById(layerName);
    w.style.display = d;
  }
}
-->
</script>

<br /><b>- Select Plot Variable:</b>
<br /><select name="var">
       <?php
          sel($var, "tmpf", "Temperature");
          sel($var, "dwpf", "Dew Point");
          sel($var, "splot", "Station Plot");
          sel($var, "feel", "Feels Like");
          sel($var, "alti", "Pressure");
          sel($var, "sped", "Wind Speed [MPH]");
          sel($var, "barb", "Wind Barb");
          sel($var, "gbarb", "Wind Gust Barb");
          sel($var, "sknt", "Wind Speed [knts]");
          sel($var, "max_sped", "Wind Gust [MPH]");
          sel($var, "max_sknt", "Wind Gust [knts]");
          sel($var, "pday", "Today Rainfall");
          sel($var, "pmonth", "Month Rainfall");
          sel($var, "tmpf_max", "Today Hi Temp");
          sel($var, "tmpf_min", "Today Low Temp");
        ?>
     </select>


      <?php
        $stStr = "";
        $strStr = "";
        foreach ($Scities as $key => $value) {
          if (in_array($key, array_keys($myStations) )) {
            $stStr .= "<option value=". $key ."> ". $Scities[$key]['short'] ;
          } else {
            $strStr .= "<option value=". $key ."> ". $Scities[$key]['short'] ;
          }
        } 
      ?>
<br /><b>- Select Sites:</b>
<br />Remove Sites:
<br /><select name="str[]" size="2" multiple>
        <?php echo $stStr; ?>
     </select>

<br />Add Sites:
<br /><select name="st[]" size="3" multiple>
        <?php echo $strStr; ?>
     </select>

<br /><b>- Select RADAR:</b>
<br /><select name="radar">
  <option value="NONE" <?php if ($radar == "NONE") echo "SELECTED"; ?>>Hide RADAR</option>
  <option value="KCCI" <?php if ($radar == "KCCI") echo "SELECTED"; ?>>Live Super Doppler</option>
  <option value="DMX" <?php if ($radar == "DMX") echo "SELECTED"; ?>>NWS - Des Moines, IA</option>
  <option value="FSD" <?php if ($radar == "FSD") echo "SELECTED"; ?>>NWS - Sioux Falls, SD</option>
  <option value="OAX" <?php if ($radar == "OAX") echo "SELECTED"; ?>>NWS - Omaha, NE</option>
  <option value="EAX" <?php if ($radar == "EAX") echo "SELECTED"; ?>>NWS - Pleasant Hill, MO</option>
  <option value="DVN" <?php if ($radar == "DVN") echo "SELECTED"; ?>>NWS - Davenport, IA</option>
  <option value="ARX" <?php if ($radar == "ARX") echo "SELECTED"; ?>>NWS - LaCrosse, WI</option>
  <option value="MPX" <?php if ($radar == "MPX") echo "SELECTED"; ?>>NWS - Minneapolis, MN</option>
</select>
<br /><input type="checkbox" value="show" name="sitelabel" <?php if ($showSiteLabel) echo "checked=\"checked\""; ?>>Show Site Labels
<br /><input type="checkbox" value="nws_warnings" name="layers[]" <?php if (in_array("nws_warnings", $layers) ) echo "checked=\"checked\""; ?>>NWS Warnings
<br /><b>- Realtime-Only Layers:</b>
<br /><input type="checkbox" value="show" name="roadcond" <?php if ($showRoadCond) echo "checked=\"checked\""; ?>>Road Conditions
<br /><input type="checkbox" value="goes_conus_vis4km" name="layers[]" <?php if (in_array("goes_conus_vis4km", $layers) && $mode != "archive") echo "checked=\"checked\""; ?>>Visible Satellite
<br /><input type="checkbox" value="goes_conus_ir4km" name="layers[]" <?php if (in_array("goes_conus_ir4km", $layers) && $mode != "archive") echo "checked=\"checked\""; ?>>Infrared Satellite

<br /><input type="submit" value="Generate Map">

<br /><b>- Special Options:</b>
<br /><input type="submit" value="Launch Geo-Coder" onclick="javascript: setLayerDisplay('geowindow', 'block'); return false;">
<br /><input type="submit" value="I need help!" onclick="javascript: setLayerDisplay('helpwindow', 'block'); return false;">

<!-- Geocoding Popup window -->
<div id="geowindow" style="z-index: 3; position: absolute; top: 150; left: 300; width: 400px; display: none; background-color: #eee; padding: 5px; border: 1px dashed #000;">
<strong>Geocoder</strong> (<a href="javascript: setLayerDisplay('geowindow', 'none');">Close</a>)

<p>Enter Street: <input type="text" name="street" size="15">ex) <i>888 9th St</i>
<br />Enter City: <input type="text" name="city" size="15">ex) <i> Des Moines</i>
<br /><input type="submit" value="Go To Location">

<p>The Geocoder takes a street address with a city and tries to find the coordinates of this location.  With the coordinates, the map is then zoomed in closely to this location.  Geocoding is not 100% accurate and it can't find all locations.  Give it a shot with your location and see how it does!</p>
</div>

   </td>
   <td align="center" bgcolor="#000080"><a href="<?php echo $baseurl; ?>tool/clicktru.php?station=CIPCO" target="_new"><img src="<?php echo $baseurl; ?>pics/banner.gif" border="0"></a></td>
	</tr>
	<tr>
<td valign="top">
<div id="imgmain" style="z-index: 1;"><img name="mymap" src="<?php echo $mapurl; ?>" width="640" height="480"></div>
<div id="imgmain2" style="margin-top: -480px; z-index: 2;"><input type="image" name="img" src="<?php echo $baseurl; ?>/images/trans640x480.png" width="640" height="480"></div>
<div class="ftext">Key for Weather Warnings: <b style="border: #f00 2px solid; background: #8c905a;">Tornado</b> <b style="border: #ff0 solid 2px; background: #8c905a;">Severe Thunderstorm</b> <b style="border: #0f0 solid 2px; background: #8c905a;">Flash Flood</b></div>
<?php if (strlen($ERROR) > 0) { ?>
<div style="background: #ff0; color: #f00; border: #000 dashed 1px;">
<?php echo $ERROR; ?>
</div>
<?php } ?>
</td>
</tr>

</table>

<!-- Help Window -->
<div id="helpwindow" style="position: absolute; top: 100px; left: 200px; width: 500px; display: none; background-color: #eee; padding: 5px; border: 1px dashed #000; text-align: left;">
<strong>Help Window</strong> (<a href="javascript: setLayerDisplay('helpwindow', 'none');">Close</a>)
<br /><b>Map Controls</b>
<br /><img src="../../../images/button_zoomin_off.png">Click this icon and then the map to zoom the map in by a factor of 2.
<br /><img src="../../../images/button_pan_off.png">Click this icon and then the map to recenter the map where you clicked.
<br /><img src="../../../images/button_zoomout_off.png">Click this icon and then the map to zoom the map out by a factor of 2.
<br /><img src="../../../images/button_zoomfull_off.png">Click to return to default scale.
<br /><img src="../../../images/button_save_off.png">Click to save the image to your desktop.

<br /><b>Time Mode:</b> allows you to set this application to use either near real-time data or archived data dating back to 1 March 2004.  

<br /><b>Select Plot Variable:</b> allows you to control the observed variable that is plotted from the SchoolNet8 network.

<br /><b>Select Sites:</b> allows you to customize which SchoolNet8 sites are plotted on the map.

<br /><b>Select RADAR:</b> gives you the choice of which RADAR you would like to see shown on the map.
</div>

</body>
</html>
