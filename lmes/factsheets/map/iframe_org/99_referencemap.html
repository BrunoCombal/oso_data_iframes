<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title></title>
    <link rel="stylesheet" href="http://onesharedocean.org/geoserver/openlayers/theme/default/style.css" type="text/css">
    <style>
      #map-id {
      width:600px;
      height: 300px;
      }
    </style>    
    <script src="http://openlayers.org/api/OpenLayers.js"></script>

    <script type="text/javascript">
    function init(){    
    var wfsUrl = 'http://onesharedocean.org/geoserver/wfs?service=wfs&version=1.0.0&request=GetFeature&typename=lmes:lmes66_pwp&outputFormat=GML2&cql_filter=LME_NUMBER=';

    var defaultExtent = new OpenLayers.Bounds(120,-16,200,13);
    var minResolution=360/600;
    var maxResolution=5/300;
    var options = {minResolution: minResolution, maxResolution:maxResolution, numZoomLevels:16,
    maxExtent:defaultExtent, controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar()]};
    var map = new OpenLayers.Map("map-id", options);

    var STILE=true;
    var TSIZE=new OpenLayers.Size(256,256);
    var WDL=true;
    var OCEANWMS='http://onesharedocean.org/geoserver/ocean/wms';
    var LMESWMS='http://onesharedocean.org/geoserver/lmes/wms';
    var GRLWMS='http://onesharedocean.org/geoserver/general/wms';

    var lmes=new OpenLayers.Layer.WMS(
		"LMEs", LMESWMS,
		{layers:'lmes:lmes66_pwp', transparent:true, styles:'lmes_grey_noname'},
		{singleTile:STILE, isBaseLayer:false, opacity:1, visibility:true,  wrapDateLine:WDL}
		);

    var lmesName=new OpenLayers.Layer.WMS(
		"LMEs", LMESWMS,
		{layers:'lmes:lmes66_pwp', transparent:true, styles:'lmes_names_id_noshape'},
		{singleTile:STILE, isBaseLayer:false, opacity:1, visibility:true,  wrapDateLine:WDL}
		);
		
    var countries = new OpenLayers.Layer.WMS(
		"Countries", GRLWMS,
		{layers:'general:world_epsg4326', transparent:true, styles:'countries_lightyellow_noname', bgcolor:'0xDCF0FA'},
		{transparent:true, isBaseLayer:true, visibility:true, opacity:1, wrapDateLine:WDL, singleTile:STILE}
		)

    var countriesNames = new OpenLayers.Layer.WMS(
		"Countries", GRLWMS,
		{layers:'general:world_epsg4326', transparent:true, styles:'countries_names_noshape'},
		{transparent:true, visibility:true, opacity:1, wrapDateLine:WDL, singleTile:STILE, ratio:1,yx:{'EPSG:4326':true}}
		)

    map.addLayers([lmes, countries, countriesNames, lmesName]);
		map.setLayerIndex(lmes, 0);
		map.setLayerIndex(countries, 2);
		map.setLayerIndex(countriesNames, 3);
		map.setLayerIndex(lmesName, 4);
		
	//map.addLayers([ lmes, countries]);
    var lmeID=99;
    var extent=OpenLayers.Request.GET({
                url: wfsUrl + lmeID,
                async:false,
                success: function(response) {
          	   var format = new OpenLayers.Format.GML();
                   var features = format.read(response.responseText);
		   // display this feature in green
		   var vectorLME = new OpenLayers.Layer.Vector('LME');
		   vectorLME.addFeatures(features[0]);
		   vectorLME.style = {strokeWidth:1, strokeColor:'#878889', fillColor:'#98E600', fillOpacity:0.4};
		   map.addLayers([vectorLME]);
		   map.setLayerIndex(lmes, 0);
		   map.setLayerIndex(vectorLME, 1);
		   map.setLayerIndex(countries, 2);
		   map.setLayerIndex(countriesNames, 3);
		   map.setLayerIndex(lmesName, 4);
		   // zoom to the extent
                   map.zoomToExtent(defaultExtent);
                   return features[0].geometry.getBounds();
                },
                failure: function() {
                   return defaultExtent;
                }
            });
}

</script>
  </head>


  <body onload="init();">

    <div style="float:left; clear:none; display:inline"> <!-- abstract containter -->
    <div id="map-id" style="float:left; margin:0; margin-right:0px; padding:0; display:inline"></div>
    <div style="float:right;  font-size:10px; max-width:280px; margin-left:0; padding-left:10px; display:inline; font-family:sans-serif;">
      <b>Pacific Warm pool</b> (<a target="_top" href="/?q=node/120">previous</a> <a target="_top" href="/?q=node/51">next</a>) <br/>
    </div>
    </div>

  </body>
  
</html>
