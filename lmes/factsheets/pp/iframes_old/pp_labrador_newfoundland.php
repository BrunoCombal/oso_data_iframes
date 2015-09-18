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
	  
	  #subLegend{
		font-family: Verdana;
		font-size: 12px;
		width:450px;
		margin:5px auto 20px auto;
		/*padding-left:51px;*/
		position: relative;
		top:-5px;
	  }
	  #subLegend ul{
		padding:0;
		nargin:0;
	}
	  #subLegend li{
		list-style-type:none;
		margin-right:50px;
		float:left;
	  }
	  #subLegend div{
		display:inline;
	  }
	  #legendRanges{
		width: 600px;
		clear:both;
		margin:auto auto;
		position:relative;
		top:10px;
	}
	#legendRanges ul{
		margin: 0;
		padding: 0;
		list-style-type: none;
		text-align: left;
	}
	#legendRanges ul li{
		float:left;
		padding-left:20px;
		font: 12px Verdana;
	}
	.legendText{
		position: relative;
		top: 4px;
	}
	#legendRanges ul li {
		/*width:160px;*/
	}
	#legendRanges ul li div{
		float:left;
		margin-right:10px;
	}
	#legendRanges ul li div span{
		position:relative;
		top:5px;
	}
	.score{
		border-radius:50%; width:20px; height:20px; padding:0px; background-color:#CBCCCB; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 20px Arial, sans-serif;
	}
	.l0{
		background-color: #CBCCCB;
	}
	.l1{
		background-color: #5FBADD;
	}
	.l2{
		background-color: #78BB4B;
	}
	.l3{
		background-color: #E4E344;
	}
	.l4{
		background-color: #EE9F42;
	}
	.l5{
		background-color: #D8232A;
	}
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
  
         var thisLMECode = "09";
        var addedLMECode = -1;
		var plotCounter = -1;
		
		var outdata = '../'+"data"+'/';		
		 var availableTags=[ "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland" ];
		
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
             credits:{enabled:false},
             chart: {renderTo: 'container', type: 'line', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-75},
                     theme:{fill: 'white', stroke:'red', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                    }
             },
			legend:{align:'center', layout:'horizontal', width:480, itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
             title: { text: 'Primary Productivity (Labrador Newfoundland)', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             xAxis: { type: 'datetime', dateTimeLabelFormats: { year: '%Y'}, title:{enabled:true, text:'Years'}},
             yAxis: { title: { text: 'Primary Productivity (g.C.m<sup>-2</sup>year<sup>-1</sup>)' , useHTML:true}, floor:0 },
             series: [],
             plotOptions:{series:{animation:false}},
<?php if((!$forPrint) && (!$forExport)){ ?>
			 exporting: {buttons: {contextButton:{symbol: 'url(/sites/all/themes/oceanskeleton/images/download_24px.png)', _titleKey:''}}/*, chartOptions: {title: { text: ''}}*/},
<?php } else { ?>
			exporting: {buttons: {contextButton: false}},
<?php } ?>
			 tooltip:{valueDecimals: 2, useHTML: true, formatter: function() {
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
				if((this.point.series.name.length == 7) || (this.point.series.name.length == 12)){
					return '<b>LME '+tt+'<b><br/>'+this.point.series.name + '<br/>' + Highcharts.dateFormat('%b - %Y', new Date(this.x)) +': '+this.y.toFixed(2)+' g.C.m<sup>-2</sup>year<sup>-1</sup>';
				} else {
					return '<b>LME '+tt+'</b><br/>'+this.point.series.name + '<br/>' + Highcharts.dateFormat('%Y', new Date(this.x)) +': '+this.y.toFixed(2)+' g.C.m<sup>-2</sup>year<sup>-1</sup>';
				}
			 }
             }};

  
  
  
  
  plotCounter++;
  
  if(plotCounter==0) {
  
  var yearavg_series={name:'Primary productivity', data:[], lineWidth:1.5, color: lineColors[plotCounter], zIndex:2, marker:{lineColor: '#ffffff', lineWidth:1.5, fillColor: lineColors[plotCounter], symbol:'circle'}};
  var longterm_series={name:'Long Term Average', data:[], lineWidth:2, dashStyle: 'Dot',  color: lineColors[plotCounter], zIndex:1, marker:{enabled:false}};
  var trend_series={name:'Trend', data:[], lineWidth:2,  dashStyle: 'longdash', color: lineColors[plotCounter], zIndex:2, marker:{enabled:false}};
  
  } else {
  
  var yearavg_series={name:'Primary productivity ('+LMECode+')', data:[], lineWidth:1.5, color: lineColors[plotCounter], zIndex:2, marker:{lineColor: '#ffffff', lineWidth:1.5, fillColor: lineColors[plotCounter], symbol:'circle'}};
  var longterm_series={name:'Long Term Average ('+LMECode+')', data:[], lineWidth:2, dashStyle: 'Dot',  color: lineColors[plotCounter], zIndex:1, marker:{enabled:false}};
  var trend_series={name:'Trend ('+LMECode+')', data:[], lineWidth:2,  dashStyle: 'longdash', color: lineColors[plotCounter], zIndex:2, marker:{enabled:false}};
  
  }
  
  var rand = Math.floor(Math.random()*999999999);
  $.get(outdata+LMECode+'_data.csv'<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
 
 
     var lines = data.split('\n');
	 var plotLME={};
             iplot=0;
	 $.each(lines, function(lineNo, line) {
		if (line) { // ignore empty line (else lines are not drawn)
          var items = line.split(' ');
  
          //if (lineNo > 0) {
			switch (items[0]){
			case "TREND":
				if(!isNaN(parseFloat(items[1]))){
					trend_series.data.push( [ Date.UTC(1998, 5, 15), parseFloat(items[1]) ] );
					trend_series.data.push( [ Date.UTC(2013, 5, 15), parseFloat(items[2]) ] );
				}
				break;
			case "LTA":
				if(!isNaN(parseFloat(items[1]))){
					longterm_series.data.push( [ Date.UTC(1998, 5, 15), parseFloat(items[1]) ] );
					longterm_series.data.push( [ Date.UTC(2013, 5, 15), parseFloat(items[1]) ] );
				}
				break;
			case "YA":
				if(!isNaN(parseFloat(items[2]))){
					yearavg_series.data.push( [ Date.UTC(parseInt(items[1]), 5, 15),  parseFloat(items[2]) ] );
				}
				break;
				case 'G':
					var color = getColor(items[1]);
					jQuery('#sL'+plotCounter).html('LME '+LMECode+': <div class="score '+color+'">&nbsp;&nbsp;&nbsp;&nbsp;</div>');
				break;
			}
		//}
       }
	 });
	
  if (plotCounter == 0) {
    options.series.push(yearavg_series);
	//options.series.push(trend_series);
	options.series.push(longterm_series);
	if(chart != false) {
		chart.destroy();
	}
	chart = new Highcharts.Chart(options);
  } else {
	
	chart.addSeries(yearavg_series);
	//chart.addSeries(trend_series);
	chart.addSeries(longterm_series);
	
	}
  
	});
	
	function getColor(item){
		item = parseFloat(item);
		if(item == 5){
			item = 'l5';
		} else if (item == 4){
			item = 'l4';
		} else if (item == 3){
			item = 'l3';
		} else if (item == 2){
			item = 'l2';
		} else if (item == 1){
			item = 'l1';
		} else { //if (item == 'Insufficient data' || item == 'no data'){
			item = 'l0';
		}
		return item;
	}
	
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
				
				if (plotCounter == 2) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					jQuery('#sL1').html('');
					jQuery('#sL2').html('');
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
	<div id="subLegend" style="clear:both">
		<ul>
			<li id="sL0"></li>
			<li id="sL1"></li>
			<li id="sL2"></li>
		</ul>
	</div>
	<div id="legendRanges">
			<ul>
				<li><span class="legendText">Risk level: </span></li>
				<li><div class="score l5"></div> <span class="legendText">Very high</span></li>
				<li><div class="score l4"></span></div> <span class="legendText">High</span></li>
				<li><div class="score l3"></div> <span class="legendText">Menium</span></li>
				<li><div class="score l2"></div> <span class="legendText">Low</span></li>
				<li><div class="score l1"></div> <span class="legendText">Very low</span></li>
			</ul>
		</div>
		<br /><br />
<?php if(!$forPrint){ ?>			    
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
<?php } ?>    
	</body>
</html>
