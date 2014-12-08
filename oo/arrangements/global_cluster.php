<?php
drupal_add_js('sites/all/libraries/OpenLayers-2.13.1/OpenLayers.js');
?>

<link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css" />

<script>
 jQuery(document).ready(function(){

   var extent = new OpenLayers.Bounds(-180,-90,180,90);
   var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
   var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});

   var options = {resolutions:[0.8, 0.4, 0.2, 0.1, 0.05], numZoomLevels:5,
                  controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule]};

   var map = new OpenLayers.Map("map-id", options);
   layersSwitcher.maximizeControl();

   var GWC="http://onesharedocean.org/geoserver/gwc/service/wms";
   var TSIZE=new OpenLayers.Size(225, 225);
   var TORG=new OpenLayers.LonLat(-180.0, 90.0);
   //       "http://onesharedocean.org/geoserver/general/wms",     
   //       "http://onesharedocean.org/geoserver/arrangements/wms",
   var optionsBL={tiled:true, isBaseLayer:true, displayInLayerSwitcher:false, tileSize:TSIZE, tileOrigin:TORG, visibility:true, wrapDateLine:true};
   var optionsShow={tiled:true, isBaseLayer:false, tileSize:TSIZE, tileOrigin:TORG, visibility:true, wrapDateLine:true};
   var optionsHide={tiled:true, isBaseLayer:false, tileSize:TSIZE, tileOrigin:TORG, visibility:false, wrapDateLine:true};

   var layersList=[ {layer:"arrangements:cluster_northeast_atlantic", name:"Northeast Atlantic"},
		    {layer:"arrangements:cluster_northwest_atlantic",name:"Northwest_atlantic"},
		    {layer:"arrangements:cluster_western_central_atlantic", name:"Western Central Atlantic"},
		    {layer:"arrangements:cluster_eastern_central_south_atlantic",name:"Eastern Central and South Atlantic"},
		    {layer:"arrangements:cluster_northeast_pacific",name:"Northeast Pacific"},
		    {layer:"arrangements:cluster_southeast_pacific",name:"Southeast Pacific"},
		    {layer:"arrangements:cluster_northwest_pacific",name:"Northwest Pacific"},
		    {layer:"arrangements:cluster_pacific_islands", name:"Pacific Islands"},
		    {layer:"arrangements:cluster_southeast_asia",name:"Southeast Asia"},
		    {layer:"arrangements:cluster_eastern_indian_ocean",name:"Eastern Indian Ocean"},
		    {layer:"arrangements:cluster_western_indian_ocean",name:"Western Indian Ocean"},
		    {layer:"arrangements:cluster_baltic_sea", name:"Baltic Sea"},
		    {layer:"arrangements:cluster_mediterranean_sea", name:"Mediterranean Sea"},
		    {layer:"arrangements:cluster_black_sea", name:"Black Sea"},
		    {layer:"arrangements:cluster_arctic", name:"Arctic Ocean"},
		    {layer:"arrangements:cluster_southern_ocean", name:"Southern Ocean"}
   ];

   var layerIndex=0

   // top layers
   var world=new OpenLayers.Layer.WMS(
     "Countries (background)",
     GWC,
     {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname' , format:'image/png', transparent:true} ,
     optionsBL
   );
   map.addLayer(world);
   map.setLayerIndex(world,layerIndex);
   layerIndex+=1;

   createdLayers=[];
   ii=0;
   for (var ii in layersList) {
     thisObject=layersList[ii];
     createdLayers[ii] = new OpenLayers.Layer.WMS(
       thisObject["name"],GWC, {layers:thisObject["layer"], transparent:true,styles:'',format:'image/png'},optionsShow
     );
     map.addLayer(createdLayers[ii]);
     map.setLayerIndex(createdLayers[ii], layerIndex);
     layerIndex += 1;
     ii=ii+1;
   }

   // LMEs + warmpool = aggregation
   var lmes = new OpenLayers.Layer.WMS(
     "LMEs & warmpool", GWC,
     //"http://onesharedocean.org/geoserver/wms",
     {layers:"LME66_warmpool", transparent:true, styles:'lmes_nofill_contour_red_labels', format:'image/png'},
     optionsHide
   );
   map.addLayer(lmes);
   map.setLayerIndex(lmes, layerIndex);
   layerIndex += 1;

   var eez=new OpenLayers.Layer.WMS(
     "EEZ",
     GWC,
     {layers:"general:World_Maritime_Boundaries_v8", transparent:true, styles:'', format:'image/png'},
     optionsHide
   );
   map.addLayer(eez);
   map.setLayerIndex(lmes, layerIndex);
   layerIndex += 1;

   var worldtop=new OpenLayers.Layer.WMS(
     "Countries", GWC,
     {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
     optionsShow
   );
   map.addLayer(worldtop);
   map.setLayerIndex(worldtop, layerIndex);
   layerIndex += 1;

   map.zoomToExtent(extent);


   //Solves the z-index issue with Drupal overlay: canceled and replaced with fixing superfish z-index to a higher value
   // called after the map is instanciated
   //jQuery('#map-id').find('div').first().css('z-index','-99');
   //jQuery('#map-id').css('z-index','-2500');
 });
</script>



<style>
 #map-id {
   width: 900px;
   height: 450px;
 }


 #layerswitcher.olControlLayerSwitcher{
   font-size:12px !important;
   font-family:sans-serif !important;
   font-weight: normal;
   position:relative;
   top:0;
   left:0;
   padding:0;
   margin:0;
 }
 .dataLbl {
   visibility:hidden;
 }
 .olControlLayerSwitcher .layersDiv{
   background-color:#c0c0c0 !important;
   margin: 0.5em !important;
   width:900px; #12em;
   padding-top:0;
   padding:0!important;
   margin:0!important;
 }
 #infoArrangement, #infoGeneral{
   font-size:12px !important;
   font-weight:normal;
   font-family:sans-serif !important;
 }
 .dataLayersDiv {
   width:900px;
   height:150px;
   font-family:Verdana, sans-serif;
   font-size:12px;
   font-weight:normal;
   -webkit-column-count: 3;
   -moz-column-count: 3;
   column-count: 3;
   -webkit-column-gap: 10px;
   -moz-column-gap: 10px;
   column-gap: 10px;
 }
 .dataLayersDiv label {
   font-family:Verdana, sans-serif;
   font-size:12px;
   font-weight:normal;
   display:inline;
   }
 .layersLegend tr, td{
   font-family:Verdana, sans-serif;
   font-size:12px;
   font-weigth:normal;
   padding-right:1em;
 }
 .layersLegend tbody {
   border:0;
 }
</style>


<div id="layerswitcher" class="olControlLayerSwitcher"></div>
<table class="layersLegend">
  <tr>
    <td><span style="background-color:#AAAAAA; opacity:0.2; filter:alpha(opacity=20);">&nbsp;&nbsp;&nbsp;&nbsp;</span>1 arrangement</td>
    <td><span style="background-color:#AAAAAA; opacity:0.4; filter:alpha(opacity=40);">&nbsp;&nbsp;&nbsp;&nbsp;</span>2 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:0.5; filter:alpha(opacity=60);">&nbsp;&nbsp;&nbsp;&nbsp;</span>3 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:0.6; filter:alpha(opacity=80);">&nbsp;&nbsp;&nbsp;&nbsp;</span>4 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:1; filter:alpha(opacity=100);">&nbsp;&nbsp;&nbsp;&nbsp;</span>5 arrangements or more</td>
  </tr>
</table>
<div style="clear:both"></div>
<div id="map-id" ></div>