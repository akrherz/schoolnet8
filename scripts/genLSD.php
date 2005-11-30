<?php
include('../config/settings.inc.php');
include('../include/radar.php');

$validRADAR = kccidopplerrecent();

dl($mapscript);

$map = ms_newMapObj($mapfile);
$map->set("width", 640);
$map->set("height", 480);

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
  $interstates = $map->getlayerbyname("usinterstates");
  $interstates->set("status", MS_ON);

$img = $map->prepareImage();
  $interstates->draw($img);
  $counties->draw($img);
  $states->draw($img);

if ($validRADAR)
{
  $tvradar->draw($img);
} else{
  $dmx->draw($img);
}
$warnings_c->draw($img);

doppler8logo($map, $img, 425, 50, 65);

$img->saveImage('monkey.png');
print_r($map->extent);

?>
