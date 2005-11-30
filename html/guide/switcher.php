<?php 
/* Something to switch the Current Lesson */

$pages = Array(
"comparing" => "1. Comparing Observations",
"solar" => "2. Solar Radiation",
"sortable" => "3. Sortable Current Conditions",
"1minute" => "4. One Minute Data Traces",
"webcam" => "5. Web Cameras",
"lsdapp" => "6. Live Super Doppler8 App",
"radarapps" => "7. Using RADAR with Observations and Warnings", 
"ortho" => "8. Orthophotography",
"barbs" => "9. Wind Barbs",
"nav" => "10. Navigating SchoolNet8.com");

function lessonSelect($selected)
{
 global $pages;
 $s = "<form method=\"GET\" action=\"index.phtml\" name=\"sel\">\n";
 $s .= "<strong>Select Lesson:</strong><select name=\"module\" onChange=\"location=this.form.module.options[this.form.module.selectedIndex].value;\">\n";
 $s .= "<option value=\"index.phtml\">-- Site Guide Index --</a>\n";
 reset($pages);
 while (list($key,$val) = each($pages))
 {
   $s .= "<option value=\"index.phtml?module=$key\" ";
   if ($selected == $key) $s .= "SELECTED";
   $s .= ">$val</option>\n";
 }
 $s .= "<option value=\"index.phtml?print=yes\">-- Print Version --</a>\n";
 $s .= "</select>\n";
 return $s;
}

?>
