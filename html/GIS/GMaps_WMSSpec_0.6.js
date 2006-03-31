//==============================================================
//Spatial Data Logic - 2005
//WMS Map Spec
/*
James Fee and others have pointed out that Cubewerks is offering an add-on to their server product that offers Google Maps data as a WMS service. This is pretty cool but what I’d expect most developers want is the ability to display WMS layers directly in a GMap instance in the browser. Fortunately, this is not too difficult if we base a WMS map spec on the existing Street Map spec and then manipulate the URL returned for each map tile. IMO, this opens up a lot more data for use in simple GMaps applications.

Download the WMSSpec code
Example:
var wms = new WMSSpec(mapTypes[0], "http://wms.jpl.nasa.gov/wms.cgi?", "WMS Test", "modis", "default", "image/jpeg" );
Either add this to your array of specs passed to the GMap constructor or use the undocumented access to the array itself. (MyGMap.mapTypes[MyGMap.mapTypes.length] = wms;)

WMSSpec(baseSpec, baseURLtoWMSServer, name, layer, style, format);
1) the Google Maps Streets spec, usually the first spec in the array but it is also pointed to by the G_MAP_TYPE and _GOOGLE_MAP_TYPE variables
2) The URL to the WMS Server, don’t forget the “?” at the end
3) Name of service, used for labeling
4) Layer – the WMS layer name to use. This parameter is passed in whole to the server so it will accept whatever the WMS server is expecting
5) Style – the WMS style to use. See 4.
6) Format – the image format to return. (e.g. image/png, image/jpeg etc.)
*/
function WMSSpec(spec, baseUrl, name, layers, styles, format, overlay, copywrite)
{
    this.BaseSpec = spec;
    this.reverse = false;
    this.baseUrl = baseUrl;
    this.Map = map;
    this.tileSize = spec.tileSize;
    //this.tileSize=1024;
    this.backgroundColor = spec.backgroundColor;
    this.emptyTileURL = spec.emptyTileURL;
    this.waterTileUrl = spec.waterTileUrl;
    this.numZoomLevels = spec.numZoomLevels;
    this.pixelsPerDegree = spec.pixelsPerDegree;
    this.mapBounds = spec.mapBounds;
    this.ukBounds = spec.ukBounds;
    this.earthBounds = spec.earthBounds;
    this.Name = name;
    this.Layers = layers;
    this.Styles = styles;
    this.Format = format;
    this.copywrite = copywrite;
    this.overlay = overlay;

}

WMSSpec.prototype.adjustBitmapX = function(a, b)
{
    return this.BaseSpec.adjustBitmapX(a, b);
}

WMSSpec.prototype.getBitmapCoordinate = function(a, b, c, d)
{
    return this.BaseSpec.getBitmapCoordinate(a, b, c, d);
}

WMSSpec.prototype.getLatLng = function(a, b, c, d)
{
    return this.BaseSpec.getLatLng(a, b, c, d);
}

WMSSpec.prototype.getTileCoordinate = function(a, b, c, d)
{
    return this.BaseSpec.getTileCoordinate(a, b, c, d);
}


WMSSpec.prototype.getOverlayURL = function(a, b, c)
{
    //    var t=this.computeTile(a,b,c);
    //prompt("",t);
    //    return t;

    if (this.reverse) {
        var t = this.computeTile(a, b, c);
        var key = a + "|" + b + "|" + c;
        //        if (typeof(radarCache[key])=='undefined') {
        //            var img=new Image();
        //            img.src=t;
        //            radarCache[key]=img;
        //        }

        return t;
    }

    return this.BaseSpec.getOverlayURL(a, b, c);
}

WMSSpec.prototype.getTileURL = function(a, b, c)
{
    //    var t=this.BaseSpec.getTileURL(a,b,c);
    // alert(t);
    //    return t;

    if (this.reverse) return this.BaseSpecOverride.getTileURL(a, b, c);
    return this.computeTile(a, b, c);
}

WMSSpec.prototype.computeTile = function(a, b, c)
{
    var ts = this.tileSize;
    var ul = this.getLatLng(a * ts, (b + 1) * ts, c);
    var lr = this.getLatLng((a + 1) * ts, b * ts, c);
    var bbox = ul.x + "," + ul.y + "," + lr.x + "," + lr.y;
    var url = this.baseUrl + "version=1.1.1&request=GetMap&Layers=" + this.Layers + "&Styles=" + this.Styles + "&SRS=EPSG:4326&BBOX=" + bbox + "&width=" + ts + "&height=" + ts + "&format=" + this.Format + "&TRANSPARENT=true";
    //var url = this.baseUrl + "REQUEST=GetMap&SERVICE=WMS&VERSION=1.1.1&LAYERS=" + this.Layers + "&STYLES=" + this.Styles + "&FORMAT=" + this.Format + "&BGCOLOR=0x000000&TRANSPARENT=TRUE&SRS=EPSG:4326&BBOX=" + bbox + "&WIDTH=" + ts + "&HEIGHT=" + ts;
    return url;
}


