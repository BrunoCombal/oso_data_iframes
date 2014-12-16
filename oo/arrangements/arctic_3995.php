<?php
$geoserver_on = @file ('http://onesharedocean.org/geoserver');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-Equiv="Cache-Control" content="no-cache"/>
    <meta http-Equiv="Pragma" Content="no-cache"/>
    <meta http-Equiv="Expires" content="0"/>
    <title>Arctic cluster</title>
    <link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css">
	<link rel="stylesheet" href="layersTable.css" type="text/css" />
    <style>
     #map-id {
       width: 600px;
       height: 600px;
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
    <script src="http://openlayers.org/api/OpenLayers.js"></script>
	 <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
	<script>
	jQuery(document).ready(function(){
	<?php if(!$geoserver_on){ ?>	
			jQuery('#map-id').html('<div style="text-align:center;width:400px;margin:auto;font-family:sans-serif"><h3>The map service is currently down.<br/>Please try again in a few minutes.</h3><span style="font-size:100px;color:red">&#8856</span></div>').height(300);
			return false;
	<?php } ?>
		
		//Check if we have access to parent document (normally not if the iframe is loaded from a different host
   var sameHost = false;
   try{
	 parent.document;
	 sameHost = true;
   }catch(e){
	 iFrame = null;
   }
   
	
		 var thisServer=window.location.hostname;
		 
		 var extent = new OpenLayers.Bounds(-11000000, -11000000, 11000000, 9000000);
		 var minResolution=11000000/600.0;
		 var maxResolution=5000000/600.0;
		 var layersSwitcher=new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('layerswitcher') , 'ascending':false});
		 
		 var options = {maxExtent:extent, projection:'EPSG:3995', units:'m', minResolution:minResolution, maxResolution:maxResolution, numZoomLevels:6,
				controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar(), layersSwitcher]};
		 
		 var map = new OpenLayers.Map("map-id", options);
		 layersSwitcher.maximizeControl();
		 
		 var world=new OpenLayers.Layer.WMS(
		   "Countries (background)",
		   "http://onesharedocean.org/geoserver/general/wms",
		   {layers:"general:Countries_north_epsg_3995", styles:'world_epsg4326_top', format:'image/png', projection:'EPSG:3995', units:'m'},
		   {singleTile:true, isBaseLayer:true, visibility:true, projection:'EPSG:3995', units:'m'}
		 );
		 
		 var worldtop=new OpenLayers.Layer.WMS(
		   "Countries",
		   "http://onesharedocean.org/geoserver/general/wms",
		   {layers:"general:Countries_north_epsg_3995", transparent:true,styles:'world_epsg4326_top', format:'image/png', projection:'EPSG:3995', units:'m'},
		   {singleTile:true, isBaseLayer:false, visibility:true, opacity:1}
		 );
		 
		 var lmes = new OpenLayers.Layer.WMS(
		   "LMEs",
		   "http://onesharedocean.org/geoserver/ocean/wms",
		   {layers:"ocean:LME66", transparent:true, styles:'lmes_nofill_contour_red_labels'},
		   {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
		 );
		 
		 var eez = new OpenLayers.Layer.WMS(
		   "EEZ",
		   "http://onesharedocean.org/geoserver/general/wms",
		   {layers:"general:World_Maritime_Boundaries_v8", transparent:true, styles:''},
		   {singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
		 );
		 
		 var ccbsp=new OpenLayers.Layer.WMS(
		   "CCBSP",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_CCBSP", transparent:true, styles:'blue_0025ee_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'CCBSP', displayInLayerSwitcher:false}
		 );
		 
		 var iccat=new OpenLayers.Layer.WMS(
		   "ICCAT",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_ICCAT", transparent:true, styles:'iccat_wca'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'ICCAT',displayInLayerSwitcher:false}
		 );
		 
		 var ices=new OpenLayers.Layer.WMS(
		   "ICES",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_ICES", transparent:true, styles:'green_00cc68_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'ICES',displayInLayerSwitcher:false}
		 );
		 
		 var nafo=new OpenLayers.Layer.WMS(
		   "NAFO",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_NAFO", transparent:true, styles:'lime_dff400_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'NAFO',displayInLayerSwitcher:false}
		 );
		 
		 var nammco=new OpenLayers.Layer.WMS(
		   "NAMMCO",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_NAMMCO", transparent:true, styles:'orange_ff7c00_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'NAMMCO',displayInLayerSwitcher:false}
		 );
		 
		 var nasco=new OpenLayers.Layer.WMS(
		   "NASCO",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_NASCO", transparent:true, styles:'purple_ee00e7_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'NASCO',displayInLayerSwitcher:false}
		 );
		 
		 var neafc=new OpenLayers.Layer.WMS(
		   "NEAFC",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_NEAFC", transparent:true, styles:'violet_5c00ee_transparent'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'NEAFC',displayInLayerSwitcher:false}
		 );
		 
		 var council = new OpenLayers.Layer.WMS(
		   "Arctic council",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:Arctic", transparent:true, styles:'nammco_wca'},
		   {singleTile:true, visibility:true, opacity:1, layerId:'Arctic',displayInLayerSwitcher:false}
		 );
		 
		 var iphc = new OpenLayers.Layer.WMS(
		   "IPHC",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_IPHC", transparent:true, styles:'lime_dff400_transparent'},
		   {tiled:false, visibility:true, opacity:1, layerId:'IPHC',displayInLayerSwitcher:false}
		 );
		 
		 var pices = new OpenLayers.Layer.WMS(
		   "PICES",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RFB_PICES", transparent:true, styles:'red_ff0038_transparent'},
		   {tiled:false, visibility:true, opacity:1, layerId:'PICES',displayInLayerSwitcher:false}
		 );
		 
		 var ospar = new OpenLayers.Layer.WMS(
		   "OSPAR",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:OSPAR", transparent:true, styles:'violet_5c00ee_transparent'},
		   {tiled:false, visibility:true, opacity:1, layerId:'OSPAR',displayInLayerSwitcher:false}
		 );

		 var nowpap = new OpenLayers.Layer.WMS(
		   "NOWPAP",
		   "http://onesharedocean.org/geoserver/arrangements/wms",
		   {layers:"arrangements:RS_North_West_Pacific_UNEP", transparent:true, styles:'yellow_ffe200_transparent'},
		   {tiled:false, visibility:true, opacity:1, layerId:'NOWPAP', displayInLayerSwitcher:false}
		 );
		 
		 map.addLayers([worldtop,world]);
		 
		 map.addLayers([worldtop, ccbsp, iccat, ices, nafo, nammco, nasco, neafc, pices, ospar, council, iphc, nowpap, lmes, eez, world]);
		 map.setLayerIndex(world, 0);
		 map.setLayerIndex(ccbsp, 1);
		 map.setLayerIndex(iccat, 2);
		 map.setLayerIndex(ices, 3);
		 map.setLayerIndex(nafo, 4);
		 map.setLayerIndex(nammco, 5);
		 map.setLayerIndex(nasco, 6);
		 map.setLayerIndex(neafc, 7);
		 map.setLayerIndex(iphc, 8);
		 map.setLayerIndex(pices, 9);
		 map.setLayerIndex(ospar, 10);
		 map.setLayerIndex(nowpap, 11);
		 map.setLayerIndex(council, 12);
		 map.setLayerIndex(worldtop, 13);
		 map.setLayerIndex(lmes, 14);
		 map.setLayerIndex(eez, 15);
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
