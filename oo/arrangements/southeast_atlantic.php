<?php
$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-Equiv="Cache-Control" content="no-cache"/>
    <meta http-Equiv="Pragma" Content="no-cache"/>
    <meta http-Equiv="Expires" content="0"/>
    <title></title>
    <link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css">
    <link rel="stylesheet" href="layersTable.css" type="text/css" />
    <style>
     #map-id {
       width: 700px;
       height: 400px;
       float:left;
       margin-left: 60px !important;
     }
     #layerswitcher.olControlLayerSwitcher{
       font-size:12px !important;
       font-family:sans-serif !important;
       font-weight: normal;
     }
     .olControlLayerSwitcher .layersDiv{
       background-color:#c0c0c0 !important;
       margin: 1.5em !important;
       padding-top:0;
       margin-top:0 !important;
     }
     #infoArrangement, #infoGeneral{
       font-size:12px !important;
       font-weight:normal;
       font-family:sans-serif !important;
     }
     .olControlLayerSwitcher {
       float:left;
       position: relative !important;
       top: 0 !important;
       right: 0;
       width: 12em !important;
     }
     div.olControlAttribution{
       font-family:Verdana;
       font-size:10px;
       bottom:3px;
       background-color:#e4e4e4;
       opacity:0.7;
       filter:alpha(opacity=70);
     }

    </style>
    <script src="/sites/all/libraries/OpenLayers-2.13.1/OpenLayers.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script>
     jQuery(document).ready(function(){

       <?php if(!$geoserver_on){ ?>
       jQuery('#map-id').html('<div style="text-align:center;width:400px;margin:auto;font-family:sans-serif"><h3>The map service is currently down.<br/>Please try again in a few minutes.</h3><span style="font-size:100px;color:red">&#8856</span></div>').height(300);
       return false;
       <?php } ?>


       //////////////////////////////////////////////////////////////
       var GWC = "http://onesharedocean.org/geoserver/gwc/service/wms";
       OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
       var TSIZE=new OpenLayers.Size(225,225);
       var TORG = new OpenLayers.LonLat(-180.0,90.0);
       var resolutions=[0.8, 0.4, 0.2, 0.1, 0.05];

       var extent = new OpenLayers.Bounds(-180,-90,180,90);
       var minResolution=360/400.0;
       var maxResolution=5/400.0;
       var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),'ascending':false});
       var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});
       var options = {//minResolution:minResolution, maxResolution:maxResolution,
         resolutions:resolutions,
         controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(),
                   layersSwitcher, graticule,
                   new OpenLayers.Control.Attribution()]};

       var map = new OpenLayers.Map("map-id", options);
       layersSwitcher.maximizeControl();

       var world=new OpenLayers.Layer.WMS(
         "Countries (background)",
         //"http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:true, visibility:true, wrapDateLine:true, displayInLayerSwitcher:false}
       );

       var worldtop=new OpenLayers.Layer.WMS(
         "Countries",
        // "http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, visibility:true, opacity:1, wrapDateLine:true,displayInLayerSwitcher:false,
	 attribution:'Political boundaries: GAUL (2015). FAO Statistics Division'}
       );

       var lmes=new OpenLayers.Layer.WMS(
         "LMEs",
         //"http://onesharedocean.org/geoserver/ocean/wms",
	 GWC,
         {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false}
       );

       var eez=new OpenLayers.Layer.WMS(
         "EEZ",
         //"http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:World_Maritime_Boundaries_v8", transparent:true, styles:''},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true,
	 attribution:"EEZ: Claus S., N. De Hauwere, B. Vanhoorne, F. Souza Dias, F. Hernandez, and J. Mees (Flanders Marine Institute) (2015). MarineRegions.org. Accessed at http://www.marineregions.org. "}
       );

       var turtles = new OpenLayers.Layer.WMS(
         "EA Turtles",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {//layers:"arrangements:East_Atlantic_Turtles_MOU"
           layers:"arrangements:east_atlantic_turtles_MOU_merged", transparent:true},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'East_Atlantic_Turtles', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var cetaceans = new OpenLayers.Layer.WMS(
         "WA Cetaceans",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {//layers:"arrangements:West_African_Cetaceans_MOU"
           layers:"arrangements:west_africa_cetaceans_MOU_merged", transparent:true, styles:'yellow_ffe200_transparent'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'West_African_Cetaceans', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var iccat=new OpenLayers.Layer.WMS(
         "ICCAT",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_ICCAT", transparent:true, styles:'iccat_baltic_sea'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'ICCAT', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var ccsbt=new OpenLayers.Layer.WMS(
         "CCSBT",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_CCSBT", transparent:true, styles:'blue_0025ee_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'CCSBT', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var cecaf=new OpenLayers.Layer.WMS(
         "CECAF",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_CECAF", transparent:true, styles:'lime_dff400_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'CECAF', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var comhafat=new OpenLayers.Layer.WMS(
         "COMHAFAT",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_COMHAFAT", transparent:true, styles:'orange_ff7c00_transparent'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'COMHAFAT', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var corep=new OpenLayers.Layer.WMS(
         "COREP",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_COREP", transparent:true, styles:'purple_ee00e7_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'COREP', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var srfc=new OpenLayers.Layer.WMS(
         "SRFC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_SRFC", transparent:true, styles:'violet_5c00ee_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'SRFC', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var abidjan=new OpenLayers.Layer.WMS(
         "Abidjan",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:Abidjan", transparent:true, styles:'abidjan_southeast_atlantic'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'Abidjan', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var seafo = new OpenLayers.Layer.WMS(
         "SEAFO",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_SEAFO", transparent:true, styles:'red_ff0038_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'SEAFO', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var fcwc = new OpenLayers.Layer.WMS(
         "FCWC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_FCWC", transparent:true, styles:'orange_ffaf00_transparent'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'FCWC', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       map.addLayers([worldtop, turtles, cetaceans, ccsbt, cecaf, comhafat, corep, iccat, srfc, lmes, abidjan, seafo, fcwc, eez, world]);
       map.setLayerIndex(world, 0);
       map.setLayerIndex(iccat, 1);
       map.setLayerIndex(comhafat, 2);
       map.setLayerIndex(cecaf, 3);
       map.setLayerIndex(ccsbt, 4);
       map.setLayerIndex(abidjan, 5);
       map.setLayerIndex(srfc, 6);
       map.setLayerIndex(corep, 7);
       map.setLayerIndex(seafo, 8);
       map.setLayerIndex(fcwc, 9);
       map.setLayerIndex(turtles, 10);
       map.setLayerIndex(cetaceans, 11);
       map.setLayerIndex(worldtop, 12);
       map.setLayerIndex(lmes, 13);
       map.setLayerIndex(eez, 14);
       map.zoomToExtent([-70,-70,35,35]);

       var infoGnrl = new OpenLayers.Control.WMSGetFeatureInfo({
         url:'http://onesharedocean.org/geoserver/ocean/wms',
         title:'identify feature by clicking',
         output:'features', infoFormat:'application/vnd.ogc.gml',
         format: new OpenLayers.Format.GML,
         eventListeners: {
           getfeatureinfo: function(event) {
             eezName='';
             lmeName='';
             if (typeof(event.features[0])=='undefined'){document.getElementById('infoGeneral').innerHTML='&nbsp;'; return};
             for (ii=0; ii< event.features.length; ii++){
               //console.log(event.features[ii].attributes);
               thisEEZNAME=event.features[ii].attributes['eez'];
               thisLMENAME=event.features[ii].attributes['LME_NAME'];
               if (typeof(thisEEZNAME)!='undefined') {if (eezName=='') {eezName=thisEEZNAME} else {eezName+=', '+thisEEZNAME}};
               if (typeof(thisLMENAME)!='undefined') {if (eezName=='') {eezName=thisEEZNAME} else {lmeName+=', '+thisLMENAME}};
             }
             document.getElementById('infoGeneral').innerHTML=eezName;
           }
         }
       });

       uneprsid={'2053':'Abidjan', '170':'Antigua', '994':'Bucharest', '2041':'Helsinki', '2054':'Lima', '1125':'Jeddah', '1119':'Kuwait', '2051':'Noumea', '510':'Cartagena', '2049':'Barcelona', '1960':'Nairobi'};
       var infoArrangement = new OpenLayers.Control.WMSGetFeatureInfo({
         url:'http://onesharedocean.org/geoserver/arrangements/wms',
         title:'identify feature by clicking',
         output:'features', infoFormat:'application/vnd.ogc.gml',
         format: new OpenLayers.Format.GML,
         eventListeners: {
           getfeatureinfo: function(event) {
             rfbName='';
             if (typeof(event.features[0])=='undefined'){document.getElementById('infoArrangement').innerHTML='&nbsp;'; return};
             for (ii=0; ii< event.features.length; ii++){
               console.log(event.features[ii].attributes);
               thisRFBNAME=event.features[ii].attributes['RFB'];
               thisUnepRSID=event.features[ii].attributes['UNEP_RS_ID'];
               if (typeof(thisUnepRSID)!='undefined') {if (rfbName=='') {rfbName=uneprsid[thisUnepRSID]} else {rfbName+=', '+uneprsid[thisUnepRSID]}}
               if (typeof(thisRFBNAME)!='undefined'){if (rfbName=='') {rfbName=thisRFBNAME} else { rfbName+=', '+thisRFBNAME}};
               if (typeof(thisOther)!='undefined'){if (rfbName=='') {rfbName=thisRFBNAME} else {rfbName+=', '+thisOther}};
             }
             document.getElementById('infoArrangement').innerHTML=rfbName;
           }
         }
       });

       map.addControl(infoGnrl);
       infoGnrl.activate();
       map.addControl(infoArrangement);
       infoArrangement.activate();
       ///////////////////////////////////////////////////////////   

       <?php include('/data/iframes/oo/arrangements/layersTable.js'); ?>

     });
    </script>
  </head>
  <body>
    <div id="tableInfo"></div>
    <div style="width:700px; margin:auto; float:left">
      <div id="layerSelector" style="float:right">
        <table cellspacing="0" cellpadding="0">
          <thead>
            <tr>
              <td class="firstTD" style="background-color:#fff;"></td>
              <td theme="integration"></td>
              <td theme="fisheries"></td>
              <td theme="pollution"></td>
              <td theme="biodiversity"></td>
              <td theme="climate"></td>
              <td theme="abnj"></td>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>

    <div style="clear:both"></div><br/>


    <div id="infoArrangement">&nbsp;</div>
    <div id="infoGeneral">&nbsp;</div>
    <div>
      <div id="map-id" ></div>
      <div id="layerswitcher" class="olControlLayerSwitcher"></div>
    </div>

  </body>
</html>
