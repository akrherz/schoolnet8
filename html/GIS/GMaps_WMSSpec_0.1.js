//==============================================================
//Spatial Data Logic - 2005
//WMS Map Spec
function WMSSpec (spec, baseUrl, name, layers, styles, format)
{
	this.BaseSpec = spec;
	this.baseUrl = baseUrl;
	this.Map = map;
    this.tileSize=spec.tileSize;
    this.backgroundColor=spec.backgroundColor;
    this.emptyTileURL=spec.emptyTileURL;
	this.waterTileUrl=spec.waterTileUrl;
    this.numZoomLevels=spec.numZoomLevels;
    this.pixelsPerDegree=spec.pixelsPerDegree;
    this.mapBounds=spec.mapBounds;
    this.ukBounds=spec.ukBounds;
    this.earthBounds=spec.earthBounds;
	this.Name = name;
	this.Layers = layers;
	this.Styles = styles;
	this.Format = format;
	
}

WMSSpec.prototype.adjustBitmapX=function(a,b)
{
    return this.BaseSpec.adjustBitmapX(a,b);
}

WMSSpec.prototype.getBitmapCoordinate=function(a,b,c,d)
{
    return this.BaseSpec.getBitmapCoordinate(a,b,c,d);
}

WMSSpec.prototype.getLatLng=function(a,b,c,d)
{
    return this.BaseSpec.getLatLng(a,b,c,d);
}

WMSSpec.prototype.getTileCoordinate=function(a,b,c,d)
{
    return this.BaseSpec.getTileCoordinate(a,b,c,d);
}


WMSSpec.prototype.getTileURL=function(a,b,c)
{
	var ts = this.tileSize;
	var ul = this.getLatLng(a*ts,(b+1)*ts, c);
	var lr = this.getLatLng((a+1)*ts, b*ts, c);
	var bbox = "BBOX=" + ul.x + "," + ul.y + "," + lr.x + "," + lr.y;
	var url = this.baseUrl + "REQUEST=GetMap&SERVICE=WMS&VERSION=1.1.1&LAYERS=" + this.Layers + "&STYLES=" + this.Styles + "&FORMAT=" + this.Format + "&BGCOLOR=0xFFFFFF&TRANSPARENT=TRUE&SRS=EPSG:4326&" + bbox + "&WIDTH=" + ts + "&HEIGHT=" + ts;
	return url;
}

WMSSpec.prototype.getLowestZoomLevel=function(a,b,c)
{
    return this.BaseSpec.getLowestZoomLevel(a,b,c);
}

WMSSpec.prototype.getPixelsPerDegree=function(a)
{
   return this.BaseSpec.getPixelsPerDegree(a);
}

WMSSpec.prototype.getLinkText=function()
{
    return this.Name;
}
WMSSpec.prototype.getShortLinkText=function()
{
    return this.Name;
}
WMSSpec.prototype.hasOverlay=function()
{
    return false;
}
WMSSpec.prototype.getURLArg=function()
{
    return this.BaseSpec.getURLArg();
}

WMSSpec.prototype.getCopyright=function()
{
    return this.BaseSpec.getCopyright();
}

WMSSpec.prototype.zoomBitmapCoord=function(a,b,c)
{
    return this.BaseSpec.zoomBitmapCoord(a,b,c);
}