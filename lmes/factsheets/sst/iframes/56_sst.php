<?php
$templateCache = true;
$sourceCache = true;
if($templateCache == true){
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Net SST change in LMEs, 1957-2012</title>
    <link rel="stylesheet" href="/sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.css" />
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1//jquery-ui.min.js"></script>
    
	<script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/highcharts.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/modules/exporting.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/Highcharts-4.0.4/js/highcharts-more.js"></script>
    <script type="text/javascript" src="/iframes/common/js/lmes.js"></script>
	
	<style>
      .ui-autocomplete{max-height:100px, overflow-y:auto;overflow-x:hidden;}
      html .ui-autocomplete{height:100px} /* IE6 does not support max-height */
      .ui-widget{font-size:12px;}
	  #viewData {
		font-family: Verdana;
		font-size: 10px;
		cursor: pointer;
	  }
    </style>
    <script type="text/javascript">
$(document).ready(function() {

        var thisLMECode = 56;
        var addedLMECode = -1;
		var plotCounter = -1;
		var chart = true;
		
		//Check if we have access to parent document (normally not if the iframe is loaded from a different host
	var sameHost = false;
	try{
		parent.document;
		sameHost = true;
	}catch(e){
		iFrame = null;
	}
	//Define the behaviour of the View Data link according to the host permissions
	$('#viewData').click(function(){
		var sourceURL = "http://onesharedocean.org/?q=node/27#381";
		if(sameHost){
			window.parent.window.location = sourceURL;
		} else {
			copyToClipboard(sourceURL);
		}
	});
	//If host allows it, resize the frame from within
	if(sameHost){
		if (window.frameElement != null) {
			var iFrame = parent.document.getElementById(window.frameElement.getAttribute('id'));
			if(iFrame != null){
				iFrame.style.height = '500px';
			}
		}
	}

        var options = {
            chart:{renderTo:'container', zoomType:'x', resetZoomButton: {relativeTo: 'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-50}, 
            theme:{fill: 'white', stroke:'silver', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                  }
			},
            legend:{align:'right', verticalAlign:'top', layout:'vertical', y:25},
            credits:{enabled:false},
            title:{text:'SST ('+'East Siberian Sea'+')'},
            xAxis:{title: {text:'Year'},labels:{formatter: function() {return this.value }}
                  },
            yAxis:{title: { text: 'Temperature (°C)', floor:0},
                  plotLines: [{value: 0,width: 1, color: '#808080' }]
                  },
            tooltip:{valueSuffix: '°C', useHTML:true, valueDecimals: 2,
                     formatter: function() {
					 
					 var tt = this.series.legendItem.textStr.substring(5)+" ";
				jQuery.each(availableTags, function(index, value){
					if(value.indexOf(tt) != -1){
						tt = value;
					}
				});
					 return '<b>LME '+tt+'</b><br/>'+'Temperature' + '<br/>' + (this.x)+': '+this.y.toFixed(2)+' °C';}
                    },
            plotOptions:{series:{animation:false}},
            series: []
            };

		function doAddPlot(codeLME){
			plotCounter++;
			
			var rand = Math.floor(Math.random()*999999999);
			$.get('../data/sst_data_trend.txt'<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
				var lines = data.split('\n');
				var plotLME={};
				iplot=0;

				$.each(lines, function(lineNo, line) {
					if (line) {
						var items = line.split(' ');
						lmeCode=parseInt(items[0]);
						if(lmeCode == codeLME) {
						plotType=items[1];
						var thisData=[];
						if (plotType=='data') {
							for (var ii=2; ii<items.length; ii++) {
							thisData.push([1957+parseInt(ii)-2, parseFloat(items[ii]) ] );
						} //for
						} else {
						thisData.push( [1957+parseFloat(items[2]), parseFloat(items[3])] );
						thisData.push( [1957+parseFloat(items[4]), parseFloat(items[5])] );
					}
					var showInLegend=true;
					var visible=true;
					lmeID='LME_'+lmeCode;
					if (plotType=='data') {
						thisSeries={name:'LME #'+items[0], id:lmeID, lineWidth:0.5, lineColor: lineColors[plotCounter], marker:{lineColor:'#FFFFFF', lineWidth:1.5, fillColor: lineColors[plotCounter],radius:3, symbol:'circle'}, data:thisData,zIndex:101};
						plotLME[lmeID]=iplot;
						iplot = iplot+1;
					} else {
						thisSeries={name:'LME #'+items[0]+'(trend)', dashStyle: 'longdash', lineColor: lineColors[plotCounter], linkedTo:lmeID, data:thisData, marker:{enabled:false}};
					}
					options.series.push( thisSeries );
					} //if lmecode
				} // if (line)
			}) //each
			chart = new Highcharts.Chart(options);
		
		

			}); //get
		} //end function
		doAddPlot(thisLMECode);


	    //add the jquery search
	    
		


			$('#addPlot')
			.click(function(){
				$('#resetPlot').attr('disabled', false);
				$('#tags').prop('value','');
				$('#tags').focus();
				doAddPlot($(this).attr('rel'));
				if(plotCounter == 5) {
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
					$(this).attr('disabled', true);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					options.series = [];
					doAddPlot(thisLMECode);
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
					$('#addPlot').attr('disabled', true);
				});
			
			$( "#tags" ).css('color', '#c0c0c0');

			$( "#tags" )
			
			.click(function(){
				if(this.value == comboText){
					this.value = "";
					$( "#tags" ).css('color', '#000000');
				}
			})
			.blur(function(){
				if(this.value ==""){
					$( "#tags" ).css('color', '#c0c0c0');
					$('#addPlot').attr('disabled', true);
					this.value = comboText;
				}
			})
			
			.autocomplete({
              source: availableTags,
			  open: function(e, ui) {
                    var list = '';
                    var results = $('ul.ui-autocomplete.ui-widget-content a');
                    results.each(function() {
                        list += $(this).html() + '<br />';
                    });
                    $('#results').html(list);
                },
              select: function(e, ui) {
                  var lmename = ui.item.value.split(' ');
                  var lmecode = lmename.shift();
                  $('#addPlot').attr('rel', lmecode);
				  $('#addPlot').attr('disabled',false);
				  
				  
				addedLMECode=lmecode;
				}
			});
	    
	});
    </script>
  </head>
  
  <body>


      <div class="ui-widget" style="margin:auto auto;width:500px">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important"></div>
	<input id="addPlot" type="button" value="add LME" disabled="disabled" style="margin-left:8px"></input>
	<input id="resetPlot" type="button" value="Reset plot" disabled="disabled"></input>
      </div>


    <div id="container" style="min-width:310px; max-width:600px; height:400px; margin:auto auto;"> </div>

	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
  </body>
</html>
