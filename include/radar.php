<?php
// Library for GIS + radar stuff!

function doppler8logo($map, $imgObj, $x0, $y0, $size) {
  $layer = $map->getLayerByName("doppler8");
  $lclass = $layer->getClass(0);
  $lstyle = $lclass->getStyle(0);
  $lstyle->set("size", $size);
  $point = ms_newpointobj();
  $point->setXY($x0, $y0);

  $point->draw($map, $layer, $imgObj, "logo", "");
}

function mktitle($map, $imgObj, $x0, $y0, $titlet) {
  $layer = $map->getLayerByName("credits");

  // point feature with text for location
  $point = ms_newpointobj();
  $point->setXY($x0, $y0);

  $point->draw($map, $layer, $imgObj, "credits", $titlet);
}

function mkstationtitle($map, $imgObj, $x0, $y0, $titlet) {
  $layer = $map->getLayerByName("credits");

  $c = $layer->getClass(0);
  $c->label->set("size", "14");
  // point feature with text for location
  $point = ms_newpointobj();
  $point->setXY($x0, $y0);

  $point->draw($map, $layer, $imgObj, "credits",
    $titlet);
}


function skntChar($sknt){
  if ($sknt < 2)  return chr(0);
  if ($sknt < 5)  return chr(227);
  if ($sknt < 10)  return chr(228);
  if ($sknt < 15)  return chr(229);
  if ($sknt < 20)  return chr(230);
  if ($sknt < 25)  return chr(231);
  if ($sknt < 30)  return chr(232);
  if ($sknt < 35)  return chr(233);
  if ($sknt < 40)  return chr(234);
  if ($sknt < 45)  return chr(235);
  if ($sknt < 50)  return chr(236);
  if ($sknt < 55)  return chr(237);
  if ($sknt < 60)  return chr(238);
}

function kccidopplerrecent() {
#  return FALSE;
  putenv("TZ=GMT");
  $fc =  file('/home/ldm/data/gis/images/26915/KCCI/KCCI_N0R_tm_0.txt');
  $ts = strtotime($fc[0]);
  $d = time() - $ts;
  putenv("TZ=CST6CDT");
  if ( $d > 1200 || $d < -1200 )
    return FALSE;
  return TRUE;
}

function missingkcci($map, $img){
   
 $layer = $map->getLayerByName("credits");
 // point feature with text for location
 $point = ms_newpointobj();
 $point->setXY(50, 50);

 $point->draw($map, $layer, $img, "credits",
    "  RADAR UNAVAILABLE  ");
 $layer->draw($img);
}

$proj = "init=epsg:26915";


?>
