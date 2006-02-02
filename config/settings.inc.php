<?php

$dbhost = 'host=127.0.0.1 dbname=kcci user=kcci password=kcciweb';
$summarydb = 'host=mesonet-db1.agron.iastate.edu dbname=summary user=nobody';
$iemkccidb = 'host=mesonet-db1.agron.iastate.edu dbname=kcci user=nobody';
$iemdbhost = 'host=mesonet-db1.agron.iastate.edu user=nobody dbname=snet';
$iemaccess = 'host=mesonet-db1.agron.iastate.edu dbname=iem user=nobody';

//$server = 'http://www.schoolnet8.com';
//$baseurl = 'http://www.schoolnet8.com/';
//$basecgi = 'http://www.schoolnet8.com/cgi-bin/nwnwebsite/';
$server = 'http://ics128-198.icsincorporated.com';
$baseurl = 'http://ics128-198.icsincorporated.com/nwnwebsite/';
$basecgi = 'http://ics128-198.icsincorporated.com/cgi-bin/nwnwebsite/';
//$server = 'http://akrherz-laptop.agron.iastate.edu';
//$baseurl = 'http://akrherz-laptop.agron.iastate.edu/nwnwebsite/';
//$basecgi = 'http://akrherz-laptop.agron.iastate.edu/cgi-bin/nwnwebsite/';
$backupbaseurl = 'http://mesonet.agron.iastate.edu/nwnwebsite/';

$nwnpath = '/home/akrherz/projects/nwnwebsite/';
$mapfile = "$nwnpath/data/GIS/base.map";

global $mapscript;
$mapscript = 'php_mapscript_461.so';
?>
