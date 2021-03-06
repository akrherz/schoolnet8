<?php
include('../config/settings.inc.php');
include('../include/radar.php');

$validRADAR = kccidopplerrecent();



function draw($bounds, $filename)
{
global $mapfile;
global $validRADAR;

$map = ms_newMapObj($mapfile);
$map->selectOutputFormat("png24");
$map->set("width", 640);
$map->set("height", 480);
$map->setextent($bounds[0], $bounds[1], $bounds[2], $bounds[3]);

$iowa_map_cities = $map->getlayerbyname("iowa_map_cities");
$iowa_map_cities->set("status", MS_ON);

$counties = $map->getlayerbyname("mwcounties");
$counties->set("status", MS_ON);

$states = $map->getlayerbyname("states");
$states->set("status", MS_ON);

$tvradar = $map->getlayerbyname("KCCI");
$tvradar->set("status", MS_ON);
  
$warnings_c = $map->getlayerbyname("warnings_c");
$warnings_c->set("status", MS_ON);

$dmx = $map->getlayerbyname("wsr88d");
$dmx->set("status", MS_ON);

  $interstates = $map->getlayerbyname("interstates");
  $interstates->set("status", 1);

  $roads = $map->getlayerbyname("roads");
  $roads->set("status", 1);

  //$ilbl = $map->getlayerbyname("interstates_label");
  //$ilbl->set("status", 1);

  //$rlbl = $map->getlayerbyname("roads_label");
  //$rlbl->set("status", 1);
 


$subbar = $map->getlayerbyname("subtitlebar");
$subbar->set("status", 1);


$img = $map->prepareImage();
$interstates->draw($img);

 $roads->draw($img);
 $interstates->draw($img);
 //$rlbl->draw($img);
 //$ilbl->draw($img);


if ($validRADAR)
{
  $tvradar->draw($img);
} else{
  $dmx->draw($img);
}
$counties->draw($img);
$warnings_c->draw($img);
$states->draw($img);
$iowa_map_cities->draw($img);

$subbar->draw($img);
 putenv("TZ=CST6CDT");
mktitle($map, $img, 335, 70, date("h:i A d M Y") );
doppler8logo($map, $img, 550, 68, 53);

$map->embedlegend($img);
$map->drawLabelCache($img);
$img->saveImage($filename);

}

draw( Array( 200000, 4400000, 710000, 4900000), "iowa.png");
draw( Array( 375000, 4550000, 500000, 4675000), "metro.png");
draw( Array( 300000, 4475000, 450000, 4625000), "SW.png");
draw( Array( 475000, 4450000, 635000, 4675000), "SE.png");
draw( Array( 475000, 4600000, 685000, 4825000), "NE.png");
draw( Array( 275000, 4600000, 485000, 4825000), "NW.png");

$tstamp = gmdate("YmdHi");
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/iowa_ bogus png" iowa.png`;
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/metro_ bogus png" metro.png`;
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/SW_ bogus png" SW.png`;
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/SE_ bogus png" SE.png`;
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/NE_ bogus png" NE.png`;
`/home/ldm/bin/pqinsert -i -p "plot r $tstamp kccirad/NW_ bogus png" NW.png`;

?>
