<?php 

function kcciSelectAuto($selected, $pre, $post, $target=""){

include_once("locs.inc.php");
global $Scities;
if (strlen($target) == 0) { 
  $s = "<form name=\"autoselect\" action=\"#\"><select name=\"station\" onchange=\"location=this.form.station.options[this.form.station.selectedIndex].value\">\n";
} else {
  $s = "<form name=\"autoselect\" action=\"#\"><select name=\"station\" onchange=\"window.open(this.form.station.options[this.form.station.selectedIndex].value, 'basewindow');\">\n";
}

reset($Scities);
while( list($id,$d) = each($Scities) )
{
  $s .= "<option value=\"". $pre . $id . $post ."\"";
  if (strcmp($selected, $id) == 0){
        $s .= " selected=\"selected\" ";
  }
  $s .= " >". $d["city"] ."</option>\n";
}

$s .= "</select></form>\n";
return $s;
}


function kcciSelect($selected){

include_once("locs.inc.php");
global $Scities;
echo "<select name=\"station\">\n";

reset($Scities);
while( list($id,$d) = each($Scities) )
{
  echo "<option value=\"". $id ."\"";
  if ($selected == $id){
        echo " selected=\"selected\" ";
  }
  echo " >". $d["city"] ."</option>\n";
}

echo "</select>\n";

}

?>
