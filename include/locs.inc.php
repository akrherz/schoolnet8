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
      $rs = pg_prepare($dbconn, "SELECT", "SELECT *, x(geom) as lon, 
            y(geom) as lat from stations WHERE id = $1");
      $rs = pg_execute($dbcoon, "SELECT", Array($station));
    } else {
      $rs = pg_query($dbconn, "SELECT *, x(geom) as lon, 
            y(geom) as lat from stations");
    }
    for( $i=0; $row = @pg_fetch_array($rs,$i); $i++)
    {
      $this->table[ $row["id"] ] = $row;
    }
    pg_close($dbconn);
  }

  function find_climate($station)
  {
    global $dbhost;
    $dbconn = pg_connect($dbhost);
    $rs = pg_prepare($dbconn, "SELECT", "SELECT * from climate51 WHERE
          station = $1 and valid = $2");
    $rs = pg_execute($dbconn, "SELECT", Array(
          strtolower($this->table[$station]["climate_site"]),
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
