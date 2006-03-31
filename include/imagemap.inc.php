<?php 

function kcciSelectAuto($selected, $pre, $post, $target=""){

include("locs.inc.php");
if (strlen($target) == 0) { 
  $s = "<form name=\"autoselect\" action=\"#\"><select name=\"station\" onchange=\"location=this.form.station.options[this.form.station.selectedIndex].value\">\n";
} else {
  $s = "<form name=\"autoselect\" action=\"#\"><select name=\"station\" onchange=\"window.open(this.form.station.options[this.form.station.selectedIndex].value, 'basewindow');\">\n";
}

for ($i = 0; $i < count($Scities); $i++) {
  $city = current($Scities);
  $s .= "<option value=\"". $pre . $city["id"] . $post ."\"";
  if (strcmp($selected, $city["id"]) == 0){
        $s .= " selected=\"selected\" ";
  }
  $s .= " >". $city["city"] ."</option>\n";
  next($Scities);
}

$s .= "</select></form>\n";
return $s;
}


function kcciSelect($selected){

include("locs.inc.php");
echo "<select name=\"station\">\n";

for ($i = 0; $i < count($Scities); $i++) {
  $city = current($Scities);
  echo "<option value=\"". $city["id"] ."\"";
  if ($selected == $city["id"]){
        echo " selected=\"selected\" ";
  }
  echo " >". $city["city"] ."</option>\n";
  next($Scities);
}

echo "</select>\n";

}

?>
