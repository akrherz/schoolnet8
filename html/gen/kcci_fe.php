<?php

$min = isset($_GET['min']) ? $_GET["min"]: 1;
$station = isset($_GET['station']) ? $_GET["station"]: 'SKCI4';

?>
<html>
<head>
<?php
  include("../../config/settings.inc.php");
  include("../../include/locs.inc.php");
  $app = "01"; include("../../include/dblog.inc.php");
 if (strlen($station) == 0){
   $station = "SKCI4";
 }
 if (strlen($min) == 0){
   $secs = 600;
   $min = 1;
 }
 $secs = intval($min) * 60;

?>
  <title>IEM | KCCI SchoolNet | <?php echo $Scities[$station]["short"]; ?></title>
  <meta http-equiv="refresh" content="<?php echo $secs; ?>; URL=<?php echo $baseurl; ?>gen/kcci_fe.php?min=<?php echo $min; ?>&station=<?php echo $station; ?>">

</head>
<body bgcolor="#96aae7">

<center>
<form method="POST" action="<?php echo $baseurl; ?>gen/kcci_fe.php" name="st">
<?php
 
  echo "SchoolNet Site: ";
echo "<select  onChange=\"location=this.form.station.options[this.form.station.selectedIndex].value\" name=\"station\">\n";

reset($Scities);
while( list($id,$d) = each($Scities))
{
  echo "<option value=\"". $baseurl ."gen/kcci_fe.php?min=".$min."&station=". $id ."\"";
  if ($station == $id){
        echo " SELECTED ";
  }
  echo " >". $d["city"] ."\n";
}

echo "</select>\n";

?>
</form>
<p>
<?php
  echo "<a href=\"". $baseurl ."tool/clicktru.php?station=".$station."\" target=\"_new\"><img src=\"". $baseurl ."spics/kcci_".$station .".gif\" border=0></a><br>\n";
  echo "<img src=\"". $baseurl ."gen/kcci.php?station=".$station ."\">\n";
?>

<?php if (! isset($mode) ){ ?>
<br>Refresh Every: 
<form name="refresh" action="kcci_fe.php">
<?php
  $mins = Array(1, 5, 10, 20);
  while (list($key, $val) = each($mins) ){
    echo "<input type=\"radio\" name=\"min\" value=\"". $val ."\" ";
    if ($min == $val){
      echo "CHECKED";
    }
    echo "> ". $val ." min";
  }
?>
<input type="hidden" value="<?php echo $station; ?>" name="station">
<input type="submit" value="Refresh">
</form>
<?php } ?>

</center>

</html>
