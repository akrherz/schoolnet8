<?php
include("../../config/settings.inc.php");

dl($mapscript);

$map = ms_newMapObj($mapfile);
$map->set("width", 150);
$map->set("height", 100);

$tvradar = $map->getlayerbyname("KCCI");
$tvradar->set("status", MS_ON);

$img = $map->prepareImage();
$tvradar->draw($img);

header("Content-type: image/png");
$img->saveImage("");

?>
