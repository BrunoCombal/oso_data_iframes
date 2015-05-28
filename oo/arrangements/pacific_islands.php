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

       var extent = new OpenLayers.Bounds(-180,-90,180,90);
       var minResolution=360/800.0;
       var maxResolution=0.5/800.0;
       var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
       var graticule = new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});
       var options = {minResolution:minResolution, maxResolution:maxResolution, numZoomLevels:18,
                      controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule]};

       var map = new OpenLayers.Map("map-id", options);
       layersSwitcher.maximizeControl();

       var world=new OpenLayers.Layer.WMS(
         "Countries (background)",
         "http://onesharedocean.org/geoserver/general/wms",
         {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname', format:'image/png'},
         {singleTile:true, isBaseLayer:true, visibility:true, displayInLayerSwitcher:false}
       );

       var worldtop=new OpenLayers.Layer.WMS(
         "Countries",
         "http://onesharedocean.org/geoserver/general/wms",
         {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
         {singleTile:true, isBaseLayer:false, visibility:true, opacity:1}
       );

       var lmes = new OpenLayers.Layer.WMS(
         "LMEs & warmpool",
         "http://onesharedocean.org/geoserver/wms",
         {layers:"LME66_warmpool", transparent:true, styles:'lmes_nofill_contour_red_labels', format:'image/png'},
         {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
       );

       var eez = new OpenLayers.Layer.WMS(
         "EEZ",
         "http://onesharedocean.org/geoserver/ocean/wms",
         {layers:"ocean:OBIS_eezs", transparent:true, styles:"eez_nofill_contour_orange_labels"},
         {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
       );

       var cetacean = new OpenLayers.Layer.WMS(
	 "Cetaceans",
	 "http://onesharedocean.org/geoserver/arrangements/wms",
	 {layers:"arrangements:pacific_islands_cetaceans_MOU_merged", transparent:true, styles:''},
	 {singleTile:true, visibility:true, opacity:1, layerId:'Pacific_Islands_Cetaceans', displayInLayerSwitcher:false}
       );

       var ffa=new OpenLayers.Layer.WMS(
         "FFAC",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_FFA", transparent:true, styles:'crfm_wca'},
         {singleTile:true, visibility:true, opacity:1, layerId:'FFAC', displayInLayerSwitcher:false}
       );

       var spc=new OpenLayers.Layer.WMS(
         "SPC",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_SPC", transparent:true, styles:'iccat_wca'},
         {singleTile:true, visibility:true, opacity:1, layerId:'SPC', displayInLayerSwitcher:false}
       );

       var wcpfc=new OpenLayers.Layer.WMS(
         "WCPFC",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_WCPFC", transparent:true, styles:'nammco_wca'},
         {singleTile:true, visibility:true, opacity:1, layerId:'WCPFC', displayInLayerSwitcher:false}
       );

       var sprfmo=new OpenLayers.Layer.WMS(
         "SPRFMO",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:RFB_SPRFMO", transparent:true, styles:'oldepesca_wca'},
         {singleTile:true, visibility:true, opacity:1, layerId:'SPRFMO', displayInLayerSwitcher:false}
       );

       var pif = new OpenLayers.Layer.WMS(
         "PIF",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:pif_merged", transparent:true, styles:'blue_0025ee_transparent'},
         {singleTile:true, visibility:true, opacity:1, layerId:'PIF', displayInLayerSwitcher:false}
       );

       var noumea=new OpenLayers.Layer.WMS(
         "Noumea",
         "http://onesharedocean.org/geoserver/arrangements/wms",
         {layers:"arrangements:Noumea", transparent:true, styles:'ospesca_wca'},
         {singleTile:true, visibility:true, opacity:1, layerId:'Noumea', displayInLayerSwitcher:false}
       );

       map.addLayers([worldtop, cetacean, ffa, spc, wcpfc, sprfmo, pif, noumea, lmes, eez, world]);
       map.setLayerIndex(world, 0);
       map.setLayerIndex(wcpfc, 1);
       map.setLayerIndex(ffa, 2);
       map.setLayerIndex(spc, 3);
       map.setLayerIndex(sprfmo, 4);
       map.setLayerIndex(pif, 5);
       map.setLayerIndex(noumea, 6);
       map.setLayerIndex(cetacean, 7);
       map.setLayerIndex(worldtop, 8);
       map.setLayerIndex(lmes, 9);
       map.setLayerIndex(eez, 10);
       map.zoomToExtent([-220,-63,-220+118,53]);

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
