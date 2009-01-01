<?php
header("Content-type: text/javascript");
/* Generate javascript lookup table of active webcams :/ */
include("../../config/settings.inc.php");
include("$nwnpath/include/cameras.inc.php");

echo "webcamlookup = {";
$data = "";
while(list($sid, $ar) = each($cxref)){
  $data .= sprintf("%s: '%s',", $sid, $ar[0]);
}
echo substr($data,0,-1);
echo "};";

?>
