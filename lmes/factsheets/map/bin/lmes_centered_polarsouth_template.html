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
      width: WIDTHTOREPLACEpx;
      height: HEIGHTTOREPLACEpx;
      }
    </style>    
    <script src="http://openlayers.org/api/OpenLayers.js"></script>

	<script type="text/javascript">
	function init(){    

		var wfsUrl = 'http://onesharedocean.org/geoserver/wfs?service=wfs&version=1.0.0&request=GetFeature&typename=general:LMEs_south_epsg_3031&outputFormat=GML2&cql_filter=LME_NUMBER=';

		var defaultExtent = new OpenLayers.Bounds(-4700000, -4700000, 4700000, 4700000);
		var options = {maxExtent:defaultExtent,  minResolution: 30000, maxResolution: 2000, projection:'EPSG:3031', units:'m', 
				controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar()]};
		var map = new OpenLayers.Map("map-id", options);

		var countries = new OpenLayers.Layer.WMS(
			"Countries", 'http://onesharedocean.org/geoserver/general/wms',
			{layers:'general:Countries_south_epsg_3031', transparent:true, styles:'countries_lightyellow_noname', bgcolor:'0xDCF0FA'},
			{transparent:true, isBaseLayer:true, visibility:true, opacity:1, wrapDateLine:true, singleTile:true}
		)
		var countriesNames = new OpenLayers.Layer.WMS(
			"Countries Names", 'http://onesharedocean.org/geoserver/general/wms',
			{layers:'general:Countries_south_epsg_3031', transparent:true, styles:'countries_names_noshape'},
			{transparent:true, visibility:true, opacity:1, wrapDateLine:true, singleTile:true}
		)
		
		var lmesName=new OpenLayers.Layer.WMS(
			"LME names", 'http://onesharedocean.org/geoserver/general/wms',
			{layers:'general:LMEs_south_epsg_3031', transparent:true, styles:'lmes_names_id_noshape'},
			{singleTile:true, isBaseLayer:false, opacity:1, visibility:true,  wrapDateLine:true}
		);

		map.addLayers([ countries, countriesNames, lmesName]);
		map.setLayerIndex(countries, 0);
		map.setLayerIndex(countriesNames, 1);
		map.setLayerIndex(lmesName, 2);
		
    var lmeID=61;
    var extent=OpenLayers.Request.GET({
                url: wfsUrl + lmeID,
                async:false,
                success: function(response) {
				var format = new OpenLayers.Format.GML();
				var features = format.read(response.responseText);
				//display this feature in green
		 		var vectorLME = new OpenLayers.Layer.Vector('LME');
				vectorLME.addFeatures(features[0]);
				vectorLME.style={strokeWidth:1, strokeColor:'#878889', fillColor:'#98E600', fillOpacity:0.4};
				map.addLayers([vectorLME]);
				map.setLayerIndex(vectorLME, 0);
				map.setLayerIndex(countries, 1);
				map.setLayerIndex(countriesNames, 2);
				map.setLayerIndex(lmesName, 3)
                                map.zoomToExtent(features[0].geometry.getBounds());
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
      <b>LME #LMECODETOREPLACE</b> (<a target="_top" href="/?q=node/NODEPREV">previous</a> <a target="_top" href="/?q=node/NODENEXT">next</a>) <br/>
      COUNTRYTOREPLACE<br/>
      <b>Area:</b> AREATOREPLACE km<sup>2</sup><br/>
    </div>
    </div>

  </body>
  
</html>
