<?php
/**
 * currentdb.inc.php
 *   - Library for current observations
 */

function &array_combine2 ( $keys, $values )
{
 // if ( count($keys) != count($values) )
 //  return false;

  //$keys = array_values($keys);
  //$values = array_values($values);

  $newarray = array();

  foreach( $keys as $index => $key )
  {
     $newarray[ $key ] = $values[$index];
  }
  return $newarray;
} 

class currentdb
{
  var $error = 0;
  var $tsthreshold = 600; // 10 minutes
  var $fname = '';
  var $db = Array();

  function currentdb($fname='/mesonet/ldmdata/csv/kcci2.dat')
  {
    $this->fname = $fname;
    $this->parse();
  } // End of currentdb()

  function parse()
  {
    $lines = file($this->fname);
    $cols = explode(',', $lines[0]);
    for($i=1; $i < sizeof($lines); $i++)
    {
      $vals = explode(',', $lines[$i]);
      $this->db[ substr($lines[$i],0,5) ] = 
          array_combine2($cols, $vals );
    }
  } // End of parse
  
  function timecheck()
  {
    $now = time();
    while( list($stid, $ob) = each($this->db) )
    {
       $this->db[ $stid ]['iscurrent'] = (($now - $this->db[ $stid ]['ts']) < $this->tsthreshold) ? true : false;
    }
  } // End of timecheck

 /**
  * Compute a bunch of stuff
  */
  function metconvert()
  {
    
  }

} // End of currentdb
?>
