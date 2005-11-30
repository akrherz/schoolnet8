<?php
/* Something to allow saving of the web image */
$img = isset($_GET["img"]) ? $_GET["img"] : die("No Image specified");

 header("Content-type: application/octet-stream");
 header("Content-Disposition: attachment; filename=image.png");
if ( strlen($img) > 30 ) die();
readfile("/home/httpd/html/$img");
?>
