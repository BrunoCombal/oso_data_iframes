//TEMPORARY -> CREATE TABLE
var allClusters = {"layer":["integration","fisheries","pollution","biodiversity","climate","abnj"],"Abidjan":["pollution","abnj"],"ACAP":["biodiversity","abnj"],"AFC":["pollution","abnj"],"Antigua":["fisheries","pollution","biodiversity","abnj"],"APFIC":["fisheries","abnj"],"Arctic":["fisheries","pollution","biodiversity","climate","abnj"],"ATS":["fisheries","pollution","biodiversity","climate","abnj"],"Barcelona":["abnj"],"BOBP-IGO":["fisheries","abnj"],"Bucharest":["biodiversity","abnj"],"BWMC":["pollution","abnj"],"Cartagena":["biodiversity","abnj"],"CBD":["biodiversity","abnj"],"CCAMLR":["fisheries","biodiversity","abnj"],"CCAS":["biodiversity","abnj"],"CCBSP":["fisheries","abnj"],"CCSBT":["fisheries","abnj"],"CECAF":["fisheries","abnj"],"CITES":["fisheries","biodiversity","abnj"],"CMS":["fisheries","biodiversity","abnj"],"COMHAFAT":["fisheries","abnj"],"FFAC":["fisheries","abnj"],"GFCM":["fisheries","abnj"],"GPA":["pollution","abnj"],"HELCON":["pollution","biodiversity","abnj"],"Hong Kong":["pollution","abnj"],"HSDN":["fisheries","abnj"],"IAC":["biodiversity","abnj"],"IATTC":["fisheries","abnj"],"ICCAT":["fisheries","abnj"],"ICES":["integration","fisheries","pollution","biodiversity","climate","abnj"],"IOTC":["fisheries","abnj"],"IPHC":["fisheries","abnj"],"IWC":["fisheries","abnj"],"Jeddah":["abnj"],"Kuwait":["pollution","abnj"],"Lima":["pollution","abnj"],"London":["pollution","climate","abnj"],"MARPOL":["pollution","abnj"],"Montreal":["pollution","abnj"],"NAFO":["fisheries","abnj"],"Nairobi":["biodiversity","abnj"],"NAMMCO":["fisheries","biodiversity","abnj"],"NASCO":["fisheries","abnj"],"NEAFC":["fisheries","abnj"],"Niue":["fisheries","abnj"],"Noumea":["pollution","biodiversity","climate","abnj"],"NPAFC":["fisheries","abnj"],"OPRC":["pollution","abnj"],"OSPAR":["pollution","biodiversity","abnj"],"PICES":["fisheries","abnj"],"PIF":["integration","fisheries","pollution","biodiversity","climate","abnj"],"PNA":["fisheries","abnj"],"PSC":["fisheries","abnj"],"SCAR":["integration","fisheries","pollution","biodiversity","climate","abnj"],"SEAFDEC":["fisheries","abnj"],"SEAFO":["fisheries","abnj"],"SIOFA":["fisheries","biodiversity","abnj"],"SP":["fisheries","abnj"],"SPC":["fisheries","abnj"],"SPRFMO":["fisheries","abnj"],"Stockholm":["pollution","abnj"],"FAO":["fisheries","abnj"],"UNCLOS":["fisheries","pollution","biodiversity","abnj"],"UNFCC":["climate","abnj"],"UNFSA":["fisheries","pollution","biodiversity","abnj"],"Vienna":["pollution","abnj"],"WCPFC":["fisheries","abnj"],"WECAFC":["fisheries","biodiversity","abnj"],"ACCOBAMS":["fisheries","biodiversity","abnj"],"ACP":["biodiversity","abnj"],"APEC":["fisheries","abnj"],"ASCOBANS":["fisheries","biodiversity","abnj"],"ASEAN":["integration","fisheries","pollution","biodiversity","climate","abnj"],"BCC":["fisheries","pollution","biodiversity","abnj"],"BEAC":["pollution","biodiversity","abnj"],"BIMSTEC":["integration","fisheries","pollution","biodiversity","climate","abnj"],"Bonn":["pollution","abnj"],"BRC":["pollution","biodiversity","abnj"],"COBSEA":["pollution","biodiversity","abnj"],"COREP":["fisheries","abnj"],"CPPS":["fisheries","pollution","biodiversity","abnj"],"CRFM":["fisheries","abnj"],"Dugong":["biodiversity","abnj"],"EU":["fisheries","biodiversity","abnj"],"FCWC":["fisheries","abnj"],"IOSEA":["biodiversity","abnj"],"Mexus":["pollution","abnj"],"NOWPAP":["integration","fisheries","pollution","biodiversity","climate","abnj"],"OLDEPESCA":["fisheries","abnj"],"OSPESCA":["fisheries","abnj"],"PEMSEA":["pollution","abnj"],"PRCM":["fisheries","abnj"],"RECOFI":["fisheries","abnj"],"Rio":["fisheries","pollution","abnj"],"SAARC":["integration","fisheries","pollution","biodiversity","abnj"],"SACEP":["pollution","abnj"],"SADC":["fisheries","abnj"],"SRFC":["fisheries","abnj"],"SWIOFC":["fisheries","abnj"], "Pacific_Islands_Cetaceans":["biodiversity"], "East_Atlantic_Turtles":["biodiversity"], "West_African_Cetaceans":["biodiversity"]};

