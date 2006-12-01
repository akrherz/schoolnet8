<?php
include("../../config/settings.inc.php"); 
$fa = file('/proc/loadavg');
$fa = explode(" ", $fa[0] );
if ($fa[0] > 3.0)
{
  header("Location: ". $backupbaseurl ."/GIS/map.php?". $_ENV["QUERY_STRING"]  );
  exit(0);
}

include("$nwnpath/include/forms.inc.php");
dl($mapscript);

$ERROR = "";
$layers = isset($_GET["layers"]) ? $_GET["layers"] : Array();
$radar = isset($_GET["radar"]) ? strtoupper($_GET["radar"]) : "KCCI";
$mode = isset($_GET["mode"]) ? $_GET["mode"] : "realtime";
$year = isset($_GET["year"]) ? $_GET["year"] : date("Y");
$month = isset($_GET["month"]) ? $_GET["month"] : date("m");
$day = isset($_GET["day"]) ? $_GET["day"] : date("d");
$hour = isset($_GET["hour"]) ? $_GET["hour"] : date("H");
$minute = isset($_GET["minute"]) ? $_GET["minute"] : date("i");
$showRoadCond = isset($_GET["roadcond"]);
$showSiteLabel = isset($_GET["sitelabel"]);
$imgwidth = isset($_GET['width']) ? $_GET['width'] : 640;
$imgheight = isset($_GET['height']) ? $_GET['height'] : 480;

$showRADAR = true;
if ($radar == "NONE") $showRADAR = false;

if ($mode == "archive")
{
  $radar = "KCCI";
  $showRoadCond = false;
  $archivestart = mktime(12,0,0,3,1,2004); // 1 March 2004
  $archivets = mktime($_GET["hour"], $_GET["minute"], 0, $_GET["month"], $_GET["day"], $_GET["year"]);
  if ($archivestart > $archivets || time() < $archivets ) 
  { 
    $mode = "realtime";
  }
}

/**
 * Function to find a radar image valid near this time 
 */
