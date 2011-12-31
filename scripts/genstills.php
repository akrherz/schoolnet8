<?php
include('../config/settings.inc.php');

@mkdir("/tmp/radimages");
set_time_limit(58);

include('../include/locs.inc.php');
$locs = new Locations();
include('../include/sponsors.inc.php');
include('../include/radar.php');
include('../include/currentdb.inc.php');

$obs = new currentdb();
$obs->timecheck();

$validRADAR = kccidopplerrecent();



/** Time to loop over each Site! */
foreach($locs->table as $key => $value)
{
  $sid = $key;
  $lat = $value['lat'];
  $lon = $value['lon'];

  $map = ms_newMapObj($mapfile);
  $map->selectOutputFormat("png24");
  $map->setsize(320,240);

  $projInObj = ms_newprojectionobj("init=epsg:4326");
  $projOutObj = ms_newprojectionobj($proj);

  $counties = $map->getlayerbyname("counties");
  $counties->set("status", MS_ON);

  $warnings_c = $map->getlayerbyname("warnings_c");
  $warnings_c->set("status", MS_ON);
                                                                                
  $tvradar = $map->getlayerbyname("KCCI");
  $tvradar->set("status", MS_ON);

  $dmx = $map->getlayerbyname("wsr88d");
  $dmx->set("status", MS_ON);

//  $roads = $map->getlayerbyname("roads");
//  $roads->set("status", MS_ON);
                                                                                
//  $rlbl = $map->getlayerbyname("roads_label");
//  $rlbl->set("status", MS_ON);
                                                                                
  $interstates = $map->getlayerbyname("interstates");
  $interstates->set("status", MS_ON);
                                                                                
  $ilbl = $map->getlayerbyname("interstates_label");
  $ilbl->set("status", MS_ON);

  $bar = $map->getlayerbyname("bar320");
  $bar->set("status", MS_ON);

  $ponly = $map->getlayerbyname("pointonly");
  $ponly->set("status", MS_ON);

  $snet = $map->getlayerbyname("snet");
  $snet->set("status", MS_ON);
  $sclass = $snet->getClass(0);

  $barbs = $map->getlayerbyname("barbs");
  $barbs->set("status", MS_ON);
  $bclass = $barbs->getClass(0);

  $point = ms_newpointobj();
  $point->setXY($lon, $lat);
  $point->project($projInObj, $projOutObj);

  // 320x240 4x3
  $map->setextent($point->x - 60000, $point->y - 45000, 
                  $point->x + 60000, $point->y + 45000);

  $img = $map->prepareImage();
//  $roads->draw($img);
//  $rlbl->draw($img);
  $interstates->draw($img);
  $ilbl->draw($img);
  $map->drawLabelCache($img);

  if ($validRADAR)
  {
    $tvradar->draw($img);
  } else 
  {
    $dmx->draw($img);
    //missingkcci($map,$img);
  }
  $counties->draw($img);
	$warnings_c->draw($img);

  foreach($obs->db as $key => $ob)
  {
    if (! $ob['iscurrent']) continue;
    $lon = $locs->table[$key]['lon'];
    $lat = $locs->table[$key]['lat'];
    $pt = ms_newPointObj();
    $pt->setXY($lon, $lat, 0);
    $rotate =  0 - intval($ob["drct"]);
    $bclass->label->set("angle", doubleval($rotate));
    $pt->draw($map, $barbs, $img, 0, skntChar($ob["sknt"]) );
    $pt->free();

    $pt = ms_newPointObj();
    $pt->setXY($lon, $lat, 0);
    $pt->draw($map, $snet, $img, 0, $ob['tmpf'] );
    $pt->free();
  }

  $pt = ms_newPointObj();
  $pt->setXY($point->x, $point->y, 0);
  $pt->draw($map, $ponly, $img, 0, " ");
  $pt->free();

  $bar->draw($img);
  if ($validRADAR)
    doppler8logo($map, $img, 260, 27, 53);
  mktitle($map, $img, 0, 230, " Sponsored by ". $sponsors[$sid]["sponsor"] );
  putenv("TZ=CST6CDT");
  mkstationtitle($map, $img,  5, 10, date("h:i A") ." - ". $locs->table[$sid]["city"]  );

  $img->saveImage('/tmp/radimages/'. $sid .'.png');
  $img->free();
}

chdir("/tmp/radimages");
foreach($locs->table as $sid => $value)
{
  system("/home/ldm/bin/pqinsert -p 'lsdimages cr 000000000000 kcci/radar/${sid}/${sid}_ bogus png' ${sid}.png");
}

?>
