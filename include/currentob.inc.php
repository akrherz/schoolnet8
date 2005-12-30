<?php

//____________________________________________________________
function cdf($fc, $format){
  $tokens = split(",", $fc);
  $stData = Array();
  while( list($key, $val) = each($format) ){
#    echo $tokens[$key] ." == ". $format[$key] ;
    $stData[$format[$key]] = $tokens[$key];
  } // End of while
  return $stData;
} // End of cdf

//___________________________________________________________
function currentOb(){
  global $nwnpath;
  $fc = file('/home/ldm/data/csv/kcci2.dat');
  $format = split(",", $fc[0] );
  $data = Array();
  for($i=1; $i < sizeof($fc); $i++ ){
    $data[ substr($fc[$i],0,5) ] = cdf($fc[$i], $format);
  }
  return $data;
} // End of currentOb

//______________________________________________________________
// \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//                 Generic Helper Functions
// /////////////////////////////////////////////////////////////
//--------------------------------------------------------------

?>