function findradarts($t)
{
  $format = "/mesonet/ARCHIVE/data/%Y/%m/%d/GIS/kcci/KCCI_%Y%m%d%H%M.png";
  $fp = "/mesonet/ARCHIVE/data/". gmdate("Y/m/d", $t) ."/GIS/kcci/KCCI_"
     . gmdate("YmdHi", $t) . ".png";
  if (is_file($fp)) { 
	  copy($fp, "/tmp/KCCI_". gmdate("YmdHi", $t) .".png");
	  copy("/home/ldm/data/kcci/max.wld", "/tmp/KCCI_". gmdate("YmdHi", $t) .".wld" );
      return $t; }

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

 /**
  * Something simple to enable click interface on a PHP mapcript
  * application
  */
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

$zoom     = isset($_GET['zoom']) ? $_GET['zoom'] : 0;
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

$st = isset($_GET['st']) ? $_GET['st'] : Array();
$str = isset($_GET['str']) ? $_GET['str'] : Array();
$var = isset($_GET['var']) ? $_GET['var'] : 'tmpf';

include("$nwnpath/include/currentdb.inc.php");
include("$nwnpath/include/locs.inc.php");
include("$nwnpath/include/radar.php");

$obs = new currentdb();
$obs->timecheck();

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
$titlets = date("h:i A d M Y");
if (isset($radarts))
{
  $obs->db = Array();
  $a = localtime($radarts, true);
  $ts = mktime($a["tm_hour"], $a["tm_min"], 0, $a["tm_mon"] + 1, $a["tm_mday"], $a["tm_year"] + 1900);

  if ($archivets > ( time() - 100000 ) ) // Use IEMAccess
  {
    $c = pg_connect($iemaccess);
    $sql = strftime("SELECT * from current_log WHERE valid = '%Y-%m-%d %H:%M' and network = 'KCCI' ", $ts);
  } else {  // Use normal archive
    $c = pg_connect($iemdbhost);
    $sql = strftime("SELECT * from t%Y_%m WHERE valid = '%Y-%m-%d %H:%M'", $ts);
  }

  $sqldate = strftime("%Y-%m-%d %H:%M", $ts);
  $titlets = strftime("%I:%M %p %d %b %Y", $ts);
  $rs = pg_query($c, $sql);
  if (! $rs) $ERROR .= "Could not connect to SNET archive.<br />";
  for( $i=0; $row = @pg_fetch_assoc($rs,$i); $i++)
  {
     $obs->db[ $row["station"] ] = $row;
  }
  pg_close($c);
}


/* Do we have a list of stations to plot? 
  $st are stations in our plotting list
  $str are stations to remove from our plotting list
  $myStations will hold eventual list of sites we want to plot
*/
$st = isset($_GET['st']) ? $_GET['st'] : Array();
$str = isset($_GET['str']) ? $_GET['str'] : Array();
$myStations = Array();

$formStr = "";
$cgiStr = "";

/* Rip thru our stations list and remove any vagrants */
foreach ($st as $key => $value) {
  if (strlen($value) > 0 && $value != "ahack") {
    if (! in_array($value, $str) ){
      $myStations[$value] = "hi";
    }
  }
}

/* Generate form stuff */
reset($myStations);
foreach ($myStations as $key => $value) {
  if (strlen($value) > 0 && $value != "ahack") {
    $formStr .= "<input type='hidden' name='st[]' value='".$key."'>\n";
    $cgiStr .= "st[]=". $key ."&";
  }
}


if ($showRoadCond)
{
  $cgiStr .= "roadcond=show&";
}
if ($showSiteLabel)
{
  $cgiStr .= "sitelabel=show&";
}

if (strlen($var) == 0){
  $var = "tmpf";
}

$varDef = Array("tmpf" => "Current Temperatures",
  "dwpf" => "Current Dew Points",
  "alti" => "Pressure",
  "splot" => "Current Station Plot",
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

$rnd = Array("tmpf" => 0,
  "dwpf" => 0,
  "sknt" => 0,
  "max_sknt" => 0,
  "feel" => 0,
  "pmonth" => 2,
  "pday" => 2);

$rlabel = Array("KCCI" => "KCCI-TV Live Super Doppler",
 "DMX" => "NWS Des Moines, IA NEXRAD",
 "DVN" => "NWS Davenport, IA NEXRAD",
 "ARX" => "NWS LaCrosse, WI NEXRAD",
 "MPX" => "NWS Minneapolis, MN NEXRAD",
 "FSD" => "NWS Sioux Falls, SD NEXRAD",
 "OAX" => "NWS Omaha, NE NEXRAD",
 "EAX" => "NWS Pleasant Hill, MO NEXRAD");

$map = ms_newMapObj($mapfile);
$map->setProjection($proj);
$map->set("height", 480);
$map->set("width", 640);

$arExtents = explode(",", $extents);
$map->setextent($arExtents[0], $arExtents[1], $arExtents[2], $arExtents[3]);

$goes_conus_vis4km = $map->getlayerbyname("goes_conus_vis4km");
$goes_conus_vis4km->set("status", (in_array("goes_conus_vis4km",$layers) && $mode != "archive") );

$goes_conus_ir4km = $map->getlayerbyname("goes_conus_ir4km");
$goes_conus_ir4km->set("status", (in_array("goes_conus_ir4km",$layers) && $mode != "archive") );


$counties = $map->getlayerbyname("counties");
$counties->set("status", MS_ON);

$snet = $map->getlayerbyname("snet");
$snet->set("status", MS_ON);
$sclass = $snet->getClass(0);

$barbs = $map->getlayerbyname("barbs");
$barbs->set("status", MS_ON);
$bclass = $barbs->getClass(0);


$cities = $map->getlayerbyname("cities");
$cities->set("status", 1);

$states = $map->getlayerbyname("states");
$states->set("status", 1);


if ($showRoadCond)
{
  $roadcond = $map->getlayerbyname("roadcond");
  $roadcond->set("status", 1);

  $roadcond_key = $map->getlayerbyname("roadcond_key");
  $roadcond_key->set("status", 1);

  $roadcond_label = $map->getlayerbyname("roadcond_label");
  $roadcond_label->set("status", 1);
}
else 
{
  $interstates = $map->getlayerbyname("interstates");
  $interstates->set("status", 1);

  $roads = $map->getlayerbyname("roads");
  $roads->set("status", 1);

  $ilbl = $map->getlayerbyname("interstates_label");
  $ilbl->set("status", 1);

  $rlbl = $map->getlayerbyname("roads_label");
  $rlbl->set("status", 1);
}



$tvgood = kccidopplerrecent();
if ($radar == "KCCI" && ($tvgood || $mode == "archive") )
{
  $lradar = $map->getlayerbyname("KCCI");
} else 
{
  if ($radar == "KCCI") $radar = "DMX";
  $lradar = $map->getlayerbyname("wsr88d");
  $lradar->set("data", "/home/ldm/data/gis/images/4326/$radar/n0r_0.tif");
}
$lradar->set("status", 1);
if (isset($radarts))
{
  //echo "SETTING data to /tmp/KCCI_". gmdate("YmdHi", $radarts) .".png";
  $lradar->set("data", "/tmp/KCCI_". gmdate("YmdHi", $radarts) .".png");
}

$bar = $map->getlayerbyname("barlsd");
$bar->set("status", 1);
$subbar = $map->getlayerbyname("subtitlebar");
$subbar->set("status", 1);

if (isset($radarts))
{
  $wc = $map->getlayerbyname("postgis_warnings_c");
  $sql = "geom from (select phenomena, geom, oid from warnings_$year WHERE significance = 'W' and gtype = 'C' and expire > '$sqldate' and issue < '$sqldate' ORDER by phenomena ASC) as foo using unique oid using SRID=4326";
  //echo $sql;
  $wc->set("data", $sql);
} else {
  $wc = $map->getlayerbyname("warnings_c");
}
$wc->set("status", in_array("nws_warnings",$layers) );

$img = $map->prepareImage();
$goes_conus_vis4km->draw($img);
$goes_conus_ir4km->draw($img);
$cities->draw($img);

if (! $showRoadCond)
{
 $roads->draw($img);
 $interstates->draw($img);
 $rlbl->draw($img);
 $ilbl->draw($img);
}
$map->drawLabelCache($img);

if ($showRADAR &&  $tvgood && $mode == "realtime")
{
  $lradar->draw($img);
} 
else if ($showRADAR && $mode == "archive" && !isset($MISSINGRADAR) )
{
  $lradar->draw($img);
} 
$lradar->draw($img);
if ($showRoadCond)
{
  $roadcond->draw($img);
  $roadcond_label->draw($img);
  $map->drawLabelCache($img);
}

$counties->draw($img);
$states->draw($img);
$wc->draw($img);

foreach($myStations as $key => $value){
  if ($key == "S03I4") continue;

  if (! isset($obs->db[$key])) continue;
  $bzz = $obs->db[$key];
  if (sizeof($bzz) == 0) continue;

  $lat = $Scities[$key]["lat"];
  $lon = $Scities[$key]["lon"];

  if ($showSiteLabel)
  {
   $pt = ms_newPointObj();
   $pt->setXY($lon, $lat, 0);
   $pt->draw($map, $snet, $img, 1, $Scities[$key]["short"] );
   $pt->free();
  }

   if (! isset($bzz['iscurrent']) && ! isset($radarts) ) continue;
    if ($var == "barb"){
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $rotate =  0 - intval($bzz["drct"]);
     $bclass->label->set("angle", doubleval($rotate));
     $pt->draw($map, $barbs, $img, 0, skntChar($bzz["sknt"]) );
     $pt->free();

     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $pt->draw($map, $snet, $img, 0, round($bzz['sknt'], $rnd['sknt']) );
     $pt->free();
    } else if ($var == "gbarb") {
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $pt->draw($map, $snet, $img, 0, round($bzz['max_sknt'], $rnd['max_sknt']) );
     $pt->free();

     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $rotate =  0 - intval($bzz["drct_max"]);
     $bclass->label->set("angle", doubleval($rotate));
     $pt->draw($map, $barbs, $img, 0, skntChar($bzz["max_sknt"]) );
     $pt->free();
    } else if ($var == "alti") {
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $pt->draw($map, $snet, $img, 0, $bzz[$var] );
     $pt->free();
    } else if ($var == "splot") {
     if ($bzz["tmpf"] == -99) continue;
     /* Draw the barb */
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $rotate =  0 - intval($bzz["drct"]);
     $bclass->label->set("angle", doubleval($rotate));
     $pt->draw($map, $barbs, $img, 0, skntChar($bzz["sknt"]) );
     $pt->free();
     /* Draw temperature */
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $sclass->label->set("offsety", 0);
     $sclass->label->set("offsetx", -10);
     $sclass->label->color->setRGB(250,0,0);
     $sclass->label->outlinecolor->setRGB(255,255,255);
     $sclass->label->shadowcolor->setRGB(255,255,255);
     $pt->draw($map, $snet, $img, 0, round($bzz['tmpf'], $rnd['tmpf']) );
     $pt->free();
     /* Draw dew point */
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $sclass->label->set("offsety", -20);
     $sclass->label->set("offsetx", -10);
     $sclass->label->color->setRGB(0,170,0);
     $sclass->label->outlinecolor->setRGB(255,255,255);
     $sclass->label->shadowcolor->setRGB(255,255,255);
     $pt->draw($map, $snet, $img, 0, round($bzz['dwpf'], $rnd['dwpf']) );
     $pt->free();

    } else {
     if ($bzz[$var] == -99) continue;
     $pt = ms_newPointObj();
     $pt->setXY($lon, $lat, 0);
     $pt->draw($map, $snet, $img, 0, round($bzz[$var], $rnd[$var]) );
     $pt->free();
    }
}
//  $ts = strftime("%I %p");


//$map->drawLabelCache($img);
$bar->draw($img);
mktitle($map, $img, 330, 48, $varDef[$var] ." @ ". $titlets );
if (($tvgood || $mode == "archive") && $radar == "KCCI")
{
  doppler8logo($map, $img, 475, 68, 45);
} else 
{
  $subbar->draw($img);
  if ($radar != "NONE") mktitle($map, $img, 350, 70, $rlabel[$radar] );
}

if ($showRoadCond)
{
  $roadcond_key->draw($img);
}

if (isset($_GET["download"]))
{
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=schoolnet.png");
  $img->saveImage('');
} else 
{
  header("Content-type: image/png");
  $img->saveImage('');
}
?>
