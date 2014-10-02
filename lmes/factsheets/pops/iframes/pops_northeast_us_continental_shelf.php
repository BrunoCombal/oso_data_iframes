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
    <title>LMES ICEP</title>
    <link rel="stylesheet" href="/sites/all/libraries/jquery-ui-1.11.1/jquery-ui.min.css" />
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1/external/jquery/jquery.js"></script>
    <script type="text/javascript" src="/sites/all/libraries/jquery-ui-1.11.1//jquery-ui.min.js"></script>
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
	  #legendRanges{
		width: 600px;
		margin:auto auto;
	}
	#legendRanges ul{
		margin: 0;
		padding: 0;
		list-style-type: none;
		text-align: center;
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
	#legendRanges ul li div{
		float:left;
		margin-right:10px;
	}
	#legendRanges ul li div span{
		position:relative;
		top:5px;
	}
	table{
		/*width:100%;*/
		font: 12px Verdana,sans-serif;
		margin: 20px auto;
		text-align:center;
		vertical-align:middle;
	}
	thead tr{
		height:20px;
	}
	tbody tr{
		height:26px;
	}
	tbody .lmeName{
		text-align: left;
		/*width:315px;*/
	}
	.l1, .l2, .l3, .l4, .l5{
		color: #000000;
		font-weight: bold;
		border:1px #fff solid;
		border-right:10px solid white;
		cursor: help;
		width: 50px;
	}
	.nlocs {
		width: 60px;
	}
	.pcba, .ddta, .hcha{
		width:60px;
		border-left:10px solid white;
	}
	.nlocs, .pcb, .pcba, .pcbr, .ddt, .ddta, .ddtr, .hcb, .hcha, .hchr {
		cursor: help;
	}
	.pcbr, .ddtr, .hchr{
		padding-right:10px;
	}
	.pcb, .ddt, .hch{
		border-bottom: 1px solid black;
		border-right: 10px solid white;
		border-left: 10px solid white;
	}
	.pcba span, .ddta span, .hcha span{
		font-size:9px;
	}
	tbody .l1{
		background: #5FBADD;
	}
	tbody .l2{
		background: #78BB4B;
	}
	tbody .l3{
		background: #E4E344;
	}
	tbody .l4{
		background: #EE9F42;
	}
	tbody .l5{
		background:#D8232A;
	}
	#title{
		text-align:center;
		font: 14px sans-serif;
		margin-bottom:20px;
	}
    </style>
    <script type="text/javascript">
