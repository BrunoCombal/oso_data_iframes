<?php
drupal_add_js('sites/all/libraries/OpenLayers-2.13.1/OpenLayers.js');
drupal_add_js('/iframes/common/js/lmes.js');
drupal_add_library('system', 'ui.autocomplete');
drupal_add_css('misc/ui/jquery.ui.autocomplete.css');

//include('/data/iframes/common/lmesnav/lmenav.php');
?>
<link rel="stylesheet" href="/iframes/lmes/maps/lmesportal.css" type="text/css" />
<link rel="stylesheet" href="/geoserver/openlayers/theme/default/style.css" type="text/css" />
<script type="text/javascript">
 //Everything is executed and scoped on document ready
 jQuery(document).ready(function(){

   ////////////////////// LOGIC FOR THE SEARCH BOX AND THE BUTTONS ////////////////
   jQuery('#lmeNav').css('top','-1px');
   var comboText = "Search for an LME or click on the map";
   jQuery('#deleteTags')
                .click(function(){
                  jQuery('#tags').attr('value', '');
                  jQuery('#tags').prop('value', '');
                  jQuery('#tags').focus();
                  jQuery('#buttonLMEs').css('display', 'none');
                  jQuery('#deleteTags').css('display', 'none');
                })
                .mouseenter(function(){
                  jQuery('#deleteTags').attr('src', '/iframes/lmes/images/delete_red.png');
                });
   jQuery('#deleteTags')
                .mouseleave(function(){
                  jQuery('#deleteTags').attr('src', '/iframes/lmes/images/delete_gray.png');
                });
   jQuery('#tags')
                .keyup(function(){
                  if(jQuery('#tags').prop('value') == ""){
                    jQuery('#deleteTags').css('display','none');
                    jQuery('#buttonLMEs').css('display', 'none');
                  }
                })
                .click(function(){
                  if(this.value == comboText){
                    this.value = "";
                    jQuery( "#tags" ).css('color', '#000000');
                  }
                })
                .keypress(function(){
                  jQuery('#deleteTags').css('display','inline-block');
                })
                .blur(function(){
                  if(this.value ==""){
                    jQuery( "#tags" ).css('color', '#c0c0c0');
                    this.value = comboText;
                    jQuery('#deleteTags').css('display','none');
                    jQuery('#buttonLMEs').css('display', 'none');
                  }
                })
                .autocomplete({
                  source: availableTags,
                  open: function(e, ui) {
                    var list = '';
                    var results = jQuery('ul.ui-autocomplete.ui-widget-content a');
                    jQuery('#results').html(list);
                  },
                  select: function(e, ui) {
                    var lmename = ui.item.value.split(' ');
                    var lmecode = lmename.shift();
                    jQuery('#buttonLMEs').attr('href','/'+lmeAliasList[parseInt(lmecode)-1]);
                    jQuery('#buttonLMEs').attr('target','_blank');
                    jQuery('#buttonLMEs').css("display", "inline-block");
                  }
                });

   jQuery('#buttonLMEs').click(function(){
     document.location.href = jQuery(this).attr('href');
     //window.open(jQuery(this).attr('href'), '_blank');
   });
   //temporary GCA click event
   jQuery('.buttonGCA').click(function(){
     document.location.href = '/node/232';
   });

   ///////////////// OPENLAYERS ////////////////////            
   OpenLayers.IMAGE_RELOAD_ATTEMPTS = 5 ;
   // to match the map:
   var extent = new OpenLayers.Bounds(-180, -90, 180, 90);
   // to match the map:
   var minResolution=360/jQuery("#mapOL").width();
   var maxResolution=50/jQuery("#mapOL").width();
   var numZoomLevels=2;
   // resolution are: 360/256, 180/256, 90/256, etc... does not exactly match the map
   var resolution = [0.48, 0.24, 0.12];

   var options = {
     //restrictedExtent:extent, maxExtent:extent,
     // minResolution:minResolution, maxResolution:maxResolution, numZoomLevels:numZoomLevels,
     resolutions: resolution,
     projection: new OpenLayers.Projection('EPSG:4326'), units:"degrees",
     controls:[new OpenLayers.Control.PanZoom(), new OpenLayers.Control.NavToolbar()]};

   var map = new OpenLayers.Map("mapOL", options);
   var WDL = false;
   var STYLE='';
   //MF var TSIZE=new OpenLayers.Size(256,256);
   var TSIZE=new OpenLayers.Size(375,375);
   var TORG = new OpenLayers.LonLat(-180.0, 90.0);
   //MF var GWCLMES="http://onesharedocean.org/geoserver/lmes/gwc/service/wms";
   //MF var GWCWORLD="http://onesharedocean.org/geoserver/general/gwc/service/wms";
   var GWCLMES="http://onesharedocean.org/geoserver/gwc/service/wms";
   var GWCWORLD="http://onesharedocean.org/geoserver/gwc/service/wms";
   var GWCGENERAL="http://onesharedocean.org/geoserver/gwc/service/wms";

   //Map of the world
   var worldtop=new OpenLayers.Layer.WMS(
     "Countries",
     GWCWORLD,
     {layers:"general:world_epsg4326", transparent:true, styles:'countries_lightyellow_noname', format:'image/png'},
//     {layers:"general:g2015_2012_0", transparent:true, styles:'gaul_lightyellow_noname', format:'image/png'}, //"general:G2014_2013_0", 
     {tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:true, visibility:true, opacity:1, wrapDateLine:true}
   );

   //LMEs
   var lmes = new OpenLayers.Layer.WMS(
     "LMES",
     GWCGENERAL,
     {layers:"general:lmes66_pwp", transparent:true, styles:'lmes66_pwp_green_blue', format:'image/png'},
     {layerId:'lmes', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:true, wrapDateLine:true}
   );

   //Primary productivity
   var chla=new OpenLayers.Layer.WMS(
     "Chlorophyll-A",
     //"http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_chl_pp_and_trends", //"lmes:lmes_chla_longterm", styles:'lmes_chla_mean'
      transparent:true, styles:'lmes_chla', format:'image/png'},
     {layerId:'chla', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var chlachange=new OpenLayers.Layer.WMS(
     "Chlorophyll-A change",
     GWCLMES,
     {layers:"lmes:lmes_chl_pp_and_trends", styles:"lmes_chla_change", transparent:true, format:'image/png'},
     {layerId:'chlachange', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var pp_group=new OpenLayers.Layer.WMS(
     "PP_Level",
     GWCLMES,
     {layers:"lmes:lmes_chl_pp_and_trends", transparent:true, styles:'lmes_ppd_group',format:'image/png'},
     {layerId:'pp_group', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   /*
      var pp_mean=new OpenLayers.Layer.WMS(
      "PP_MEAN",
      //"http://onesharedocean.org/geoserver/lmes/wms",
      GWCLMES,
      {layers:"lmes:lmes_chl_pp_and_trends", transparent:true, styles:'lmes_ppy',format:'image/png'},
      {layerId:'pp_mean', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false}
      );
    */
   var pp_trend=new OpenLayers.Layer.WMS(
     "PP_TREND",
     //"http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_chl_pp_and_trends",
      transparent:true, styles:'lmes_ppy_change', format:'image/png'},
     {layerId:'pp_trend', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   //SST net change
   var sst_net_change=new OpenLayers.Layer.WMS(
     "SST net change",
     //    "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_sst_net_change", transparent:true, styles:'lmes_sst_change', format:'image/png'},
     {layerId:'sst_net_change', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   // fish and fisheries
   var fish_subsidy = new OpenLayers.Layer.WMS(
     "Fisheries subsidy",
     GWCLMES,
     {layers:'lmes:lmes_fisheries_10year_average', transparent:true, styles:'lmes_fisheries_10year_subsidy', format:'image/png'},
     {layerId:'fishSubsidy', tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );
   var fish_footprint = new OpenLayers.Layer.WMS(
     "Fisheries ecological footprint",
     GWCLMES,
     {layers:'lmes:lmes_fisheries_10year_average', transparent:true, styles:'lmes_fisheries_10year_footprint', format:'image/png'},
     {layerId:'fishFootprint', tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );
   var fish_MTI = new OpenLayers.Layer.WMS(
     "Fisheries MTI",
     GWCLMES,
     {layers:'lmes:lmes_fisheries_10year_average',transparent:true,styles:'lmes_fisheries_10year_mti',format:'image/png'},
     {layerId:'fishMTI',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_FiB = new OpenLayers.Layer.WMS(
     "Fisheries FiB",
     GWCLMES,
     {layers:'lmes:lmes_fisheries_10year_average',transparent:true,styles:'lmes_fisheries_10year_fib',format:'image/png'},
     {layerId:'fishFiB',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_stock_number = new OpenLayers.Layer.WMS(
     "Fisheries Stock Status (number)",
     GWCLMES,
     {layers:'lmes:lmes_fisheries_10year_average',transparent:true,styles:'lmes_fisheries_10year_stockstatus_number',format:'image/png'},
     {layerId:'fishStockNumber',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_stock_biomass = new OpenLayers.Layer.WMS(
     "Fisheries Stock Status (biomass)",
     GWCLMES,
     {layers:"lmes:lmes_fisheries_10year_average",transparent:true,styles:"lmes_fisheries_10year_stockstatus_biomass",format:"image/png"},
     {layerId:'fishStockBiomass',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_trawling = new OpenLayers.Layer.WMS(
     "Fisheries Trawling",
     GWCLMES,
     {layers:"lmes:lmes_fisheries_10year_average",transparent:true,styles:"lmes_fisheries_10year_trawling",format:"image/png"},
     {layerId:'fishTrawling',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_rate_effective = new OpenLayers.Layer.WMS(
     "Fisheries Rate effective fishing",
     GWCLMES,
     {layers:"lmes:lmes_fisheries_10year_average",transparent:true,styles:"lmes_fisheries_10year_ratechange_effectivefishing",format:"image/png"},
     {layerId:'fishRateEffective',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );
   var fish_change_percent = new OpenLayers.Layer.WMS(
     "Fisheries percent change",
     GWCLMES,
     {layers:"lmes:lmes_fisheries_10year_average",transparent:true,styles:"lmes_fisheries_10year_percentchange",format:"image/png"},
     {layerId:'fishPercentChange',tiled:true,tileSize:TSIZE,tileOrigin:TORG,isBaseLayer:false,opacity:1,visibility:false,wrapDateLine:true}
   );

   var catch_rel_2030 = new OpenLayers.Layer.WMS(
     "Catch change (2030)",
     GWCLMES,
     {layers:"lmes_and_catch_relative_2030", transparent:true, styles:"futureFishCatch_percent", format:'image/png'},
     {layerId:'fishRelative2030', tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var catch_rel_2050 = new OpenLayers.Layer.WMS(
     "Catch change (2050)",
     GWCLMES,
     {layers:"lmes_and_catch_relative_2050", transparent:true, styles:"futureFishCatch_percent", format:'image/png'},
     {layerId:'fishRelative2050', tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   //Ecosystem
   //Coral reefs
   var coral = new OpenLayers.Layer.WMS(
     "Coral coverage",
     //    "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_coral_coverage",  styles:'coral_coverage', transparent:true, format:'image/png'},
     {layerId:'coral', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var coral_int_risk = new OpenLayers.Layer.WMS(
     "Reefs integrated risk",
     GWCLMES,
     {layers:"lmes:lmes_reef_total_integrated_risk", styles:'', transparent:true, format:'image/png'},
     {layerId:'reef_risk',tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
     );

   // mangroves
//   var mangroves=new OpenLayers.Layer.WMS(
//     "Mangroves",
//     GWCLMES,
//     {layers:"lmes:",  styles:'lmes_mangrovespercent', transparent:true, format:'image/png'},
//     {layerId:'mangroves', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
//   );

   //Areas
   var areas= new OpenLayers.Layer.WMS(
     "Shelf Area",
     //"http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_shelf_area",  styles:'lmes_shelf_area', transparent:true, format:'image/png'},
     {layerId:'areas', tiled:true,tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   // Impacts
   var cumulImpact = new OpenLayers.Layer.WMS(
     "CumulImpact",
     GWCLMES,
     {layers:"lmes:lmes_cumulative_human_impact", styles:'lmes_chi', transparent:true, format:'image/png'},
     {layerId:'cumulImpact', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   // Socio eco: ohi
   var ohi = new OpenLayers.Layer.WMS(
     "OHI",
     "http://onesharedocean.org/geoserver/lmes/wms",
     {layers:"lmes:lmes_ohi", styles:'lmes_ohi', transparent:true, format:'image/png'},
     {layerId:'ohi', tiled:true,tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   // Socio eco: population
   var population = new OpenLayers.Layer.WMS(
     "Population",
     "http://onesharedocean.org/geoserver/lmes/wms",
     {layers:"lmes:lmes_area_pop_nldi_hdi", styles:'lmes_socioeco_popdensity', transparent:true, format:'image/png'},
     {layerId:'population', tiled:true,tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var hdi = new OpenLayers.Layer.WMS(
     "HDI",
     //"http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_area_pop_nldi_hdi", styles:'lmes_socioeco_hdi', transparent:true, format:'image/png'},
     {layerId:'hdi', tiled:true,tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var nldi = new OpenLayers.Layer.WMS(
     "NLDI",
     // "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_area_pop_nldi_hdi", styles:'lmes_socioeco_nldi', transparent:true, format:'image/png'},
     {layerId:'nldi', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var overfishing = new OpenLayers.Layer.WMS(
     "Overfishing",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //    GWCLMES,
     {layers:"lmes:lmes_fishing_revenues_indicators", styles:'lmes_socioeco_overfishing', transparent:true, format:'image/png'},
     {layerId:'overfishing', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   // icep
   // there is a problem with the cache management
   var icep = new OpenLayers.Layer.WMS(
     "ICEP",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2000", styles:'lmes_icep_cat', transparent:true, format:'image/png'},
     {layerId:'icep', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var icep2030 = new OpenLayers.Layer.WMS(
     "ICEP 2030",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2030", styles:'lmes_icep_cat', transparent:true, format:'image/png'},
     {layerId:'icep2030', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var icep2050 = new OpenLayers.Layer.WMS(
     "ICEP 2050",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2050", styles:'lmes_icep_cat', transparent:true, format:'image/png'},
     {layerId:'icep2050', tiled:true, tileSize:TSIZE, tileOrigin: TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var ld_din = new OpenLayers.Layer.WMS(
     "ld_din",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2000", styles:'lmes_ld_din_cat', transparent:true, format:'image/png'},
     {layerId:'ld_din', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var ld_din2030 = new OpenLayers.Layer.WMS(
     "ld_din2030",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2030", styles:'lmes_ld_din_cat', transparent:true, format:'image/png'},
     {layerId:'ld_din2030', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var ld_din2050 = new OpenLayers.Layer.WMS(
     "ld_din2050",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2050", styles:'lmes_ld_din_cat', transparent:true, format:'image/png'},
     {layerId:'ld_din2050', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var merged_ind = new OpenLayers.Layer.WMS(
     "Merged indicator",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2000", styles:'lmes_mergedin_cat', transparent:true, format:'image/png'},
     {layerId:'merged_ind', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var merged_ind2030 = new OpenLayers.Layer.WMS(
     "Merged indicator 2030",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2030", styles:'lmes_mergedin_cat', transparent:true, format:'image/png'},
     {layerId:'merged_ind2030', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var merged_ind2050 = new OpenLayers.Layer.WMS(
     "Merged indicator 2050",
     "http://onesharedocean.org/geoserver/lmes/wms",
     //GWCLMES,
     {layers:"lmes:lmes_nutrients_loading_eutrophication_2050", styles:'lmes_mergedin_cat', transparent:true, format:'image/png'},
     {layerId:'merged_ind2050', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   /*
      var icepn = new OpenLayers.Layer.WMS(
      "ICEP N",
      "http://onesharedocean.org/geoserver/lmes/wms",
      {layers:"lmes:nutrients_icep_LMES66", styles:'lmes_nutrients_icep_n', transparent:true, format:'image/png'},
      {layerId:'icep_n', singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
      );

      var icepp = new OpenLayers.Layer.WMS(
      "ICEP P",
      "http://onesharedocean.org/geoserver/lmes/wms",
      {layers:"lmes:nutrients_icep_LMES66", styles:'lmes_nutrients_icep_p', transparent:true, format:'image/png'},
      {layerId:'icep_p', singleTile:true, isBaseLayer:false, opacity:1, visibility:false}
      );
    */
   var plasticsModelMicroCount = new OpenLayers.Layer.WMS(
     "Micro plastics",
     //    "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_plastics_modeldistribution", styles:'lmes_plasticsdistr_5classes_micro_count', transparent:true, format:'image/png'},
     {layerId:'plasticsmicro', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var plasticsModelMacroWeight = new OpenLayers.Layer.WMS(
     "Macro plastics",
     //    "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lmes_plastics_modeldistribution", styles:'lmes_plasticsdistr_5classes_macro_weight', transparent:true, format:'image/png'},
     {layerId:'plasticsmacro', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var governance_int = new OpenLayers.Layer.WMS(
     "Integration",
     GWCLMES,
     {layers:"lmes:lme_governance_analysis_indicators", styles:'lmes_governance_integration', transparent:true, format:'image/png'},
     {layerId:'govInt', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var governance_engt = new OpenLayers.Layer.WMS(
     "Engagement",
     GWCLMES,
     {layers:"lmes:lme_governance_analysis_indicators", styles:'lmes_governance_engagement', transparent:true, format:'image/png'},
     {layerId:'govEng', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var governance_compl = new OpenLayers.Layer.WMS(
     "Completeness",
     //        "http://onesharedocean.org/geoserver/lmes/wms",
     GWCLMES,
     {layers:"lmes:lme_governance_analysis_indicators", styles:'lmes_governance_completeness', transparent:true, format:'image/png'},
     {layerId:'govCompl', tiled:true, tileSize:TSIZE, tileOrigin: TORG,isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );


   var pops_ddt = new OpenLayers.Layer.WMS(
     "DDT score",
     GWCLMES,
     {layers:"lmes:lmes_pops", styles:'lmes_pops_risk_ddt', transparent:true, format:'image/png'},
     {layerId:'pops_ddt', tiled:true, tileSize:TSIZE, tileOrigin:TORG, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var pops_hch = new OpenLayers.Layer.WMS(
     "HCHs score",
     GWCLMES,
     {layers:"lmes:lmes_pops", styles:'lmes_pops_risk_hch', transparent:true, format:'image/png'},
     {layerId:'pops_hch', tiled:true, tileSize:TSIZE, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   var pops_pcb = new OpenLayers.Layer.WMS(
     "DDT score",
     GWCLMES,
     {layers:"lmes:lmes_pops", styles:'lmes_pops_risk_pcb', transparent:true, format:'image/png'},
     {layerId:'pops_pcb', tiled:true, tileSize:TSIZE, isBaseLayer:false, opacity:1, visibility:false, wrapDateLine:true}
   );

   //Add layers to map objects
   map.addLayers([
     worldtop,
     lmes,
     chla, chlachange, pp_group, pp_trend,
     sst_net_change,
     fish_subsidy, fish_footprint, fish_MTI,
     fish_FiB, fish_stock_number, fish_stock_biomass,
     fish_trawling,fish_rate_effective,fish_change_percent,
     catch_rel_2030, catch_rel_2050,
     coral, coral_int_risk,
//     mangroves,
     areas,
     cumulImpact,
     ohi, population, hdi, nldi, overfishing,
     icep, icep2030, icep2050, ld_din, ld_din2030, ld_din2050, merged_ind, merged_ind2030, merged_ind2050,
     plasticsModelMicroCount, plasticsModelMacroWeight,
     governance_int, governance_engt, governance_compl,
     pops_ddt, pops_hch, pops_pcb
   ]);

   map.zoomToMaxExtent();
   //Defines behaviour of map interactions
   var info = new OpenLayers.Control.WMSGetFeatureInfo({
     url:GWCLMES, //'http://onesharedocean.org/geoserver/lmes/wms',
     output:'features',
     infoFormat:'application/vnd.ogc.gml',
     format: new OpenLayers.Format.GML,
     layers:[lmes],
     eventListeners: {
       getfeatureinfo: function(event) {
         if (typeof(event.features[0])=='undefined'){return};
         LME_NUMBER=event.features[0].attributes.LME_NUMBER;
         LME_NAME=event.features[0].attributes.LME_NAME;
         document.location.href = '/'+lmeAliasList[parseInt(LME_NUMBER)-1];
         jQuery('#buttonLMEs').attr('href','/'+lmeAliasList[parseInt(LME_NUMBER)-1]);
         jQuery('#buttonLMEs').css('display', 'inline-block');
         jQuery('#tags').prop('value', LME_NUMBER+' '+LME_NAME);
         jQuery('#tags').css('color', '#000000');
         jQuery('#deleteTags').css('display', 'inline-block');
       }
     }
   });

   map.addControl(info);
   info.activate();


   //Handles interaction between menu, map and legends
   jQuery("#accordion li").click(function(event){
     event.stopPropagation();
     if(jQuery(this).attr('rel') != undefined){
       showLayer(this);
     }
     if(jQuery(this).find('ul').first().css('display') == 'block'){
       jQuery(this).find('ul').first().slideUp(function(){
         jQuery('.mainTool').height(Math.max(jQuery('.column-map').height(),jQuery('.column‌​-layers').height(),jQuery('#accordion').height()));
       });
     } else {
       className = '.'+jQuery(this).attr('class').split(/[ \n\r\t\f]+/).join('.');
       jQuery(className).not(this).find('ul').slideUp(function(){
         jQuery('.mainTool').height(Math.max(jQuery('.column-map').height(),jQuery('.column‌​-layers').height(),jQuery('#accordion').height()));
       });
       jQuery(this).find('ul').first().slideDown(function(){
         jQuery('.mainTool').height(Math.max(jQuery('.column-map').height(),jQuery('.column‌​-layers').height(),jQuery('#accordion').height()));
       });
     }
   });


   //Includes legends file
   jQuery.get("/iframes/lmes/maps/lmes_portal_legends.html", function(data){
     jQuery('#legOL').html(data);
     jQuery('.mainTool').height(Math.max(jQuery('.column-map').height(),jQuery('.column‌​-layers').height()));
   });

   //Post-change of the controls position on the map
   jQuery('#OpenLayers_Control_PanZoom_2').css('top','220px');

   //Business logic for controlling the layers and legends display
   function showLayer(el){
     menuItem = jQuery(el).attr('rel');
     jQuery('li span').removeClass('selected');
     jQuery(el).find('span').first().addClass('selected');
     var l = false;
     for(var i=1; i<map.getNumLayers();i++){
       var layerId = map.layers[i].layerId;
       if(layerId == menuItem){
         l = {id: layerId, pos: i};
       }
     }
     if(l){
       for(var i=1; i<map.getNumLayers();i++){
         map.layers[i].setVisibility(false);
       }
       jQuery('.legendOL').css('display', 'none');
       map.layers[l.pos].setVisibility(true);
       jQuery('#leg-'+l.id).css('display', 'block');
     } else {
       if(menuItem.substring(0,4) != 'desc'){

         jQuery('.legendOL').css('display', 'none');
       } else {
         jQuery('.legendOL').css('display', 'none');
         jQuery('#leg-'+menuItem).css('display', 'block');
       }
     }
   }

   //Solves the z-index issue with Drupal overlay
   jQuery('#mapOL').find('div').first().css('z-index','0');

 }); //Document ready   
</script>



<?php include('/data/iframes/common/lmesnav/lmenav.php'); ?>


<div class="ui-widget" style="line-height:38px;">
  <input id="tags" style="position:relative; top:-2px; line-height:30px; width:370px; font-family:sans-serif; font-size:18px; border:1px solid #C0C0C0; color:#c0C0C0; vertical-align:middle" value="Search for an LME or click on the map"/>
  <img id="deleteTags" src="/iframes/lmes/images/delete_gray.png" title="Clear search box" style="display:none; vertical-align:middle;margin-right:20px" />
  <div id="buttonLMEs">read the LME factsheet</div>
  <div id="results" class="ui-front autocomplete; "></div>
</div>

<div class="mainTool">
  <div class="column-map">
    <div id="mapOL" style="width:750px; height:375px; cursor:pointer"></div>
<!--
    <span style="font-size:0.75em;">Political boundaries: <a href="http://www.fao.org/geonetwork/srv/en/main.home?uuid=f7e7adb0-88fd-11da-a88f-000d939bc5d8">GAUL (2015)</a>, FAO Statistics Division. For other data, metainformation is found in the <a href="/node/27">data page</a>.</span><br/>
-->
    <div id="legOL" style="clear:both;"></div>
  </div>
  <div class="column-layers">

    <!--    <div style="background-color:#F0DF90; color:#000000; margin-bottom:10px; cursor:pointer" onclick="window.open('/node/244');">Read more about LMEs</div> -->

    <div id="accordion">
      <ul>
        <li class="buttonLMEs" onclick="window.open('<?php echo drupal_get_path_alias('/node/244'); ?>');">Read more about LMEs</li>
        <li class="empty"></li>
        <li class="l1" rel="lmes"><span class="selected">LMEs</span>
        </li>
        <li class="l1" rel="descProductivity"><span>Productivity</span>
          <ul>
            <li class="l2" rel="chla"><span>Chlorophyll-A</span></li>
            <li class="l2" rel="chlachange"><span class="double">Chlorophyll-A<br/>(% change)</span></li>
            <li class="l2" rel="pp_group"><span>Primary productivity group</span></li>
            <li class="l2" rel="pp_trend"><span class="double">Primary productivity<br/>(% change)</span></li>
            <li class="l2" rel="sst_net_change"><span>SST net change</li>
          </ul>
        </li>
        <li class="l1" rel="descFisheries"><span>Fish &amp; Fisheries</span>
          <ul>
	    <li class="l2" rel="fishSubsidy"><span>Fishing subsidy</span></li>
	    <li class="l2" rel="fishFootprint"><span>Ecological footprint</span></li>
	    <li class="l2" rel="fishMTI"><span>Marine Trophic Index</span></li>
	    <li class="l2" rel="fishFiB"><span>Fishing-in-Balance</span></li>
	    <li class="l2" rel="fishStockNumber"><span>Stock status (number)</span></li>
	    <li class="l2" rel="fishStockBiomass"><span>Stock status (biomass)</span></li>
	    <li class="l2" rel="fishTrawling"><span>Catch from bottom trawling</span></li>
	    <li class="l2" rel="fishRateEffective"><span>Fishing effort</span></li>
            <li class="l2" rel="fishRelative2030"><span>Catch change (2030), sub-LME</span></li>
            <li class="l2" rel="fishRelative2050"><span>Catch change (2050), sub-LME</span></li>
	    <li class="l2" rel="fishPercentChange"><span>Percent change in catch potential (2050)</span></li>
          </ul>
        </li>
        <li class="l1"><span>Pollution</span>
          <ul>
            <li class="l2 level"><span>Nutrients</span>
              <ul>
                <li class="l3" rel="icep"><span>ICEP</span></li>
                <li class="l3" rel="icep2030"><span>ICEP (2030)</span></li>
                <li class="l3" rel="icep2050"><span>ICEP (2050)</span></li>
                <li class="l3" rel="ld_din"><span>DIN loading</span></li>
                <li class="l3" rel="ld_din2030"><span>DIN loading (2030)</span></li>
                <li class="l3" rel="ld_din2050"><span>DIN loading (2050)</span></li>
                <li class="l3" rel="merged_ind"><span>Merged indicator</span></li>
                <li class="l3" rel="merged_ind2030"><span>Merged indicator (2030)</span></li>
                <li class="l3" rel="merged_ind2050"><span>Merged indicator (2050)</span></li>
              </ul>
            </li>
            <li class="l2 level"><span>Plastics</span>
              <ul>
                <li class="l3" rel="plasticsmicro"><span>Micro Plastics</span></li>
                <li class="l3" rel="plasticsmacro"><span>Macro Plastics</span></li>
              </ul>
            </li>

            <li class="l2 level"><span>POPs</span>
              <ul>
                <li class="l3" rel="pops_ddt"><span>DDT score</span></li>
                <li class="l3" rel="pops_hch"><span>HCHs score</span></li>
                <li class="l3" rel="pops_pcb"><span>PCBs score</span></li>
              </ul>
            </li>
          </ul>
        </li>
        <li class="l1"><span>Ecosystem health</span>
          <ul>
            <li class="l2" rel="ohi"><span>Ocean Health Index</span></li>
            <li class="l2" rel="cumulImpact"><span>Cumulative Impact</span></li>
            <li class="l2" rel="coral"><span>Coral coverage</span></li>
	    <li class="l2" rel="reef_risk"><span>Reefs integrated risk</span></li>
<!--            <li class="l2" rel="mangroves"><span>Mangroves</span></li> -->
          </ul>
        </li>
        <li class="l1"><span>Socio-economics</span>
          <ul>
            <li class="l2" rel="population"><span>Population density</span></li>
            <li class="l2" rel="hdi"><span>HDI</span></li>
            <li class="l2" rel="nldi"><span>NLDI</span></li>
            <li class="l2" rel="overfishing"><span>Overfishing</span></li>
          </ul>
        </li>
        <li class="l1"><span>Governance</span>
          <ul>
            <li class="l2" rel="govInt"><span>Integration</span></li>
            <li class="l2" rel="govEng"><span>Engagement</span></li>
            <li class="l2" rel="govCompl"><span>Completeness</span></li>
          </ul>
        </li>
        <!--
        <li class="l1"><span>General information</span>
        <ul>
        <li class="l2" rel="areas"><span>LMEs Area</span></li>
        </ul>
        </li>
        -->
        <li class="empty"></li>
        <li class="l1 lastItem"><span>Global Comparative Assessment</span>
        </li>
        <!--<li class="empty"></li>-->
        <!--         <li class="buttonIntroLMEs" onclick="window.open('/node/244');">Read more about LMEs</li> -->
        <li class="empty"></li>
        <li class="buttonWP" onclick="window.open('<?php  echo drupal_get_path_alias('node/242'); ?>');">Western Pacific Warm Pool</li>
        <!--<li class="empty"></li>
        <li class="buttonGCA">Global Comparative Assessment</li>-->
      </ul>
    </div>
  </div>
</div>