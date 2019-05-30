<?php
/* 
 * The goal of this script is to figure out if we need to generate images
 * of KCCI RADAR data
 */
// Don't run too close to top of minute, give chance for file to arrive
sleep(15);

date_default_timezone_set('UTC');

define("KCCI_TIME_FILE", 
		"/home/ldm/data/gis/images/26915/KCCI/KCCI_N0R_tm_0.txt");
define("DMX_TIME_FILE", "/home/ldm/data/gis/images/4326/ridge/DMX/N0Q_0.json");
define("HISTORY_FILE", "/tmp/last_kcci.json");
define("DEBUG", false);

function get_last_run(){
	/*
	 * Get the timestamp that we last run this script successfully for
	 */
	if (! is_file(HISTORY_FILE)){
		return null;
	}
	$farray = file(HISTORY_FILE);
	$json = json_decode($farray[0], true);
	return strtotime($json["valid"]);
}

function get_dmx_time(){
	/*
	 * Get the timestamp of the DMX N0Q file
	 */
	if (! is_file(DMX_TIME_FILE)){
		return null;
	}
	$farray = file(DMX_TIME_FILE);
	$json = json_decode($farray[0], true);
	return strtotime($json["meta"]["valid"]);
}

function get_kcci_time(){
	/*
	 * Get the timestamp of KCCI's RADAR image, the metadata file does not
	 * have a year included, sigh
	 */
	$f = file(KCCI_TIME_FILE);
	if (sizeof($f) == 0){
		/* Empty file, lets wait a bit and try again! */
		sleep(5);
		$f = file(KCCI_TIME_FILE);
		if (sizeof($f) == 0){
			/* Give up! */
			return null;
		}
	}
	$tmstring = gmdate("Y") ."/". $f[0];
	$ts = strtotime($tmstring);
	return $ts;
}

function write_lastts($ts){
	/*
	 * Write our temp file for the $ts
	 */
	$meta = Array( 'valid' => gmdate("Y-m-d\\TH:i:s\\Z", $ts) );
	$f = fopen(HISTORY_FILE, 'w');
	fwrite($f, json_encode($meta));
	fclose($f);
}

function run(){
	`php -q genLSD.php`;
}

$now = time();
$kccits = get_kcci_time();
if (DEBUG) echo sprintf("KCCI Time is: %s\n", date("Y-m-d\\TH:i:s\\Z", $kccits) );
$dmxts = get_dmx_time();
if (DEBUG) echo sprintf("DMX  Time is: %s\n", date("Y-m-d\\TH:i:s\\Z", $dmxts) );
$lastts = get_last_run();
if (DEBUG) echo sprintf("Last Time is: %s\n", date("Y-m-d\\TH:i:s\\Z", $lastts) );

// Case 1: Account for nulls
if ($kccits == null){
	if (DEBUG) echo "kccits is null, exiting\n";
	exit(0);
}

// Case 2: KCCI is newer than lastts
if ($kccits > $lastts){
	if (DEBUG) echo "kccits > lastts\n";
	write_lastts($kccits);
	run();
	exit(0);
}

// Case 3: KCCI is old, but DMX is newer than lastts
if ( ($now - $kccits) > 8*60 && $dmxts > $lastts){
	if (DEBUG) echo "kccits old, dmxts newer than lastts\n";
	write_lastts($dmxts);
	run();
	exit(0);
}

// Case 4: KCCI == lastts
if ($kccits == $lastts){
	if (DEBUG) echo "kccits == lastts\n";
	exit(0);
}

if (DEBUG) echo "I am without hope\n";

?>
