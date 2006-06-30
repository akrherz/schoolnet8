
<?php

/* $Id: sunrise.inc.php 597 2005-10-26 22:57:30Z bb $

Source: http://www.zend.com/codex.php?id=135&single=1

This code will give you the sunrise and sunset times for any
latitude and longitude in the world. You just need to supply
the latitude, longitude and difference from GMT.

This script includes code translated from the perl module
Astro-SunTime-0.01.

PHP code mattf@mail.com - please use this code in any way you wish
and if you want to, let me know how you are using it.

Made into a class by <bbolli@ewanet.ch>, 2003-12-14 */

class Astro_Sunrise {

  // coordinates to calculate sunrise/sunset for
  var $lat = 47.0452;    // -90..+90; > 0 is north of the equator
  var $lon =  7.2715;    // -180..+180; > 0 is east of Greenwich

  // date
  var $year;        // 4 digits, please
  var $month;
  var $mday;        // day of the month
  var $tz;        // timezone offset in hours, > 0 is east of GMT, < 0 is west
  var $yday;        // day of the year

  var $twilight = array(
    'effective' => -.0145439,    // sunrise/sunset
    'civil' => -.104528,    // civil twilight
    'nautical' => -.207912,    // nautical twilight
    'astronomical' => -.309017    // astronomical twilight
  );

  var $R;        // radius used for twilight calculation

  var $last_utc;    // UNIX timestamp of last calculation

  function Astro_Sunrise() {
    $this->setTwilight('effective');
  }

  function setCoords($lat, $lon) {
    if ($lat < -90 || $lat > 90 || $lon < -180 || $lon > 180)
      return null;
    $this->lat = $lat;
    $this->lon = $lon;
  }

  function getCoords() {
    return sprintf('%1.4f %s %1.4f %s',
      abs($this->lat), $this->lan < 0 ? 'S' : 'N',
      abs($this->lon), $this->lon < 0 ? 'W' : 'E'
    );
  }

  function setDate($year, $month, $mday) {
    if ($year < 100)
      $year += 1900;
    if ($year < 1600 || !checkdate($month, $mday, $year))
      return null;

    $this->year = $year;
    $this->month = $month;
    $this->mday = $mday;

    $daysinmonth = array(0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334);
    $this->yday = $daysinmonth[$month - 1] + $mday - 1;
    if ($month > 2 && ($year % 4) == 0 && (($year % 100) != 0 || ($year % 400) == 0))
      $this->yday++;
  }

  function getDate() {
    return sprintf('%04d-%02d-%02d', $this->year, $this->month, $this->mday);
  }

  function setTimestamp($time) {
    list($this->year, $this->month, $this->mday, $this->yday) =
      explode(':', date('Y:n:j:z', $time));
  }

  function setTimezone($tz=0) {
    if ($tz < -13 || $tz > 13)
      return null;
    $this->tz = $tz;
  }

  function getTimezone() {
    $tz = abs($this->tz);
    if ($tz == 0)
      return 'UTC';
    $hours = intval($tz);
    $mins = intval(($tz - $hours) * 60 + 0.5);
    return sprintf('%s%02d%02d', $this->tz < 0 ? '-' : '+', $hours, $mins);
  }

  function setTwilight($type) {
    if (!array_key_exists($type, $this->twilight))
      return null;
    $this->R = $this->twilight[$type];
  }


  function getSunrise() {
    return $this->calcSunrise(true);
  }

  function getSunset() {
    return $this->calcSunrise(false);
  }

  function getLastSwatchBeat() {
    $tm = ($this->last_utc + 3600) % 86400;    // MEZ
    return sprintf("@%03d", 1000 * $tm / 86400);
  }

  function calcSunrise($isRise) {

    // multiples of pi
    $A = 0.5 * M_PI;            // Quarter circle
    $B =       M_PI;            // Half circle
    $C = 1.5 * M_PI;            // 3/4 circle
    $D = 2   * M_PI;            // Full circle

    // convert coordinates and time zone to radians
    $E = $this->lat * $B / 180;
    $F = $this->lon * $B / 180;
    $G = $this->tz * $D / 24;

    $J = $isRise ? $A : $C;

    $K = $this->yday + ($J - $F) / $D;
    $L = $K * .017202 - .0574039;    // Solar Mean Anomoly
    $M = $L + .0334405 * sin($L);    // Solar True Longitude
    $M += 4.93289 + 3.49066E-4 * sin(2 * $L);

    // Quadrant Determination
    $M = norm($M, $D);

    if (($M / $A) - intval($M / $A) == 0)
      $M += 4.84814E-6;
    $P = sin($M) / cos($M);        // Solar Right Ascension
    $P = atan2(.91746 * $P, 1);

    // Quadrant Adjustment
    if ($M > $C)
      $P += $D;
    elseif ($M > $A)
      $P += $B;

    $Q = .39782 * sin($M);        // Solar Declination
    $Q /= sqrt(-$Q * $Q + 1);
    $Q = atan2($Q, 1);

    $S = $this->R - sin($Q) * sin($E);
    $S /= cos($Q) * cos($E);

    if (abs($S) > 1)
      return "(Mitternachtssonne/Dauernacht)";

    $S /= sqrt(-$S * $S + 1);
    $S = $A - atan2($S, 1);

    if ($isRise)
      $S = $D - $S;

    $T = $S + $P - 0.0172028 * $K - 1.73364;    // Local apparent time
    $U = $T - $F;            // Universal time
    $V = $U + $G;            // Wall clock time

    // Quadrant Determination
    $U = norm($U, $D);
    $V = norm($V, $D);

    // Scale from radians to hours
    $U *= 24 / $D;
    $V *= 24 / $D;

    // Universal time
    $hour = intval($U);
    $U    = ($U - $hour) * 60;
    $min  = intval($U);
    $U    = ($U - $min) * 60;
    $sec  = intval($U);
    $this->last_utc = gmmktime($hour, $min, $sec, $this->month, $this->mday, $this->year);

    // Local time
    $hour = intval($V);
    $min  = intval(($V - $hour) * 60);

    return sprintf('%02d:%02d', $hour, $min);

  }    // function calcSunrise

}    // class Astro_SunTime

function norm($a, $b) {        // normalize $a to be in [0, $b)
  while ($a < 0)
    $a += $b;
  while ($a >= $b)
    $a -= $b;
  return $a;
}    // function norm

?>
