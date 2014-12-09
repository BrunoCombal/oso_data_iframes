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
    <title>LMES Annual Catch</title>
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
		width:550px;
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
		margin-right:10px;
		float:left;
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
  
         var thisLMECode = "61";
        var addedLMECode = -1;
		var plotCounter = -1;
		var outdata = '../'+"data"+'/';
		var title = "Cumulative Human Impact (Antarctica)";
		var legend = "tons/year";
		var iFr = false;
		var maxAllowedLMEs = 4;
		 var availableTags=[ "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "61 Antarctica", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland", "99 Pacific Warm Pool" ];
		var tempSeriesHolder = [];
		
		
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
		var sourceURL = "http://onesharedocean.org/data#394";
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
				iFrame.style.height = '850px';
			}
		}
	}
	
	
		

		function genChart(LMECode) {
		
		 jQuery('#title').html(title);
		 var options1 = {
             credits:{enabled:false},
             chart: {renderTo: 'container1', type: 'column', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-75},
                     theme:{fill: 'white', stroke:'red', r:0, states:{hover:{fill:'#41739D', style:{color:'white'}}}}
                    }
             },
			legend:{align:'center', layout:'horizontal', itemStyle:{'font-weight': 'normal', 'max-width':'125'}, x:20},
             title: { text: 'Climate Change', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             xAxis: { type: 'linear', title:{text:''}, labels:{style:{fontSize:"10px"}}, categories: ["Ocean Acid-ification","SLR","SST","UV"]},
             yAxis: { title: { text: 'CHI' , useHTML:true}, floor:0 },
             series: [],
			 exporting: {buttons: {contextButton:{symbol: 'url(/sites/all/themes/oceanskeleton/images/download_24px.png)', _titleKey:''}}/*, chartOptions: {title: { text: ''}}*/},
			 tooltip:{
				valueDecimals: 2,
				useHTML: true,
				formatter: function() {
					LMECode = this.series.legendItem.textStr.substring(this.series.legendItem.textStr.indexOf(' ')+1);
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
					return '<b>LME '+tt+'</b><br />'+this.point.category+'<br/>'+this.y.toFixed(4);
				}
			}};
			
			var options2 = jQuery.extend(true,{},options1);
			options2.chart.renderTo = 'container2';
			options2.title.text = "Fishing";
			options2.xAxis.categories = ["Artisanal Fishing","Demersal Destructive Fishing","a)", "b)","c)","d)"];
			var options3 = jQuery.extend(true,{},options1);
			options3.chart.renderTo = 'container3';
			options3.title.text = "Ocean Industry";
			options3.xAxis.categories = ["Shipping","Ocean-based pollution","Oil Rigs"];
			var options4 = jQuery.extend(true,{},options1);
			options4.chart.renderTo = "container4";
			options4.title.text = "Land-based";
			options4.xAxis.categories = ["Invasive Species","Inorganic Pollution","Light Pollution","Nutrient Pollution","Organic Pollution","Direct Human Impact"];
			

  
  plotCounter++;
  
  
  
  
	var series1={name:'LME '+LMECode+'', data:[], color: lineColors[plotCounter]};
	var series2=$.extend(true,{},series1);
	var series3=$.extend(true,{},series1);
	var series4=$.extend(true,{},series1);
  
  
  var rand = Math.floor(Math.random()*999999999);
  $.get(outdata+'data.csv'<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
	var lines = data.split('\n');
	$.each(lines, function(lineNo, line) {
		if (line) { // ignore empty line (else lines are not drawn)
			var items = line.split(',');
			if(parseInt(items[0]) == parseInt(LMECode)){
				var lme={
					lmeCode: parseInt(items[0]),
					lmeName: items[1],
					index: parseFloat(items[2]).toFixed(2),
					oa: parseFloat(items[3]),
					slr: parseFloat(items[4]),
					sst: parseFloat(items[5]),
					uv: parseFloat(items[6]),
					af: parseFloat(items[7]),
					ddf: parseFloat(items[8]),
					dnhbf: parseFloat(items[9]),
					dnlbf: parseFloat(items[10]),
					phbf: parseFloat(items[11]),
					plbf: parseFloat(items[12]),
					ship: parseFloat(items[13]),
					obp: parseFloat(items[14]),
					or: parseFloat(items[15]),
					is: parseFloat(items[16]),
					ip: parseFloat(items[17]),
					lp: parseFloat(items[18]),
					np: parseFloat(items[19]),
					op: parseFloat(items[20]),
					dhi: parseFloat(items[21])
					
				};
				series1.data.push(lme.oa, lme.slr, lme.sst, lme.uv);
				series2.data.push(lme.af, lme.ddf, lme.dnhbf, lme.dnlbf, lme.phbf, lme.plbf);
				series3.data.push(lme.ship, lme.obp, lme.or);
				series4.data.push(lme.is, lme.ip, lme.lp, lme.np, lme.op, lme.dhi);
				var flag = false;
				for (var i=0;i < tempSeriesHolder.length; i++){
					if(tempSeriesHolder[i].lmeCode === lme.lmeCode){
						flag = true;
						break;
					}
				}
				if(!flag){
					tempSeriesHolder.push(lme);
					var color = getColor(lme.index);
					jQuery('#sL'+plotCounter).html('LME '+LMECode+' CHI: <span class="'+color+'" style="display:inline; width:100px; padding:3px 10px">'+lme.index+'</span>');
				}
			}
		}
	 });
	
	
	
  if (plotCounter == 0) {
	
	options1.series.push(series1);
	options2.series.push(series2);
	options3.series.push(series3);
	options4.series.push(series4);
	
	chart1 = new Highcharts.Chart(options1);
	chart2 = new Highcharts.Chart(options2);
	chart3 = new Highcharts.Chart(options3);
	chart4 = new Highcharts.Chart(options4);
  } else {
	
	chart1.addSeries(series1);
	chart2.addSeries(series2);
	chart3.addSeries(series3);
	chart4.addSeries(series4);
	
	}
	
 });
  
	function getColor(item){
		item = parseFloat(item);
		if(item >= 4.39){
			item = 'l5';
		} else if (item >= 3.85 && item < 4.39){
			item = 'l4';
		} else if (item >= 3.444 && item < 3.85){
			item = 'l3';
		} else if (item >= 2.95 && item < 3.444){
			item = 'l2';
		} else if (item < 2.95){
			item = 'l1';
		} else { //if (item == 'Insufficient data' || item == 'no data'){
			item = 'l0';
		}
		return item;
	}
	
	
 } //end genChart
 var chart1 = false;
 var chart2 = false;
 var chart3 = false;
 var chart4 = false;
 
 //Init Chart
 if(thisLMECode.toString().length == 1){thisLMECode =  "0"+thisLMECode;}
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
			jQuery('#resetPlot')
				.click(function(){
					plotCounter = -1;
					jQuery('#sL1').html('');
					jQuery('#sL2').html('');
					jQuery('#sL3').html('');
					tempSeriesHolder = [];
					genChart(thisLMECode);
					
					//self.history.go(0);
					jQuery('#tags').attr('disabled', false);
					jQuery('#tags').prop('value','');
					jQuery('#tags').focus();
					jQuery(this).attr('disabled', true);
				});
			
			
			var comboText = "Type LME code or name";
			var maxComboText = 'Maximum number of datasets reached';
			$( "#tags" ).css('color', '#c0c0c0');

			jQuery( "#tags" )
			
			.click(function(){
				if(this.value == comboText){
					this.value = "";
					
					jQuery( "#tags" ).css('color', '#000000');
				}
			})
			.blur(function(){
				if(this.value ==""){
					jQuery( "#tags" ).css('color', '#c0c0c0');
					jQuery('#addPlot').attr('disabled', true);
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
                    jQuery('#results').html(list);
                },
              select: function(e, ui) {
                  var lmename = ui.item.value.split(' ');
                  var lmecode = lmename.shift();
                  jQuery('#addPlot').attr('rel',lmecode);
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
	<div id="title" style="font: 14px Verdana, sans-serif; text-align: center"></div>
<?php if((!$forPrint) && (!$forExport)){ ?>
	<div class="ui-widget" style="margin:auto; width:500px">
	<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
	<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
	<input id="addPlot" type="button" value="add LME" disabled="disabled" />
	<input id="resetPlot" type="button" value="Reset plot" disabled="disabled" />
	</div>
<?php } ?>

  
    <div style="clear:both; width:880px;margin:auto">
		<div id="container1" style="width:440px; height:340px; margin:0 auto;float:left"></div>
		<div id="container2" style="width:440px; height:340px; margin:0 auto;float:left"></div>
		<div id="intermediateLegend" style="font: 10px Lucida sans unicode; color: #606060; clear:both"><div style="width:440px; margin: 0 0 0 auto;">a) Demersal Non-destructive High Bycatch Fishing&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;c) Pelagic High Bycatch Fishing<br/>b) Demersal Non-destructive Low Bycatch Fishing&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;d) Pelagic Low Bycatch Fishing</div></div>
		<div id="container3" style="width:440px; height:340px; margin:0 auto;float:left"></div>
		<div id="container4" style="width:440px; height:340px; margin:0 auto;float:left"></div>
	</div>
	<div id="subLegend" style="clear:both">
		<ul>
			<li id="sL0"></li>
			<li id="sL1"></li>
			<li id="sL2"></li>
			<li id="sL3"></li>
		</ul>
	</div>
	<div id="legendRanges">
			<ul>
				<li><span class="legendText">OHI Risk Levels: </span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very high</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">High</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Menium</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Low</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very low</span></li>
			</ul>
		</div>
		<br /><br />
<?php if(!$forPrint){ ?>		
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
<?php } ?>    
    </body>
</html>