WMSSpec.prototype.getLowestZoomLevel = function(a, b, c)
{
    return this.BaseSpec.getLowestZoomLevel(a, b, c);
}

WMSSpec.prototype.getPixelsPerDegree = function(a)
{
    return this.BaseSpec.getPixelsPerDegree(a);
}

WMSSpec.prototype.getLinkText = function()
{
    return this.Name;
}
WMSSpec.prototype.getShortLinkText = function()
{
    return this.Name;
}
WMSSpec.prototype.hasOverlay = function()
{
    return this.BaseSpec.hasOverlay();
}
WMSSpec.prototype.getURLArg = function()
{
    return this.BaseSpec.getURLArg();
}

WMSSpec.prototype.getCopyright = function()
{
    return this.copywrite;
    //return this.BaseSpec.getCopyright();
}

WMSSpec.prototype.zoomBitmapCoord = function(a, b, c)
{
    return this.BaseSpec.zoomBitmapCoord(a, b, c);
}


function m(a){return Math.round(a)+"px"}


// Handle mulitple overlays
 // d - no overlays
 //a - the tile
/*GMap.prototype.configureImage = function(a, b, c, d) {

    var e = (this.currentPanOffset.width + b) * this.spec.tileSize;
    var f = (this.currentPanOffset.height + c) * this.spec.tileSize;
    var g = -this.tilePaddingOffset.width + e;
    var h = -this.tilePaddingOffset.height + f;
    if (a.tileLeft != g || a.tileTop != h) {
        a.style.left = m(g);
        a.style.top = m(h);
        a.tileLeft = g;
        a.tileTop = h
    }
    if (!this.isLoaded()) {
        if (!d) {
            a.src = this.spec.emptyTileUrl
        }
    } else {
        var i = d?this.spec.getOverlayURL(this.topLeftTile.x + b, this.topLeftTile.y + c, this.zoomLevel):this.spec.getTileURL(this.topLeftTile.x + b, this.topLeftTile.y + c, this.zoomLevel);
        if (a.src != i) {
            if (d) {
                p.clearImage(a, this.spec.emptyTileUrl);
                p.setImage(a, i)
            } else {

                //if we have a double overlay, need to do this twice

                a.src = this.spec.emptyTileUrl;
                a.src = i;
                a.style.display = "";
                if (a.overlayImage) {
                    a.overlayImage.style.display = ""
                }
                if (a.errorTile) {
                    a.errorTile.style.display = "none"
                }
            }
        }
    }
}*/


var radarCache = {};
//var radarIndex = new Array("","-m05min","-m10m","-m15m","-m20m","-m25m","-m30m","-m35m","-m40m","-m45m");
var radarIndex = new Array("", "-m45m", "-m40m", "-m35m", "-m30m", "-m25m", "-m20m", "-m15m", "-m10m", "-m05min");


function loopRadar(i) {
    var dave = new WMSSpec(map.mapTypes[2], "http://mesonet.agron.iastate.edu/cgi-bin/wms/nexrad/n0r.cgi?", "Radar", "nexrad-n0r" + radarIndex[i], "", "image/png", "", "copyright dude");
    dave.reverse = true;
    dave.BaseSpecOverride = map.mapTypes[0];
    map.setMapType(dave);
    setTimeout(function() {
        loopRadar((i + 1) % 10);
    }, 1000);

}


var advMapsAdded = false;

function showAdvancedMaps() {


    if (advMapsAdded) return;
    //    var radTotalPcp = new WMSSpec(map.mapTypes[2], "http://mesonet.agron.iastate.edu/cgi-bin/wms/nexrad/ntp.cgi?", "Storm Pcp", "nexrad_stormtotal_precip", "", "image/png", "", "Data from <a target='_new' href='http://mesonet.agron.iastate.edu/'>IEM</a>");
    //    map.mapTypes.push(radTotalPcp)

    var satVis = new WMSSpec(map.mapTypes[2], "http://mesonet.agron.iastate.edu/cgi-bin/wms/goes/conus_vis.cgi?", "US Vis Sat", "goes_conus_vis", "", "image/png", "", "CONUS GOES Visible Satellite from <a target='_new' href='http://mesonet.agron.iastate.edu/'>IEM</a>");
    map.mapTypes.push(satVis)

    var satIr = new WMSSpec(map.mapTypes[2], "http://mesonet.agron.iastate.edu/cgi-bin/wms/goes/conus_ir.cgi?", "US IR Sat", "goes_conus_ir", "", "image/png", "", "CONUS GOES Infrared Satellite from <a target='_new' href='http://mesonet.agron.iastate.edu/'>IEM</a>");
    map.mapTypes.push(satIr)

    advMapsAdded = true;
    var ccenter = map.getCenterLatLng();
    map.recenterOrPanToLatLng(new GPoint(ccenter.x, ccenter.y - .1));
    map.recenterOrPanToLatLng(ccenter);

}