<?
header("content-type: image/png");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");                          // HTTP/1.0

$station = $_GET['station'];

 include("../../config/settings.inc.php");
 include("../../include/locs.inc.php");
 include("../../include/currentdb.inc.php");
 $site = substr($station, 0, 5);
 if (strlen($site) == 0)  $site = 'SKCI4';

 $obs = new currentdb();
 $data = $obs->db;
 $myOb = $data[$site];

 $tmpf = $myOb["tmpf"];
 $relh = $myOb["relh"];
 $alti = $myOb["alti"];
 $pday = $myOb["pday"];
 $drct = $myOb["drctTxt"];
 $gust = $myOb["gmph"];
 $time = "Valid: ". strftime("%Y-%m-%d %I:%M %p", $myOb["ts"]) ;
 $sped = $myOb["sped"];
 $dwpf = $myOb["dwpf"];

 $gustDir = $myOb["max_drctTxt"];
 $maxTemp =  $myOb["tmpf_max"];

 $minTemp = $myOb["tmpf_min"];
 $feel = $myOb["feel"];


 $width = 320;
 $height = 240;
 $Font = './kcci.ttf';

 $gif = ImageCreate($width,$height);


 $black = ImageColorAllocate($gif,0,0,0);
	$white = ImageColorAllocate($gif,250,250,250);
	$green = ImageColorAllocate($gif, 0, 255, 0);
	$yellow = ImageColorAllocate($gif, 255, 255, 120);
	$red = ImageColorAllocate($gif, 148, 52, 53);
	$blue = ImageColorAllocate($gif, 0, 0, 255);
	$grey = ImageColorAllocate($gif, 110, 110, 110);
	
	ImageFilledRectangle($gif,2,2, $width, $height, $grey);
	ImageFilledRectangle($gif,1,1, $width -2 , $height -2, $white);

        $kcci_logo = imagecreatefrompng ("Ames320.png");
        imagecopy($gif, $kcci_logo, 0, 0, 0, 0, 320, 320);

        $wlogo = imagecreatefrompng ("dirs/Wind_". strtolower($drct) .".png");
        imagecolortransparent( $wlogo, $black);
        imagecopy($gif, $wlogo, 96, 23, 0, 0, 139, 139);

//imagettftext ( resource image, int size, int angle, int x, int y, int col, string fontfile, string text)

 ImageTTFText($gif, 10, 0, 169 , 34, $white, "./kcci.ttf", strtoupper(substr($Scities[$site]["short"], 0, 16)) );

 // Box to hold current dew Point!
// imagerectangle ( $gif, 10, 40, 40, 60, $black);
// imagefilledrectangle ( $gif, 11, 41, 39, 59, $blue);
// ImageTTFText($gif, 12, 0, 11 , 55, $white, "./kcci.ttf", $dwpf );
 
 $fs = 22;
// RelHumidity
 $size = imagettfbbox($fs, 0, $Font, $relh ." %");
 $dx = abs($size[2] - $size[0]);
 $x0 = 206;
 $width = 74;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad, 125, $white, $Font, $relh ." %");

// Dew Point
 $size = imagettfbbox($fs, 0, $Font, $dwpf);
 $dx = abs($size[2] - $size[0]);
 $x0 = 204;
 $width = 74;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad, 165, $white, $Font, $dwpf );

// Feels Like
 $size = imagettfbbox($fs, 0, $Font, $feel );
 $dx = abs($size[2] - $size[0]);
 $x0 = 204;
 $width = 74;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad, 205, $white, "./kcci.ttf", $feel );

// Precip
 $size = imagettfbbox($fs, 0, $Font, $pday );
 $dx = abs($size[2] - $size[0]);
 $x0 = 106;
 $width = 76;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad , 205, $white, "./kcci.ttf", $pday );

// Gust
 $size = imagettfbbox($fs, 0, $Font, $gustDir ."-".$gust);
 $dx = abs($size[2] - $size[0]);
 $x0 = 106;
 $width = 76;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad, 165, $white, $Font, $gustDir ."-".$gust );

// Wind Speed
 $size = imagettfbbox($fs, 0, $Font, $sped);
 $dx = abs($size[2] - $size[0]);
 $x0 = 122;
 $width = 45;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad , 80, $white, $Font, $sped );


