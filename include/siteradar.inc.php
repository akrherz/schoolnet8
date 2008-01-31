<?php
  $imgbase = "$baseurl/static/radar/$station/$station";
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
<script language="JavaScript" src="<?php echo $baseurl; ?>js/animation.js"></script>

<form method="get" action="site.phtml">
<p><h2>Live Super Doppler8:</h2>
  <a href="javascript: switchLayers('layerStill')" style="background: #C0DBFF;"> &nbsp; Latest Image &nbsp; </a><a href="javascript: switchLayers('layerLoop')" style="background: #FFC0CB;"> &nbsp; Show Loop &nbsp; </a>

<div id="layerStill" style="background: #C0DBFF; text-align: center; padding: 5px;">
<input type="hidden" name="station" value="<?php echo $station; ?>">
<input type="hidden" name="station2" value="<?php echo $station2; ?>">
<?php
 if ($var == "tmpf")
 {
  ?><img width="320" height="240" src="<?php echo $imgbase; ?>_0.png?<?php echo $t; ?>" alt="Image" border="2" /><?php
 } else 
 {
 ?><img src="<?php echo $baseurl; ?>GIS/apps/radar/site.php?station=<?php echo $station;?>&amp;var=<?php echo $var;?>" width="320" height="240" alt="Live Doppler" border="2" /><?php
 }
?>
    <br />Plot:
     <select name="var">
       <?php
          sel($var, "tmpf", "Temperature");
          sel($var, "dwpf", "Dew Point");
          sel($var, "feel", "Feels Like");
          sel($var, "alti", "Pressure");
          sel($var, "sped", "Wind Speed [MPH]");
          sel($var, "barb", "Wind Barb");
          sel($var, "gbarb", "Wind Gust Barb");
          sel($var, "max_sped", "Wind Gust [MPH]");
          sel($var, "max_sknt", "Wind Gust [knts]");
          sel($var, "pday", "Today Rainfall");
          sel($var, "pmonth", "Month Rainfall");
          sel($var, "tmpf_max", "Today Hi Temp");
          sel($var, "tmpf_min", "Today Low Temp");
        ?>
     </select><input type="submit" value="Go" /></form></p>
</div>
<div id="layerLoop" style="background: #FFC0CB; text-align: center; padding: 5px;">

<img name="animation" width="320" height="240" src="<?php echo $baseurl; ?>images/pixel.gif" alt="Image" border="2" />

  <form method="POST" name="control_form">
<a href="JavaScript: func()" onClick="change_mode(1);reverse()"><img border="0" src="<?php echo $baseurl; ?>images/rev_button.gif" alt="REV"></a>
<a href="JavaScript: func()" onClick="stop()"><img BORDER=0 src="<?php echo $baseurl; ?>images/stp_button.gif" alt="STOP"></a>
<a href="JavaScript: func()" onClick="change_mode(1);fwd()"><img border="0" src="<?php echo $baseurl; ?>images/fwd_button.gif" alt="FWD"></a>
  <font size=-1 color="#3300CC">Frame No:</font>
  <input type="text" name="frame_nr" value="9" size="2" onFocus="this.select()" onChange="go2image(this.value)"></input>
  </form>
</div>

<script LANGUAGE="JavaScript1.2" type="text/javascript">
//<!--
function switchLayers( layerName ) {
  if ( document.getElementById ) {

    var layerStill = document.getElementById( 'layerStill' );
    var layerLoop = document.getElementById( 'layerLoop' );
    layerStill.style.display = 'none';
    layerLoop.style.display = 'none';

    switch(layerName) {
      case "layerStill":
        stop();
        layerStill.style.display = 'block';
      break;
      case "layerLoop":
        clearTimeout(refresh_timeout);
        setTimeout('document.location=document.location',300000);
        launch();
        layerLoop.style.display = 'block';
      break;
      default:
      break;
    }
  }
}
switchLayers('layerStill');
-->
</script>
