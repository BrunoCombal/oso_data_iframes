<?php
$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-Equiv="Cache-Control" content="no-cache"/>
    <meta http-Equiv="Pragma" Content="no-cache"/>
    <meta http-Equiv="Expires" content="0"/>
    <title>Governance: Northeast Pacific</title>
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
       var WDL=true;
       var TORG = new OpenLayers.LonLat(-180.0, 90.0);
       var TSIZE=new OpenLayers.Size(225, 225);
       var resolutions=[0.8, 0.4, 0.2, 0.1, 0.05];
       var GWC="http://onesharedocean.org/geoserver/gwc/service/wms"

       var extent = new OpenLayers.Bounds(-180,-90,180,90);
       var minResolution=360/700.0;
       var maxResolution=0.5/700.0;
       var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
       var graticule=new OpenLayers.Control.Graticule({numPoints:2, labelled:true, layerName:'Grid', labelFormat:'dd', visible:false, displayInLayerSwitcher:true, labelSymbolizer:{fontFamily:"sans-serif",fontColor:"#000000", fontSize:"12px"}});
       var options = {
         resolutions:resolutions, projection: new OpenLayers.Projection('EPSG:4326'), units:"degrees",
         maxExtent:extent, wrapDateline:true,
         controls:[new OpenLayers.Control.PanZoom(),
                   new OpenLayers.Control.NavToolbar(), layersSwitcher, graticule,
                   new OpenLayers.Control.Attribution()]};

       var map = new OpenLayers.Map("map-id", options);
       layersSwitcher.maximizeControl();

       var world=new OpenLayers.Layer.WMS(
         "Countries (background)",
         //"http://onesharedocean.org/geoserver/general/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"general:G2014_2013_0", styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, wrapDateLine:true, isBaseLayer:true, visibility:true, displayInLayerSwitcher:false}
       );

       var worldtop=new OpenLayers.Layer.WMS(
         "Countries",
         //"http://onesharedocean.org/geoserver/general/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"general:G2014_2013_0", transparent:true,styles:'gaul_lightyellow_noname', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, wrapDateLine:true, visibility:true, opacity:1,
          attribution:"Political boundaries: GAUL (2015), FAO Statistics Division"}
       );

       var lmes = new OpenLayers.Layer.WMS(
         "LMEs",
         // "http://onesharedocean.org/geoserver/wms",
         GWC,
         {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels', format:'image/png'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG, wrapDateLine:WDL, isBaseLayer:false, opacity:1, visibility:false}
       );

       var eez = new OpenLayers.Layer.WMS(
         "EEZ",
         //"http://onesharedocean.org/geoserver/general/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"general:outer_line_EEZ", transparent:true, styles:''},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,isBaseLayer:false, opacity:1, visibility:false,
          attribution:"EEZ: Claus S., N. De Hauwere, B. Vanhoorne, F. Souza Dias, F. Hernandez, and J. Mees (Flanders Marine Institute) (2015). MarineRegions.org. Accessed at http://www.marineregions.org."}
       );

       var iac = new OpenLayers.Layer.WMS(
         "IAC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {//layers:"arrangements:IAC_MOU_simplified"
           layers:"arrangements:iac_mou_merged", transparent:true, styles:'green_00cc68_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,isBaseLayer:false, layerId:'IAC', opacity:1, visibility:true, displayInLayerSwitcher:false}
       );

       var ccbsp=new OpenLayers.Layer.WMS(
         "CCBSP",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_CCBSP", transparent:true, styles:'crfm_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'CCBSP', displayInLayerSwitcher:false}
       );

       var iattc=new OpenLayers.Layer.WMS(
         "IATTC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_IATTC", transparent:true, styles:'iccat_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'IATTC', displayInLayerSwitcher:false}
       );

       var iphc=new OpenLayers.Layer.WMS(
         "IPHC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_IPHC", transparent:true, styles:'nammco_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'IPHC', displayInLayerSwitcher:false}
       );

       var npafc=new OpenLayers.Layer.WMS(
         "NPAFC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_NPAFC", transparent:true, styles:'oldepesca_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'NPAFC', displayInLayerSwitcher:false}
       );

       var oldepesca=new OpenLayers.Layer.WMS(
         "OLDEPESCA",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_OLDEPESCA", transparent:true, styles:'ospesca_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'OLDEPESCA', displayInLayerSwitcher:false}
       );

       var pices=new OpenLayers.Layer.WMS(
         "PICES",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_PICES", transparent:true, styles:'wecafc_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'PICES', displayInLayerSwitcher:false}
       );

       var psc=new OpenLayers.Layer.WMS(
         "PSC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_PSC", transparent:true, styles:'wecafc_wca'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,visibility:true, opacity:1, layerId:'PSC', displayInLayerSwitcher:false}
       );

       var wcpfc=new OpenLayers.Layer.WMS(
         "WCPFC",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_WCPFC", transparent:true, styles:'orange_ffaf00_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,opacity:1, layerId:'WCPFC', displayInLayerSwitcher:false}
       );

       var ospesca=new OpenLayers.Layer.WMS(
         "OSPESCA",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:RFB_OSPESCA", transparent:true, styles:'purple_ee00e7_transparent'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,opacity:1, layerId:'OSPESCA', displayInLayerSwitcher:false}
       );

       var antigua=new OpenLayers.Layer.WMS(
         "Antigua",
         //"http://onesharedocean.org/geoserver/arrangements/wms",
         "http://onesharedocean.org/geoserver/gwc/service/wms",
         {layers:"arrangements:Antigua", transparent:true, styles:'antigua_ne_pacific'},
         {tiled:true, tileSize:TSIZE, tileOrigin:TORG,opacity:1, layerId:'Antigua', displayInLayerSwitcher:false}
       );

       map.addLayers([worldtop, iac, ccbsp, iattc, iphc, npafc, oldepesca, ospesca, pices, psc, wcpfc, antigua, lmes, eez, world]);
       map.setLayerIndex(world, 0);
       map.setLayerIndex(wcpfc, 1);
       map.setLayerIndex(iattc, 2);
       map.setLayerIndex(npafc, 3);
       map.setLayerIndex(iphc, 4);
       map.setLayerIndex(pices, 5);
       map.setLayerIndex(oldepesca, 6);
       map.setLayerIndex(ospesca, 7);
       map.setLayerIndex(antigua, 8);
       map.setLayerIndex(psc, 9);
       map.setLayerIndex(ccbsp, 10);
       map.setLayerIndex(iac, 11);
       map.setLayerIndex(worldtop, 12);
       map.setLayerIndex(lmes, 13);
       map.setLayerIndex(eez, 14);
       map.zoomToExtent([-170,-5,-80,65]);

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
