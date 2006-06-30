<?php
/* SQL 
select '"'||id||'" => array("short" => "'||plot_name||'", "city" => "'||name||'", "lat" => '||latitude||', "lon" => '||longitude||', "online" => true, "nwn_id" => '||nwn_id||', "climate_site" => "'||climate_site||'"),' from stations WHERE network = 'KCCI' ORDER by id ASC;

*/
$Scities = array(
// "S03I4" => array("short" => "State Fair", "city" => "Iowa State Fair 2003", "lat" => 41.59611, "lon" => -93.555, "online" => false, "nwn_id" => 899, "climate_site" => "IA0241"),
 "SADI4" => array("short" => "Adair", "city" => "Adair Casey", "lat" => 41.51995, "lon" => -94.5838166667, "online" => true, "nwn_id" => 43, "climate_site" => "IA3509"),
 "SAEI4" => array("short" => "Adel", "city" => "Adel / ADM MS", "lat" => 41.6182166667, "lon" => -94.0225833333, "online" => true, "nwn_id" => 51, "climate_site" => "IA6566"),
 "SAFI4" => array("short" => "Afton", "city" => "Afton / East Union", "lat" => 41.0250833333, "lon" => -94.1804333333, "online" => true, "nwn_id" => 44, "climate_site" => "IA5769"),
 "SAGI4" => array("short" => "Algona", "city" => "Algona", "lat" => 43.0706, "lon" => -94.2289, "online" => true, "nwn_id" => 128, "climate_site" => "IA0133"),
 "SAKI4" => array("short" => "Ankeny", "city" => "Ankeny / Christian Academy", "lat" => 41.7323333333, "lon" => -93.62275, "online" => true, "nwn_id" => 57, "climate_site" => "IA0241"),
 "SALI4" => array("short" => "Albia", "city" => "Albia Lincoln Center", "lat" => 41.0290833333, "lon" => -92.8054166667, "online" => true, "nwn_id" => 83, "climate_site" => "IA0112"),
 "SAMI4" => array("short" => "Ames", "city" => "Ames / St Cecilia", "lat" => 42.0503516667, "lon" => -93.6186833333, "online" => true, "nwn_id" => 82, "climate_site" => "IA0200"),
 "SATI4" => array("short" => "Anita", "city" => "Anita", "lat" => 41.4535, "lon" => -94.7691, "online" => true, "nwn_id" => 148, "climate_site" => "IA0364"),
 "SAUI4" => array("short" => "Audubon", "city" => "Audubon Community", "lat" => 41.7125833333, "lon" => -94.9222833333, "online" => true, "nwn_id" => 72, "climate_site" => "IA0385"),
 "SBDI4" => array("short" => "Bedford", "city" => "Bedford", "lat" => 40.6721666667, "lon" => -94.7236333333, "online" => true, "nwn_id" => 123, "climate_site" => "IA0576"),
 "SBKI4" => array("short" => "Belmond", "city" => "Belmond Klemme", "lat" => 42.8497666667, "lon" => -93.6037, "online" => true, "nwn_id" => 92, "climate_site" => "IA1541"),
 "SBMI4" => array("short" => "Bloomfield", "city" => "Davis County / Bloomfield", "lat" => 40.7506, "lon" => -92.4136, "online" => true, "nwn_id" => 124, "climate_site" => "IA0753"),
 "SBOI4" => array("short" => "Boone", "city" => "Boone / United Community", "lat" => 42.02555, "lon" => -93.77575, "online" => true, "nwn_id" => 55, "climate_site" => "IA0200"),
 "SBRI4" => array("short" => "Brooklyn", "city" => "Brooklyn / BGM", "lat" => 41.73895, "lon" => -92.4466833333, "online" => true, "nwn_id" => 86, "climate_site" => "IA0600"),
 "SBXI4" => array("short" => "Baxter", "city" => "Baxter", "lat" => 41.8264, "lon" => -93.1513, "online" => true, "nwn_id" => 238, "climate_site" => "IA5992"),
 "SBZI4" => array("short" => "B. P. Zoo", "city" => "Blank Park Zoo, DSM", "lat" => 41.5255, "lon" => -93.6214, "online" => true, "nwn_id" => 133, "climate_site" => "IA0241"),
 "SCAI4" => array("short" => "Carroll", "city" => "Carroll / Kuemper Catholic", "lat" => 42.0749666667, "lon" => -94.8701333333, "online" => true, "nwn_id" => 62, "climate_site" => "IA1233"),
 "SCBI4" => array("short" => "Coon Rapids", "city" => "Coon Rapids / Bayard", "lat" => 41.87865, "lon" => -94.6811333333, "online" => true, "nwn_id" => 73, "climate_site" => "IA1233"),
 "SCDI4" => array("short" => "Corydon", "city" => "Wayne Community / Corydon", "lat" => 40.7516166667, "lon" => -93.3213666667, "online" => true, "nwn_id" => 125, "climate_site" => "IA1394"),
 "SCEI4" => array("short" => "Centerville", "city" => "Centerville", "lat" => 40.7308, "lon" => -92.8726, "online" => true, "nwn_id" => 150, "climate_site" => "IA1354"),
 "SCGI4" => array("short" => "Clarion", "city" => "Clarion / Clarion Goldfield", "lat" => 42.7349333333, "lon" => -93.7297666667, "online" => true, "nwn_id" => 50, "climate_site" => "IA1541"),
 "SCHI4" => array("short" => "Chariton", "city" => "Chariton / Van Allen Elem", "lat" => 41.0234666667, "lon" => -93.3117333333, "online" => true, "nwn_id" => 53, "climate_site" => "IA1394"),
 "SCNI4" => array("short" => "Corning", "city" => "Corning Community", "lat" => 40.9917, "lon" => -94.73975, "online" => true, "nwn_id" => 79, "climate_site" => "IA1833"),
 "SCOI4" => array("short" => "Colo", "city" => "Colo Elem", "lat" => 42.0107, "lon" => -93.3182666667, "online" => true, "nwn_id" => 67, "climate_site" => "IA5198"),
 "SCSI4" => array("short" => "Creston", "city" => "Creston HS", "lat" => 41.0731833333, "lon" => -94.3695833333, "online" => true, "nwn_id" => 68, "climate_site" => "IA3438"),
 "SDRI4" => array("short" => "River Woods", "city" => "Des Moines / River Woods", "lat" => 41.5578833333, "lon" => -93.57745, "online" => true, "nwn_id" => 60, "climate_site" => "IA0241"),
 "SEGI4" => array("short" => "Eagle Grove", "city" => "Eagle Grove", "lat" => 42.6653, "lon" => -93.9124, "online" => true, "nwn_id" => 171, "climate_site" => "IA1541"),
 "SFAI4" => array("short" => "Farnhamville", "city" => "Farnhamville / Prairie Valley", "lat" => 42.33715, "lon" => -94.41475, "online" => true, "nwn_id" => 84, "climate_site" => "IA7161"),
 "SFDI4" => array("short" => "Fort Dodge", "city" => "Fort Dodge", "lat" => 42.52162, "lon" => -94.18518, "online" => true, "nwn_id" => 149, "climate_site" => "IA2999"),
 "SFOI4" => array("short" => "Fontanelle", "city" => "Fontanelle / Nodaway Valley", "lat" => 41.2884333333, "lon" => -94.5663833333, "online" => true, "nwn_id" => 40, "climate_site" => "IA3438"),
 "SGLI4" => array("short" => "Glidden", "city" => "Glidden Ralston", "lat" => 42.0618833333, "lon" => -94.7274333333, "online" => true, "nwn_id" => 63, "climate_site" => "IA1233"),
 "SGRI4" => array("short" => "Grimes", "city" => "Grimes Elementary", "lat" => 41.685, "lon" => -93.79585, "online" => true, "nwn_id" => 39, "climate_site" => "IA0241"),
 "SHUI4" => array("short" => "Humboldt", "city" => "Humboldt / Taft Elem", "lat" => 42.7158833333, "lon" => -94.2286166667, "online" => true, "nwn_id" => 59, "climate_site" => "IA3980"),
 "SIAI4" => array("short" => "ICA", "city" => "Iowa Christian", "lat" => 41.5800333333, "lon" => -93.7393333333, "online" => true, "nwn_id" => 93, "climate_site" => "IA0241"),
 "SIFI4" => array("short" => "Iowa Falls", "city" => "Iowa Falls / Rock Run Elem", "lat" => 42.5296, "lon" => -93.2652833333, "online" => true, "nwn_id" => 77, "climate_site" => "IA4142"),
 "SINI4" => array("short" => "Indianola", "city" => "Indianola / Emerson Elem", "lat" => 41.36505, "lon" => -93.548, "online" => true, "nwn_id" => 38, "climate_site" => "IA4063"),
 "SJCI4" => array("short" => "Jordan Creek", "city" => "Jordan Creek", "lat" => 41.5595, "lon" => -93.7583, "online" => true, "nwn_id" => 155, "climate_site" => "IA0241"),
 "SJEI4" => array("short" => "Jefferson", "city" => "Jefferson Scranton HS", "lat" => 42.0023833333, "lon" => -94.3755833333, "online" => true, "nwn_id" => 37, "climate_site" => "IA4228"),
 "SJWI4" => array("short" => "South Hamilton", "city" => "Jewell / South Hamilton", "lat" => 42.3033, "lon" => -93.6475666667, "online" => true, "nwn_id" => 85, "climate_site" => "IA8806"),
 "SKCI4" => array("short" => "KCCI", "city" => "Des Moines / KCCI Studios", "lat" => 41.5929, "lon" => -93.6298666667, "online" => true, "nwn_id" => 47, "climate_site" => "IA0241"),
 "SKNI4" => array("short" => "Knoxville", "city" => "Knoxville / West Elem", "lat" => 41.3167666667, "lon" => -93.1156166667, "online" => true, "nwn_id" => 81, "climate_site" => "IA4502"),
 "SLEI4" => array("short" => "Leon", "city" => "Leon", "lat" => 40.7473833333, "lon" => -93.74105, "online" => true, "nwn_id" => 94, "climate_site" => "IA6316"),
 "SLMI4" => array("short" => "Latimer", "city" => "Latimer / CAL Community", "lat" => 42.7520833333, "lon" => -93.3686, "online" => true, "nwn_id" => 70, "climate_site" => "IA3584"),
 "SLOI4" => array("short" => "Lamoni", "city" => "Lamoni Community ", "lat" => 40.62535, "lon" => -93.9365, "online" => true, "nwn_id" => 75, "climate_site" => "IA5769"),
 "SLUI4" => array("short" => "LuVerne", "city" => "LuVerne", "lat" => 42.91242, "lon" => -94.08588, "online" => true, "nwn_id" => 147, "climate_site" => "IA3980"),
 "SMAI4" => array("short" => "Marshalltown", "city" => "Fisher Elem _ Marshalltown", "lat" => 42.0245166667, "lon" => -92.9201666667, "online" => true, "nwn_id" => 54, "climate_site" => "IA5198"),
 "SMDI4" => array("short" => "Madrid", "city" => "Madrid", "lat" => 41.8832, "lon" => -93.8135, "online" => true, "nwn_id" => 170, "climate_site" => "IA0200"),
 "SMLI4" => array("short" => "Mallard", "city" => "Mallard", "lat" => 42.9348833333, "lon" => -94.6821166667, "online" => true, "nwn_id" => 78, "climate_site" => "IA2689"),
 "SMNI4" => array("short" => "Montezuma", "city" => "Montezuma Community", "lat" => 41.5895333333, "lon" => -92.52445, "online" => true, "nwn_id" => 74, "climate_site" => "IA3473"),
 "SMOI4" => array("short" => "Mount Ayr", "city" => "Mount Ayr Community", "lat" => 40.7160166667, "lon" => -94.2319166667, "online" => true, "nwn_id" => 76, "climate_site" => "IA5769"),
 "SMSI4" => array("short" => "NW Webster", "city" => "Manson NW Webster", "lat" => 42.5045833333, "lon" => -94.3626333333, "online" => true, "nwn_id" => 109, "climate_site" => "IA2999"),
 "SMUI4" => array("short" => "Murray", "city" => "Murray", "lat" => 41.03885, "lon" => -93.95392, "online" => true, "nwn_id" => 122, "climate_site" => "IA6316"),
 "SNEI4" => array("short" => "Newton", "city" => "Newton / Berg MS", "lat" => 41.7037166667, "lon" => -93.02815, "online" => true, "nwn_id" => 56, "climate_site" => "IA5992"),
 "SNVI4" => array("short" => "Nevada", "city" => "Nevada Community", "lat" => 42.02185, "lon" => -93.4395333333, "online" => true, "nwn_id" => 121, "climate_site" => "IA0200"),
 "SOCI4" => array("short" => "Oskaloosa", "city" => "Oskaloosa Christian", "lat" => 41.3018, "lon" => -92.6503, "online" => true, "nwn_id" => 129, "climate_site" => "IA6327"),
 "SOGI4" => array("short" => "Ogden", "city" => "Ogden", "lat" => 42.0411, "lon" => -94.0308, "online" => true, "nwn_id" => 90, "climate_site" => "IA6566"),
 "SOSI4" => array("short" => "Osceola", "city" => "Osceola / Clarke Community", "lat" => 41.0322, "lon" => -93.7587166667, "online" => true, "nwn_id" => 41, "climate_site" => "IA6316"),
 "SPAI4" => array("short" => "Panora", "city" => "Panora / Panorama", "lat" => 41.6958666667, "lon" => -94.3749166667, "online" => true, "nwn_id" => 58, "climate_site" => "IA3509"),
 "SPEI4" => array("short" => "Pella", "city" => "Pella MS", "lat" => 41.4061666667, "lon" => -92.90015, "online" => true, "nwn_id" => 52, "climate_site" => "IA4502"),
 "SPKI4" => array("short" => "North Polk", "city" => "North Polk / West Elem", "lat" => 41.778, "lon" => -93.7181, "online" => true, "nwn_id" => 151, "climate_site" => "IA0241"),
 "SPYI4" => array("short" => "Perry", "city" => "Perry", "lat" => 41.8408166667, "lon" => -94.0798333333, "online" => true, "nwn_id" => 91, "climate_site" => "IA6566"),
 "SROI4" => array("short" => "Rockwell City", "city" => "Rockwell City", "lat" => 42.3922166667, "lon" => -94.6439166667, "online" => true, "nwn_id" => 61, "climate_site" => "IA7161"),
 "SSTI4" => array("short" => "Stuart", "city" => "Stuart", "lat" => 41.5039, "lon" => -94.3183, "online" => true, "nwn_id" => 239, "climate_site" => "IA3509"),
 "SSUI4" => array("short" => "Sully", "city" => "Sully Christian", "lat" => 41.5736166667, "lon" => -92.8387, "online" => true, "nwn_id" => 80, "climate_site" => "IA3473"),
 "STQI4" => array("short" => "Meskwaki SS Tama", "city" => "Meskwaki Settlement School Tama", "lat" => 41.99262, "lon" => -92.6465, "online" => true, "nwn_id" => 173, "climate_site" => "IA8296"),
 "SUNI4" => array("short" => "Union", "city" => "BCLUW MS Union", "lat" => 42.2438, "lon" => -93.0675, "online" => true, "nwn_id" => 172, "climate_site" => "IA5198"),
 "SWAI4" => array("short" => "Wall Lake", "city" => "Wall Lake-View-Auburn", "lat" => 42.27195, "lon" => -95.0950833333, "online" => true, "nwn_id" => 71, "climate_site" => "IA7312"),
 "SWBI4" => array("short" => "Webster City", "city" => "Webster City Community", "lat" => 42.4654333333, "lon" => -93.8221666667, "online" => true, "nwn_id" => 69, "climate_site" => "IA8806"),
 "SWII4" => array("short" => "Winterset", "city" => "Winterset Community", "lat" => 41.3334166667, "lon" => -94.0148, "online" => true, "nwn_id" => 42, "climate_site" => "IA9132"),
);

function find_nwsli($id)
{
  reset($Scities);
  while( list($k,$d) = each($Scities))
  {
    if (intval($id) == $d["nwn_id"]) return $id;
  }
  return "";
}

?>
