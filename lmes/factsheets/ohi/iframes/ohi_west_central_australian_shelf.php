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
		width:460px;
		margin:auto;
		padding-left:51px;
		position: relative;
		top:-5px;
	  }
	  #subLegend td{
		width:153px;
	  }
	  #legendRanges{
		width: 600px;
		margin:auto auto;
		margin-top:20px;
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
	tbody .l0{
		background: #CBCCCB;
	}
	tbody .l1{
		background: #5FBADD;
	}
	.l2{
		background: #78BB4B;
	}
	.l3{
		background: #E4E344;
	}
	.l4{
		background: #EE9F42;
	}
	.l5{
		background: #D8232A;
	}
    </style>
    <script type="text/javascript">
      $(document).ready(function() {
  
         var thisLMECode = "44";
        var addedLMECode = -1;
		var plotCounter = -1;
		var outdata = '../'+"data"+'/';
		var title = "Ocean Health Index";
		var legend = "tons/year";
		var iFr = false;
		var maxAllowedLMEs = 3;
		 var availableTags=[ "01 East Bering Sea", "02 Gulf Of Alaska", "03 California Current", "04 Gulf Of California", "05 Gulf Of Mexico", "06 Southeast U.S. Continental Shelf", "07 Northeast U.S. Continental Shelf", "08 Scotian Shelf", "09 Labrador Newfoundland", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "17 North Brazil Shelf", "18 Canadian Eastern Arctic West Greenland", "19 Greenland Sea", "20 Barents Sea", "21 Norwegian Sea", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "33 Red Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "39 North Australian Shelf", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "45 Northwest Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "50 Sea Of Japan", "51 Oyashio Current", "52 Sea Of Okhotsk", "53 West Bering Sea", "54 Northern Bering Chukchi Seas", "55 Beaufort Sea", "56 East Siberian Sea", "57 Laptev Sea", "58 Kara Sea", "59 Iceland Shelf And Sea", "60 Faroe Plateau", "62 Black Sea", "63 Hudson Bay Complex", "64 Central Arctic", "65 Aleutian Islands", "66 Canadian High Arctic North Greenland" ];
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
		var sourceURL = "http://onesharedocean.org/data#388";
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
             chart: {polar:true, renderTo: 'container', type: 'line', zoomType:'x',
                     resetZoomButton:{relativeTo:'chart', position: {align:'right', verticalAlign: 'bottom', x:-10, y:-68}
                    }
             },
			legend:{align:'center', layout:'horizontal', width:460, itemStyle:{'font-weight': 'normal'}, itemWidth:153, x:20},
             title: { text: title+' (West Central Australian Shelf)', x: 0, useHTML: true, align: 'center', style: {font: '14px Verdana, sans-serif', color: '#000000'} },
             pane: {size:'80%'},
			 xAxis: { categories:[
				'Food provision',
				'Artisanal fishing opportunity',
				'Natural products',
				'Carbon storage',
				'Coastal protection',
				'Tourism & recreation', 
				'Coastal livelihoods & economies',
				'Sense of place', 
				'Clean water', 
				'Biodiversity'
				], tickmarkPlacement: 'on', lineWidth: 0},
             yAxis: { gridLineInterpolation: 'polygon', lineWidth: 0, min: 0, max:100, type: 'linear',minRange:25, tickInterval:25},
			 series: [],
             //plotOptions:{series:{animation:false}},
<?php if((!$forPrint) && (!$forExport)){ ?>
			 exporting: {buttons: {contextButton:{symbol: 'url(/sites/all/themes/oceanskeleton/images/download_24px.png)', _titleKey:''}}/*, chartOptions: {title: { text: ''}}*/},
<?php } else { ?>
			exporting: {buttons: {contextButton: false}},
<?php } ?>
			 tooltip:{valueDecimals: 2, formatter: function() {
				LMECode = this.series.legendItem.textStr.substring(this.series.legendItem.textStr.indexOf(' ')+1);
				var subGoals = '';
				for (var i=0;i < tempSeriesHolder.length; i++){
					if(tempSeriesHolder[i].lmeCode === parseInt(LMECode)){
						switch (this.point.category){
							case 'Food provision':
								subGoals = '<br/><i>subgoals</i><br/>  - FIS: <b>'+tempSeriesHolder[i].fis+'</b><br/>  - MAR: <b>'+tempSeriesHolder[i].mar+'</b>';
							break;
							case 'Coastal livelihoods & economies':
								subGoals = '<br/><i>subgoals</i><br/>  - LIV: <b>'+tempSeriesHolder[i].liv+'</b><br/>  - ECO: <b>'+tempSeriesHolder[i].eco+'</b>';
							break;
							case'Sense of place':
								subGoals = '<br/><i>subgoals</i><br/>  - ICO: <b>'+tempSeriesHolder[i].ico+'</b><br/>  - LSP: <b>'+tempSeriesHolder[i].lsp+'</b>';
							break;
							case 'Biodiversity':
								subGoals = '<br/><i>subgoals</i><br/>  - HAB: <b>'+tempSeriesHolder[i].hab+'</b><br/>  - SPP: <b>'+tempSeriesHolder[i].spp+'</b>';
							break;
						}
						
						break;
					}
				}
				
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
				return '<b>LME '+tt+'</b><br/>'+this.point.category+' <b>'+this.point.y+'</b>'+subGoals;
				}
             
			 }};

  
  plotCounter++;
  
  
  
  
	var series={name:'LME '+LMECode+'', data:[], pointPlacement: 'on',lineWidth:1.5, color: lineColors[plotCounter], zIndex:2, marker:{lineColor: '#ffffff', lineWidth:1.5, fillColor: lineColors[plotCounter], symbol:'circle'}};
  
  
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
					index: parseFloat(items[2]),
					mar: parseFloat(items[3]),
					fp: parseFloat(items[4]),
					fis: parseFloat(items[5]),
					ao: parseFloat(items[6]),
					np: parseFloat(items[7]),
					cp: parseFloat(items[8]),
					cs: parseFloat(items[9]),
					eco: parseFloat(items[10]),
					le: parseFloat(items[11]),
					liv: parseFloat(items[12]),
					tr: parseFloat(items[13]),
					lsp: parseFloat(items[14]),
					sp: parseFloat(items[15]),
					ico: parseFloat(items[16]),
					cw: parseFloat(items[17]),
					spp: parseFloat(items[18]),
					bd: parseFloat(items[19]),
					hab: parseFloat(items[20])
					
				};
				series.data.push(lme.fp, lme.ao, lme.np, lme.cs, lme.cp, lme.tr, lme.le, lme.sp, lme.cw, lme.bd );
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
					jQuery('#sL'+plotCounter).html('OHI: <span class="'+color+'" style="disdplay:block; width:100px; padding:3px 10px">'+lme.index+'</span>');
				}
				
			}
		}
	 });
	
	
	
  if (plotCounter == 0) {
	
	options.series.push(series);
	
	chart = new Highcharts.Chart(options);
  } else {
	
	chart.addSeries(series);
	
	}
 
 });
  
	function getColor(item){
		item = parseFloat(item);
		if(item >= 74.7){
			item = 'l1';
		} else if (item > 71.6 && item <= 74.7){
			item = 'l2';
		} else if (item > 68.7 && item <= 71.6){
			item = 'l3';
		} else if (item > 66.7 && item <= 68.7){
			item = 'l4';
		} else if (item < 66.7){
			item = 'l5';
		} else { //if (item == 'Insufficient data' || item == 'no data'){
			item = 'l0';
		}
		return item;
	}
	
	
 } //end genChart
 var chart = false;
 
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
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					jQuery('#sL1').html('');
					jQuery('#sL2').html('');
					genChart(thisLMECode);
					
					//self.history.go(0);
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
				});
			
			
			var comboText = "Type LME code or name";
			var maxComboText = 'Maximum number of datasets reached';
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
	<div id="subLegend">
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td id="sL0"></td>
				<td id="sL1"></td>
				<td id="sL2"></td>
			</tr>
		</table>
	</div>
	<div id="legendRanges">
			<ul>
				<li><span class="legendText">OHI Risk Levels: </span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very high</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">High</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Medium</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Low</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very low</span></li>
			</ul>
		</div>
		<br />
<?php if(!$forPrint){ ?>		
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
<?php } ?>    
    </body>
</html>
