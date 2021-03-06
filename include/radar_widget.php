<?php
class radarWidget 
{
	var $showVariableSwitch = true;
	var $controlPageRefresh = true;

function radarWidget($station, $var){
	$this->station = $station;
	$this->station2 = "";
	$this->var = $var;
}

function printHTML(){
  global $backupbaseurl;
  $imgbase = BASEURL ."/static/radar/". $this->station ."/". $this->station;
  $t = time();
?>
<script language="JavaScript1.2" type="text/javascript">
//<!--
modImages = new Array();
modImages[0] = "<?php echo $imgbase; ?>_9.png?<?php echo $t; ?>";
modImages[1] = "<?php echo $imgbase; ?>_8.png?<?php echo $t; ?>";
modImages[2] = "<?php echo $imgbase; ?>_7.png?<?php echo $t; ?>";
modImages[3] = "<?php echo $imgbase; ?>_6.png?<?php echo $t; ?>";
modImages[4] = "<?php echo $imgbase; ?>_5.png?<?php echo $t; ?>";
modImages[5] = "<?php echo $imgbase; ?>_4.png?<?php echo $t; ?>";
modImages[6] = "<?php echo $imgbase; ?>_3.png?<?php echo $t; ?>";
modImages[7] = "<?php echo $imgbase; ?>_2.png?<?php echo $t; ?>";
modImages[8] = "<?php echo $imgbase; ?>_1.png?<?php echo $t; ?>";
modImages[9] = "<?php echo $imgbase; ?>_0.png?<?php echo $t; ?>";
first_image = 1;
last_image = 10;
current_image = first_image;
-->
</script>
<script language="JavaScript" type="text/javascript" src="<?php echo BASEURL; ?>/js/animation.js"></script>

<form method="get" action="site.phtml" name="radSelect">
  <a href="javascript: switchLayers('radlayerstill')" style="background: #C0DBFF;"> &nbsp; Latest Image &nbsp; </a><a href="javascript: switchLayers('radlayerloop')" style="background: #FFC0CB;"> &nbsp; Show Loop &nbsp; </a>

<div id="radlayerstill">
<input type="hidden" name="station" value="<?php echo $this->station; ?>" />
<input type="hidden" name="station2" value="<?php echo $this->station2; ?>" />
<?php
 if ($this->var == "tmpf")
 {
  ?><img width="320" height="240" src="<?php echo $imgbase; ?>_0.png?<?php echo $t; ?>" alt="Image" /><?php
 } else 
 {
 ?><img src="<?php echo BASEURL; ?>/GIS/apps/radar/site.php?station=<?php echo $this->station;?>&amp;var=<?php echo $this->var;?>" width="320" height="240" alt="Live Doppler" /><?php
 }
?>

<?php if ($this->showVariableSwitch) { ?>
    <br />Plot:
     <select name="var">
       <?php
          sel($this->var, "tmpf", "Temperature");
          sel($this->var, "dwpf", "Dew Point");
          sel($this->var, "feel", "Feels Like");
          sel($this->var, "alti", "Pressure");
          sel($this->var, "sped", "Wind Speed [MPH]");
          sel($this->var, "barb", "Wind Barb");
          sel($this->var, "gbarb", "Wind Gust Barb");
          sel($this->var, "max_sped", "Wind Gust [MPH]");
          sel($this->var, "max_sknt", "Wind Gust [knts]");
          sel($this->var, "pday", "Today Rainfall");
          sel($this->var, "pmonth", "Month Rainfall");
          sel($this->var, "tmpf_max", "Today Hi Temp");
          sel($this->var, "tmpf_min", "Today Low Temp");
        ?>
     </select><input type="submit" value="Go" />
<?php } ?>
</div>
</form>
<div id="radlayerloop">

<img name="animation" width="320" height="240" src="<?php echo BASEURL; ?>/images/pixel.gif" alt="Image" border="2" />

  <form method="post" name="control_form" action="#">
<a href="JavaScript: func()" onclick="change_mode(1);reverse()"><img border="0" src="<?php echo BASEURL; ?>/images/rev_button.gif" alt="REV" /></a>
<a href="JavaScript: func()" onclick="stop()"><img border="0" src="<?php echo BASEURL; ?>/images/stp_button.gif" alt="STOP" /></a>
<a href="JavaScript: func()" onclick="change_mode(1);fwd()"><img border="0" src="<?php echo BASEURL; ?>/images/fwd_button.gif" alt="FWD" /></a>
  <font size="-1" color="#3300CC">Frame No:</font>
  <input type="text" name="frame_nr" value="9" size="2" onfocus="this.select()" onchange="go2image(this.value)"></input>
  </form>
</div>

<script language="JavaScript1.2" type="text/javascript">
//<!--
function switchLayers( layerName ) {
  if ( document.getElementById ) {

    var radlayerstill = document.getElementById( 'radlayerstill' );
    var radlayerloop = document.getElementById( 'radlayerloop' );
    radlayerstill.style.display = 'none';
    radlayerloop.style.display = 'none';

    switch(layerName) {
      case "radlayerstill":
        stop();
        radlayerstill.style.display = 'block';
      break;
      case "radlayerloop":
<?php if ($this->controlPageRefresh){ ?>
        clearTimeout(refresh_timeout);
        setTimeout('document.location=document.location',300000);
<?php } ?>
        launch();
        radlayerloop.style.display = 'block';
      break;
      default:
      break;
    }
  }
}
switchLayers('radlayerstill');
-->
</script>
<?php
} // End of function printHTML()

} // End of radarWidget()

?>
