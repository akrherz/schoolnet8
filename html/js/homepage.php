<?php
header("Content-type: text/javascript");
include("../../config/settings.inc.php");
?>
var selectedSite;
var map;

function updater(){
  var d = new Date();
  document.getElementById("lsdimage").innerHTML = '<a href="<?php echo $baseurl; ?>/site.phtml?station='+ selectedSite +'"><img src="<?php echo $baseurl; ?>static/radar/'+ selectedSite +'/'+ selectedSite +'_0.png?'+ d.getTime() +'" border="1" width="320" height="240" alt="Live Super Doppler" style=\"float: right;\"/></a><br />Click on map for more details.';
  document.getElementById("webcamimage").innerHTML = '<a href="<?php echo $baseurl; ?>/camera/"><img src="<?php echo $baseurl; ?>camera/stills/'+ eval("webcamlookup."+ selectedSite ) +'.jpg?'+ d.getTime() +'" border="1" width="320" height="240" alt="Webcam" style=\"float: right;\"/></a><br />Click on image for other cameras.';

};

function myrefresh(layer){
  if (layer.visibility){
   layer.moveTo(layer.map.getExtent(), true);
  }
}

 function cb_siteOver(feature){
  selectedSite = feature.attributes.sid;
  updater();
 };

 function cb_siteOut(feature){
   // map.removePopup(feature.popup);
  //document.getElementById("sname").innerHTML = "No Site Selected";
  //  feature.popup.destroy();
  //  feature.popup = null;
 };



function olinit(){

  // Build Map Object
  map = new OpenLayers.Map( 'map',{
        projection: new OpenLayers.Projection('EPSG:900913'),
        displayProjection: new OpenLayers.Projection('EPSG:4326'),
        units: 'm',
        wrapDateLine: false,
        numZoomLevels: 18,
        maxResolution: 156543.0339,
        maxExtent: new OpenLayers.Bounds(-20037508, -20037508,
                                         20037508, 20037508.34)
  });

   var styleMap = new OpenLayers.StyleMap({
       'default': {
           fillColor: 'black',
           strokeColor: 'yellow',
           strokeWidth: 2,
           pointRadius: 5,
           strokeOpacity: 1
       },
       'select': {
          fillOpacity: 1,
          strokeColor: 'white',
          fillColor: 'red'
       }
   });


  // Traditional Google Map Layer
  var googleLayer = new OpenLayers.Layer.Google(
                'Google Streets',
                 {'sphericalMercator': true}
            );
  googleLayer.setVisibility(true);

  var superdoppler = new OpenLayers.Layer.WMS.Untiled("KCCI Super DopplerHD",
     "http://mesonet.agron.iastate.edu/cgi-bin/wms/iowa/kcci.cgi?",
      {layers:"kccidoppler",
       transparent:true,
       format:'image/png'
      }
   );
   superdoppler.setVisibility(true);

   // NEXRAD Composite Layer.
   var nexrad = new OpenLayers.Layer.WMS( 'US NEXRAD',
     'http://mesonet.agron.iastate.edu/cgi-bin/wms/nexrad/n0r.cgi?', 
     {layers: 'nexrad-n0r', format: 'image/png', transparent: 'true'});
   nexrad.setVisibility(false);


  var geojson = new OpenLayers.Layer.GML("KCCI SchoolNet8", 
    "<?php echo $baseurl; ?>geojson/kcci.txt",
            {
                projection: new OpenLayers.Projection('EPSG:4326'),
                format: OpenLayers.Format.GeoJSON, 
                styleMap: styleMap
             });
  geojson.setVisibility(true);
  map.addLayers([googleLayer,superdoppler, nexrad, geojson]);

  selectControl = new OpenLayers.Control.SelectFeature(geojson, {
       onSelect: cb_siteOver, 
       onUnselect: cb_siteOut
   });
   map.addControl(selectControl);
   selectControl.activate();


   var proj = new OpenLayers.Projection('EPSG:4326');
   var proj2 = new OpenLayers.Projection('EPSG:900913');
   var point = new OpenLayers.LonLat(-93.8, 41.8);
   point.transform(proj, proj2);

   map.setCenter(point, 7);
   map.addControl( new OpenLayers.Control.LayerSwitcher({id:'ls'}) );

   window.setInterval(myrefresh, 300876,superdoppler);
   window.setInterval(myrefresh, 301876,nexrad);
   window.setInterval(updater, 60100);




}; /* End of olinit() */
