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
echo "};\n";

/* Now we output a dictionary of sponsor stuff! */
echo "webcamsponsor = {";
$data = "";
while(list($sid, $ar) = each($cameras)){
  $d = "";
  if ($ar["sponsor"] != ""){
     $d .= "Sponsored by: <a href=\"". $ar["sponsorurl"] ."\">". $ar["sponsor"] ."</a><br />";
  }

  if ($ar["iservice"] != "" ){
     $d .= "Internet service by: <a href=\"". $ar["iserviceurl"] ."\">". $ar["iservice"] ."</a><br />";
  }

  if ($ar["hosted"] != "")
  {
     if ($ar["hostedurl"] != ""){
       $d .= "Hosted by: <a href=\"". $ar["hostedurl"] ."\">". $ar["hosted"] ."</a><br />";
      }
      else {
       $d .= "Hosted by: <b>". $ar["hosted"] ."</b><br />";
      }
  }
  $data .= sprintf("%s: '%s',\n", str_replace("-", "_", $sid), $d);

}
echo substr($data,0,-2);
echo "};";

?>
