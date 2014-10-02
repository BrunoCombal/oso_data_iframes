<?php
$templateCache = true;
$sourceCache = true;
if($templateCache == true){
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LMES Catch Value</title>
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
	  #viewData {
		font-family: Verdana;
		font-size: 10px;
		cursor: pointer;
	  }
      .ui-widget{font-size:12px;}
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
  
         var thisLMECode = "44";
        var addedLMECode = -1;
		var plotCounter = -1;
		var outdata = '../'+"data"+'/';
		var title = "Catch Value";
		var legend = "USD/year";
		var iFr = false;
		var maxAllowedLMEs = 6;
		
		Highcharts.SVGRenderer.prototype.symbols.cross = function (x, y, w, h) {
        return ['M', x, y, 'L', x + w, y + h, 'M', x + w, y, 'L', x, y + h, 'z'];
    };
    if (Highcharts.VMLRenderer) {
        Highcharts.VMLRenderer.prototype.symbols.cross = Highcharts.SVGRenderer.prototype.symbols.cross;
    }
	
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
		var sourceURL = "http://onesharedocean.org/?q=data#246";
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
				iFrame.style.height = '530px';
			}
		}
	}
		

		function genChart(LMECode) {
		
		 var options = {
             credits:{enabled:false},
             chart: {renderTo: 'container', type: 'line', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-75},
                     theme:{fill: 'white', stroke:'red', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                    }
             },
			legend:{align:'center', layout:'horizontal', width:460, itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
             title: { text: title+' (West Central Australian Shelf)', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             xAxis: { type: 'datetime', dateTimeLabelFormats: { year: '%Y'}, title:{enabled:true, text:'Years'}},
             yAxis: { title: { text: legend , useHTML:true}, floor:0 },
             series: [],
             plotOptions:{series:{animation:false}},
			 tooltip:{valueDecimals: 2, useHTML:true, formatter: function() {
				var start = this.series.legendItem.textStr.indexOf('(')+1;
				var end = this.series.legendItem.textStr.indexOf(')');
				if(LMECode.toString().length == 1){LMECode = "0"+LMECode;}
				if(end == -1) {
					var tt = LMECode+" ";
				} else {
					var tt = this.series.legendItem.textStr.substring(start, end);
				}
				jQuery.each(availableTags, function(index, value){
					if(value.indexOf(tt) != -1){
						tt = value;
					}
				});
				return '<b>LME '+tt+'</b><br/>' + Highcharts.dateFormat('%Y', new Date(this.x)) +': '+numberWithCommas(this.y.toFixed(0))+' '+legend;
				}
             }};

  
  plotCounter++;
  
  if(plotCounter == 0) {

  
	var usd_series={name:'Catch value', data:[], lineWidth:1.5, color: lineColors[plotCounter], zIndex:2, marker:{lineColor: '#ffffff', lineWidth:1.5, fillColor: lineColors[plotCounter], symbol:'circle'}};
  
  
  } else {
  
  
	var usd_series={name:'Catch value ('+LMECode+')', data:[], lineWidth:1.5, color: lineColors[plotCounter], zIndex:2, marker:{lineColor: '#ffffff', lineWidth:1.5, fillColor: lineColors[plotCounter], symbol:'circle'}};
  
  }
  //Yeat and Month mean
  if(LMECode.toString().length == 1){LMECode = "0"+LMECode;}
  var rand = Math.floor(Math.random()*999999999);
  $.get(outdata+LMECode+'_data.csv'<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
 
     var lines = data.split('\n');
	 var plotLME={};
             iplot=0;

			 
     $.each(lines, function(lineNo, line) {
		if (line) { // ignore empty line (else lines are not drawn)
          var items = line.split(',');
			
				usd_series.data.push( [ Date.UTC(parseInt(items[1]), 06, 15),  parseFloat(items[3]) ] );
			
       }
	 });
	
	
	
  if (plotCounter == 0) {
	options.series.push(usd_series);
	
	chart = new Highcharts.Chart(options);
  } else {
	if(iFr == false){
		chart.addSeries(usd_series);
	} else {
		chart.addSeries(usd_series);
	}
}
 
  });
  
	
 }
 var chart = false;
 //end f
 //Init Chart
 genChart(thisLMECode, true);
 
	    //add the jquery search
	    $(function() {
		


			$('#addPlot')
			.click(function(){
				$('#resetPlot').attr('disabled', false);
				$('#tags').prop('value', '');
				$('#tags').focus();
				addedLMECode = $('#addPlot').attr('rel');
				genChart(addedLMECode);
				
				if (plotCounter == maxAllowedLMEs-1) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					genChart(thisLMECode);
					
					//self.history.go(0);
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
				});
			
			
			$( "#tags" ).css('color', '#c0c0c0');
/* END */
			$( "#tags" )
/* ADDED */			
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
/* END */	.autocomplete({
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
                  $('#addPlot').attr('rel',lmecode);
				  //document.getElementById('addPlot').innerHTML=lmecode;
/* ADDED */				  
				  document.getElementById('addPlot').disabled = false;
				  
/* END */				  
	          addedLMECode=lmecode;
              }
            });
	    });

		
		
      }); //get
   
  </script>

</head>
  <body>
<!-- ADDED -->
      <div class="ui-widget" style="margin:auto; width:500px">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
	<input id="addPlot" type="button" value="add LME" disabled="disabled"></input>
	<input id="resetPlot" type="button" value="Reset plot" disabled="disabled"></input>
      </div>
<!-- END -->
  
    <div id="container" style="min-width:310px; max-width:600px; height:400px; margin:0 auto"></div>
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
    
    </body>
</html>
