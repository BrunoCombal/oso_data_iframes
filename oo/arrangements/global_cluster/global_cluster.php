<?php
drupal_add_js('sites/all/libraries/OpenLayers-2.13.1/OpenLayers.js');

$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>

<link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css" />

<style>
 div.olControlAttribution{
   font-family:Verdana;
   font-size:10px;
   bottom:3px;
   background-color:#e4e4e4;
   opacity:0.7;
   filter:alpha(opacity=70);
 }
</style>

<script>
 jQuery(document).ready(function(){

   var layersList=[ {layer:"arrangements:cluster_northeast_atlantic", name:"Northeast Atlantic", node:"155",layerId:"ne_atlantic"},
                    {layer:"arrangements:cluster_northwest_atlantic_v2",name:"Northwest Atlantic", node:"154", layerId:"nw_atlantic"},
                    {layer:"arrangements:cluster_western_central_atlantic_v2", name:"Western Central Atlantic", node:"156",layerId:"wc_atlantic"},
                    {layer:"arrangements:cluster_eastern_central_south_atlantic_v2",name:"Eastern Central and South Atlantic", node:'157',layerId:"ecs_atlantic"},
                    {layer:"arrangements:cluster_northeast_pacific_v2",name:"Northeast Pacific", node:'158',layerId:"ne_pacific"},
                    {layer:"arrangements:cluster_southeast_pacific_v2",name:"Southeast Pacific", node:'159',layerId:"se_pacific"},
                    {layer:"arrangements:cluster_northwest_pacific",name:"Northwest Pacific", node:'230',layerId:"sw_pacific"},
                    {layer:"arrangements:cluster_pacific_islands_v2", name:"Pacific Islands", node:'160',layerId:"pacific_islands"},
                    {layer:"arrangements:cluster_southeast_asia",name:"Southeast Asia", node:'161',layerId:"se_asia"},
                    {layer:"arrangements:cluster_eastern_indian_ocean",name:"Eastern Indian Ocean", node:'162',layerId:"e_indian"},
                    {layer:"arrangements:cluster_western_indian_ocean",name:"Western Indian Ocean", node:'163',layerId:"w_indian"},
                    {layer:"arrangements:cluster_baltic_sea", name:"Baltic Sea", node:'153',layerId:"baltic_sea"},
                    {layer:"arrangements:cluster_mediterranean_sea", name:"Mediterranean Sea", node:'150',layerId:"mediterranean_sea"},
                    {layer:"arrangements:cluster_black_sea", name:"Black Sea", node:'151',layerId:"black_sea"},
                    {layer:"arrangements:cluster_arctic", name:"Arctic Ocean", node:'164',layerId:"arctic"},
                    {layer:"arrangements:cluster_southern_ocean", name:"Southern Ocean", node:'165',layerId:"southern"}
   ];

   <?php if(!$geoserver_on){ ?>
   //PLACE SOMETHING HERE
   jQuery('#clearAll').css('display', 'none');
   jQuery('.ulColumns input').css('display', 'none');
   jQuery('.outerLsControls').css('display', 'none');
   for (var ii in layersList) {
     thisObject=layersList[ii];
     jQuery('#'+thisObject["layerId"]).find('a').attr('href','http://onesharedocean.org/node/'+thisObject["node"]);
   }
   <?php } else { ?>

   var extent = new OpenLayers.Bounds(30,-90,390,90);
   var viewInit = extent;
   var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
   var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"},layerId:'grid'});

   var options = {resolutions:[0.8, 0.4, 0.2, 0.1, 0.05], numZoomLevels:5,
                  controls:[new OpenLayers.Control.PanZoom(),
                            new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule,
                            new OpenLayers.Control.Attribution()]};

   var map = new OpenLayers.Map("map-id", options);
   layersSwitcher.maximizeControl();

   var GWC="http://onesharedocean.org/geoserver/gwc/service/wms";
   var TSIZE=new OpenLayers.Size(225, 225);
   var TORG=new OpenLayers.LonLat(-180.0, 90.0);
   GWCGNRL = GWC
   //       "http://onesharedocean.org/geoserver/general/wms",     
   GWCARR = GWC
   //       "http://onesharedocean.org/geoserver/arrangements/wms",
   var optionsBL={tiled:true, isBaseLayer:true, displayInLayerSwitcher:false, tileSize:TSIZE, tileOrigin:TORG, visibility:true, wrapDateLine:true};
   var optionsShow={tiled:true, isBaseLayer:false, tileSize:TSIZE, tileOrigin:TORG, visibility:true, wrapDateLine:true};
   var optionsHide={tiled:true, isBaseLayer:false, tileSize:TSIZE, tileOrigin:TORG, visibility:false, wrapDateLine:true};

   var layerIndex=0

   // top layers
   var oBL = {};
   jQuery.extend(oBL,optionsBL);
   oBL.layerId = 'world';
   var world=new OpenLayers.Layer.WMS(
     "Countries (background)",
     GWC,
     {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname' , format:'image/png', transparent:true} ,
     oBL
   );
   map.addLayer(world);
   map.setLayerIndex(world,layerIndex);
   layerIndex+=1;

   createdLayers=[];
   ii=0;
   for (var ii in layersList) {
     thisObject=layersList[ii];
     thisGWC = GWC;
     //thisGWC = "http://onesharedocean.org/geoserver/arrangements/wms";
     jQuery('#'+thisObject["layerId"]).find('a').attr('href','http://onesharedocean.org/node/'+thisObject["node"]);
     jQuery('#'+thisObject["layerId"]).find('input').attr('checked',true);
     var oS = {};
     jQuery.extend(oS,optionsShow);
     oS.layerId = thisObject['layerId'];
     createdLayers[ii] = new OpenLayers.Layer.WMS(
       thisObject["name"], thisGWC, {layers:thisObject["layer"], transparent:true,styles:'gray_transparent_outline',format:'image/png'},oS
     );
     map.addLayer(createdLayers[ii]);
     map.setLayerIndex(createdLayers[ii], layerIndex);
     layerIndex += 1;
     ii=ii+1;
   }

   // LMEs + warmpool = aggregation
   var oH = {};
   jQuery.extend(oH,optionsHide);
   oH.layerId = 'lmes';
   var lmes = new OpenLayers.Layer.WMS(
     "LMEs & warmpool", GWC,
     //"http://onesharedocean.org/geoserver/wms",
     {layers:"LME66_warmpool", transparent:true, styles:'lmes_nofill_contour_red', format:'image/png'},
     oH
   );
   map.addLayer(lmes);
   map.setLayerIndex(lmes, layerIndex);
   layerIndex += 1;


   var oS = {};
   jQuery.extend(oS,optionsShow);
   oS.layerId = 'worldtop';
   var worldtop=new OpenLayers.Layer.WMS(
     "Countries (overlay)", GWCGNRL,
     {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
     oS
   );
   map.addLayer(worldtop);
   map.setLayerIndex(worldtop, layerIndex);
   layerIndex += 1;


   var oH = {};
   jQuery.extend(oH,optionsHide,{attribution:'EEZ: Claus S., N. De Hauwere, B. Vanhoorne, F. Souza Dias, F. Hernandez, and J. Mees (Flanders Marine Institute) (2015). MarineRegions.org. Accessed at http://www.marineregions.org.'});
   oH.layerId = 'eez';
   var eez=new OpenLayers.Layer.WMS(
     "EEZ",
     GWC,
     {//layers:"general:World_Maritime_Boundaries_v8",
       layers:"general:outer_line_EEZ",
       transparent:true, styles:'', format:'image/png'},
     oH
   );
   map.addLayer(eez);
   map.setLayerIndex(eez, layerIndex);
   layerIndex += 1;

   map.zoomToExtent(viewInit);

   // hide the layerswitcher button
   jQuery('#OpenLayers_Control_MinimizeDiv').css("visibility",'hidden');
   // hide table header
   jQuery('div.dataLbl').remove();

   jQuery('.ulColumns li').each(function(){
     var li = this;
     jQuery(li).find('input').click(function(){
       var input = this;
       jQuery.each(map.layers,function(){
         if(this.layerId == li.id || this.name.toLowerCase() == li.id){
           if(jQuery(input).prop('checked')){
             map.getLayer(this.id).setVisibility(true);
           } else {
             map.getLayer(this.id).setVisibility(false);
           }
         }

       });
     });
   });
   jQuery('#clearAll').click(function(){
     jQuery(".ulColumns").not(".lsControls").find('input').attr('checked', false);
     jQuery.each(map.layers,function(){
       if(this.layerId){
         if(this.layerId != 'worldtop' && this.layerId != 'lmes' && this.layerId != 'eez'){
           map.getLayer(this.id).setVisibility(false);
         }
       }
     });
   });
   <?php } ?>
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



 /*------------------------*/
 .outerColumns{
   float:left;
   width:20%;
   padding:0;
   margin:0;
   font-size:13px;
 }
 .innerColumns{
   width:150px;
   align:left;
   color:#A0A0A0;
   background-color:#e2F0F0;
   padding-left:5px;
   overflow:hidden";
 }
 .ulColumns{
   margin:0 !important;
   padding:0 !important;
   list-style-type: none;
 }
 .ulCoumns li{
   padding:0;
   margin:0;
   vertical-align: middle;
 }
 .ulColumns input{
   margin-right:5px !important;
   position: relative !important;
   top:3px !important;
 }
 .outerLsControls{
   width:550px;
   margin:auto auto;
 }
 .lsControls{
 }
 .lsControls li{
   display:inline;
   float:left;
   margin-right:20px;
 }
 #clearAll{
   float:right;
   margin-top:20px;
   margin-right:40px;
 }
</style>

<p style="padding-top:1em; padding-bottom:1em">
  Regional clusters of arrangements are grouped in 5 main regions (Atlantic, Pacific, Indian ocean, Inland seas and Polar regions). Individual arrangements are shown as a shade of gray: the more the arrangements overlap an area the darker it is.
  To show or hide a cluster, click on the check button in front of its name. To see the relation between a regional cluster and related issues, click on its name.
</p>

<div style="clear:both"></div>

<div id="layerswitcher" class="olControlLayerSwitcher" style="display:none"></div>


<div class="outerColumns">
  <div class="innerColumns">Atlantic</div>
  <ul class="ulColumns">
    <li id="ne_atlantic"><input type="checkbox" /><a>Northeast Atlantic</a></li>
    <li id="nw_atlantic"><input type="checkbox" /><a>Northwest Atlantic</a></li>
    <li id="wc_atlantic"><input type="checkbox" /><a>Western Central Atlantic</a></li>
    <li id="ecs_atlantic"><input type="checkbox" /><a>Eastern Central and South Atlantic</a></li>
  </ul>
</div>

<div class="outerColumns">
  <div class="innerColumns">Pacific</div>
  <ul class="ulColumns">
    <li id="ne_pacific"><input type="checkbox" /><a>Northeast Pacific</a></li>
    <li id="se_pacific"><input type="checkbox" /><a>Southeast Pacific</a></li>
    <li id="sw_pacific"><input type="checkbox" /><a>Northwest Pacific</a></li>
    <li id="pacific_islands"><input type="checkbox" /><a>Pacific Islands Region</a></li>
  </ul>
</div>

<div class="outerColumns">
  <div class="innerColumns">Indian ocean</div>
  <ul class="ulColumns">
    <li id="se_asia"><input type="checkbox" /><a>Southeast Asia</a></li>
    <li id="e_indian"><input type="checkbox" /><a>Eastern Indian Ocean</a></li>
    <li id="w_indian"><input type="checkbox" /><a>Western Indian Ocean</a></li>
  </ul>
</div>

<div class="outerColumns">
  <div class="innerColumns">Inland seas</div>
  <ul class="ulColumns">
    <li id="baltic_sea"><input type="checkbox" /><a>Baltic Sea</a></li>
    <li id="mediterranean_sea"><input type="checkbox" /><a>Mediterranean Sea</a></li>
    <li id="black_sea"><input type="checkbox" /><a>Black Sea</a></li>
  </ul>
</div>

<div class="outerColumns">
  <div class="innerColumns">Polar</div>
  <ul class="ulColumns">
    <li id="arctic"><input type="checkbox" /><a>Arctic</a></li>
    <li id="southern"><input type="checkbox" /><a>Southern Ocean</a></li>
  </ul>
</div>
<button id="clearAll">clear all</button>

<div style="clear:both"></div>
<div class="outerLsControls">
  <ul class="ulColumns lsControls">
    <li id="grid"><label><input type="checkbox"/>Grid</label></li>
    <li id="worldtop"/><label><input type="checkbox" checked/>Countries (overlay)</label></li>
    <li id="eez"/><label><input type="checkbox"/>EEZ</label></li>
    <li id="lmes"/><label><input type="checkbox"/>LMEs & warmpool</label></li>
  </ul>
</div>
<div style="clear:both"></div>

<div id="map-id" ></div>
<table class="layersLegend">
  <tr>
    <td><span style="background-color:#AAAAAA; opacity:0.2; filter:alpha(opacity=20);">&nbsp;&nbsp;&nbsp;&nbsp;</span>1 arrangement</td>
    <td><span style="background-color:#AAAAAA; opacity:0.4; filter:alpha(opacity=40);">&nbsp;&nbsp;&nbsp;&nbsp;</span>2 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:0.5; filter:alpha(opacity=60);">&nbsp;&nbsp;&nbsp;&nbsp;</span>3 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:0.6; filter:alpha(opacity=80);">&nbsp;&nbsp;&nbsp;&nbsp;</span>4 arrangements</td>
    <td><span style="background-color:#AAAAAA; opacity:1; filter:alpha(opacity=100);">&nbsp;&nbsp;&nbsp;&nbsp;</span>5 arrangements or more</td>
  </tr>
</table>