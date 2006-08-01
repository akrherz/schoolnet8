<?php
include("../../../../config/settings.inc.php");

//_________________ vars
$station = isset($_GET['station']) ? $_GET['station'] : "SKCI4";
$var = isset($_GET['var']) ? $_GET['var'] : "tmpf";
$width = isset($_GET['width']) ? $_GET["width"]: 320;
$height = isset($_GET['height']) ? $_GET["height"]: 240;

//__________________ Includes ----------------
include("$nwnpath/include/locs.inc.php");
include("$nwnpath/include/currentdb.inc.php");
include("$nwnpath/include/radar.php");
dl($mapscript);

$obs = new currentdb();
$obs->timecheck();


$rnd = Array("tmpf" => 0,
  "dwpf" => 0,
  "sknt" => 0,
  "max_sknt" => 0,
  "feel" => 0,
  "pmonth" => 2,
  "pday" => 2);


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

$lats = Array();
$lons = Array();

foreach($Scities as $key => $value){
  $lats[$key] = $Scities[$key]["lat"];
  $lons[$key] = $Scities[$key]["lon"];
}


//_________________ Mapserver stuff time!!!
$map = ms_newMapObj($mapfile);
$map->setProjection($proj);
$map->set("width", $width);
$map->set("height", $height);

$lat = $Scities[$station]['lat'];
$lon = $Scities[$station]['lon'];
$projInObj = ms_newprojectionobj("init=epsg:4326");
$projOutObj = ms_newprojectionobj($proj);

$point = ms_newpointobj();
$point->setXY($lon, $lat);
$point->project($projInObj, $projOutObj);
// 100x75 km  (4x3)
// 80x60 km  (4x3) # 31 Jul 2003, Roads are just too big!


if ($height == 480 && $width == 640){
 $map->setextent($point->x - 80000, $point->y - 60000, $point->x + 80000, $point->y + 60000);
} else {
 $map->setextent($point->x - 40000, $point->y - 30000, $point->x + 40000, $point->y + 30000);
}
$counties = $map->getlayerbyname("counties");
$counties->set("status", MS_ON);

$tvradar = $map->getlayerbyname("KCCI");
$tvradar->set("status", MS_ON);

$dmx = $map->getlayerbyname("wsr88d");
$dmx->set("status", MS_ON);

$barbs = $map->getlayerbyname("barbs");
$barbs->set("status", MS_ON);
$bclass = $barbs->getClass(0);

$temps = $map->getlayerbyname("temps");
$temps->set("status", MS_ON);
$temps->setprojection("proj=latlong");

$snet = $map->getlayerbyname("snet");
$snet->set("status", MS_ON);
$sclass = $snet->getClass(0);

$ponly = $map->getlayerbyname("pointonly");
$ponly->set("status", MS_ON);

$roads = $map->getlayerbyname("roads");
$roads->set("status", MS_ON);

$rlbl = $map->getlayerbyname("roads_label");
$rlbl->set("status", MS_ON);

$interstates = $map->getlayerbyname("interstates");
$interstates->set("status", MS_ON);

$ilbl = $map->getlayerbyname("interstates_label");
$ilbl->set("status", MS_ON);

if ($width == 640 && $height == 480){
  $bar = $map->getlayerbyname("bar640");
} else {
  $bar = $map->getlayerbyname("bar320");
}
$bar->set("status", MS_ON);


$img = $map->prepareImage();
$roads->draw($img);
$rlbl->draw($img);
$interstates->draw($img);
$ilbl->draw($img);
$counties->draw($img);
$map->drawLabelCache($img);

$tvgood = kccidopplerrecent();
if ($tvgood) {
  $tvradar->draw($img);
} else {
  $dmx->draw($img);
  //missingkcci($map, $img);
}

foreach ($obs->db as $key => $bzz)
{
  if (! $bzz['iscurrent']) continue;

  if ($var == "barb")
  {
    $pt = ms_newPointObj();
    $pt->setXY($lons[$key], $lats[$key], 0);
    $rotate =  0 - intval($bzz["drct"]);
    $bclass->label->set("angle", doubleval($rotate));
    $pt->draw($map, $barbs, $img, 0, skntChar($bzz["sknt"]) );
    $pt->free();

    $pt = ms_newPointObj();
    $pt->setXY($lons[$key], $lats[$key], 0);
    $pt->draw($map, $snet, $img, 0, round($bzz['sknt'], $rnd['sknt']) );
    $pt->free();
  } else if ($var == "gbarb") 
  {
    $pt = ms_newPointObj();
    $pt->setXY($lons[$key], $lats[$key], 0);
    $pt->draw($map, $snet, $img, 0, round($bzz['max_sknt'], $rnd['max_sknt']) );
    $pt->free();

     $pt = ms_newPointObj();
     $pt->setXY($lons[$key], $lats[$key], 0);
     $rotate =  0 - intval($bzz["drct_max"]);
     $bclass->label->set("angle", doubleval($rotate));
     $pt->draw($map, $barbs, $img, 0, skntChar($bzz["max_sknt"]) );
     $pt->free();

    } else if ($var == "alti") {
     $pt = ms_newPointObj();
     $pt->setXY($lons[$key], $lats[$key], 0);
     $pt->draw($map, $snet, $img, 0, $bzz[$var] );
     $pt->free();
    } else {
     $pt = ms_newPointObj();
     $pt->setXY($lons[$key], $lats[$key], 0);
     $pt->draw($map, $snet, $img, 0, round($bzz[$var], $rnd[$var]) );
     $pt->free();
    }
  
} // End of while

$pt = ms_newPointObj();
$pt->setXY($point->x, $point->y, 0);
$pt->draw($map, $ponly, $img, 0, " ");
$pt->free();

$bar->draw($img);

if ($tvgood)
{
 doppler8logo($map, $img, 235, 20, 25);
}
mktitle($map, $img, 0, $height - 10, " ". $varDef[$var] ." @ ". date("h:i A") ."                                                    ");
mkstationtitle($map, $img,  5, 10, $Scities[$station]["city"] );

header("Content-type: image/png");
$img->saveImage('');
?>
