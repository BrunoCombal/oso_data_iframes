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
       var extent = new OpenLayers.Bounds(-8000000, -5000000, 1900000, 240000);
       var minResolution=10000000/700.0;
       var maxResolution=5000000/700.0;
       var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher'),'ascending':false});
       var graticule=new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false});
       var options = { maxExtent:extent, minResolution:minResolution, maxResolution:maxResolution, projection:'EPSG:3995', units:'m', numZoomLevels:6,
                       controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher]};

       var map = new OpenLayers.Map("map-id", options);
       layersSwitcher.maximizeControl();

       var world=new OpenLayers.Layer.WMS(
         "Countries (background)",
         "http://onesharedocean.org/geoserver/general/wms",
         {layers:"general:Countries_north_epsg_3995", styles:'world_epsg4326_top', format:'image/png'},
         {singleTile:true, isBaseLayer:true, visibility:true, displayInLayerSwitcher:false}
       );

       var worldtop=new OpenLayers.Layer.WMS(
         "Countries",
         "http://onesharedocean.org/geoserver/general/wms",
         {layers:"general:Countries_north_epsg_3995", transparent:true,styles:'world_epsg4326_top', format:'image/png'},
         {singleTile:true, isBaseLayer:false, visibility:true, opacity:1}
       );

       var lmes=new OpenLayers.Layer.WMS(
         "LMEs",
         "http://onesharedocean.org/geoserver/ocean/wms",
         {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels'},
         {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
       );

       var eez = new OpenLayers.Layer.WMS(
         "EEZ",
         "http://onesharedocean.org/geoserver/general/wms",
         {layers:"general:World_Maritime_Boundaries_v8", transparent:true, styles:''},
         {tiled:true, isBaseLayer:false, opacity:1, visibility:false}
       );

       var iac = new OpenLayers.Layer.WMS(
         "IAC",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {//layers:"arrangements:IAC_MOU_simplified", 
	   layers:"arrangements:iac_mou_merged_3995",transparent:true, styles:'green_00cc68_transparent'},
         {tiled:true, isBaseLayer:false, layerId:'IAC', opacity:1, visibility:true, displayInLayerSwitcher:false}
       );

       var iccat=new OpenLayers.Layer.WMS(
         "ICCAT",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_ICCAT", transparent:true, styles:'iccat_baltic_sea'},
         {singleTile:true, visibility:true, opacity:1, layerId:'ICCAT', displayInLayerSwitcher:false}
       );


       var nammco=new OpenLayers.Layer.WMS(
         "NAMMCO",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_NAMMCO", transparent:true, styles:'nammco_baltic_sea'},
         {singleTile:true, visibility:true, opacity:1, layerId:'NAMMCO', displayInLayerSwitcher:false}
       );

       var nasco=new OpenLayers.Layer.WMS(
         "NASCO",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_NASCO", transparent:true, styles:'nasco_baltic_sea'},
         {singleTile:true, visibility:true, opacity:1, layerId:'NASCO', displayInLayerSwitcher:false}
       );

       var nafo=new OpenLayers.Layer.WMS(
         "NAFO",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_NAFO", transparent:true, styles:'nafo_northwest_atlantic'},
         {singleTile:true, visibility:true, opacity:1, layerId:'NAFO', displayInLayerSwitcher:false}
       );

       map.addLayers([worldtop, iac, nasco, nafo,nammco, iccat, lmes, eez, world]);
       map.setLayerIndex(world, 0);
       map.setLayerIndex(iccat, 1);
       map.setLayerIndex(nasco, 2);
       map.setLayerIndex(nammco, 3);
       map.setLayerIndex(nafo, 4);
       map.setLayerIndex(iac, 5);
       map.setLayerIndex(worldtop, 6);
       map.setLayerIndex(lmes, 7);
       map.setLayerIndex(eez, 8);
       map.zoomToExtent(extent);


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