// Time
 ImageTTFText($gif, 14, 0, 150 , 235, $white, "./kcci.ttf", $time );

// TempF
 $size = imagettfbbox($fs, 0, $Font, $tmpf);
 $dx = abs($size[2] - $size[0]);
 $x0 = 32;
 $width = 55;
 $x_pad = ($width - $dx) / 2 ;
 ImageTTFText($gif, $fs, 0, $x0 + $x_pad, 205, $white, "./kcci.ttf", $tmpf );

// Time to do the rotation!!!
//              x   y    x   y    x   y
$windDirs = Array(
  "N" => Array(140, 50, 145, 40, 150, 50),
  "S" => Array(140, 92, 145,102, 150, 92),
  "W" => Array(124, 66, 124, 76, 114, 71),
  "E" => Array(176, 71, 166, 76, 166, 66),
  "NNE" => Array(150, 50, 157, 54, 156, 44)
   );

// imagefilledpolygon($gif, $windDirs["N"], 3, $yellow);
// imagefilledpolygon($gif, $windDirs["S"], 3, $yellow);
// imagefilledpolygon($gif, $windDirs["W"], 3, $yellow);
// imagefilledpolygon($gif, $windDirs["E"], 3, $yellow);
// imagefilledpolygon($gif, $windDirs["NNE"], 3, $yellow);

// imagepolygon($gif, $windDirs["N"], 3, $black);
// imagepolygon($gif, $windDirs["S"], 3, $black);
// imagepolygon($gif, $windDirs["W"], 3, $black);
// imagepolygon($gif, $windDirs["E"], 3, $black);
// imagepolygon($gif, $windDirs["NNE"], 3, $black);

// Time to do temperature
 $leftside = 57;
 $rightside = 61;
 $maxT_y = 47;
 $minT_y = 147;
 $maxT = 120;
 $minT = -20;

 $pixels = ($minT_y - $maxT_y);
 $x = $tmpf + 20; // Adjust for -20 start
 $height = $pixels * ($x / ($maxT - $minT) ) ;
 $maxLineHeight = $pixels * (($maxTemp + 20)/ ($maxT - $minT) ) ;
 $minLineHeight = $pixels * (($minTemp + 20)/ ($maxT - $minT) ) ;


 imagefilledrectangle ( $gif, $leftside, $minT_y - $height, $rightside, $minT_y, $red);

 imagefilledrectangle( $gif, $leftside -50, $minT_y - $maxLineHeight -1, 
                  $rightside +2, $minT_y - $maxLineHeight, $black);
 imagefilledrectangle( $gif, $leftside -50, $minT_y - $minLineHeight -1, 
                  $rightside +2, $minT_y - $minLineHeight, $black);

// MAX
 imagefilledrectangle($gif, $leftside - 50, $minT_y - $maxLineHeight - 1 - 20,
                  $leftside - 30, $minT_y - $maxLineHeight - 1, $red);
 ImageTTFText($gif, 18, 0, $leftside - 50 + 2 , $minT_y - $maxLineHeight -1 -2, 
     $white, "./kcci.ttf", $maxTemp );
 ImageTTFText($gif, 12, 0, $leftside - 50 + 1 , $minT_y - $maxLineHeight -1 -20 -2, 
     $white, "./kcci.ttf", "MAX");

// MIN
 imagefilledrectangle($gif, $leftside - 50, $minT_y - $minLineHeight - 1 ,
                  $leftside - 30, $minT_y - $minLineHeight - 1 + 20, $blue);
 ImageTTFText($gif, 18, 0, $leftside - 50 + 2 , $minT_y - $minLineHeight - 1 +20 -2, 
     $white, "./kcci.ttf", $minTemp );
 ImageTTFText($gif, 12, 0, $leftside - 50 + 1 , $minT_y - $minLineHeight -1 +20 +10, 
     $white, "./kcci.ttf", "MIN");

//	$size = imagettfbbox(12, 0, $Font, $Scities[$site]["city"]);
//	$dx = abs($size[2] - $size[0]);
//	$dy = abs($size[5] - $size[3]);
//	$x_pad = ($width - $dx) / 2 ;
//  ImageTTFText($gif, 8, 0, 10 , 85, $red, "./kcci.tff",$Scities[$site]["city"] );

	ImagePng($gif);
	ImageDestroy($gif);
?>
