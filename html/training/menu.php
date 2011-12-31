<?php

$pages = Array(
 "website" => "Website Tour [14 MB] (9 minutes)",
 "1minutetrace" => "1 Minute Time Series Graphs [2 MB] (6 minutes)",
 "snet8viewer" => "SchoolNet8 Desktop Viewer Install/Use [3 MB] (4 minutes)",
);


function lessonSelect($selected)
{
 global $pages;
 $s = "<form method=\"GET\" action=\"index.phtml\" name=\"sel\">\n";
 $s .= "<strong>Select Video: </strong><select name=\"module\" onChange=\"location=this.form.module.options[this.form.module.selectedIndex].value;\">\n";
 reset($pages);
 while (list($key,$val) = each($pages))
 {
   $s .= "<option value=\"". BASEURL ."/training/$key/index.phtml\" ";
   if ($selected == $key) $s .= "SELECTED";
   $s .= ">$val</option>\n";
 }
 $s .= "</select>\n";
 return $s;
}

?>
