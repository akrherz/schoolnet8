<div id="footer">
<?php
if (isset($station) && $station != "" && $station != "MAIN") {
  	include_once("$nwnpath/include/locs.inc.php");
	include_once("$nwnpath/include/sponsors.inc.php");
  echo "<b><a href=\"$baseurl/site.phtml?station=$station\">". $Scities[$station]["city"] ."</a></b> SchoolNet8 Site is sponsored by <a href=\"". $baseurl ."tool/clicktru.php?station=$station\">". $sponsors[$station]["sponsor"] ."</a>";
}
?>
<br />SchoolNet8.com sponsored by: <a href="<?php echo $baseurl; ?>tool/clicktru.php?station=KCCI" target="_new" class="ftext">CIPCO</a>
&nbsp; &nbsp; Page Loaded: <?php echo date("d F Y  h:i A"); ?> [<a href="<?php echo $baseurl; ?>contact.phtml">Contact Info</a>]
</div>
</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-784549-1";
urchinTracker();
</script>
</body>
</html>
