<?php
/** live.php request a live shot! */
include("../../config/settings.inc.php");
$app = "26"; include("$nwnpath/include/dblog.inc.php");
include("../../include/cameras.inc.php");

$id = isset($_GET["id"]) ? $_GET["id"] : "SMAI4";

if (! $cameras[$id]["active"]) die();

$ip = $cameras[$id]["ip"];
$cache = "/tmp/". $id ."_cache.jpg";
/* Test for a cache file */
if ( is_file($cache) )
{
	$now = time();
	$c = filectime($cache);
	if ( ($now - $c) < 15 )
	{
		header("Content-type: image/jpeg");
		readfile($cache);
		die();
	}
}


$im = imagecreatefromjpeg("http://root:${CAMPASS}@$ip/-wvhttp-01-/GetOneShot");

if (! $im ) die();

$white = imagecolorallocate($im, 250, 250, 250);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 3, 465, 637, 480, $black);
imagestring($im, 5, 15, 465, date("d M Y h:i:s A, ") . $cameras[$id]["name"] ." Live Shot!", $white);

header("Content-type: image/jpeg");
imagejpeg($im);

/* Write to cache */
imagejpeg($im, $cache);

?>
