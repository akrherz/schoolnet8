<?php
// Load MapScript
dl("php_mapscript_5.4.0.so");

//----------------------------------------------------------
// produce shapefile
//----------------------------------------------------------

function createPoint( $x, $y, $programId )
{
    GLOBAL $shpFile, $dbfFile;

    // Create shape
    $oShp = ms_newShapeObj(MS_SHP_POINT);
    $oLine = ms_newLineObj();
    $oLine->addXY($x, $y);
    $oShp->add( $oLine );
    $shpFile->addShape($oShp);

    // Write attribute record
    dbase_add_record($dbfFile, $programId);
}

$shpFname = "locs";
$shpFile = ms_newShapeFileObj( $shpFname, MS_SHP_POINT);
$dbfFile = dbase_create( $shpFname.".dbf", array(
  array("SID", "C", 5, 0),
  array("SNAME", "C", 30, 0))
);

include("/home/akrherz/projects/nwnwebsite/include/locs.inc.php");
$locs = new Locations();

while (list($key, $val) = each($locs->table) ){
  if ($val["online"] == false){ continue; }
  createPoint( $val["lon"], $val["lat"], array($key, $val["short"]) );
}

echo "Shapes Created.<BR>";

//----------------------------------------------------------
// done... cleanup
//----------------------------------------------------------

$shpFile->free();
echo "Shape File ($shpFname) closed.<BR>";

dbase_close($dbfFile);
echo "Dbase file closed.<BR>";

?>