var themes = allClusters.layer;
var thisCluster = [];



//fill the array of the layers, according to the layers present on the file
jQuery.each(map.layers, function(){
    if(jQuery(this).prop('layerId')){
        var layerId = jQuery(this).prop('layerId');
        var layer = this;
        var tr = document.createElement('TR');
        var td0 = document.createElement('TD');
        jQuery(td0)
            .html(this.name)
            .addClass('firstTD')
            .appendTo(tr);
        jQuery.each(themes, function(){
            var td = document.createElement('TD');
            if(allClusters[layerId]){
                if(allClusters[layerId].indexOf(this.toString()) > -1 ){
                    jQuery(td)
                        .addClass('selectable')
                        .attr('xlayer', layerId.toLowerCase())
                        .attr('xtheme', this.toString());
                }
            }
            jQuery([td]).appendTo(tr);
        });
        jQuery(tr).appendTo('tbody');
    }
});
jQuery('thead td[theme="integration"]').html('Integration');
jQuery('thead td[theme="fisheries"]').html('Fisheries');
jQuery('thead td[theme="pollution"]').html('Pollution');
jQuery('thead td[theme="biodiversity"]').html('Biodiversity');
jQuery('thead td[theme="climate"]').html('Climate Change');
jQuery('thead td[theme="abnj"]').html('Relevance to ABNJ');
jQuery('#tableInfo').html('In the table below, a green rectangle at the intersection of a line and a column means that an arrangement (line) covers a themes (column). Click in the table to start with an empty map and show or hide each arrangement individually.');

//Check if we have access to parent document (normally not if the iframe is loaded from a different host
var sameHost = false;
try{
    parent.document;
    sameHost = true;
}catch(e){
    iFrame = null;
}
//If host allows it, resize the frame from within
if(sameHost){
    if (window.frameElement != null) {
        var iFrame = parent.document.getElementById(window.frameElement.getAttribute('id'));
        if(iFrame != null){
            iFrame.style.height = jQuery('table').height()+jQuery('#map-id').height()+jQuery('#tableInfo').height()+100+"px";
            iFrame.style.width = '960px';
        }
    }
}


//Logic for the interaction between the table and the map
jQuery('tbody .selectable').addClass('selected');
jQuery('tbody .selectable')
    .mouseenter(function(){
        jQuery(this).parent().children().not('.selectable').addClass('trdissel');
        jQuery(this).parent().find('.selectable').addClass('hover');
    })
    .mouseleave(function(){
        jQuery(this).parent().find('.selectable').removeClass('hover');
        jQuery(this).parent().children().not('.selectable').removeClass('trdissel');
    })
    .click(function(){
        var td = this;
        var layer = false;
        jQuery.each(map.layers, function(){
            if(jQuery(this).prop('layerId')){
                if(jQuery(this).prop('layerId').toLowerCase() == jQuery(td).attr('xlayer').toLowerCase()){
                    layer = this;
                }
            }
        });
        if(jQuery(this).hasClass('selected')){
            if(jQuery('.selectable').not(jQuery(this).siblings()).not('.selected').length == 0){
                //console.log('all selected to this selected');
                jQuery('.selectable')
                    .removeClass('selected')
                    .addClass('disabled');
                jQuery(this).parent().find('.selectable')
                    .removeClass('disabled')
                    .addClass('selected')
                    .removeClass('hover');
                jQuery.each(map.layers, function(){
                    if(jQuery(this).prop('layerId')){
                        if(jQuery(this).prop('layerId').toLowerCase() != layer.layerId.toLowerCase()){
                            map.getLayer(this.id).setVisibility(false);
                        }
                    }
                });
            } else if(jQuery('.selected').not('disabled').not(jQuery(this).siblings()).not(jQuery(this)).length == 0){
                //console.log('selected this to all selected');
                jQuery('.selectable')
                    .addClass('selected')
                    .removeClass('disabled');
                jQuery.each(map.layers, function(){
                    if(jQuery(this).prop('layerId')){
                        map.getLayer(this.id).setVisibility(true);
                    }
                });
            } else {
                //console.log('selected this to disabled');
                jQuery(this).parent().find('.selectable')
                    .removeClass('selected')
                    .removeClass('hover')
                    .addClass('disabled');
                map.getLayer(layer.id).setVisibility(false);
            }
        } else if(jQuery(this).hasClass('disabled')){
            //console.log('disabled to selected');
            map.getLayer(layer.id).setVisibility(true);
            jQuery(this).parent().find('.selectable')
                .addClass('selected')
                .removeClass('disabled')
                .removeClass('hover');
        } else {
            return false;
        }
    });

