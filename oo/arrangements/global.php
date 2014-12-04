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

   var layersList=[ {"layer":"arrangements:Abidjan", "name":"Abidjan"}, {"layer":"arrangements:Antigua","name":"Antigua"},
                    {layer:"arrangements:RS_Baltic", name:"Baltic"},
                    {"layer":"arrangements:Barcelona", "name":"Barcelona"}, {"layer":"arrangements:Bucharest","name":"Bucharest"}, {"layer":"arrangements:Cartagena", "name":"Cartagena"},
                    {"layer":"arrangements:Helsinki","name":"Helsinki"}, {"layer":"arrangements:Jeddah","name":"Jeddah"},
                    {"layer":"arrangements:Kuwait","name":"Kuwait"}, {"layer":"arrangements:Lima","name":"Lima"},
                    {"layer":"arrangements:Nairobi","name":"Nairobi"},{"layer":"arrangements:Noumea","name":"Noumea"},
                    {"layer":"arrangements:Arctic", name:"Arctic"},
                    {layer:"arrangements:APFIC", name:"APFIC"},
                    {layer:"arrangements:ASCOBANS_simplified", name:"ASCOBANS"},
                    {layer:"arrangements:ATS", name:"ATS"},                 {layer:"arrangements:CCAS", name:"CCAS"},
                    {layer:"arrangements:OSPAR", name:"OSPAR"},                     {layer:"arrangements:PIF", name:"PIF"},
                    {layer:"arrangements:RFB_BOBP_IGO", name:"RFB_BOBP_IGO"},
                    {layer:"arrangements:RFB_CCAMLR", name:"CCAMLR"},{layer:"arrangements:RFB_CCBSP", name:"CCBSP"},
                    {layer:"arrangements:EU_simplified", name:"EU"},
                    {layer:"arrangements:RFB_CCSBT", name:"CCSBT"},
                    {layer:"arrangements:RFB_CECAF", name:"CECAF"},
                    {layer:"arrangements:RFB_COMHAFAT", name:"COMHAFAT"},
                    {layer:"arrangements:RFB_COREP", name:"COREP"},
                    {layer:"arrangements:RFB_CPPS", name:"CPPS"},
                    {layer:"arrangements:RFB_CRFM", name:"CRFM"},
                    {layer:"arrangements:RFB_FCWC", name:"FCWC"},
                    {layer:"arrangements:RFB_FFA", name:"FFA"},
                    {layer:"arrangements:RFB_GFCM", name:"GFCM"},
                    {layer:"arrangements:RFB_IATTC", name:"IATTC"},
                    {layer:"arrangements:RFB_ICCAT", name:"ICCAT"},
                    {layer:"arrangements:RFB_ICES", name:"ICES"},
                    {layer:"arrangements:RFB_IOTC", name:"IOTC"},
                    {layer:"arrangements:RFB_IPHC", name:"IPHC"},
                    {layer:"arrangements:RFB_NAFO", name:"NAFO"},
                    {layer:"arrangements:RFB_NAMMCO", name:"NAMMCO"},
                    {layer:"arrangements:RFB_NASCO", name:"NASCO"},
                    {layer:"arrangements:RFB_NEAFC", name:"NEAFC"},
                    {layer:"arrangements:RFB_NPAFC", name:"NPAFC"},
                    {layer:"arrangements:RFB_OLDEPESCA", name:"OLDEPESCA"},
                    {layer:"arrangements:RFB_OSPESCA", name:"OSPESCA"},
                    {layer:"arrangements:RFB_PICES", name:"PICES"},
                    {layer:"arrangements:RFB_PSC", name:"PSC"},
                    {layer:"arrangements:RFB_RECOFI", name:"RECOFI"},
                    {layer:"arrangements:RFB_SEAFDEC", name:"SEAFDEC"},
                    {layer:"arrangements:RFB_SEAFO", name:"SEAFO"},
                    {layer:"arrangements:RFB_SIOFA", name:"SIOFA"},
                    {layer:"arrangements:RFB_SPC", name:"SPC"},
                    {layer:"arrangements:RFB_SPRFMO", name:"SPRFMO"},
                    {layer:"arrangements:RFB_SRFC", name:"SRFC"},
                    {layer:"arrangements:RFB_SWIOFC", name:"SWIOFC"},
                    {layer:"arrangements:RFB_WCPFC", name:"WCPFC"},
                    {layer:"arrangements:RFB_WECAFC", name:"WECAFC"},
                    {layer:"arrangements:RS_East_Asian_Seas", name:"East Asian seas"},
                    {layer:"arrangements:RS_North_West_Pacific_UNEP", name:"North West Pacific (UNEP)"},
                    {layer:"arrangements:RS_South_Asian_Seas", name:"South Asian Seas"}
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


   //Solves the z-index issue with Drupal overlay
   // called after the map is instanciated
   jQuery('#map-id').find('div').first().css('z-index','-99');
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
   height:250px;
   font-family:Verdana, sans-serif;
   font-size:10px;
   font-weight:normal;
   -webkit-column-count: 5;
   -moz-column-count: 5;
   column-count: 5;
   -webkit-column-gap: 10px;
   -moz-column-gap: 10px;
   column-gap: 10px;
 }
 .dataLayersDiv label {
   font-family:Verdana, sans-serif;
   font-size:11px;
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


<div id="map-id" ></div>
<div style="clear:both"></div>

<table class="layersLegend">
  <tr>
    <td><span style="background-color:#AAAAAA; opacity:0.2; filter:alpha(opacity=20);">&nbsp;&nbsp;&nbsp;&nbsp;</span>1 layer</td>
    <td><span style="background-color:#AAAAAA; opacity:0.4; filter:alpha(opacity=40);">&nbsp;&nbsp;&nbsp;&nbsp;</span>2 layers</td>
    <td><span style="background-color:#AAAAAA; opacity:0.5; filter:alpha(opacity=60);">&nbsp;&nbsp;&nbsp;&nbsp;</span>3 layers</td>
    <td><span style="background-color:#AAAAAA; opacity:0.6; filter:alpha(opacity=80);">&nbsp;&nbsp;&nbsp;&nbsp;</span>4 layers</td>
    <td><span style="background-color:#AAAAAA; opacity:1; filter:alpha(opacity=100);">&nbsp;&nbsp;&nbsp;&nbsp;</span>5 layers or more</td>
  </tr>
</table>
<div id="layerswitcher" class="olControlLayerSwitcher"></div>
