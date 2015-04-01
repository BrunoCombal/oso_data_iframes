<?php
$templateCache = true;
$sourceCache = true;
if($templateCache == true){
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
}
$forPrint = false;
if($_GET['forPrint']){
	$forPrint = true;
}
$forExport = false;
if($_GET['forExport']){
	$forExport = true;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>LMES CHLA</title>
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
  
         var thisLMECode = 11;
        var addedLMECode = -1;
		var plotCounter = -1;
		
		var outdata = '../data/';		
		 var availableTags=[ "00 ", "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland" ];
		
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
		var sourceURL = "http://onesharedocean.org/data#189";
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
				iFrame.style.height = '550px';
			}
		}
	}
		function genChart(LMECode) {
		
		 var options = {
            chart:{renderTo:'container', zoomType:'x', resetZoomButton: {relativeTo: 'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-50}, 
            theme:{fill: 'white', stroke:'silver', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                  }
			},
            legend:{align:'center', layout:'horizontal', width:480, itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
            credits:{enabled:false},
            title:{text:'SST ('+'Pacific Central American Coastal'+')'},
            xAxis:{title: {text:'Year'},labels:{formatter: function() {return this.value }}
                  },
            yAxis:{title: { text: 'Temperature (°C)', floor:0},
                  plotLines: [{value: 0,width: 1, color: '#808080' }]
                  },
            tooltip:{valueSuffix: '°C', useHTML:true, valueDecimals: 2,
                     formatter: function() {
					 
					 var tt = this.series.legendItem.textStr.substring(5)+" ";
					 console.log('"'+tt+'"');
					 if(tt == ' '){tt = LMECode;}
					 console.log(tt);
				jQuery.each(availableTags, function(index, value){
					if(value.indexOf(tt) != -1){
						tt = value;
					}
				});
					 return '<b>LME '+tt+'</b><br/>'+'Temperature' + '<br/>' + (this.x)+': '+this.y.toFixed(2)+' °C';}
                    },
<?php if((!$forPrint) && (!$forExport)){ ?>
			 exporting: {buttons: {contextButton:{symbol: 'url(/sites/all/themes/oceanskeleton/images/download_24px.png)', _titleKey:''}}/*, chartOptions: {title: { text: ''}}*/},
<?php } else { ?>
			exporting: {buttons: {contextButton: false}},
<?php } ?>
			plotOptions:{series:{animation:false}},
            series: []
            };
  
  
  
  
  plotCounter++;
  
  if(plotCounter==0) {
  
  var series1={name:'SST', lineWidth:0.5, lineColor: lineColors[plotCounter], marker:{lineColor:'#FFFFFF', lineWidth:1.5, fillColor: lineColors[plotCounter],radius:3, symbol:'circle'}, data:[],zIndex:101};
  var series2={name:'trend)', dashStyle: 'longdash', lineColor: lineColors[plotCounter], data:[], marker:{enabled:false}};
  
  } else {
  
  var series1={name:'SST ('+LMECode+')', lineWidth:0.5, lineColor: lineColors[plotCounter], marker:{lineColor:'#FFFFFF', lineWidth:1.5, fillColor: lineColors[plotCounter],radius:3, symbol:'circle'}, data:[],zIndex:101};
  var series2={name:'trend ('+LMECode+')', dashStyle: 'longdash', lineColor: lineColors[plotCounter], data:[], marker:{enabled:false}};
  
  }
  
  var rand = Math.floor(Math.random()*999999999);
  $.get(outdata+'sst_data_trend.txt'<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
 
 
     var lines = data.split('\n');
	 var plotLME={};
             iplot=0;
	 $.each(lines, function(lineNo, line) {
		if (line) { // ignore empty line (else lines are not drawn)
          var items = line.split(' ');
  
          //if (lineNo > 0) {
			if(items[0] == parseInt(LMECode)){
				if (items[1] ==  'data'){
					for (var ii=2; ii<items.length; ii++) {
						series1.data.push([1957+parseInt(ii)-2, parseFloat(items[ii])]);
					}
				} else {
					series2.data.push([1957+parseFloat(items[2]), parseFloat(items[3])]);
					series2.data.push([1957+parseFloat(items[4]), parseFloat(items[5])]);
				}
			}
		//}
       }
	 });
	
  if (plotCounter == 0) {
    options.series.push(series1);
	options.series.push(series2);
	if(chart != false) {
		chart.destroy();
	}
	chart = new Highcharts.Chart(options);
  } else {
	
	chart.addSeries(series1);
	chart.addSeries(series2);
	
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
				
				if (plotCounter == 5) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					genChart(thisLMECode);
					
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
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
                  $('#addPlot').attr('rel',lmecode);
				  //document.getElementById('addPlot').innerHTML=lmecode;
			  
				  document.getElementById('addPlot').disabled = false;
				  
				  
	          addedLMECode=lmecode;
              }
            });
	    });

      }); //get
  </script>
</head>
  <body>
<?php if((!$forPrint) && (!$forExport)){ ?>
      <div class="ui-widget" style="margin:auto; width:500px">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
	<input id="addPlot" type="button" value="add LME" disabled="disabled"></input>
	<input id="resetPlot" type="button" value="Reset plot" disabled="disabled"></input>
      </div>
<?php } ?>
  
    <div id="container" style="min-width:310px; max-width:600px; height:400px; margin:0 auto"></div>
	
<?php if(!$forPrint){ ?>			    
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
<?php } ?>    
	</body>
</html>