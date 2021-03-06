<?php
/* Locations class 
 *  Makes metadata easy and fun for the website
 */
class Locations {

  function Locations($station=false)
  {
    $this->table = Array();

    global $dbhost;
    $dbconn = pg_connect($dbhost);
    if ($station){
      $rs = pg_prepare($dbconn, "SELECT", "SELECT *, ST_x(geom) as lon, 
            ST_y(geom) as lat from stations WHERE id = $1 and online");
      $rs = pg_execute($dbconn, "SELECT", Array($station));
    } else {
      $rs = pg_query($dbconn, "SELECT *, ST_x(geom) as lon, 
            ST_y(geom) as lat from stations WHERE online ORDER by city ASC");
    }
    for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
    {
      $this->table[ $row["id"] ] = $row;
    }
    pg_close($dbconn);
  }
  
  function verify_station($station)
  {
  	if (array_key_exists($station, $this->table)){return $station;}
  	// Uh oh, we have an invalid station, lets try some standard tricks 
  	$station = strtoupper($station);
  	$station = str_replace('1','I', $station);
    if (array_key_exists($station, $this->table)){return $station;}
  	die("Invalid Station ID given, sorry: $station");
  }

  function find_climate($station)
  {
    global $dbhost;
    $dbconn = pg_connect($dbhost);
    $rs = pg_prepare($dbconn, "SELECT", "SELECT * from climate51 WHERE
          station = $1 and valid = $2");
    $rs = pg_execute($dbconn, "SELECT", Array(
          $this->table[$station]["climate_site"],
          "2000-". date("m-d")));
    $row = pg_fetch_array($rs,0);
    pg_close($dbconn);
    return $row;
  }

  function find_nwsli($id)
  {
    reset($this->table);
    while( list($key,$db) = each($this->table))
    {
      if ($db["nwn_id"] == $id){ return $key; }
    }
    return null;
  }

}
?>
