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
