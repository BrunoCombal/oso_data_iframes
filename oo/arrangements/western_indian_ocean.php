<?php
$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>
<!DOCTYPE html><!DO<!DOCTYPE html>
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
       OpenLayers.IMAGE_RELOAD_ATTEMPTS = 3;
       var GWC = "http://onesharedocean.org/geoserver/gwc/service/wms";
       var TSIZE=new OpenLayers.Size(225,225);
       var TORG = new OpenLayers.LonLat(-180.0,90.0);
       var resolutions=[0.8, 0.4, 0.2, 0.1, 0.05];

       var extent = new OpenLayers.Bounds(-180,-90,180,90);
       var minResolution=360/700.0;
       var maxResolution=10/700.0;
       var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
       var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});
       var options = {//minResolution:minResolution, maxResolution:maxResolution,
         resolutions:resolutions,
         controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule,
                   new OpenLayers.Control.Attribution()]};

       var map = new OpenLayers.Map("map-id", options);
       layersSwitcher.maximizeControl();

       var world=new OpenLayers.Layer.WMS(
         "Countries (background)",
         //"http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:true, visibility:true, displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var worldtop=new OpenLayers.Layer.WMS(
         "Countries",
         //"http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, visibility:true, opacity:1,wrapDateLine:true,
	 attribution:'Political boundaries: GAUL (2015). FAO Statistics Division'}
       );

       var lmes = new OpenLayers.Layer.WMS(
         "LMEs",
         //"http://onesharedocean.org/geoserver/wms",
	 GWC,
         {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
       );

       var eez=new OpenLayers.Layer.WMS(
         "EEZ",
         //"http://onesharedocean.org/geoserver/general/wms",
	 GWC,
         {layers:"general:outer_line_EEZ", transparent:true, styles:''},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false,wrapDateLine:true,
	 attribution:"EEZ: Claus S., N. De Hauwere, B. Vanhoorne, F. Souza Dias, F. Hernandez, and J. Mees (Flanders Marine Institute) (2015). MarineRegions.org. Accessed at http://www.marineregions.org. "}
       );

       var dugong = new OpenLayers.Layer.WMS(
         "Dugong",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {//layers:"arrangements:Dugong_MOU_simplified",
           layers:"arrangements:dugong_mou_merged", transparent:true, styles:'red_ff2d00_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, opacity:1, layerId:'Dugong', visibility:true, displayInLayerSwitcher: false}
       );

       var iosea = new OpenLayers.Layer.WMS(
         "IOSEA",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {//layers:"arrangements:IOSEA_MOU_simplified",
           layers:"arrangements:iosea_merged", transparent:true, styles:'yellow_ffe200_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, opacity:1, layerId:'IOSEA', visibility:true, displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var iotc=new OpenLayers.Layer.WMS(
         "IOTC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_IOTC", transparent:true, styles:'violet_5c00ee_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'IOTC', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var siofa=new OpenLayers.Layer.WMS(
         "SIOFA",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_SIOFA", transparent:true, styles:'green_57da00_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'SIOFA', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var swiofc=new OpenLayers.Layer.WMS(
         "SWIOFC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_SWIOFC", transparent:true, styles:'purple_ee00e7_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'SWIOFC', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var recofi=new OpenLayers.Layer.WMS(
         "RECOFI",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:RFB_RECOFI", transparent:true, styles:'lime_dff400_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'RECOFI', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var jeddah=new OpenLayers.Layer.WMS(
         "Jeddah",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:Jeddah", transparent:true, styles:'orange_ff7c00_transparent'},
         {tiled:true,  tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'Jeddah', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var kuwait=new OpenLayers.Layer.WMS(
         "Kuwait",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:Kuwait", transparent:true, styles:'lime_dff400_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'Kuwait', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       var nairobi=new OpenLayers.Layer.WMS(
         "Nairobi",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
	 GWC,
         {layers:"arrangements:Nairobi", transparent:true, styles:'blue_0025ee_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, visibility:true, opacity:1, layerId:'Nairobi', displayInLayerSwitcher:false, wrapDateLine:true}
       );

       map.addLayers([worldtop, dugong, iosea, iotc, siofa, swiofc, recofi, jeddah, kuwait, nairobi, lmes, eez, world]);
       map.setLayerIndex(world, 0);
       map.setLayerIndex(iotc, 1);
       map.setLayerIndex(siofa, 2);
       map.setLayerIndex(swiofc, 3);
       map.setLayerIndex(recofi, 4);
       map.setLayerIndex(nairobi, 5);
       map.setLayerIndex(jeddah, 6);
       map.setLayerIndex(kuwait, 7);
       map.setLayerIndex(dugong, 8);
       map.setLayerIndex(iosea, 9);
       map.setLayerIndex(worldtop, 10);
       map.setLayerIndex(lmes, 11);
       map.setLayerIndex(eez, 12);
       map.zoomToExtent([0,-60,150,30]);


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
