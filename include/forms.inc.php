<?php
/**
 * Library for doing repetetive forms stuff
 */

function minuteSelect($selected, $name="minute"){
 $s = "<select name='$name'>";
 for ($i=0;$i<60;$i++)
 {
   $s .= "<option value='$i' ";
   if ($i == intval($selected)) $s .= "SELECTED";
   $s .= ">$i";
 }
 $s .= "</select>\n";
 return $s;
}

function minute5Select($selected, $name="minute"){
 $s = "<select name='$name'>";
 for ($i=0;$i<60;$i = $i + 5)
 {
   $s .= "<option value='$i' ";
   if ($i == intval($selected)) $s .= "SELECTED";
   $s .= ">$i";
 }
 $s .= "</select>\n";
 return $s;
}

function monthSelect($selected, $name="month"){
  $s = "<select name='$name'>\n";
  for ($i=1; $i<=12;$i++) {
    $ts = mktime(0,0,0,$i,1,0);
    $s .= "<option value='".$i ."' ";
    if ($i == intval($selected)) $s .= "SELECTED";
    $s .= ">". strftime("%B" ,$ts) ."\n";
  }
  $s .= "</select>\n";
  return $s;
}

function yearSelect($start, $selected, $name){
  $start = intval($start);
  $now = time();
  $tyear = strftime("%Y", $now);
  $s = "<select name='$name'>\n";
  for ($i=$start; $i<=$tyear;$i++) {
    $s .= "<option value='".$i ."' ";
    if ($i == intval($selected)) $s .= "SELECTED";
    $s .= ">". $i ."\n";
  }
  $s .= "</select>\n";
  return $s;
}

function daySelect($selected){
  $s = "<select name='day'>\n";
  for ($k=1;$k<32;$k++){
    $s .= "<option value=\"".$k."\" ";
    if ($k == (int)$selected){
      $s .= "SELECTED";
    }
    $s .= ">".$k."\n";
  }
  $s .= "</select>\n";
  return $s;
} // End of daySelect

function localHourSelect($selected, $name){
  echo "<select name='".$name."'>\n";
  for ($i=0; $i<24;$i++) {
    $ts = mktime($i,0,0,1,1,0);
    echo "<option value='".$i."' ";
    if ($i == intval($selected)) echo "SELECTED";
    echo ">". strftime("%I %p" ,$ts) ."\n";
  }
  echo "</select>\n";
}


?>
