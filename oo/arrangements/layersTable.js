//TEMPORARY -> CREATE TABLE
	var themes = ['integration', 'fisheries', 'pollution', 'biodiversity', 'climate', 'abnj'];
	var thisCluster = [];
	
	jQuery.get('layers_themes_matrix_input.txt',function(data){
		//fill the array of the layers, according to the layers present on the file
		var lines = data.split('\n');
		jQuery.each(lines, function(lineNo, line){
			var lineArr = line.split(';');
			jQuery.each(map.layers, function(){
				
				if(jQuery(this).prop('layerId')){
					if(jQuery(this).prop('layerId').toLowerCase() === lineArr[0].toLowerCase()){
						var obj = {layerId:lineArr[0], themes:[]};
						for(var i=1;i<lineArr.length;i++){
							if(lineArr[i] != ""){
								obj.themes.push(themes[i-1]);
							}
						}
						thisCluster.push(obj);
					//}
				}
				}
			});
			
		});
		//console.log(JSON.stringify(thisCluster));
		
			
		//Create the table
		jQuery.each(thisCluster, function(){
			if(jQuery(this).prop('layerId')){
				var layer = this;
				var tr = document.createElement('TR');
				var td0 = document.createElement('TD');
				jQuery(td0)
					.html(this.layerId)
					.appendTo(tr);
				jQuery.each(themes, function(){
					var td = document.createElement('TD');
					if(layer.themes.indexOf(this.toString()) > -1 ){
						jQuery(td)
							.addClass('selectable')
							.attr('xlayer', layer.layerId)
							.attr('xtheme', this.toString());
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
		//If host allows it, resize the frame from within
   if(sameHost){
	 if (window.frameElement != null) {
	   var iFrame = parent.document.getElementById(window.frameElement.getAttribute('id'));
	   if(iFrame != null){
		 iFrame.style.height = jQuery('table').height()+jQuery('#map-id').height()+jQuery('#tableInfo').height()+100+"px";
		}
	 }
   }
	   

       //Logic for the interaction between the table and the map
       jQuery('tbody .selectable').addClass('selected');
       jQuery('tbody .selectable')
             .hover(function(){
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

	});//end get

	function cbHandler(num,el){
		var cI = jQuery(el).prop('cellIndex');
		var xt = jQuery(el).attr('xtheme');
		var nD = jQuery('tbody tr .disabled[xtheme="'+xt+'"]').length;
		var nS = jQuery('tbody tr .selected[xtheme="'+xt+'"]').length;
		var nSel = jQuery('tbody tr .selectable[xtheme="'+xt+'"]').length;
		if(nD == 0){ //check
			jQuery('thead td[theme="'+xt+'"] input[type="checkbox"]')
				.prop('checked', true)
				.prop('indeterminate', false);
		} else if(nD == nSel){
			jQuery('thead td[theme="'+xt+'"] input[type="checkbox"]')
				.prop('checked', false)
				.prop('indeterminate', false);
		} else { //indefinite
			jQuery('thead td[theme="'+xt+'"] input[type="checkbox"]')
				.prop('checked', false)
				.prop('indeterminate', true);
		}
	}//end function