$(document).ready(function() {
	  
	  
  
        var thisLMECode = "07";
        var addedLMECode = -1;
		var plotCounter = -1;
		var categoryPlots = [thisLMECode];
		var outdata = '../'+"data"+'/data.csv';
		var lmesData = [];
		var title = "POPs (Northeast U.S. Continental Shelf)";
		
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
		var sourceURL = "http://onesharedocean.org/?q=data#332";
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
		
		
function genColumn(lmeNumber)
{
  
  
  
	plotCounter++;
  
	var rand = Math.floor(Math.random()*999999999);
	$.get(outdata<?php if($sourceCache == true){?>+'?uid='+rand<?php } ?>, function(data) {
		var lines = data.split('\n');
		 var plotLME={};
				 iplot=0;
		$.each(lines, function(lineNo, line) {
			if (line) { // ignore empty line (else lines are not drawn)
				var items = line.split(',');
				if(items[0] == lmeNumber){
					var lme = {
						lmeNumber: items[0],
						lmeName: items[1],
						nlocs: items[2],
						pcb: items[3],
						pcbRisk: items[4],
						ddt: items[5],
						ddtRisk: items[6],
						hch: items[7],
						hchRisk: items[8]
					};
					//console.log(lme);
					//console.log(items);
					var tr = document.createElement('TR');
					var td1 = document.createElement('TD');
					$(td1).html(lme.lmeNumber+' '+lme.lmeName);
					$(td1).addClass('lmeName');
					var td2 = document.createElement('TD');
					$(td2).html(lme.nlocs);
					$(td2).addClass('nlocs');
					var td3 = document.createElement('TD');
					$(td3).html(lme.pcb);
					$(td3).addClass('pcba');
					var td4 = document.createElement('TD');
					$(td4).html(lme.pcbRisk);
					$(td4).addClass(riskClass(lme.pcbRisk));
					$(td4).addClass('pcnr');
					var td5 = document.createElement('TD');
					$(td5).html(lme.ddt);
					$(td5).addClass('ddta');
					var td6 = document.createElement('TD');
					$(td6).html(lme.ddtRisk);
					$(td6).addClass(riskClass(lme.ddtRisk));
					$(td6).addClass('ddtr');
					var td7 = document.createElement('TD');
					$(td7).html(lme.hch);
					$(td7).addClass('hcha');
					var td8 = document.createElement('TD');
					$(td8).html(lme.hchRisk);
					$(td8).addClass(riskClass(lme.hchRisk));
					$(td8).addClass('hcbr');
					
					$('table').append(tr);
					$(tr).append(td1,td2,td3,td4,td5,td6,td7,td8);
					if($('tbody').find('tr').length > 1){
						var td9 = document.createElement('TD');
						var img = document.createElement('IMG');
						$(img).attr('src', '/iframes/lmes/images/delete_gray.png');
						$(img).addClass('deleteRow');
						$(td9).append(img);
						$(tr).append(td9);
						$(img).click(function(){
							$(this).parents('tr').remove();
							plotCounter--;
							$('#tags').attr('disabled', false);
							$( "#tags" ).css('color', '#c0c0c0');
							$('#tags').prop('value', comboText);
							if(iFrame != null){
								iFrame.style.height = $('#container').height()+200+'px';
							}
						});
						$(img).mouseenter(function(){
							$(this).attr('src', '/iframes/lmes/images/delete_red.png');
						});
						$(img).mouseleave(function(){
							$(this).attr('src', '/iframes/lmes/images/delete_gray.png');
						});
					}else{
						var td5 = document.createElement('TD');
						$(td5).css('width','16.8');
						$(tr).append(td5);
					}
					
					$('.nlocs').mouseover(function(){showLegendTooltip('nlocs')});
					$('.nlocs').mouseout(function(){hideLegendTooltip()});
					$('.pcb').mouseover(function(){showLegendTooltip('pcb')});
					$('.pcb').mouseout(function(){hideLegendTooltip()});
					$('.pcba').mouseover(function(){showLegendTooltip('pcba')});
					$('.pcba').mouseout(function(){hideLegendTooltip()});
					$('.pcbr').mouseover(function(){showLegendTooltip('pcbr')});
					$('.pcbr').mouseout(function(){hideLegendTooltip()});
					$('.ddt').mouseover(function(){showLegendTooltip('ddt')});
					$('.ddt').mouseout(function(){hideLegendTooltip()});
					$('.ddta').mouseover(function(){showLegendTooltip('ddta')});
					$('.ddta').mouseout(function(){hideLegendTooltip()});
					$('.ddtr').mouseover(function(){showLegendTooltip('ddtr')});
					$('.ddtr').mouseout(function(){hideLegendTooltip()});
					$('.hch').mouseover(function(){showLegendTooltip('hch')});
					$('.hch').mouseout(function(){hideLegendTooltip()});
					$('.hcha').mouseover(function(){showLegendTooltip('hcha')});
					$('.hcha').mouseout(function(){hideLegendTooltip()});
					$('.hchr').mouseover(function(){showLegendTooltip('hchr')});
					$('.hchr').mouseout(function(){hideLegendTooltip()});
					
					
					if(iFrame != null){
						iFrame.style.height = $('#container').height()+200+'px';
					}
					
				}
			}
		});
		
	});
	
	
	function riskClass(item){
		switch (parseInt(item)){
			case 1:
				item = 'l1';
				break;
			case 2:
				item = 'l2';
				break;
			case 3:
				item = 'l3';
				break;
			case 4:
				item = 'l4';
				break;
			case 5:
				item = 'l5';
				break;
		}
		return item;
	}
	
 } //end function
 genColumn(thisLMECode);
 
 function showLegendTooltip(item){
	var itemText = '';
	switch (item){
		case 'nlocs':
			itemText = "Number of locations"
			break;
		case 'pcb':
			itemText = "Polychlorinated biphenyls, a man-made chemical used for industrial applications";
			break;
		case 'pcba':
			itemText = 'Averaged PCBs concentration (ng/g)';
			break;
		case 'pcbr':
			itemText = 'Risk category of PCBs';
			break;
		case 'ddt':
			itemText = "Dichlorodiphenyltrichloroethylene and its metabolites, a man-made chemical for agricultural applications.";
			break;
		case 'ddta':
			itemText = 'Averaged DDTs concentration (ng/g)';
			break;
		case 'ddtr':
			itemText = 'Risk category of DDTs';
			break;
		case 'hch':
			itemText = "Hexachlorocyclohexane isomers, a man-made chemical for agricultural applications.";
			break;
		case 'hcha':
			itemText = 'Averaged HCHs concentration (ng/g)';
			break;
		case 'hchr':
			itemText = 'Risk category for HCHs';
			break;
		}
		$('#legendTooltip')
			.html(itemText)
			.css('display', 'block')
			.css('background', '#e2e2e2');
 
 }
 function hideLegendTooltip(){
	$('#legendTooltip').css('display', 'none');
 }
 
 
 
 
	    //add the jquery search
	    $(function() {
		


			$('#addPlot')
			.click(function(){
				$('#resetPlot').attr('disabled', false);
				$('#tags').prop('value', '');
				$('#tags').focus();
				addedLMECode = $('#addPlot').attr('rel');
				genColumn(addedLMECode);
				
				if (plotCounter == 9) {
					$(this).attr('disabled', true);
					$('#tags').attr('disabled', true);
					$('#tags').prop('value', maxComboText);
				}
			});
			$('#resetPlot')
				.click(function(){
					plotCounter = -1;
					$('tbody').remove();
					genColumn(thisLMECode);
					
					$('#tags').attr('disabled', false);
					$('#tags').prop('value','');
					$('#tags').focus();
					$(this).attr('disabled', true);
				});
			
			
			//console.log(availableTags);
			//console.log(maxComboText);
			
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

 var availableTags=[ "02 Gulf Of Alaska", "03 California Current", "05 Gulf Of Mexico", "07 Northeast U.S. Continental Shelf", "10 Insular Pacific Hawaiian", "11 Pacific Central American Coastal", "12 Caribbean Sea", "13 Humboldt Current", "14 Patagonian Shelf", "15 South Brazil Shelf", "16 East Brazil Shelf", "22 North Sea", "23 Baltic Sea", "24 Celtic Biscay Shelf", "25 Iberian Coastal", "26 Mediterranean Sea", "27 Canary Current", "28 Guinea Current", "29 Benguela Current", "30 Agulhas Current", "31 Somali Coastal Current", "32 Arabian Sea", "34 Bay Of Bengal", "35 Gulf Of Thailand", "36 South China Sea", "37 Sulu Celebes Sea", "38 Indonesian Sea", "40 Northeast Australian Shelf", "41 East Central Australian Shelf", "42 Southeast Australian Shelf", "43 South West Australian Shelf", "44 West Central Australian Shelf", "46 New Zealand Shelf", "47 East China Sea", "48 Yellow Sea", "49 Kuroshio Current", "62 Black Sea" ];

$('#title').html(title);
		
      }); //get
   
  </script>

</head>
<body>
	<div id="title"></div>
	<div class="ui-widget" style="margin:auto; width:500px">
		<input id="tags" class="autocomplete" style="width:320px; z-index:999 !important; float:left;" value="Type LME code or name"/>
		<div id="#results" class="ui-front autocomplete" style="z-index:999 !important" ></div>
		<input id="addPlot" type="button" value="add LME" disabled="disabled"></input>
		<input id="resetPlot" type="button" value="Reset" disabled="disabled"></input>
	</div>
	<div id="container" style="width:600px; margin:0 auto">
		<table cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<td ></td>
					<td></td>
					<td class="pcb" colspan="2">
						PCBs
					</td>
					<td class="ddt" colspan="2">
						DDTs
					</td>
					<td class="hch" colspan="2">
						HCHs
					</td>
					<td></td>
				</tr>
				<tr>
					<td>LME</td>
					<td class="nlocs">Locations</td>
					<td class="pcba">Avg.<span> (ng/g)</span></td>
					<td class="pcbr">Risk</td>
					<td class="ddta">Avg.<span> (ng/g)</span></td>
					<td class="ddtr">Risk</td>
					<td class="hcha">Avg.<span> (ng/g)</span></td>
					<td class="hchr">Risk</td>
				</tr>
			</thead>
		</table>
		
		<div id="legendRanges">
			<ul>
				<li><span class="legendText">Risk Level:&nbsp;&nbsp;&nbsp;</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#5FBADD; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very High</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#78BB4B; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">High</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#E4E344; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Medium</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#EE9F42; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Low</span></li>
				<li><div style="border-radius:50%; width:20px; height:20px; padding:0px; background:#D8232A; border: 1px solid #CBCCCB; color:#FFFFFF; text-align:center; font: 10px Arial, sans-serif;"><span style="margin: auto auto;"></span></div> <span class="legendText">Very Low</span></li>
			</ul>
		</div>
		<div style="clear:both; font: 12px Verdana, sans-serif; padding:5px; position:absolute" id="legendTooltip"></div>
	</div>
	<br/><br/>
	<div style="text-align:right"><span id="viewData">Get data and metainformation</span></div>
</body>
</html>
