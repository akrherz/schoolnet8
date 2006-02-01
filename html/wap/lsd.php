<?php
include("../../config/settings.inc.php");
include("$nwnpath/include/radar.php");
$sector = isset($_GET["sector"]) ? $_GET["sector"] : "iowa";
$bnds = Array(
"iowa" => Array( 200000, 4400000, 710000, 4900000),
"metro" => Array( 375000, 4550000, 500000, 4675000),
"sw" => Array( 300000, 4475000, 450000, 4625000),
"se" => Array( 475000, 4450000, 635000, 4675000), 
"ne" => Array( 475000, 4600000, 685000, 4825000),
"nw" => Array( 275000, 4600000, 485000, 4825000)
);
$bounds = $bnds[$sector];

dl($mapscript);

$map = ms_newMapObj($mapfile);
$map->setextent($bounds[0], $bounds[1], $bounds[2], $bounds[3]);
$map->set("width", 150);
$map->set("height", 100);

$tvradar = $map->getlayerbyname("KCCI");
$tvradar->set("status", MS_ON);

$counties = $map->getlayerbyname("counties");
$counties->set("status", MS_OFF);
if ($sector != "iowa")
  $counties->set("status", MS_ON);

$states = $map->getlayerbyname("states");
$states->set("status", MS_ON);


$img = $map->prepareImage();
$tvradar->draw($img);
$counties->draw($img);
$states->draw($img);
doppler8logo($map, $img, 75, 7, 29);
mktitle($map, $img, 50, 90, date("h:i A") );

header("Content-type: image/png");
$img->saveImage("");

?>
